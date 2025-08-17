<?php

use App\Helpers\Helper;
use App\Helpers\UrlGen;
use App\Models\User;
use App\Models\Notification;
use App\Models\Company;
use App\Models\Post;
use App\Models\Nationality;
use App\Models\Country;
// Search parameters
$data['nationality_list'] = Nationality::pluck('id', 'name');
$login_company_data = array();
if (auth()->check()) {
    $child_company_data = User::get_child_company(auth()->user()->id);
    $parent_company_data = User::get_parant_company(auth()->user()->id);
    $login_company_data = array_merge($child_company_data, $parent_company_data);
}

// Logo Label
$logoLabel = '';

$logoLabel = config('settings.app.app_name') . ((!empty(config('country.name'))) ? ' ' . config('country.name') : '');

$logo_show = !empty(config('settings.app.logo')) ? config('settings.app.logo') : 'app/default/picture.jpg';
$notification = '';
$notification_applicants = '';
$notification_message = '';
$notification_rejected = '';
$notification_resume = '';
if (auth()->check()) {
    $notification = Notification::get_notification_data();
    $notification_message = Notification::get_notification_by_type('message');
}
?>


<?php
$countries_list = Country::orderBy('name')->get();
?>

@include('pages.inc.modal')
<div class="header">
    <nav class="navbar fixed-top navbar-site navbar-light bg-light navbar-expand-md" role="navigation">
        <div class="container-fluid">
            <div class="navbar-identity">
                <a href="{{ url('/') }}" class="navbar-brand logo logo-title">
                    <img src="{{ url()->asset('icon/logo.png') }}" onclick="page_count('logo_click')" alt="{{ strtolower(config('settings.app.app_name')??'') }}" class=" main-logo"/>
                </a>
                <button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggler pull-right"
                    type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30" width="30" height="30"
                        focusable="false">
                        <title>{{ t('Menu') }}</title>
                        <path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-miterlimit="10"
                            d="M4 7h22M4 15h22M4 23h22"></path>
                    </svg>
                </button>

                @if (request()->segment(1) != 'countries')
                @if (!empty(config('country.icode')))
                @if (file_exists(public_path().'/images/flags/24/' . config('country.icode') . '.png'))
                <button class="flag-menu country-flag d-block d-md-none btn btn-secondary hidden pull-right"
                    data-target="#selectCountry" data-toggle="modal">
                    <img src="{{ url('/public/images/flags/24/' . config('country.icode') . '.png')}}"
                        alt="{{ config('country.name') }}" style="float: left;">
                    <span class="caret hidden-xs"></span>
                </button>
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
                <ul class="nav navbar-nav ml-auto navbar-right">
                    <?php
                    $addListingCanBeShown = false;
                    $addListingUrl = UrlGen::addPost();
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
                    ?>
                    @if (!auth()->check())
                    <li class="nav-item">
                    <li class="nav-item">
                        <a href="{{ UrlGen::login() }}" class="nav-link">{{ t('Log In') }}</a>
                    </li>
                    </li>
                    <li class="nav-item">
                        <a href="<?= url('registration') ?>" class="nav-link">{{ t('Register') }}</a>
                    </li>
                    @else
                    <li class="nav-item">
                        @if (app('impersonate')->isImpersonating())
                        <a href="{{ route('impersonate.leave') }}" class="nav-link">
                            <i class="icon-logout hidden-sm"></i> {{ t('Leave') }}
                        </a>
                        @else
                        <a href="{{ UrlGen::logout() }}" class="nav-link">
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
                                class="badge badge-pill badge-important count-threads-with-new-messages">{{$notification->count()}}</span>
                            @endif

                            <i class="icon-down-open-big fa"></i>
                        </a>
                        <ul id="userMenuDropdown"
                            class="dropdown-menu user-menu dropdown-menu-right shadow-sm animate slideIn">
                            @if(auth()->user()->is_admin == 1)
                            <li class="dropdown-item">
                                <a href="{{admin_url()}}"><i class="icon-user" style="color:black;font-size: 14px;"></i> {{ t('Go to Dashboard') }}
                                </a>
                            </li>
                            @else
                            <li class="dropdown-item"><a href="{{url('affiliate/dashboard')}}"><i
                                        class="icon-home" style="color:black"></i> {{ t('Dashboard') }}
                                </a>
                            </li>
                            <li class="dropdown-item"><a href="{{url('affiliate/affiliate_profile')}}/{{auth()->user()->id}}"><i
                                        class="icon-user" style="color:black"></i> {{ t('My Profile') }}
                                </a>
                            </li>
                            <li class="dropdown-item"><a href="{{url('affiliate/referral_users')}}"><i
                                        class="icon-users" style="color:black"></i> {{ t('referral_users') }}
                                </a>
                            </li>
                            <li class="dropdown-item"><a href="{{url('affiliate/referral_affiliates')}}"><i
                                        class="icon-users" style="color:black"></i> {{ t('referral_affiliates') }}
                                </a>
                            </li>
                            <li class="dropdown-item"><a href="{{url('affiliate/bank_details')}}"><i
                                        class="icon-bank" style="color:black"></i> {{ t('bank_details') }}
                                </a>
                            </li>
                            <li class="dropdown-item"><a href="{{url('affiliate/commissions')}}"><i
                                        class="icon-money" style="color:black"></i> {{ t('Commissions') }}
                                </a>
                            </li>
                            <li class="dropdown-item"><a href="{{url('affiliate/withdraw_requests')}}"><i
                                        class="fa fa-envelope" style="color:black"></i> {{ t('withdraw_requests') }}
                                </a>
                            </li>
                            <li class="dropdown-item"><a href="{{url('affiliate/messages')}}"><i 
                                        class="icon-chat" style="color:black"></i> {{ t('affiliate_messenger') }}&nbsp;&nbsp;
                                    @if($notification_message->count() >0)
                                            <span class="badge badge-pill badge-important count-threads-with-new-messages hidden-sm"
                                        style="font-size: smaller;">{{$notification_message->count()}}</span>
                                    @endif
                                </a>
                            </li>
                            @endif
                    </li>
                    <li class="dropdown-item">
                        @if (app('impersonate')->isImpersonating())
                        <a href="{{ route('impersonate.leave') }}"><i class="icon-logout" style="color:black"></i>
                            {{ t('Leave') }}</a>

                        @endif
                    </li>
                </ul>
                </li>
                @endif
               
                <li class="nav-item browse-job" style="">
                    <a href="{{ url('/search-resumes/?cat=&country=&city=&keyword=&limit=&offset=0&send=&nationality=&sort=') }}" class="nav-link">
                        <i class="fas fa-search"></i>&nbsp{{t('Search Employees')}}
                    </a>
                </li>

                &nbsp;&nbsp;&nbsp;

                @if (request()->segment(1) != 'countries')
                @if (config('settings.geo_location.country_flag_activation'))
                @if (!empty(config('country.icode')))

                <li class="flag-menu country-flag tooltipHere nav-item" data-toggle="tooltip"
                    data-placement="{{ (config('lang.direction') == 'rtl') ? 'bottom' : 'right' }}">

                    <a href="#" data-target="#selectCountry" data-toggle="modal" class="nav-link">
                        <img class="flag-icon"
                            src="{{ url()->asset('images/flags/32/' . strtolower(config('country.icode')) . '.png') }}"
                            alt="{{ config('country.name') }}">
                        <span class="caret hidden-sm"></span>
                    </a>
                </li>
                @endif
                @endif
                @endif
            </div>
        </div>
    </nav>
</div>


<form id="companyForm" method="POST" action="" style="display: none">
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
<?php if (!empty(request()->get('login_model'))) { ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    $('#quickLogin').modal('show');
});
</script>
<?php } ?>