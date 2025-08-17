
@extends('layouts.master')

@section('search')
    @parent
@endsection

@section('content')
    @includeFirst([config('larapen.core.customizedViewPath') . 'common.spacer', 'common.spacer'])
    <style>
        .cat-list ul {
            display: block;
        }
    </style>
    <div class="main-container inner-page">
        <div class="container">
            <div class="section-content">
                <div class="row">

                    @if (session('message'))
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            {{ session('message') }}
                        </div>
                    @endif

                    @if (Session::has('flash_notification'))
                        <div class="col-xl-12">
                            <div class="row">
                                <div class="col-xl-12">
                                    @include('flash::message')
                                </div>
                            </div>
                        </div>
                    @endif

                    @includeFirst([config('larapen.core.customizedViewPath') . 'home.inc.spacer', 'home.inc.spacer'])
                    <h1 class="text-center title-1"><strong>{{ t('Sitemap') }}</strong></h1>
                    <hr class="center-block small mt-0">

                    <div class="col-xl-12">
                        <div class="content-box">
                            <div class="row-featured-category">
                                <div class="col-xl-12 box-title">
                                    <div class="inner">
                                        <h2 style="float: initial;">
                                            <span class="title-3"><span
                                                        style="font-weight: bold;">{{ t('List of Skills') }}</span></span>
                                        </h2>
                                    </div>
                                </div>
                                <br>
                                <div class="col-xl-12">
                                    <div class="list-categories-children styled">
                                        <div class="row">
                                            @foreach ($employee_skills as $key => $col)
                                            
                                                <div class="col-md-4 col-sm-4 {{ (count($employee_skills) == $key+1) ? 'last-column' : '' }}">
                                                    <div class="cat-list">
                                                        <h3 class="cat-title rounded">
                                                            <ul class="cat-collapse collapse show cat-id- long-list-home">
                                                                <li>
                                                                    <a style="color: #4e575d;"
                                                                    href="<?=url('/search-resumes/?cat='.$col->skill.'&country=&city=&keyword=&limit=&offset=0&send=')?>">
                                                                        {{ $col->skill }}
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </h3>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if (isset($cities))
                        @includeFirst([config('larapen.core.customizedViewPath') . 'home.inc.spacer', 'home.inc.spacer'])
                        <div class="col-xl-12">
                            <div class="content-box mb-0">
                                <div class="row-featured-category">
                                    <div class="col-xl-12 box-title">
                                        <div class="inner">
                                            <h2 style="float: initial;">
												<span class="title-3" style="font-weight: bold;">
													<i class="icon-location-2"></i> {{ t('List of Cities in') }} {{ config('country.name') }}
												</span>
                                            </h2>
                                        </div>
                                    </div>

                                    <div class="col-xl-12">
                                        <div class="list-categories-children">
                                            <div class="row">
                                                @foreach ($cities as $key => $cols)
                                                    <ul class="cat-list col-lg-3 col-md-4 col-sm-6 {{ ($cities->count() == $key+1) ? 'cat-list-border' : '' }}">
                                                        @foreach ($cols as $j => $city)
                                                            <li>
                                                                <a href="<?=url('latest-jobs?post=&country_code=&q=&l='.$city->id.'&min_salary=&max_salary=&type[]=')?>"
                                                                   title="{{ t('Free Ads') }} {{ $city->name }}">
                                                                    <strong>{{ $city->name }}</strong>
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
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

@section('before_scripts')
    @parent
    <script>
        var maxSubCats = 15;
    </script>
@endsection
