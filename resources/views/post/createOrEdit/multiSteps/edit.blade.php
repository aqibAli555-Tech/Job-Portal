<!DOCTYPE html>
@extends('layouts.master')

@section('wizard')
@includeFirst([config('larapen.core.customizedViewPath') . 'post.createOrEdit.multiSteps.inc.wizard',
'post.createOrEdit.multiSteps.inc.wizard'])
@endsection
<?php
// Category
if ($post->category) {
    if (empty($post->category->parent_id)) {
        $postCatParentId = $post->category->id;
    } else {
        $postCatParentId = $post->category->parent_id;
    }
} else {
    $postCatParentId = 0;
}
?>
<?php

?>
@section('content')
@includeFirst([config('larapen.core.customizedViewPath') . 'common.spacer', 'common.spacer'])
<div class="main-container">
    <div class="container">
        <div class="row">

            @includeFirst([config('larapen.core.customizedViewPath') . 'post.inc.notification',
            'post.inc.notification'])

            <div class="col-md-9 page-content">
                <div class="inner-box category-content">
                    <h2 class="title-2">
                        <strong><i class="icon-docs"></i>{{t('Update Job Post')}}</strong>
                        -&nbsp;<a href="{{ \App\Helpers\UrlGen::post($post) }}" class="tooltipHere" title=""
                            data-placement="top" data-toggle="tooltip" data-original-title="{!! $post->title !!}">{!!
                            \Illuminate\Support\Str::limit($post->title, 45) !!}</a>
                    </h2>
                    <div class="row">
                        <div class="col-sm-12">
                            <form class="form-horizontal" id="postForm" method="POST" action="{{ url()->current() }}"
                                enctype="multipart/form-data">
                                {!! csrf_field() !!}
                                <!-- <input name="_method" type="hidden" value="PUT"> -->
                                <input type="hidden" name="post_id" value="{{ $post->id }}">
                                <fieldset>
                                    <!-- COMPANY -->
                                    <div class="content-subheading mt-0">
                                        <i class="icon-town-hall fa"></i>
                                        <strong>{{ t('Company Information') }}</strong>
                                    </div>

                                    <!-- company_id -->
                                    <?php $companyIdError = (isset($errors) and $errors->has('company_id')) ? ' is-invalid' : ''; ?>
                                    <div class="form-group row required">
                                        <label class="col-md-3 col-form-label{{ $companyIdError }}">
                                            {{ t('Select a Company') }} <sup>*</sup>
                                        </label>
                                        <div class="col-md-8">
                                            <select id="companydetail" name="company_id" onchange="getCompanydetail()"
                                                class="form-control selecter{{ $companyIdError }}">

                                                @if (isset($companies) and $companies->count() > 0)
                                                @foreach ($companies as $item)
                                                <option value="{{ $item->id }}"
                                                    data-logo="{{ App\Helpers\Helper::get_company_logo_AWS($item) }}" @if (old('company_id', (isset($postCompany) ? $postCompany->id :0))==$item->id) selected ="selected" @endif  >{{ $item->name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <input type="hidden" value="{{$post->id}}" name="post_id" id="post_id">

                                    <!-- logo -->
                                    <div id="logoField" class="form-group row">
                                        <label class="col-md-3 col-form-label">&nbsp;</label>
                                        <div class="col-md-8">
                                            <div class="mb10">
                                                <div id="logoFieldValue"></div>
                                            </div>
                                            <small id="" class="form-text text-muted">
                                                <a id="companyFormLink" href="{{ url('account/companies/0/edit') }}"
                                                    class="btn btn-primary">
                                                    <i class="fa fa-pencil-square-o"></i> {{ t('Edit the Company') }}
                                                </a>
                                            </small>
                                        </div>
                                    </div>

                                    {{--  @includeFirst([config('larapen.core.customizedViewPath') . 'account.company._form', 'account.company._form'], ['originForm' => 'post'])--}}


                                    <!-- POST -->
                                    <div class="content-subheading">
                                        <i class="icon-town-hall fa"></i>
                                        <strong>{{ t('Job Details') }}</strong>
                                    </div>

                                    <!-- category_id -->
                                    <?php $categoryIdError = (isset($errors) and $errors->has('category_id')) ? ' is-invalid' : ''; ?>
                                    <div class="form-group row required">
                                        <label class="col-md-3 col-form-label{{ $categoryIdError }}">{{ t('Skill') }}
                                            <sup>*</sup></label>
                                        <div class="col-md-8">
                                            <select class="form-control skill_set" required="required"
                                                name="category_id">
                                                @foreach($employee_skills as $item)
                                                <option value="{{$item->id}}" <?php if ($post->category_id == $item->id) {
                                                            echo 'selected';
                                                        } ?>>{{$item->skill}}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                        <input type="hidden" name="" id="categoryId" value="">
                                    </div>

                                    <!-- title -->
                                    <?php $titleError = (isset($errors) and $errors->has('title')) ? ' is-invalid' : ''; ?>
                                    <div class="form-group row required">
                                        <label class="col-md-3 col-form-label" for="title">{{ t('Position') }}
                                            <sup>*</sup></label>
                                        <div class="col-md-8">
                                            <input id="title" readonly name="title" placeholder="{{ t('Job title') }}"
                                                class="form-control input-md{{ $titleError }}" type="text"
                                                value="{{ old('title', $post->title) }}">
                                            <small hidden id="" class="form-text text-muted">
                                                {{ t('A great title needs at least 60 characters.') }}
                                            </small>
                                            <small style="color:red">{{t('msg-edit-job-position')}}</small>
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
                                            } else {
                                                $post->description = strip_tags($post->description);
                                            }
                                            ?>
                                        <label class="col-md-3 col-form-label{{ $descriptionErrorLabel }}"
                                            for="description">
                                            {{ t('Description') }} <sup>*</sup>
                                        </label>
                                        <div class="{{ $descriptionColClass }}">
                                            <textarea class="form-control {{ $descriptionError }}" id="description"
                                                name="description" rows="15" maxlength="50000"
                                                required="">{{ old('description', $post->description) }}</textarea>
                                            <small id=""
                                                class="form-text text-muted">{{ t('Give a detailed description of your job offer') }}</small>
                                        </div>
                                    </div>


                                    <!-- post_type_id -->
                                    <?php $postTypeIdError = (isset($errors) and $errors->has('post_type_id')) ? ' is-invalid' : ''; ?>
                                    <div id="postTypeBloc" class="form-group row required">
                                        <label class="col-md-3 col-form-label{{ $postTypeIdError }}">
                                            {{ t('Job Type') }} <sup>*</sup>
                                        </label>
                                        <div class="col-md-8">
                                            <select name="post_type_id" id="postTypeId"
                                                class="form-control select4 selecter{{ $postTypeIdError }}">
                                                @foreach ($postTypes as $postType)
                                                <option value="{{ $postType->id }}" @if (old('post_type_id', $post->
                                                    post_type_id) == $postType->id)
                                                    selected="selected"
                                                    @endif
                                                    >{{ $postType->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- salary_min & salary_max -->
                                    <?php $salaryMinError = (isset($errors) and $errors->has('salary_min')) ? ' is-invalid' : ''; ?>
                                    <?php $salaryMaxError = (isset($errors) and $errors->has('salary_max')) ? ' is-invalid' : ''; ?>
                                    <div id="salaryBloc" class="form-group row">
                                        <label class="col-md-3 col-form-label" for="salary_max">{{ t('Salary') }}<sup
                                                style="color:red">*</sup></label>
                                        <div class="col-md-4">
                                            <div class="row">
                                                <div class="input-group col-md-12">
                                                    @if (config('currency')['in_left'] == 1)
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">{!! config('currency')['symbol']
                                                            !!}</span>
                                                    </div>
                                                    @endif
                                                    <?php
                                                        $salaryMin = \App\Helpers\Number::format(old('salary_min', $post->salary_min), 2, '.', '');
                                                        ?>
                                                    <input id="salary_min" name="salary_min"
                                                        class="form-control tooltipHere{{ $salaryMinError }}"
                                                        data-toggle="tooltip"
                                                        data-original-title="{{ t('salary_min') }}"
                                                        placeholder="{{ t('salary_min') }}" type="text" min="0"
                                                        step="{{ getInputNumberStep((int)config('currency.decimal_places', 2)) }}"
                                                        value="{!! $salaryMin !!}">
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
                                                        $salaryMax = \App\Helpers\Number::format(old('salary_max', $post->salary_max), 2, '.', '');
                                                        ?>
                                                    <input id="salary_max" name="salary_max"
                                                        class="form-control tooltipHere{{ $salaryMaxError }}"
                                                        data-toggle="tooltip"
                                                        data-original-title="{{ t('salary_max') }}"
                                                        placeholder="{{ t('salary_max') }}" type="text" min="0"
                                                        step="{{ getInputNumberStep((int)config('currency.decimal_places', 2)) }}"
                                                        value="{!! $salaryMax !!}">
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
                                            <select name="salary_type_id" id="salaryTypeId"
                                                class="form-control select4 selecter{{ $salaryTypeIdError }}">
                                                @foreach ($salaryTypes as $salaryType)
                                                <option value="{{ $salaryType->id }}" @if (old('salary_type_id', $post->
                                                    salary_type_id) == $salaryType->id)
                                                    selected="selected"
                                                    @endif
                                                    >{{ t('per') . ' ' . $salaryType->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label pt-2">
                                                    <input id="hide_salary" name="hide_salary" type="checkbox" value="1"
                                                        {{ (old('hide_salary', $post->postMeta->hide_salary)=='1') ? 'checked="checked"' : '' }}>&nbsp;Hide
                                                    salary
                                                </label>
                                            </div>

                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label pt-2">
                                                    <input id="negotiable" name="negotiable" type="checkbox" value="1"
                                                        {{ (old('negotiable', $post->negotiable)=='1') ? 'checked="checked"' : '' }}>&nbsp;{{ t('Negotiable') }}
                                                </label>
                                            </div>
{{--                                            <input type="hidden" name="negotiable" value="0">--}}

                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label"
                                            for="start_date">{{ t('Transportation Available') }} </label>
                                        <div class="col-md-4">
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label  disabled1 pt-2">
                                                    <input id="transportation_available" name="transportation_available"
                                                        type="checkbox" value="1"
                                                        {{ (old('transportation_available',$post->postDetail->transportation_available)=='1') ? 'checked="checked"' : '' }}>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label"
                                            for="start_date">{{ t('Overtime Pay available') }} <sup
                                                style="color:red"></sup></label>
                                        <div class="col-md-4">
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label  disabled1 pt-2">
                                                    <input id="overtime_pay" name="overtime_pay" type="checkbox"
                                                        value="1"
                                                        {{ (old('overtime_pay',$post->postDetail->overtime_pay)=='1') ? 'checked="checked"' : '' }}>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label"
                                            for="start_date">{{ t('Housing Available') }} <sup
                                                style="color:red"></sup></label>
                                        <div class="col-md-4">
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label  disabled1 pt-2">
                                                    <input id="housing_available" name="housing_available"
                                                        type="checkbox" value="1"
                                                        {{ (old('housing_available',$post->postDetail->housing_available)=='1') ? 'checked="checked"' : '' }}>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $startDateError = (isset($errors) and $errors->has('gender')) ? ' is-invalid' : ''; ?>
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label" for="gender">{{ t('Gender Preference') }}
                                            <sup style="color:red">*</sup></label>
                                        <div class="col-md-8">
                                            <select id="gender" name="gender" class="select4 form-control "
                                                required>
                                                <option value="">{{t('select gender')}}</option>
                                                <option value="Male" <?php if (!empty($post->postDetail->gender == 'Male')) {
                                                        echo "selected";
                                                    } ?>>{{t('male')}}</option>
                                                <option value="Female" <?php if (!empty($post->postDetail->gender == 'Female')) {
                                                        echo "selected";
                                                    } ?>>{{t('female')}}</option>
                                                <option value="Not Specified" <?php if (!empty($post->postDetail->gender == 'Not Specified')) {
                                                        echo "selected";
                                                    } ?>>{{t('not specified')}}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label"
                                            for="experience">{{ t('Work Experience') }} <sup
                                                style="color:red">*</sup></label>
                                        <div class="col-md-8">
                                            <select id="experience" name="experience" class="form-control select4 " required>
                                                <option value="">{{t('select experience')}}</option>
                                                <option value="Not Specified" <?php if (!empty($post->postDetail->experiences == 'Not Specified')) {
                                                        echo "selected";
                                                    } ?>>{{t('not specified')}}</option>
                                                <option value="Fresh" <?php if (!empty($post->postDetail->experiences == 'Fresh')) {
                                                        echo "selected";
                                                    } ?>>{{t('fresh')}}</option>
                                                <option value="0-1 years" <?php if (!empty($post->postDetail->experiences == '0-1 years')) {
                                                        echo "selected";
                                                    } ?>>{{t('0-1 years')}}</option>
                                                <option value="1-3 years" <?php if (!empty($post->postDetail->experiences == '1-3 years')) {
                                                        echo "selected";
                                                    } ?>>{{t('1-3 years')}}</option>
                                                <option value="3-5 years" <?php if (!empty($post->postDetail->experiences == '3-5 years')) {
                                                        echo "selected";
                                                    } ?>>{{t('3-5 years')}}</option>
                                                <option value="5-10 years" <?php if (!empty($post->postDetail->experiences == '5-10 years')) {
                                                        echo "selected";
                                                    } ?>>{{t('5-10 years')}}</option>
                                                <option value="10-20 years" <?php if (!empty($post->postDetail->experiences == '10-20 years')) {
                                                        echo "selected";
                                                    } ?>>{{t('10-20 years')}}</option>
                                                <option value="20+" <?php if (!empty($post->postDetail->experiences == '20+')) {
                                                        echo "selected";
                                                    } ?>>{{t('20+ years')}}</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label" for="nationality">{{ t('Nationality') }}
                                            </label>
                                        <div class="col-md-8">
                                            <select multiple class="select4" name="nationality[]" id="nationality"
                                                size="1">
                                                <?php
                                                    if (!empty($post->postDetail->nationality)) {
                                                        $nationalities = explode(',', $post->postDetail->nationality);
                                                    } else {
                                                        $nationalities = [];
                                                    }
                                                    if (!empty($nationality)){
                                                    foreach ($nationality as $key => $value) {

                                                        ?>
                                                <option value="{{$value}}" <?php if (in_array($value, $nationalities)) {
                                                        echo "selected";
                                                    } ?>>{{$key}}</option>
                                                <?php }
                                                    } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label" for="start_date">{{ t('As Soon As Possible') }} <sup style="color:red"></sup></label>
                                        <div class="col-md-4">
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label  disabled1 pt-2">
                                                    <input id="as_soon" name="as_soon" type="checkbox" value="1" <?php if (!empty($post->as_soon) && $post->as_soon == 1) {
                                                                                                                        echo "checked";
                                                                                                                    } ?>>
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
                                            <input required id="start_date" name="start_date"
                                                onkeyup="check_date_not_empty(this)" placeholder="{{ t('Start Date') }}"
                                                class="form-control input-md{{ $startDateError }} cf-date" type="text"
                                                value="{{ old('start_date', $post->start_date) }}" autocomplete="off">
                                        </div>
                                    </div>
                                   

                                    <!-- contact_name -->
                                    <?php $contactNameError = (isset($errors) and $errors->has('contact_name')) ? ' is-invalid' : ''; ?>
                                    <div class="form-group row required">
                                        <label class="col-md-3 col-form-label"
                                            for="contact_name">{{ t('Contact Name') }} <sup>*</sup></label>
                                        <div class="input-group col-md-8" style="height: 38px;">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="icon-user"></i></span>
                                            </div>
                                            <input required id="contact_name" name="contact_name"
                                                placeholder="{{ t('Contact Name') }}"
                                                class="form-control input-md{{ $contactNameError }}" type="text"
                                                value="{{ old('contact_name', $post->contact_name) }}">
                                        </div>
                                    </div>

                                    <!-- email -->
                                    <?php $emailError = (isset($errors) and $errors->has('email')) ? ' is-invalid' : ''; ?>
                                    <div class="form-group row required">
                                        <label class="col-md-3 col-form-label" for="email"> {{ t('Contact Email') }}
                                            <sup>*</sup><br><small>{{t('(will be hidden)')}}</small></label>
                                        <div class="input-group col-md-8">
                                            <div class="input-group-prepend" style="height: 38px;">
                                                <span class="input-group-text"><i class="icon-mail"></i></span>
                                            </div>
                                            <input required id="email" name="email" class="form-control{{ $emailError }}"
                                                placeholder="{{ t('Email') }}" type="text"
                                                value="{{ old('email', $post->email) }}">
                                        </div>
                                    </div>

                                    <!-- phone -->
                                    <?php $phoneError = (isset($errors) and $errors->has('phone')) ? ' is-invalid' : ''; ?>
                                    <div class="form-group row required">
                                        <label class="col-md-3 col-form-label" for="phone">{{ t('Phone Number') }}<sup
                                                style="color:red">*</sup><br><small>{{t('(will be hidden)')}}</small></label>
                                        <div class="input-group col-md-8">
                                            <div class="input-group-prepend" style="height: 38px;">
                                                <span id="phoneCountry" class="input-group-text">{!!
                                                    getPhoneIcon($post->country_code) !!}</span>
                                            </div>

                                            <input required id="phone" name="phone" placeholder="{{ t('Phone') }}"
                                                class="form-control  input-md{{ $phoneError }}"
                                                oninput="this.value = this.value.replace(/[^0-9+]/g, '').replace(/(\..*)\./g, '$1');"
                                                type="text"
                                                value="{{ phoneFormat(old('phone', $post->phone), $post->country_code) }}">


                                        </div>
                                    </div>



                                    @if(!empty($data['valid_package']))
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label"
                                            for="start_date">{{ t('Hide Company Name & Logo?') }}</sup></label>
                                        <div class="col-md-4">
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label  disabled1 pt-2">
                                                    <input id="hide_company_logo" name="hide_company_logo"
                                                        type="checkbox" value="1"
                                                        <?php if(!empty($post->postDetail->hide_company_logo) && $post->postDetail->hide_company_logo == 1){echo "checked";}?>>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="form-group row required ml-2">
                                        <label class="col-md-3 col-form-label "
                                            style="margin-top:-12px">{{ t('Who Can Apply?') }} <sup
                                                style="color:red">*</sup></label>
                                        <div class="col-md-8">

                                            <span class="row">
                                                <a href="" onclick="show_skill_dropdown(1)" class="col-md-5">
                                                    <input class="form-check-input" type="radio" name="post_type"
                                                        id="flexRadioDefault1"
                                                        <?php if(!empty($post->postDetail->post_type == 1)) {echo "checked";} ?>
                                                        value="1">
                                                    <label class="form-check-label" for="flexRadioDefault1">
                                                        {{t('Anyone with any Skills Sets')}}
                                                    </label>
                                                </a>
    
    
                                                <a href="javascript:void(0)" onclick="show_skill_dropdown(2)" class="col-md-7">
                                                    <input class="form-check-input" type="radio" name="post_type"
                                                        id="flexRadioDefault2"
                                                        <?php if(!empty($post->postDetail->post_type == 2)) {echo "checked";} ?>
                                                        value="2">
                                                    <label class="form-check-label" for="flexRadioDefault2">
                                                        {{t('Only specific Skills Sets (choose them below)')}}
                                                    </label>
                                                </a>
                                            </span>

                                        </div>
                                    </div>

                                    <!-- select1 disabled1 -->
                                    <style>


                                    </style>
                                    <div class="form-group row required slecect2_input dropdown_skills_sets"
                                        id="dropdown" style="display:none">
                                        <label class="col-md-3 col-form-label">{{ t('Specific Skills Sets') }}
                                            <sup>*</sup> </label>
                                        <div class="col-md-8">


                                            <select class="select2_input skills_sets_select" multiple name="skill_set[]">
                                                @foreach($employee_skills as $item)
                                                <?php
                                                         
                                                         if (!empty($post->postDetail->skills_set)) {
                                                            $skills_set = explode(',', $post->postDetail->skills_set);
                                                         } else {
                                                          $skills_set = [];
                                                          }  
                                                          $skill=rtrim($item->skill);
                                                          ?>
                                                <option value="{{$item->skill}}"
                                                    <?php if(in_array($skill,$skills_set)){echo "selected";}?>>
                                                    {{$item->skill}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                    <?php $country_name=App\Models\Country::find($post->country_code);?>
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label" for="country">{{t('Country')}}<sup
                                                style="color:red">*</sup></label>
                                        <div class="col-md-8">
                                            <input id="countryCode" class="form-control" name="country_code" type="text"
                                                value="{{ !empty($country_name->name) ? $country_name->name : config('country.code') }}"
                                                readonly>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label" for="experience">{{t('City')}}<sup
                                                style="color:red">*</sup></label>
                                        <div class="col-md-8">
                                            <select id="experience" name="city_id" class="form-control select4" required>
                                                <option value="">Select City</option>
                                                <?php if(!empty($post_country_city)){?>
                                                @foreach($post_country_city as $city)
                                                
                                                <option value="{{$city->id}}"
                                                    <?php if (!empty($city->id == $post->postDetail->city_id)) { echo "selected"; } ?>>
                                                    {{$city->name}}</option>
                                                @endforeach
                                                <?php }?>
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
                                                    <input class="form-check-input" type="radio" name="who_can_apply" id="who_can_apply"  <?php if(!empty($post->postDetail->who_can_apply == 1)) {echo "checked";} ?> value="1" checked>
                                                    <label class="form-check-label" for="who_can_apply">
                                                        {{t('Local Hire Only')}}
                                                        {{$country_name->name}}
                                                        <img src="{{ url()->asset('images/flags/16/' . strtolower(auth()->user()->country_code) . '.png') }}">
                                                    </label>
                                                </div>
                                                <div class="col-md-4">
                                                    <input class="form-check-input" type="radio" name="who_can_apply" id="who_can_apply2"  <?php if(!empty($post->postDetail->who_can_apply == 2)) {echo "checked";} ?> value="2">
                                                    <label class="form-check-label" for="who_can_apply2">
                                                        {{t('International Hire Only')}} ðŸŒŽ
                                                    </label>
                                                </div>
                                                 <div class="col-md-4">
                                                    <input class="form-check-input" type="radio" name="who_can_apply" id="who_can_apply3"  <?php if(!empty($post->postDetail->who_can_apply == 3)) {echo "checked";} ?> value="3">
                                                    <label class="form-check-label" for="who_can_apply3">
                                                    Both Local Hire {{$country_name->name}}
                                                        <img src="{{ url()->asset('images/flags/16/' . strtolower(auth()->user()->country_code) . '.png') }}">
                                                    & International Hire ðŸŒŽ
                                                    </label>
                                                </div>
                                            </span>
                                        </div>
                                    </div>
                                    {{-- end --}}


                                    <!-- Button -->
                                    <div class="form-group row">
                                        <div class="col-md-12 text-center">
                                            <a href="{{ \App\Helpers\UrlGen::post($post) }}"
                                                class="btn btn-default btn-lg"> {{ t('Back') }}</a>
                                            <button id="nextStepBtn" class="btn btn-primary-dark btn-lg">
                                                {{ t('Update') }} </button>
                                            <button type="button" class="btn btn-primary-dark btn-lg"
                                                onclick="previewpost()">{{t('preview')}}</button>
                                            <!-- data-toggle="modal" data-target="#exampleModal" data-whatever="@fat" -->
                                        </div>
                                    </div>

                                </fieldset>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            <!-- /.page-content -->
            @includeFirst([config('larapen.core.customizedViewPath') . 'post.createOrEdit.inc.category-modal',
            'post.createOrEdit.inc.category-modal'])

            <div class="col-md-3 reg-sidebar">
                @includeFirst([config('larapen.core.customizedViewPath') . 'post.createOrEdit.inc.right-sidebar',
                'post.createOrEdit.inc.right-sidebar'])
            </div>

        </div>
    </div>
</div>

@includeFirst([config('larapen.core.customizedViewPath') . 'post.createOrEdit.inc.form-assets',
'post.createOrEdit.inc.form-assets'])
@includeFirst([config('larapen.core.customizedViewPath') . 'post.createOrEdit.multiSteps.models',
'post.createOrEdit.multiSteps.models'])
@endsection

@section('after_styles')
@endsection

@section('after_scripts')
<script>
    function show_skill_dropdown(value) {
        if (value == 2) {
            $('.dropdown_skills_sets').show();
            $('.skills_sets_select').attr('required', true);
        } else {
            $('.dropdown_skills_sets').hide();
            $('.skills_sets_select').attr('required', false);

        }
    }
 var value = $("input:radio[name=post_type]:checked").val();
    if (value == 2) {
        $('.dropdown_skills_sets').show();
    } else {
        $('.dropdown_skills_sets').hide();
    }



</script>
<script>
$('#salary_max,#salary_min').keyup(function(e) {
    if (/\D/g.test(this.value)) {
        this.value = this.value.replace(/\D/g, '');
    }
});
$(".select2_input").select2({
    maximumSelectionLength: 10,
    width: '100%'
});

$(".select4").select2({
    maximumSelectionLength: 10,
    width: '100%'
});
</script>

<script>
window.onload = function() {
    $('#dropdown_skills_sets').select2();
    getCompanydetail();
}

  $(document).ready(function() {
    // Initially check the checkbox state
    if ($('#as_soon').is(':checked')) {
        $('#start_date').hide().removeAttr('required');
        $('.start_date').hide();
    } else {
        $('#start_date').show().attr('required', true);
        $('.start_date').show();
    }

    // Add an event listener to handle changes in the checkbox
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
            $('#logoFieldValue').html('<img src="' + c.logo + '">');
            // $('#email').val(c.email);
            // $('#phone').val(c.phone);
            $('#overlay').hide();
        },
    });
}

function check_date_not_empty(obj) {
    swal({
        title: "OOPS!",
        text: "{{ t('date_cannot_be_in_the_past') }}",
        icon: "error",
        button: "Ok",
    });
    $(obj).val('');
}
</script>
@endsection

<style>
.select2 textarea {
    display: none;
}

.select2-container--default .select2-selection--multiple,
.select2-container--default.select2-container--focus .select2-selection--multiple {
    border: none !important;
}
</style>