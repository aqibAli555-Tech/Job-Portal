@extends('layouts.master')

@section('search')
    @parent
    @include('pages.inc.contact-intro')
@endsection

@section('content')
    @include('common.spacer')
    <script src='https://www.hCaptcha.com/1/api.js' async defer></script>
    <div class="main-container">
        <div class="container">
            <div class="row clearfix">
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
                                html: '<?= $errorMessage ?>',
                                icon: "error",
                                confirmButtonText: "<u>Ok</u>",
                            });
                        </script>
                    @endif
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

                <div class="col-md-12 ">
                    <div style="background-color: white; border-radius: 10px; padding: 15px;">
                        <div class="contact-form">
                            <h2 class="list-title gray mt-0">
                                <b>{{ t('Contact Us') }}</b>
                            </h2>

                            <form class="form-horizontal" method="post" action="{{ \App\Helpers\UrlGen::contact() }}">
                                {!! csrf_field() !!}
                                <fieldset>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?php $firstNameError = (isset($errors) and $errors->has('first_name')) ? ' is-invalid' : ''; ?>
                                            <div class="form-group required">
                                                <input id="first_name" name="first_name" type="text" required
                                                       placeholder="{{ t('First Name') }}"
                                                       class="form-control{{ $firstNameError }}"
                                                       value="{{ old('first_name') }}">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <?php $lastNameError = (isset($errors) and $errors->has('last_name')) ? ' is-invalid' : ''; ?>
                                            <div class="form-group required">
                                                <input id="last_name" name="last_name" type="text" required
                                                       placeholder="{{ t('Last Name') }}"
                                                       class="form-control{{ $lastNameError }}"
                                                       value="{{ old('last_name') }}">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <?php $companyNameError = (isset($errors) and $errors->has('company_name')) ? ' is-invalid' : ''; ?>

                                            <div class="form-group required">

                                                <div class="input-group-prepend" style="height: 38px;">
                                                    <input style="max-width: 70px;" readonly class="input-group-text"
                                                           type="text" name="countryCode"
                                                           value="<?= '+' . config('country.phone') ?>">
                                                    <input id="phone" name="phone" type="text" required
                                                           onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"
                                                           placeholder="{{ t('Phone Number') }} "
                                                           class="form-control{{ $companyNameError }}"
                                                           value="" maxlength="11" minlength="7">
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-md-6">
                                            <?php $emailError = (isset($errors) and $errors->has('email')) ? ' is-invalid' : ''; ?>
                                            <div class="form-group required">
                                                <input id="email" name="email" type="text" required
                                                       placeholder="{{ t('Email') }}"
                                                       class="form-control{{ $emailError }}"
                                                       value="{{ old('email') }}">
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <label class="">{{t('Are you')}}:</label>&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input type="radio" name="user_type" id="user_type_id-1"
                                                   class="" checked value="Employee">
                                            <label class="" for="user_type_id-1">{{t('Employee (Job seeker)')}}</label>&nbsp;&nbsp;<b>{{t('OR')}}</b>&nbsp;&nbsp;
                                            <input type="radio" name="user_type" id="user_type_id-2"
                                                   class="" value="Employer">
                                            <label class="" for="user_type_id-2">{{t('Employer')}}</label>
                                        </div>

                                        <div class="col-md-12">
                                            <?php $messageError = (isset($errors) and $errors->has('message')) ? ' is-invalid' : ''; ?>
                                            <div class="form-group required">
                                            <textarea class="form-control{{ $messageError }}" id="message" required
                                                      name="message" placeholder="{{ t('Message') }}"
                                                      rows="7">{{ old('message') }}</textarea>
                                            </div>
                                            <div class="h-captcha"
                                                 data-sitekey="91a560b1-b25f-450a-8fd7-b8c7e0c2575e"></div>


                                            @includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.tools.recaptcha', 'layouts.inc.tools.recaptcha'], ['label' => true])

                                            <div class="form-group">
                                                <button type="submit"
                                                        class="btn btn-primary btn-lg">{{ t('submit') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('after_scripts')
    <script>
        window.onload = function () {
            var $recaptcha = document.querySelector('#g-recaptcha-response');

            if ($recaptcha) {
                $recaptcha.setAttribute("required", "required");
            }
        };
    </script>

    <script src="{{ url()->asset('js/form-validation.js') }}"></script>
@endsection
