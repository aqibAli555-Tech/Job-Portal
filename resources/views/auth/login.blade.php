@extends('auth.layouts.master')
@section('content')
<?php
$image_path = url("storage/app/public/Login_popup_new.jpeg");
?>

<style>
    #wrapper, #main-content-div, #main-content-div > .row {
        height: 100%;
    }
    .login-background {
        background-image: url('<?= $image_path ?>');
        background-size: cover;
        background-repeat: no-repeat;
        min-height: 470px;
        display: block !important;
    }
    
    @media screen and (max-width: 768px) {
        .quickLoginLeft {
            padding-top: 50px !important;
        }
    }

</style>
<div class="row">
    <div class="col-md-6 login-background"></div>
    <div class="col-md-6">
        <div class="main-container">
            <div class="container">
                <form role="form" id="login_form" method="POST" action="">
                    {!! csrf_field() !!}
                    <div class="quickLoginLeft">
                        <h4><b>Sign in to Hungry For Jobs</b></h4>
                        <div class="form-group">
                            <label for="email" class="control-label">{{ t('Email') }}</label>
                            <div class="input-group">
                                <input id="email" name="login" value="{{ old('email') }}" class="form-control" required type="email" placeholder="Email address">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="control-label">{{ t('Password') }}</label>
                            <div class="input-group show-pwd-group">
                                <input id="password" name="password" class="form-control" type="password" placeholder="Password" autocomplete="off" required>
                                <span class="icon-append show-pwd">
                                    <button type="button" class="eyeOfPwd" onclick="showPwd()">
                                        <i id="eyeIcon" class="far fa-eye-slash" style="color:#474b51"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="checkbox form-check-label pull-left mt-2" style="font-weight: normal;">
                                <input type="checkbox" value="1" name="remember" id="mRemember"> {{ t('Keep me logged in') }}
                            </label>
                            <p class="pull-right mt-2">
                                <a href="{{ url('password/reset') }}" style="color:#615583">
                                    {{ t('Lost your password') }}
                                </a>
                            </p>
                            <div style=" clear:both"></div>
                        </div>
                        <button id="loginBtn" type="submit" class="btn btn-primary btn-login btn-block" style="font-weight: bold;">
                            <span class="btn-text">{{ t('Login') }}</span>
                        </button>                        
                        <br>

                        <div class="form-group mt-2">
                            <p class="pull-left mt-2">
                                {{ t('Don’t have an account with Hungry for Jobs?') }}
                            </p>
                            <a href="<?= url('registration') ?>" class="btn btn-success btn-register pull-right" style="color:white; font-size: 12px; font-weight: bold; padding: 5px 10px; float: right;">
                                Register Here
                            </a>
                            <div style=" clear:both"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('after_scripts')
<script>
    $(document).ready(function () {
        $('#login_form').on('submit', function (event) {
            $('#login_form .alret-success').addClass('d-none');
            $('#login_form .alret-danger').addClass('d-none');
            event.preventDefault();
            $('#overlay').show();
            // Serialize form data
            var formData = $(this).serialize();

            // Make AJAX request
            $.ajax({
                type: 'POST',
                url: '{{ \App\Helpers\UrlGen::login() }}',
                data: formData,
                dataType: 'json',
                success: function (response) {
                    $('#overlay').hide();
                    if (response.status === false) {
                        Swal.fire({
                            html: response.message,
                            icon: "error",
                            confirmButtonText: "<u>Ok</u>",
                        });
                        $('#login_form .alert-danger').removeClass('d-none');
                    } else {
                        $('#login_form .alert-success').removeClass('d-none');
                        window.location.replace(response.url);
                    }
                },
                error: function (error) {
                    $('#login_form .alret-danger').removeClass('d-none');
                    $('#overlay').hide();
                }
            });
        });
    });
</script>
@endsection