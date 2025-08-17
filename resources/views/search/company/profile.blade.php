
@extends('layouts.master')

@section('search')
@parent
@includeFirst([config('larapen.core.customizedViewPath') . 'search.inc.form', 'search.inc.form'])
@includeFirst([config('larapen.core.customizedViewPath') . 'search.inc.breadcrumbs', 'search.inc.breadcrumbs'])
@includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.advertising.top',
'layouts.inc.advertising.top'])
@endsection

@section('content')
@includeFirst([config('larapen.core.customizedViewPath') . 'common.spacer', 'common.spacer'])
<div class="main-container">
    <div class="container">

        <div class="section-content">
            <div class="inner-box">
                <div class="row">
                    <?php
                    $companyInfoExists = false;
                    $infoCol = 'col-sm-12';
                    if (
                        (isset($company->address) and !empty($company->address)) or
                        (isset($company->phone) and !empty($company->phone)) or
                        (isset($company->mobile) and !empty($company->mobile)) or
                        (isset($company->fax) and !empty($company->fax))
                    ) {
                        $companyInfoExists = true;
                        $infoCol = 'col-sm-6';
                    }
                    ?>
                    <div class="{{ $infoCol }}">
                        <div class="seller-info seller-profile">
                            <div class="seller-profile-img">
                                <?php
                            
                                $logo_show = \App\Helpers\Helper::get_company_logo_AWS($company);
                                ?>
                                <a><img src="{{ $logo_show }}"
                                        class="img-fluid img-thumbnail" alt="img"> </a>
                            </div>
                            <h3 class="no-margin no-padding link-color">
                                @if (auth()->check())
                                @if (auth()->user()->id == $company->user_id)
                                <a href="{{ url('account/companies/' . $company->id . '/edit') }}"
                                    class="btn btn-default">
                                    <i class="fa fa-pencil-square-o"></i> {{ t('Edit') }}
                                </a>
                                @endif
                                @endif
                                @if (auth()->check())
                                @if (auth()->user()->id)
                                <a href="{{ url('companyprofile/' . $company->c_id) }}">
                                    {{ $company->name }}
                                </a>
                                @endif
                                @else
                                {{ $company->name }}
                                @endif

                            </h3>


                            <h6>{{ $cityData->name}}, {{ $company->country->name}}
                                <img src="{{ url()->asset('images/flags/16/' . strtolower($company->country->code) . '.png') }}"/>

                            </h6>

                            <div class="seller-social-list">
                                <ul class="share-this-post">
                                    @if (isset($company->linkedin) and !empty($company->linkedin))
                                    <li><a href="{{ $company->linkedin }}" target="_blank"><i
                                                class="fa icon-linkedin-rect"></i></a></li>
                                    @endif
                                    @if (isset($company->facebook) and !empty($company->facebook))
                                    <li><a class="facebook" href="{{ $company->facebook }}" target="_blank"><i
                                                class="fa fa-facebook"></i></a></li>
                                    @endif
                                    @if (isset($company->twitter) and !empty($company->twitter))
                                    <li><a href="{{ $company->twitter }}" target="_blank"><i
                                                class="fa fa-twitter"></i></a></li>
                                    @endif
                                    @if (isset($company->pinterest) and !empty($company->pinterest))
                                    <li><a class="pinterest" href="{{ $company->pinterest }}" target="_blank"><i
                                                class="fa fa-pinterest"></i></a></li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                        <br>
                        <h3> {{ t('Company Discription') }} </h3>
                        <div class="text-muted">
                            {!! $company->description !!}
                        </div>
                    </div>

                    @if ($companyInfoExists)
                    <div class="{{ $infoCol }}">
                        <div class="seller-contact-info mt5">

                            <dl class="dl-horizontal">
                                <div class="col-md-12">
                                    <p class="company-detail-p">
                                        <?php
                                        $entiteis = '';
                                        $count_en = '';
                                        if (!empty($company->entities)) {
                                            $entiteis = explode(',', $company->entities);
                                            $count_en = array_key_last($entiteis);
                                        }
                                        ?>
                                        <b>{{t('Entities')}}: </b>
                                        @foreach($entiteis as $key => $entitei)
                                        <?= trim($entitei) ?>@if($key < $count_en),&nbsp;@endif @endforeach </p>
                                </div>
                        </div>

                        @if (isset($company->website) and !empty($company->website))
                        <dt>{{ t('Website') }}:</dt>
                        <dd class="contact-sensitive">
                            <a href="{!! $company->website !!}" target="_blank">
                                {!! $company->website !!}
                            </a>
                        </dd>
                        @endif
                        </dl>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @if(!empty($all_companies[0]))
        <div class="col-lg-12 box-title no-border">
            <div class="inner">
                <h2>
                    <small>{{ t('Companies') }}</small>
                </h2>
            </div>
        </div>
        <div class="inner-box">
            <div class="row">
                @foreach($all_companies as $all_company)
            
                    <div class="col-md-3" style="">
                        <a href="{{url("companyprofile/$all_company->id")}}"><img
                                    style="width: 90%; height: 93%!important;"
                                    src="{{ \App\Helpers\Helper::getImageOrThumbnailLink($all_company) }}"
                                    class="img-fluid" alt="img">
                            <center><label>{{$all_company->name}}</label></center>

                        </a>
                    </div>
                @endforeach

            </div>
        </div>
        @endif

        @if(!empty($company->EmployerLogo[0]))
        <div class="col-lg-12 box-title no-border">
            <div class="inner">
                <h2>
                    <small>{{ t('Sub Companies') }}</small>
                </h2>
            </div>
        </div>
        <div class="inner-box">
            <div class="row">
                @foreach($company->EmployerLogo as $logo)

                <div class="col-md-3" style="">
                      <a href="{{ url('/public').'/' .$logo->logo}}" target="_blank">
                    <img style="    width: 74%; height: 93%!important;" src="{{ url('/public').'/' .$logo->logo}}" class="img-fluid"
                        alt="img">
                        </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="section-block" style="margin-top: 20px;">
            <div class="category-list">
                <div class="tab-box clearfix">

                    <!-- Nav tabs -->
                    <div class="col-lg-12 box-title no-border">
                        <div class="inner">
                            <h2>
                                <small>{{ $count->get('all') }} {{ t('Jobs Found') }}</small>
                            </h2>
                        </div>
                    </div>

                    <!-- Mobile Filter bar -->
                    <div class="mobile-filter-bar col-lg-12 hidden">

                    </div>
                    <div class="menu-overly-mask"></div>
                    <!-- Mobile Filter bar End-->


                    <div class="tab-filter hide-xs" style="padding-top: 6px; padding-right: 6px;">

                    </div>
                    <!--/.tab-filter-->

                </div>
                <!--/.tab-box-->

                <div class="listing-filter">
                    <div class="pull-left col-sm-10 col-xs-12">
                        <div class="breadcrumb-list">
                            {!! (isset($htmlTitle)) ? $htmlTitle : '' !!}

                        </div>
                    </div>
                    <div class="pull-right col-sm-2 col-xs-12 text-right text-center-xs listing-view-action">
                        @if (!empty(request()->all()))
                        <a class="clear-all-button text-muted"
                            href="{!! \App\Helpers\UrlGen::search() !!}">{{ t('Clear all') }}</a>
                        @endif
                    </div>
                    <div style="clear:both;"></div>
                </div>
                <!--/.listing-filter-->

                <div class="adds-wrapper jobs-list">
                    @includeFirst([config('larapen.core.customizedViewPath') . 'search.inc.posts', 'search.inc.posts'])
                </div>
                <!--/.adds-wrapper-->

                <div class="tab-box save-search-bar text-center" hidden>
                    @if (request()->filled('q') and request()->get('q') != '' and $count->get('all') > 0)
                    <a name="{!! qsUrl(request()->url(), request()->except(['_token', 'location'])) !!}" id="saveSearch"
                        count="{{ $count->get('all') }}">
                        <i class=" icon-star-empty"></i> {{ t('Save Search') }}
                    </a>
                    @else
                    <a href="#"> &nbsp; </a>
                    @endif
                </div>
            </div>

            <div class="pagination-bar text-center">
                {!! (isset($links)) ? $links : '' !!}
            </div>
            <!--/.pagination-bar -->
        </div>

        <div style="clear:both;"></div>

        <!-- Advertising -->
        @includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.advertising.bottom',
        'layouts.inc.advertising.bottom'])
    </div>

</div>
</div>
@endsection