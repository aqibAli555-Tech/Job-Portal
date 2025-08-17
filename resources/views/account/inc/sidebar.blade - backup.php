<style>
    .class-icon {
        width: 13px;
    }
</style>
<?php

use App\Models\Allsaved_resume;
use App\Applicant;
use App\Models\Company;
use App\Models\Favoriteresume;
use App\Models\Post;
use App\Models\Unlock;

$segment = request()->segment(1);

if ($segment == 'profile' || request()->segment(2) == 'profile' || $segment == "employee_profile") {
    $pagePath = 'profile';
}

if (request()->segment(1) && empty(request()->segment(2))) {
    $pagePath = 'account';
}
?>
<aside>
    <div class="inner-box">
        <div class="user-panel-sidebar">

            @if (isset($user))
            <!-- /.collapse-box  -->
            @if (!empty($user->user_type_id) and $user->user_type_id != 0)
            <i class="fa fa-user theme-color fa-user-icon" hidden></i>
            <ul class="acc-list">
                <!-- COMPANY -->
                @if (in_array($user->user_type_id, [1]))

                @if(!empty(auth()->user()->parent_id))
                <li style="padding: 1px;" hidden>
                    <a{!! ($pagePath=='account' ) ? ' class="active"' : '' !!} href="{{ url('account') }}
                    ">
                    <i class="icon-home"></i>&nbsp;&nbsp;&nbsp;{{ t('Personal Home') }}
                    <!-- <i class="fas fa-question-circle" title="Upgrade Your Account" ></i> -->
                    </a>
                </li>
                <li>
                    <a id="profile_sidebar" {!! ($pagePath=='profile') ? ' class="active"' : '' !!} href="{{url('/profile')}}/{{auth()->user()->id}}"><i
                            class="icon-user"></i> &nbsp;{{ t('My Profile') }}</a>
                </li>
                @if(auth()->user()->parent_id==auth()->user()->id)
                <li hidden>
                    <a {!! ($pagePath=='profile') ? ' class="active"' : '' !!} href="{{url('/profile')}}/{{auth()->user()->id}}"><i
                            class="icon-user"></i> {{ t('Parent Account Profile') }}</a>
                </li>
                <li>
                    <a {!! ($pagePath=='companies') ? ' class="active"' : '' !!} href="{{ url('account/companies') }}">
                    <i class="icon-town-hall"></i> {{ t('My companies') }}&nbsp;
                    <span class="badge badge-pill">
                                      <?php
                                      $companies = Company::where(['user_id' => auth()->user()->id, 'deleted_at' => NULL])->orderByDesc('id');
                                      ?>
                                            {{ $companies->count() }}
                                     </span>
                    </a>
                </li>
                @endif
                @if(auth()->user()->user_type_id != '3' || (auth()->user()->user_type_id == '3' && isset(auth()->user()->permissions) && in_array('2',explode(",",auth()->user()->permissions))))
                <li>
                    <a {!! ($pagePath=='account-staff') ? ' class="active"' : '' !!} href="{{url('/account/staff')}}">
                    <i class="icon-user"></i>
                    {{ t('My staff') }}
                    </a>
                </li>
                @endif


                @if(auth()->user()->user_type_id != '3' || (auth()->user()->user_type_id == '3' && isset(auth()->user()->permissions) && in_array('3',explode(",",auth()->user()->permissions))))
                <li>
                    <a{!! ($pagePath=='my-posts' ) ? ' class="active"' : '' !!} href="{{ url('account/my-posts') }}">
                    <img class="class-icon" src="{{url()->asset('icon/1.png')}}"/>&nbsp;&nbsp; {{ t('My ads') }}
                    &nbsp;
                    <span class="badge badge-pill">

                                   {{ isset($countMyPosts) ? \App\Helpers\Number::short($countMyPosts) : 0 }}
                                     </span>
                    </a>
                </li>
                @endif
                @if(auth()->user()->user_type_id != '3' || (auth()->user()->user_type_id == '3' && isset(auth()->user()->permissions) && in_array('4',explode(",",auth()->user()->permissions))))
                <li>
                    <a{!! ($pagePath=='search-resumes' ) ? ' class="active"' : '' !!} href="{{ url('/account/search-resumes') }}">
                    <img class="class-icon" src="{{url()->asset('icon/2.png')}}"/>&nbsp;&nbsp; {{ t('Search Resume') }}
                    &nbsp;&nbsp;
                    <!-- <i class="fas fa-question-circle" title="Click here to Search Your Resume" ></i> -->
                    </a>
                </li>
                @endif
                @if(auth()->user()->user_type_id != '3' || (auth()->user()->user_type_id == '3' && isset(auth()->user()->permissions) && in_array('5',explode(",",auth()->user()->permissions))))
                <li>
                    <?php
                    $fav_count = Favoriteresume::where('company_id', auth()->user()->id)->count();
                    ?>
                    <a{!! ($pagePath=='favorite-resumes' ) ? ' class="active"' : '' !!} href="{{ url('/account/favorite-resumes') }}
                    ">
                    <img class="class-icon" src="{{url()->asset('icon/3.png')}}"/>&nbsp;&nbsp;
                    {{t('Favorite Resumes')}}<span class="badge badge-pill">{{$fav_count}}</span>
                    &nbsp;&nbsp;
                    <!-- <i class="fas fa-question-circle" title="Click here to Search Your Resume" ></i> -->
                    </a>
                </li>
                @endif
                @if(auth()->user()->user_type_id != '3' || (auth()->user()->user_type_id == '3' && isset(auth()->user()->permissions) && in_array('6',explode(",",auth()->user()->permissions))))
                <li>
                    <?php
                    $posts = Post::where('user_id', auth()->user()->id)->get();
                    foreach ($posts as $post) {
                        $postid[] = $post->id;
                    }
                    if (!empty($postid)) {
                        $totalapplicatnsapplied = Applicant::whereIn('post_id', $postid)->where('status', 'applied')->count();
                        $totalapplicatnsinterview = Applicant::whereIn('post_id', $postid)->where('status', 'interview')->count();
                        $totalapplicatns = $totalapplicatnsapplied + $totalapplicatnsinterview;
                    } else {
                        $totalapplicatns = 0;
                    }
                    ?>
                    <a{!! ($pagePath=='Applicants' ) ? ' class="active"' : '' !!} href="{{ url('/account/Applicants') }}
                    ">
                    <img class="class-icon" src="{{url()->asset('icon/applicants.png')}}"/>&nbsp;&nbsp; {{ t('Applicants') }}
                    <span class="badge badge-pill">{{$totalapplicatns}}</span>
                    &nbsp;
                    <!-- <i class="fas fa-question-circle" title="Check all Applicants" ></i> -->
                    </a>
                </li>
                @endif
                @if(auth()->user()->user_type_id != '3' || (auth()->user()->user_type_id == '3' && isset(auth()->user()->permissions) && in_array('7',explode(",",auth()->user()->permissions))))
                <li>
                    <a{!! ($pagePath=='Saved-Resume' ) ? ' class="active"' : '' !!} href="{{ url('/account/Saved-Resume') }}">
                    <img class="class-icon" src="{{url()->asset('icon/4.png')}}"/>&nbsp;&nbsp; {{ t('Saved Resume') }}
                    <?php $totalsaved = Allsaved_resume::where('user_id', auth()->user()->id)->count() ?>
                    <span class="badge badge-pill">{{$totalsaved}}</span>
                    &nbsp;&nbsp;
                    <!-- <i class="fas fa-question-circle" title="check Your Saved Resume" ></i> -->
                    </a>
                </li>
                @endif
                @if(auth()->user()->user_type_id != '3' || (auth()->user()->user_type_id == '3' && isset(auth()->user()->permissions) && in_array('8',explode(",",auth()->user()->permissions))))
                <li>
                    <a{!! ($pagePath=='Unlocked-Contact-Cards' ) ? ' class="active"' : '' !!} href="{{ url('/account/Unlocked-Contact-Cards') }}
                    ">
                    <img class="class-icon" src="{{url()->asset('icon/5.png')}}"/>&nbsp;&nbsp; {{ t('Unlocked Contact Cards') }}
                    <?php

                    $totalunlock = Unlock::where('to_user_id', auth()->user()->id)->where('is_unlock', 1)->count();
                    ?>
                    <span class="badge badge-pill">{{$totalunlock}}</span>
                    </a>
                </li>
                @endif
                <li hidden>
                    <a{!! ($pagePath=='pending-approval' ) ? ' class="active"' : '' !!} href="{{ url('account/pending-approval') }}
                    ">
                    <i class="icon-hourglass"></i> {{ t('Pending approval') }}&nbsp;
                    <span class="badge badge-pill">
                                        {{ isset($countPendingPosts) ? \App\Helpers\Number::short($countPendingPosts) : 0 }}
                                    </span> &nbsp;
                    <!-- <i class="fas fa-question-circle" title="check all pending Approval" ></i> -->
                    </a>
                </li>
                @if(auth()->user()->user_type_id != '3' || (auth()->user()->user_type_id == '3' && isset(auth()->user()->permissions) && in_array('9',explode(",",auth()->user()->permissions))))
                <li>
                    <a{!! ($pagePath=='archived' ) ? ' class="active"' : '' !!} href="{{ url('account/archived') }}
                    ">
                    <img class="class-icon" src="{{url()->asset('icon/6.png')}}"/>&nbsp;&nbsp; {{ t('Archived ads') }}
                    &nbsp;
                    <span class="badge badge-pill">
                                        {{ isset($countArchivedPosts) ? \App\Helpers\Number::short($countArchivedPosts) : 0 }}
                                    </span>
                    <!-- <i class="fas fa-question-circle" title="Archived Jobs" ></i> -->
                    </a>
                </li>
                @endif
                @if(auth()->user()->user_type_id != '3' || (auth()->user()->user_type_id == '3' && isset(auth()->user()->permissions) && in_array('10',explode(",",auth()->user()->permissions))))
                <li>
                    <a{!! ($pagePath=='messenger' ) ? ' class="active"' : '' !!} href="{{ url('account/messages') }}">
                    <img style="width: 13px"
                         src="{{url()->asset('icon/Untitled.png')}}"/>&nbsp;&nbsp; {{ t('messenger') }}
                    <!-- <i class="fas fa-question-circle" title="check all Messages" ></i> -->
                    <span class="badge badge-pill">
                                            {{ isset($countThreads) ? \App\Helpers\Number::short($countThreads) : 0 }}
                                        </span>
                    </a>
                </li>
                @endif
                @if(auth()->user()->user_type_id != '3' || (auth()->user()->user_type_id == '3' && isset(auth()->user()->permissions) && in_array('11',explode(",",auth()->user()->permissions))))
                <li>
                    <a{!! ($pagePath=='transactions' ) ? ' class="active"' : '' !!} href="{{ url('account/transactions') }}
                    ">
                    <img class="class-icon" src="{{url()->asset('icon/7.png')}}"/>&nbsp;&nbsp;
                    {{ t('Transactions') }}&nbsp;
                    <span class="badge badge-pill">
                                        {{ isset($countTransactions) ? \App\Helpers\Number::short($countTransactions) : 0 }}
                                    </span>&nbsp;
                    <!-- <i class="fas fa-question-circle" title="check all Your Transaction History"></i> -->
                    </a>
                </li>
                @endif
                @if(auth()->user()->user_type_id != '3' || (auth()->user()->user_type_id == '3' && isset(auth()->user()->permissions) && in_array('12',explode(",",auth()->user()->permissions))))
                <li>
                    <a{!! ($pagePath=='upgrade' ) ? ' class="active"' : '' !!} href="{{ url('account/upgrade') }}
                    ">
                    <img class="class-icon" src="{{url()->asset('icon/8.png')}}"/>&nbsp;&nbsp; {{ t('Upgrade Account') }}
                    &nbsp;
                    <!-- <i class="fas fa-question-circle" title="Upgrade Your Account" ></i> -->
                    </a>
                </li>
                @endif
                @endif
                @endif
                <!-- CANDIDATE or employee side icons-->
                @if (in_array($user->user_type_id, [2]))
                <!-- <i class="fas fa-question-circle" title="check all Your Transaction History" ></i> -->

                {{--
                <li>--}}
                    {{-- <a{!! ($pagePath=='upgrade' ) ? ' class="active"' : '' !!} href="{{ url('account/upgrade') }}">--}}
                    {{-- <i class="icon-money"></i> {{ t('Upgrade Account') }}&nbsp;--}}
                    {{--                    <!-- <i class="fas fa-question-circle" title="Upgrade Your Account" ></i> -->--}}
                    {{--                    </a>--}}
                    {{--
                </li>
                --}}

                @endif
                @endif
                <!-- CANDIDATE or employee side icons-->
                @if (in_array($user->user_type_id, [2]))
                <li style="margin-left: 1px;">
                    <a {!! ( $pagePath=='profile') ? ' class="active"' : '' !!}

                    href="{{url('/employee_profile')}}/{{auth()->user()->id}}">
                    <i class="icon-user"></i> &nbsp;&nbsp;{{ t('My Profile') }}
                    <!-- <i class="fas fa-question-circle" title="Check Profile" ></i> -->
                    </a>
                </li>
                <li>
                    <a{!! ($pagePath=='resumes' ) ? ' class="active"' : '' !!} href="{{ url('account/resumes/user_resume') }}
                    ">
                    <img class="class-icon" src="{{url()->asset('icon/1.png')}}"/> &nbsp;&nbsp;{{ t('My resumes') }}
                    &nbsp;
                    <span class="badge badge-pill">
                            {{ isset($countResumes) ? \App\Helpers\Number::short($countResumes) : 0 }}
                        </span>
                    &nbsp;
                    <!-- <i class="fas fa-question-circle" title="check All Resume" ></i> -->
                    </a>
                </li>
                <li>
                    <a{!! ($pagePath=='Applied-Jobs' ) ? ' class="active"' : '' !!} href="{{url('account/Applied-Jobs')}}
                    ">
                    <img class="class-icon" src="{{url()->asset('icon/4.png')}}"/>&nbsp;&nbsp; {{ t('Applied Jobs') }}
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
                    <img class="class-icon" src="{{url()->asset('icon/3.png')}}"/>&nbsp;&nbsp; {{ t('Favourite jobs') }}
                    &nbsp;
                    <span class="badge badge-pill">
                            {{ isset($countFavouritePosts) ? \App\Helpers\Number::short($countFavouritePosts) : 0 }}
                        </span>
                    &nbsp;
                    <!-- <i class="fas fa-question-circle" title="check your favourite jobs" ></i> -->
                    </a>
                </li>
                <li hidden>
                    <a{!! ($pagePath=='saved-search' ) ? ' class="active"' : '' !!} href="{{ url('account/saved-search') }}">
                    <i class="icon-star-circled"></i> {{ t('Saved searches') }}&nbsp;
                    <span class="badge badge-pill">
                            {{ isset($countSavedSearch) ? \App\Helpers\Number::short($countSavedSearch) : 0 }}
                        </span>
                    <!-- <i class="fas fa-question-circle" title="check your searches history" ></i> -->
                    </a>
                </li>
                <li>
                    <a{!! ($pagePath=='messenger' ) ? ' class="active"' : '' !!} href="{{ url('account/messages') }}">
                    <img class="class-icon" src="{{url()->asset('icon/Untitled.png')}}"/>&nbsp;&nbsp; {{ t('messenger') }}
                    &nbsp;
                    <span class="badge badge-pill">
                            {{ isset($countThreads) ? \App\Helpers\Number::short($countThreads) : 0 }}
                        </span>
                    </a>
                </li>
                @endif
                @if (config('plugins.apijc.installed'))
                <li>
                    <a{!! ($pagePath=='api-dashboard' ) ? ' class="active"' : '' !!} href="{{ url('account/api-dashboard') }}
                    ">
                    <i class="icon-cog"></i> {{ trans('api::messages.Clients & Applications') }}&nbsp;
                    </a>
                </li>
                @endif
            </ul>

            <!-- /.collapse-box  -->

            <div class="collapse-box" style="display: none">
                <h5 class="collapse-title">
                    {{ t('Terminate Account') }}&nbsp;
                    <a href="#TerminateAccount" data-toggle="collapse" class="pull-right"><i
                                class="fa fa-angle-down"></i></a>
                </h5>
                <div class="panel-collapse collapse show" id="TerminateAccount">
                    <ul class="acc-list">
                        <li>
                            <a {!! ($pagePath=='close' ) ? 'class="active"' : '' !!} href="{{ url('account/close') }}">
                            <i class="icon-cancel-circled "></i> {{ t('Close account') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- /.collapse-box  -->
            @endif

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
<style>
    .class-icon {
        width: 13px;
    }
</style>
<?php
$segment = request()->segment(1);

if ($segment == 'profile' || request()->segment(2) == 'profile' || $segment == "employee_profile") {
    $pagePath = 'profile';
}

if (request()->segment(1) && empty(request()->segment(2))) {
    $pagePath = 'account';
}
?>
<aside>
    <div class="inner-box">
        <div class="user-panel-sidebar">

            @if (isset($user))
            <!-- /.collapse-box  -->
            @if (!empty($user->user_type_id) and $user->user_type_id != 0)
            <i class="fa fa-user theme-color fa-user-icon" hidden></i>
            <ul class="acc-list">
                <!-- COMPANY -->
                @if (in_array($user->user_type_id, [1]))

                @if(!empty(auth()->user()->parent_id))
                <li style="padding: 1px;" hidden>
                    <a{!! ($pagePath=='account' ) ? ' class="active"' : '' !!} href="{{ url('account') }}
                    ">
                    <i class="icon-home"></i>&nbsp;&nbsp;&nbsp;{{ t('Personal Home') }}
                    <!-- <i class="fas fa-question-circle" title="Upgrade Your Account" ></i> -->
                    </a>
                </li>
                <li>
                    <a id="profile_sidebar" {!! ($pagePath=='profile') ? ' class="active"' : '' !!} href="{{url('/profile')}}/{{auth()->user()->id}}"><i
                            class="icon-user"></i> &nbsp;{{ t('My Profile') }}</a>
                </li>
                @if(auth()->user()->parent_id==auth()->user()->id)
                <li hidden>
                    <a {!! ($pagePath=='profile') ? ' class="active"' : '' !!} href="{{url('/profile')}}/{{auth()->user()->id}}"><i
                            class="icon-user"></i> {{ t('Parent Account Profile') }}</a>
                </li>
                <li>
                    <a {!! ($pagePath=='companies') ? ' class="active"' : '' !!} href="{{ url('account/companies') }}">
                    <i class="icon-town-hall"></i> {{ t('My companies') }}&nbsp;
                    <span class="badge badge-pill">
                                      <?php
                                      $companies = Company::where(['user_id' => auth()->user()->id, 'deleted_at' => NULL])->orderByDesc('id');
                                      ?>
                                            {{ $companies->count() }}
                                     </span>
                    </a>
                </li>
                @endif
                @if(auth()->user()->user_type_id != '3' || (auth()->user()->user_type_id == '3' && isset(auth()->user()->permissions) && in_array('2',explode(",",auth()->user()->permissions))))
                <li>
                    <a {!! ($pagePath=='account-staff') ? ' class="active"' : '' !!} href="{{url('/account/staff')}}">
                    <i class="icon-user"></i>
                    {{ t('My staff') }}
                    </a>
                </li>
                @endif


                @if(auth()->user()->user_type_id != '3' || (auth()->user()->user_type_id == '3' && isset(auth()->user()->permissions) && in_array('3',explode(",",auth()->user()->permissions))))
                <li>
                    <a{!! ($pagePath=='my-posts' ) ? ' class="active"' : '' !!} href="{{ url('account/my-posts') }}">
                    <img class="class-icon" src="{{url()->asset('icon/1.png')}}"/>&nbsp;&nbsp; {{ t('My ads') }}
                    &nbsp;
                    <span class="badge badge-pill">

                                   {{ isset($countMyPosts) ? \App\Helpers\Number::short($countMyPosts) : 0 }}
                                     </span>
                    </a>
                </li>
                @endif
                @if(auth()->user()->user_type_id != '3' || (auth()->user()->user_type_id == '3' && isset(auth()->user()->permissions) && in_array('4',explode(",",auth()->user()->permissions))))
                <li>
                    <a{!! ($pagePath=='search-resumes' ) ? ' class="active"' : '' !!} href="{{ url('/account/search-resumes') }}">
                    <img class="class-icon" src="{{url()->asset('icon/2.png')}}"/>&nbsp;&nbsp; {{ t('Search Resume') }}
                    &nbsp;&nbsp;
                    <!-- <i class="fas fa-question-circle" title="Click here to Search Your Resume" ></i> -->
                    </a>
                </li>
                @endif
                @if(auth()->user()->user_type_id != '3' || (auth()->user()->user_type_id == '3' && isset(auth()->user()->permissions) && in_array('5',explode(",",auth()->user()->permissions))))
                <li>
                    <?php
                    $fav_count = Favoriteresume::where('company_id', auth()->user()->id)->count();
                    ?>
                    <a{!! ($pagePath=='favorite-resumes' ) ? ' class="active"' : '' !!} href="{{ url('/account/favorite-resumes') }}
                    ">
                    <img class="class-icon" src="{{url()->asset('icon/3.png')}}"/>&nbsp;&nbsp;
                    {{t('Favorite Resumes')}}<span class="badge badge-pill">{{$fav_count}}</span>
                    &nbsp;&nbsp;
                    <!-- <i class="fas fa-question-circle" title="Click here to Search Your Resume" ></i> -->
                    </a>
                </li>
                @endif
                @if(auth()->user()->user_type_id != '3' || (auth()->user()->user_type_id == '3' && isset(auth()->user()->permissions) && in_array('6',explode(",",auth()->user()->permissions))))
                <li>
                    <?php
                    $posts = Post::where('user_id', auth()->user()->id)->get();
                    foreach ($posts as $post) {
                        $postid[] = $post->id;
                    }
                    if (!empty($postid)) {
                        $totalapplicatnsapplied = Applicant::whereIn('post_id', $postid)->where('status', 'applied')->count();
                        $totalapplicatnsinterview = Applicant::whereIn('post_id', $postid)->where('status', 'interview')->count();
                        $totalapplicatns = $totalapplicatnsapplied + $totalapplicatnsinterview;
                    } else {
                        $totalapplicatns = 0;
                    }
                    ?>
                    <a{!! ($pagePath=='Applicants' ) ? ' class="active"' : '' !!} href="{{ url('/account/Applicants') }}
                    ">
                    <img class="class-icon" src="{{url()->asset('icon/applicants.png')}}"/>&nbsp;&nbsp; {{ t('Applicants') }}
                    <span class="badge badge-pill">{{$totalapplicatns}}</span>
                    &nbsp;
                    <!-- <i class="fas fa-question-circle" title="Check all Applicants" ></i> -->
                    </a>
                </li>
                @endif
                @if(auth()->user()->user_type_id != '3' || (auth()->user()->user_type_id == '3' && isset(auth()->user()->permissions) && in_array('7',explode(",",auth()->user()->permissions))))
                <li>
                    <a{!! ($pagePath=='Saved-Resume' ) ? ' class="active"' : '' !!} href="{{ url('/account/Saved-Resume') }}">
                    <img class="class-icon" src="{{url()->asset('icon/4.png')}}"/>&nbsp;&nbsp; {{ t('Saved Resume') }}
                    <?php $totalsaved = Allsaved_resume::where('user_id', auth()->user()->id)->count() ?>
                    <span class="badge badge-pill">{{$totalsaved}}</span>
                    &nbsp;&nbsp;
                    <!-- <i class="fas fa-question-circle" title="check Your Saved Resume" ></i> -->
                    </a>
                </li>
                @endif
                @if(auth()->user()->user_type_id != '3' || (auth()->user()->user_type_id == '3' && isset(auth()->user()->permissions) && in_array('8',explode(",",auth()->user()->permissions))))
                <li>
                    <a{!! ($pagePath=='Unlocked-Contact-Cards' ) ? ' class="active"' : '' !!} href="{{ url('/account/Unlocked-Contact-Cards') }}
                    ">
                    <img class="class-icon" src="{{url()->asset('icon/5.png')}}"/>&nbsp;&nbsp; {{ t('Unlocked Contact Cards') }}
                    <?php

                    $totalunlock = Unlock::where('to_user_id', auth()->user()->id)->where('is_unlock', 1)->count();
                    ?>
                    <span class="badge badge-pill">{{$totalunlock}}</span>
                    </a>
                </li>
                @endif
                <li hidden>
                    <a{!! ($pagePath=='pending-approval' ) ? ' class="active"' : '' !!} href="{{ url('account/pending-approval') }}
                    ">
                    <i class="icon-hourglass"></i> {{ t('Pending approval') }}&nbsp;
                    <span class="badge badge-pill">
                                        {{ isset($countPendingPosts) ? \App\Helpers\Number::short($countPendingPosts) : 0 }}
                                    </span> &nbsp;
                    <!-- <i class="fas fa-question-circle" title="check all pending Approval" ></i> -->
                    </a>
                </li>
                @if(auth()->user()->user_type_id != '3' || (auth()->user()->user_type_id == '3' && isset(auth()->user()->permissions) && in_array('9',explode(",",auth()->user()->permissions))))
                <li>
                    <a{!! ($pagePath=='archived' ) ? ' class="active"' : '' !!} href="{{ url('account/archived') }}
                    ">
                    <img class="class-icon" src="{{url()->asset('icon/6.png')}}"/>&nbsp;&nbsp; {{ t('Archived ads') }}
                    &nbsp;
                    <span class="badge badge-pill">
                                        {{ isset($countArchivedPosts) ? \App\Helpers\Number::short($countArchivedPosts) : 0 }}
                                    </span>
                    <!-- <i class="fas fa-question-circle" title="Archived Jobs" ></i> -->
                    </a>
                </li>
                @endif
                @if(auth()->user()->user_type_id != '3' || (auth()->user()->user_type_id == '3' && isset(auth()->user()->permissions) && in_array('10',explode(",",auth()->user()->permissions))))
                <li>
                    <a{!! ($pagePath=='messenger' ) ? ' class="active"' : '' !!} href="{{ url('account/messages') }}">
                    <img style="width: 13px"
                         src="{{url()->asset('icon/Untitled.png')}}"/>&nbsp;&nbsp; {{ t('messenger') }}
                    <!-- <i class="fas fa-question-circle" title="check all Messages" ></i> -->
                    <span class="badge badge-pill">
                                            {{ isset($countThreads) ? \App\Helpers\Number::short($countThreads) : 0 }}
                                        </span>
                    </a>
                </li>
                @endif
                @if(auth()->user()->user_type_id != '3' || (auth()->user()->user_type_id == '3' && isset(auth()->user()->permissions) && in_array('11',explode(",",auth()->user()->permissions))))
                <li>
                    <a{!! ($pagePath=='transactions' ) ? ' class="active"' : '' !!} href="{{ url('account/transactions') }}
                    ">
                    <img class="class-icon" src="{{url()->asset('icon/7.png')}}"/>&nbsp;&nbsp;
                    {{ t('Transactions') }}&nbsp;
                    <span class="badge badge-pill">
                                        {{ isset($countTransactions) ? \App\Helpers\Number::short($countTransactions) : 0 }}
                                    </span>&nbsp;
                    <!-- <i class="fas fa-question-circle" title="check all Your Transaction History"></i> -->
                    </a>
                </li>
                @endif
                @if(auth()->user()->user_type_id != '3' || (auth()->user()->user_type_id == '3' && isset(auth()->user()->permissions) && in_array('12',explode(",",auth()->user()->permissions))))
                <li>
                    <a{!! ($pagePath=='upgrade' ) ? ' class="active"' : '' !!} href="{{ url('account/upgrade') }}
                    ">
                    <img class="class-icon" src="{{url()->asset('icon/8.png')}}"/>&nbsp;&nbsp; {{ t('Upgrade Account') }}
                    &nbsp;
                    <!-- <i class="fas fa-question-circle" title="Upgrade Your Account" ></i> -->
                    </a>
                </li>
                @endif
                @endif
                @endif
                <!-- CANDIDATE or employee side icons-->
                @if (in_array($user->user_type_id, [2]))
                <!-- <i class="fas fa-question-circle" title="check all Your Transaction History" ></i> -->

                {{--
                <li>--}}
                    {{-- <a{!! ($pagePath=='upgrade' ) ? ' class="active"' : '' !!} href="{{ url('account/upgrade') }}">--}}
                    {{-- <i class="icon-money"></i> {{ t('Upgrade Account') }}&nbsp;--}}
                    {{--                    <!-- <i class="fas fa-question-circle" title="Upgrade Your Account" ></i> -->--}}
                    {{--                    </a>--}}
                    {{--
                </li>
                --}}

                @endif
                @endif
                <!-- CANDIDATE or employee side icons-->
                @if (in_array($user->user_type_id, [2]))
                <li style="margin-left: 1px;">
                    <a {!! ( $pagePath=='profile') ? ' class="active"' : '' !!}

                    href="{{url('/employee_profile')}}/{{auth()->user()->id}}">
                    <i class="icon-user"></i> &nbsp;&nbsp;{{ t('My Profile') }}
                    <!-- <i class="fas fa-question-circle" title="Check Profile" ></i> -->
                    </a>
                </li>
                <li>
                    <a{!! ($pagePath=='resumes' ) ? ' class="active"' : '' !!} href="{{ url('account/resumes/user_resume') }}
                    ">
                    <img class="class-icon" src="{{url()->asset('icon/1.png')}}"/> &nbsp;&nbsp;{{ t('My resumes') }}
                    &nbsp;
                    <span class="badge badge-pill">
                            {{ isset($countResumes) ? \App\Helpers\Number::short($countResumes) : 0 }}
                        </span>
                    &nbsp;
                    <!-- <i class="fas fa-question-circle" title="check All Resume" ></i> -->
                    </a>
                </li>
                <li>
                    <a{!! ($pagePath=='Applied-Jobs' ) ? ' class="active"' : '' !!} href="{{url('account/Applied-Jobs')}}
                    ">
                    <img class="class-icon" src="{{url()->asset('icon/4.png')}}"/>&nbsp;&nbsp; {{ t('Applied Jobs') }}
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
                    <img class="class-icon" src="{{url()->asset('icon/3.png')}}"/>&nbsp;&nbsp; {{ t('Favourite jobs') }}
                    &nbsp;
                    <span class="badge badge-pill">
                            {{ isset($countFavouritePosts) ? \App\Helpers\Number::short($countFavouritePosts) : 0 }}
                        </span>
                    &nbsp;
                    <!-- <i class="fas fa-question-circle" title="check your favourite jobs" ></i> -->
                    </a>
                </li>
                <li hidden>
                    <a{!! ($pagePath=='saved-search' ) ? ' class="active"' : '' !!} href="{{ url('account/saved-search') }}">
                    <i class="icon-star-circled"></i> {{ t('Saved searches') }}&nbsp;
                    <span class="badge badge-pill">
                            {{ isset($countSavedSearch) ? \App\Helpers\Number::short($countSavedSearch) : 0 }}
                        </span>
                    <!-- <i class="fas fa-question-circle" title="check your searches history" ></i> -->
                    </a>
                </li>
                <li>
                    <a{!! ($pagePath=='messenger' ) ? ' class="active"' : '' !!} href="{{ url('account/messages') }}">
                    <img class="class-icon" src="{{url()->asset('icon/Untitled.png')}}"/>&nbsp;&nbsp; {{ t('messenger') }}
                    &nbsp;
                    <span class="badge badge-pill">
                            {{ isset($countThreads) ? \App\Helpers\Number::short($countThreads) : 0 }}
                        </span>
                    </a>
                </li>
                @endif
                @if (config('plugins.apijc.installed'))
                <li>
                    <a{!! ($pagePath=='api-dashboard' ) ? ' class="active"' : '' !!} href="{{ url('account/api-dashboard') }}
                    ">
                    <i class="icon-cog"></i> {{ trans('api::messages.Clients & Applications') }}&nbsp;
                    </a>
                </li>
                @endif
            </ul>

            <!-- /.collapse-box  -->

            <div class="collapse-box" style="display: none">
                <h5 class="collapse-title">
                    {{ t('Terminate Account') }}&nbsp;
                    <a href="#TerminateAccount" data-toggle="collapse" class="pull-right"><i
                                class="fa fa-angle-down"></i></a>
                </h5>
                <div class="panel-collapse collapse show" id="TerminateAccount">
                    <ul class="acc-list">
                        <li>
                            <a {!! ($pagePath=='close' ) ? 'class="active"' : '' !!} href="{{ url('account/close') }}">
                            <i class="icon-cancel-circled "></i> {{ t('Close account') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- /.collapse-box  -->
            @endif

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