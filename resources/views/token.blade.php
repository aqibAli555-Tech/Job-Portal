{{--
* JobClass - Job Board Web Application
* Copyright (c) BedigitCom. All Rights Reserved
*
* Website: https://bedigit.com
*
* LICENSE
* -------
* This software is furnished under a license and may be used and copied
* only in accordance with the terms of such license and with the inclusion
* of the above copyright notice. If you Purchased from CodeCanyon,
* Please read the full License from here - http://codecanyon.net/licenses/standard
--}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@php 
use Illuminate\Http\Request;
@endphp

@extends('layouts.master')
@section('content')
@includeFirst([config('larapen.core.customizedViewPath') . 'common.spacer', 'common.spacer'])
<div class="main-container">
    <div class="container">
        <div class="row">
            @include('flash::message')


            <div class="col-xl-12">
                <div class="alert alert-info">
                    {{trans('auth.To access your account, please verify your email and phone number SMS to be able to login, if any problems occur please')}} <a href="{{url('contact')}}">Contact Us</a>
                </div>
            </div>
            <div class="col-lg-5 col-md-8 col-sm-10 col-xs-12 login-box">
                <div class="card card-default">
                    <div class="panel-intro text-center">
                        <h2 class="logo-title"><strong>{{ t('Code') }}</strong></h2>
                    </div>

                    <div class="card-body">
                        <form id="tokenForm" role="form" method="POST"
                              action="{{ url(getRequestPath('verify/.*')) }}">
                            {!! csrf_field() !!}

                            <!-- code -->
                            <?php use App\Models\User;

                            $codeError = (isset($errors) and $errors->has('code')) ? ' is-invalid' : ''; ?>
                            <div class="form-group">
                                <label for="code" class="col-form-label">{{ getTokenLabel() }}:</label>
                                <div class="input-icon">
                                    <i class="fa icon-lock-2"></i>
                                    <input id="code"
                                           name="code"
                                           type="text"
                                           placeholder="{{ t('Enter the validation code') }}"
                                           class="form-control{{ $codeError }}"
                                           value="{{ old('code') }}"
                                           autocomplete="one-time-code">
                                </div>
                            </div>
                            <div class="form-group">
                                <button id="tokenBtn" type="submit"
                                        class="btn btn-primary btn-lg btn-block">{{ t('submit') }}
                                </button>
                            </div>
                            <div class="row">
                                <?php $user_data = User::withoutGlobalScopes()->where('id', $request->get('user_id'))->first(); ?>
                                @if($user_data->sms_count < 3)
                                <div class="col-md-6">
                                    <span>{{t('Code not received via SMS?')}}</span>
                                </div>

                                <div class="col-md-6">
                                    <a class="btn btn-primary-dark btn-sm" style="float:right;" href="{{url('verify/user/'.$request->get('user_id').'/resend/sms?form_login=1')}}">{{t('Resend Sms')}}</a>
                                </div>
                                @endif
                            </div>
                        </form>
                    </div>

                    <div class="card-footer text-center">
                        &nbsp;
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('after_scripts')
<script>
    $(document).ready(function () {
        $("#tokenBtn").click(function () {
            $("#tokenForm").submit();
            return false;
        });
    });
</script>
@endsection