
@extends('layouts.master')

@section('content')
@includeFirst([config('larapen.core.customizedViewPath') . 'common.spacer', 'common.spacer'])
<div class="main-container">
    <div class="container">
        <div class="row">

            @if (Session::has('flash_notification'))
            <div class="col-xl-12">
                <div class="row">
                    <div class="col-lg-12">
                        @include('flash::message')
                    </div>
                </div>
            </div>
            @endif
            <div class="col-md-12 page-content">
                @if (!Session::has('flash_notification'))
                @if (Session::has('message'))
                <div class="inner-box">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="alert alert-success pgray alert-lg" role="alert">
                                <p>Please activate your email and login.</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
