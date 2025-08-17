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
                <h4 class="card-title">{{ trans('admin.payment_setting') }}</h4>
                <div class="card">
                    <div class="card-body">
                        <form action="{{admin_url('/payment_setting_update')}}" method="post">
                            <div class="box-header with-border">
                                <h3 class="box-title">{{ trans('admin.Tap') }}</h3><br>
                                @if ($message = Session::get('success'))
                                <div class="alert alert-success alert-block col-md-10 pl-5 ml-4">
                                    <button type="button" class="close" data-dismiss="alert">x</button>
                                    <strong>{{ $message }}</strong>
                                </div>
                                @endif
                                <div class="ml-5">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-sm-4 col-xs-12">
                                                <label>{{ trans('admin.status') }}</label>
                                            </div>
                                            <div class="col-md-4 col-sm-4 col-xs-12 col-option">
                                                <input type="hidden" name="payment_id" id="payment_id" value="<?php if (!empty($payment_setting->id)) {
                                                    echo $payment_setting->id;
                                                } ?>">
                                                <input type="radio" name="Tap_enabled" value="1" id="Tap_enabled1"
                                                       class="square-purple" <?php if (isset($payment_setting->Tap_enabled) && $payment_setting->Tap_enabled == 1) {
                                                    echo 'checked';
                                                } ?>>
                                                <strong>{{ trans('admin.enable') }}</strong>
                                            </div>
                                            <div class="col-md-4 col-sm-4 col-xs-12 col-option">
                                                <input type="radio" name="Tap_enabled" value="0" id="Tap_enabled2"
                                                       class="square-purple" <?php if (isset($payment_setting->Tap_enabled) && $payment_setting->Tap_enabled == 0) echo 'checked'; ?> >
                                                <strong>{{ trans('admin.disabled') }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row pt-4">
                                            <div class="col-sm-4">
                                                <label>{{ trans('admin.mode') }}</label>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="radio" name="Tap_mode" value="1" id="Tap_mode1"
                                                       class="square-purple"<?php if (isset($payment_setting->Tap_mode) && $payment_setting->Tap_mode == 1) echo 'checked'; ?> >
                                                <strong>{{ trans('admin.production') }}</strong>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="radio" name="Tap_mode" value="0" id="Tap_mode2"
                                                       class="square-purple" <?php if (isset($payment_setting->Tap_mode) && $payment_setting->Tap_mode == 0) echo 'checked'; ?> >
                                                <strong>{{ trans('admin.sandbox') }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row pt-4">
                                            <div class="col-md-10">
                                                <label class="control-label">{{ trans('admin.secret_key') }}</label>
                                                <input type="text" class="form-control" name="secret_key" placeholder="{{ trans('admin.secret_key') }}"
                                                       value="<?php if (!empty($payment_setting->secret_key)) echo $payment_setting->secret_key; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-12 pt-4">
                                            <button type="submit" class="btn btn-primary float-right save">{{ trans('admin.save changes') }}</button>
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
@endsection

@section('after_scripts')
@endsection
