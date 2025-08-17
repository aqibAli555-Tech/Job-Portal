<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Auth\Traits\ResetsPasswords;
use App\Http\Controllers\Auth\Traits\ResetsPasswordsUsingTokenTrait;
use App\Http\Controllers\FrontController;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\Permission;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Torann\LaravelMetaTags\Facades\MetaTag;

class ResetPasswordController extends FrontController
{
    use ResetsPasswords, ResetsPasswordsUsingTokenTrait;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/account';

    /**
     * ResetPasswordController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->redirectTo = config('lang.abbr') . '/account';

        $this->middleware('guest');
    }

    // -------------------------------------------------------
    // Laravel overwrites for loading JobClass views
    // -------------------------------------------------------

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param Request $request
     * @param null $token
     * @return mixed
     */
    public function showResetForm(Request $request, $token = null)
    {
        $users = User::withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])->where('reset_token', $token)->first();
        if (empty($users)) {
            flash(t("Token is expire Please send new request for reset password"))->error();
            return redirect(url('/'));
        }
        $decoded = base64_decode($token);
        $currentDateTime = Carbon::now();
        $currentDateTime = $currentDateTime->format('Y-m-d h:i:s');
        if ($decoded >= $currentDateTime) {
            MetaTag::set('title', t('Reset Password'));
            MetaTag::set('description', t('Reset your Password'));
            return appView('auth.passwords.reset')->with(['token' => $token, 'email' => $request->email, 'reset_email' => $users->email]);
        } else {
            flash(t("Token is expire Please send new request for reset password"))->error();
            return redirect(url('/'));
        }
    }

    /**
     * Reset the given user's password.
     *
     * @param ResetPasswordRequest $request
     * @return RedirectResponse
     */
    public function reset(ResetPasswordRequest $request)
    {
        // Get the right login field
        $field = getLoginField($request->get('login'));
        $request->merge([$field => $request->get('login')]);
        if ($field != 'email') {
            $request->merge(['email' => $request->get('login')]);
        }


        // Go to the custom process (Phone)
        if ($field == 'phone') {
            return $this->resetPasswordUsingToken($request);
        }

        // Go to the core process (Email)

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
            $this->resetPassword($user, $password);
        }
        );
        $users = User::withoutGlobalScopes()->where('email', $request->get('login'))->first();
        if ($users->verified_phone == 0) {
            flash(t("Password update successfully.Please verify your Phone to login"))->success();
            $url = url('/');
        } else {
            $url = '';
        }

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $response == Password::PASSWORD_RESET
            ? $this->sendResetResponse($request, $response, $url, $users)
            : $this->sendResetFailedResponse($request, $response);
    }

    /**
     * Reset the given user's password.
     *
     * @param CanResetPassword $user
     * @param string $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $userInfo = [
            'password' => Hash::make($password),
            'remember_token' => Str::random(60),
            'verified_email' => 1, // Email auto-verified
        ];

        if ($user->can(Permission::getStaffPermissions())) {
            // Phone auto-verified
            $userInfo['verified_phone'] = 1;
        }

        $user->forceFill($userInfo)->save();
        if ($user->verified_phone == 1) {
            $this->guard()->login($user);
        }

    }
}
