@extends('affiliate.layouts.master')
@section('content')
    @include('common.spacer')

    <div class="main-container">
        <div class="container">
            @include('affiliate/inc/breadcrumbs')
            <div class="row">
                <div class="col-md-3 page-sidebar">
                    @include('affiliate.inc.sidebar')
                </div>
                <div class="col-md-9 page-content">
                    @include('flash::message')
                    @if (isset($errors) and $errors->any())
                            <?php $errorMessage = '' ?>
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

                    <div class="inner-box default-inner-box">
                        <div class="row">
                            <div class="col-md-5 col-xs-4 col-xxs-12">
                                <h3 class="no-padding text-center-480 useradmin">
                                    <a href="">
                                        <img class="userImg" src="{{  \App\Helpers\Helper::getImageOrThumbnailLink($user); }}"
                                             alt="user">&nbsp;
                                        {{ !empty($user->name)?$user->name:'' }}
                                    </a>
                                </h3>
                            </div>
                        </div>
                    </div>


                    <div class="inner-box default-inner-box">
                        <div class="welcome-msg">
                            <h3 class="page-sub-header2 clearfix no-padding">{{ t('Hello') }} {{ $user->name }} ! </h3>
                            <span class="page-sub-header-sub small">
                            {{ t('You last logged in at') }}:
                            {{ \App\Helpers\Date::format($user->last_login_at, 'datetime') }}
                        </span>

                        </div>
                        <div id="accordion" class="panel-group">
                            <!-- USER -->
                            <div class="card card-default">
                                <div class="card-header">
                                    <h4 class="card-title" data-toggle="collapse" data-parent="#accordion" style="text-decoration: none; color: white;">
                                        {{ t('Account Details') }}
                                    </h4>
                                </div>
                                <div class="panel-collapse collapse {{ (old('panel')=='' or old('panel')=='userPanel') ? 'show' : '' }}"
                                     id="userPanel">
                                    <div class="card-body">
                                        <form name="details" id="updateprofile" method="POST"
                                              action="{{ url('/affiliate/update_profile') }}"
                                              enctype="multipart/form-data"
                                              class="dashboard-form">
                                            {!! csrf_field() !!}
                                            <input name="user_id" type="hidden" value="{{$user->id}}">
                                            <input name="_method" type="hidden" value="POST">
                                            <input name="panel" type="hidden" value="userPanel">

                                            @if (empty($user->user_type_id) or $user->user_type_id == 0)

                                                    <?php $userTypeIdError = (!empty($errors) and $errors->has('user_type_id')) ? ' is-invalid' : ''; ?>
                                                <div class="form-group row required">
                                                    <label
                                                            class="col-md-3 col-form-label{{ $userTypeIdError }}">{{ t('You are a') }}
                                                        <sup>*</sup></label>
                                                    <div class="col-md-9">
                                                        <select name="user_type_id" id="userTypeId"
                                                                class="form-control selecter{{ $userTypeIdError }}">
                                                            <option value="0" @if (old( 'user_type_id' )=='' or
                                                        old('user_type_id')==0) selected="selected" @endif>
                                                                {{ t('Select') }}
                                                            </option>
                                                            @foreach ($userTypes as $type)
                                                                <option value="{{ $type->id }}" @if (old( 'user_type_id' , $user->
                                                        user_type_id)==$type->id)
                                                                    selected="selected"
                                                                        @endif
                                                                >
                                                                    {{ t($type->name) }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                            @else


                                                <!-- name -->
                                                    <?php $nameError = (!empty($errors) and $errors->has('name')) ? ' is-invalid' : ''; ?>
                                                <div class="form-group row required">
                                                    <label class="col-md-3 col-form-label">{{ t('Name') }} <sup>*</sup></label>
                                                    <div class="col-md-9">
                                                        <input name="name" type="text"
                                                               class="form-control{{ $nameError }}"
                                                               placeholder="" value="{{ old('name', $user->name) }}">
                                                    </div>
                                                </div>


                                                <!-- username -->
                                                    <?php $usernameError = (isset($errors) and $errors->has('username')) ? ' is-invalid' : ''; ?>
                                                <div class="form-group row" hidden>
                                                    <label class="col-md-3 col-form-label"
                                                           for="email">{{ t('Username') }}<sup>*</sup></label>
                                                    <div class="input-group col-md-9">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i
                                                                        class="icon-user"></i></span>
                                                        </div>

                                                        <input id="username" name="username" type="text"
                                                               class="form-control{{ $usernameError }}"
                                                               placeholder="{{ t('Username') }}"
                                                               value="{{ old('username', $user->username) }}">
                                                    </div>
                                                </div>

                                                <!-- email -->
                                                    <?php $emailError = (!empty($errors) and $errors->has('email')) ? ' is-invalid' : ''; ?>
                                                <div class="form-group row required">
                                                    <label class="col-md-3 col-form-label">{{ t('Email') }}
                                                        @if (!isEnabledField('phone'))
                                                            <sup>*</sup>
                                                        @endif
                                                    </label>
                                                    <div class="input-group col-md-9">
                                                        <div class="input-group-prepend">
                                                            <i class="fa fa-envelope border p-2"
                                                               style="padding-top: 12px !important; background-color: white; color: #22d3fd; height: 38px;"
                                                               aria-hidden="true"></i>
                                                        </div>

                                                        <input id="email" name="email" type="email" readonly="readonly"
                                                               class="form-control{{ $emailError }}"
                                                               placeholder="{{ t('Email') }}"
                                                               value="{{ old('email', $user->email) }}">
                                                    </div>
                                                </div>

                                                <input name="country_code" type="hidden"
                                                       value="{{ $user->country_code }}">

                                                <!-- phone -->
                                                    <?php $phoneError = (isset($errors) and $errors->has('phone')) ? ' is-invalid' : ''; ?>
                                                @if(auth()->user()->user_type_id!=2)
                                                    <div class="form-group row required">
                                                        <label for="phone"
                                                               class="col-md-3 col-form-label">{{ t('Phone') }}
                                                            <sup>*</sup>
                                                        </label>
                                                        <div class="input-group col-md-6">
                                                            <div class="input-group-prepend">
                                                                <i class="icon-phone-1 border icon-phone-profile p-1"></i>
                                                            </div>

                                                                <?php $countryCodeString = '+' . config('country.phone'); ?>
                                                            <input name="phone" inputmode="numeric" pattern="[0-9+,\.]+"
                                                                   oninput="handleInput(this,'{{$countryCodeString}}')"
                                                                   class="phone_number form-control{{ $phoneError }}"
                                                                   placeholder="{{t('Mobile Phone Number')}}"
                                                                   value="{{ $user->phone }}">

                                                        </div>


                                                    </div>
                                    </div>
                                    @endif
                                    @if(auth()->user()->user_type_id!=1 && auth()->user()->user_type_id!=5)
                                        <div class="form-group row required for_employee_div">
                                            <label class="col-md-3 col-form-label" for="skill_set">{{ t('skill Set') }}
                                                <sup>*</sup>
                                            </label>
                                            <div class="input-group col-md-9">

                                                    <?php $skill_set = explode(',', $user->skill_set); ?>
                                                <select class="skill_set" multiple name="skill_set[]" max="5" required>
                                                    @foreach($employee_skills as $item)
                                                            <?php $skill = rtrim($item->skill); ?>
                                                        <option value="{{$item->skill}}"
                                                                <?php if (in_array($skill, $skill_set)) {
                                                            echo "selected";
                                                        } ?>>
                                                            {{$item->skill}}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>
                                        <div class="form-group row required availability" id="availability">
                                            <label class="col-md-3 col-form-label" for="email">{{ t('availability') }}
                                                <sup>*</sup>
                                            </label>
                                            <div class="input-group col-md-9">
                                                <select class="form-control select1 for_employee_input"
                                                        name="availability" required>
                                                    @if($availability)
                                                        @foreach ($availability as $item)
                                                            <option value="{{$item->id}}"
                                                                    {{!empty($user->availability) && $user->availability == $item->id? 'selected':''}}>
                                                                {{$item->name}}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row required availability" id="availability">
                                            <label class="col-md-3 col-form-label"
                                                   for="nationality">{{ t('Nationality') }} <sup>*</sup></label>
                                            <div class="col-md-9">
                                                <select class="form-control for_employee_input select1"
                                                        name="nationality" required>
                                                    @if($nationality)
                                                        @foreach ($nationality as $key => $value)
                                                            @if($key !== 'Any')
                                                                <option value="{{$value}}"
                                                                        {{!empty($user->nationality) && $user->nationality == $value? 'selected':''}}>
                                                                    {{$key}}</option>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row required availability" id="availability">
                                            <label class="col-md-3 col-form-label"
                                                   for="experience">{{ t('Work Experience') }} <sup
                                                        style="color:red">*</sup></label>
                                            <div class="col-md-9">
                                                <select id="experience" name="experiences" class="form-control select1 "
                                                        required>
                                                    <option value="0-1 years"
                                                            {{!empty($user->experiences) && $user->experiences == '0-1 years'? 'selected':''}}>
                                                        {{t('0-1 years')}}</option>
                                                    <option value="1-3 years"
                                                            {{!empty($user->experiences) && $user->experiences == '1-3 years'? 'selected':''}}>
                                                        {{t('1-3 years')}}</option>
                                                    <option value="3-5 years"
                                                            {{!empty($user->experiences) && $user->experiences == '3-5 years'? 'selected':''}}>
                                                        {{t('3-5 years')}}</option>
                                                    <option value="5-10 years"
                                                            {{!empty($user->experiences) && $user->experiences == '5-10 years'? 'selected':''}}>
                                                        {{t('5-10 years')}}</option>
                                                    <option value="10-20 years"
                                                            {{!empty($user->experiences) && $user->experiences == '10-20 years'? 'selected':''}}>
                                                        {{t('10-20 years')}}</option>
                                                    <option value="20+ years"
                                                            {{!empty($user->experiences) && $user->experiences == '20+ years'? 'selected':''}}>
                                                        {{t('20+ years')}}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row required visa" id="visa_div" style="display: none">
                                            <label class="col-md-3 col-form-label"
                                                   for="visa">{{ t('Work Visa') }} <sup
                                                        style="color:red">*</sup></label>
                                            <div class="col-md-9">
                                                <select id="work_visa" name="visa"
                                                        class="form-control select1 work_visa"
                                                        onchange="show_visa_box(this)">
                                                    <option value="">{{ t('Choose an Option') }}
                                                    </option>
                                                    <option value="No, I don’t NEED a visa"
                                                            {{ !empty($user->visa) && in_array($user->visa, ["No, I dont NEED a visa", "No, I don't NEED a visa"]) ? 'selected' : '' }}>
                                                        {{ t('No, I don’t NEED a visa') }}</option>
                                                    <option value="No, I don’t HAVE a visa"
                                                            {{ !empty($user->visa) && in_array($user->visa, ["No, I dont HAVE a visa", "No, I don't HAVE a visa"]) ? 'selected' : '' }}>
                                                        {{ t('No, I don’t HAVE a visa') }}</option>
                                                    <option value="Yes, I HAVE a visa"
                                                            {{ !empty($user->visa) && $user->visa == 'Yes, I HAVE a visa' ? 'selected' : '' }}>
                                                        {{ t('Yes, I HAVE a visa') }}</option>

                                                </select>


                                            </div>


                                        </div>
                                        <div class="form-group row required country_of_work_visa_div"
                                             id="country_of_work_visa_div" style="display:none">

                                            <label class="col-md-3 col-form-label"
                                                   for="country_of_work_visa_div">{{ t('Country of Work Visa') }}
                                                <sup style="color:red">*</sup></label>
                                            <div class="col-md-9">
                                                <select class="form-control country_work_visa select1"
                                                        name="country_work_visa"
                                                        onchange="work_visa_country(this)">
                                                    <option value="">Choose a Country</option>
                                                    @if (!empty($countries_list))
                                                        @foreach ($countries_list as $item)
                                                            @if($item->code=='KW')
                                                                <option value="{{ $item->code }}"
                                                                        {{ !empty($user->country_work_visa) && $user->country_work_visa == $item->code ? 'selected' : '' }}>
                                                                    {{ $item->name }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </select>

                                            </div>
                                        </div>
                                        <div class="form-group row required visa_number_div"
                                             id="visa_number_div" style="display:none">
                                            <label class="col-md-3 col-form-label"
                                                   for="visa">{{ t('Work Visa Type') }} <sup
                                                        style="color:red">*</sup></label>
                                            <div class="col-md-9">
                                                <select name="visa_number"
                                                        class="form-control select1 work_visa_number">
                                                    <option value="">{{ t('Choose an Option') }}
                                                    </option>
                                                        <?php foreach ($visa_types as $visa) : ?>
                                                        <?php foreach ($visa as $value => $label) : ?>
                                                    <option value="<?php echo $value; ?>"
                                                            {{ !empty($user->visa_number) && $user->visa_number == $value ? 'selected' : '' }}>
                                                            <?php echo $label; ?></option>
                                                    <?php endforeach; ?>
                                                    <?php endforeach; ?>


                                                </select>

                                            </div>
                                        </div>

                                    @endif

                                    <div class="form-group row">
                                        <label for="logo"
                                               class="col-md-3 col-form-label">{{ t('Profile Image') }}<sup
                                                    style="color:red">*</sup></label>
                                        <div class="file-upload col-md-9 "><input type="file"
                                                                                  accept="image/png, image/jpeg, image/jpg"
                                                                                  id="logo" name="file"
                                                                                  class="form-control">
                                        </div>
                                        <style>
                                            .update-photo {
                                                margin-top: 10px;
                                            }
                                        </style>
                                        <div class="col-md-3">
                                        </div>
                                        <div class="update-photo col-md-9"><img class="image"
                                                                                src="{{  \App\Helpers\Helper::getImageOrThumbnailLink($user); }}">
                                        </div>
                                    </div>

                                    <div class="form-group row required">
                                        <label class="col-md-3 col-form-label" for="email">{{ t('Country') }}
                                            <sup>*</sup>
                                        </label>
                                        <div class="input-group col-md-9">
                                            <select id="countryCode1"
                                                    class="form-control select1 for_employer_input" name="country_code"
                                                    onchange="get_cities()" required>
                                                @foreach ($countries as $item)
                                                    <option value="{{$item['code']}}" <?php if (!empty($user->country_code) && $user->country_code == $item['code']) {
                                                        echo "selected";
                                                    } ?>>{{$item['name']}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                    <div class="form-group row required">
                                        <label class="col-md-3 col-form-label" for="email">{{ t('City') }}
                                            <sup>*</sup>
                                        </label>
                                        <div class="input-group col-md-9">
                                            <select id="cityId1" class="form-control select1 for_employer_input"
                                                    name="city" required>
                                                @foreach ($cities as $item)
                                                    <option value="{{$item->id}}" <?php if (!empty($user->city) && $user->city == $item->id) {
                                                        echo "selected";
                                                    } ?>>{{$item->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    @endif


                                    <!-- Button -->
                                    @if(auth()->user()->user_type_id == 1)
                                        <div class="row">
                                            <div class="col-md-3">
                                            </div>
                                            <div class="col-md-9">
                                                    <?php $company_data = App\Models\Company::get_post_company(auth()->user()->id);
                                                    if (!empty($company_data)) {
                                                        $company_c_id = $company_data->id;
                                                    } else {
                                                        $company_c_id = '0';
                                                    }
                                                    ?>
                                                    <?php if (!empty($company_c_id)){ ?>
                                                <p><a
                                                            href="{{ url('/account/companies/'.$company_c_id.'/edit') }}">{{t('Click HERE to edit this company’s description, cuisines and entities')}}</a>
                                                </p>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="row">
                                        <div class="col-md-3">
                                        </div>
                                        <div class="col-md-9">
                                            <button type="submit" onchange="this.form.submit()" id="updatepro"
                                                    class="btn btn-primary">{{ t('Update') }}</button>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <br>
                    @include('affiliate.inc.email_settings')
                    <br>
                    <!-- SETTINGS -->

                    <div class="card card-default" <?php if (!empty(auth()->user()->parent_id)) {
                        echo 'hidden';
                    } ?>>
                        <div class="card-header">
                            <h4 class="card-title"><a href="#settingsPanel" class="profileset"
                                                      style="text-decoration: none; color: white;"
                                                      data-toggle="collapse"
                                                      data-parent="#accordion">{{ t('Settings') }}</a></h4>
                        </div>
                        <div class="panel-collapse collapse show {{ (old('panel')=='settingsPanel') ? 'in' : '' }}"
                             id="settingsPanel">
                            <div class="card-body">
                                <form name="settings_password" class="form-horizontal" role="form" method="POST"
                                      action="{{ url('affiliate/settings') }}">
                                    {!! csrf_field() !!}
                                    <input name="_method" type="hidden" value="POST">
                                    <input name="panel" type="hidden" value="settingsPanel">

                                    @if (config('settings.single.activation_facebook_comments') and
                                    config('services.facebook.client_id'))
                                        <!-- disable_comments -->
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label"></label>
                                            <div class="col-md-9">
                                                <div class="form-check form-check-inline pt-2">
                                                    <label>
                                                        <input id="disable_comments" name="disable_comments" value="1"
                                                               type="checkbox"
                                                                {{ ($user->disable_comments==1) ? 'checked' : '' }}>
                                                        {{ t('Disable comments on my ads') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <!-- password -->
                                    <?php $passwordError = (isset($errors) and $errors->has('password')) ? ' is-invalid' : ''; ?>
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label">{{ t('New Password') }}</label>
                                        <div class="col-md-9 show-pwd-group">
                                            <input id="password" required name="password" type="password"
                                                   class="form-control{{ $passwordError }}"
                                                   placeholder="{{ t('Password') }}">
                                            <span class="icon-append show-pwd">
                                                <button type="button" class="eyeOfPwd" onclick="showPwd()">
                                                    <i id="eyeIcon" class="far fa-eye-slash" style="color:#474b51"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>

                                    <!-- password_confirmation -->
                                    <?php $passwordError = (isset($errors) and $errors->has('password')) ? ' is-invalid' : ''; ?>
                                    <div
                                            class="form-group row <?php echo (isset($errors) and $errors->has('password')) ? ' is-invalid' : ''; ?>">
                                        <label class="col-md-3 col-form-label">{{ t('Confirm Password') }}</label>
                                        <div class="col-md-9 show-pwd-group">
                                            <input id="password_confirmation" required name="password_confirmation"
                                                   type="password" class="form-control{{ $passwordError }}"
                                                   placeholder="{{ t('Confirm Password') }}">
                                            <span class="icon-append show-pwd">
                                                <button type="button" class="eyeOfPwd" onclick="showconfirmdPwd()">
                                                    <i id="eyeIcon2" class="far fa-eye-slash" style="color:#474b51"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>

                                    @if ($user->accept_terms != 1)
                                        <!-- accept_terms -->
                                            <?php $acceptTermsError = (isset($errors) and $errors->has('accept_terms')) ? ' is-invalid' : ''; ?>
                                        <div class="form-group row required">
                                            <label class="col-md-3 col-form-label"></label>
                                            <div class="col-md-9">
                                                <div class="form-check">
                                                    <input name="accept_terms" id="acceptTerms"
                                                           class="form-check-input{{ $acceptTermsError }}" value="1"
                                                           type="checkbox"
                                                            {{ (old('accept_terms', $user->accept_terms)=='1') ? 'checked="checked"' : '' }}>

                                                    <label class="form-check-label" for="acceptTerms"
                                                           style="font-weight: normal;">
                                                        {!! t('accept_terms_label', ['attributes' =>
                                                        getUrlPageByType('terms')]) !!}
                                                    </label>
                                                </div>
                                                <div style="clear:both"></div>
                                            </div>
                                        </div>

                                        <input type="hidden" name="user_accept_terms"
                                               value="{{ (int)$user->accept_terms }}">
                                    @endif

                                    <!-- accept_marketing_offers -->
                                    <?php $acceptMarketingOffersError = (isset($errors) and $errors->has('accept_marketing_offers')) ? ' is-invalid' : ''; ?>
                                    <div class="form-group row required" hidden>
                                        <label class="col-md-3 col-form-label"></label>
                                        <div class="col-md-9">
                                            <div class="form-check">
                                                <input name="accept_marketing_offers" id="acceptMarketingOffers"
                                                       class="form-check-input{{ $acceptMarketingOffersError }}"
                                                       value="1"
                                                       type="checkbox"
                                                        {{ (old('accept_marketing_offers', $user->accept_marketing_offers)=='1') ? 'checked="checked"' : '' }}>

                                                <label class="form-check-label" for="acceptMarketingOffers"
                                                       style="font-weight: normal;">
                                                    {!! t('accept_marketing_offers_label') !!}
                                                </label>
                                            </div>
                                            <div style="clear:both"></div>
                                        </div>
                                    </div>

                                    <!-- time_zone -->
                                    <?php $timeZoneError = (isset($errors) and $errors->has('time_zone')) ? ' is-invalid' : ''; ?>
                                    <div class="form-group row " hidden>
                                        <label class="col-md-3 col-form-label{{ $timeZoneError }}" for="time_zone">
                                            {{ t('preferred_time_zone_label') }} <sup>*</sup>
                                        </label>
                                        <div class="col-md-9">
                                            <select hidden name="time_zone"
                                                    class="form-control sselecter{{ $timeZoneError }}">
                                                <option value="" {{ (empty(old(
                                                'time_zone'))) ? 'selected="selected"' : '' }}>
                                                    {{ t('select_a_time_zone') }}
                                                </option>
                                                <?php $tz = !empty($user->time_zone) ? $user->time_zone : ''; ?>
                                                @foreach (\App\Helpers\Date::getTimeZones() as $key => $item)
                                                    <option value="{{ $key }}" {{ (old(
                                                'time_zone', $tz)==$key) ? 'selected="selected"' : '' }}>
                                                        {{ $item }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <small id="" class="form-text text-muted">
                                                @if (auth()->user()->can(\App\Models\Permission::getStaffPermissions()))
                                                    {!! t('admin_preferred_time_zone_info', [
                                                    'frontTz' => config('country.time_zone'),
                                                    'country' => config('country.name'),
                                                    'adminTz' => config('app.timezone'),
                                                    ]) !!}
                                                @else
                                                    {!! t('preferred_time_zone_info', [
                                                    'frontTz' => config('country.time_zone'),
                                                    'country' => config('country.name'),
                                                    ]) !!}
                                                @endif
                                            </small>
                                        </div>
                                    </div>

                                    <!-- Button -->
                                    <div class="form-group row">
                                        <div class="offset-md-3 col-md-9">
                                            <button type="submit" class="btn btn-primary">{{ t('Update') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
                <!--/.row-box End-->

            </div>
        </div>
        <!--/.page-content-->
    </div>
    <!--/.row-->
    </div>
    <!--/.container-->
    </div>
    <!-- /.main-container -->
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


<?php

if (!empty($company->city_id)) {
    $city_id = $company->city_id;

} else {
    $city_id = '';
}
?>
@section('after_scripts')
    <script>
        function handleInput(input, countryCode) {
            const inputValue = input.value;
            const countryCodeLength = countryCode.length;

            if (inputValue.length < countryCodeLength) {
                input.value = countryCode;
            } else if (!inputValue.startsWith(countryCode)) {
                input.value = countryCode + inputValue;
            }
        }

        function get_cities() {

            var countryCode = $('#countryCode1').val();
            show_hide_visa_options(countryCode);
            var url = '{{url("/ajax/get_city_by_country/")}}/' + countryCode

            var appenddata1 = "";
            $.ajax({
                type: "GET",
                url: url,
                dataType: 'json',
                beforeSend: function () {
                    // setting a timeout
                    $("#cityId1").empty();
                },
                success: function (c) {
                    $(c.items).each(function (key, value) {
                        var name = value.text.split(',')[0];
                        var slelectd_city = '{{$city_id }}';
                        if (value.id == slelectd_city) {
                            var selected = 'selected';
                        } else {
                            selected = '';
                        }
                        appenddata1 += "<option value = '" + value.id + " ' " + selected + ">" + name +
                            "  </option>";
                    });
                    // cityId1
                    $("#cityId1").append(appenddata1);
                },
            });
        }

        $('#phone').keyup(function (e) {
            if (/\D/g.test(this.value)) {
                this.value = this.value.replace(/\D/g, '');
                this.value = '+' + this.value;
            } else {
                this.value = '+' + this.value;
            }
        });
        $(document).ready(function () {
            $(".skill_set").select2({
                width: '100%',
                maximumSelectionLength: 5,
                placeholder: '{{t("Employee Skills(you can choose more than one)")}}',
            })
            var visa = $('#work_visa').val();
            var country_work_visa = $('#country_work_visa').val();
            if (country_work_visa == 'KW') {
                $('.visa_number_div').show();
            } else {
                $('.visa_number_div').hide();
            }
            if (visa == 'Yes, I HAVE a visa') {
                $('.country_of_work_visa_div').show();
            } else {
                $('.country_of_work_visa_div').hide();
            }
            var country = $('#country_work_visa').val();
            if (country == 'KW') {
                $('.visa_number_div').show();
            } else {
                $('.visa_number_div').hide();
            }
            show_hide_visa_options("{{ $user->country_code}}");


        });


        function show_visa_box(obj) {
            var visa = $(obj).val();

            if (visa == 'Yes, I HAVE a visa') {
                $('.country_of_work_visa_div').show();
                $('.country_work_visa').prop('required', true);

            } else {
                $('.country_of_work_visa_div').hide();
                $('.country_work_visa').prop('required', false);
                $('.visa_number_div').hide();
            }
            $('.country_work_visa').val('').trigger('change');
            $('.work_visa_number').val('').trigger('change');
        }


        function work_visa_country(obj) {
            var country = $(obj).val();
            if (country == 'KW') {
                $('.visa_number_div').show();
                $('.work_visa_number').prop('required', true);
            } else {
                $('.visa_number_div').hide();
                $('.work_visa_number').prop('required', false);
                $('.work_visa_number').val('').trigger('change');
            }
        }

        function show_hide_visa_options(countryCode) {
            if (countryCode == 'KW') {
                $('#visa_div').show();
                $('.work_visa').prop('required', true);
            } else {
                $('#visa_div').hide();
                $('.country_of_work_visa_div').hide();
                $('.visa_number_div').hide();
                $('.country_work_visa').prop('required', false);
                $('.work_visa').prop('required', false);
                $('.work_visa').val(null).trigger('change');
                $('.country_work_visa').val('').trigger('change');
                $('.work_visa_number').val('').trigger('change');
            }
        }

    </script>
@endsection