@extends('admin.layouts.master')

@section('content')

<div class="row">
    @if (Session::has('flash_notification'))
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="col-xl-12">
                    @include('flash::message')
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ trans('admin.email setting') }}</h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ admin_url('/email_setting_update') }}" method="post">
                                    @csrf
                                    <div class="box-header with-border">
                                        <div class="ml-5">
                                            <h4 class="card-title">{{ trans('admin.email setting') }}</h4>

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-4 col-xs-12">
                                                        <label>{{ trans('admin.status') }}</label>
                                                    </div>
                                                    <div class="col-md-4 col-sm-4 col-xs-12 col-option">
                                                        <input type="hidden" name="id" id="payment_id"
                                                               value="<?php if (!empty($email_setting->id)) {
                                                                echo $email_setting->id;
                                                            } ?>">
                                                        <input type="radio" name="status" value="1" id="Tap_enabled1"
                                                               class="square-purple" <?php if (isset($email_setting->status) && $email_setting->status == 1) {
                                                            echo 'checked';
                                                        } ?>>
                                                        <strong>{{ trans('admin.enable') }}</strong>
                                                    </div>
                                                    <div class="col-md-4 col-sm-4 col-xs-12 col-option">
                                                        <input type="radio" name="status" value="0" id="Tap_enabled2"
                                                               class="square-purple" <?php if (isset($email_setting->status) && $email_setting->status == 0) {
                                                            echo 'checked';
                                                        } ?>>
                                                        <strong>{{ trans('admin.disabled') }}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group pt-4">
                                                <div class="row">
                                                    <div class="col-md-10">
                                                        <label class="control-label">{{ trans('Email') }}</label>
                                                        <input type="text" class="form-control" name="email"
                                                               placeholder="{{ trans('Email') }}" value="<?php if (!empty($email_setting->email)) {
                                                                echo $email_setting->email;
                                                            } ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group pt-4">
                                                <div class="row">
                                                    <div class="col-md-10">
                                                        <label class="control-label">{{ trans('admin.secret_key') }}</label>
                                                        <input type="text" class="form-control" name="key"
                                                               placeholder="{{ trans('admin.secret_key') }}"
                                                               value="<?php if (!empty($email_setting->key)) {
                                                                echo $email_setting->key;
                                                            } ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group pt-4">
                                                <div class="col-sm-10">
                                                    <button type="submit"
                                                            class="btn btn-primary float-right save">{{ trans('admin.save changes') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Validate Email Api</h4>


                                <form action="{{ admin_url('/check_email_setting_update') }}" method="post">
                                    @csrf
                                    <div class="box-header with-border">
                                        <input type="hidden" name="id" id="id" value="<?php if (!empty($email_setting->id)) {
                                            echo $email_setting->id;
                                        } ?>">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-4 col-xs-12">
                                                    <label>{{ trans('admin.status') }}</label>
                                                </div>
                                                <div class="col-md-4 col-sm-4 col-xs-12 col-option">

                                                    <input type="radio" name="check_email_status" value="1"
                                                           id="check_email_status"
                                                           class="square-purple" <?php if (isset($email_setting->check_email_status) && $email_setting->check_email_status == 1) {
                                                        echo 'checked';
                                                    } ?>>
                                                    <strong>{{ trans('admin.enable') }}</strong>
                                                </div>
                                                <div class="col-md-4 col-sm-4 col-xs-12 col-option">
                                                    <input type="radio" name="check_email_status" value="0"
                                                           id="check_email_status"
                                                           class="square-purple" <?php if (isset($email_setting->check_email_status) && $email_setting->check_email_status == 0) {
                                                        echo 'checked';
                                                    } ?>>
                                                    <strong>{{ trans('admin.disabled') }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group pt-4">
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <label class="control-label">{{ trans('admin.api_key') }}</label>
                                                    <input type="text" class="form-control" name="check_email_api_key"
                                                           placeholder="{{ trans('admin.check_email_api_key') }}"
                                                           value="<?php if (!empty($email_setting->check_email_api_key)) {
                                                            echo $email_setting->check_email_api_key;
                                                        } ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group pt-4">
                                            <div class="col-sm-10">
                                                <button type="submit"
                                                        class="btn btn-primary float-right save">{{ trans('admin.save changes') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Mail Gun Setting</h4>

                                <form action="{{ admin_url('/update_mailgun_settings') }}" method="post">
                                    @csrf
                                    <div class="box-header with-border">
                                        <div class="ml-5">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-4 col-xs-12">
                                                        <label>{{ trans('admin.status') }}</label>
                                                    </div>
                                                    <div class="col-md-4 col-sm-4 col-xs-12 col-option">
                                                        <input type="hidden" name="id" id="payment_id"
                                                               value="<?php if (!empty($email_setting->id)) {
                                                            echo $email_setting->id;
                                                        } ?>">
                                                        <input type="radio" name="status_mailgun" value="1" id="status_mailgun1"
                                                               class="square-purple" <?php if (isset($email_setting->status_mailgun) && $email_setting->status_mailgun == 1) {
                                                            echo 'checked';
                                                        } ?>>
                                                        <strong>{{ trans('admin.enable') }}</strong>
                                                    </div>
                                                    <div class="col-md-4 col-sm-4 col-xs-12 col-option">
                                                        <input type="radio" name="status_mailgun" value="0" id="status_mailgun2"
                                                               class="square-purple" <?php if (isset($email_setting->status_mailgun) && $email_setting->status_mailgun == 0) {
                                                            echo 'checked';
                                                        } ?>>
                                                        <strong>{{ trans('admin.disabled') }}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group pt-4">
                                                <div class="row">
                                                    <div class="col-md-10">
                                                        <label class="control-label">{{ trans('Domain Name') }}</label>
                                                        <input type="text" class="form-control" name="domain_name"
                                                               placeholder="{{ trans('Api Key') }}" value="<?php if (!empty($email_setting->domain_name)) {
                                                            echo $email_setting->domain_name;
                                                        } ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group pt-4">
                                                <div class="row">
                                                    <div class="col-md-10">
                                                        <label class="control-label">{{ trans('Api Key') }}</label>
                                                        <input type="text" class="form-control" name="api_key"
                                                               placeholder="{{ trans('Api Key') }}" value="<?php if (!empty($email_setting->api_key)) {
                                                            echo $email_setting->api_key;
                                                        } ?>">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group pt-4">
                                                <div class="col-sm-10">
                                                    <button type="submit"
                                                            class="btn btn-primary float-right save">{{ trans('admin.save changes') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('after_scripts')
@endsection
