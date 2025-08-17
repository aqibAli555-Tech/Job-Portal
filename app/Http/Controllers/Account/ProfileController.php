<?php

namespace App\Http\Controllers\Account;

use App\Helpers\EmailCheck;
use App\Helpers\Helper;
use App\Helpers\Localization\Country as CountryLocalization;
use App\Helpers\Localization\Helpers\Country as CountryLocalizationHelper;
use App\Http\Controllers\Auth\Traits\VerificationTrait;
use App\Models\Allsaved_resume;
use App\Models\Applicant;
use App\Models\CompanyPackages;
use App\Models\Availability;
use App\Models\City;
use App\Models\Company;
use App\Models\Country;
use App\Models\EmployeeLogo;
use App\Models\EmployeeSkill;
use App\Models\Experience;
use App\Models\Favoriteresume;
use App\Models\Gender;
use App\Models\Nationality;
use App\Models\OptionalSelectedEmails;
use App\Models\Post;
use App\Models\Resume;
use App\Models\SavedPost;
use App\Models\Scopes\VerifiedScope;
use App\Models\SystemEmails;
use App\Models\Unlock;
use App\Models\User;
use App\Models\UserSkills;
use App\Models\UserType;
use Creativeorange\Gravatar\Facades\Gravatar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Imagick;
use Illuminate\Support\Carbon;
use Torann\LaravelMetaTags\Facades\MetaTag;
use App\Models\ContactCardsRemaining;
use App\Models\PostRemaining;


class ProfileController extends AccountBaseController
{
    use VerificationTrait;

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if (app('impersonate')->isImpersonating()) {
            //  session(['impersonate_back_url' => url()->previous()]);
            if (empty(session::get('impersonate'))) {
                Session::put('impersonate_back_url', url()->previous());
                Session::put('impersonate', 1);
            }

            return redirect('/profile/' . auth()->user()->id);
        }
        if (auth()->user()->user_type_id == 1 && empty(auth()->user()->parent_id)) {
            flash(t("Please change account to child company to view this page."))->error();
            return redirect('/');
        }
        return redirect(url('profile/' . auth()->user()->id));

        $data = [];

        $data['genders'] = Gender::query()->get();
        $data['userTypes'] = UserType::all();

        // Mini Stats
        $data['countPostsVisits'] = DB::table((new Post())->getTable())
            ->select('user_id', DB::raw('SUM(visits) as total_visits'))
            ->where('country_code', config('country.code'))
            ->where('user_id', auth()->user()->id)
            ->groupBy('user_id')
            ->first();
        $data['countPosts'] = Post::currentCountry()
            ->where('user_id', auth()->user()->id)
            ->count();
        $data['countFavoritePosts'] = SavedPost::whereHas('post', function ($query) {
            $query->currentCountry();
        })->where('user_id', auth()->user()->id)
            ->count();

        // Meta Tags
        MetaTag::set('title', t('My account'));
        MetaTag::set('description', t('My account on', ['appName' => config('settings.app.app_name')]));
        $applied_jobs = Applicant::where('user_id', auth()->user()->id)->where('status', '!=', 'not')->get();
        $data['applied_jobs'] = count($applied_jobs);
        $fav_jobs = SavedPost::where('user_id', auth()->user()->id)->get();
        $data['fav_jobs'] = count($fav_jobs);
        $data['resume'] = Resume::where('user_id', auth()->user()->id)->first();
        return appView('account.edit', $data);
    }

    public function profile()
    {
        if (!Helper::check_permission(1)) {
            flash(t("You do not have permission to access this module"))->error();
            return redirect()->back();
        }


        $data = [];
        $title = t('profile');
        view()->share('title', $title);
        $data['userTypes'] = UserType::all();
        $data['userPhoto'] = (!empty(auth()->user()->email)) ? Gravatar::fallback(url('images/user.jpg'))->get(auth()->user()->email) : null;
        $data['cities'] = City::select('id', 'name')->where('country_code', auth()->user()->country_code)->orderBy('name')->get();

        $data['visa_types'] = array(
            array("Visa 18 (Normal/Professional)" => t("Visa 18 (Normal/Professional)")),
            array("Visa 18 (Mubarak AlKabeer/Small Business)" => t("Visa 18 (Mubarak AlKabeer/Small Business)")),
            array("Visa 18 (VIP/Golden)" => t('Visa 18 (VIP/Golden)')),
            array("Visa 18 (Other)" => t('Visa 18 (Other)')),
            array("Visa 22 (Family)" => t('Visa 22 (Family)')),
        );
          
        $data['countries'] = CountryLocalizationHelper::transAll(CountryLocalization::getCountries());
        $data['countries_list'] = Country::orderBy('name')->get();

        if (auth()->user()->user_type_id == 2) {
            $data['employee_skills'] = EmployeeSkill::getAllskill();
            $data['availability'] = Availability::select('id', 'name')->orderBy('id')->where('status', 1)->get();
            $data['countFavoritePosts'] = SavedPost::whereHas('post', function ($query) {
                $query->currentCountry();
            })->where('user_id', auth()->user()->id)
                ->count();
            $data['resume'] = Resume::where('user_id', auth()->user()->id)->first();
        } else {
            $data['countPostsVisits'] = Post::select('posts.user_id', DB::raw('SUM(posts_meta.visits) as total_visits'))
                ->join('posts_meta', 'posts.id', '=', 'posts_meta.post_id')
                ->where('posts.country_code', config('country.code'))
                ->where('posts.user_id', auth()->user()->id)
                ->groupBy('posts.user_id')
                ->first();

            $data['countPosts'] = Post::currentCountry()
                ->where('user_id', auth()->user()->id)
                ->count();
            $data['system_emails'] = SystemEmails::get_all_emails();
            $data['optional_selected_emails'] = OptionalSelectedEmails::get_selected_emails(auth()->user()->id);
        }

        $data['nationality'] = Nationality::pluck('id', 'name');
        $data['experience'] = Experience::all();
        // Meta Tags
        MetaTag::set('title', t('My account'));
        MetaTag::set('description', t('My account on :app_name', ['app_name' => config('settings.app.app_name')]));

        return appView('account.profile', $data);
    }


    public function update_profile(Request $request)
    {
    

        if (!Helper::check_permission(1)) {
            flash(t("You do not have permission to access this module"))->error();
            return redirect()->back();
        }
        if (empty($request->name)) {
            flash(t("Please fill all the required data"))->error();
            $nextUrl = 'account/profile';
            return redirect($nextUrl);
        }

        $file = $request->file('file');
        if (!empty($file)) {
            $image = $request->file('file');
            $extention = $image->getClientOriginalExtension();
            $allowed = array('jpg', 'png', 'jpeg');
            if (!in_array($extention, $allowed)) {

                flash('Image Should be in PNG and JPG.')->error();
                return redirect()->back();
            }
        }

        if (!empty($request->skill_set)) {
            $skills_set = implode(',', $request->skill_set);
        } else {
            $skills_set = [];
        }

        // Check if these fields has changed
        $emailChanged = $request->filled('email') && $request->get('email') != auth()->user()->email;
        $phoneChanged = $request->filled('phone') && $request->get('phone') != auth()->user()->phone;
        $usernameChanged = $request->filled('username') && $request->get('username') != auth()->user()->username;

        // Conditions to Verify User's Email or Phone
        $emailVerificationRequired = config('settings.mail.email_verification') == 1 && $emailChanged;
        $phoneVerificationRequired = config('settings.sms.phone_verification') == 1 && $phoneChanged;

        // Get User
        $user = User::withoutGlobalScopes([VerifiedScope::class])->find(auth()->user()->id);

        // Update User
        $input = $request->only($user->getFillable());
        foreach ($input as $key => $value) {

            if (in_array($key, ['email', 'phone', 'username']) && empty($value)) {
                continue;
            }
            $user->{$key} = $value;
        }
        if ($user->user_type_id == 1) {

            if (!empty($request->input('phone'))) {
                if (strlen($request->input('phone')) < 6) {
                    flash('Please enter valid phone number')->error();
                    return redirect()->back();
                }
            }
        }
        $data_update['country_code'] = $request->country_code;
        $data_update['city'] = $request->get('city');
        $data_update['name'] = $request->get('name');

        if ($user->user_type_id == 2) {
            $data_update['skill_set'] = implode(",", $request->get('skill_set'));
            $data_update['availability'] = $request->get('availability');
            $data_update['visa'] = !empty($request->get('visa')) ? $request->get('visa') : '';
            $data_update['visa_number'] = !empty($request->get('visa_number')) ? $request->get('visa_number') : '';
            $data_update['country_work_visa'] = !empty($request->get('country_work_visa')) ? $request->get('country_work_visa') : '';
            $data_update['nationality'] = !empty($request->get('nationality')) ? $request->get('nationality') : '';
            $data_update['experiences'] = !empty($request->get('experiences')) ? $request->get('experiences') : '';

        } else {
            $data_update['phone'] = $request->get('phone');
        }

        $user_update = User::find(auth()->user()->id)->update($data_update);
        if (!$user_update) {
            flash(t("Unable to save profile data"))->error();
            return redirect()->back();
        }

        $Companydata = Company::where('c_id', auth()->user()->id)->first();
        if (!empty($Companydata)) {

            $Companydata->email = $request->get('email');
            $Companydata->phone = $request->get('phone');
            $Companydata->name = $request->get('name');
            $Companydata->country_code = $request->get('country_code');
            $Companydata->city_id = $request->get('city');
            if (!$Companydata->save()) {
                flash(t("Unable to save company data"))->error();
                return redirect()->back();
            }
        }

        $file = $request->file('file');
        if (!empty($file)) {
            $file_type = $file->getClientOriginalExtension();
            $destinationPath = public_path('/') . 'storage/pictures/kw/' . auth()->user()->id;
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $fileName = 'pictures/kw/' . auth()->user()->id . '/profile_' . time() . "." . $file_type;
            $file->move($destinationPath, $fileName);

            $url = url('public/storage/' . $fileName);
            $unique = 'thumbnail_' . uniqid() . '.jpg';
            Helper::generate_thumbnail($url, auth()->user()->id, $unique);

            $values = array(
                'thumbnail' => 'pictures/kw/' . auth()->user()->id . '/' . $unique,
                'file' => $fileName,
                'is_image_uploaded_on_aws' => 0,
            );
            User::where('id', auth()->user()->id)->update($values);

            if (auth()->user()->user_type_id == 1) {
                $values = array(
                    'logo' => $fileName,
                    'thumbnail' => 'pictures/kw/' . auth()->user()->id . '/' . $unique,
                    'is_image_uploaded_on_aws' => 0,
                );
                Company::where('c_id', auth()->user()->id)->update($values);
            }
            // Using stripos() - case-insensitive search
            $findstring = "default";
            $remoteFileUrl_file = 'storage/' . $user->file;
            $remoteFileUrl_thumbnail = 'storage/' . $user->thumbnail;

            if (stripos($remoteFileUrl_file, $findstring) == false) {
                if (is_file($remoteFileUrl_file) && file_exists($remoteFileUrl_file)) {
                    unlink($remoteFileUrl_file);
                }
            }

            if (stripos($remoteFileUrl_thumbnail, $findstring) == false) {
                if (is_file($remoteFileUrl_thumbnail) && file_exists($remoteFileUrl_thumbnail)) {
                    unlink($remoteFileUrl_thumbnail);
                }
            }

        }

        // Message Notification & Redirection
        flash(t("Your account details has updated successfully"))->success();
        $nextUrl = '/profile/' . $user->id;


        // Send Phone Verification message
        if ($phoneVerificationRequired) {
            // Save the Next URL before verification
            session()->put('itemNextUrl', $nextUrl);
            // Go to Phone Number verification
            $nextUrl = 'verify/user/phone/';
        }


        $name = auth()->user()->name;
        if (auth()->user()->user_type_id == 2) {
            $user_url = admin_url() . '/job-seekers?search=' . auth()->user()->email;
        } else {
            $user_url = admin_url() . '/employer?search=' . auth()->user()->email;
        }

        $companyDescription['user_url'] = url('/profile') . '/' . auth()->user()->id;
        $companyDescription['name'] = $name;
        $description = "A User Name: <b> <a href='$user_url'>$name</a></b> Updated his Profile details <br>";

        $changes = [];

        if (auth()->user()->name != $request->name) {
            $changes[] = "Name : " . $request->name . " <br>";
        }

        if (auth()->user()->availability != $request->availability) {
            $availability = Availability::availability_name_by_id($request->availability);
            $changes[] = "Availability : " . $availability->name . " <br>";
        }

        if (!empty($request->file) && auth()->user()->file != $request->file) {
            $changes[] = "Profile Photo" . " <br>";
        }

        if (auth()->user()->country_code != $request->country_code) {
            $country = Country::where('code', $request->country_code)->first();
            $old_country = Country::where('code', auth()->user()->country_code)->first();
            $changes[] = "Old Country : " . $old_country->name . " <br>";
            $changes[] = "Country : " . $country->name . " <br>";
        }

        if (auth()->user()->city != $request->city) {
            $city = City::where('id', $request->city)->first();
            $old_city = City::where('id', auth()->user()->city)->first();
            $changes[] = "Old City : " . $old_city->name . " <br>";
            $changes[] = "City : " . $city->name . " <br>";
        }
        
        if (auth()->user()->phone != $request->phone) {
            if (!empty($request->phone)) {
                $changes[] = "Phone : " . $request->phone . " <br>";
            }
        }

        if (auth()->user()->skill_set != $skills_set) {
            UserSkills::create(auth()->user()->skill_set,$skills_set);
            $changes[] = "Skills Sets : <strong>" . $skills_set . "</strong> <br>";
            $changes[] = "Skills Sets Old: <strong>" . auth()->user()->skill_set . "</strong> <br>";
        }
        
         if (auth()->user()->visa != $request->visa ) {
            $changes[] = "Visa : <strong>" . $request->visa . "</strong> <br>";
            $changes[] = "Visa Old: <strong>" . auth()->user()->visa . "</strong> <br>";
            if(!empty($request->get('country_work_visa')) && $request->get('country_work_visa') == 'KW'){
                $changes[] = "Visa Number: <strong>" . auth()->user()->visa_number . "</strong> <br>";
            }
            
        }
        if (auth()->user()->country_work_visa != $request->get('country_work_visa')) {
            $country = Country::where('code', $request->country_work_visa)->first();
            $old_country = Country::where('code', auth()->user()->country_work_visa)->first();
            $changes[] = "Old Country Work Visa: " . ($old_country ? $old_country->name : '') . "<br>";
            $changes[] = "Country Work Visa: " . ($country ? $country->name : '') . "<br>";
        }
        
        if (!empty($changes)) {
            $description .= implode(" ", $changes) . "</a>";
            $companyDescription['changes'] = $description;
            Helper::activity_log($description);
            $companyDescription = Helper::companyDescriptionData($changes, 'profile');
            if(!empty($companyDescription)){
                Helper::activity_log($companyDescription,auth()->user()->id);
            }

        }

        return redirect($nextUrl);
    }

    public function updateSettings(Request $request)
    {

        // Get User
        $user = User::find(auth()->user()->id);

        // Update
        if ($request->get('password') != $request->get('password_confirmation')) {
            flash('Confirm password not matched')->error();
            return redirect()->back();
        }
        $user->disable_comments = (int)$request->get('disable_comments');
        if ($request->filled('password')) {
            $user->password = Hash::make($request->get('password'));
        }
        if ($request->filled('accept_terms')) {
            $user->accept_terms = (int)$request->get('accept_terms');
        }
        $user->accept_marketing_offers = (int)$request->get('accept_marketing_offers');
        $user->time_zone = $request->get('time_zone');
        $user->save();

        flash(t("Your account settings has updated successfully"))->success();
        $nextUrl = '/profile/' . $user->id;
        return redirect($nextUrl);
    }

    public function upload_logo(Request $request)
    {
        if ($request->hasFile('logos')) {
            foreach ($request->file('logos') as $key => $file) {
                if ($file->isValid()) {
                    $newFilename = date('YmdHis') . mt_rand() . '.jpg';
                    $file->move('storage/logo/', $newFilename);
                    $image = 'storage/logo/' . $newFilename;

                    $employeeLogo = new EmployeeLogo();
                    $employeeLogo->logo = $image;
                    $employeeLogo->user_id = auth()->user()->id;
                    $employeeLogo->save();

                }
            }
            $name = auth()->user()->name;

            $companyDescription['user_url'] = url('/profile/') . '/' . auth()->user()->id;
            $companyDescription['name'] = $name;
            $description = 'upload logo';            
            $companyDescription['changes'] = $description;
            $companyDescription = Helper::companyDescriptionData($description, 'upload_logo');
            if(!empty($companyDescription)){
                Helper::activity_log($companyDescription,auth()->user()->id);
            }
            flash('Logo Uploaded Successfully')->success();
            return redirect()->back();
        } else {
            flash('Please Uploads Logo')->error();
            return redirect()->back();
        }


    }


    public function DeleteEmployeelogo($id)
    {
        $images = EmployeeLogo::find($id);
        $images->delete();
        return redirect()->back();
    }

    public function employee_profile($id)
    {
        if(!auth()->check()){
            return redirect()->back();
        }
        if (!Helper::check_permission(1)) {
            flash(t("You do not have permission to access this module"))->error();
            return redirect()->back();
        }
        if(!auth()->check()){
            return redirect('/');
        }
        $data['user_data'] = User::user_by_id($id);
        $data['nationality_list'] = Nationality::pluck('id', 'name');
        // Meta Tags
        view()->share([
            'title' => t('Profile'),
            'description' => t('Profile'),
            'keywords' => t('Profile'),
            // Add more variables as needed
        ]);
        return view('pages.employee_profile')->with('data', $data);
    }


     public function get_profile(Request $request, $id)
    {
        
        if (!auth()->check()) {
            return redirect('/');
        }
        if (!Helper::check_permission(1)) {
            flash(t("You do not have permission to access this module"))->error();
            return redirect('/');
        }


        if (!empty($request->get('page')) && $request->get('page') == 'applicants') {
            if (url()->previous()) {
                Session::put('previous_ul', url()->previous());
            }
        }
        $title = t('profile');
        view()->share('title', $title);
        $data['user'] = User::user_by_id($id);
       
        if(empty($data['user'])){
            return redirect('/');
        }
        $data['nationality_list'] = Nationality::pluck('id', 'name');

        $data['company_data'] = Company::where('c_id', auth()->user()->id)->first();

        $data['city_data'] = City::where('id', $data['user']->city)->first();

        $data['country_data'] = Country::where('code', $data['user']->country_code)->first();
        $data['fav_data'] = Favoriteresume::get_Favoriteresume_by_user_id_and_company_id($id);

        if (auth()->check()) {
            if (auth()->user()->id == $id) {
                if (auth()->user()->user_type_id == 2) {

                    return redirect(url('/employee_profile/' . $id));

                }
                if (auth()->user()->user_type_id == 5) {
                    return redirect(url('/affiliate/affiliate_profile/' . $id));
                }
            } else {
                if (auth()->user()->user_type_id == 2) {
                    if ($data == null) {
                        return redirect()->back();
                    }
                }
            }
            $data['isUnlock'] = 0;
            if (auth()->user()->user_type_id == 2) {

                return view('pages.profile')->with('data', $data);
            } else {

                if (auth()->user()->id == $id) {
                    $temp = User::where('id', $id)->first();
                    $data['companies'] = Company::where('user_id', $id)->get();
                    $data['remaning_days'] = 0;
                    $package_expire_date=CompanyPackages::get_latest_package_subscribed();
                    if (!empty($package_expire_date)) {
                        // Pass true for absolute value
                        $data['remaning_days'] = helper::calculate_remaining_days_with_time($package_expire_date);
                    }
                    $data['latest_package'] = CompanyPackages::get_latest_package_details();
                    if(!empty($data['latest_package']) && $data['latest_package']->unlimited_credit == 1){
                        $remaining_credits  = ContactCardsRemaining::check_total_used_credits(auth()->user()->id);
                    }else{
                        $remaining_credits  = CompanyPackages::check_credit_available(auth()->user()->id);
                    }
                   
                    
                    $data['packages_count'] = count(CompanyPackages::get_subscribed_package_details());
                    $credits  = CompanyPackages::check_total_credit(auth()->user()->id);
                    $data['credits'] = $credits;
                    $data['remaining_credits'] = $remaining_credits;
                    $remaining_post  = PostRemaining::check_total_post_used(auth()->user()->id);
                    $total_post  = CompanyPackages::check_total_post(auth()->user()->id);
                    $package_expire_posts  = PostRemaining::expire_package_posts(auth()->user()->id);

                    if($data['remaning_days'] > 0){
//                        $total_post=$total_post+$package_expire_posts;
                    }
//                    if(!empty($_GET['debug'])){
//                        dd($package_expire_posts);
//                    }
                    // commenting this code to avoid expired package posts adding in remaining posts
                    // if($package_expire_posts > 0){
                    //     $remaining_post=$remaining_post+$package_expire_posts;
                    // }
                   
                   
                    $data['total_post'] = $total_post;
                    $data['remaining_post'] = $remaining_post;
                   
                    $data['logoData'] = EmployeeLogo::get_comapny_logo(auth()->user()->id);
                    return view('pages.employer_profile')->with('data', $data);
                } else {

                    $data['company_has_premium_packages']=[];
                    if (auth()->user()->user_type_id == 1) {
                        $data['company_has_premium_packages'] = CompanyPackages::get_premium_packages_for_last_login();
                    }
                    
                    $temp = Unlock::get_unlocked_by_user_id($id);
                    $data['remainig_count']  = CompanyPackages::check_credit_available(auth()->user()->id);
                    if ($temp == null) {
                        $data['isUnlock'] = 0;
                    } else {
                        $data['isUnlock'] = 1;
                    }
                    $data['save_cv_data'] = Allsaved_resume::get_Allsaved_resume_by_applicant_id($id);
                    return view('pages.profile')->with('data', $data);
                }
            }
        } else {
            return redirect()->back();
        }
    }

    public function update_employee_profile(Request $request)
    {

        
        if (empty(auth()->user()->id)) {
            flash(t("Please Login to update your profile"))->error();
            return redirect()->back();
        }
          if (empty($request->post('experiences')) && empty(auth()->user()->experiences)) {
            flash(t("Please fill all the required data"))->error();
            return redirect()->back();
        }
        if (empty($request->post('nationality')) && empty(auth()->user()->nationality)) {
            flash(t("Please fill all the required data"))->error();
            return redirect()->back();
        }
        $user = User::find(auth()->user()->id);
        if(!empty($request->post('nationality'))){
            $user->nationality = $request->post('nationality');
        }
        if(!empty($request->post('experiences'))){
            $user->experiences = $request->post('experiences');
        }
        if(!empty($request->post('visa'))){
            $user->visa = $request->post('visa');
        }
        if(!empty($request->post('visa_number'))){
            $user->visa_number = $request->post('visa_number');
        }
        if(!empty($request->post('country_work_visa'))){
            $user->country_work_visa = $request->post('country_work_visa');
        }
       
        if ($user->save()) {
            flash(t("Your account details has updated successfully"))->success();
            return redirect()->back();
        } else {
            flash(t("Unable to save profile data"))->error();
            return redirect()->back();
        }


    }

    public function update_email_settings(Request $request)
    {
        if (auth()->user()->user_type_id == 1) {
            $optional_emails = $request->get('optional_emails');
            if (!empty($optional_emails)) {
                $exploded_emails = explode(',', $optional_emails);
                $valid_optional_emails = true;
                foreach ($exploded_emails as $emails) {
                    $response_email = EmailCheck::check_user_email($emails);
                    if (!$response_email) {
                        $valid_optional_emails = false;
                        break;
                    }
                }
                if (empty($valid_optional_emails)) {
                    flash('Optional email addresses you provided are not valid.')->error();
                    return redirect()->back();
                }
            }
        }
        $data_update['optional_emails'] = $optional_emails;
        User::find(auth()->user()->id)->update($data_update);
        if (!empty($optional_emails)) {
            OptionalSelectedEmails::update_selection($request->get('selected_emails'), auth()->user()->id);
        }
        $name = auth()->user()->name;

        $companyDescription['user_url'] = url('/profile/') . '/' . auth()->user()->id;
        $companyDescription['name'] = $name;
        $changes[] = "Emails : <strong>" . $optional_emails . "</strong> <br>";
        if(!empty($changes))
        {
            $description = '';
            $description .= implode(" ", $changes) . "</a>";
            $companyDescription['changes'] = $description;
            $companyDescription = Helper::companyDescriptionData($changes, 'email_settings');
            if(!empty($companyDescription)){
                Helper::activity_log($companyDescription,auth()->user()->id);
            }
        }
        flash('Email settings update successfully.')->success();
        return redirect()->back();
    }

}