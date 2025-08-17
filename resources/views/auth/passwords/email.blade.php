
@extends('layouts.master')

@section('content')
@includeFirst([config('larapen.core.customizedViewPath') . 'common.spacer', 'common.spacer'])
<div class="main-container">
    <div class="container">
        <div class="row">

            @if (isset($errors) and $errors->any())
            <?php $errorMessage = ''; ?>
            <div class="col-xl-12" style="display:none;">
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><strong>{{ t('oops_an_error_has_occurred') }}</strong></h5>
                    <ul class="list list-check">
                        @foreach ($errors->all() as $error)
                        <?php $errorMessage .= "<li>" . $error . "</li>" ?>
                        @endforeach
                    </ul>
                </div>
            </div>
            @if(!empty($errorMessage))
            <script>
                Swal.fire({
                    html: "<?=$errorMessage?>",
                    icon: "error",
                    confirmButtonText: "<u>Ok</u>",
                });
            </script>
            @endif
            @endif

            @if (session('status'))
            <div class="col-xl-12">
                <div class="">
                    {{--
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    --}}
                    {{-- <p>{{ session('status') }}</p>--}}
                    <script>
                        Swal.fire({
                            html: "<?= session('status')?>",
                            icon: "success",
                            confirmButtonText: "<u>Ok</u>",
                        });
                    </script>
                </div>
            </div>
            @endif

            @if (session('email'))
            <div class="col-xl-12">
                <div class="">
                    {{--
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    --}}
                    {{-- <p>{{ session('email') }}</p>--}}
                    <script>
                        Swal.fire({
                            html: "<?= session('email')?>",
                            icon: "danger",
                            confirmButtonText: "<u>Ok</u>",
                        });
                    </script>
                </div>
            </div>
            @endif

            @if (session('phone'))
            <div class="col-xl-12">
                <div class="">
                    {{--
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    --}}
                    {{-- <p>{{ session('phone') }}</p>--}}
                    <script>
                        Swal.fire({
                            html: "<?= session('phone')?>",
                            icon: "danger",
                            confirmButtonText: "<u>Ok</u>",
                        });
                    </script>
                </div>
            </div>
            @endif

            @if (session('login'))
            <div class="col-xl-12">
                <div class="">
                    {{--
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    --}}
                    {{-- <p>{{ session('login') }}</p>--}}
                    <script>
                        Swal.fire({
                            html: "<?= session('login')?>",
                            icon: "danger",
                            confirmButtonText: "<u>Ok</u>",
                        });
                    </script>
                </div>
            </div>
            @endif

            @if (Session::has('flash_notification'))
            <div class="col-xl-12">
                <div class="row">
                    <div class="col-xl-12">
                        @include('flash::message')
                    </div>
                </div>
            </div>
            @endif

            <div class="col-lg-5 col-md-8 col-sm-10 col-xs-12 login-box">
                <div class="card card-default">
                    <div class="panel-intro text-center">
                        <h2 class="logo-title">
                            <span class="logo-icon"> </span> {{ t('Password') }} <span> </span>
                        </h2>
                    </div>

                    <div class="card-body">
                        <form id="pwdForm" role="form" method="POST" action="{{ url('send-reset-password-email') }}">
                            {!! csrf_field() !!}

                            <!-- login -->
                            <?php $loginError = (isset($errors) and $errors->has('login')) ? ' is-invalid' : ''; ?>
                            <div class="form-group">
                                <label for="login" class="col-form-label">{{ t('Login') . ' (' . getLoginLabel() . ')' }}:</label>
                                <div class="input-icon">
                                    <i class="icon-user fa"></i>
                                    <input id="login"
                                           name="login"
                                           type="text"
                                           placeholder="{{ getLoginLabel() }}"
                                           class="form-control{{ $loginError }}"
                                           value="{{ old('login') }}"
                                           required
                                    >
                                </div>
                            </div>

                            @includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.tools.recaptcha', 'layouts.inc.tools.recaptcha'], ['noLabel' => true])

                            <!-- Submit -->
                            <div class="form-group">
                                <button id="pwdBtn" type="submit" class="btn btn-primary btn-lg btn-block">{{ t('submit') }}</button>
                            </div>
                        </form>
                    </div>

                    <div class="card-footer text-center">
                        <a href="{{ \App\Helpers\UrlGen::login() }}"> {{ t('Back to the Log In page') }} </a>
                    </div>
                </div>
                <div class="login-box-btm text-center">
                    <p>
                        {{ t('Do not have an account') }} <br>
                        <a href="<?= url('registration') ?>"><strong>{{ t('sign_up_') }}</strong></a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('after_scripts')
<script>
    $(document).ready(function () {
        $("#pwdBtn").click(function () {
            $("#pwdForm").submit();
            return false;
        });
    });
</script>
@endsection
