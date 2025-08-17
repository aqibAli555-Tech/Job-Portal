@extends('affiliate.layouts.master')
@section('content')
@includeFirst([config('larapen.core.customizedViewPath') . 'common.spacer', 'common.spacer'])
    <div class="main-container">
        <div class="container">
            @include('affiliate/inc/breadcrumbs')
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
            </div>
            <div class="row">
                <div class="col-md-3 page-sidebar">
                    @include('affiliate.inc.sidebar')
                </div>
                <div class="col-md-9 page-content">
                    <div class="inner-box" id="stats-container">
                        @include('affiliate.dashboard.inc.stats-boxes')
                    </div>
                    <div class="mt-2" id="stats-container">
                        <p style="background: #615583;color: #fff;padding: 15px;text-align: left;float: right; border-radius: 10px;">
                            {!! t('You are part of our tiered commission system, which ranges from 10% to 50% based on the value of the commission you earn') !!}
                        </p>                    
                    </div>
                    <div class="inner-box mt-2" id="commission-container">
                        @include('affiliate.dashboard.inc.commission-table')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection