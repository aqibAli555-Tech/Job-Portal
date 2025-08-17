@extends('admin.layouts.master')
@section('content')
    @if(auth()->user()->user_type_id!=4)
        <div class="row">
            @include('admin.dashboard.inc.affiliate-stats-boxes')
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