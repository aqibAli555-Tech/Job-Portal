<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Helpers\Auth\Traits\SendsPasswordResetEmails;
use App\Http\Requests\Admin\ForgotPasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Larapen\Admin\app\Http\Controllers\Controller;

class ForgotPasswordController extends Controller
{
    protected $data = []; // the information we send to the view
    protected $redirectTo = 'dashboard';

    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails {
        sendResetLinkEmail as public traitSendResetLinkEmail;
    }

    /**
     * PasswordController constructor.
     */
    public function __construct()
    {
        $this->middleware('guest');

        $this->redirectTo = admin_uri('dashboard');

        parent::__construct();
    }

    // -------------------------------------------------------
    // Laravel overwrites for loading admin views
    // -------------------------------------------------------

    /**
     * Display the form to request a password reset link.
     *
     * @return Response
     */
    public function showLinkRequestForm()
    {
        $this->data['title'] = trans('admin.reset_password'); // set the page title

        return view('admin::auth.passwords.email', $this->data);
    }

    /**
     * Send a reset link to the given user.
     *
     * @param ForgotPasswordRequest $request
     * @return RedirectResponse
     */
    public function sendResetLinkEmail(ForgotPasswordRequest $request)
    {
        // Go to the core process
        return $this->traitSendResetLinkEmail($request);
    }
}
