
@extends('layouts.master')

@section('search')
@parent
@includeFirst([config('larapen.core.customizedViewPath') . 'search.company.inc.search', 'search.company.inc.search'])
@endsection

@section('content')
@includeFirst([config('larapen.core.customizedViewPath') . 'common.spacer', 'common.spacer'])
<div class="main-container">
    <div class="container">

        <div class="col-lg-12 content-box">
            <div class="row row-featured row-featured-category row-featured-company">
                <div class="col-lg-12 box-title no-border">
                    <div class="inner">
                        <h2>
                            <span class="title-3">{{ t('Companies List') }}</span>
                            <a class="sell-your-item" href="{{ \App\Helpers\UrlGen::search() }}?q=&l=">
                                {{ t('Browse Jobs') }}
                                <i class="icon-th-list"></i>
                            </a>
                        </h2>
                    </div>
                </div>

                @if (isset($companies) and $companies->count() > 0)
                @foreach($companies as $key => $iCompany)
                <?php
                $companyUrl = \App\Helpers\UrlGen::company(null, $iCompany->id);
                $logo_show = \App\Helpers\Helper::get_company_logo_AWS($iCompany);
                
                ?>
                <div class="col-lg-2 col-md-3 col-sm-3 col-xs-4 f-category">
                    <a href="{{ $companyUrl }}">
                        <img alt="{{ $iCompany->name }}" class="img-fluid"
                             src="{{  $logo_show }}">
                        <h6> {{ t('Jobs at') }}
                            <span class="company-name">{{ $iCompany->name }}</span>
                            <span class="jobs-count text-muted">({{ $iCompany->posts_count }})</span>
                        </h6>
                    </a>
                    @if(!empty($iCompany->EmployerLogo))
                    <div class="row">
                        @foreach($iCompany->EmployerLogo as $logo)
                        <div class="col-md-4">
                            <img style="height: 40px !important;width: 30px;"
                                 class="img-fluid"
                                 src="{{url('public/'.$logo->logo)}}">
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
                @endforeach
                @else
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 f-category" style="width: 100%;">
                    {{ t('no_result_refine_your_search') }}
                </div>
                @endif

            </div>
        </div>

        <div style="clear: both"></div>

        <div class="pagination-bar text-center">
            {{-- {{ (isset($companies)) ? $companies->links() : '' }} --}}
        </div>

    </div>
</div>
@endsection
