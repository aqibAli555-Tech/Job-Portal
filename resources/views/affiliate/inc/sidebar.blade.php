<?php
$segment = request()->segment(1);
if ($segment == 'affiliate' && request()->segment(2) == 'dashboard') {
    $pagePath = 'dashboard';
}

if (request()->segment(2) == 'affiliate_profile' || $segment == "employee_profile" || request()->segment(2) == 'profile') {
    $pagePath = 'profile';
}

if (request()->segment(2) == 'referral_users' || request()->segment(2) == 'user_purchase') {
    $pagePath = 'referral_users';
}

if (request()->segment(2) == 'referral_affiliates') {
    $pagePath = 'referral_affiliates';
}

if (request()->segment(2) == 'referral_commissions') {
    $pagePath = 'referral_commissions';
}

if (request()->segment(2) == 'bank_details') {
    $pagePath = 'bank_details';
}

if (request()->segment(2) == 'withdraw_requests') {
    $pagePath = 'withdraw_requests';
}

if (request()->segment(2) == 'commissions') {
    $pagePath = 'commissions';
}

if (request()->segment(2) == 'messages') {
    $pagePath = 'affiliate_messenger';
}
?>
<aside>
    <div class="inner-box">
        <div class="user-panel-sidebar">
            <ul class="acc-list">
                @if(!empty(auth()->user()) && auth()->user()->user_type_id==5)
                    <li style="margin-left: 1px;">
                        <a {!! ( $pagePath=='dashboard' ) ? ' class="active"' : '' !!} href="{{url('affiliate/dashboard')}}">
                            <i class="icon-home"></i> &nbsp;&nbsp;{{ t('Dashboard') }}
                        </a>
                    </li>
                    <li style="margin-left: 1px;">
                        <a {!! ( $pagePath=='profile' ) ? ' class="active"' : '' !!} href="{{url('affiliate/affiliate_profile')}}/{{auth()->user()->id}}">
                            <i class="icon-user"></i> &nbsp;&nbsp;{{ t('My Profile') }}
                        </a>
                    </li>
                    <li style="margin-left: 1px;">
                        <a {!! ( $pagePath=='referral_users'  || $pagePath=='referral_commissions') ? ' class="active"' : '' !!} href="{{url('affiliate/referral_users')}}">                    
                                <i class="icon-users"></i> &nbsp;&nbsp;{{ t('referral_users') }}
                        </a>
                    </li>
                    <li style="margin-left: 1px;">
                        <a {!! ( $pagePath=='referral_affiliates') ? ' class="active"' : '' !!} href="{{url('affiliate/referral_affiliates')}}">                    
                                <i class="icon-users"></i> &nbsp;&nbsp;{{ t('referral_affiliates') }}
                        </a>
                    </li>
                    <li style="margin-left: 1px;">
                        <a {!! ( $pagePath=='bank_details' ) ? ' class="active"' : '' !!} href="{{url('affiliate/bank_details')}}">                    
                            <i class="icon-bank"></i> &nbsp;&nbsp;{{ t('bank_details') }}
                        </a>
                    </li>
                    <li style="margin-left: 1px;">
                        <a {!! ( $pagePath=='commissions' ) ? ' class="active"' : '' !!} href="{{url('affiliate/commissions')}}">                    
                            <i class="icon-money"></i> &nbsp;&nbsp;{{ t('Commissions') }}
                        </a>
                    </li>
                    <li style="margin-left: 1px;">
                        <a {!! ( $pagePath=='withdraw_requests' ) ? ' class="active"' : '' !!} href="{{url('affiliate/withdraw_requests')}}">                    
                            <i class="fa fa-envelope"></i> &nbsp;&nbsp;{{ t('withdraw_requests') }}
                        </a>
                    </li>
                    <li>
                        <a {!! ($pagePath=='affiliate_messenger' ) ? ' class="active"' : '' !!} href="{{ url('affiliate/messages') }}
                                "><i class="icon-chat"></i> {{ t('affiliate_messenger') }}&nbsp;&nbsp;
                                @if($messagenotificationcount > 0)
                                    <span class="badge badge-pill badge-important-circle" style="position: relative; margin-right: 10px; top: 13px;">
                                        {{ $messagenotificationcount }}
                                    </span>
                                @endif
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</aside>