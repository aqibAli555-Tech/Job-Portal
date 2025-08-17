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
@extends('layouts.master')
@section('content')
@includeFirst([config('larapen.core.customizedViewPath') . 'common.spacer', 'common.spacer'])
<div class="main-container">
    <div class="container">
        <div class="row">
            <div class="col-md-3 page-sidebar">
                @includeFirst([config('larapen.core.customizedViewPath') . 'account.inc.sidebar', 'account.inc.sidebar'])
            </div>
            <!--/.page-sidebar-->
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
                    <h2 class="title-2"><i class="icon-town-hall"></i> {{ t('Edit the Staff') }} </h2>
                    <div class="mb30" style="float: right; padding-right: 5px;">
                        <a href="{{ url('account/staff') }}">{{ t('My staff') }}</a>
                    </div>
                    <div style="clear: both;"></div>
                    <div id="accordion" class="panel-group">
                        <!-- COMPANY -->
                        <div class="card card-default">
                            <div class="card-header">
                                <h4 class="card-title">
                                    <a href="#companyPanel" data-toggle="collapse" data-parent="#accordion" class="card-title-create-staff"> {{ t('Staff details') }} </a>
                                </h4>
                            </div>
                            <div class="panel-collapse collapse show" id="companyPanel">
                                <div class="card-body">
                                    <form name="company" class="form-horizontal" role="form" method="POST"
                                          action="{{ url('account/staff/' . $staff->id) }}"
                                          enctype="multipart/form-data">
                                        {!! csrf_field() !!}
                                        <input name="_method" type="hidden" value="PUT">
                                        <input name="panel" type="hidden" value="companyPanel">
                                        <input name="staff_id" type="hidden" value="{{ $staff->id }}">

                                        @includeFirst([config('larapen.core.customizedViewPath') . 'account.staff._form', 'account.staff._form'])

                                        <div class="form-group row">
                                            <div class="offset-md-3 col-md-9"></div>
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
<script>

    $(".select1").select2({
        maximumSelectionLength: 5,
    });

</script>

@endsection

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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@section('after_scripts')
<script>
    function getCompanydetail(id) {
        var companyid = id;

        var url = siteUrl + '/posts/get_company_by_id/' + companyid;
        $.ajax({
            type: "GET",
            url: url,
            dataType: 'json',
            beforeSend: function () {
                // $('#overlay').show();
            },
            success: function (c) {
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
                        } else {
                        }
                    }
                } else {
                    window.location.replace(siteUrl + '/posts/create/?company=' + companyid);

                }

            },
        });
    }
</script>
@endsection
