
@extends('layouts.master')
@section('content')
@include('common.spacer')
<style>
    body {
        padding: 0;
        margin: 0;
        font-family: "Roboto", sans-serif;
        font-size: 1rem;
        line-height: 1.85;
        position: relative;
    }

    .dashboard-content-wrapper .dashboard-section + .dashboard-section {
        margin-top: 60px;
    }

    .dashboard-section {
        position: relative;
    }

    .dashboard-recent-activity {
        /*border: 1px solid rgba(0, 0, 0, 0.05);*/
        /*border-radius: 3px;*/
    }

    .dashboard-recent-activity .title {
        padding: 20px;
        font-size: 1rem;
        font-weight: 600;
        border-top: 1px solid rgba(0, 0, 0, 0.3);
        background: transparent;
    }

    dashboard-recent-activity .activity-list > i {
        width: 30px;
        color: rgba(1, 209, 61, 0.5);
        -webkit-transition: all .3s ease;
        -o-transition: all .3s ease;
        transition: all .3s ease;
    }

    .fa, .fas {
        font-weight: 900;
    }

    .fa, .far, .fas {
        font-family: Font Awesome\ 5 Free;
    }

    .fa, .fab, .fal, .far, .fas {
        -moz-osx-font-smoothing: grayscale;
        -webkit-font-smoothing: antialiased;
        display: inline-block;
        font-style: normal;
        font-variant: normal;
        text-rendering: auto;
        line-height: 1;
    }

    *, ::after, ::before {
        box-sizing: border-box;
    }

    .dashboard-recent-activity .activity-list:last-child {
        border-bottom: 0;
    }

    .dashboard-recent-activity .activity-list {
        padding: 20px;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        -webkit-transition: all .3s ease;
        -o-transition: all .3s ease;
        transition: all .3s ease;
    }

    *, ::after, ::before {
        box-sizing: border-box;
    }
    div {
        display: block;
    }

    

</style>
<div class="main-container">
    <div class="container">
        @include('account/inc/breadcrumbs')

        <div class="row">
            <div class="col-md-3 page-sidebar">
                @include('account.inc.sidebar')
            </div>
            <!--/.page-sidebar-->

            <div class="col-md-9 page-content">
                @include('flash::message')
                @if (isset($errors) and $errors->any())
                <?php $errorMessage = ''; ?>
                <div class="col-xl-12" style="display:none;">
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><strong>{{ t('oops_an_error_has_occurred') }}</strong></h5>
                        <ul class="list list-check">
                            @foreach ($errors->all() as $error)
                            <?php $errorMessage .= "<li>" . $error . "</li>" ?>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @if(!empty($errorMessage))
                <script>
                    Swal.fire({
                        html: '<?=$errorMessage?>',
                        icon: "error",
                        confirmButtonText: "<u>Ok</u>",
                    });
                </script>
                @endif
                @endif
                <div class="inner-box default-inner-box">
                    <div class="row">
                        <div class="col-md-2 col-xs-4 col-xxs-12 inline-block">
                            <h3 class="no-padding text-center-480 useradmin">
                                <a href="">
                                    <img class="userImg" src="{{  \App\Helpers\Helper::getImageOrThumbnailLink($user); }}" alt="user">&nbsp;
                                </a>
                            </h3>
                        </div>
                        <div class="col-md-2 col-xs-4 col-xxs-12 inline-block">
                            {{ $user->name }}
                        </div>
                        
                        <div class="col-md-8 col-xs-12 col-xxs-12">
                            <div class="header-data text-center-xs">
                                <!-- Threads Stats -->
                                <ul>


                                    @if (isset($user) and in_array($user->user_type_id, [1]))

                                    <li class="account-counter-li" hidden>
                                        <a href="{{ url('account/my-posts') }}" hidden>
                                            <span class="account-counter">
                                                <?php $totalPostsVisits = (isset($countPostsVisits) and $countPostsVisits->total_visits) ? $countPostsVisits->total_visits : 0 ?>
                                                        {{ \App\Helpers\Number::short($totalPostsVisits) }}
                                            </span>
                                            {{ trans_choice('global.Jobs visit', getPlural($totalPostsVisits)) }}
                                        </a>
                                    </li>

                                    <li class="account-counter-li">
                                        <a href="{{ url('account/my-posts') }}">
                                            <span class="account-counter">
                                                    {{ \App\Helpers\Number::short($countPosts) }}
                                            </span>
                                            <p>{{ trans_choice('global.count_posts', getPlural($countPosts)) }}</p>
                                        </a>
                                    </li>
                                    @endif
                                    <li class="account-counter-li">
                                        <a href="{{ url('account/messages') }}">
                                            <span class="account-counter">
                                                {{ isset($countThreads) ? \App\Helpers\Number::short($countThreads) : 0 }}
                                            </span>
                                            <p>{{ trans_choice('global.Direct Messages', getPlural($countThreads)) }}</p>
                                        </a>
                                    </li>

                                    @if (isset($user) and in_array($user->user_type_id, [2]))
                                    <!-- Favorites Stats -->
                                    <li class="account-counter-li">
                                        <a href="{{ url('account/favourite') }}">
                                            <span class="account-counter">
                                                {{ \App\Helpers\Number::short($countFavoritePosts) }}
                                            </span>
                                            {{ trans_choice('global.count_favorites', getPlural($countFavoritePosts)) }}
                                        </a>
                                    </li>
                                    @endif

                                    @if (isset($user) and in_array($user->user_type_id, [1]))
                                    <li class="account-counter-li">
                                        <a href="javascript:void(null)">
                                            <span class="account-counter">
                                                {{ \App\Helpers\Number::short(auth()->user()->credits - auth()->user()->remaining_credits) }}/{{auth()->user()->credits}}
                                            </span>
                                            <p>{{t('Contact Card Remaining')}}</p>
                                        </a>
                                    </li>
                                    @endif
                                    @if (isset($user) and in_array($user->user_type_id, [1]))
                                    <li class="account-counter-li">
                                        <a href="javascript:void(null)">
                                            <span class="account-counter">
                                                {{ \App\Helpers\Number::short(auth()->user()->posts - auth()->user()->remaining_posts) }}/{{auth()->user()->posts}}
                                            </span>
                                            <p>{{t('Job Posts Remaining')}}</p>
                                        </a>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                    <br>

                    <div class="dashboard-section dashboard-recent-activity" hidden
                         style="max-height: 500px;overflow: hidden">
                        <h4 class="title">Recent Activities </h4>
                        @foreach($activity as $key=>$item)
                        <div class="activity-list">
                            <i class="fas fa-bolt"></i>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <div class="content">
                                <h5>{{$item->title}}</h5>
                                <span class="time">{{$item->created_at->format('Y-m-d') }}</span>
                            </div>
                            <a href="{{url('/account/deleteActivity/')}}/{{$item->id}}"
                               class="close-activity" style=" position: absolute;
							  right: 160px">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <!--/.page-content-->
        </div>
        <!--/.row-->
    </div>
    <!--/.container-->
</div>
<!-- /.main-container -->
@endsection

@section('after_styles')
<link href="{{ url()->asset('plugins/bootstrap-fileinput/css/fileinput.min.css') }}" rel="stylesheet">
@if (config('lang.direction') == 'rtl')
<link href="{{ url()->asset('plugins/bootstrap-fileinput/css/fileinput-rtl.min.css') }}" rel="stylesheet">
@endif
<style>
    .krajee-default.file-preview-frame:hover:not(.file-preview-error) {
        box-shadow: 0 0 5px 0 #666666;
    }
</style>
@endsection

@section('after_scripts')

@endsection

