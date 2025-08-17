@extends('layouts.master')
@section('search')
@parent
@includeFirst([config('larapen.core.customizedViewPath') . 'search.inc.form', 'search.inc.form'])
@endsection

@section('content')

<?php

use App\Models\PostType;

if (!empty(session()->get('country_code_for_search'))) {
    $code = session()->get('country_code_for_search');
    $code = strtolower($code);
    config()->set('country.icode', $code);
}

$search_jobs_page_list = [];
?>


<div class="main-container">
    <br>

    @includeFirst([config('larapen.core.customizedViewPath') . 'search.inc.categories', 'search.inc.form'])
    <?php if (isset($topAdvertising) and !empty($topAdvertising)): ?>
        @includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.advertising.top',
        'layouts.inc.advertising.top'], ['paddingTopExists' => true])
    <?php
$paddingTopExists = false;
else:
    if (isset($paddingTopExists) and $paddingTopExists) {
        $paddingTopExists = false;
    }
endif;
?>
    @includeFirst([config('larapen.core.customizedViewPath') . 'common.spacer', 'common.spacer'])

    <div class="container">

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

            <!-- Sidebar -->
            @includeFirst([config('larapen.core.customizedViewPath') . 'search.inc.sidebar', 'search.inc.sidebar'])
            <style>
                .category-list {
                    box-shadow: none;
                    border: none;
                    margin-bottom: 5px;
                    border-radius: 0px !important;
                }
            </style>
            <!-- Content -->
            <div class="col-md-9 page-content col-thin-left">

                <div class="category-list">
                    <h3 class="job-counter-heading">
                        @if($count->get('all') == 0)
                        <small>
                            {{ t('No jobs have been found in this country, to find jobs in other countries click the All Countries filter on the left hand side') }}</small>
                        @else
                        <small>{{ $count->get('all') }} {{ t('Jobs Found') }}</small>
                        @endif
                    </h3>
                    <div class="menu-overly-mask"></div>

                    <div style="margin-top:0px">
                        <div class="p-3">
                            <?php $country = !empty(request()->get('country_code')) ? request()->get('country_code') : '';?>
                            @if($country)
                            <?php $countries = App\Models\Country::where('code', $country)->first();
?>
                            <?php if (!empty($countries)) {
                             $search_jobs_page_list['country_name'] = $countries->name;?>
                                <div class="box">
                                    <?=$countries->name?>
                                    <i class="fas fa-times" onclick="rem(null,null,null,null,null,'<?=request()->get('country_code')?>')"></i>
                                </div>
                            <?php }?>
                            @endif
                            @if(request()->filled('type'))
                            @foreach(request()->get('type') as $type)
                            <?php $post_data = \App\Models\PostType::where('id', $type)->first();?>
                            <?php if (!empty($post_data)) {
                             $search_jobs_page_list['post_type'][] = $post_data->name?>
                                <div class="box">
                                    <?=$post_data->name?>
                                    <i class="fas fa-times" onclick="rem('<?=$type?>')"></i>
                                </div>
                            <?php }?>
                            @endforeach
                            @endif
                            @if(request()->filled('q'))
                            <?php $skill = \App\Models\EmployeeSkill::where('id', request()->get('q'))->first();?>
                            <?php if (!empty($skill)) {
    $search_jobs_page_list['skill_name'] = $skill->skill;?>
                                <div class="box">
                                    <?=$skill->skill?>
                                    <i class="fas fa-times" onclick="rem(null,'<?=request()->get('q')?>')"></i>
                                </div>
                            <?php }?>
                            @endif

                            @if(request()->filled('l'))
                            <?php $city = \App\Models\City::where('id', request()->get('l'))->first();?>
                            <?php if (!empty($city)) {
    $search_jobs_page_list['city_name'] = $city->name;?>
                                <div class="box">
                                    <?=$city->name?>
                                    <i class="fas fa-times" onclick="rem(null,null,'<?=request()->get('l')?>')"></i>
                                </div>
                            <?php }?>
                            @endif
                            @if(request()->filled('min_salary'))
                            <div class="box">
                                @if(!empty(request()->get('min_salary')) && empty(request()->get('max_salary')))
                                Salary Range: <?=request()->get('min_salary')?>+
                                @else
                                Salary Range: <?=request()->get('min_salary')?>
                                - <?=request()->get('max_salary')?>
                                @endif
                                <i class="fas fa-times" onclick="rem(null,null,null,'<?=request()->get('min_salary')?>','<?=request()->get('max_salary')?>')"></i>
                            </div>
                            @endif

                        </div>
                    </div>
                    <style>
                        .box {
                            padding: 5px 10px;
                            background: #d2eef2;
                            display: inline-block;
                            margin-right: 10px;
                            margin-bottom: 10px;
                        }

                        .box .fa-times {
                            font-size: 12px;
                            padding-left: 10px;
                        }
                    </style>
                    <div class="listing-filter hidden-xs">
                        <div class="pull-left col-sm-6 col-xs-12">
                            <div class="breadcrumb-list text-center-xs">
                                {!! (isset($htmlTitle)) ? $htmlTitle : '' !!}
                            </div>
                        </div>
                        <div class="pull-right col-sm-6 col-xs-12 text-right text-center-xs listing-view-action">
                            @if (!empty(request()->all()))
                            <a class="clear-all-button text-muted" href="{!! \App\Helpers\UrlGen::search() !!}">{{ t('Clear all') }}</a>
                            @endif
                        </div>
                        <div style="clear:both;"></div>
                    </div>

                    <div class="adds-wrapper jobs-list">
                        @includeFirst([config('larapen.core.customizedViewPath') . 'search.inc.posts',
                        'search.inc.posts'])
                    </div>

                    @if(auth()->check())

                    @if(auth()->user()->user_type_id==2)

                    <div class="tab-box save-search-bar text-center" hidden>
                        @if (request()->filled('q') and request()->get('q') != '' and $count->get('all') > 0)
                        <a name="{!! qsUrl(request()->url(), request()->except(['_token', 'location']), null, false) !!}" id="saveSearch" onclick="saveSearch1(this)" count="{{ $count->get('all') }}">
                            <i class="icon-star-empty"></i> {{ t('Save Search') }}
                        </a>
                        @else
                        <a href="javascript:void(0)"> &nbsp; </a>
                        @endif
                    </div>
                    @endif
                    @endif
                </div>

                <nav class="pagination-bar mb-5 pagination-sm" aria-label="">
                    {!! $posts->appends(request()->query())->links() !!}
                </nav>

                @if (!auth()->check())

                <div class="col-md-3">
                </div>
                <div class="col-md-9">
                    <div class="post-promo text-center">
                        <h2> {{ t('Looking for a job') }} </h2>
                        <h5> {{ t('Your CV will be in our database so Companies can contact you at anytime in the future') }}
                        </h5>
                        <a href="<?=url('register?user_type_id=2')?>" class="btn btn-primary">
                            {{ t('Upload Your CV') }} <i class="icon-attach"></i>
                        </a>
                    </div>
                </div>

                @else
                @if(auth()->user()->user_type_id == 2)
                <div class="col-md-3">
                </div>
                <div class="col-md-9">
                    <div class="post-promo text-center">
                        <h2> {{ t('Looking for a job') }} </h2>
                        <h5> {{ t('Your CV will be in our database so Companies can contact you at anytime in the future') }}
                        </h5>
                        <a href="#" data-toggle="modal" data-target="#exampleModal" class="btn btn-primary">
                            {{ t('Upload Your CV') }} <i class="icon-attach"></i>
                        </a>
                    </div>
                </div>
                @endif
                @endif
            </div>

            <div style="clear:both;"></div>

            <!-- Advertising -->
            @includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.advertising.bottom',
            'layouts.inc.advertising.bottom'])

        </div>

    </div>
</div>


<form id="companyForm" method="POST" action="{{ \App\Helpers\UrlGen::login() }}" style="display: none">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <input id="companyEmail" name="login" type="hidden" placeholder="Email" class="form-control" value="">
                </div>
                <div class="form-group">
                    <input id="NewcompanyPassword" value="" name="password" type="hidden" class="form-control" placeholder="Password">
                </div>
                <input id="to_upgrade" value="" name="to_upgrade" type="hidden" class="form-control" placeholder="to_upgrade">

                <input type="hidden" name="quickLoginForm" value="1">
                <button type="submit" class="btn btn-success pull-right">Log In</button>
            </div>
        </div>
    </div>
</form>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">RE Submit Resume</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="{{ url('account/resumes/reupload_resume') }}" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Upload CV:</label>
                        <input type="file" id="reupload_resume" name="reupload_resume" class="file-input" accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint,
                  text/plain, application/pdf">
                        @if (auth()->check())
                        <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                        @endif
                    </div>
                    <div class="modal-footer">

                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>

@endsection

@section('modal_location')
@parent
@includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.modal.location', 'layouts.inc.modal.location'])
@endsection

@section('after_scripts')
<?php $post_type_list = !empty($search_jobs_page_list['post_type']) ? json_encode($search_jobs_page_list['post_type']) : '';
?>
<script>
    $(document).ready(function() {
        var QuaryParameter = [];

        var country_name = "{{$search_jobs_page_list['country_name']??''}}";
        var city_name = "{{$search_jobs_page_list['city_name']??''}}";
        var skill_name = "{{$search_jobs_page_list['skill_name']??''}}";
        const jsonData = "<?=$post_type_list ?? ''?>";
        const postTypeList = '';
        if (jsonData.trim() !== '') {
            const postTypeList = JSON.parse(jsonData);
        }
        if(postTypeList != ''){
            $.each(postTypeList, function(index, value) {
                QuaryParameter.push(value); // Perform your action here
            });
        }

        if (country_name != '') {
            QuaryParameter.push(country_name);
        }
        if (city_name != '') {
            QuaryParameter.push(city_name);
        }
        if (skill_name != '') {
            QuaryParameter.push(skill_name);
        }
        console.log(QuaryParameter);
       
        page_count('search_jobs', QuaryParameter);
    });
    $(document).ready(function() {


        $('#postType a').click(function(e) {
            e.preventDefault();
            var goToUrl = $(this).attr('href');
            redirect(goToUrl);
        });
        $('#orderBy').change(function() {
            var goToUrl = $(this).val();

            redirect(goToUrl);
        });
        document.addEventListener("DOMContentLoaded", function() {
            $('#quickLogin').modal('show');
        });
    });
</script>
<script>
    function saveSearch1(elmt) {

        var url = $(elmt).attr('name');
        var countPosts = $(elmt).attr('count');

        $.ajax({
            method: 'POST',
            url: siteUrl + '/ajax/save/search',
            data: {
                'url': url,
                'countPosts': countPosts,
                '_token': $('input[name=_token]').val()
            }
        }).done(function(data) {
            if (typeof data.logged == "undefined") {
                return false;
            }

            /* Guest Users - Need to Log In */
            if (data.logged == 0) {
                $('#quickLogin').modal();
                return false;
            }

            /* Logged Users - Notification */
            if (data.status == 1) {
                alert(lang.confirmationSaveSearch);
            } else {
                alert(lang.confirmationRemoveSaveSearch);
            }

            return false;
        });
        return false;
    }

    function rem(type, q, l, minsalary, maxsalary, country) {
        var currenturl = window.location.href;

        if (type) {
            var mystring = "type[]=" + type;
            currenturl = currenturl.replace(mystring, 'type[]=');
            window.location.href = currenturl;
        } else if (q) {
            var mystring = "q=" + q;
            currenturl = currenturl.replace(mystring, 'q=');
            window.location.href = currenturl;
        } else if (l) {
            var mystring = "&l=" + l;
            currenturl = currenturl.replace(mystring, '&l=');
            window.location.href = currenturl;
        } else if (minsalary) {
            var mystring = "min_salary=" + minsalary;
            currenturl = currenturl.replace(mystring, 'min_salary=');
            var mystring = "&max_salary=" + maxsalary;
            currenturl = currenturl.replace(mystring, '&max_salary=');
            window.location.href = currenturl;
        } else if (country) {
            var mystring = "country_code=" + country;
            currenturl = currenturl.replace(mystring, 'country_code=');
            window.location.href = currenturl;
        }
    }
</script>

@endsection