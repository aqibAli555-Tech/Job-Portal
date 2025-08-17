<?php

namespace App\Http\Controllers\Affiliate;

use App\Helpers\Helper;
use App\Http\Requests\Admin\Request;
use App\Models\City;
use App\Models\Company;
use App\Models\Country;
use Torann\LaravelMetaTags\Facades\MetaTag;
use App\Models\Nationality;
use App\Models\User;
use App\Helpers\Localization\Country as CountryLocalization;
use App\Helpers\Localization\Helpers\Country as CountryLocalizationHelper;
use App\Models\Package;
use Creativeorange\Gravatar\Facades\Gravatar;
use Illuminate\Support\Facades\Hash;
use App\Models\Scopes\VerifiedScope;

class ProfileController extends AffiliateBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function profile()
    {
        if(!auth()->check()){
            return redirect('/');
        }
        if (!Helper::check_permission(1)) {
            flash(t("You do not have permission to access this module"))->error();
            return redirect()->back();
        }


        $data = [];
        $title = t('profile');
        view()->share('title', $title);
        $data['userPhoto'] = (!empty(auth()->user()->email)) ? Gravatar::fallback(url('images/user.jpg'))->get(auth()->user()->email) : null;
        $data['cities'] = City::select('id', 'name')->where('country_code', auth()->user()->country_code)->orderBy('name')->get();
          
        $data['countries'] = CountryLocalizationHelper::transAll(CountryLocalization::getCountries());
        $data['countries_list'] = Country::orderBy('name')->get();

        // Meta Tags
        MetaTag::set('title', t('My account'));
        MetaTag::set('description', t('My account on :app_name', ['app_name' => config('settings.app.app_name')]));

        return appView('affiliate.profile', $data);
    }

    public function affiliate_profile($id)
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

        $excludedId = 5;
        $data['packages'] = Package::orderBy('lft', 'asc')->whereNotIn('id', [$excludedId])->get();

        // Meta Tags
        view()->share([
            'title' => t('Profile'),
            'description' => t('Profile'),
            'keywords' => t('Profile'),
            // Add more variables as needed
        ]);
        return view('affiliate.affiliate_profile')->with('data', $data);
    }

    public function update_profile(Request $request)
    {
    

        if (!Helper::check_permission(1)) {
            flash(t("You do not have permission to access this module"))->error();
            return redirect()->back();
        }
        if (empty($request->name)) {
            flash(t("Please fill all the required data"))->error();
            $nextUrl = 'affiliate/profile';
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

        $data_update['country_code'] = $request->country_code;
        $data_update['city'] = $request->get('city');
        $data_update['name'] = $request->get('name');

        
        $data_update['phone'] = $request->get('phone');
        

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
        $nextUrl = '/affiliate/affiliate_profile/' . $user->id;


        // Send Phone Verification message
        if ($phoneVerificationRequired) {
            // Save the Next URL before verification
            session()->put('itemNextUrl', $nextUrl);
            // Go to Phone Number verification
            $nextUrl = 'verify/user/phone/';
        }


        $name = auth()->user()->name;
        if (auth()->user()->user_type_id == 5) {
            $user_url = admin_url() . '/affiliates?search=' . auth()->user()->email;
        } else {
            $user_url = admin_url() . '/employer?search=' . auth()->user()->email;
        }
        $description = "An affiliate User Name: <b> <a href='$user_url'>$name</a></b> updated his Profile details <br>";

        $changes = [];

        if (auth()->user()->name != $request->name) {
            $changes[] = "Name : " . $request->name . " <br>";
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
            $changes[] = "Old City : " . @$old_city->name . " <br>";
            $changes[] = "City : " . @$city->name . " <br>";
        }
        
        if (auth()->user()->phone != $request->phone) {
            if (!empty($request->phone)) {
                $changes[] = "Phone : " . $request->phone . " <br>";
            }
        }
        
        if (!empty($changes)) {
            $description .= implode(" ", $changes) . "</a>";
            $affiliateDescription['changes'] = $description;
            Helper::activity_log($description);
            $affiliateDescription = Helper::affiliateDescriptionData($changes, 'profile');
            if(!empty($affiliateDescription)){
                Helper::activity_log($affiliateDescription,auth()->user()->id);
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
        if ($request->filled('password')) {
            $user->password = Hash::make($request->get('password'));
        }
        if ($request->filled('accept_terms')) {
            $user->accept_terms = (int)$request->get('accept_terms');
        }
        $user->time_zone = $request->get('time_zone');
        $user->save();

        flash(t("Your account settings has updated successfully"))->success();
        $nextUrl = '/affiliate/affiliate_profile/' . $user->id;
        return redirect($nextUrl);
    }
}