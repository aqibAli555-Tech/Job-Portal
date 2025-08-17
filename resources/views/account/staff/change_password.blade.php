@extends('layouts.master')
@section('content')
@includeFirst([config('larapen.core.customizedViewPath') . 'common.spacer', 'common.spacer'])
<div class="main-container">
    <div class="container">
        <div class="row">
            <div class="col-md-3 page-sidebar">
                @includeFirst([config('larapen.core.customizedViewPath') . 'account.inc.sidebar',
                'account.inc.sidebar'])
            </div>
            <div class="col-md-9 page-content">
                @include('flash::message')
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
                    html: '<?=$errorMessage?>',
                    icon: "error",
                    confirmButtonText: "<u>Ok</u>",
                });
                </script>
                @endif
                @endif

                <div class="inner-box">
                    <h2 class="title-2"><i class="icon-town-hall"></i> {{ t('Change Staff Password') }} </h2>
                    <div class="mb30" style="float: right; padding-right: 5px;">
                        <a href="{{ url('account/staff') }}">{{ t('My staff') }}</a>
                    </div>
                    <div style="clear: both;"></div>
                    <div id="accordion" class="panel-group">
                        <!-- COMPANY -->
                        <div class="card card-default">
                            <div class="card-header">
                                <h4 class="card-title">
                                    <a href="#companyPanel" data-toggle="collapse" data-parent="#accordion"
                                        class="card-title-create-staff"> {{ t('Staff details') }} </a>
                                </h4>
                            </div>
                            <div class="panel-collapse collapse show" id="companyPanel">
                                <div class="card-body">
                                    <form name="company" class="form-horizontal" role="form" method="POST"
                                        action="{{ url('account/staff/update_passwords') }}"
                                        enctype="multipart/form-data">
                                        {!! csrf_field() !!}
                                        <input name="_method" type="hidden" value="PUT">
                                        <input name="panel" type="hidden" value="companyPanel">
                                        <input name="id" type="hidden" value="{{$staff->id}}">


                                        <div class="form-group row required show-pwd-group">
                                            <label class=" control-label"
                                                for="staff.phone">{{t('old_password')}}</label>
                                            <div class="input-group ">
                                                <span class="icon-append show-pwd">
                                                    <button type="button" class="eyeOfPwd" onclick="oldshowPwd()">
                                                        <i id="eyeIcon_old" class="far fa-eye-slash"
                                                            style="color:#474b51"></i>
                                                    </button>
                                                </span>
                                                <input name="old_password" type="password" class="form-control"
                                                    placeholder="" id="old_password" min="8" value="" required>
                                            </div>
                                        </div>
                                        <div class="form-group row required show-pwd-group">
                                            <label class=" control-label"
                                                for="staff.new_password">{{t('new_password')}}</label>
                                            <div class="input-group ">
                                                <span class="icon-append show-pwd">
                                                    <button type="button" class="eyeOfPwd" onclick="showPwd()">
                                                        <i id="eyeIcon_new" class="far fa-eye-slash"
                                                            style="color:#474b51"></i>
                                                    </button>
                                                </span>
                                                <input name="password" type="password" id="new_password" class="form-control"
                                                    placeholder="" pattern=".{6,}" title="6 characters minimum" value="" required>
                                            </div>
                                        </div>
                                        <div class="form-group row required show-pwd-group">
                                            <label class=" control-label"
                                                for="staff.new_password">{{t('confirm_password')}}</label>
                                            <div class="input-group ">
                                                <span class="icon-append show-pwd">
                                                    <button type="button" class="eyeOfPwd" onclick="showconfirmdPwd()">
                                                        <i id="eyeIcon_con" class="far fa-eye-slash"
                                                            style="color:#474b51"></i>
                                                    </button>
                                                </span>
                                                <input name="confirm_password" type="password" id="confirm_password" class="form-control"
                                                    placeholder="" title="6 characters minimum" pattern=".{6,}" value="" required>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="offset-md-3 col-md-9"></div>
                                        </div>
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
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(".select1").select2({
    maximumSelectionLength: 5,
});
</script>
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
function getCompanydetail(id) {
    var companyid = id;
    var url = siteUrl + '/posts/get_company_by_id/' + companyid;
    $.ajax({
        type: "GET",
        url: url,
        dataType: 'json',
        beforeSend: function() {},
        success: function(c) {
            if (c.lowCredit == 1) {
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
                    } else {}
                }
            } else {
                window.location.replace(siteUrl + '/posts/create/?company=' + companyid);

            }

        },
    });
}
</script>
@endsection