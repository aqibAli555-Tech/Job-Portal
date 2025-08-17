<style>
#quickLogin .modal-body {
    padding: 0px;
}
#quickLogin{
  z-index: 9999;
}

#quickLogin .close {
    color: #fff;
    opacity: 1;
    margin-top: 5px;
}

#quickLogin .input-group-text {
    background: #e2e2e2;
    border: none;
    border-bottom: 1px solid #e2e2e2;
    border-radius: 0;
}

.new {
    position: relative;
    right: -232px;
    top: 13px;
}

#quickLogin .form-control {
    background: transparent !important;
    border: none !important;
    box-shadow: none !important;
    outline: none !important;
    border-bottom: 1px solid #e2e2e2 !important;
}

#quickLogin .form-control:focus {
    background: transparent !important;
}
#quickLogin{
        z-index: 9999;
}

.btn-login {
    background: #22D3FD;
    color: #fff;
    border: none;
}

.btn-register {
    background: #615583;
    color: #fff;
    border: none;

}

.btn-register:hover {
    background: #615583;
    color: white !important;
    font-size: 14px;
}


@media screen and (min-width: 900px) {
    #quickLogin .modal-dialog {
        width: 900px;
    }
}
@media (max-width: 767px) {
    #quickLogin{
        margin-top: 20px;
         z-index: 999999;
    }
}
</style>

<?php

use App\Models\User;

$logo_show = !empty(config('settings.app.logo')) ? config('settings.app.logo') : 'app/default/picture.jpg';
$image_path = url("public/storage/Login_popup.jpeg");
if (!empty(session('login_id'))) {
    $user = User::withoutGlobalScopes()->where('id', session('login_id'))->first();
}
$firstDiv = 'col-md-11 my-2';
$secondDiv = '';
$count = 0;
if (!empty(session('login_id'))) {
    if (!empty(session('email_status'))) {
        $firstDiv = 'col-md-12';
        $secondDiv = 'col-md-12';
    } else {
        if ($user->sms_count < 3) {
            $firstDiv = 'col-md-12';
            $secondDiv = 'col-md-12';
        }
    }
} else {
    $count = 1;
}
?>
<div class="modal fade" id="quickLogin" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <form role="form" id="login_form" method="POST" action="">
                            {!! csrf_field() !!}
                            <div class="quickLoginLeft">
                                Sign In

                                @if (isset($errors) and $errors->any() and old('quickLoginForm')=='1')
                                <div class="alert alert-danger">
                                    <div class="row">
                                        <div class="<?= $firstDiv ?>">
                                            @foreach($errors->all() as $error)
                                            <li style="">{{ $error }}
                                                @if(!empty(session('email_status') ||session('phone_status')))
                                                <a href="{{url('contact')}}">Contact Us</a>
                                                @endif
                                            </li>
                                            @endforeach
                                        </div>
                                        @if(!empty(session('login_id')))
                                        <div class="<?= $secondDiv ?>">
                                            <ul class="list list-check my-2">

                                                @if(!empty(session('email_status')))
                                                <a class="btn btn-primary btn-sm"
                                                    href="{{url('verify/user/'.session('login_id').'/resend/email')}}">{{t('Resend Email')}}</a>
                                                @else
                                                @if($user->sms_count < 3) <a class="btn btn-primary btn-sm"
                                                    href="{{url('verify/user/'.session('login_id').'/resend/sms?form_login=1')}}">
                                                    {{t('Resend Sms')}}</a>
                                                    @endif
                                                    @endif

                                            </ul>
                                        </div>
                                        @endif
                                        <div class="col-md-1" hidden>
                                            <button type="button" class="close my-2" data-dismiss="alert"
                                                aria-hidden="true">
                                                &times;
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @if (
                                config('settings.social_auth.social_login_activation')
                                and (
                                (config('settings.social_auth.facebook_client_id') and
                                config('settings.social_auth.facebook_client_secret'))
                                or (config('settings.social_auth.linkedin_client_id') and
                                config('settings.social_auth.linkedin_client_secret'))
                                or (config('settings.social_auth.twitter_client_id') and
                                config('settings.social_auth.twitter_client_secret'))
                                or (config('settings.social_auth.google_client_id') and
                                config('settings.social_auth.google_client_secret'))
                                )
                                )
                                <div class="row mb-3 d-flex justify-content-center pl-2 pr-2">
                                    @if (config('settings.social_auth.facebook_client_id') and
                                    config('settings.social_auth.facebook_client_secret'))
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-1 pl-1 pr-1">
                                        <div class="col-xl-12 col-md-12 col-sm-12 col-xs-12 btn btn-lg btn-fb">
                                            <a href="{{ url('auth/facebook') }}" class="btn-fb"
                                                title="{!! strip_tags(t('Login with Facebook')) !!}">
                                                <i class="icon-facebook-rect"></i> Facebook
                                            </a>
                                        </div>
                                    </div>
                                    @endif
                                    @if (config('settings.social_auth.linkedin_client_id') and
                                    config('settings.social_auth.linkedin_client_secret'))
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-1 pl-1 pr-1">
                                        <div class="col-xl-12 col-md-12 col-sm-12 col-xs-12 btn btn-lg btn-lkin">
                                            <a href="{{ url('auth/linkedin') }}" class="btn-lkin"
                                                title="{!! strip_tags(t('Login with LinkedIn')) !!}">
                                                <i class="icon-linkedin"></i> LinkedIn
                                            </a>
                                        </div>
                                    </div>
                                    @endif
                                    @if (config('settings.social_auth.twitter_client_id') and
                                    config('settings.social_auth.twitter_client_secret'))
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-1 pl-1 pr-1">
                                        <div class="col-xl-12 col-md-12 col-sm-12 col-xs-12 btn btn-lg btn-tw">
                                            <a href="{{ url('auth/twitter') }}" class="btn-tw"
                                                title="{!! strip_tags(t('Login with Twitter')) !!}">
                                                <i class="icon-twitter-bird"></i> Twitter
                                            </a>
                                        </div>
                                    </div>
                                    @endif
                                    @if (config('settings.social_auth.google_client_id') and
                                    config('settings.social_auth.google_client_secret'))
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-1 pl-1 pr-1">
                                        <div class="col-xl-12 col-md-12 col-sm-12 col-xs-12 btn btn-lg btn-danger">
                                            <a href="{{ url('auth/google') }}" class="btn-danger"
                                                title="{!! strip_tags(t('Login with Google')) !!}">
                                                <i class="icon-googleplus-rect"></i> Google
                                            </a>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                @endif

                                <?php
                                $loginValue = (session()->has('login')) ? session('login') : old('login');
                                $loginField = getLoginField($loginValue);
                                if ($loginField == 'phone') {
                                    $loginValue = phoneFormat($loginValue, old('country', config('country.code')));
                                }
                                ?>
                                <!-- login -->
                                <?php $loginError = (isset($errors) and $errors->has('login')) ? ' is-invalid' : ''; ?>
                                <div class="form-group">
                                    <label for="mLogin"
                                        class="control-label">{{ t('Login') . ' (' . getLoginLabel() . ')' }}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-user fa"></i></span>
                                        </div>
                                        <input id="mLogin" name="login" required type="email"
                                            placeholder="{{ getLoginLabel() }}" class="form-control{{ $loginError }}"
                                            value="{{ $loginValue }}">
                                    </div>
                                </div>

                                <!-- password -->
                                <?php $passwordError = (isset($errors) and $errors->has('password')) ? ' is-invalid' : ''; ?>
                                <div class="form-group">
                                    <label for="mPassword" class="control-label">{{ t('Password') }}</label>
                                    
                                    <div class="input-group show-pwd-group">
                                    <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-lock fa"></i></span>
                                        </div>
                                                    <input id="password" name="password" minlength="8"
                                                           type="password" class="form-control{{ $passwordError }}"
                                                           placeholder="{{ t('Password') }}" autocomplete="off"
                                                           required>
                                                    <span class="icon-append show-pwd">
                                                            <button type="button" class="eyeOfPwd" onclick="showPwd()">
                                                                <i id="eyeIcon" class="far fa-eye-slash" style="color:#474b51"></i>
                                                            </button>
                                                        </span>
                                                </div>
                                </div>



                                <!-- remember -->
                                <?php $rememberError = (isset($errors) and $errors->has('remember')) ? ' is-invalid' : ''; ?>
                                <div class="form-group">
                                    <label class="checkbox form-check-label pull-left mt-2"
                                        style="font-weight: normal;">
                                        <input type="checkbox" value="1" name="remember" id="mRemember"
                                            class="{{ $rememberError }}"> {{ t('Keep me logged in') }}
                                    </label>
                                    <p class="pull-right mt-2">
                                        <a href="{{ url('password/reset') }}" style="color:#615583">
                                            {{ t('Lost your password') }}
                                        </a>
                                    </p>
                                    <div style=" clear:both"></div>
                                </div>

                                @includeFirst([config('larapen.core.customizedViewPath') .
                                'layouts.inc.tools.recaptcha', 'layouts.inc.tools.recaptcha'], ['label' => true])
                                <button type="submit" class="btn btn-success btn-login btn-block">{{ t('Log In') }}
                                </button>
                                <br>

                                <a href="<?= url('register') ?>?user_type_id=2"
                                    class="btn btn-block btn btn-success btn-register "
                                    style="color:white;font-size: 14px;"><i class="icon-user"></i>Register as Employee
                                    (Job Seeker)</a>
                                <a href="<?= url('register') ?>?user_type_id=1"
                                    class="btn btn-block btn btn-success btn-register"
                                    style="color:white;font-size: 14px;"><i class="icon-town-hall"></i>Register as
                                    Employer (Company)</a>
                                <input type="hidden" name="quickLoginForm" value="1">
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6 login-background"
                        style="background-image: url('<?= $image_path ?>');background-size: contain">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">{{ t('Close') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('#login_form').on('submit', function(event) {
        event.preventDefault();
        $('#login_form .alret-success').addClass('d-none');
        $('#login_form .alret-danger').addClass('d-none');
        var $btn = $('#loginBtn');
        var $btnText = $btn.find('.btn-text');

        $btn.prop('disabled', true);
        $btnText.html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Login');
        $('#overlay').show();
        // Serialize form data
        var formData = $(this).serialize();

        // Make AJAX request
        $.ajax({
            type: 'POST',
            url: '{{ \App\Helpers\UrlGen::login() }}',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.status === false) {
                    Swal.fire({
                        html: response.message,
                        icon: "error",
                        confirmButtonText: "<u>Ok</u>",
                    });
                    $('#login_form .alert-danger').removeClass('d-none');
                    $('#overlay').hide();
                } else {
                    page_count('login_users');
                    $('#login_form .alert-success').removeClass('d-none');
                    window.location.replace(response.url);
                }
                $btn.prop('disabled', false);
                $btnText.html('Login');
            },
            error: function(error) {
                $('#login_form .alret-danger').removeClass('d-none');
                $btn.prop('disabled', false);
                $btnText.html('Login');
                $('#overlay').hide();
            }
        });
    });
});
$('.modal').on('show.bs.modal', function () {
    $('.modal').not($(this)).each(function () {
        $(this).modal('hide');
    });
});
</script>
