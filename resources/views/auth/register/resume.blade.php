{{--
* JobClass - Job Board Web Application
* Copyright (c) BedigitCom. All Rights Reserved
*
* Website: https:bedigit.com
*
* LICENSE
* -------
* This software is furnished under a license and may be used and copied
* only in accordance with the terms of such license and with the inclusion
* of the above copyright notice. If you Purchased from CodeCanyon,
* Please read the full License from here - http:codecanyon.net/licenses/standard
--}}

<?php $nationality = DB::table('nationality')->pluck('id', 'name');

?>
@extends('layouts.master')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@section('content')
@includeFirst([config('larapen.core.customizedViewPath') . 'common.spacer', 'common.spacer'])
<script src="https://cdn.tiny.cloud/1/g32uu0l64r1nwhk18lpt0pc4pi3etv7h11hxiwdtdocu500w/tinymce/5/tinymce.min.js"
        referrerpolicy="origin"></script>
<!-- Breadcrumb -->
<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #615583;
        border: 1px #615583;
    }
</style>
<style>
    .disbaledEducation {
        pointer-events: none;
        opacity: 0.4;
    }

    .disbaledskill {
        pointer-events: none;
        opacity: 0.4;
    }

    .dashboard-content-wrapper .update-photo {
        height: 140px;
        width: 140px;
        position: relative;
        overflow: hidden;
        top: -40px;
    }

    .update-photo img {
        height: 100%;
        width: 100%;
        border-radius: 100px;

    }

    .file-input {
        display: inline-block;
        text-align: left;
        background: #fff;
        padding: 16px;
        width: 100%;
        position: relative;
        border-radius: 3px;
        border: 1px solid #eeeeee;
        margin-bottom: 20px;
    }

    .tox-statusbar {
        display: none;
    }

    .file-input > [type='file'] {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        z-index: 10;
        cursor: pointer;
    }

    .file-input > .button {
        display: inline-block;
        cursor: pointer;
        background: #eee;
        padding: 8px 16px;
        border-radius: 2px;
        margin-right: 8px;
        width: 100px;
        text-align: center;
    }

    .file-input:hover > .button {
        background: #22d3fd;
        color: white;
    }

    .file-input > .label {
        color: #333;
        white-space: nowrap;
        opacity: .3;
    }

    .file-input.-chosen > .label {
        opacity: 1;
    }

    .tagit-new {
        list-style: none !important;
    }

    .tagit-new input {
        width: 100%;
        border: none;
        list-style: none;
        outline: none;
        border-bottom: 1px solid #ebebeb;
        margin-top: 15px;
    }

    .tagit-choice {
        margin: 3px;
        float: left;
        list-style: none;
        background: #f3f5f8;
        padding: 3px;
        border-radius: 5px;
    }

    .tagit-close {
        padding-left: 5px;
        padding-right: 3px;
    }

    .ui-helper-hidden-accessible {
        display: none;
    }

    .select2-search__field {
        width: 100%;
        border: none;
        outline: none;
        border-bottom: 1px solid #22d3fd;
        border-radius: 2px;
        padding: 5px;
    }

    .select2-selection__rendered {
        color: #6f7484
    }

    .input-group-text {
        background-color: #f6f8fb;
    }

    .myTags ul {
        padding: 0px !important;
    }

    /*.select2-container {*/
    /*    width: 100% !important;*/
    /*}*/

    .select2-container {
        border: 1px solid #ddd !important;
        border-radius: 4px;
    }

    .select2-container .select2-selection--single {
        height: 38px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        margin-left: -9px;
        margin-top: 2px;
    }

    .select2-selection--multiple {
        border: none !important;
        outline: 0 !important;
    }

    .select2-selection--single {
        border: none !important;

        outline: 0 !important;
    }

    .dashboard-content-wrapper .update-photo {
        height: 140px;
        width: 140px;
        position: relative;
        overflow: hidden;
        top: -40px;
    }

    .update-photo img {
        height: 100%;
        width: 100%;
        border-radius: 100px;

    }

    .file-input {
        display: inline-block;
        text-align: left;
        background: #fff;
        padding: 16px;
        width: 100%;
        position: relative;
        border-radius: 3px;
        border: 1px solid #eeeeee;
        margin-bottom: 20px;
    }

    .file-input > [type='file'] {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        z-index: 10;
        cursor: pointer;
    }

    .file-input > .button {
        display: inline-block;
        cursor: pointer;
        background: #eee;
        padding: 8px 16px;
        border-radius: 2px;
        margin-right: 8px;
        width: 100px;
        text-align: center;
    }

    .file-input:hover > .button {
        background: #22d3fd;
        color: white;
    }

    .file-input > .label {
        color: #333;
        white-space: nowrap;
        opacity: .3;
    }

    .file-input.-chosen > .label {
        opacity: 1;
    }

    .tagit-new {
        list-style: none !important;
    }

    .tagit-new input {
        width: 100%;
        border: none;
        list-style: none;
        outline: none;
        border-bottom: 1px solid #ebebeb;
        margin-top: 15px;
    }

    .tagit-choice {
        margin: 3px;
        float: left;
        list-style: none;
        background: #f3f5f8;
        padding: 3px;
        border-radius: 5px;
    }

    .tagit-close {
        padding-left: 5px;
        padding-right: 3px;
    }

    .ui-helper-hidden-accessible {
        display: none;
    }

    .select2-search__field {
        width: 100%;
        border: none;
        outline: none;
        border-bottom: 1px solid #22d3fd;
        border-radius: 2px;
        padding: 5px;
    }

    .select2-selection__rendered {
        color: #6f7484
    }

    .input-group-text {
        background-color: #f6f8fb;
    }

    .myTags ul {
        padding: 0px !important;
    }

    /*.select2-container {*/
    /*    width: 100% !important;*/
    /*}*/

    .select2-selection--multiple {
        border: none !important;
        outline: 0 !important;
    }

    .select2-selection--single {
        border: none !important;

        outline: 0 !important;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background: #615583;
    }

    .mybtn {
        padding: 10px;
        width: 200px;
        border: none;
        border-radius: 3px;
        color: white;
        cursor: pointer;
        background: #22d3fd !important;
        outline: none !important;

    }

    button, select {
        text-transform: none;
    }

    .card-header {
        background-color: rgb(97 85 131);
        color: #fff;
    }

    .card-body {
        background: #fcfcfc;
    }

    .inner-box {
        background: #f6f6f6;
    }

    .user-panel-sidebar ul.acc-list li a {
        background: transparent;
    }

    .alert-warning {
        display: none !important;
    }
</style>

<div class="main-container">
    <div class="container">
        <div class="row">

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
                <h3>Create Resume</h3>
                <div class="dashboard-content-wrapper">
                    <form action="{{url('/')}}/api/generatePdf" method="post" id="form_sumbit_resume"
                          enctype="multipart/form-data"
                          class="job-post-form">
                        <input type="hidden" value="{{$data['user']->id}}" name="id">
                        <input type="hidden" value="1" name="from_register">
                        <input type="hidden" value="{{$data['user']->file}}" name="userPhoto">
                        <input type="hidden" value="web" name="device">
                        <div class="basic-info-input">
                            <!--  <h4><i data-feather="plus-circle"></i>Create Resume</h4>-->
                            <div class="row" hidden>
                                <div class="col-md-9">
                                    <img class="profile_image_img" id="blah"
                                         src="{{ url('/storage/app/default/picture.jpg')}}"
                                         alt="">
                                    <label class="image_uploader" for="imgInp">Profile Image</label>
                                    <input type="file" id="imgInp" accept="image/png, image/gif, image/jpeg"
                                           style="">
                                </div>
                            </div>
                            <script>
                                var imgInp = document.getElementById('imgInp');
                                var blah = document.getElementById('blah');
                                imgInp.onchange = evt => {
                                    const [file] = imgInp.files
                                    if (file) {
                                        blah.src = URL.createObjectURL(file)
                                    }
                                }
                            </script>
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">
                                        {{ t('Personal Details') }} <span style="color: red;">*</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <select class="form-control" required="required" name="gender">

                                                    @foreach ($data['gender'] as $item)
                                                    <?php
                                                    $name = $item->name;
                                                    $name = json_decode($name, true);
                                                    $item->name = $name['en'];
                                                    // $item->name=$name[$data['lang_code']];
                                                    ?>
                                                    <option value="{{$item->id}}" <?php if ($item->id == $data['user']->gender_id) {
                                                        echo 'selected';
                                                    } ?>>{{$item->name}}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                {{-- <i class="fa fa-caret-down"></i> --}}
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <input type="text" class="form-control" required="required"
                                                       required="required"
                                                       value="{{ $data['user']->name }}" name="fullName"
                                                       placeholder=" {{ t('First name') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <input type="text" required="required" name="fatherName"
                                                       required="required" class="form-control"
                                                       placeholder="{{ t('Last name') }} ">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="date" id="birthdate" required="required"
                                                       name="date" onchange="validateAge(this.value)"
                                                       max="<?= date('Y-m-d', strtotime('now -18 years')); ?>"
                                                       class="form-control">
                                                <small style="color:red">* {{ t('Date of Birth') }} </small>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="civilid"
                                                       required="required"
                                                       name="cnic" placeholder="{{ t('Local civil id number') }}">

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="email" class="form-control" required="required"
                                                       name="email" value="{{ $data['user']->email }}"
                                                       placeholder="{{ t('Email') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="phone"
                                                       required="required"
                                                       name="phone" value="{{ $data['user']->phone }}"
                                                       placeholder="{{ t('Phone Number') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <select class="skill_set" multiple
                                                        required="required" name="catgory[]">
                                                    @foreach($data['employee_skills'] as $item)
                                                    <option value="{{$item->skill}}">{{$item->skill}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group" style="border-bottom: 1px solid #EBEBEB;">
                                                <input type="hidden" value="{{config('country.code')}}"
                                                       name="default_country">
                                                <select id="country" disabled onchange="countrySelected()"
                                                        class=" form-control" name="country_data">
                                                    <option value=""
                                                            readonly="">{{ t('Select a Country') }}
                                                    </option>
                                                    @foreach($data['country'] as $key => $item)

                                                    <option value="{{$item->code}}" <?php if (config('country.code') == $item->code) {
                                                        echo 'selected';
                                                    } ?>>{{$item->name}}
                                                    </option>

                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <?php $cityIdError = (isset($errors) and $errors->has('city_id')) ? ' is-invalid' : ''; ?>
                                        <div class="col-md-6">
                                            <img src="{{asset('/public/images/images/spinning-wheel.gif')}}"
                                                 id="loading"
                                                 style="width: 27px;margin: 5px;display: none"
                                                 alt="">
                                            <div class="form-group"
                                                 style="display: none;border-bottom: 1px solid #EBEBEB;"
                                                 id="cityDev">
                                                <select id="city" required name="city_id"
                                                        class="citySelect form-control"
                                                        style="display: none;width: 280px"
                                                        onchange="citySelected()">
                                                    <option value="" readonly="">{{ t('Select a City') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="nationality">{{ t('nationality') }} </label>
                                            <div class="form-group">
                                                <select class="nationality form-control" name="nationality" id="nationality" required>
                                                    <?php if (!empty($nationality)) {
                                                        foreach ($nationality as $key => $value) { ?>
                                                            <option value="{{$value}}">{{$key}}</option>
                                                        <?php }
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control" maxlength="100"
                                                       required="required" name="address"
                                                       placeholder="{{ t('Your Address') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">
                                        {{ t('About You') }}<span style="color: red;">*</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                                    <textarea rows="10" class="form-control" maxlength="400" required
                                                              name="about"
                                                              placeholder="{{ t('Describe who you are so employers can get to know you better when they see your resume') }}."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            {{--Skill & Experience--}}
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">
                                        {{ t('Skills & Experiences') }} <span style="color: red;">*</span>
                                    </div>
                                    <input type="checkbox" id="skill_check" name="skill_check"
                                           style="position: relative;top:1px;">
                                    <label for="skill_check" style="font-size: 12px;">Note: I hereby verify that
                                        I do not have any Skills & Experiences.</label>
                                </div>
                                <div class="card-body">
                                    <div class="row disbaledRowskill">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-9" id="skillAndExperience">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control skill_input"
                                                                       maxlength="100"

                                                                       name="sillExperienceComapny[]"
                                                                       placeholder="{{ t('Company Name') }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group"
                                                                 style="border-bottom: 1px solid #EBEBEB;">
                                                                <select id="country_1"
                                                                        onchange="SEcountrySelected(1)"
                                                                        name="sillExperienceCoutry[]"
                                                                        class="countrySelect form-control skill_input">
                                                                    <option value="" readonly="">Country</option>
                                                                    @foreach($data['country'] as $key => $item)
                                                                    <option value="{{$item->code}}">{{$item->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <?php $cityIdError = (isset($errors) and $errors->has('city_id')) ? ' is-invalid' : ''; ?>
                                                        <div class="col-md-6">
                                                            <img src="{{asset('/public/images/images/spinning-wheel.gif')}}"
                                                                 id="loading_1"
                                                                 style="width: 27px;margin: 5px;display: none"
                                                                 alt="">
                                                            <div class="form-group"
                                                                 style="display: none;border-bottom: 1px solid #EBEBEB;"
                                                                 id="cityDev_1">
                                                                <select id="city_1" class="form-control skill_input"
                                                                        name="sillExperienceCity[]"
                                                                        class="citySelect"
                                                                        style="display: none;width: 220px">
                                                                    <option value="" readonly="">City</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <select class="form-control skill_input"
                                                                        name="skill[]">
                                                                    <option readonly=""
                                                                            value="">{{ t('Skills Set') }}
                                                                    </option>
                                                                    @foreach($data['employee_skills'] as $item)
                                                                    <option value="{{$item->skill}}">{{$item->skill}}</option>
                                                                    @endforeach
                                                                </select>
                                                                {{-- <i class="fa fa-caret-down"></i> --}}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <select class="form-control skill_input"
                                                                        name="experience[]">
                                                                    <option readonly=""
                                                                            value=""> {{ t('Experience') }}
                                                                    </option>
                                                                    @foreach ($data['exp'] as $item)
                                                                    <option value="{{$item->name}}">{{$item->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                                {{-- <i class="fa fa-caret-down"></i> --}}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <input type="date"
                                                                       class="form-control skill_input mindate"
                                                                       onchange="check_date(this)"
                                                                       name="sillExperiencestartdate[]">
                                                                <small style="color:red">* {{ t('Start date') }}</small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <input type="date"
                                                                       class="form-control skill_input mindate"
                                                                       onchange="check_date(this)"

                                                                       name="sillExperienceenddate[]">
                                                                <small style="color:red">* {{ t('End date') }}</small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                        <textarea rows="6"
                                                                                  class="form-control skill_input"
                                                                                  maxlength="200"
                                                                                  name="sillExperienceDis[]"
                                                                                  placeholder="{{ t('Short Description of Your Tasks') }} ."></textarea>
                                                                {{-- <input type="text" class="form-control" maxlength="100" --}}
                                                                            {{-- required="required" name="sillExperienceDis[]" --}}
                                                                            {{-- placeholder="Short Description of Your Tasks">--}}
                                                            </div>


                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <button class="mybtn" style="width: unset;" type="button"
                                                            name="Author" id="addSe">+ {{ t('More') }}
                                                    </button>
                                                    <button class="mybtn" style="width: unset;" type="button"
                                                            name="X" id="removeSe">X
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="about">
                                <div class="row">
                                    <label hidden class="col-md-3 col-form-label">
                                                                                <span class="label-create-resume">

                                                                                </span>
                                    </label>
                                    <div class="col-md-6" hidden>
                                        <div class="form-group"
                                             style="border-bottom: 1px solid #EBEBEB;">

                                            <select class="skill_set" multiple
                                                    name="employee_skill[]">
                                                @foreach($data['employee_skills'] as $item)
                                                <option value="{{$item->skill}}">{{$item->skill}}</option>
                                                @endforeach

                                            </select>
                                            {{-- <i class="fa fa-caret-down"></i> --}}
                                        </div>
                                    </div>

                                </div>
                            </div>


                            <br>
                            {{--Education & Degree--}}
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">
                                        {{ t('Education & Degree') }}

                                    </div>
                                    <input type="checkbox" id="education_check" name="education_check"
                                           style="position: relative;top:1px;">
                                    <label for="education_check" style="font-size: 12px;">Note: I hereby verify that
                                        I do not own an official college or university degree.</label>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 ">
                                            <div class="row" id="disbaledRow">
                                                <div class="col-md-9" id="universityEdu">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <input type="text"
                                                                       class="form-control requfeild universityfeild"
                                                                       maxlength="80"
                                                                       name="university[]"
                                                                       placeholder="{{ t('University or College') }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <input type="text"
                                                                       class="form-control requfeild degreefeild"
                                                                       maxlength="80"
                                                                       name="degree[]"
                                                                       placeholder="{{ t('Degree') }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <input placeholder="{{t('Start Date')}}"
                                                                       class="form-control requfeild datestartfeild"
                                                                       type="text"
                                                                       name="dateStart[]"
                                                                       onchange="check_date(this)"
                                                                       onfocus="(this.type='date')">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group ">
                                                                <input placeholder="{{t('End Date')}} "
                                                                       class="form-control requfeild  dateendfeild"
                                                                       type="text"
                                                                       name="dateEnd[]"
                                                                       onchange="check_date(this)"
                                                                       onfocus="(this.type='date')">
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <button class="mybtn" style="width: unset;" type="button"
                                                            name="Author" id="addUd">+ {{ t('More') }}
                                                    </button>
                                                    <button class="mybtn" style="width: unset;" type="button"
                                                            name="X" id="removeUd">X
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            {{--INTERESTS--}}
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">
                                        {{ t('Interests') }} <span hidden style="color: red;">*</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 ">
                                            <div class="row">
                                                <div class="col-md-9" id="interestsDes">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control"
                                                                       maxlength="20" name="interests[]"
                                                                       placeholder="{{ t('Reading,Travelling,Gaming') }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                        <textarea rows="6" class="form-control"
                                                                                  maxlength="100" name="interestDes[]"
                                                                                  placeholder="{{ t('Short Description') }}"></textarea>

                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <button class="mybtn" style="width: unset;" type="button"
                                                            name="Author" id="addHD">+ {{ t('More') }}
                                                    </button>
                                                    <button class="mybtn" style="width: unset;" type="button"
                                                            name="X" id="removeHD">X
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <br>
                            {{--Social links--}}
                            <div id="information" class="row" hidden>
                                <label class="col-md-3 col-form-label"><span class="label-create-resume">Social Links</span></label>
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="url" class="form-control" maxlength="100"
                                                       name="facebook" placeholder="Facebook (optional)">
                                                <input type="url" class="form-control" maxlength="100"
                                                       name="facebook" placeholder="Facebook (optional)">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="url" class="form-control" maxlength="100"
                                                       name="twitter" placeholder="Twitter (optional)">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="url" class="form-control" maxlength="100"
                                                       name="linkedin" placeholder="Linkedin (optional)">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="url" class="form-control" maxlength="100"
                                                       name="instagram" placeholder="Instagram (optional)">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="url" class="form-control" maxlength="100"
                                                       name="tiktok" placeholder="Tik Tok (optional)">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="information" class="row" style="display:none;" hidden>
                                <label class="col-md-3 col-form-label">
                                            <span class="label-create-resume">Resume via YouTube link
                                            (optional)
                                            </span></label>
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                        <textarea class="form-control"
                                                                  name="videoUrl"
                                                                  placeholder="Paste youtube embedded code"></textarea>
                                                <small>Copy from youtube and paste here youtube embedded code
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="information" class="row" hidden>
                                <label class="col-md-3 col-form-label">
                                            <span class="label-create-resume">
                                                Upload file(optional)
                                            </span></label>
                                <div class="col-md-9">
                                    <div class='file-input'>
                                        <div class="file-upload">
                                            <div class="file-select">
                                                <div class="file-select-button"
                                                     id="fileName">{{ t('Resume File') }}
                                                </div>
                                                <div class="file-select-name" id="noFile">No file chosen...
                                                </div>
                                                <input type="file" name="file" id="chooseFile">
                                            </div>
                                        </div>
                                        <span class='label'
                                              style="font-size: 10px;">{{ t('Your resume') }} <span>({{ t('File types: :file_types', ['file_types' => showValidFileTypes('file')]) }})</span></span>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="button mybtn submitbutton" type="submit"
                                            name="send">{{ t('Register') }}
                                    </button>
                                </div>
                            </div>
                            <br>
                        </div>
                    </form>
                </div>


            </div>
            <div class="col-md-4 reg-sidebar">
                <div class="reg-sidebar-inner text-center">
                    <div class="promo-text-box"><i class=" icon-picture fa fa-4x icon-color-1"></i>
                        <h3><strong>{{ t('Post a Job') }}</strong></h3>
                        <p>
                            {{ t('Do you have a post to be filled within your company',
                            ['appName' => config('app.name')]) }}
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
<style>
    .label-create-resume {
        padding: 10px;
        background: #22d3fd;
        color: #fff;
    }


    .label-create-resume::after {
        width: 0;
        height: 0;
        border-top: 19px solid transparent;
        border-bottom: 19px solid transparent;
        border-left: 31px solid #22d3fd;
        content: "";
        position: absolute;
        margin-top: -9px;
        border-right: 10px solid transparent;
    }


</style>
@endsection
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet"/>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script><!-- jQuery base library needed -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>


@section('after_styles')
<link href="{{ url('assets/plugins/bootstrap-fileinput/css/fileinput.min.css') }}" rel="stylesheet">
@if (config('lang.direction') == 'rtl')
<link href="{{ url('assets/plugins/bootstrap-fileinput/css/fileinput-rtl.min.css') }}" rel="stylesheet">
@endif
<style>
    .krajee-default.file-preview-frame:hover:not(.file-preview-error) {
        box-shadow: 0 0 5px 0 #666666;
    }
</style>
@endsection


<script>
    function validateAge(birthday) {
        var today = new Date();
        var birthDate = new Date(birthday);
        var age = today.getFullYear() - birthDate.getFullYear();
        var m = today.getMonth() - birthDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        if (age < 18) {
            $('#birthdate').val('');
            swal({
                title: "OOPS!",
                text: 'Date of birth must be greater than 18',
                icon: "error",
                button: "Ok",
            });
        }
        return true;
    }
</script>

<script>

    // tinymce.init({
    //     selector: '.textarea',
    //     // selector: '#mytextarea1'
    // });
    $(".countrySelect").select2({
        width: '100%',
        placeholder: '{{t('Select Country')}}',

    });
    $(".citySelect").select2({
        width: '100%',
        placeholder: '{{t('Select City')}}',
    });

</script>
<script>
    $(document).ready(function () {

        $('#phone').keyup(function (e) {
            if (/\D/g.test(this.value)) {
                this.value = this.value.replace(/\D/g, '');
                this.value = '+' + this.value;
            } else {
                this.value = '+' + this.value;
            }
        });
        $('#civilid').keyup(function (e) {
            if (/\D/g.test(this.value)) {
                this.value = this.value.replace(/\D/g, '');
            }
        });
// Thank you for registering on Hungry For Jobs, please check your email and WhatsApp phone number to verify your account and log in to start applying for jobs

        $('#form_sumbit_resume').submit(function () {
            // $('.submitbutton').prop("disabled", true);
            swal({
                title: 'Congrats',
                text: 'Thank you for registering on Hungry For Jobs, please login with given email and password.',
                timer: 20000,
                icon: "success",
                showCancelButton: false,
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                closeOnClickOutside: false,
                buttons: false,
            })

        });

        $(".skill_set").select2({
            width: '100%',
            maximumSelectionLength: 5,
            placeholder: '{{t('Employee Skills(you can choose more than one)
        ')}}',
        // allowClear: true
    })

        $(".degreefeild, .universityfeild,.datestartfeild,.dateendfeild").blur(function () {
            if ($('.degreefeild').val() != '') {
                $('.requfeild').prop('required', true);
            } else if ($('.universityfeild').val() != '') {
                $('.requfeild').prop('required', true);
            } else if ($('.datestartfeild').val() != '') {
                $('.requfeild').prop('required', true);
            } else if ($('.dateendfeild').val() != '') {
                $('.requfeild').prop('required', true);
            } else {
                $('.requfeild').prop('required', false);
            }
        });

        $("#education_check").click(function () {

            if ($('#education_check').not(':checked').length) {
                $('.requfeild').prop('required', true);
                $('#disbaledRow').toggleClass('disbaledEducation');
            } else {
                $('.requfeild').prop('required', false);
                $('#disbaledRow').toggleClass('disbaledEducation');
            }
        });
        $(document).ready(function () {
            $('.skill_input').prop('required', true);
        });


        $("#skill_check").click(function () {

            if ($('#skill_check').not(':checked').length) {
                $('.skill_input').prop('required', true);
                $('.disbaledRowskill').toggleClass('disbaledskill');
            } else {
                $('.skill_input').prop('required', false);
                $('.disbaledRowskill').toggleClass('disbaledskill');
            }
        })

        setTimeout(function () {
            $('.tox-notifications-container').hide();
        }, 2000);
    });
</script>
<script>
    function countrySelected() {
        $('#cityDev').hide();
        $('#_cityDiv').show();
        var code = $('#country').val();
        console.log(code);
        if (code !== '') {
            var action = '{{url(' / getCity / ')}}' + '/' + code;
            $.ajax({
                type: "GET",
                url: action,
                data: {},
                beforeSend: function () {
                    $('#loading').show();
                    $('#city').empty();
                    $('#_cityDiv').hide();
                },
                success: function (result) {
                    $('#city').empty();

                    $.each(result, function (key, value) {
                        console.log(value.name);
                        var html = '';
                        html = '<option value="' + value.id + '" mykey="' + value.name + '">' + value.name + '</option>';
                        $('#city').append(html);
                    });
                    $('#loading').hide();
                    $('#cityDev').show();
                    $('#city').show();
                }
            });
        }
    }

    function citySelected() {
        var c = $('#city').find(':selected').attr("mykey");
        console.log(c);
        $('#lSearch').val(c);
    }
</script>
{{-- Skill and Experience --}}
<script>
    var seCountryCount = 1;

    function SEcountrySelected(j) {
        seCountryCount = j;
        $('#cityDev_' + seCountryCount).hide();
        $('#_cityDiv_' + seCountryCount).show();
        var code = $('#country_' + seCountryCount).val();
        console.log(code, 'code');
        if (code !== '') {
            var action = '{{url(' / getCity / ')}}' + '/' + code;
            $.ajax({
                type: "GET",
                url: action,
                data: {},
                beforeSend: function () {
                    $('#loading_' + seCountryCount).show();
                    $('#city_' + seCountryCount).empty();
                    $('#_cityDiv_' + seCountryCount).hide();
                },
                success: function (result) {
                    console.log(result);
                    $.each(result, function (key, value) {
                        $('#city_' + seCountryCount)
                            .append($("<option></option>")
                                .attr("value", value.name)
                                .attr("mykey", value.id)
                                .text(value.name));
                    });
                    $('#loading_' + seCountryCount).hide();
                    $('#cityDev_' + seCountryCount).show();
                    $('#city_' + seCountryCount).show();
                }
            });
        }
    }

</script>

<script>
    // Skill and Experiencetry

    // Education
    $(document).ready(function () {
        $("#addSe").click(function () {
            seCountryCount++;
            $("#skillAndExperience").append('<div class="row" id="skillAndExperienceR"><div class="col-md-12"><div class="form-group"><input type="text" class="form-control skill_input" maxlength="100"  name="sillExperienceComapny[]" placeholder="{{t('
            Company
            Name
            ')}}"></div></div><div class="col-md-6"><div class="form-group skill_input" style="border-bottom: 1px solid #EBEBEB;"><select id="country_' + seCountryCount + '"  onchange="SEcountrySelected(' + seCountryCount + ')" name="sillExperienceCoutry[]" class="countrySelect form-control skill_input"><option value="" readonly="">Country</option>@foreach($data['
            country
            '] as $key => $item)<option value="{{$item->code}}">{{$item->name}}</option>@endforeach</select></div></div><?php $cityIdError = (isset($errors) and $errors->has('city_id')) ? ' is-invalid' : ''; ?><div class="col-md-6"><img src="{{asset(' / public
            /images/im
            ages / spinning - wheel.gif
            ')}}"id="loading_' + seCountryCount + '"style="width: 27px;margin: 5px;display: none"alt=""><div class="form-group skill_input" style="display: none;border-bottom: 1px solid #EBEBEB;" id="cityDev_' + seCountryCount + '"><select id="city_' + seCountryCount + '"  name="sillExperienceCity[]" class="citySelect form-control skill_input" style="display: none"><option value="" readonly="">City</option></select></div></div><div class="col-md-6"><div class="form-group"><select class="form-control skill_input" name="skill[]"><option readonly="" value="">Skills Set</option>@foreach($data['
            employee_skills
            '] as $item)<option value="{{$item->skill}}">{{$item->skill}}</option>@endforeach</select></div></div><div class="col-md-6"><div class="form-group"><select class="form-control skill_input" name="experience[]"><option readonly="" value="">Experience</option>@foreach ($data['
            exp
            '] as $item)<option value="{{$item->name}}">{{$item->name}}</option>@endforeach</select></div></div><div class="col-md-6"><div class="form-group"><input type="date" class="form-control mindate skill_input" onchange="check_date(this)"  name="sillExperiencestartdate[]"> <small style="color:red">* Start date</small></div></div><div class="col-md-6"> <div class="form-group"> <input type="date" class="form-control mindate skill_input" onchange="check_date(this)"  name="sillExperienceenddate[]"> <small style="color:red">* End date</small></div></div><div class="col-md-12"><div class="form-group"><textarea rows="6" class="form-control skill_input" maxlength="200" name="sillExperienceDis[]" placeholder=" {{t('
            Short
            Description
            of
            Your
            Tasks
            ')}}."></textarea></div></div><hr style="height: 2px;border: none;background: #665c8a;"></div>'
        )


        });
        $("body").on("click", "#removeSe", function () {
            var div = document.querySelector('#skillAndExperienceR:last-child')
            div && div.remove()
        });

        $("#addUd").click(function () {
            $("#universityEdu").append(`<div class="row" id="universityEduR"><div class="col-md-6"><div class="form-group"><input type="text" class="form-control" maxlength="80"name="university[]" required="required" placeholder="{{t('University or College')}}"></div></div><div class="col-md-6"><div class="form-group"><input type="text" class="form-control" maxlength="80" required="required" name="degree[]" placeholder="{{t('Degree')}}"></div></div><div class="col-md-6"><div class="form-group"><input placeholder="{{t('Start Date')}}" required="required"class="form-control" type="text" name="dateStart[]"  onchange="check_date(this)" onfocus="(this.type='date')"></div></div><div class="col-md-6"><div class="form-group"><input placeholder="{{t('End Date')}}" required="required"class="form-control" type="text" name="dateEnd[]" onchange="check_date(this)" onfocus="(this.type='date')"></div></div></div>`);
        });
        $("body").on("click", "#removeUd", function () {
            var div = document.querySelector('#universityEduR:last-child')
            div && div.remove()
        });
    });
    // Employment History
    $(document).ready(function () {
        countrySelected();
    });
    $(document).ready(function () {
        setTimeout(function () {
            $(".tox-statusbar").hide();
        }, 2000);


        $("#addCexp").click(function () {
            $("#companyExp").append('<div class="row" id="companyExpR"><div class="col-md-6"><div class="form-group"><input type="text" class="form-control" required="required" maxlength="80"name="company[]"placeholder="{{t('
            Company
            or
            Organization
            ')}}"></div></div><div class="col-md-6"><div class="form-group"><input type="text" class="form-control" required="required" maxlength="80"name="companyExp[]"placeholder="6 Months"></div></div></div>'
        )

        });
        $("body").on("click", "#removeCexp", function () {
            var div = document.querySelector('#companyExpR:last-child')
            div && div.remove()
        });
    });
    // interestDes
    $(document).ready(function () {
        $("#addHD").click(function () {
            $("#interestsDes").append('<div class="row" id="interestsDesR"><div class="col-md-12"><div class="form-group"><input type="text"   class="form-control" maxlength="20" name="interests[]" placeholder="{{t('
            Reading, Travelling, Gaming
            ')}}"></div></div><div class="col-md-12"><div class="form-group"><textarea rows="6" class="form-control" maxlength="100" name="interestDes[]" placeholder="{{t('
            Short
            Description
            ')}}"></textarea> </div></div></div>'
        )

        });
        $("body").on("click", "#removeHD", function () {
            var div = document.querySelector('#interestsDesR:last-child')
            div && div.remove()
        });
    });

    function check_date(thi) {
        var selectedText = thi.value;
        var selectedDate = new Date(selectedText);
        var now = new Date();

        if (selectedDate > now) {
            thi.value = "";
            swal({
                title: "OOPS!",
                text: 'Date must be in the past',
                icon: "error",
                button: "Ok",
            });
        }
        return true;
    }
</script>
{{--    <!-- Call to Action -->--}}
{{--    @include('pages.inc.post-resume')--}}
{{--    <!-- Call to Action End -->--}}
<script>
    $('#chooseFile').bind('change', function () {
        var filename = $("#chooseFile").val();
        if (/^\s*$/.test(filename)) {
            $(".file-upload").removeClass('active');
            $("#noFile").text("No file chosen...");
        } else {
            $(".file-upload").addClass('active');
            $("#noFile").text(filename.replace("C:\\fakepath\\", ""));
        }
    });
</script>
@section('after_scripts')
<script src="{{ url('assets/plugins/bootstrap-fileinput/js/plugins/sortable.min.js') }}"
        type="text/javascript"></script>
<script src="{{ url('assets/plugins/bootstrap-fileinput/js/fileinput.min.js') }}" type="text/javascript"></script>
<script src="{{ url('assets/plugins/bootstrap-fileinput/themes/fa/theme.js') }}" type="text/javascript"></script>
<script src="{{ url('js/fileinput/locales/' . config('app.locale') . '.js') }}" type="text/javascript"></script>
@endsection
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

    /*.input-group-text {*/
    /*    background: transparent !important;*/
    /*    color:#22d3fd !important;*/
    /*    border:none !important;*/
    /*    border-bottom: 1px solid #22d3fd !important;*/
    /*}*/

    .icon-color-3, .icon-color-2, .icon-color-1 {
        color: #22d3fd !important
    }
</style>


