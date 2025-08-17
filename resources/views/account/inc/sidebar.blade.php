<style>
    .class-icon {
        width: 13px;
    }

    .user-panel-sidebar ul.acc-list li a {

        padding: 10px 10px;
    }

    .user-panel-sidebar ul.acc-list li a i {
        font-size: 16px;
    }

    .badge-important-circle {
        position: absolute;
        top: 21px;
        right: 0;
        transform: translate(50%, -50%);
        width: 24px;
        height: 20px;
        border-radius: 10rem !important;
        background-color: #d9534f !important;
        border: 2px solid #d9534f;
        padding: 2px;
    }
</style>
<?php

use App\Models\MessageRequest;

$segment = request()->segment(1);

if ($segment == 'profile' || request()->segment(2) == 'profile' || $segment == "employee_profile") {
    $pagePath = 'profile';
}

if (request()->segment(1) && empty(request()->segment(2))) {
    $pagePath = 'account';
}
if (request()->segment(2)  == 'Applied-Jobs') {
    $pagePath = 'Applied-Jobs';
}
if (request()->segment(2)  == 'upgrade') {
    $pagePath = 'upgrade';
}
if (request()->segment(2)  == 'user_setting') {
    $pagePath = 'user_setting';
}
$staff_id = \Session::get('staff_id');

?>
<aside>
    <div class="<?php if(!empty(auth()->user()) && auth()->user()->user_type_id == 1 || auth()->user()->user_type_id==2 ) {echo 'inner-box';} ?>">
        <div class="user-panel-sidebar">
            <ul class="acc-list">
                <!-- COMPANY -->
                @if(!empty(auth()->user()) && auth()->user()->user_type_id==1)
                @if(\App\Helpers\Helper::check_permission(1))
                <li>
                    <a id="profile_sidebar" {!! ($pagePath=='profile' ) ? ' class="active"' : '' !!} href="{{url('/profile')}}/{{auth()->user()->id}}"><i class="icon-user"></i>
                        &nbsp;{{ t('My Profile') }}</a>
                </li>
                @endif

                @if(auth()->user()->id==auth()->user()->parent_id && \App\Helpers\Helper::check_permission(2))
                <li>
                    <a {!! ($pagePath=='companies' ) ? ' class="active"' : '' !!} href="{{ url('account/companies') }}">
                        <i class="icon-town-hall"></i> {{ t('My companies') }}&nbsp;
                        <span class="badge badge-pill">
                            {{ $companyCount }}
                        </span>
                    </a>
                </li>
                @endif
                @if(auth()->user()->id==auth()->user()->parent_id && empty($staff_id))
                <li>
                    <a {!! ($pagePath=='staff' ) ? ' class="active"' : '' !!} href="{{url('/account/staff')}}">
                        <i class="icon-users"></i>
                        {{ t('My staff') }}
                    </a>
                </li>
                @endif
                @if(\App\Helpers\Helper::check_permission(3))
                <li>
                    <a{!! ($pagePath=='my-posts' ) ? ' class="active"' : '' !!} href="{{ url('account/my-posts') }}">
                        <i class="icon-bookmarks"></i> {{ t('My ads') }}
                        &nbsp;
                        <span class="badge badge-pill">
                            {{ isset($countMyPosts) ? \App\Helpers\Number::short($countMyPosts) : 0 }}
                        </span>
                        </a>
                </li>
                @endif
                @if(\App\Helpers\Helper::check_permission(4))
                <li>
                    <a{!! ($pagePath=='search-resumes' ) ? ' class="active"' : '' !!} href="{{ url('/search-resumes/?cat=&country=&city=&keyword=&limit=&offset=0&send=&nationality=&sort=') }}
                            ">
                        <i class="icon-search"></i> {{ t('Search CV') }}
                        </a>
                </li>
                @endif
                @if(\App\Helpers\Helper::check_permission(5))
                <li>
                    <a{!! ($pagePath=='favorite-resumes' ) ? ' class="active"' : '' !!} href="{{ url('/account/favorite-resumes') }}
                            ">
                        <i class="icon-heart"></i> {{t('Favorite Employee')}}<span class="badge badge-pill">{{$fav_count}}</span>
                        &nbsp;&nbsp;
                        </a>
                </li>
                @endif
                @if(\App\Helpers\Helper::check_permission(6))
                <li>
                    <a{!! ($pagePath=='Applicants' ) ? ' class="active"' : '' !!} href="{{ url('/account/applicants') }}
                            ">
                        <i class="icon-user-add"></i> {{ t('Applicants') }}
                    <span class="badge badge-pill badge-secondary" style="position: relative;">
                            <span>{{$totalapplicatns}}</span>
                            @if($applicants_notification > 0)
                            <span class="badge badge-pill badge-important-circle">{{$applicants_notification}}</span>
                        @endif
                         </span>
                    &nbsp;
                        </a>
                </li>
                @endif
                @if(\App\Helpers\Helper::check_permission(7))

                <li>
                    <a{!! ($pagePath=='Saved-Resume' ) ? ' class="active"' : '' !!} href="{{ url('/account/Saved-Resume') }}">
                        <i class="icon-doc-new"></i>
                        {{ t('Saved CV') }}
                        <span class="badge badge-pill">{{$totalsaved}}</span>
                        &nbsp;&nbsp;
                        </a>
                </li>
                @endif
                @if(\App\Helpers\Helper::check_permission(8))
                <li>
                    <a{!! ($pagePath=='Unlocked-Contact-Cards' ) ? ' class="active"' : '' !!} href="{{ url('/account/Unlocked-Contact-Cards') }}">
                        <i class="icon-lock-open"></i> {{ t('Unlocked Contact') }}
                        <span class="badge badge-pill">{{$totalunlock}}</span>
                        </a>
                </li>
                @endif
           
                @if(\App\Helpers\Helper::check_permission(10))

                <li>
                    <a {!! ($pagePath=='messenger' ) ? ' class="active"' : '' !!} href="{{ url('account/messages') }}
                            "><i class="icon-chat"></i> {{ t('messenger') }}
                    <span class="badge badge-pill badge-secondary" style="position: relative;">
                            <span>{{ isset($countThreads) ? \App\Helpers\Number::short($countThreads) : 0 }}</span>
                            @if($messagenotificationcount > 0)
                            <span class="badge badge-pill badge-important-circle">{{$messagenotificationcount}}</span>
                        @endif
                          </span>
                        </a>
                </li>
                @endif
                @if(\App\Helpers\Helper::check_permission(14))
                <li>
                    <a{!! ($pagePath=='message_request' ) ? ' class="active"' : '' !!} href="{{ url('account/messages/message_request') }}
                    "><i class="icon-chat-1"></i> {{ t('bulk_chat_requests') }}&nbsp;
                        <span class="badge badge-pill">
                            {{ isset($message_request_count) ? $message_request_count : 0}}
                        </span>&nbsp;
                        </a>
                </li>
                @endif

                @if(\App\Helpers\Helper::check_permission(11))

                <li>
                    <a{!! ($pagePath=='transactions' ) ? ' class="active"' : '' !!} href="{{ url('account/transactions') }}
                            "><i class="icon-credit-card-1"></i> {{ t('Transactions') }}&nbsp;
                        <span class="badge badge-pill">
                            {{ isset($countTransactions) ? \App\Helpers\Number::short($countTransactions) : 0 }}
                        </span>&nbsp;
                        </a>
                </li>
                <li>
                    <a{!! ($pagePath=='activity_logs' ) ? ' class="active"' : '' !!} href="{{ url('account/activity_logs') }}
                        "><i class="icon-cog"></i> {{ t('Activity Logs') }}&nbsp;
                    </a>
                </li>
                @endif
                @if(\App\Helpers\Helper::check_permission(12))
                <li>
                    <a{!! ($pagePath=='upgrade' ) ? ' class="active"' : '' !!} href="{{ url('account/upgrade') }}
                            "><i class="icon-up-open-big"></i> {{ t('Upgrade Account') }}
                        &nbsp;
                        <!-- <i class="fas fa-question-circle" title="Upgrade Your Account" ></i> -->
                        </a>
                </li>
                @endif

                @if(auth()->user()->parent_id != auth()->user()->id)
                <?php $login_company_data = array();
                if (auth()->check()) {

                    if (empty(auth()->user()->parent_id)) {
                        $child_company_data = \App\Models\User::get_child_company(auth()->user()->id);
                        $parent_company_data = \App\Models\User::get_parant_company(auth()->user()->id);
                        $login_company_data = array_merge($child_company_data, $parent_company_data);
                    } else {
                        $userCompanyData = \App\Models\User::get_parant_company(auth()->user()->parent_id);

                        if (!empty($userCompanyData[0]['parent_id'])) {
                            $child_company_data = \App\Models\User::get_child_company($userCompanyData[0]['parent_id']);
                            $parent_company_data = \App\Models\User::get_parant_company($userCompanyData[0]['parent_id']);
                            $login_company_data = array_merge($child_company_data, $parent_company_data);
                        }
                    }
                }
                ?>
                <?php if (!empty($parent_company_data)) { ?>
                    <li>
                        <a href="{{ url('impersonate/leave/?user_id='.$parent_company_data[0]['id']) }}" style="font-size:11px" >
                            <i class="icon-logout"></i> &nbsp;&nbsp; {{ t('Go to parent company') }}
                        </a>
                    </li>
                <?php } ?>
                @endif
                @endif
                <!-- CANDIDATE or employee side icons-->
                @if(!empty(auth()->user()) && auth()->user()->user_type_id==2)
                <li style="margin-left: 1px;">
                    <a {!! ( $pagePath=='profile' ) ? ' class="active"' : '' !!} href="{{url('/employee_profile')}}/{{auth()->user()->id}}">
                        <i class="icon-user"></i> &nbsp;&nbsp;{{ t('My Profile') }}
                        <!-- <i class="fas fa-question-circle" title="Check Profile" ></i> -->
                    </a>
                </li>
                    <li>
                        <a {!! ($pagePath=='resumes' ) ? ' class="active"' : '' !!} href="{{ url('account/resumes/user_resume') }}
                        ">
                            <i class="icon-book"></i>&nbsp;{{ t('My CV') }}
                            &nbsp;

                            <span class="badge badge-pill badge-secondary" style="position: relative;">
                            <span> <?php if (!empty(auth()->user()->employee_cv)) {
                                    echo '1';
                                } else {
                                    echo '0';
                                } ?>
                            </span>
                            @if($notification_resume > 0)
                                    <span class="badge badge-pill badge-important-circle">{{$notification_resume}}</span>
                                @endif
                         </span>
                            &nbsp;
                        </a>
                    </li>
                <li>
                    <a{!! ($pagePath=='Applied-Jobs' ) ? ' class="active"' : '' !!} href="{{url('account/Applied-Jobs')}}
                        ">
                        <i class="icon-post"></i>&nbsp;{{ t('Applied Jobs') }}
                        &nbsp;&nbsp;
                        <!-- <i class="fas fa-question-circle" title="check Jobs Applied history" ></i> -->
                        <span class="badge badge-pill">
                            {{ isset($appliedjobDataCount) ? $appliedjobDataCount : 0 }}
                        </span>
                        &nbsp;
                        </a>
                </li>
                <li>
                    <a{!! ($pagePath=='favourite' ) ? ' class="active"' : '' !!} href="{{ url('account/favourite') }}
                        ">
                        <i class="icon-heart"></i>&nbsp;{{ t('Favourite jobs') }}
                        &nbsp;
                        <span class="badge badge-pill">
                            {{ isset($countFavouritePosts) ? \App\Helpers\Number::short($countFavouritePosts) : 0 }}
                        </span>
                        &nbsp;
                        <!-- <i class="fas fa-question-circle" title="check your favourite jobs" ></i> -->
                        </a>
                </li>
                <li>
                    <a{!! ($pagePath=='cv-viewed' ) ? ' class="active"' : '' !!} href=" {{ url('account/cv-viewed') }}
                        ">
                        <i class="icon-eye"></i>&nbsp;{{ t('Who viewed your cv') }}
                        <span class="badge badge-pill">{{$who_view_your_cv_count}}</span>

                        &nbsp;
                        </a>
                </li>
                <li>
                    <a{!! ($pagePath=='messenger' ) ? ' class="active"' : '' !!} href="{{ url('account/messages') }}
                        ">
                        <i class="icon-chat"></i> &nbsp;{{ t('Chat With Companies') }}
                        &nbsp;
                    <span class="badge badge-pill badge-secondary" style="position: relative;">
                            <span>{{ isset($countThreads) ? \App\Helpers\Number::short($countThreads) : 0 }}</span>
                            @if($messagenotificationcount > 0)
                            <span class="badge badge-pill badge-important-circle">{{$messagenotificationcount}}</span>
                        @endif
                          </span>
                        </a>
                </li>
                @if(IS_WHATSAPP_ALLOWED)
                <li>
                    <a{!! ($pagePath == 'user_setting') ? ' class="active"' : '' !!} href="{{ url('account/user_setting') }}">
                        <i class="fas fa-cog"></i>&nbsp; {{ t('Settings') }}
                    </a>
                </li>
                @endif

                @endif
                <!-- CANDIDATE or employee side icons-->
            </ul>
        </div>
    </div>
    <!-- /.inner-box  -->
</aside>

<!-- help Model is Define Here-->
<div class="modal fade" id="helpModel" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                Discription
                <button type="button" class="close" data-dismiss="modal">&times;</button>

            </div>
            <div class="modal-body">
                <p>If You make Your Job Archive from My Jobs Page, It will show only here (Not on Main page) Untill You
                    Repost it.</p>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
            </div>
        </div>

    </div>
</div>