@extends('layouts.master')

@section('header')
@includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.header', 'layouts.inc.header'])
@endsection

@section('search')
@parent
@endsection

@section('content')
@includeFirst([config('larapen.core.customizedViewPath') . 'common.spacer', 'common.spacer'])
<br><br>
<div class="main-container inner-page">
    <div class="container">
        <div class="section-content">
            <div class="row">

                <h1 class="text-center title-1" style="text-transform: none;">
                    <strong>{{ t('countries') }}</strong>
                </h1>
                <hr class="center-block small mt-0">

                @if (isset($countryCols))
                <div class="col-md-12 page-content">
                    <div class="inner-box relative">

                        <i class="icon-location-2"></i> {{ t('Select A Country - Where Do You Live Right Now?') }}

                        <div class="row m-0">
                            @if (!empty($countryCols))
                            @foreach ($countryCols as $key => $col)
                            <?php $classBorder = (count($countryCols) == $key + 1) ? ' cat-list-border' : ''; ?>
                            <ul class="cat-list col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6 {{ $classBorder }}">
                                @foreach ($col as $k => $country)
                                <li>
                                    <img src="{{ url()->asset('images/blank.gif') . getPictureVersion() }}"
                                         class="flag flag-{{ ($country->get('icode')=='uk') ? 'gb' : $country->get('icode') }}"
                                         style="margin-bottom: 4px; margin-right: 5px;"
                                    >
                                    <a href="<?php echo url('?d=' . $country['code']) ?>"
                                       title="{!! $country->get('name') !!}"
                                       class="tooltipHere"
                                       data-toggle="tooltip"
                                       data-original-title="{!! $country->get('name') !!}"
                                    >{{ \Illuminate\Support\Str::limit($country->get('name'), 30) }}</a>
                                </li>
                                @endforeach
                            </ul>
                            @endforeach
                            @else
                            <div class="col-12 text-center mb30 text-danger">
                                <strong>{{ t('countries_not_found') }}</strong>
                            </div>
                            @endif
                        </div>

                    </div>
                </div>
                @endif

            </div>

            @includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.social.horizontal', 'layouts.inc.social.horizontal'])

        </div>
    </div>
</div>
@endsection

@section('info')
@endsection

@section('after_scripts')
@endsection
