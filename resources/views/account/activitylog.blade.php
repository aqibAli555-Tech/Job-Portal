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

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<style>
    .form-control:disabled, .form-control[readonly]
    {
        background-color:#ffffff !important;
    }
</style>
<?php
    $searchNew = !empty(request('search_new')) ? request('search_new') : '';
    $limit = !empty(request('limit')) ? request('limit') : '';
?>
@include('common.spacer')
<div class="main-container">
    <div class="container">
        @include('account/inc/breadcrumbs')

        <div class="row">
            @if (Session::has('flash_notification'))
            <div class="col-xl-12">
                <div class="row">
                    <div class="col-xl-12">
                        @include('flash::message')
                    </div>
                </div>
            </div>
            @endif
            <div class="col-md-3 page-sidebar">
                @include('account.inc.sidebar')
            </div>


            <!--/.page-sidebar-->

            <div class="col-md-8 page-content">
                <div class="inner-box">
                    <h2 class="title-2"><i class=""></i> {{ t('Activity Logs') }}
                        <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{t('List of activity logs')}}" hidden></i>
                    </h2>
                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group">
                                <input type="search" id="search_new" value="<?= $searchNew ?>" class="form-control" name="search_new" placeholder="Search">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <button type="button" class="btn btn-primary" onclick="resetFields()">Reset</button>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="table-responsive">
                        <table class="table table-striped table-sm table-bordered datatables-activitylogs"
                            data-url="{{ url('account/activity-logs-ajax') }}">
                            <thead>
                            <tr>
                                <th>{{ t('Date') }}</th>
                                <th>{{ t('Description') }}</th>
                            </tr>
                            </thead>
                        </table>
                    </div>

                    <div style="clear:both"></div>

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

@section('after_scripts')

<script>
    function resetFields() {
        document.getElementById('search_new').value = '';
        $('#search_new').trigger('change');
    }
</script>
@endsection
