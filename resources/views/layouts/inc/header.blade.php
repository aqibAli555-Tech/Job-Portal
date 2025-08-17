<?php

use App\Helpers\Helper;
use App\Helpers\UrlGen;
use App\Models\User;
use App\Models\Notification;
use App\Models\Company;
use App\Models\Post;
use App\Models\Nationality;
use App\Models\Country;
$staff_id = \Session::get('staff_id');
// Search parameters
$data['nationality_list'] = Nationality::pluck('id', 'name');
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
            $parent_company_data = User::get_parant_company($userCompanyData[0]['parent_id']);
            $login_company_data = array_merge($child_company_data, $parent_company_data);
        }
    }
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
    $notification_rejected = Helper::get_notification('rejected', auth()->user()->id);
    $notification = Notification::get_notification_data();
    $notification_applicants = Notification::get_notification_by_type('applicants');
    $notification_message = Notification::get_notification_by_type('message');
    $notification_resume = Notification::get_notification_by_type('resume');
}
?>


<?php
 $visa_types = array(
            array("Visa 18 (Normal/Professional)" => t("Visa 18 (Normal/Professional)")),
            array("Visa 18 (Mubarak AlKabeer/Small Business)" => t("Visa 18 (Mubarak AlKabeer/Small Business)")),
            array("Visa 18 (VIP/Golden)" => t('Visa 18 (VIP/Golden)')),
            array("Visa 18 (Other)" => t('Visa 18 (Other)')),
            array("Visa 22 (Family)" => t('Visa 22 (Family)')),
        );
$countries_list = Country::orderBy('name')->get();
          
if (auth()->check()) {
    if (auth()->user()->user_type_id == 2) {
        if (
            (empty(auth()->user()->experiences) || 
            empty(auth()->user()->nationality)) || 
            (empty(auth()->user()->visa) && auth()->user()->country_code == 'KW' ) 
        
        ) {
?>
<script>
$(document).ready(function() {
    $('#experience_modal').modal({
        show: true
    });
    window.addEventListener('click', showModal);
});
</script>
<?php
        }
       
    }
}

?>

<?php
if (auth()->check()) {
    if (auth()->user()->user_type_id == 2) {
        if (empty(auth()->user()->employee_cv)) {
?>
<script>
$(document).ready(function() {
    $('#employee_cv_modal').modal({
        show: true
    });
    window.addEventListener('click', showModal);
});
</script>
<?php
        }
       
    }
}

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
                        <a href="{{ UrlGen::login() }}" class="nav-link">{{ t('Log In') }}</a>
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
                            @if(Helper::check_permission(1))
                            @if(auth()->user()->user_type_id==1)
                            @if(!empty(auth()->user()->parent_id))
                            <li class="dropdown-item"><a href="{{url('/profile')}}/{{auth()->user()->id}}"><i
                                        class="icon-user" style="color:black"></i> {{ t('My Profile') }}
                                </a></li>
                            @else
                            <li class="dropdown-item">
                                <a href="{{url('/profile')}}/{{auth()->user()->id}}"><i class="icon-user"
                                        style="color:black;font-size: 14px;"></i> {{ t('Parent Account Profile') }}
                                </a>
                            </li>
                            @endif
                            @elseif(auth()->user()->user_type_id==5)
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
                                            class="icon-chat" style="color:black"></i> {{ t('affiliate_messenger') }}
                                    </a>
                                </li>
                                @endif
                            @else
                            @if(auth()->user()->is_admin == 1)
                            <li class="dropdown-item">
                                <a href="{{admin_url()}}"><i class="icon-user" style="color:black;font-size: 14px;"></i> {{ t('Go to Dashboard') }}
                                </a>
                            </li>
                            @else
                            <li class="dropdown-item"><a href="{{url('/employee_profile')}}/{{auth()->user()->id}}"><i
                                        class="icon-user" style="color:black"></i> {{ t('My Profile') }}
                                </a>
                            </li>
                            @endif
                            @endif
                            @endif

                            @if(in_array(auth()->user()->user_type_id, [1]))
                            @if(auth()->user()->parent_id== auth()->user()->id &&
                            Helper::check_permission(2))
                            <a href="{{ url('account/companies') }}">
                                <li class="dropdown-item dropdown-item-main"><i class="icon-town-hall"
                                        style="color:black"></i> {{ t('My companies') }}
                                    <i class="fas fa-angle-down my_companies_arrow"></i>
                            </a>
                            <ul class="dropdown-inner">
                                <?php
                                $companies = Company::get_company_by_id();
                                ?>
                                @foreach($companies as $company)
                                <?php
                                if (!empty($company->c_id)) {
                                    $users = User::get_user_by_id($company->c_id);
                                    if (!empty($users->password_without_hash)) { ?>
                                <li class="dropdown-item dropdown-item-child"><a href="{{ url('impersonate/leave/?user_id='.$users->id.'&from_parent_company=1') }}"
                                        ><i
                                            class="icon-town-hall" style="color:black"></i> {{$users->name}}
                                    </a>
                                </li>
                                <?php }
                                }
                                ?>

                                @endforeach
                                <li class="dropdown-item dropdown-item-child"><a
                                        href="{{ url('account/companies/create') }}"><i class="icon-plus"
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
                    @if(Helper::check_permission(3))
                    <li class="dropdown-item"><a href="{{ url('account/my-posts') }}"><i class="icon-bookmarks"
                                style="color:black"></i>{{ t('My ads') }}
                        </a>
                    </li>
                    @endif
                    @if(Helper::check_permission(4))
                    <li class="dropdown-item"><a
                            href="{{ url('/search-resumes/?cat=&country=&city=&keyword=&limit=&offset=0&send=') }}"><i
                                class="icon-search" style="color:black"></i>{{ t('Search CV') }}
                        </a></li>
                    @endif
                    @if(Helper::check_permission(5))
                    <li class="dropdown-item"><a href="{{ url('account/favorite-resumes') }}"><i class="icon-heart"
                                style="color:black"></i> {{ t('Favorite Employee') }}
                        </a></li>
                    @endif
                    @if(Helper::check_permission(6))
                    <li class="dropdown-item"><a href="{{ url('account/applicants') }}"><i class="icon-user-add"
                                style="color:black"></i>{{ t('Applicants') }}
                            @if($notification_applicants->count() >0)
                            <span
                                class="badge badge-pill badge-important count-threads-with-new-messages">{{$notification_applicants->count()}}</span>
                            @endif
                        </a>
                    </li>
                    @endif
                    @if(Helper::check_permission(7))

                    <li class="dropdown-item"><a href="{{ url('/account/Saved-Resume') }}"><i class="icon-doc-new"
                                style="color:black"></i>{{ t('Saved CV') }}
                        </a></li>
                    @endif
                    @if(Helper::check_permission(8))

                    <li class="dropdown-item"><a href="{{ url('account/Unlocked-Contact-Cards') }}"><i
                                class="icon-lock-open" style="color:black"></i>{{ t('Unlocked Contact') }}
                        </a></li>
                    @endif

                    
                    @if(Helper::check_permission(10))
                    <li class="dropdown-item">
                        <a href="{{ url('account/messages') }}">
                            <i class="icon-chat" style="color:black"></i> {{ t('messenger') }}
                            @if($notification_message->count() >0)
                            <span
                                class="badge badge-pill badge-important count-threads-with-new-messages">{{$notification_message->count()}}</span>
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
                    @if(Helper::check_permission(11))
                    <li class="dropdown-item"><a href="{{ url('account/transactions') }}"><i class="icon-credit-card-1"
                                style="color:black"></i> {{ t('Transactions') }}
                        </a></li>
                    @endif
                    @if(Helper::check_permission(11))
                    <li class="dropdown-item"><a href="{{ url('account/activity_logs') }}"><i class="icon-cog" style="color:black"></i> {{ t('Activity Logs') }}
                        </a></li>
                    @endif
                    @if(Helper::check_permission(12))
                    <li class="dropdown-item"><a href="{{ url('account/upgrade') }}"><i class="icon-up-open-big"
                                style="color:black"></i> {{ t('Upgrade Account') }}
                        </a></li>
                    @endif
                    @if(auth()->user()->parent_id != auth()->user()->id)
                    <li class="dropdown-item"><a  href="{{ url('impersonate/leave/?user_id='.$parent_company_data[0]['id']) }}"
                            >
                            <i class="icon-logout" style="color:black"></i>&nbsp;&nbsp;
                            {{ t('Go to parent company') }}
                        </a></li>
                    @endif


                    @endif
                    @endif
                    @if (in_array(auth()->user()->user_type_id, [2]))
                    <li class="dropdown-item"><a href="{{ url('account/resumes/user_resume') }}"><i class="icon-book"
                                style="color:black"></i>{{ t('My resumes') }}
                            @if($notification_resume->count() >0)
                                <span class="badge badge-pill badge-important count-threads-with-new-messages">{{$notification_resume->count()}}</span>
                            @endif
                        </a></li>
                    <li class="dropdown-item"><a href="{{url('account/Applied-Jobs')}}"><i class="icon-post"
                                style="color:black"></i>{{ t('Applied Jobs') }}
                            @if($notification_rejected->count() >0)
                            <span
                                class="badge badge-pill badge-important count-threads-with-new-messages">{{$notification_rejected->count()}}</span>
                            @endif
                        </a></li>
                    <li class="dropdown-item"><a href="{{ url('account/favourite') }}"><i class="icon-heart"
                                style="color:black"></i>{{ t('Favourite jobs') }}
                        </a></li>
                    <li class="dropdown-item"><a href="{{ url('account/cv-viewed') }}"><i class="icon-eye"
                                style="color:black"></i>{{ t('Who viewed your cv') }}
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
                    @if(IS_WHATSAPP_ALLOWED)
                    <li class="dropdown-item">
                        <a href="{{ url('account/user_setting') }}">
                            <i class="fa fa-cog me-2" style="color: black;"></i>{{ t('Settings') }}&nbsp;&nbsp;
                        </a>
                    </li>
                    @endif
                    @endif
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
               
                <?php if (auth()->check() && !empty(auth()->user()->user_type_id == 2)) : ?>
                <li class="nav-item pt-1">
                    <a href="{{ url('latest-jobs?post=&country_code=&q=&l=&min_salary=&max_salary=&type[]=') }}"
                        class="btn btn-primary Browse-Jobs btn-sm py-2 px-3">
                        <i class="icon-th-list-2 hidden-sm"></i> {{ t('Browse Jobs') }}
                    </a>
                </li>

                <?php elseif (!auth()->check()) : ?>
                <li class="nav-item browse-job " style="">
                    <a href="{{ url('latest-jobs?post=&country_code=&q=&l=&min_salary=&max_salary=&type[]=') }}"
                        class="nav-link">
                        <i class="icon-th-list-2"></i>{{ t('Search Jobs') }}
                    </a>
                </li>
                <?php endif; ?>


                <?php if (auth()->check() && !empty(auth()->user()->user_type_id == 1)) : ?>
                <li class="nav-item postadd pt-1">
                    <a class="btn btn-primary create-job-click btn-sm py-2 px-5" style="font-size: 18px;" onclick="check_subscription()"
                        href="javascript:void(0)" {!! $addListingAttr !!}>
                        <i class="fa fa-plus"></i>&nbsp;&nbsp;&nbsp;{{ t('Create a Job ad') }}
                    </a>
                </li>
                <?php endif; ?>

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


<script>
function check_subscription() {

    var myurl = '{{ url("check_subscription_availiblity") }}';
    $.ajax({
        url: myurl,
        method: "get",
        success: function(responce) {
            var url = '<?= url('account/upgrade') ?>';

            if (responce == 1) {
                var message = '<?= t("To job post you have to subscribe"); ?>';
                const config = {
                    html: true,
                    title: 'Attention',
                    html: message,
                    icon: 'error',
                    allowOutsideClick: false,
                    confirmButtonText: 'Subscribe',
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

$(document).ready(function () {
        $('#countrySearchInput').on('keyup', function () {
            var searchText = $(this).val().toLowerCase();
            $('#countryListContainer .country-link').each(function () {
                var countryName = $(this).attr('title').toLowerCase();
                if (countryName.indexOf(searchText) > -1) {
                    $(this).closest('.cat-list').show();
                } else {
                    $(this).closest('.cat-list').hide();
                }
            });
        });
    });

</script>