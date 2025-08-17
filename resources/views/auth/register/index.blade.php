@extends('layouts.master')
@section('content')
    @includeFirst([config('larapen.core.customizedViewPath') . 'common.spacer', 'common.spacer'])
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <style>
        #signupForm .form-control {
            border: none;
            box-shadow: none;
            border-bottom: 1px solid #615583 !important;
            border-radius: 0px;
        }

        #signupBtn {
            background: #22d3fd;
            border-color: #22d3fd;
        }

        .icon-color-3,
        .icon-color-2,
        .icon-color-1 {
            color: #22d3fd !important
        }


        .container-redio {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .selector-redio {
            position: relative;
            width: 100%;
            background-color: var(--smoke-white);
            height: 70px;
            display: flex;
            justify-content: space-around;
            align-items: center;
            border-radius: 9999px;
            box-shadow: 0 0 16px rgba(0, 0, 0, .2);
        }

        .selecotr-item {
            position: relative;
            flex-basis: calc(82% / 2);
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 10px
        }

        .selector-item_radio {
            appearance: none;
            display: none;
        }

        .selector-item_label {
            position: relative;
            height: 80%;
            width: 100%;
            text-align: center;
            border-radius: 9999px;
            line-height: 400%;
            font-weight: 900;
            transition-duration: .5s;
            transition-property: transform, color, box-shadow;
            transform: none;
        }

        .selector-item_radio:checked + .selector-item_label {
            background-color: #615583;
            color: var(--white);
            box-shadow: 0 0 4px rgba(0, 0, 0, .5), 0 2px 4px rgba(0, 0, 0, .5);
            transform: translateY(-2px);
        }

        .form-control:focus {
            box-shadow: none !important;
            border: 1px solid #f1f1f1;
            outline: none 0px;
        }

        .form-control {
            border: 1px solid #f1f1f1;
        }

        @media (max-width: 480px) {
            .selector-redio {
                width: 90%;
            }
        }

        .select2-container--default {
            box-sizing: border-box;
            display: inline-block;
            margin: 0;
            position: relative;
            vertical-align: middle;
            border: none;
            border-bottom: 1px solid #615583;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple,
        .select2-container--default .select2-selection--multiple {
            border: none;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background: #615583;
            color: #fff;
        }

        .button_user_type {
            float: left;
            margin: 0 5px 0 0;
            width: 150px;
            height: 57px;
            position: relative;
        }

        .button_user_type label,
        .button_user_type input {
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
        }

        .button_user_type input[type="radio"] {
            opacity: 0.011;
            z-index: 100;
        }

        .button_user_type input[type="radio"]:checked + label {
            background: #20b8be;
            border-radius: 4px;
        }

        .button_user_type label {
            cursor: pointer;
            z-index: 90;
            line-height: 1.8em;
        }

        #signupForm .form-control , .select2-container--default {
            border: 1px solid #f1f1f1 !important;
            border-bottom: 1px solid #f1f1f1 !important;
            border-radius: 6px;
        }

        .custom-select.is-invalid:focus, .form-control.is-invalid:focus, .was-validated .custom-select:invalid:focus, .was-validated .form-control:invalid:focus {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 .2rem rgba(220, 53, 69, .25) !important;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-5px); }
            40%, 80% { transform: translateX(5px); }
        }

        .shake {
            animation: shake 0.4s;
        }

        @media (max-width: 700px) {
            .btn-register {
                max-width: 100% !important;
            }
            .form-group {
                margin-bottom: 0 !important;
            }
        }

    </style>
    <?php
    $country_select = !empty(request()->get('country')) ? request()->get('country') : '';
    $user_type_id_select = !empty(request()->get('user_type_id')) ? request()->get('user_type_id') : '';
    ?>
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
                                        <?php $errorMessage .= '<li>' . $error . '</li>'; ?>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @if (!empty($errorMessage))
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
                            <div class="col-lg-12">
                                @include('flash::message')
                            </div>
                        </div>
                    </div>
                @endif
                <div class="col-md-8 page-content">
                    <div class="inner-box">
                        <h2 class="title-2 mb-2">
                            @if($user_type_id_select==2)
                                <strong><i class="icon-user-add"></i> {{ t('Register for FREE and apply to jobs now!') }}
                                </strong>
                            @else
                                <strong><i class="icon-user-add"></i> {{ t('Register as a Company for FREE and start posting jobs with us!') }}
                                </strong>
                            @endif
                        </h2>
                        @if($user_type_id_select==1)
                            <div class="row">
                                <div class="col-md-12">
                                    <center>
                                        <a href="<?= url('register') ?>?user_type_id=2"
                                           class="btn btn-block btn btn-success btn-register "
                                           style="color:white;font-size: 14px;max-width:60%;margin-bottom:10px;"><i class="icon-user"></i>Register
                                            as Employee
                                            (Job Seeker)</a>
                                    </center>
                                </div>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-xl-12">
                                <form id="signupForm" class="form-horizontal" method="POST" action="" enctype="multipart/form-data" novalidate>
                                    {!! csrf_field() !!}
                                    <fieldset>
                                        <div id="check_user_type" style="display:none">
                                            <input type="hidden" name="user_type_id" id="user_type_id" value="<?= $user_type_id_select ?>">
                                            <?php $nameError = (isset($errors) and $errors->has('name')) ? ' is-invalid' : ''; ?>
                                            <div class="form-group row required">
                                                <label class="col-md-4 col-form-label usertypename">{{ t('Name') }}
                                                    <sup>*</sup></label>
                                                <div class="col-md-6">
                                                    <input name="name" id="registerName" placeholder="{{ t('Name') }}" class="form-control input-md{{ $nameError }}" type="text" required value="{{ old('name') }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                @if ($user_type_id_select == '1')
                                                    <label class="col-md-4 col-form-label">{{ t('Company Logo') }}
                                                        <sup>*</sup></label>
                                                @else
                                                    <label class="col-md-4 col-form-label">{{ t('Profile Image') }}
                                                        <sup>*</sup></label>
                                                @endif
                                                <div class="col-md-6">
                                                    <input type="file" id="file" name="file" required class="file-input form-control"
                                                           accept="image/png, image/gif, image/jpeg">
                                                </div>
                                            </div>
                                            @if ($user_type_id_select == '2')
                                                <div class="form-group row">
                                                    <label class="col-md-4 col-form-label">{{ t('CV') }}
                                                        <sup>*</sup></label>
                                                    <div class="col-md-6">
                                                        <input type="file" id="cv" name="cv" class="file-input form-control" required accept="application/pdf">
                                                        <p class="text-danger">CV has to be a pdf file</p>
                                                    </div>
                                                </div>
                                            @endif
                                            @if (empty(config('country.code')))
                                                    <?php $countryCodeError = (isset($errors) and $errors->has('country_code')) ? ' is-invalid' : ''; ?>
                                                <div class="form-group row required">
                                                    <label class="col-md-4 col-form-label{{ $countryCodeError }}"
                                                           for="country_code">{{ t('Your Country') }}
                                                        <sup>*</sup></label>
                                                    <div class="col-md-6">
                                                        <select id="countryCode" name="country_code"
                                                                class="form-control sselecter{{ $countryCodeError }} global-select"
                                                                required>
                                                            <option value="0"
                                                                    {{ (!old(
                                                            'country_code') or old('country_code') == 0) ? 'selected="selected"' : '' }}>
                                                                {{ t('Select') }}</option>
                                                            @foreach ($countries as $code => $item)
                                                                <option value="{{ $code }}"
                                                                        {{ old(
                                                                'country_code', !empty(config('ipCountry.code')) ? config('ipCountry.code') : 0) == $code
                                                                ? 'selected="selected"'
                                                                : '' }}>
                                                                    {{ $item->get('name') }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @else
                                                <input id="countryCode" name="country_code" type="hidden"
                                                       value="{{ config('country.code') }}">
                                            @endif
                                            <?php $phoneError = (isset($errors) and $errors->has('phone')) ? ' is-invalid' : ''; ?>
                                            @if ($user_type_id_select == '1')
                                                <div class="form-group row">
                                                    <label class="col-md-4 col-form-label">{{ t('Phone') }}
                                                        <sup>*</sup>
                                                    </label>
                                                        <?php $countryCodeString = str_replace('+', '', config('country.phone')); ?>
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span id="phoneCountry" style="color:#474b51 !important"
                                                                      class="input-group-text">{!! getPhoneIcon(old('country', config('country.code'))) !!}
                                                                    +{{ $countryCodeString }}</span>
                                                            </div>
                                                            <input name="phone" pattern="[-+]?\d*" id="phone_number"
                                                                   placeholder="{{ t('Phone') }}"
                                                                   class="form-control input-md{{ $phoneError }}"
                                                                   oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                                                   type="text" minlength="8" value="{{ old('phone') }}"
                                                                   required>
                                                        </div>
                                                        <p class="text-danger">Phone number will be hidden</p>
                                                    </div>
                                                </div>
                                            @endif
                                            <?php $emailError = (isset($errors) and $errors->has('email')) ? ' is-invalid' : ''; ?>
                                            <div class="form-group row required">
                                                <label class="col-md-4 col-form-label" for="email">{{ t('Email') }}<sup>*</sup>
                                                </label>
                                                <div class="col-md-6">
                                                    <input id="email" name="email" type="email" required
                                                           class="form-control{{ $emailError }}"
                                                           placeholder="{{ t('Email') }}"
                                                           value="{{ old('email') }}">

                                                    @if ($user_type_id_select == '1')
                                                        <p class="text-danger">Email will be hidden</p>
                                                    @endif
                                                </div>
                                            </div>
                                            @if ($user_type_id_select == '2')
                                                <div class="form-group row">
                                                    <label class="col-md-4 col-form-label">{{ t('skill Set') }}
                                                        <sup>*</sup>
                                                    </label>
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <select class="skill_set_entities global-select" multiple
                                                                    name="skill_set[]"
                                                                    required max="5">
                                                                @foreach ($employee_skills as $item)
                                                                    <option value="{{ $item->skill }}">
                                                                        {{ $item->skill }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <p class="text-danger  m-0">You can choose more than one</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            @if ($user_type_id_select == '1')
                                                <div class="form-group row required">
                                                    <label class="col-md-4 col-form-label"
                                                           for="email">{{ t('Entities') }}
                                                        <sup>*</sup>
                                                    </label>
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <select class="skill_set_entities form-control global-select"
                                                                    required
                                                                    multiple name="entities[]">
                                                                @foreach ($entities as $item)
                                                                    <option value="{{ $item->name }}">
                                                                        {{ $item->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <p class="text-danger m-0">You can choose more than one</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            @if ($user_type_id_select == '2')
                                                <div class="form-group row">
                                                    <label class="col-md-4 col-form-label"
                                                           for="email">{{ t('availability') }}
                                                        <sup>*</sup>
                                                    </label>
                                                    <div class="col-md-6">
                                                        <select class="form-control global-select"
                                                                name="availability" required>
                                                            @if ($availability)
                                                                @foreach ($availability as $item)
                                                                    <option value="{{ $item->id }}">
                                                                        {{ $item->name }}
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="form-group row">
                                                <label class="col-md-4 col-form-label"
                                                       for="email">{{ t('city_Of_Where_You_live_Right_Now') }}
                                                    <sup>*</sup>
                                                </label>
                                                <div class="col-md-6">
                                                    <select class="form-control global-select" name="city_id" required>
                                                        @foreach ($cities as $item)
                                                            <option value="{{ $item->id }}">{{ $item->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            @if ($user_type_id_select == '2')
                                                <div class="form-group row required experiences">
                                                    <label class="col-md-4 col-form-label"
                                                           for="experience">{{ t('Work Experience') }} <sup
                                                                style="color:red">*</sup></label>
                                                    <div class="col-md-6">
                                                        <select id="experiences1" name="experiences"
                                                                class="form-control global-select"
                                                                required>
                                                            <option value="">{{ t('select experiences') }}</option>
                                                            <option value="0-1 years">{{ t('0-1 years') }}</option>
                                                            <option value="1-3 years">{{ t('1-3 years') }}</option>
                                                            <option value="3-5 years">{{ t('3-5 years') }}</option>
                                                            <option value="5-10 years">{{ t('5-10 years') }}</option>
                                                            <option value="10-20 years">{{ t('10-20 years') }}</option>
                                                            <option value="20+ years">{{ t('20+ years') }}</option>

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group row required nationality">
                                                    <label class="col-md-4 col-form-label"
                                                           for="nationality">{{ t('Nationality') }} <sup
                                                                style="color:red">*</sup></label>
                                                    <div class="col-md-6">
                                                        <select class="form-control global-select" name="nationality"
                                                                required>
                                                            <option value="">{{t('Choose Your Nationality')}}</option>
                                                            @if ($nationality)
                                                                @foreach ($nationality as $key => $value)
                                                                    @if($key !== 'Any')
                                                                        <option value="{{ $value }}">
                                                                            {{ $key }}
                                                                        </option>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>

                                                @if(config('country.code') === 'KW')

                                                    <div class="form-group row required visa">
                                                        <label class="col-md-4 col-form-label"
                                                               for="visa">{{ t('Work Visa') }} <sup
                                                                    style="color:red">*</sup></label>
                                                        <div class="col-md-6">
                                                            <select id="visa" name="visa"
                                                                    class="form-control global-select" required
                                                                    onchange="show_visa_box(this)">
                                                                <option value="">{{ t('Choose an Option') }}</option>
                                                                <option value="No, I don’t NEED a visa">{{ t("No, I don’t NEED a visa") }}</option>
                                                                <option value="No, I don’t HAVE a visa">{{ t("No, I don’t HAVE a visa") }}</option>
                                                                <option value="Yes, I HAVE a visa">{{ t('Yes, I HAVE a visa') }}</option>

                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row required visa country_of_work_visa_div"
                                                         id="country_of_work_visa_div" style="display:none">
                                                        <label class="col-md-4 col-form-label"
                                                               for="country_of_work_visa_div">{{ t('Country of Work Visa') }}
                                                            <sup style="color:red">*</sup></label>
                                                        <div class="col-md-6">
                                                            <select class="form-control global-select"
                                                                    name="country_work_visa" id="country_work_visa"
                                                                    onchange="work_visa_country(this)">

                                                                @if(!empty($countries))
                                                                    <option value="">Choose a Country</option>
                                                                    @foreach ($countries as $item)
                                                                        <option value="{{ $item->code }}">{{ $item->name }}
                                                                        </option>
                                                                    @endforeach
                                                                @endif
                                                            </select>

                                                        </div>
                                                    </div>
                                                    <div class="form-group row required  visa_number_div"
                                                         id="visa_number_div" style="display:none">
                                                        <label class="col-md-4 col-form-label"
                                                               for="visa">{{ t('Work Visa Type') }} <sup
                                                                    style="color:red">*</sup></label>
                                                        <div class="col-md-6">
                                                            <select id="work_visa_number" name="visa_number"
                                                                    class="form-control global-select">
                                                                <option value="">{{ t('Choose an Option') }}</option>
                                                                    <?php foreach ($visa_types as $visa) : ?>
                                                                    <?php foreach ($visa as $value => $label) : ?>
                                                                <option value="<?php echo $value; ?>"><?php echo $label; ?></option>
                                                                <?php endforeach; ?>
                                                                <?php endforeach; ?>

                                                            </select>

                                                        </div>
                                                    </div>
                                                @endif

                                            @endif
                                            <?php $passwordError = (isset($errors) and $errors->has('password')) ? ' is-invalid' : ''; ?>
                                            <div class="form-group row required">
                                                <label class="col-md-4 col-form-label"
                                                       for="password">{{ t('Password') }}
                                                    <sup>*</sup></label>
                                                <div class="col-md-6">
                                                    <div class="input-group show-pwd-group">
                                                        <input id="password_1" name="password" minlength="8"
                                                               type="password" class="form-control{{ $passwordError }}"
                                                               placeholder="{{ t('Password') }}" autocomplete="off"
                                                               required>
                                                        <span class="icon-append show-pwd">
                                                            <button type="button" class="eyeOfPwd" onclick="showPwd()">
                                                                <i id="eyeIcon" class="far fa-eye-slash"
                                                                   style="color:#474b51"></i>
                                                            </button>
                                                        </span>
                                                    </div>
                                                    <br>
                                                    <div class="input-group show-pwd-group">
                                                        <input id="password_confirmation" name="password_confirmation"
                                                               type="password" class="form-control{{ $passwordError }}"
                                                               placeholder="{{ t('Password Confirmation') }}"
                                                               autocomplete="off"
                                                               minlength="8" required>
                                                        <span class="icon-append show-pwd">
                                                        <button type="button" class="eyeOfPwd"
                                                                onclick="showconfirmdPwd()">
                                                            <i id="eyeIcon2" class="far fa-eye-slash"
                                                               style="color:#474b51"></i>
                                                        </button>
                                                    </span>
                                                    </div>
                                                    <small id="" class="form-text text-muted">
                                                        {{ t('At least X characters', ['num' => config('larapen.core.passwordLength.min', 6)]) }}
                                                    </small>
                                                </div>
                                            </div>

                                            {{-- referral_code --}}
                                            @if(!empty($referral_code))
                                                <div class="form-group row">
                                                    <label class="col-md-4 col-form-label"
                                                           for="email">{{ t('referred_by') }}</label>
                                                    <div class="col-md-6">
                                                        <input type="hidden" class="form-control" name="referral_code"
                                                               value="{{ ($referral_code)?$referral_code:old('referral_code') }}">

                                                        <input id="refer_by" name="refer_by" type="text"
                                                               class="form-control"
                                                               placeholder="{{ t('referred_by') }}"
                                                               value="{{ ($refer_by)?$refer_by:old('refer_by') }}"
                                                               readonly>
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- referral_code --}}

                                            @if (config('larapen.core.register.showCompanyFields'))
                                                <div id="companyBloc">
                                                    <div class="content-subheading">
                                                        <i class="icon-town-hall fa"></i>
                                                        <strong>{{ t('Company Information') }}</strong>
                                                    </div>

                                                    @includeFirst(
                                                    [
                                                    config('larapen.core.customizedViewPath') .
                                                    'account.company._form',
                                                    'account.company._form',
                                                    ],
                                                    ['originForm' => 'user']
                                                    )
                                                </div>
                                            @endif

                                            @if (config('larapen.core.register.showResumeFields'))
                                                <div id="resumeBloc">
                                                    <div class="content-subheading">
                                                        <i class="icon-attach fa"></i>
                                                        <strong>{{ t('Resume') }}</strong>
                                                    </div>

                                                    @includeFirst(
                                                    [
                                                    config('larapen.core.customizedViewPath') .
                                                    'account.resume._form',
                                                    'account.resume._form',
                                                    ],
                                                    ['originForm' => 'user']
                                                    )
                                                </div>
                                            @endif

                                            @includeFirst(
                                            [
                                            config('larapen.core.customizedViewPath') .
                                            'layouts.inc.tools.recaptcha',
                                            'layouts.inc.tools.recaptcha',
                                            ],
                                            ['colLeft' => 'col-md-4', 'colRight' => 'col-md-6']
                                            )

                                            <!-- accept_terms -->
                                            <?php $acceptTermsError = (isset($errors) and $errors->has('accept_terms')) ? ' is-invalid' : ''; ?>
                                            <div class="form-group row required">
                                                <label class="col-md-4 col-form-label"></label>
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input name="accept_terms" id="acceptTerms"
                                                               class="form-check-input{{ $acceptTermsError }}" required
                                                               value="1" type="checkbox"
                                                                {{ old('accept_terms') == '1' ? 'checked="checked"' : '' }}>

                                                        <label class="form-check-label" for="acceptTerms"
                                                               style="font-weight: normal;">
                                                            {!! t('accept_terms_label', ['attributes' => getUrlPageByType('terms')]) !!}
                                                        </label>
                                                    </div>
                                                    <div style="clear:both"></div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-4 col-form-label"></label>
                                                <div class="col-md-8">
                                                    <button id="signupBtn" class="btn btn-success btn-lg">
                                                        <span class="btn-text">{{ t('Register') }}</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div style="margin-bottom: 30px;"></div>

                                    </fieldset>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 reg-sidebar">
                    <div class="reg-sidebar-inner text-center">
                        <div class="promo-text-box"><i class=" icon-picture fa fa-4x icon-color-1"></i>
                            <h3><strong>{{ t('Post a Job') }}</strong></h3>
                            <p>
                                {{ t('Do you have a post to be filled within your company', ['appName' => config('app.name')]) }}
                            </p>
                        </div>
                        <div class="promo-text-box"><i class="icon-pencil-circled fa fa-4x icon-color-2"></i>
                            <h3><strong>{{ t('Create and Manage Jobs') }}</strong></h3>
                            <p>{{ t('become_a_best_company_text') }}</p>
                        </div>
                        <div class="promo-text-box"><i class="icon-heart-2 fa fa-4x icon-color-3"></i>
                            <h3><strong>{{ t('create_your_favorite_jobs_list') }}</strong></h3>
                            <p>{{ t('create_your_favorite_jobs_list_text') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Change Country -->
    <div class="modal fade modalHasList" id="selectCountry" data-backdrop="static" role="dialog"
         aria-labelledby="selectCountryLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title"><i
                                class="icon-location-2"></i> {{ t('Select A Country - Where Do You Live Right Now?') }}
                    </div>
                </div>
                <?php $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>
                <div class="modal-body">
                    <input type="text" id="countrySearchInput" class="form-control mb-3" placeholder="Search country...">
                    <div class="row" id="countryListContainer">
                        @if (isset($countryCols))
                            @foreach ($countryCols as $key => $col)
                                @foreach ($col as $k => $country)
                                    <div class="cat-list col-6 col-xl-3 col-lg-3 col-md-4 mb-1">
                                        <a rel="nofollow" href="{{ url('register_session?user_type_id=' . $user_type_id_select . '&d=' . $country['code'] . '&referral_code=' . $referral_code) }}" class="tooltip-test p-2 bg-light d-block country-link" title="{{ $country->get('name') }}">
                                            <img alt="{{ $country->get('name') }}" src="{{ url()->asset('images/blank.gif') . getPictureVersion() }}" class="flag flag-{{ $country->get('icode') == 'uk' ? 'gb' : $country->get('icode') }}">
                                            {{ \Illuminate\Support\Str::limit($country->get('name'), 28) }}
                                        </a>
                                    </div>
                                @endforeach
                            @endforeach
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
    <!-- /.modal -->

@endsection

@section('after_styles')
    <link href="{{ url()->asset('plugins/bootstrap-fileinput/css/fileinput.min.css') }}" rel="stylesheet">
    @if (config('lang.direction') == 'rtl')
        <link href="{{ url()->asset('plugins/bootstrap-fileinput/css/fileinput-rtl.min.css') }}" rel="stylesheet">
    @endif
    <style>
        .krajee-default.file-preview-frame:hover:not(.file-preview-error) {
            box-shadow: 0 0 5px 0 #666666;
        }
    </style>
@endsection

@section('after_scripts')

    <script>
        $(document).ready(function () {
            $('#countrySearchInput').on('keyup', function () {
                var searchText = $(this).val().toLowerCase();
                $('#countryListContainer .country-link').each(function () {
                    var countryName = $(this).attr('title').toLowerCase();
                    if (countryName.indexOf(searchText) > -1) {
                        $(this).closest('.cat-list').show();
                    } else {
                        $(this).closest('.cat-list').hide();
                    }
                });
            });
        });

        function show_visa_box(obj) {
            removeRequired();
            $('#work_visa_number').prop('required', false);
            var visa = $(obj).val();
            if (visa == 'Yes, I HAVE a visa') {
                $('.country_of_work_visa_div').show();
                $('#country_work_visa').val('KW').trigger('change');

                addRequired();
            } else {
                $('.visa_number_div').hide();
                $('.country_of_work_visa_div').hide();
                removeRequired();
            }
        }

        function work_visa_country(obj) {
            var country = $(obj).val();
            if (country == 'KW') {
                $('.visa_number_div').show();
                $('#work_visa_number').prop('required', true);
            } else {
                $('.visa_number_div').hide();
                $('#work_visa_number').prop('required', false);
                $('#work_visa_number').val('').trigger('change');

            }
        }

        // Function to add the "required" attribute to input elements
        function addRequired() {
            $('#country_work_visa').prop('required', true);
        }

        // Function to remove the "required" attribute from input elements
        function removeRequired() {
            $('#country_work_visa').prop('required', false);
            $('#country_work_visa').val('').trigger('change');
            $('#work_visa_number').val('').trigger('change');
        }


        $(document).ready(function () {
            $('#signupForm').on('submit', function (event) {
                event.preventDefault();
                var isValid = true;
                $(this).find('[required]').each(function () {
                    if (!$(this).val()) {
                        isValid = false;
                        $(this).addClass('is-invalid');
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });

                if (!isValid) {
                    const $firstInvalid = $(this).find('.is-invalid').first();
                    const $target = $firstInvalid.hasClass('select2-hidden-accessible')
                        ? $firstInvalid.next('.select2') // Get the Select2 container
                        : $firstInvalid;

                    Swal.fire({
                        title: "OOPS!",
                        text: "Please fill all required fields.",
                        icon: "error",
                        confirmButtonText: "OK"
                    }).then(() => {
                        $('html, body').animate({
                            scrollTop: $target.offset().top - 200
                        }, 500, 'swing', function () {
                            // Trigger dropdown open (optional)
                            if ($firstInvalid.hasClass('select2-hidden-accessible')) {
                                $firstInvalid.select2('open');
                            } else {
                                $firstInvalid.focus();
                            }
                            $firstInvalid.addClass('shake');
                            setTimeout(() => {
                                $firstInvalid.removeClass('shake');
                            }, 400);
                        });
                    });
                    return;
                }
                var fileInput = $('#file')[0];
                var file = fileInput.files[0];
                var $btn = $('#signupBtn');
                var $btnText = $btn.find('.btn-text');

                if (!file) {
                    Swal.fire({
                        title: "OOPS!",
                        text: "Please Upload Profile Image",
                        icon: "error",
                        confirmButtonText: "Ok",
                    });
                    return;
                }

                var user_type_id = {{$user_type_id_select}};
                if (user_type_id == 2) {
                    var fileInputcv = $('#cv')[0];
                    var fileInputcv = fileInputcv.files[0];
                    if (!fileInputcv) {
                        Swal.fire({
                            title: "OOPS!",
                            text: "Please Upload You Cv",
                            icon: "error",
                            confirmButtonText: "Ok",
                        });
                        return;
                    }
                }

                var formData = new FormData(this);
                $btn.prop('disabled', true);
                $btnText.html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Register');
                //    $('#overlay').show();
                // Make AJAX request
                $.ajax({
                    type: 'POST',
                    url: '<?= url()->current() ?>',
                    data: formData,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function (response) {

                        // Handle the response after a successful submission
                        if (response.status == false) {
                            $btn.prop('disabled', false);
                            $btnText.html('Register');
                            //    $('#overlay').hide();
                            Swal.fire({
                                title: "OOPS!",
                                text: response.message,
                                icon: "error",
                                button: "Ok",
                            });
                        } else {
                            window.location.replace(response.url);
                        }

                    },
                    error: function (error) {
                        //    $('#overlay').hide();
                        $btn.prop('disabled', false);
                        $btnText.html('Register');
                        var errors = error.responseJSON.data;
                        var errorList = "<ul>";
                        $.each(errors, function (key, value) {
                            errorList += "<li>" + value + "</li>";
                        });
                        errorList += "</ul>";
                        // Handle the error if the submission fails
                        Swal.fire({
                            title: "OOPS!",
                            html: true,
                            html: errorList,
                            icon: "error",
                            button: "Ok",
                        });


                    }
                });
            });

        });

        $(".skill_set_entities").select2({
            maximumSelectionLength: 5,
            width: "100%"
        });

        $(".global-select").select2({
            width: "100%",
            maximumSelectionLength: 5,
        });

        $(".skill_set").select2({
            width: '100%',
            maximumSelectionLength: 5,
            placeholder: '<?= t('Employee Skills(you can choose more than one)') ?>',
        });


        $(".skill_set_cuisines").select2({
            maximumSelectionLength: 5,
            width: "100%"
        });
        $('#phone_number').keyup(function (e) {
            if (/\D/g.test(this.value)) {
                this.value = this.value.replace(/\D/g, '');
                this.value = '+' + this.value;
            }
        });
    </script>

    <script>

        $(document).ready(function () {

            var token = '<?php echo $country_select; ?>';
            if (token == '' || !token) {
                $('#selectCountry').modal('show');
            }
            page_count('register_page');
        });
    </script>
@endsection