<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Auth\Traits\AuthenticatesUsers;
use App\Helpers\UrlGen;
use App\Http\Controllers\FrontController;
use App\Http\Requests\LoginRequest;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Session;
use Torann\LaravelMetaTags\Facades\MetaTag;

class
LoginController extends FrontController
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    // If not logged in redirect to
    protected $loginPath = 'login';

    // The maximum number of attempts to allow
    protected $maxAttempts = 5;

    // The number of minutes to throttle for
    protected $decayMinutes = 15;

    // After you've logged in redirect to
    protected $redirectTo = 'account';

    // After you've logged out redirect to
    protected $redirectAfterLogout = '/';

    /**
     * LoginController constructor.
     */
    public function __construct()
    {

        parent::__construct();

        $this->middleware('guest')->except(['except' => 'logout']);

        // Set default URLs
        $isFromLoginPage = Str::contains(url()->previous(), '/' . UrlGen::loginPath());
        $this->loginPath = $isFromLoginPage ? UrlGen::loginPath() : url()->previous();
        $this->redirectTo = $isFromLoginPage ? 'account' : url()->previous();
        $this->redirectAfterLogout = '/';

        // Get values from Config
        $this->maxAttempts = (int)config('settings.security.login_max_attempts', $this->maxAttempts);
        $this->decayMinutes = (int)config('settings.security.login_decay_minutes', $this->decayMinutes);
    }

    // -------------------------------------------------------
    // Laravel overwrites for loading JobClass views
    // -------------------------------------------------------

    /**
     * Show the application login form.
     *
     * @return RedirectResponse|mixed
     */
    public function showLoginForm()
    {
        // Remembering Login
        if (auth()->viaRemember()) {
            return redirect()->intended($this->redirectTo);
        }

        // Meta Tags
        MetaTag::set('title', getMetaTag('title', 'login'));
        MetaTag::set('description', strip_tags(getMetaTag('description', 'login')));
        MetaTag::set('keywords', getMetaTag('keywords', 'login'));

        return appView('auth.login');
    }

    /**
     * @param LoginRequest $request
     * @return $this|RedirectResponse|void
     * @throws ValidationException
     */
    public function login(LoginRequest $request)
    {
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            $this->sendLockoutResponse($request);
            $response = array(
                'status' => false,
                'message' => 'To many login attempt.Please try again later',
                'url' => '',
            );
            return response()->json($response);
        }

        // Get the right login field
        $loginField = getLoginField($request->get('login'));

        // Get credentials values
        $credentials = [
            $loginField => $request->get('login'),
            'password' => $request->get('password'),
            'blocked' => 0,
        ];
        if (in_array($loginField, ['email', 'phone'])) {
            $credentials['verified_' . $loginField] = 1;
        } else {
            $credentials['verified_email'] = 1;
            $credentials['verified_phone'] = 1;
        }

        if (empty($credentials['email'])) {
            $response = array(
                'status' => false,
                'message' => trans('auth.failed'),
                'url' => '',
            );
            return response()->json($response);
        }


        // Auth the User
        if (auth()->attempt($credentials)) {

            $user = User::find(auth()->user()->getAuthIdentifier());
            $values = array(
                'last_login_at' => date('Y-m-d H:i:s')
            );
            User::where('id', $user->id)->update($values);
            // Redirect admin users to the Admin panel
            if (auth()->check() && auth()->user()->is_admin == 1) {
                if ($user->hasAllPermissions(Permission::getStaffPermissions())) {
                    $response = array(
                        'status' => true,
                        'message' => 'Login Successfully',
                        'url' => url('admin/dashboard'),
                    );
                  
                    return response()->json($response);
                }
            }

            if ($user->user_type_id == 3) {
                $parent_data = User::where('id', $user->company_id)->first();
                $staff_id = $user->id;
                auth()->logout();
                if (empty($user->permissions)) {
                    $response = array(
                        'status' => false,
                        'message' => "You can't log in because you don't have the necessary permissions to do so.",
                        'url' => '',
                    );
                    return response()->json($response);
                }

                $credentials_for_employer = [
                    'email' => $parent_data->email,
                    'password' => $parent_data->password_without_hash,
                ];
                if (auth()->attempt($credentials_for_employer)) {
                    Session::put('staff_id', $staff_id);
                    $response = array(
                        'status' => true,
                        'message' => 'Login Successfully',
                        'url' => url('/profile/' . $parent_data->id),
                    );
                    return response()->json($response);
                }
            }

            if ($user->user_type_id == 2) {
                $response = array(
                    'status' => true,
                    'message' => 'Login Successfully',
                    'url' => url('/employee_profile/' . $user->id),
                );
                return response()->json($response);
            } else if($user->user_type_id == 5){
                $response = array(
                    'status' => true,
                    'message' => 'Login Successfully',
                    'url' => url('/affiliate/dashboard/'),
                );
                if($user->is_active == 0){
                    auth()->logout();
                    $response = array(
                        'status' => false,
                        'message' => "Your account has been deactivated by the admin. Please contact support for assistance.",
                        'url' => '',
                    ); 
                }
                return response()->json($response);
            } else {
                if ($user->user_type_id == 1 && empty($user->parent_id)) {

                    $response = array(
                        'status' => true,
                        'message' => t('Welcome to your parent account, where you can add as many companies as you have. Once you add a company, go ahead and post a job!'),
                        'url' => url('/account/companies'),
                    );
                    return response()->json($response);

                } else {

                    if ($request->to_upgrade == 1) {
                        Session::put('to_post_job', 1);
                        return redirect('/account/upgrade');
                    } else {

                        $response = array(
                            'status' => true,
                            'message' => 'Login Successfully',
                            'url' => url('/profile/' . $user->id),
                        );
                        return response()->json($response);

                    }
                }
            }
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        // $this->incrementLoginAttempts($request);

        // Check and retrieve previous URL to show the login error on it.
        if (session()->has('url.intended')) {
            $this->loginPath = session()->get('url.intended');
        }
        if (!empty($credentials['email'])) {
            $user_data = User::withoutGlobalScopes()->where('email', $credentials['email'])->first();
        } else if (!empty($credentials['phone'])) {
            $user_data = User::withoutGlobalScopes()->where('phone', $credentials['phone'])->first();
        } else {
            $user_data = [];
        }

        $loginPath = url('?login_model=true');
        if (!empty($user_data)) {

            session(['login_id' => $user_data->id]);
            if ($user_data->verified_email == 0) {
                session(['email_status' => true]);
                session()->forget('phone_status');
                $response = array(
                    'status' => false,
                    'message' => trans('auth.To access your account, please verify your email and phone number SMS to be able to login, if any problems occur please'),
                    'url' => '',
                );
                return response()->json($response);
            } elseif ($user_data->verified_phone == 0) {
                session(['phone_status' => true]);
                session()->forget('email_status');
                $mess = '';
                if ($user_data->sms_count >= 3) {
                    $mess = trans('auth.phone_count');
                }
                $response = array(
                    'status' => false,
                    'message' => trans('auth.To access your account, please verify your email and phone number SMS to be able to login, if any problems occur please'),
                    'url' => '',
                );
                return response()->json($response);

            }
        }

        session()->flush();
        $response = array(
            'status' => false,
            'message' => trans('auth.failed'),
            'url' => '',
        );
        return response()->json($response);
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Redirector
     */
    public function logout(Request $request)
    {
        // Get the current Country
        if (session()->has('country_code')) {
            $countryCode = session('country_code');
        }
        if (session()->has('allowMeFromReferrer')) {
            $allowMeFromReferrer = session('allowMeFromReferrer');
        }

        // Remove all session vars
        $this->guard()->logout();
        $request->session()->forget(['_token', '_previous', '_flash', 'url', 'staff_id']);
        $request->session()->regenerate();

        // Retrieve the current Country
        if (isset($countryCode) && !empty($countryCode)) {
            session()->put('country_code', $countryCode);
        }
        if (isset($allowMeFromReferrer) && !empty($allowMeFromReferrer)) {
            session()->put('allowMeFromReferrer', $allowMeFromReferrer);
        }

        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }
}
