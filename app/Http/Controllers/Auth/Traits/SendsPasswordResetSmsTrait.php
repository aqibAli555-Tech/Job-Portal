<?php

namespace App\Http\Controllers\Auth\Traits;

use App\Models\PasswordReset;
use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;

trait SendsPasswordResetSmsTrait
{
    /**
     * Send a reset code to the given user.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function sendResetTokenSms(Request $request)
    {
        // Form validation
        $rules = ['login' => 'required'];
        $this->validate($request, $rules);

        // Check if the phone exists
        $user = User::where('phone', $request->get('phone'))->first();
        if (empty($user)) {
            return back()->withErrors(['phone' => t('The entered value is not registered with us')])->withInput();
        }

        // Create the token in database
        $token = mt_rand(100000, 999999);
        $passwordReset = PasswordReset::where('phone', $request->get('phone'))->first();
        if (empty($passwordReset)) {
            $passwordResetInfo = [
                'email' => null,
                'phone' => $request->get('phone'),
                'token' => $token,
                'created_at' => date('Y-m-d H:i:s'),
            ];
            $passwordReset = new PasswordReset($passwordResetInfo);
        } else {
            $passwordReset->token = $token;
            $passwordReset->created_at = date('Y-m-d H:i:s');
        }
        $passwordReset->save();

        try {
            // Send the token by SMS
            $passwordReset->notify(new ResetPasswordNotification($user, $token, 'phone'));
        } catch (Exception $e) {
            flash($e->getMessage())->error();
        }

        // Got to Token verification Form
        return redirect('password/token');
    }

    /**
     * URL: Token Form
     *
     * @return Factory|View
     */
    public function showTokenRequestForm()
    {
        return view('token');
    }

    /**
     * URL: Token Form POST method
     *
     * @param Request $request
     * @return $this|RedirectResponse|Redirector
     */
    public function sendResetToken(Request $request)
    {
        // Form validation
        $rules = ['code' => 'required'];
        $this->validate($request, $rules);

        // Check if the token exists
        $passwordReset = PasswordReset::where('token', $request->get('code'))->first();
        if (empty($passwordReset)) {
            return back()->withErrors(['code' => t('The entered code is invalid')])->withInput();
        }

        // Go to Reset Password Form
        return redirect('password/reset/' . $request->get('code'));
    }
}
