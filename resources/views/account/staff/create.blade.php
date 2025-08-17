
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
                    <h2 class="title-2"><i class="icon-town-hall"></i> {{ t('Create a new staff') }} </h2>
                    <div class="mb30" style="float: right; padding-right: 5px;">
                        <a href="{{ url('account/staff') }}">{{ t('My staff') }}</a>
                    </div>
                    <div style="clear: both;"></div>
                    <div class="panel-group" id="accordion">
                        <!-- COMPANY -->
                        <div class="card card-default">
                            <div class="card-header">
                                <h4 class="card-title" ><a href="#companyPanel" data-toggle="collapse" data-parent="#accordion" class="card-title-create-staff"> {{ t('Staff details') }} </a></h4>
                            </div>
                            <div class="panel-collapse collapse show" id="companyPanel">
                                <div class="card-body">
                                    <form class="form-horizontal" role="form" method="POST" action="{{ url('account/staff/') }}">
                                        @csrf
                                        <input name="panel" type="hidden" value="companyPanel">

                                        @includeFirst([config('larapen.core.customizedViewPath') . 'account.staff._form', 'account.staff._form'])

                                        <div class="form-group">
                                            <div class="offset-md-3 col-md-9"></div>
                                        </div>

                                        <!-- Button -->
                                        <div class="form-group">
                                            <div class="offset-md-3 col-md-9" style="text-align: right">
                                                <button type="submit" class="btn btn-primary">{{ t('submit') }}</button>
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

@section('after_scripts')


@endsection
