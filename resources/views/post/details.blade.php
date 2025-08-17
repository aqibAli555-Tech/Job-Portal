@extends('layouts.master')

<?php
$data = session()->all();
if (!empty(session()->get('country_code_for_details'))) {
    $code = session()->get('country_code_for_details');
    $code = strtolower($code);
    config()->set('country.icode', $code);
    session()->put('country_code', session()->get('country_code_for_details'));
}
$logo_show = \App\Helpers\Helper::get_post_logo($post->company);
?>

@section('content')
    <style>
        .sidebar-card {
            margin-left: 0px !important;
        }

        <?php if( !empty($data['langCode']) && $data['langCode']=="ar") {
        ?>.save-job {
            position: absolute;
            left: 19px;
            top: 10px;
            padding: 6.2px !important;
        }

        .email-job {
            position: absolute;
            left: 45px;
            top: 10px;

        }

        <?php
    }
    
    else {
        ?>.email-job {
            position: absolute;
            right: 20px;
            top: 20px;
        }

        .save-job {
            position: absolute;
            right: 134px;
            top: 20px;
        }

            <?php
        }

        ?>
    </style>

    {!! csrf_field() !!}
    <input type="hidden" id="postId" name="post_id" value="{{ $post->id }}">

    @if (Session::has('flash_notification'))
        @includeFirst([config('larapen.core.customizedViewPath') . 'common.spacer', 'common.spacer'])
            <?php $paddingTopExists = true; ?>
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    @include('flash::message')
                </div>
            </div>
        </div>
            <?php Session::forget('flash_notification.message'); ?>
    @endif
    <div class="main-container">
        <?php if (isset($topAdvertising) and !empty($topAdvertising)) : ?>
        @includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.advertising.top',
        'layouts.inc.advertising.top'], ['paddingTopExists' => $paddingTopExists ?? false])
            <?php
            $paddingTopExists = false;
        endif;

        $post_time = date('M d, Y', strtotime($post->created_at));
        ?>
        <style>
            .posttime {
                display: inline-block;
                margin-bottom: 20px;
            }

            .btn-primary-dark.active,
            .btn-primary-dark:active,
            .btn-primary-dark:focus,
            .btn-primary-dark:hover,
            .open .dropdown-toggle.btn-primary-dark {
                background-color: #22d3fd;
                border-color: #22d3fd;
                color: #fff !important;
            }
        </style>
        <div class="container {{ (isset($topAdvertising) and !empty($topAdvertising)) ? 'mt-3' : 'mt-2' }}">
            <div class="row">
                <div class="col-md-12">

                    <nav aria-label="breadcrumb" role="navigation" class="pull-left">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="icon-home fa"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('country.name') }}</a></li>
                            <li class="breadcrumb-item"><a href="javascript:void(null)"
                                                           style="pointer-events: none;">{{ $post->employeeskill->skill }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ \Illuminate\Support\Str::limit($post->title, 70) }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <?php $post_time = date('M d, Y', strtotime($post->created_at)); ?>
                <div class="col-lg-9 page-content col-thin-right">

                    <div class="inner inner-box items-details-wrapper pb-0">

                        @if($post->postDetail->hide_company_logo != 1)
                            <div class="col-md-2 col-4 mt-md-0 mt-5 no-padding photobox d-md-block">
                                <div class="">
                                    <a href="{{url('/companyprofile')}}/{{$post->user_id}}">
                                        <img class="img-thumbnail no-margin"
                                             src="{{ $logo_show }}"
                                             alt="{{ $post->company->name }}">
                                    </a>
                                </div>
                            </div>
                        @endif
                       
                        <br>
                        <ul class="job-title">
                            <a
                                    href="{{url('/companyprofile')}}/{{$post->user_id}}">{{ \Illuminate\Support\Str::limit($post->title, 70) }}</a>
                            <br><span class="posttime">{{t('Posted On')}} <?= $post_time; ?></span>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="posttime" {!! (config('lang.direction')=='rtl' )
                            ? ' dir="rtl"' : '' !!}>
                            <i class="icon-eye-3"></i> {{ \App\Helpers\Number::short($post->postMeta->visits) }}
                                {{ trans_choice('global.count_views', getPlural($post->postMeta->visits)) }}
                        </span>

                            @if (auth()->check())
                                @if (in_array(auth()->user()->user_type_id, [2]))
                                    {{-- {!! genEmailContactBtn($post) !!}--}}
                                    <form role="form" method="POST"
                                          action="{{ url('account/apply_post/' . $post->id) }}"
                                          enctype="multipart/form-data">
                                        {!! csrf_field() !!}
                                        <input id="from_phone" name="from_phone" hidden
                                               value="{{ old('from_phone', (auth()->check()) ? auth()->user()->phone : '') }}">
                                        <input type="hidden" name="from_email" value="{{ auth()->user()->email }}">
                                        <input type="hidden" name="from_name" value="{{ auth()->user()->name }}">
                                        <input name="url" hidden type="text"
                                               value="{{ \App\Helpers\UrlGen::post($post) }}">
                                        <input name="body" hidden type="text"
                                               value="Hi i am applying for this job title {{$post->title}}</a>">
                                        <input type="hidden" name="country_code" value="{{ config('country.code') }}">
                                        <input type="hidden" name="post_id" value="{{ $post->id }}">
                                        <input type="hidden" name="messageForm" value="1">

                                            <?php $check_applied = \App\Models\Applicant::where('user_id', auth()->user()->id)->where('post_id', $post->id)->get()->toArray(); ?>
                                        @if(!empty($check_applied))
                                            <button class="btn btn-primary-dark email-job" style="right:170px;line-height: 1.7;" type="button">
                                                {{t('Applied')}}
                                            </button>
                                        @else
                                            <button class="btn btn-primary email-job" style="right:170px;line-height: 1.7;" type="submit">
                                                {{ t('Apply to Job') }}
                                            </button>
                                        @endif
                                        <br>
                                    </form>
                                @endif
                            @else
                                <a href="{{ url('login') }}" class="btn btn-primary btn-sm float-right email-job" style="right:140px;">Apply
                                    Now </a>
                                <br><br><br>
                            @endif

                            @if (isset($post->savedByLoggedUser) && $post->savedByLoggedUser->count() > 0)
                                <li>
                                    <a class="btn btn-primary btn-sm save-job" id="save-{{ $post->id }}"
                                       href="javascript:void(0)"
                                       onclick="savePost1(this,<?=$post->id?>)"><span class="fa fa-heart"></span></a>
                                </li>
                                <li>
                                    <a class="btn btn-primary btn-sm email-job" data-toggle="modal"
                                       data-id="{{ $post->id }}" href="#sendByEmail" id="email-{{ $post->id }}"><span
                                                class="icon-mail-2"></span>&nbsp;&nbsp;{{t('Share Job')}} </a>
                                </li>
                            @else
                                @if(!empty(auth()->user()))
                                    @if(auth()->user()->user_type_id !=1)
                                        <li>
                                            <a class="btn btn-primary btn-sm save-job" id="save-{{ $post->id }}"
                                               style="background-color: white;" href="javascript:void(0)"
                                               onclick="savePost1(this,<?=$post->id?>)"><span
                                                        style="color: #22d3fd !important;"
                                                        class="fa fa-heart"></span></a>
                                        </li>
                                        <li>
                                            <a class="btn btn-primary btn-sm email-job" data-toggle="modal"
                                               data-id="{{ $post->id }}" href="#sendByEmail" id="email-{{ $post->id }}"><span
                                                        class="icon-mail-2"></span>&nbsp;&nbsp;{{t('Share Job')}} </a>
                                        </li>

                                    @endif
                                @else
                                    <a class="btn btn-primary btn-sm email-job" data-toggle="modal"
                                       data-id="{{ $post->id }}" href="#sendByEmail" id="email-{{ $post->id }}"><span
                                                class="icon-mail-2"></span>&nbsp;&nbsp;{{t('Share Job')}} </a>
                                @endif

                            @endif
                        </ul>
                        <div class="spacer"></div>
                        <div class="">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>{{t('Company')}}: </strong>
                                    @if (!empty($post->company_id))

                                        @if($post->postDetail->hide_company_logo != 1)
                                            <a href="{{url('/companyprofile')}}/{{$post->user_id}}">
                                                {{ $post->company->name }}
                                            </a>
                                        @else
                                            <span class="item-location">This company decided to hide it's logo & name for this job
                                    post</span>
                                        @endif
                                    @else
                                        {{ $post->company->name }}
                                    @endif
                                    <div class="spacer"></div>
                                </div>
                                <div class="col-md-6">
                                    <strong>{{t('Salary')}}: </strong>
                                    <span class="salary">
                                @if ($post->postMeta->hide_salary == 0)
                                            @if ($post->salary_min > 0 || $post->salary_max > 0)
                                                @if ($post->salary_min > 0)
                                                    {!! \App\Helpers\Number::money($post->salary_min, $post->country_code) !!}
                                                @endif
                                                @if ($post->salary_max > 0)
                                                    @if ($post->salary_min > 0)
                                                        &nbsp;-&nbsp;
                                                    @endif
                                                    {!! \App\Helpers\Number::money($post->salary_max, $post->country_code) !!}
                                                @endif
                                            @else
                                                {!! \App\Helpers\Number::money('--') !!}
                                            @endif
                                            @if (isset($post->salaryType) && !empty($post->salaryType))
                                                {{ t('per') }} {{ $post->salaryType->name }}
                                            @endif
                                        @else
                                            {{ t('Salary Hidden by Employer') }}
                                        @endif
                                </span>
                                    <div class="spacer"></div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>{{t('Start Date')}}: </strong>
                                    {{ !empty($post->as_soon)?t('As Soon As Possible'): date("d M Y", strtotime($post->start_date)) }}
                                    <div class="spacer"></div>
                                </div>
                                <div class="col-md-6">
                                    <strong>{{t('Negotiable')}}: </strong>
                                    {{ !empty($post->negotiable)?'Yes':'No' }}
                                    <div class="spacer"></div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <strong>{{t('Location')}}:</strong>
                                    <span class="item-location">
                                    <a
                                            href="{{ url('latest-jobs?post=&country_code=&q=&l='.$post->postDetail->city->id.'&min_salary=&max_salary=&type[]=') }}">
                                        {{ $post->postDetail->city->name }},
                                    </a>
                                    <a
                                            href="{{ url('latest-jobs?post=&country_code='.$post->country->id.'&q=&l=&min_salary=&max_salary=&type[]=')}}">
                                        {{ $post->country->name }}
                                    </a>
                                    &nbsp;&nbsp;
                                    <img data-toggle="tooltip" data-placement="top" title="{{$post->country->name}}"
                                         src="{{ url()->asset('images/flags/16/' . strtolower($post->country->code) . '.png') }}">
                                </span>
                                    <div class="spacer"></div>
                                </div>
                                <div class="col-md-6">
                                    <strong>{{t('Job Type')}}: </strong>
                                    <a
                                            href="{{  url('latest-jobs?post=&country_code=&q=&l=&min_salary=&max_salary=&type[]='.$post->postType->id) }}">
                                        {{ $post->postType->name }}
                                    </a>
                                    <div class="spacer"></div>
                                </div>
                                <div class="col-md-6">
                                    <strong>{{t('Skill set')}}: </strong>
                                    <?php $skill_id = (!empty($post->employeeskill->id) ? $post->employeeskill->id : 0); ?>
                                    <a
                                            href="{{ url('latest-jobs?post=&country_code=&q='.$skill_id.'&l=&min_salary=&max_salary=&type[]=')  }}">
                                        {{ $post->employeeskill->skill }}
                                    </a>
                                    <div class="spacer"></div>
                                </div>
                                <div class="col-md-6">
                                    <strong>{{t('Transportation Available')}}: </strong>
                                    <?php if (!empty($post->postDetail->transportation_available)) {
                                        echo 'Yes';
                                    } else {
                                        echo 'No';
                                    } ?>
                                    </a>
                                    <div class="spacer"></div>
                                </div>
                                <div class="col-md-6">
                                    <strong>{{t('Overtime Pay available')}}: </strong>
                                    <?php if (!empty($post->postDetail->overtime_pay)) {
                                        echo 'Yes';
                                    } else {
                                        echo 'No';
                                    } ?>
                                    </a>
                                    <div class="spacer"></div>
                                </div>

                                <div class="col-md-6">
                                    <strong>{{t('Housing Available')}}: </strong>
                                    <?php if (!empty($post->postDetail->housing_available)) {
                                        echo 'Yes';
                                    } else {
                                        echo 'No';
                                    } ?>
                                    </a>
                                    <div class="spacer"></div>
                                </div>
                                <div class="col-md-6">
                                    <strong>{{t('Work Experience')}}: </strong>
                                    <?php if (!empty($post->postDetail->experiences)) {
                                        echo t($post->postDetail->experiences);
                                    } ?>
                                    </a>
                                    <div class="spacer"></div>
                                </div>
                                <div class="col-md-6">
                                    <strong>{{t('Gender Preference')}}: </strong>
                                    <?php if (!empty($post->postDetail->gender)) {
                                        echo t($post->postDetail->gender);
                                    } ?>
                                    </a>
                                    <div class="spacer"></div>
                                </div>
                                <?php
                                $nationality = '';
                                if (!empty($post->postDetail->nationality)) {
                                    $nationalities = explode(',', $post->postDetail->nationality);
                                    foreach ($nationalities as $key => $value) {
                                        $nationalityTableData = \App\Models\Nationality::where('id', $value)->first();

                                        if (empty($nationality)) {
                                            $nationality .= $nationalityTableData->name;
                                        } else {
                                            $nationality .= ', ' . $nationalityTableData->name;
                                        }
                                    }
                                }
                                ?>
                                <div class="col-md-6">
                                    <strong>{{t('nationality')}}: </strong>
                                    <?php if (!empty($nationality)) {
                                        echo $nationality;
                                    } ?>
                                    </a>
                                    <div class="spacer"></div>
                                </div>


                                <div class="col-md-6">
                                    @php

                                        if($post->postDetail->post_type == 2){
                                        $skill_sets = $post->postDetail->skills_set;
                                        $skill_sets = str_replace(',', ', ', $skill_sets);
                                        $who_can_apply=" Only These Skills Sets: ".$skill_sets;
                                        }else{
                                        $who_can_apply="All Skills Sets";
                                        }
                                    @endphp
                                    <strong>{{t('Who Can Apply?')}}: </strong>
                                    {{$who_can_apply}}

                                    <div class="spacer"></div>
                                </div>

                                <div class="col-md-6">
                                    <strong>{{t('Type Of Hiring?')}}: </strong>

                                    @if($post->postDetail->who_can_apply == 1)
                                        {{"Local Hire ".$post->country->name;}}
                                        <img data-toggle="tooltip" data-placement="top" title="{{$post->country->name}}"
                                             src="{{ url()->asset('images/flags/16/' . strtolower($post->country->code) . '.png') }}">

                                    @elseif($post->postDetail->who_can_apply == 2)
                                        {{"International Hire  🌎"}}
                                    @else
                                        {{" Both local hire in ".$post->country->name;}}
                                        <img data-toggle="tooltip" data-placement="top" title="{{$post->country->name}}"
                                             src="{{ url()->asset('images/flags/16/' . strtolower($post->country->code) . '.png') }}">
                                        {{"and International hire  🌎"}}
                                    @endif
                                    <div class="spacer"></div>
                                </div>


                            </div>
                        </div>
                        <br>
                        <h4 class="">{{ t('Job Details') }}: </h4>

                        <div>
                            {!! nl2br($post->description) !!}
                        </div>

                        @if (!empty($post->company->description))
                            <br>
                            <h4>{{ t('Company Description') }}: </h4>
                            <div>
                                {!! nl2br(createAutoLink(strCleaner($post->company->description))) !!}
                                <br><br>
                            </div>
                        @endif
                        @if(auth()->check())
                            <a class="btn btn-sm btn-primary "
                               href="{{url('/companyprofile')}}/{{$post->user_id}}">{{t('Company profile')}}</a>
                            <br><br>
                        @endif


                    </div>

                    <!--/.items-details-wrapper-->
                </div>
                <!--/.page-content-->

                <div class="col-lg-3 page-sidebar-right">
                    <aside>

                        @if (auth()->check() && auth()->user()->id == $post->user_id)
                            <a class="btn btn-primary-dark btn-block"
                               href="{{ \App\Helpers\UrlGen::editPost($post) }}">
                                <i class="fa fa-pencil-square-o"></i> {{ t('Edit Job Details') }}
                            </a>
                            <br>
                        @endif
                        <br>

                        <div class="card sidebar-card card-contact-seller">
                            <div class="card-header">{{ t('Company Information') }}</div>
                            <div class="card-content user-info">
                                <div class="card-body text-center">
                                    <div class="seller-info">
                                        <div class="company-logo-thumb mb20">

                                            @if (isset($post->company) && !empty($post->company))
                                                @if($post->postDetail->hide_company_logo != 1)
                                                    <a href="{{ \App\Helpers\UrlGen::company(null, $post->company->id) }}">
                                                        <img alt="Logo {{ $post->company->name }}" class="img-fluid"
                                                             src="{{$logo_show}}">
                                                    </a>
                                                @endif

                                            @else
                                                <img alt="Logo {{ $post->company->name }}" class="img-fluid"
                                                     src="{{$logo_show}}">
                                            @endif
                                        </div>
                                        @if (isset($post->company) && !empty($post->company))

                                            @if($post->postDetail->hide_company_logo != 1)
                                                <h3 class="no-margin">
                                                    <a href="{{url('/companyprofile')}}/{{$post->user_id}}">
                                                        {{ $post->company->name }}
                                                    </a>
                                                </h3>
                                            @else
                                                <span class="item-location">This company decided to hide it's logo & name for this
                                        job post</span>
                                            @endif
                                        @else
                                            @if($post->postDetail->hide_company_logo != 1)
                                                <h3 class="no-margin">{{ $post->company->name }}</h3>
                                            @else
                                                <span class="item-location">This company decided to hide it's logo & name for this
                                        job post</span>
                                            @endif

                                        @endif
                                        <p>
                                            {{ t('Location') }}:&nbsp;
                                            <strong>
                                                <a href="{{url('/latest-jobs?q=&l='.$post->postDetail->id)}}">
                                                    {{ $post->postDetail->city->name }}, {{ $post->country->name }}
                                                </a>
                                            </strong>
                                        </p>
                                        @if (!config('settings.single.hide_dates'))
                                            @if (isset($user) && !empty($user) && !empty($user->created_at_formatted))
                                                <p>{{ t('Joined') }}:
                                                    <strong>{!! $user->created_at_formatted !!}</strong>
                                                </p>
                                            @endif
                                        @endif
                                        @if (isset($post->company) && !empty($post->company))
                                            @if (!empty($post->company->website))
                                                <p>
                                                    {{ t('Web') }}:
                                                    <strong>
                                                        <a href="{{ $post->company->website }}" target="_blank"
                                                           rel="nofollow">
                                                            {{ getHostByUrl($post->company->website) }}
                                                        </a>
                                                    </strong>
                                                </p>
                                            @endif
                                        @endif
                                    </div>
                                    <div class="user-ads-action" hidden>
                                        @if (auth()->check())
                                            @if (auth()->user()->id == $post->user_id)
                                                <a href="{{ \App\Helpers\UrlGen::editPost($post) }}"
                                                   class="btn btn-default btn-block">
                                                    <i class="fa fa-pencil-square-o"></i> {{ t('Update the Details') }}
                                                </a>
                                                @if (config('settings.single.publication_form_type') == '1')
                                                    @if (empty($post->featured))
                                                        <a hidden href="{{ url('posts/' . $post->id . '/payment') }}"
                                                           class="btn btn-success btn-block">
                                                            <i class="icon-ok-circled2"></i> {{ t('Make It Premium') }}
                                                        </a>
                                                    @endif
                                                @endif
                                            @else
                                                @if (in_array(auth()->user()->user_type_id, [2]))
                                                    {{-- {!! genEmailContactBtn($post, true) !!}--}}
                                                @endif
                                                <!-- {!! genPhoneNumberBtn($post, true) !!} -->
                                            @endif
                                                <?php
                                                try {
                                                    if (auth()->user()->can(\App\Models\Permission::getStaffPermissions())) {

                                                        $btnUrl = admin_url('blacklists/add') . '?email=' . $post->email;

                                                        if (!isDemo($btnUrl)) {
                                                            $cMsg = trans('admin.confirm_this_action');
                                                            $cLink = "window.location.replace('" . $btnUrl . "'); window.location.href = '" . $btnUrl . "';";
                                                            $cHref = "javascript: if (confirm('" . addcslashes($cMsg, "'") . "')) { " . $cLink . " } else { void('') }; void('')";

                                                            $btnText = trans('admin.ban_the_user');
                                                            $btnHint = trans('admin.ban_the_user_email', ['email' => $post->email]);
                                                            $tooltip = ' data-toggle="tooltip" data-placement="bottom" title="' . $btnHint . '"';

                                                            $btnOut = '';
                                                            $btnOut .= '<a href="' . $cHref . '" class="btn btn-danger btn-block"' . $tooltip . '>';
                                                            $btnOut .= $btnText;
                                                            $btnOut .= '</a>';

                                                            echo $btnOut;
                                                        }
                                                    }
                                                } catch (\Exception $e) {
                                                }
                                                ?>
                                        @else

                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if (config('settings.single.show_post_on_googlemap'))
                            <div class="card sidebar-card">
                                <div class="card-header">{{ t('location_map') }}</div>
                                <div class="card-content">
                                    <div class="card-body text-left p-0">
                                        <div class="ads-googlemaps">
                                            <iframe id="googlemaps" width="100%" height="250" frameborder="0"
                                                    scrolling="no"
                                                    marginheight="0" marginwidth="0"
                                                    src="https://www.google.com/maps/embed/v1/place
											?key=
											&q={{ (isset($post->city) && !empty($post->city)) ? addslashes($post->city->name) . ',' . config('country.name') : config('country.name') }}"></iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (isVerifiedPost($post))
                            @includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.social.horizontal',
                            'layouts.inc.social.horizontal'])
                        @endif

                        <div class="card sidebar-card" hidden>
                            <div class="card-header">{{ t('Tips for candidates') }}</div>
                            <div class="card-content">
                                <div class="card-body text-left">
                                    <ul class="list-check">
                                        <li> {{ t('Check if the offer matches your profile') }} </li>
                                        <li> {{ t('Check the start date') }} </li>
                                        <li> {{ t('Meet the employer in a professional location') }} </li>
                                    </ul>
                                    <?php $tipsLinkAttributes = getUrlPageByType('tips'); ?>
                                    @if (!\Illuminate\Support\Str::contains($tipsLinkAttributes, 'href="#"') &&
                                    !\Illuminate\Support\Str::contains($tipsLinkAttributes, 'href=""'))
                                        <p>
                                            <a class="pull-right" {!! $tipsLinkAttributes !!}>
                                                {{ t('Know more') }}
                                                <i class="fa fa-angle-double-right"></i>
                                            </a>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>

        @if (config('settings.single.similar_posts') == '1' || config('settings.single.similar_posts') == '2')
            @includeFirst([config('larapen.core.customizedViewPath') . 'home.inc.featured', 'home.inc.featured'],
            ['firstSection' => false])
        @endif

        @includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.advertising.bottom',
        'layouts.inc.advertising.bottom'], ['firstSection' => false])

        @if (isVerifiedPost($post))
            @includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.tools.facebook-comments',
            'layouts.inc.tools.facebook-comments'], ['firstSection' => false])
        @endif

    </div>
@endsection

@section('after_styles')
@endsection
@section('modal_location')
    @parent
    @include('layouts.inc.modal.send-by-email')
@endsection
@section('after_scripts')
    @if (config('services.googlemaps.key'))
        <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.googlemaps.key') }}"
                type="text/javascript"></script>
    @endif


    <script>
        $(document).ready(function () {
            /* Get Post ID */
            $('.email-job').click(function () {
                var postId = $(this).attr("data-id");
                $('input[type=hidden][name=post]').val(postId);
            });
            document.addEventListener("DOMContentLoaded", function () {
                $('#quickLogin').modal('show');
            });

            var QuaryParameter = [];
            var post_title = "{{$post->title??''}}";
            QuaryParameter.push(post_title)
            page_count('post_details', QuaryParameter);


        });
        /* Favorites Translation */
        var lang = {
            labelSavePostSave: "{!! t('Favourite Job') !!}",
            labelSavePostRemove: "{{ t('Favourited Job') }}",
            loginToSavePost: "{!! t('Please log in to save the Ads') !!}",
            loginToSaveSearch: "{!! t('Please log in to save your search') !!}",
            confirmationSavePost: "{!! t('Post saved in favorites successfully') !!}",
            confirmationRemoveSavePost: "{!! t('Post deleted from favorites successfully') !!}",
            confirmationSaveSearch: "{!! t('Search saved successfully') !!}",
            confirmationRemoveSaveSearch: "{!! t('Search deleted successfully') !!}"
        };


        function savePost1(elmt, id) {
            var postId = id;

            $.ajax({
                method: 'POST',
                url: siteUrl + '/ajax/save/post',
                data: {
                    'postId': postId,
                    '_token': $('input[name=_token]').val()
                }
            }).done(function (data) {
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
                    Swal.fire({
                        text: lang.confirmationSavePost,
                        icon: "success",
                        button: "Ok",
                    }).then(function () {
                        location.reload();
                    });

                } else {
                    Swal.fire({
                        text: lang.confirmationRemoveSavePost,
                        icon: "success",
                        button: "Ok",
                    }).then(function () {
                        location.reload();
                    });
                }

                return false;
            });

            return false;
        }

        /* Get Post ID */
        $('.email-job').click(function () {

            var postId = $(this).attr("data-id");
            $('input[type=hidden][name=post]').val(postId);
            var url = siteUrl + '/posts/get_post_data/' + postId;
            $.ajax({
                type: "GET",
                url: url,
                dataType: 'json',
                success: function (c) {
                    if (c) {

                        var fb = document.getElementById('fb_share');
                        fb.href = "https://www.facebook.com/sharer.php?u=" + c + "&t=HFJ Job";

                        var twitter = document.getElementById('twitter_share');
                        twitter.href = "https://twitter.com/intent/tweet?url=" + c;

                        var whatsapp = document.getElementById('whatsapp_share');
                        whatsapp.href = "https://api.whatsapp.com/send?text=" + c;

                        var telegram = document.getElementById('telegram_share');
                        telegram.href = "https://telegram.me/share/url?url=" + c + "&text=HFJ JOB";
                    }
                }
            });
        });

        $('.applybutton').click(function (e) {
            var post_id = $(this).data('id');
            var post_url = $(this).data('url');
            var post_body = $(this).data('body');
            $('#post_id').val(post_id);
            $('#post_url').val(post_url);
            $('#post_body').val(post_body);
            $('#applynowform').prop('action', "{{ url('account/apply_post') }}" + "/" + post_id);
            $('#applynowform').submit();
            $('#overlay').show();
        });
        //     @if(config('settings.single.show_post_on_googlemap'))
        //     /* Google Maps */
        //     getGoogleMaps(
        //         '{{ config('
//         services.googlemaps.key ') }}',
        //         '{{ (isset($post->city) && !empty($post->city)) ? addslashes($post->city->name) . ', ' . config('
//         country.name ') : config('
//         country.name ') }}',
        //         '{{ config('
//         app.locale ') }}'
        //     );
        //     @endif
        // })
        @if(isset($errors) && $errors->any())
        @if(old('sendByEmailForm') == '1')
        $('#sendByEmail').modal();
        @endif
        @endif


    </script>
@endsection