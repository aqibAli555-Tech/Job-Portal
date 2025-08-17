<?php

use App\Helpers\Helper;
use App\Models\User;

// Search parameters
$queryString = (request()->getQueryString() ? ('?' . request()->getQueryString()) : '');
// Check if the Multi-Countries selection is enabled
$multiCountriesIsEnabled = false;
$multiCountriesLabel = '';
if (config('settings.geo_location.country_flag_activation')) {
    if (!empty(config('country.code'))) {
        if (isset($countries) && $countries->count() > 1) {
            $multiCountriesIsEnabled = true;
            $multiCountriesLabel = 'title="' . t('Select a Country') . '"';
        }
    }
}
$login_company_data = array();
if (auth()->check()) {
    if (empty(auth()->user()->parent_id)) {
        $child_company_data = User::get_child_company(auth()->user()->id);
        $parent_company_data = User::get_parant_company(auth()->user()->id);
        $login_company_data = array_merge($child_company_data, $parent_company_data);
    } else {
        $userCompanyData = User::get_parant_company(auth()->user()->id);
        if (!empty($userCompanyData[0]['parent_id'])) {
            $child_company_data = User::get_child_company($userCompanyData[0]['parent_id']);
            $parent_company_data = \App\Models\User::get_parant_company($userCompanyData[0]['parent_id']);
            $login_company_data = array_merge($child_company_data, $parent_company_data);
        }
    }
}

// Logo Label
$logoLabel = '';
if (request()->segment(1) != 'countries') {
    if (isset($multiCountriesIsEnabled) and $multiCountriesIsEnabled) {
        $logoLabel = config('settings.app.app_name') . ((!empty(config('country.name'))) ? ' ' . config('country.name') : '');
    }
}
$logo_show = !empty(config('settings.app.logo')) ? config('settings.app.logo') : 'app/default/picture.jpg';
$notification = '';
$notification_applicants = '';
$notification_message = '';
$notification_rejected = '';
if (auth()->check()) {
    $notification_rejected = Helper::get_notification('rejected', auth()->user()->id);
    $notification = \App\Models\Notification::get_notification_data();
    $notification_applicants = \App\Models\Notification::get_notification_applicants();
    $notification_message = \App\Models\Notification::get_notification_messages();

}
?>
<div class="header">
    <nav class="navbar fixed-top navbar-site navbar-light bg-light navbar-expand-md" role="navigation">
        <div class="container-fluid">

            <div class="navbar-identity">
                {{-- Logo --}}
                <a href="{{ url('/') }}" class="navbar-brand logo logo-title">
                    {{-- {{url('/storage/app/logo')}}/{{config('settings.app.logo'))}} --}}
                    <img src="{{ url()->asset('icon/logo.png') }}"
                         alt="{{ strtolower(config('settings.app.app_name')??'') }}" class="tooltipHere main-logo"
                         title="" data-placement="bottom" data-toggle="tooltip"
                         data-original-title="{!! isset($logoLabel) ? $logoLabel : '' !!}"/>
                </a>
                {{-- Toggle Nav (Mobile) --}}
                <button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggler pull-right"
                        type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30" width="30" height="30"
                         focusable="false">
                        <title>{{ t('Menu') }}</title>
                        <path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-miterlimit="10"
                              d="M4 7h22M4 15h22M4 23h22"></path>
                    </svg>
                </button>

                {{-- Country Flag (Mobile) --}}
                @if (request()->segment(1) != 'countries')
                    @if (isset($multiCountriesIsEnabled) and $multiCountriesIsEnabled)
                        @if (!empty(config('country.icode')))
                            @if (file_exists(public_path().'/images/flags/24/' . config('country.icode') . '.png'))
                                <button class="flag-menu country-flag d-block d-md-none btn btn-secondary hidden pull-right"
                                        data-target="#selectCountry" data-toggle="modal">
                                    <img src="{{ url('/public/images/flags/24/' . config('country.icode') . '.png') . getPictureVersion() }}"
                                         alt="{{ config('country.name') }}" style="float: left;">
                                    <span class="caret hidden-xs"></span>
                                </button>
                            @endif
                        @endif
                    @endif
                @endif
                @if(!auth()->check())
                    <div class="dropdown nav-register-mobile pull-right dropdown-menu-right">
                        <a class="dropdown-toggle" data-toggle="dropdown">
                            Register
                        </a>
                        <div class="dropdown-menu">
                            <a href="<?= url('register') ?>?user_type_id=2" class="dropdown-item"><i
                                        class="icon-user"></i>Register as Employee (Job seeker)</a>
                            <a href="<?= url('register') ?>?user_type_id=1" class="dropdown-item"><i
                                        class="icon-town-hall"></i>Register as Employer (Company)</a>

                        </div>
                    </div>
                @endif
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-left">
                    {{-- Country Flag --}}

                    @if(auth()->check())
                        @if(!empty($child_company_data))
                            @if(auth()->user()->user_type_id==1)

                                <ul class="nav navbar-nav ml-auto">
                                    <select
                                            style="margin-top: 11px;margin-left: 7px;width: auto;border-radius: 7px;background-color: #22D3FD;display: none"
                                            onchange="change_account()" id="change_account"
                                            class="form-control change_account">
                                        <option>Select a option</option>
                                        @foreach($login_company_data as $user_data)
                                            @if(!empty($user_data['parent_id']))
                                                <option value="{{$user_data['id']}}" <?php if (auth()->user()->id == $user_data['id']) {
                                                    echo 'selected';
                                                } ?>> {{$user_data['name']}}</option>
                                            @endif;
                                        @endforeach
                                    </select>

                                    <li class="nav-item dropdown no-arrow" hidden>
                                        <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                                            <i class="fas fa-sign-in-alt"></i>
                                            <span>{{ auth()->user()->name }} </span>
                                            <i class="icon-down-open-big fa haveValue" id="iconHaveValue"></i>
                                        </a>
                                        <ul id="haveValue" class="dropdown-menu shadow-sm haveValue">
                                                <?php $haveValue = 0; ?>
                                            @foreach($login_company_data as $user_data)
                                                @if($user_data['id'] !=auth()->user()->id && !empty($user_data['parent_id']))
                                                        <?php $haveValue = 1; ?>
                                                    <li class="dropdown-item">
                                                        <a href="javascript:void(0)"
                                                           onclick="login_company('{{$user_data['password_without_hash']}}','{{$user_data['email']}}')"><i
                                                                    class="icon-user-add"></i> {{$user_data['name']}}
                                                        </a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </li>
                                </ul>
                            @endif
                        @endif
                    @endif
                </ul>
                <?php
                if (empty($haveValue)){
                    ?>
                <style>
                    #haveValue {
                        display: none;
                    }

                    #iconHaveValue {
                        display: none;
                    }
                </style>
                    <?php
                }
                ?>

                <ul class="nav navbar-nav ml-auto navbar-right">
                    <?php
                    $addListingCanBeShown = false;
                    $addListingUrl = \App\Helpers\UrlGen::addPost();
                    $addListingAttr = '';
                    if (!auth()->check()) {
                        $addListingCanBeShown = true;
                        if (config('settings.single.guests_can_post_ads') != '1') {
                            $addListingUrl = '#quickLogin';
                            $addListingAttr = ' data-toggle="modal"';
                        }
                    } else {
                        if (in_array(auth()->user()->user_type_id, [1])) {
                            $addListingCanBeShown = true;
                        }
                    }
                    if (config('settings.single.pricing_page_enabled') == '1') {
                        $addListingUrl = \App\Helpers\UrlGen::pricing();
                        $addListingAttr = '';
                    }
                    ?>

                    @if (!auth()->check())
                        <li class="nav-item">
                            @if (config('settings.security.login_open_in_modal'))
                                <a href="#quickLogin" class="nav-link" data-toggle="modal">{{ t('Log In') }}</a>
                            @else
                                <a href="{{ \App\Helpers\UrlGen::login() }}" class="nav-link">{{ t('Log In') }}</a>
                            @endif
                        </li>
                        <li class="nav-item dropdown no-arrow">
                            <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">

                                <span>Register </span>
                                <i class="icon-down-open-big fa"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right">

                                <li class="dropdown-item" style="color:black;font-size: 14px;">
                                    <a href="<?= url('register') ?>?user_type_id=2" class="nav-link"
                                       style="color:black;font-size: 14px;"><i class="icon-user"></i>Register as
                                        Employee
                                        (Job seeker)</a>
                                </li>
                                <li class="dropdown-item">
                                    <a href="<?= url('register') ?>?user_type_id=1" class="nav-link"
                                       style="color:black;font-size: 14px;"><i class="icon-town-hall"></i>Register as
                                        Employer (Company)</a>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            @if (app('impersonate')->isImpersonating())
                                <a href="{{ route('impersonate.leave') }}" class="nav-link">
                                    <i class="icon-logout hidden-sm"></i> {{ t('Leave') }}
                                </a>
                            @else
                                <a href="{{ \App\Helpers\UrlGen::logout() }}" class="nav-link">
                                    <i class="icon-logout"></i> {{ t('Logout') }}
                                </a>
                            @endif

                        </li>

                        <li class="nav-item dropdown no-arrow">
                            <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                                <i class="icon-user fa hidden-sm"></i>
                                <span>{{ auth()->user()->name }}</span>
                                @if($notification->count() >0)
                                    <span
                                            class="badge badge-pill badge-important count-threads-with-new-messages hidden-sm">{{$notification->count()}}</span>
                                @endif
                                <span hidden
                                      class="badge badge-pill badge-important count-threads-with-new-messages hidden-sm">0</span>
                                <i class="icon-down-open-big fa"></i>
                            </a>
                            <ul id="userMenuDropdown"
                                class="dropdown-menu user-menu dropdown-menu-right shadow-sm animate slideIn">
                                @if(\App\Helpers\Helper::check_permission(1))
                                    @if(auth()->user()->user_type_id==1)
                                        @if(!empty(auth()->user()->parent_id))
                                            <li class="dropdown-item"><a
                                                        href="{{url('/profile')}}/{{auth()->user()->id}}"><i
                                                            class="icon-user"
                                                            style="color:black"></i> {{ t('My Profile') }}
                                                </a></li>
                                        @else
                                            <li class="dropdown-item">
                                                <a href="{{url('/profile')}}/{{auth()->user()->id}}"><i
                                                            class="icon-user"
                                                            style="color:black;font-size: 14px;"></i> {{ t('Parent Account Profile') }}
                                                </a>
                                            </li>
                                        @endif
                                    @else
                                        <li class="dropdown-item"><a
                                                    href="{{url('/employee_profile')}}/{{auth()->user()->id}}"><i
                                                        class="icon-user" style="color:black"></i> {{ t('My Profile') }}
                                            </a>
                                        </li>
                                    @endif
                                @endif

                                @if (in_array(auth()->user()->user_type_id, [1]))
                                    @if(auth()->user()->parent_id== auth()->user()->id &&
                                    \App\Helpers\Helper::check_permission(2))
                                        <a href="{{ url('account/companies') }}">
                                            <li class="dropdown-item dropdown-item-main"><i class="icon-town-hall"
                                                                                            style="color:black"></i> {{ t('My companies') }}
                                                <i class="fas fa-angle-down my_companies_arrow"></i>
                                        </a>
                                        <ul class="dropdown-inner">
                                                <?php
                                                $companies = \App\Models\Company::get_company_by_id();
                                                ?>
                                            @foreach($companies as $company)
                                                    <?php
                                                if (!empty($company->c_id)){
                                                    $users = \App\Models\User::get_user_by_id($company->c_id);
                                                if (!empty($users->password_without_hash)){
                                                    ?>
                                                <li class="dropdown-item dropdown-item-child"><a
                                                            onclick="login_company('{{$users->password_without_hash}}','{{$users->email}}')"><i
                                                                class="icon-town-hall"
                                                                style="color:black"></i> {{$users->name}}
                                                    </a>
                                                </li>
                                                    <?php
                                                }
                                                }
                                                    ?>

                                            @endforeach
                                            <li class="dropdown-item dropdown-item-child"><a
                                                        href="{{ url('account/companies/create') }}"><i
                                                            class="icon-plus"
                                                            style="color:black"></i>{{t('New Company')}}</a></li>
                                        </ul>
                        </li>

                    @endif
                    @if(auth()->user()->id==auth()->user()->parent_id && empty($staff_id))
                        <li class="dropdown-item">
                            <a {!! ($pagePath=='account-staff' ) ? ' class=" active"' : '' !!}
                               href="{{url('/account/staff')}}">
                                <i class="icon-users" style="color:black"></i>
                                {{ t('My staff') }}
                            </a>
                        </li>
                    @endif
                    @if(!empty(auth()->user()->parent_id))
                        @if(\App\Helpers\Helper::check_permission(3))
                            <li class="dropdown-item"><a href="{{ url('account/my-posts') }}"><i class="icon-bookmarks"
                                                                                                 style="color:black"></i>{{ t('My ads') }}
                                </a>
                            </li>
                        @endif
                        @if(\App\Helpers\Helper::check_permission(4))
                            <li class="dropdown-item"><a
                                        href="{{ url('/search-resumes/?cat=&country=&city=&keyword=&limit=&offset=0&send=') }}"><i
                                            class="icon-search" style="color:black"></i>{{ t('Search CV') }}
                                </a></li>
                        @endif
                        @if(\App\Helpers\Helper::check_permission(5))
                            <li class="dropdown-item"><a href="{{ url('account/favorite-resumes') }}"><i
                                            class="icon-heart"
                                            style="color:black"></i> {{ t('Favorite Employee') }}
                                </a></li>
                        @endif
                        @if(\App\Helpers\Helper::check_permission(6))
                            <li class="dropdown-item"><a href="{{ url('account/applied_applicants') }}"><i
                                            class="icon-user-add"
                                            style="color:black"></i>{{ t('Applicants') }}
                                    @if($notification_applicants->count() >0)
                                        <span
                                                class="badge badge-pill badge-important count-threads-with-new-messages hidden-sm">{{$notification_applicants->count()}}</span>
                                    @endif
                                </a>
                            </li>
                        @endif
                        @if(\App\Helpers\Helper::check_permission(7))

                            <li class="dropdown-item"><a href="{{ url('/account/Saved-Resume') }}"><i
                                            class="icon-doc-new"
                                            style="color:black"></i>{{ t('Saved CV') }}
                                </a></li>
                        @endif
                        @if(\App\Helpers\Helper::check_permission(8))

                            <li class="dropdown-item"><a href="{{ url('account/Unlocked-Contact-Cards') }}"><i
                                            class="icon-lock-open" style="color:black"></i>{{ t('Unlocked Contact') }}
                                </a></li>
                        @endif
                        @if(\App\Helpers\Helper::check_permission(9))

                            <li class="dropdown-item"><a href="{{ url('account/archived') }}"><i
                                            class="icon-folder-close"
                                            style="color:black"></i>{{ t('Archived ads') }}
                                </a></li>
                        @endif

                        @if(\App\Helpers\Helper::check_permission(6))
                            <li>
                                    <?php
                                    $posts = \App\Models\Post::where(function ($query) {
                                        $query->where('is_post_expire', 1)
                                            ->orWhere('archived', 1);
                                    })->where('user_id', auth()->user()->id)
                                        ->get();
                                    $postid = [];
                                    if (!empty($posts)) {
                                        foreach ($posts as $post) {
                                            $postid[] = $post->id;
                                        }
                                    }


                                    if (!empty($postid)) {
                                        $totalapplicatns = \App\Models\Applicant::whereIn('post_id', $postid)->count();

                                    } else {
                                        $totalapplicatns = 0;
                                    }
                                    ?>
                                <a{!! ($pagePath=='Archive_applicants' ) ? ' class="active"' : '' !!} style=""
                                href="{{ url('/account/Archive_applicants') }}
                                "><i class="icon-user-add" style="color:black"></i> {{ t('Archived_applicants') }}
                                <!-- <i class="fas fa-question-circle" title="Check all Applicants" ></i> -->
                                </a>
                            </li>
                        @endif
                        @if(\App\Helpers\Helper::check_permission(10))

                            <li class="dropdown-item">
                                <a href="{{ url('account/messages') }}">
                                    <i class="icon-chat" style="color:black"></i> {{ t('messenger') }}
                                    @if($notification_message->count() >0)
                                        <span
                                                class="badge badge-pill badge-important count-threads-with-new-messages hidden-sm">{{$notification_message->count()}}</span>
                                    @endif

                                </a>
                            </li>
                            <li class="dropdown-item">
                                <a href="{{ url('account/messages/message_request') }}">
                                    <i class="icon-chat-1"
                                       style="color:black"></i>&nbsp;&nbsp;{{ t('bulk_chat_requests') }}&nbsp;&nbsp;
                                </a>
                            </li>
                        @endif
                        @if(\App\Helpers\Helper::check_permission(11))

                            <li class="dropdown-item"><a href="{{ url('account/transactions') }}"><i
                                            class="icon-credit-card-1"
                                            style="color:black"></i> {{ t('Transactions') }}
                                </a></li>
                        @endif
                        @if(\App\Helpers\Helper::check_permission(12))
                            <li class="dropdown-item"><a href="{{ url('account/upgrade') }}"><i class="icon-up-open-big"
                                                                                                style="color:black"></i> {{ t('Upgrade Account') }}
                                </a></li>
                        @endif
                        @if(auth()->user()->parent_id != auth()->user()->id)
                            <li class="dropdown-item"><a href="javascript:void(0)"
                                                         onclick="login_company('{{$parent_company_data[0]['password_without_hash']}}','{{$parent_company_data[0]['email']}}')">
                                    <img class="class-icon" src="{{url('/storage/app/icon/9.png')}}"/>&nbsp;&nbsp;
                                    {{ t('Go to parent company') }}
                                </a></li>
                        @endif

                    @endif
                    @endif
                    @if (in_array(auth()->user()->user_type_id, [2]))
                        <li class="dropdown-item"><a href="{{ url('account/resumes/user_resume') }}"><i
                                        class="icon-book"
                                        style="color:black"></i>{{ t('My resumes') }}
                            </a></li>
                        <li class="dropdown-item"><a href="{{url('account/Applied-Jobs')}}"><i class="icon-post"
                                                                                               style="color:black"></i>{{ t('Applied Jobs') }}
                                @if($notification_rejected->count() >0)
                                    <span
                                            class="badge badge-pill badge-important count-threads-with-new-messages hidden-sm">{{$notification_rejected->count()}}</span>
                                @endif
                            </a></li>
                        <li class="dropdown-item"><a href="{{ url('account/favourite') }}"><i class="icon-heart"
                                                                                              style="color:black"></i>{{ t('Favourite jobs') }}
                            </a></li>
                        <li class="dropdown-item"><a href="{{ url('account/cv-viewed') }}"><i class="icon-eye"
                                                                                              style="color:black"></i>{{ t('Who viewed your cv') }}
                            </a></li>
                        <li hidden class="dropdown-item"><a href="{{ url('account/saved-search') }}"><img
                                        class="class-icon"
                                        src="{{url('/storage/app/icon/Untitled.png')}}"/>&nbsp;&nbsp;{{ t('Saved searches') }}
                            </a></li>
                        <li class="dropdown-item">
                            <a href="{{ url('account/messages') }}">
                                <i class="icon-chat" style="color:black"></i>{{ t('Chat With Companies') }}&nbsp;&nbsp;
                                @if($notification_message->count() >0)
                                    <span class="badge badge-pill badge-important count-threads-with-new-messages hidden-sm"
                                          style="font-size: smaller;">{{$notification_message->count()}}</span>
                                @endif

                            </a>
                        </li>
                        <li class="dropdown-item" hidden>
                            <a href="{{ url('latest-jobs?q=&l=') }}">
                                <i class="icon-th-list-2 hidden-sm"></i> {{t('Apply To Jobs')}}
                            </a>
                        </li>
                    @endif

                    <!--                                <li class="dropdown-divider"></li>-->
                    <li class="dropdown-item">
                        @if (app('impersonate')->isImpersonating())
                            <a href="{{ route('impersonate.leave') }}"><i class="icon-logout" style="color:black"></i>
                                {{ t('Leave') }}</a>
                        @else
                            <a hidden href="{{ \App\Helpers\UrlGen::logout() }}"><i class="icon-logout"
                                                                                    style="color:black"></i> {{ t('Logout') }}
                            </a>
                        @endif
                    </li>
                </ul>
                </li>
                @endif
                <li class="nav-item browse-job" style="">
                    <a href="{{ url('/search-resumes/?cat=&country=&city=&keyword=&limit=&offset=0&send=') }}"
                       class="nav-link">
                        <i class="fas fa-search"></i>&nbsp{{t('Search Employees')}}
                    </a>
                </li>
                <?php if (auth()->check() && !empty(auth()->user()->user_type_id == 2)) : ?>
                <li class="nav-item pt-1">
                    <a href="{{ url('latest-jobs?post=&country_code=&q=&l=&min_salary=&max_salary=&type[]=') }}"
                       class="btn btn-primary Browse-Jobs btn-sm py-2 px-3">
                        <i class="icon-th-list-2 hidden-sm"></i> {{ t('Browse Jobs') }}
                    </a>
                </li>

                <?php elseif (!auth()->check()): ?>
                <li class="nav-item browse-job " style="">
                    <a href="{{ url('latest-jobs?post=&country_code=&q=&l=&min_salary=&max_salary=&type[]=') }}"
                       class="nav-link">
                        <i class="icon-th-list-2 hidden-sm"></i>{{ t('Search Jobs') }}
                    </a>
                </li>
                <?php endif; ?>
                @if ($addListingCanBeShown)
                        <?php if (!auth()->check()) { ?>
                    <li class="nav-item postadd" hidden>
                        <a class="btn btn-block btn-post btn-add-listing" href="{{ \App\Helpers\UrlGen::login() }}" {!!
                        $addListingAttr !!}>
                            <i class="fa fa-plus"></i> {{ t('Create a Job ad') }}
                        </a>
                    </li>
                    <?php } else{ ?>
                        <?php if (auth()->check() && !empty(auth()->user()->user_type_id == 1)) : ?>
                    <li class="nav-item postadd pt-1">
                        <a class="btn btn-primary create-job-click btn-sm py-2 px-5" onclick="check_subscription()"
                           href="javascript:void(0)" {!! $addListingAttr !!}>
                            <i class="fa fa-plus"></i>&nbsp;&nbsp;&nbsp;{{ t('Create a Job ad') }}
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php } ?>
                @endif&nbsp;&nbsp;&nbsp;

                @if (request()->segment(1) != 'countries')
                    @if (config('settings.geo_location.country_flag_activation'))
                        @if (!empty(config('country.icode')))

                            <li class="flag-menu country-flag tooltipHere nav-item" data-toggle="tooltip"
                                data-placement="{{ (config('lang.direction') == 'rtl') ? 'bottom' : 'right' }}" {!!
                    $multiCountriesLabel !!}>
                                @if (isset($multiCountriesIsEnabled) and $multiCountriesIsEnabled)
                                    <a href="#" data-target="#selectCountry" data-toggle="modal" class="nav-link">
                                        <img class="flag-icon"
                                             src="{{ url()->asset('images/flags/32/' . strtolower(config('country.icode')) . '.png') }}"
                                             alt="{{ config('country.name') }}">
                                        <span class="caret hidden-sm"></span>
                                    </a>
                                @else
                                    <a style="cursor: default;">
                                        <img class="flag-icon no-caret"
                                             src="{{ url('/public/images/flags/32/' . config('country.icode') . '.png') . getPictureVersion() }}"
                                             alt="{{ config('country.name') }}">
                                    </a>
                                @endif
                            </li>

                        @endif
                    @endif
                @endif
                @includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.menu.select-language',
                'layouts.inc.menu.select-language'])
                @if (!auth()->check() || (auth()->check() && in_array(auth()->user()->user_type_id, [1]) &&
                (empty(auth()->user()->parent_id))))
                    @if (config('settings.single.pricing_page_enabled') == '2')
                        <li class="nav-item pricing">
                            <a href="{{ \App\Helpers\UrlGen::pricing() }}" class="nav-link">
                                <i class="fas fa-tags"></i> {{ t('pricing_label') }}
                            </a>
                        </li>
                        @endif
                        @endif
                        </ul>
            </div>
        </div>
    </nav>
</div>


<form id="companyForm" method="POST" action="{{ \App\Helpers\UrlGen::login() }}" style="display: none">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <input id="companyEmail" name="login" type="hidden" placeholder="Email" class="form-control"
                           value="">
                </div>
                <div class="form-group">
                    <input id="NewcompanyPassword" value="" name="password" type="hidden" class="form-control"
                           placeholder="Password">
                </div>
                <input id="to_upgrade" value="" name="to_upgrade" type="hidden" class="form-control"
                       placeholder="to_upgrade">

                <input type="hidden" name="quickLoginForm" value="1">
                <button type="submit" class="btn btn-success pull-right">Log In</button>
            </div>
        </div>
    </div>
</form>
{{--<input type="hidden" id="login_token" value="{{ csrf_token() }}">--}}
<?php if (!empty(request()->get('login_model'))) { ?>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        $('#quickLogin').modal('show');
    });
</script>
<?php } ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function check_subscription() {

        var myurl = '{{ url("check_subscription_availiblity") }}';
        $.ajax({
            url: myurl,
            method: "get",
            success: function (responce) {
                var url = '<?= url('account/upgrade') ?>';

                if (responce == 1) {
                    var message = '<?= t("You have reached the maximum amount of Contact"); ?>';
                    const config = {
                        html: true,
                        title: 'Attention',
                        html: message,
                        icon: 'error',
                        allowOutsideClick: false,
                        confirmButtonText: 'Upgrade',
                        showCancelButton: true,
                    };
                    Swal.fire(config).then((result) => {
                        /* Read more about isConfirmed, isDenied below */
                        if (result.isConfirmed) {
                            window.location.replace(url);
                        } else if (result.isDenied) {
                            return false;
                        }
                    });
                } else {
                    var myurl = '{{ $addListingUrl }}';
                    window.location.replace(myurl);
                }

            }
        });

    }

    function change_account() {

        var id = $('#change_account').val();
        var url = "{{ url('account/messages/getuserbyid')}}";
        $.ajax({
            type: "POST",
            url: url,
            data: {
                id: id
            },
            success: function (c) {
                console.log(c);
                var password = c.password_without_hash;
                var email = c.email;
                login_company(password, email);
            }
        });
    };


    function login_company(password, email, to_upgrade = null) {
        if (email == undefined || email == '') {
            swal({
                title: "OOPS!",
                text: "Error while switch company. Please try again later",
                icon: "error",
                button: "Ok",
            });
            return;
        }
        if (password == undefined || password == '') {
            swal({
                title: "OOPS!",
                text: "Error while switch company. Please try again later",
                icon: "error",
                button: "Ok",
            });
            return;
        }
        $('#companyEmail').val(email);
        $('#NewcompanyPassword').val(password);
        if (to_upgrade != null) {
            $('#to_upgrade').val(1);
        }
        var urlData = '{{url("userdata")}}';

        $.ajax({
            type: "GET",
            url: urlData,
            beforeSend: function () {
                // setting a timeout
                $('#overlay').show();
            },
            success: function (c) {
                // if(c == 1){
                $('#companyForm').submit();

                // }else{
                // alert('Error while switch company. Please try again later.');
                // return;
                // }

            },
        });
    }
</script>
<!-- 1)header desgin issuse,solve it by adding the custom css br code here.... -->