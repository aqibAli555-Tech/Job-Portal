<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Auth\Traits\RegistersUsers;
use App\Helpers\EmailCheck;
use App\Helpers\EmailHelper;
use App\Helpers\Helper;
use App\Helpers\Ip;
use App\Http\Controllers\Auth\Traits\VerificationTrait;
use App\Http\Controllers\FrontController;
use App\Http\Requests\UserRequest;
use App\Models\Activitylog;
use App\Models\Availability;
use App\Models\Causes;
use App\Models\City;
use App\Models\Company;
use App\Models\EmployeeSkill;
use App\Models\Entities;
use App\Models\Gender;
use App\Models\Country;
use App\Models\Nationality;
use App\Models\Permission;
use App\Models\Skill;
use App\Models\User;
use App\Models\UserType;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Imagick;
use Session;
use Str;
use Torann\LaravelMetaTags\Facades\MetaTag;

class RegisterController extends FrontController
{
    use RegistersUsers, VerificationTrait;

    /**
     * @var array
     */
    public $msg = [];
    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/account';

    /**
     * RegisterController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware(function ($request, $next) {
            $this->commonQueries();
            return $next($request);
        });
    }

    /**
     * Common Queries
     */
    public function commonQueries()
    {
        $this->redirectTo = 'account';
    }

    /**
     * Show the form the create a new user account.
     *tt
     * @return Factory|View
     */
    public function set_user_type(Request $request)
    {

        Session::put('check_user_type', $request->input('user_type'));
        // dd(\Session::get('check_user_type'));
        die;
    }
    public function registration() {
        MetaTag::set('title', getMetaTag('title', 'register'));
        MetaTag::set('description', strip_tags(getMetaTag('description', 'register')));
        MetaTag::set('keywords', getMetaTag('keywords', 'register'));
        return appView('auth.registration');
        
    }
    public function showRegistrationForm()
    {
        $refer_by = '';
        $referral_code = '';
        if (!in_array(request()->get('user_type_id'), [1, 2])) {
            abort(404);
        }
        if (request()->get('user_type_id') == 1 && !empty(request()->get('referral_code'))) {

            $referral_code = request()->get('referral_code');
            $referrer = User::where('referral_code', request()->get('referral_code'))->first();
            if (empty($referrer)) {
                flash(t("referral_not_exist"))->error();
                return redirect('/');
            }

            if ($referrer->is_active == 0) {
                flash(t("The_person_who_referred_you_is_no_longer_active"))->error();
                return redirect('/');
            }

            $refer_by = $referrer->name;
        }
        if (request()->get('user_type_id') == 2 && !empty(request()->get('referral_code'))) {
            flash(t("using_referrer_only_company_register"))->error();
                return redirect('/');
        }

        $data = [];
        // References
        $data['genders'] = Cache::remember('genders', config('cache.stores.file.expire'), function () {
            // Your actual query here with filters
            return Gender::query()->get();
        });
        $data['userTypes'] = Cache::remember('userTypes', config('cache.stores.file.expire'), function () {
            // Your actual query here with filters
            return UserType::query()->orderBy('id', 'DESC')->get();
        });
        
        $data['entities'] = Cache::remember('entities', config('cache.stores.file.expire'), function () {
            // Your actual query here with filters
            return Entities::orderBy('name')->get();
        });

        $data['cities'] = City::where('country_code', config('country.code'))->orderBy('name')->get();

        $data['skill'] = Cache::remember('skill_list_register', config('cache.stores.file.expire'), function () {
            // Your actual query here with filters
            return Skill::get();
        });
        $data['employee_skills'] = Cache::remember('employee_skills_list_register', config('cache.stores.file.expire'), function () {
            // Your actual query here with filters
            return EmployeeSkill::getAllskill();
        });
        $data['availability'] = Cache::remember('availability_list_register', config('cache.stores.file.expire'), function () {
            // Your actual query here with filters
            return Availability::orderBy('id')->where('status', 1)->get();
        });
        $data['nationality'] = Cache::remember('nationality_list_register', config('cache.stores.file.expire'), function () {
            // Your actual query here with filters
            return Nationality::pluck('id', 'name');
        });
        $data['countries'] = Country::orderBy('name')->get();
        $data['visa_types'] = array(
            array("Visa 18 (Normal/Professional)" => t("Visa 18 (Normal/Professional)")),
            array("Visa 18 (Mubarak AlKabeer/Small Business)" => t("Visa 18 (Mubarak AlKabeer/Small Business)")),
            array("Visa 18 (VIP/Golden)" => t('Visa 18 (VIP/Golden)')),
            array("Visa 18 (Other)" => t('Visa 18 (Other)')),
            array("Visa 22 (Family)" => t('Visa 22 (Family)')),
        );
        // Meta Tags
        if (request()->get('user_type_id') == 1) {
            $seo=Helper::getSeo('register_employer',request()->get('country'));
            $title = $seo['title'];
            $description = $seo['description'];
            $keywords = $seo['description'];
        } else {

            $seo=Helper::getSeo('register_employee',request()->get('country'));
            $title = $seo['title'];
            $description = $seo['description'];
            $keywords = $seo['description'];
        }
        if(empty(request()->get('user_type_id'))){
            MetaTag::set('title', getMetaTag('title', 'register'));
            MetaTag::set('description', strip_tags(getMetaTag('description', 'register')));
            MetaTag::set('keywords', getMetaTag('keywords', 'register'));
            return appView('auth.register.new_register');
        }
        view()->share([
            'title' => $title,
            'description' => $description,
            'keywords' => $keywords,
            'refer_by' => $refer_by,
            'referral_code' => $referral_code,
        ]);
        return appView('auth.register.index', $data);
    }


    /**
     * Register a new user account.
     *
     * @param UserRequest $request
     * @return $this|RedirectResponse
     */
    public function register(UserRequest $request)
    {

        if ($request->get('user_type_id') == 1) {
            if (strlen($request->get('phone')) < 8) {
                $response = array(
                    'status' => false,
                    'message' => t("Please enter correct Phone number"),
                    'url' => '',
                );
                return response()->json($response);
            }
        }

        $file = $request->file('file');
        $cv_file = $request->file('cv');
        if (empty($file)) {
            $response = array(
                'status' => false,
                'message' => "Please upload profile pictures",
                'url' => '',
            );
            return response()->json($response);
        }

        if (!empty($file)) {
            $image = $request->file('file');
            $extention = $image->getClientOriginalExtension();
            $allowed = array('jpg', 'png', 'jpeg');
            if (!in_array($extention, $allowed)) {

                $response = array(
                    'status' => false,
                    'message' => "Image Should be in PNG and JPG.",
                    'url' => '',
                );
                return response()->json($response);
            }
        }
        if ($request->get('user_type_id') == 1) {
            if (empty($request->get('entities'))) {
                $response = array(
                    'status' => false,
                    'message' => "Please select entities",
                    'url' => '',
                );
                return response()->json($response);
            }
        }


        if ($request->get('user_type_id') == 2) {
            if ($cv_file->getSize() > 5000000) {
                $response = array(
                    'status' => false,
                    'message' => "Exceeded filesize limit. You can upload maximum 5 MB files",
                    'url' => '',
                );
                return response()->json($response);
            }
            if (empty($cv_file->getFilename())) {
                $response = array(
                    'status' => false,
                    'message' => t("Please upload CV"),
                    'url' => '',
                );
                return response()->json($response);
            }

            if (empty($request['skill_set'])) {
                $response = array(
                    'status' => false,
                    'message' => "Please select skills sets",
                    'url' => '',
                );
                return response()->json($response);
            }

            $result = Helper::validatepdffile($cv_file);
            if ($result == false) {

                $response = array(
                    'status' => false,
                    'message' => "Please upload valid pdf file",
                    'url' => '',
                );
                return response()->json($response);
            }

            $filename = $cv_file->getRealPath();
            $handle = fopen($filename, "r");
            $contents = fread($handle, filesize($filename));
            fclose($handle);
            if (stristr($contents, "/Encrypt")) {
                $response = array(
                    'status' => false,
                    'message' => "Please upload valid pdf file",
                    'url' => '',
                );
                return response()->json($response);
            }

            $allowed = array('pdf','PDF');
            $fileName1 = $cv_file->getClientOriginalName();
            $ext = pathinfo($fileName1, PATHINFO_EXTENSION);
            if (!in_array($ext, $allowed)) {
                $response = array(
                    'status' => false,
                    'message' => t("Please select pdf file"),
                    'url' => '',
                );
                return response()->json($response);
            }

            if (config('country.code') === 'KW') {

                if (empty($request['visa'])) {
                    $response = array(
                        'status' => false,
                        'message' => "Please select visa",
                        'url' => '',
                    );
                    return response()->json($response);
                }

                if (!empty($request['visa']) && $request['visa'] == 'Yes I have visa') {
                    if (empty($request['country_work_visa'])) {
                        $response = array(
                            'status' => false,
                            'message' => "Please Add Country of work visa",
                            'url' => '',
                        );
                        return response()->json($response);
                    }
                    if ($request['country_work_visa'] == "KW") {
                        if (empty($request['visa_number'])) {
                            $response = array(
                                'status' => false,
                                'message' => "Please Add Work Visa Type",
                                'url' => '',
                            );
                            return response()->json($response);
                        }
                    }
                }
            }
        }
        $check_email_exist = User::where('email', $request['email'])->first();
        // Save
        if (!empty($check_email_exist)) {
            $response = array(
                'status' => false,
                'message' => "The email address has already been taken.",
                'url' => '',
            );
            return response()->json($response);
        }


        if (!empty($request->get('email'))) {
            $result = EmailCheck::check_user_email($request->get('email'));
            if (!$result) {
                $response = array(
                    'status' => false,
                    'message' => t("please_provide_valid_email_to_register_your_account"),
                    'url' => '',
                );
                return response()->json($response);
            }
        }

        // get referral details 
        $affiliate_id = '';
        if (!empty($request->referral_code) && $request->user_type_id == 1) {
            $referral_user = User::where('referral_code', $request->referral_code)->first();
            if (empty($referral_user)) {
                $response = array(
                    'status' => false,
                    'message' => t("referral_not_exist"),
                    'url' => '',
                );
                return response()->json($response);
            }

            $affiliate_id = $referral_user->id;
        }
        if ($request->user_type_id == 2 && !empty($request->referral_code)) {
                $response = array(
                    'status' => false,
                    'message' => t("using_referrer_only_company_register"),
                    'url' => '/',
                );
                return response()->json($response);
        }

        // New User
        $user = new User();
        $input = $request->only($user->getFillable());
        foreach ($input as $key => $value) {
            $user->{$key} = $value;
        }
        if (!empty($affiliate_id) && $request->user_type_id == 1) {
            $user->affiliate_id = $affiliate_id;
        }
        $user->country_code = config('country.code');
        $user->city = $request->get('city_id');
        $user->language_code = config('app.locale');
        $user->password = Hash::make($request->get('password'));
        $user->ip_addr = Ip::get();
        $user->verified_email = 1;
        $user->verified_phone = 1;
        $user->city = $request['city_id'];
        $user->password_without_hash = $request->get('password');
        $user->created_at = date('Y-m-d H:i:s');
        $user->nationality = !empty($request->get('nationality')) ? $request->get('nationality') : '';
        $user->experiences = !empty($request->get('experiences')) ? $request->get('experiences') : '';

        if (!str_contains($request->get('phone'), '+' . config('country.phone'))) {
            $user->phone = '+' . config('country.phone') . $request->get('phone');
        }
        if ($request->get('user_type_id') == 2) {
            $user->skill_set = implode(",", $request['skill_set']);
            $user->availability = (!empty($request['availability']) ? $request['availability'] : 1);
            $user->visa = !empty($request->get('visa')) ? $request->get('visa') : '';
            $user->visa_number = !empty($request->get('visa_number')) ? $request->get('visa_number') : '';
            $user->country_work_visa = !empty($request->get('country_work_visa')) ? $request->get('country_work_visa') : '';
        }
        $nextUrl = '/';

        if ($user->save()) {
            $user_id = $user->id;
            if (!empty($file)) {
                $file_type = $file->getClientOriginalExtension();
                $destinationPath = public_path('/') . 'storage/pictures/kw/' . $user_id;
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                $result = Helper::validateUserProfileImage($file);

                if (!$result) {
                    $fileName = 'pictures/default.jpg';
                } else {
                    $fileName = 'pictures/kw/' . $user_id . '/profile_' . time() . "." . $file_type;
                }

                $file->move($destinationPath, $fileName);
                $user_up = User::withoutGlobalScopes()->where('id', $user_id)->first();
                $user_up->file = $fileName;
                $user_up->save();

            }

            if (!empty($cv_file)) {
                $file_type_cv = $cv_file->getClientOriginalExtension();
                $destinationPathCv = public_path('/') . 'storage/employee_cv/' . $user_id;
                if (!file_exists($destinationPathCv)) {
                    mkdir($destinationPathCv, 0777, true);
                }
                $fileNameCv = 'employee_cv/' . $user_id . '/' . time() . "." . $file_type_cv;
                $cv_file->move($destinationPathCv, $fileNameCv);
                $user_up = User::withoutGlobalScopes()->where('id', $user_id)->first();
                $user_up->employee_cv = $fileNameCv;
                $user_up->save();
            }


            if (!empty($user_up)) {
                if ($request->get('user_type_id') == 1) {
                    $type = 'Employer';
                    $user_up->parent_id = $user_id;
                    $user_up->save();
                    $compnay['user_id'] = $user_id;
                    $compnay['c_id'] = $user_id;
                    $compnay['description'] = '';
                    $compnay['country_code'] = config('country.code');
                    $compnay['city_id'] = $request['city_id'];
                    $compnay['phone'] = $user_up->phone;
                    $compnay['name'] = $user_up->name;
                    $compnay['email'] = $user_up->email;
                  
                    if (!empty($request->get('entities'))) {
                        $entities = implode(",", $request->get('entities'));
                    } else {
                        $entities = $request->get('entities');
                    }
                    $compnay['entities'] = $entities;
                    $compnay['logo'] = $user_up->file;
                    $compnay['thumbnail'] = $user_up->thumbnail;
                    Company::insert($compnay);
                } else {
                    $type = 'Employee';
                }

                $name = $request->get('name');
                $email = $request->get('email');
                if ($request->get('user_type_id') == 1) {
                    $profile_url = admin_url() . '/employer?search=' . $email;
                } else {
                    $profile_url = admin_url() . '/job-seekers?search=' . $email;
                }
                if (!empty($request->referral_code)) {
                    $referrer_name = $referral_user->name;
                    $referrer_url = admin_url() . '/affiliates?search=' . $referral_user->email;
                    $description = "A new User Name: <a href='$profile_url'>$name</a> has registered as an $type using a referral link. Referred by: <a href='$referrer_url'>$referrer_name</a>";
                }else{
                    $description = "A new User Name: <a href='$profile_url'>$name</a> has registered as an $type ";
                }
                Helper::activity_log($description);
                $data['page'] = 'user_registered';
                $data['from'] = url()->previous();
                $data['server'] = json_encode(request()->server());
                $data['request'] = $request;
                $data['quary_parameter'] = [];
                Helper::page_count_post($data);


                
            }


            // Send Admin Notification Email
            if (config('settings.mail.admin_notification') == 1) {
                try {
                    // Get all admin users
                    $admins = User::permission(Permission::getStaffPermissions())->get();
                    if ($admins->count() > 0) {
                        foreach ($admins as $admin) {
                            EmailHelper::sendadminemail($admin, $user);
                            EmailHelper::senduserregisteremail($user);
                        }
                    }
                } catch (Exception $e) {
                    flash($e->getMessage())->error();
                }
            }
            // Save the Next URL before verification
            session()->put('userNextUrl', $nextUrl);
            $credentials = [
                'email' => $request->get('email'),
                'password' => $request->get('password'),
                'blocked' => 0,
            ];
            // Auth the User
            if (auth()->attempt($credentials)) {
                \Artisan::call('cache:clear');
                $user = User::find(auth()->user()->getAuthIdentifier());
                $values = array(
                    'last_login_at' => date('Y-m-d h:i:s')
                );
                User::where('id', $user->id)->update($values);

                try {
                    $url = url('public/storage/' . $fileName);
                    $unique = 'thumbnail_' . uniqid() . '.jpg';
                    Helper::generate_thumbnail($url, $user_id, $unique);
                    $values_thum = array(
                        'thumbnail' => 'pictures/kw/' . $user_id . '/' . $unique,
                    );
                    User::where('id', $user_id)->update($values_thum);

                } catch (Exception $e) {
                    error_log($e->getMessage());
                }


                $data['page'] = 'login_users';
                $data['from'] = url()->previous();
                $data['server'] = json_encode(request()->server());
                $data['request'] = $request;
                $data['quary_parameter'] = [];
                Helper::page_count_post($data);
                
                if ($user->user_type_id == 2) {
                    $message = "Thank you for registering on Hungry For Jobs! You can now start applying to as many jobs as you like! Companies can now also see your profile in our Search CV Database, we wish you the best of luck!";
                    flash($message)->success();
                    $response = array(
                        'status' => true,
                        'message' => '',
                        'url' => url('/employee_profile/' . $user->id),
                    );

                    return response()->json($response);
                } else {
                    flash('Thank you for registering on Hungry For Jobs! Get started by subscribing to one of our packages in the Upgrade Account section on the left side menu and start posting jobs with us! Once you post a job, you can either wait for applicants to apply or you can also search for your employees through our CV Database - Good Luck!')->success();
                    $response = array(
                        'status' => true,
                        'message' => '',
                        'url' => url('/profile/' . $user->id),
                    );
                    return response()->json($response);
                }
            }
        } else {
            $response = array(
                'status' => false,
                'message' => 'Unable to Register. Please try again later!',
                'url' => '',
            );
            return response()->json($response);
        }
    }


    /**
     * @return Factory|RedirectResponse|Redirector|View
     */
    public
    function finish()
    {
        // Keep Success Message for the page refreshing
        session()->keep(['message']);
        if (!session()->has('message')) {
            return redirect('/');
        }

        // Meta Tags
        MetaTag::set('title', session('message'));
        MetaTag::set('description', session('message'));

        return appView('auth.register.finish');
    }

    public function resetpass(Request $request)
    {


        if (!empty($request['user_id'])) {
            $id = $request['user_id'];
            $user = User::find($id);
            if ($request['password'] == $request['cpassword']) {
                $user->password_without_hash = $request['password'];
                $user->password = Hash::make($request['password']);
                if ($user->save()) {
                    if ($user->user_type_id == 2) {
                        $employee_url = admin_url() . '/job-seekers?search=' . $user->email;
                        $description = "The administrator changed the password for the <a href='$employee_url'>$user->name</a> user.";
                        Helper::activity_log($description);
                    } else {
                        $employer_url = admin_url() . '/employer?search=' . $user->email;
                        $description = "The administrator changed the password for the <a href='$employer_url'>$user->name</a> user.";
                        Helper::activity_log($description);
                    }
                    flash(trans('Password Updated Successfully'))->success();
                    return redirect()->back();
                } else {
                    flash(trans('Please Try Again'))->success();
                    return redirect()->back();
                }
            } else {
                flash(trans('Password Confirmation does not match'))->error();
                return redirect()->back();
            }
        } else {
            return redirect()->back();
        }
    }

    function sendResetPasswordEmail(Request $request)
    {
        $verifyEmail = User::where('email', $request['login'])->first();
        if (!empty($verifyEmail)) {
            $currentDateTime = Carbon::now();
            $currentDateTime1 = $currentDateTime->addMinutes(30);
            $currentDateTime2 = $currentDateTime1->format('Y-m-d h:i:s');
            $encoded = base64_encode($currentDateTime2);
            $verifyEmail->reset_token = $encoded;
            $verifyEmail->reset_token = $encoded;
            if ($verifyEmail->save()) {
                $resetPwdUrl = url('password/reset/' . $encoded);
                $data['subject'] = 'Reset Your Password';
                $data['myName'] = $verifyEmail->name;
                $data['email'] = $verifyEmail->email;
                $data['reseturl'] = $resetPwdUrl;
                $data['header'] = 'Reset Password';
                $data['view'] = 'emails.reset_password';

                $helper = new Helper();
                $response = $helper->send_email($data);
                if ($response) {
                    flash('Email Send Successfully')->success();
                    return redirect()->back();
                } else {
                    flash('Email sending failed. Please try again.')->error();
                    return redirect()->back();
                }
            }
        } else {
            flash(trans('Your email is not valid. Please enter correct email address'))->error();
            return redirect()->back();
        }
    }

    function UpdatePassword(Request $request)
    {
        if ($request['password'] != $request['password_confirmation']) {
            flash(trans('Password do not match!'))->error();
            return redirect()->back();
        }
        $user = User::where('email', $request['login'])->first();
        if (!empty($user)) {
            $user->password = Hash::make($request['password']);
            $user->remember_token = Str::random(60);
            $user->password_without_hash = $request['password'];
            $user->reset_token = '';
            if ($user->save()) {
                // Activity  Log for reset Password
                if ($user->user_type_id == 2) {
                    $employee_url = admin_url() . '/job-seekers?search=' . $user->email;
                    $description = "<a href='$employee_url'>$user->name</a>  updates his account password. ";
                    Helper::activity_log($description);
                } else {
                    $employer_url = admin_url() . '/employer?search=' . $user->email;
                    $description = "<a href='$employer_url'>$user->name</a>  updates his account password. ";
                    Helper::activity_log($description);
                }
                flash('Password Updated Successfully')->success();
                return redirect('login');
            } else {
                flash('Password not updated. Please try again.')->error();
                return redirect()->back();
            }
        }
    }

    public function affiliateRegistrationForm()
    {
        $data = [];

        $data['cities'] = City::where('country_code', config('country.code'))->orderBy('name')->get();
        $refer_by = '';
        $referral_code = '';

        if (request()->get('user_type_id') == 5 && !empty(request()->get('referral_code'))) {

            $referral_code = request()->get('referral_code');
            $referrer = User::where('referral_code', request()->get('referral_code'))->first();
            if (empty($referrer)) {
                flash(t("referral_not_exist"))->error();
                return redirect('/');
            }

            if ($referrer->is_active == 0) {
                flash(t("The_person_who_referred_you_is_no_longer_active"))->error();
                return redirect('/');
            }

            $refer_by = $referrer->name;
        }

        $seo=Helper::getSeo('affiliate-register',request()->get('country'));
        $title = $seo['title'];
        $description = $seo['description'];
        $keywords = $seo['description'];

        view()->share([
            'title' => $title,
            'description' => $description,
            'keywords' => $keywords,
            'refer_by' => $refer_by,
            'referral_code' => $referral_code,
        ]);
        return appView('auth.register.affiliate', $data);
    }
}
