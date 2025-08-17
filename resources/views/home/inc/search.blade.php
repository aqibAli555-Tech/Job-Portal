<?php

// Using data base model of country and employee skill for using values in search box
use App\Models\Country;
use App\Models\EmployeeSkill;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Str;

$emp_skills = EmployeeSkill::getAllskill();
$countrie_name = Country::all();

// Init.
$sForm = [
    'enableFormAreaCustomization' => '0',
    'hideTitles' => '0',
    'title' => t('Find a job near you'),
    'subTitle' => t('Simple, fast and efficient'),
    'bigTitleColor' => '', // 'color: #FFF;',
    'subTitleColor' => '', // 'color: #FFF;',
    'backgroundColor' => '', // 'background-color: #444;',
    'backgroundImage' => '', // null,
    'height' => '', // '450px',
    'parallax' => '0',
    'hideForm' => '0',
    'formBorderColor' => '', // 'background-color: #7324bc;',
    'formBorderSize' => '', // '5px',
    'formBtnBackgroundColor' => '', // 'background-color: #7324bc; border-color: #7324bc;',
    'formBtnTextColor' => '', // 'color: #FFF;',
];

// Get Search Form Options
if (isset($searchFormOptions)) {
    if (isset($searchFormOptions['enable_form_area_customization']) and !empty($searchFormOptions['enable_form_area_customization'])) {
        $sForm['enableFormAreaCustomization'] = $searchFormOptions['enable_form_area_customization'];
    }
    if (isset($searchFormOptions['hide_titles']) and !empty($searchFormOptions['hide_titles'])) {
        $sForm['hideTitles'] = $searchFormOptions['hide_titles'];
    }
    if (isset($searchFormOptions['title_' . config('app.locale')]) and !empty($searchFormOptions['title_' . config('app.locale')])) {
        $sForm['title'] = $searchFormOptions['title_' . config('app.locale')];
        $sForm['title'] = str_replace(['{app_name}', '{country}'], [config('app.name'), config('country.name')], $sForm['title']);
        if (Str::contains($sForm['title'], '{count_jobs}')) {
            try {
                $countPosts = Post::currentCountry()->unarchived()->count();
            } catch (Exception $e) {
                $countPosts = 0;
            }
            $sForm['title'] = str_replace('{count_jobs}', $countPosts, $sForm['title']);
        }
        if (Str::contains($sForm['title'], '{count_users}')) {
            try {
                $countUsers = User::count();
            } catch (Exception $e) {
                $countUsers = 0;
            }
            $sForm['title'] = str_replace('{count_users}', $countUsers, $sForm['title']);
        }
    }
    if (isset($searchFormOptions['sub_title_' . config('app.locale')]) and !empty($searchFormOptions['sub_title_' . config('app.locale')])) {
        $sForm['subTitle'] = $searchFormOptions['sub_title_' . config('app.locale')];
        $sForm['subTitle'] = str_replace(['{app_name}', '{country}'], [config('app.name'), config('country.name')], $sForm['subTitle']);
        if (Str::contains($sForm['subTitle'], '{count_jobs}')) {
            try {
                $countPosts = Post::currentCountry()->unarchived()->count();
            } catch (Exception $e) {
                $countPosts = 0;
            }
            $sForm['subTitle'] = str_replace('{count_jobs}', $countPosts, $sForm['subTitle']);
        }
        if (Str::contains($sForm['subTitle'], '{count_users}')) {
            try {
                $countUsers = User::count();
            } catch (Exception $e) {
                $countUsers = 0;
            }
            $sForm['subTitle'] = str_replace('{count_users}', $countUsers, $sForm['subTitle']);
        }
    }
    if (isset($searchFormOptions['parallax']) and !empty($searchFormOptions['parallax'])) {
        $sForm['parallax'] = $searchFormOptions['parallax'];
    }
    if (isset($searchFormOptions['hide_form']) and !empty($searchFormOptions['hide_form'])) {
        $sForm['hideForm'] = $searchFormOptions['hide_form'];
    }
}

// Country Map status (shown/hidden)
$showMap = false;
if (file_exists(config('larapen.core.maps.path') . config('country.icode') . '.svg')) {
    if (isset($citiesOptions) and isset($citiesOptions['show_map']) and $citiesOptions['show_map'] == '1') {
        $showMap = true;
    }
}
$hideOnMobile = '';
if (isset($searchFormOptions, $searchFormOptions['hide_on_mobile']) and $searchFormOptions['hide_on_mobile'] == '1') {
    $hideOnMobile = ' hidden-sm';
}
?>
@if (isset($sForm['enableFormAreaCustomization']) and $sForm['enableFormAreaCustomization'] == '1')

    @if (isset($firstSection) and !$firstSection)
        <div class="h-spacer"></div>
    @endif

        <?php $parallax = (isset($sForm['parallax']) and $sForm['parallax'] == '1') ? 'parallax' : ''; ?>
    <div class="wide-intro {{ $parallax }}{{ $hideOnMobile }}">
        <div class="dtable hw100" style="background: rgba(0,0,0,0.5)">
            <div class="dtable-cell hw100">
                <div class="container text-center">

                    @if ($sForm['hideTitles'] != '1')
                        <h1 class="intro-title animated fadeInDown"> {{t('Find a job')}} <span
                                    style="color:#22d3fd">{{t('near you ')}}</span></h1>
                        <h2 class="sub animateme fittext3 animated fadeIn" style="color:#fff;">
                            {!! $sForm['subTitle'] !!}
                        </h2>
                    @endif

                    @if ($sForm['hideForm'] != '1')
                        <div class="search-row animated fadeInUp rounded">
                            <form id="search" name="search" action="{{ \App\Helpers\UrlGen::search() }}" method="GET">
                                <div class="row m-0">
                                    <div class="col-md-5 col-sm-12 mb-1 mb-xl-0 mb-lg-0 mb-md-0 search-col relative">
                                        <select name="q" id="select-a-skill"
                                                class="form-control keyword has-icon" placeholder="{{ t('What') }}"
                                                value="">
                                            <option value="">Select a Skill</option>
                                            @foreach($emp_skills as $skills)
                                                <option value="{{$skills->id}}">{{$skills->skill}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-5 col-sm-12 search-col relative locationicon">
                                            <?php
                                            $code = config('country.code');
                                            $city_data = DB::select("select * from cities where country_code = '$code'"); ?>
                                        <select name="l" class="form-control"
                                                id="select-a-location">
                                            <option value="">Select all Cities</option>
                                            @foreach($city_data as $city)
                                                <option value="{{$city->id}}">{{$city->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2 col-sm-12 search-col">
                                        <button class="btn btn-primary btn-search btn-block">
                                            <i class="icon-search"></i> {{ t('Find') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

@else

    @includeFirst([config('larapen.core.customizedViewPath') . 'home.inc.spacer', 'home.inc.spacer'])
    <div class="container">
        <div class="intro rounded">
            <div class="dtable hw100">
                <div class="dtable-cell hw100">
                    <div class="container text-center">

                        <div class="search-row fadeInUp">
                            <form id="search" name="search" action="{{ \App\Helpers\UrlGen::search() }}" method="GET">
                                <div class="row m-0">
                                    <div class="col-md-5 col-sm-12 mb-1 mb-xl-0 mb-lg-0 mb-md-0 search-col relative">
                                        <i class="icon-docs icon-append"></i>
                                        <input type="text" name="q" class="form-control keyword has-icon"
                                               placeholder="{{ t('What') }}" value="">
                                    </div>

                                    <div class="col-md-5 col-sm-12 search-col relative locationicon">
                                        <i class="icon-location-2 icon-append"></i>
                                        <input type="hidden" id="lSearch" name="l" value="">


                                        @if ($showMap)
                                            <input type="text" id="locSearch" name="location"
                                                   class="form-control locinput input-rel searchtag-input has-icon tooltipHere"
                                                   placeholder="{{ t('Where') }}" value="" title=""
                                                   data-placement="bottom"
                                                   data-toggle="tooltip" type="button"
                                                   data-original-title="{{ t('Enter a city name OR a state name with the prefix', ['prefix' => t('area')]) . t('State Name') }}">
                                        @else
                                            <input type="text" id="locSearch" name="location"
                                                   class="form-control locinput input-rel searchtag-input has-icon"
                                                   placeholder="{{ t('Where') }}" value="">
                                        @endif
                                    </div>

                                    <div class="col-md-2 col-sm-12 search-col">
                                        <button class="btn btn-primary btn-search btn-block">
                                            <i class="icon-search"></i> <strong>{{ t('Find') }}</strong>
                                        </button>
                                    </div>
                                    {!! csrf_field() !!}
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endif
<style>
    #select-a-skill option, #select-a-location option {
        background: #000 !important;
    }
</style>
