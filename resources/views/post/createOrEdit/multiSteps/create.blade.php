@extends('layouts.master')


@section('wizard')
@includeFirst([config('larapen.core.customizedViewPath') . 'post.createOrEdit.multiSteps.inc.wizard',
'post.createOrEdit.multiSteps.inc.wizard'])
@endsection
@section('content')
@includeFirst([config('larapen.core.customizedViewPath') . 'common.spacer', 'common.spacer'])

<?php

use App\Helpers\Number;
use App\Models\User;
use App\Models\CompanyPackages;

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

            <style>
                .select2-selection--multiple {
                    border: none !important;
                    outline: 0 !important;
                }
            </style>

            @endif
            {{-- @includeFirst([config('larapen.core.customizedViewPath') . 'post.inc.notification', 'post.inc.notification']) --}}

            <div class="col-md-9 page-content">
                <div class="inner-box category-content">
                    <h2 class="title-2" hidden>
                        <strong><i class="icon-docs"></i> {{ t('Post a Job') }}</strong>
                    </h2>

                    <div class="row">
                        <div class="col-xl-12">
                            <form class="form-horizontal" id="postForm" method="POST" action="{{ request()->fullUrl() }}" enctype="multipart/form-data">
                                {!! csrf_field() !!}
                                <fieldset>

                                    <!-- COMPANY -->
                                    <div class="content-subheading mt-0">
                                        <i class="icon-town-hall fa"></i>
                                        <strong>{{ t('Company Information') }}</strong>
                                    </div>

                                    <!-- company_id -->
                                    <?php $companyIdError = (isset($errors) and $errors->has('company_id')) ? ' is-invalid' : ''; ?>
                                    <div class="form-group row required">
                                        <label class="col-md-3 col-form-label{{ $companyIdError }}">{{ t('Select a Company') }}
                                            <sup>*</sup></label>

                                        <div class="col-md-8">
                                            <div id="lowCredit" class="alert" style="display:none"></div>
                                            <select required id="companydetail" onchange="getCompanydetail()" name="company_id" class="form-control selecter{{ $companyIdError }}">
                                                @if (isset($companies) and $companies->count() > 0)
                                                @foreach ($companies as $item)
                                                <?php $user_data = User::where('id', $item->c_id)->first();
                                                 $post_count= CompanyPackages::check_post_available($user_data->id);
                                                if (empty($post_count)) {
                                                    $disabled = '';
                                                }else{
                                                    $disabled = ''; 
                                                } 
                                                ?>
                                                <option value="{{ $item->id }}" {{$disabled}} data-logo="{{url('/public/storage/')}}/{{$item->logo}}" @if(!empty(request('company')) && request('company')==$item->id)
                                                    selected="selected"
                                                    @endif>{{ $item->name }}
                                                    ({{$post_count}} {{ t('Post available') }}
                                                    )
                                                </option>

                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <!-- logo -->
                                    <div id="logoField" class="form-group row">
                                        <label class="col-md-3 col-form-label">&nbsp;</label>
                                        <div class="col-md-8">
                                            <div class="mb10">
                                                <div id="logoFieldValue"></div>
                                            </div>
                                            <small id="" class="form-text text-muted">
                                                @if($companies->count() > 0)
                                                <a id="companyFormLink" href="{{url('account/profile/')}}" class="btn btn-primary">
                                                    <i class="fa fa-pencil-square-o"></i> {{ t('Edit the Company') }}
                                                </a>
                                                @else
                                                <a id="companyFormLink" href="{{url('account/companies/create')}}" class="btn btn-primary">
                                                    <i class="fa fa-pencil-square-o"></i> {{ t('Create a Company') }}
                                                </a>
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                    {{--@includeFirst([config('larapen.core.customizedViewPath') . 'account.company._form', 'account.company._form'], ['originForm' => 'post'])--}}
                                    <!-- POST -->
                                    <div class="content-subheading">
                                        <i class="icon-town-hall fa"></i> <strong>{{ t('Job Details') }}</strong>
                                        <span class="t-text-size">Choose a Skill Set from the below dropdown or create a new one</span>
                                        <span> <a href="#"  class=" pull-right" data-toggle="modal" data-target="#add_new_skill_set"><i class="icon-plus fa"></i>{{ t('Add New Skill Set') }}</a></span>
                                    </div>
                                    <!-- category_id -->
                                    <?php $categoryIdError = (isset($errors) && $errors->has('category_id')) ? ' is-invalid' : ''; ?>
                                    <div class="form-group row required">
                                        <label class="col-md-3 col-form-label{{ $categoryIdError }}">{{ t('Skills Set') }}
                                            <sup>*</sup> </label>
                                        <div class="col-md-8">
                                            <select class="form-control skill_set  disabled1" required="required" name="category_id" >
                                                @foreach($data['employee_skills'] as $item)
                                                <option value="{{$item->id}}">{{$item->skill}}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                        <input type="hidden" id="categoryId" value="{{ old('category_id', 0) }}">
                                    </div>

                                    <!-- title -->
                                    <?php $titleError = (isset($errors) and $errors->has('title')) ? ' is-invalid' : ''; ?>
                                    <div class="form-group row required">
                                        <label class="col-md-3 col-form-label" for="title">{{ t('Position') }}
                                            <sup>*</sup></label>
                                        <div class="col-md-8">
                                            <input id="title" name="title" placeholder="{{ t('Job Title - youre only allowed to put one open position per job post') }}" class="form-control input-md{{ $titleError }} disabled1" type="text" value="{{ old('title') }}" required>
                                        </div>
                                    </div>

                                    <!-- description -->
                                    <?php $descriptionError = (isset($errors) and $errors->has('description')) ? ' is-invalid' : ''; ?>
                                    <div class="form-group row required">
                                        <?php
                                        $descriptionErrorLabel = '';
                                        $descriptionColClass = 'col-md-8';
                                        if (config('settings.single.wysiwyg_editor') != 'none') {
                                            $descriptionColClass = 'col-md-8';
                                            $descriptionErrorLabel = $descriptionError;
                                        }
                                        ?>
                                        <label class="col-md-3 col-form-label{{ $descriptionErrorLabel }}" for="description">
                                            {{ t('Description') }} <sup>*</sup>
                                        </label>
                                        <div class="{{ $descriptionColClass }}">
                                            <textarea class="form-control {{ $descriptionError }} disabled1" id="description" name="description" maxlength="5000" rows="10">{{ old('description') }}</textarea>
                                            <small id="" class="form-text text-muted">{{ t('Give a detailed description of your job offer') }}</small>
                                        </div>
                                    </div>
                                    <!-- post_type_id -->
                                    <?php $postTypeIdError = (isset($errors) and $errors->has('post_type_id')) ? ' is-invalid' : ''; ?>
                                    <div id="postTypeBloc" class="form-group row required">
                                        <label class="col-md-3 col-form-label{{ $postTypeIdError }}">
                                            {{ t('Job Type') }} <sup>*</sup>
                                        </label>
                                        <div class="col-md-8">
                                            <select name="post_type_id" id="postTypeId" class="form-control select4 disabled1 selecter{{ $postTypeIdError }}">
                                                @foreach ($postTypes as $postType)
                                                <option value="{{ $postType->id }}" @if (old( 'post_type_id' )==$postType->id)
                                                    selected="selected"
                                                    @endif
                                                    >{{ $postType->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- salary_min & salary_max -->
                                    <?php $salaryMinError = (isset($errors) && $errors->has('salary_min')) ? ' is-invalid' : ''; ?>
                                    <?php $salaryMaxError = (isset($errors) && $errors->has('salary_max')) ? ' is-invalid' : ''; ?>
                                    <div id="salaryBloc" class="form-group row">
                                        <label class="col-md-3 col-form-label" for="salary_min">{{ t('Salary') }} <sup style="color: red">*</sup></label>
                                        <div class="col-md-4">
                                            <div class="row">
                                                <div class="input-group col-md-12">
                                                    @if (config('currency')['in_left'] == 1)
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">{!! config('currency')['symbol']
                                                            !!}</span>
                                                    </div>
                                                    @endif
                                                    <?php $salaryMin = Number::format(old('salary_min'), 2, '.', ''); ?>
                                                    <input id="salary_min" name="salary_min" class="form-control salary disabled1 tooltipHere{{ $salaryMinError }}" data-toggle="tooltip" data-original-title="{{ t('salary_min') }}" placeholder="{{ t('salary_min') }}" type="text" min="0" step="{{ getInputNumberStep((int)config('currency.decimal_places', 2)) }}" value="{!! $salaryMin !!}" required>
                                                    @if (config('currency')['in_left'] == 0)
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">{!! config('currency')['symbol']
                                                            !!}</span>
                                                    </div>
                                                    @endif
                                                </div>
                                                <div class="input-group col-md-12">
                                                    @if (config('currency')['in_left'] == 1)
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">{!! config('currency')['symbol']
                                                            !!}</span>
                                                    </div>
                                                    @endif
                                                    <?php
                                                    $salaryMax = Number::format(old('salary_max'), 2, '.', '');
                                                    ?>
                                                    <input id="salary_max" name="salary_max" class="form-control salary disabled1 tooltipHere{{ $salaryMaxError }}" data-toggle="tooltip" data-original-title="{{ t('salary_max') }}" placeholder="{{ t('salary_max') }}" type="text" min="0" step="{{ getInputNumberStep((int)config('currency.decimal_places', 2)) }}" value="{!! $salaryMax !!}" required>
                                                    @if (config('currency')['in_left'] == 0)
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">{!! config('currency')['symbol']
                                                            !!}</span>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- salary_type_id -->
                                        <?php $salaryTypeIdError = (isset($errors) and $errors->has('salary_type_id')) ? ' is-invalid' : ''; ?>
                                        <div class="col-md-4">
                                            <select name="salary_type_id" id="salaryTypeId" class="form-control select4 disabled1 selecter{{ $salaryTypeIdError }}">
                                                @foreach ($salaryTypes as $salaryType)
                                                <option value="{{ $salaryType->id }}" @if (old( 'salary_type_id' )==$salaryType->id)
                                                    selected="selected"
                                                    @elseif($salaryType->id==3)
                                                    selected="selected"
                                                    @endif
                                                    >{{ t('per') . ' ' . $salaryType->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label  disabled1 pt-2">
                                                    <input id="negotiable" name="negotiable" type="checkbox" value="1" {{ (old('negotiable')=='1') ? 'checked="checked"' : '' }}>&nbsp;{{ t('Negotiable') }}
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label  disabled1 pt-2">
                                                    <input id="hide_salary" name="hide_salary" type="checkbox" value="1" {{ (old('hide_salary')=='1') ? 'checked="checked"' : '' }}>&nbsp;Hide
                                                    Salary
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label" for="start_date">{{ t('Transportation Available') }}</sup></label>
                                        <div class="col-md-4">
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label  disabled1 pt-2">
                                                    <input id="transportation_available" name="transportation_available" type="checkbox" value="1" {{ (old('transportation_available')=='1') ? 'checked="checked"' : '' }}>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label" for="start_date">{{ t('Overtime Pay available') }} <sup style="color:red"></sup></label>
                                        <div class="col-md-4">
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label  disabled1 pt-2">
                                                    <input id="overtime_pay" name="overtime_pay" type="checkbox" value="1" {{ (old('overtime_pay')=='1') ? 'checked="checked"' : '' }}>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label" for="start_date">{{ t('Housing Available') }} <sup style="color:red"></sup></label>
                                        <div class="col-md-4">
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label  disabled1 pt-2">
                                                    <input id="housing_available" name="housing_available" type="checkbox" value="1" {{ (old('housing_available')=='1') ? 'checked="checked"' : '' }}>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label" for="gender">{{ t('Gender Preference') }}
                                            <sup style="color:red">*</sup></label>
                                        <div class="col-md-8">
                                            <select id="gender" name="gender" class="select4 form-control " required>
                                                <option value="">{{t('select gender')}}</option>
                                                <option value="Male">{{t('male')}}</option>
                                                <option value="Female">{{t('female')}}</option>
                                                <option value="Not Specified">{{t('not specified')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label" for="experience">{{ t('Work Experience') }} <sup style="color:red">*</sup></label>
                                        <div class="col-md-8">
                                            <select id="experience" name="experience" class="form-control select4" required>
                                                <option value="">{{t('select experience')}}</option>
                                                <option value="Not Specified">{{t('not specified')}}</option>
                                                <option value="Fresh">{{t('fresh')}}</option>
                                                <option value="0-1 years">{{t('0-1 years')}}</option>
                                                <option value="1-3 years">{{t('1-3 years')}}</option>
                                                <option value="3-5 years">{{t('3-5 years')}}</option>
                                                <option value="5-10 years">{{t('5-10 years')}}</option>
                                                <option value="10-20 years">{{t('10-20 years')}}</option>
                                                <option value="20+ years">{{t('20+ years')}}</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label" for="nationality">{{ t('Nationality') }}
                                            <small class="text-muted">({{t('optional')}})</small></label>
                                        <div class="col-md-8">
                                            <select class="nationality select4 form-control" multiple name="nationality[]" id="nationality">
                                                <?php
                                                if (!empty($data['nationality'])) {

                                                    foreach ($data['nationality'] as $key => $value) {
                                                ?>
                                                        <option value="{{$value}}">{{$key}}</option>
                                                <?php }
                                                } ?>
                                            </select>

                                        </div>
                                    </div>
                                     <div class="form-group row">
                                        <label class="col-md-3 col-form-label" for="as_soon">{{ t('As Soon As Possible') }} <sup style="color:red"></sup></label>
                                        <div class="col-md-4">
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label  disabled1 pt-2">
                                                    <input id="as_soon" name="as_soon" type="checkbox" value="1">
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- start_date -->
                                    <?php $startDateError = (isset($errors) and $errors->has('start_date')) ? ' is-invalid' : ''; ?>
                                    <div class="form-group row start_date">
                                        <label class="col-md-3 col-form-label" for="start_date">{{ t('Start Date') }}
                                            <sup style="color:red">*</sup></label>
                                        <div class="col-md-8">
                                            <input id="start_date" name="start_date" onkeyup="check_date_not_empty(this)" placeholder="{{ t('Start Date') }}" class="form-control disabled1 input-md{{ $startDateError }} cf-date" type="text" value="{{ old('start_date') }}" autocomplete="off" required>
                                        </div>
                                    </div>

                                    @if (empty(config('country.code')))
                                    <!-- country_code -->
                                    <?php $countryCodeError = (isset($errors) and $errors->has('country_code')) ? ' is-invalid' : ''; ?>
                                    <div class="form-group row required">
                                        <label class="col-md-3 col-form-label{{ $countryCodeError }}" for="country_code">{{ t('Your Country') }} <sup>*</sup></label>
                                        <div class="col-md-8">
                                            <select id="countryCode" name="country_code" disabled class="form-control">
                                                <option value="0" {{ (!old(
                                                'country_code') or old('country_code')==0) ? 'selected="selected"' : '' }}>
                                                    {{ t('select_a_country') }}
                                                </option>
                                                @foreach ($countries as $item)
                                                <option value="{{ $item->get('code') }}" {{ (old(
                                                'country_code', (!empty(config('ipCountry.code'))) ? config('ipCountry.code') : 0)==$item->get('code')) ? 'selected="selected"' : '' }}>
                                                    {{ $item->get('name') }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @else
                                    <input id="countryCode" name="country_code" type="hidden" value="{{ config('country.code') }}">
                                    @endif



                                    <!-- contact_name -->
                                    @if (auth()->check())
                                    <input id="contact_name" name="contact_name" type="hidden" value="{{ auth()->user()->name }}">
                                    @else
                                    <?php $contactNameError = (isset($errors) and $errors->has('contact_name')) ? ' is-invalid' : ''; ?>
                                    <div class="form-group row required">
                                        <label class="col-md-3 col-form-label" for="contact_name">{{ t('Contact Name') }} <sup>*</sup></label>
                                        <div class="input-group col-md-8">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="icon-user"></i></span>
                                            </div>
                                            <input id="contact_name" name="contact_name" placeholder="{{ t('Contact Name') }}" class="form-control disabled1 input-md{{ $contactNameError }}" type="text" value="{{ old('contact_name') }}" required>
                                        </div>
                                    </div>
                                    @endif

                                    <!-- email -->
                                    <?php $emailError = (isset($errors) and $errors->has('email')) ? ' is-invalid' : ''; ?>
                                    <div class="form-group row required">
                                        <label class="col-md-3 col-form-label" for="email"> {{ t('Contact Email') }}
                                            <sup>*</sup></label>
                                        <div class="input-group col-md-8">
                                            <div class="input-group-prepend" style="height: 38px;">
                                                <span class="input-group-text"><i class="icon-mail"></i></span>
                                            </div>
                                            <input id="email" name="email" class="form-control{{ $emailError }} disabled1" placeholder="{{ t('Email') }}" type="text" value="{{ old('email', ((auth()->check() and isset(auth()->user()->email)) ? auth()->user()->email : '')) }}" required>
                                        </div>
                                    </div>


                                    <?php
                                    if (auth()->check()) {
                                        $formPhone = (auth()->user()->country_code == config('country.code')) ? auth()->user()->phone : '';
                                    } else {
                                        $formPhone = '';
                                    }
                                    ?>
                                    <!-- phone -->
                                    <?php $phoneError = (isset($errors) and $errors->has('phone')) ? ' is-invalid' : ''; ?>
                                    <div class="form-group row required">
                                        <label class="col-md-3 col-form-label" for="phone">{{ t('Phone') }}
                                            <sup>*</sup></label>
                                        <div class="input-group col-md-8">
                                            <div class="input-group-prepend disabled1" style="height: 38px;">
                                                <span id="phoneCountry" class="input-group-text">{!!
                                                    getPhoneIcon(config('country.code')) !!}</span>
                                            </div>

                                            <input id="phone" name="phone" placeholder="{{ t('Phone Number') }}" class="form-control  disabled1 input-md{{ $phoneError }}" oninput="this.value = this.value.replace(/[^0-9+]/g, '').replace(/(\..*)\./g, '$1');" type="text" value="{{ phoneFormat(old('phone', $formPhone), old('country', config('country.code'))) }}">

                                            <div class="input-group-append">
                                                <span class="input-group-text" hidden>
                                                    <input name="phone_hidden" id="phoneHidden" disabled type="checkbox" value="1" {{ (old('phone_hidden')=='1') ? 'checked="checked"' : '' }}>&nbsp;
                                                    <small>{{ t('Hide') }}</small>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    @if(!empty($data['valid_package']))
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label" for="start_date">{{ t('Hide Company Name & Logo?') }}</sup></label>
                                        <div class="col-md-4">
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label  disabled1 pt-2">
                                                    <input id="hide_company_logo" name="hide_company_logo" type="checkbox" value="1">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="form-group row required ml-2">
                                        <label class="col-md-3 col-form-label" style="margin-top:-12px" for="post_type">{{ t('Who Can Apply?') }} <sup>*</sup></label>
                                        <div class="col-md-8">
                                            <span class="row">
                                                <a class="col-md-5">
                                                    <input class="form-check-input" type="radio" name="post_type" id="post_type" value="1"
                                                        onchange="post_type_change(this)" checked>
                                                    <label class="form-check-label" for="post_type">
                                                        {{t('Anyone with any Skills Sets')}}
                                                    </label>
                                                </a>
                                                <a class="col-md-7">
                                                    <input class="form-check-input" type="radio" name="post_type" id="post_type2" value="2"
                                                        onchange="post_type_change(this)">
                                                    <label class="form-check-label" for="post_type2">
                                                        {{t('Only specific Skills Sets (choose them below)')}}
                                                    </label>
                                                </a>
                                            </span>
                                        </div>
                                    </div>
                                    <!-- select1 disabled1 -->
                                    <div class="form-group row required" id="dropdown" style="display:none">
                                        <label class="col-md-3 col-form-label{{ $categoryIdError }}">{{ t('Specific Skills Sets') }}
                                            <sup>*</sup> </label>
                                        <div class="col-md-8">
                                            <select class="form-control skill_set select4" multiple name="skill_set[]" id="specific_skill_set">
                                                @foreach($data['employee_skills'] as $item)
                                                <option value="{{$item->skill}}">{{$item->skill}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    {{-- start --}}

                                    <div class="form-group row required">
                                        <label class="col-md-3 col-form-label" for="phone">{{t('Country')}}<sup>*</sup></label>
                                        <div class="input-group col-md-8">
                                            <input name="country_code" placeholder="{{t('Country')}}" class="form-control input-md" type="text" value="{{ !empty($data['country_name']->name) ? $data['country_name']->name : config('country.name') }}" readonly>
                                        </div>
                                    </div>
                                    {{-- start --}}
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label" for="experience">{{t('City')}} <sup style="color:red">*</sup></label>
                                        <div class="col-md-8">
                                            <select id="experience" name="city_id" class="form-control select4" required>
                                                <option value="">Select City</option>
                                                <?php if (!empty($data['city_name'])) { ?>
                                                    @foreach($data['city_name'] as $city)
                                                    <option value="{{$city->id}}">{{$city->name}}</option>
                                                    @endforeach
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    {{-- start --}}
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label" style="margin-top:-12px" for="who_can_apply">{{t('Type Of Hiring?')}}
                                             <sup>*</sup></label>
                                        <div class="col-md-8">
                                            <span class="row">
                                                <div class="col-md-4">
                                                    <input class="form-check-input" type="radio" name="who_can_apply" id="who_can_apply" value="1" checked>
                                                    <label class="form-check-label" for="who_can_apply">
                                                        {{t('Local Hire Only')}}
                                                        {{$data['country_name']->name}}
                                                        <img src="{{ url()->asset('images/flags/16/' . strtolower(auth()->user()->country_code) . '.png') }}">
                                                    </label>
                                                </div>
                                                <div class="col-md-4">
                                                    <input class="form-check-input" type="radio" name="who_can_apply" id="who_can_apply2" value="2">
                                                    <label class="form-check-label" for="who_can_apply2">
                                                        {{t('International Hire Only')}}
                                                    </label>
                                                </div>
                                              <div class="col-md-4">
                                                    <input class="form-check-input" type="radio" name="who_can_apply" id="who_can_apply3" value="3">
                                                    <label class="form-check-label" for="who_can_apply3">
                                                    Both Local Hire {{$data['country_name']->name}}
                                                        <img src="{{ url()->asset('images/flags/16/' . strtolower(auth()->user()->country_code) . '.png') }}">
                                                   & International Hire
                                                    </label>
                                                </div>
                                            </span>
                                        </div>
                                    </div>



                                    @includeFirst([config('larapen.core.customizedViewPath') .
                                    'layouts.inc.tools.recaptcha', 'layouts.inc.tools.recaptcha'], ['colLeft' =>
                                    'col-md-3', 'colRight' => 'col-md-8'])

                                    @if (!auth()->check())
                                    <!-- accept_terms -->
                                    <?php $acceptTermsError = (isset($errors) and $errors->has('accept_terms')) ? ' is-invalid' : ''; ?>
                                    <div class="form-group row required">
                                        <label class="col-md-3 col-form-label"></label>
                                        <div class="col-md-8">
                                            <div class="form-check">
                                                <input required name="accept_terms" id="acceptTerms" class="form-check-input{{ $acceptTermsError }}" value="1" type="checkbox" {{ (old('accept_terms')=='1') ? 'checked="checked"' : '' }}>

                                                <label class="form-check-label" for="acceptTerms" style="font-weight: normal;">
                                                    {!! t('accept_terms_label', ['attributes' =>
                                                    getUrlPageByType('terms')]) !!}
                                                </label>
                                            </div>
                                            <div style="clear:both"></div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="created_at" id="created_at" value="{{ date('Y-m-d H:i:s')}}">

                                    <!-- accept_marketing_offers -->
                                    <?php $acceptMarketingOffersError = (isset($errors) and $errors->has('accept_marketing_offers')) ? ' is-invalid' : ''; ?>
                                    <div class="form-group row required">
                                        <label class="col-md-3 col-form-label"></label>
                                        <div class="col-md-8">
                                            <div class="form-check">
                                                <input name="accept_marketing_offers" id="acceptMarketingOffers" class="form-check-input{{ $acceptMarketingOffersError }}" value="1" type="checkbox" {{ (old('accept_marketing_offers')=='1') ? 'checked="checked"' : '' }}>

                                                <label class="form-check-label" for="acceptMarketingOffers" style="font-weight: normal;">
                                                    {!! t('accept_marketing_offers_label') !!}
                                                </label>
                                            </div>
                                            <div style="clear:both"></div>
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Button  -->
                                    <div class="form-group row">
                                        <div class="col-md-12 text-center">
                                            <input id="nextStepBtn" class="btn btn-primary btn-lg" type="submit" value="{{ t('submit') }}">
                                            <button type="button" class="btn btn-primary-dark btn-lg" onclick="previewpost()">{{t('preview')}}</button>
                                        </div>
                                    </div>

                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.page-content -->
            <div class="col-md-3 reg-sidebar">
                @includeFirst([config('larapen.core.customizedViewPath') . 'post.createOrEdit.inc.right-sidebar',
                'post.createOrEdit.inc.right-sidebar'])
            </div>

        </div>
    </div>
</div>
@includeFirst([config('larapen.core.customizedViewPath') . 'post.createOrEdit.inc.category-modal',
'post.createOrEdit.inc.category-modal'])
@includeFirst([config('larapen.core.customizedViewPath') . 'post.createOrEdit.multiSteps.models',
'post.createOrEdit.multiSteps.models'])
@endsection

@section('after_styles')

<script>
    window.onload = function() {
        getCompanydetail();
        // $('.monthselect').removeAttr('disabled')
    }
</script>
@endsection

@section('after_scripts')
<script>
    function post_type_change(obj) {

        var classID = $(obj).val();
        if (classID == 2) {
            $('#dropdown').show();
            $('#specific_skill_set').attr('required', true);
        } else {
            $('#dropdown').hide();
            $('#specific_skill_set').attr('required', false);

        }
    }


    function check_date_not_empty(obj) {
        swal({
            title: "OOPS!",
            text: "{{ t('date_cannot_be_in_the_past ') }}",
            icon: "error",
            button: "Ok",
        });
        $(obj).val('');
    }
    $('#salary_max,#salary_min').keyup(function(e) {
        if (/\D/g.test(this.value)) {
            this.value = this.value.replace(/\D/g, '');
        }
    });
</script>

<script>
  $(document).ready(function() {
        $('#as_soon').change(function() {
            if (this.checked) {
                $('#start_date').hide().removeAttr('required');
                $('.start_date').hide();
            } else {
                $('#start_date').show().attr('required', true);
                $('.start_date').show();
            }
        });
    });
    $('#postForm').submit(function(e) {
        // Stop the form submitting
        e.preventDefault();
        var btn = document.getElementById('nextStepBtn');
        btn.disabled = true;
        e.currentTarget.submit();

    });

    $(".nationality").select2({
        maximumSelectionLength: 5,
        width: '100%'
    });
    $(".select4").select2({
        maximumSelectionLength: 100,
        width: '100%'
    });

    $(document).ready(function() {
        var companyid = $('#companydetail').val();
        if (companyid == '' || companyid == null) {
            $('.disabled').attr('disabled', true);
        }
    })


    function getCompanydetail() {
        var companyid = $('#companydetail').val();
        var url = siteUrl + '/posts/get_company_by_id/' + companyid;
        $.ajax({
            type: "GET",
            url: url,
            dataType: 'json',
            beforeSend: function() {
                $('#overlay').show();
            },
            success: function(c) {
                if (c.lowCredit == 1) {
                    $("#companydetail").val('').trigger('change');
                    $('#logoFieldValue').hide();
                    $("#companyFormLink").hide();
                    $(".disabled1").attr('disabled', 'disabled');
                    const config = {
                        html: true,
                        title: 'Error',
                        html: c.lowCreditMessage,
                        icon: 'error',
                        allowOutsideClick: false,
                        confirmButtonText: 'Upgrade',
                        showCancelButton: true,
                    };
                    Swal.fire(config).then(callback);

                    function callback(result) {
                        if (result.value) {
                            var to_upgrade = true;
                            login_company(c.password, c.email, to_upgrade);

                            // $('.disabled').prop('disabled', true);
                        } else {}
                    }
                } else {
                    $(".disabled1").removeAttr('disabled');
                    $('#lowCredit').html('');
                    $('#lowCredit').hide();
                    $('#logoFieldValue').show();
                    $("#companyFormLink").show();
                }
                var logo = c.logo;
                $('#logoFieldValue').html('<img src="' + logo + '">');
                $('#email').val(c.email);
                $('#phone').val(c.phone);
                $('#overlay').hide();
            },
        });
    }
</script>

@endsection

@includeFirst([config('larapen.core.customizedViewPath') . 'post.createOrEdit.inc.form-assets',
'post.createOrEdit.inc.form-assets'])