@extends('admin.layouts.master')
@section('content')
    @if(auth()->user()->user_type_id!=4)
        <div class="row">
            @include('admin.dashboard.inc.stats-boxes')
        </div>
        <div class="row">
            @include('admin.dashboard.inc.revenue')
            @include('admin.dashboard.inc.latest-activitylog')

            @include('admin.dashboard.inc.top_country_employees')
            @include('admin.dashboard.inc.top_country_employers')
            @include('admin.dashboard.inc.top_nationality_employee')
            @include('admin.dashboard.inc.top_skills')
            @include('admin.dashboard.inc.latest-posts')
        </div>
        <div class="row">
            @include('admin.dashboard.inc.latest-users')
            @include('admin.dashboard.inc.latest-contact')
            @include('admin.dashboard.inc.latest-email-queue')
            @include('admin.dashboard.inc.latest-contact-card-problem')
            @include('admin.dashboard.inc.rejected_applicant_reasons')
            @include('admin.dashboard.inc.package_cancel_reasons')
            @include('admin.dashboard.inc.post_archived_reasons')
        </div>
    @else
        <div class="row" style="min-height: 400px;">
            <h4>You have been logged in as subadmin please select menu on left side to perform some action</h4>
        </div>
    @endif
    <style>
        td {
            font-size: 13px;
        }
    </style>
@endsection


