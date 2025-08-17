@extends('layouts.master')

@section('content')
    @include('common.spacer')
    <style>
        .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
            color: white !important;
            background-color: #615583 !important;
            border-color: #dee2e6 #dee2e6 #fff;
            padding: 10px 60px 10px 60px;
        }

        .nav-tabs .nav-link {

            padding: 10px 60px 10px 60px;
            background-color: #ffffff;
            min-width: 200px;
            color: black;
        }
        .inner-box {
            min-width: 66% !important;
        }
        .visitor-container {
            width: 60px;
        }
    </style>

    <div class="main-container">
        <div class="container">
            @include('account/inc/breadcrumbs')
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

                <div class="col-md-3 page-sidebar">
                    @include('account/inc/sidebar')
                </div>
                <!--/.page-sidebar-->
              @if ($pagePath != 'favourite')
                <div class="col-md-9 page-content">
                    <div class="container-fluid">
                        <div class="row justify-content-center">
                            <div class="col-12">
                                <ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" id="tab1-tab" data-toggle="tab" href="my-posts" role="tab" aria-controls="my-posts" aria-selected="true">
                                            Active Jobs ({{$post_count}})
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="tab2-tab" data-toggle="tab" href="posts-archived" role="tab" aria-controls="posts-archived" aria-selected="false">
                                            Archived Jobs ({{$archived_post_count}})
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <br>
                    @endif
                    <div class="inner-box">
                        @if ($pagePath == 'favourite')
                            <h2 class="title-2"><i class=""></i> {{ t('Favourite jobs') }}</h2>
                        @else
                            <h2 class="title-2"><i class="icon-docs"></i> {{ t('Posts') }} </h2>
                        @endif

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    @if (auth()->user()->user_type_id == 1)
                                        <a class="btn btn-primary company_post" onclick="check_subscription()"
                                           href="javascript:void(0)">
                                            <i class="fa fa-plus"></i> {{ t('Create a Job ad') }}
                                        </a>
                                    @endif
                                </div>
                                <div class="col-sm-2 text-right">
                                    <label class="control-label text-right">{{ t('Search') }}
                                    </label>
                                </div>
                                <div class="col-sm-4 searchpan">
                                    <input type="text" class="form-control" id="filterInput">
                                    <a title="clear filter" class="clear-filter" href="#clear"><i
                                                class="fa fa-times"></i></a>
                                </div>
                            </div>
                        </div>
                        <tr>
                            <td colspan="4">
                                <h6 class="text-muted archived-text" style="text-align: center;display:none">
                                    {{ t('When your 30 day subscription ends, your job posts become archived here. You can resubscribe and repost your archived jobs again from here at anytime. Please keep in mind, after 3 months of being archived, your job posts will be deleted automatically') }}
                                </h6>
                            </td>
                        </tr>
                        <div class="table-data">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('modal_location')
    @parent
    @include('layouts.inc.modal.send-by-email')
    @include('modals.post_archived_or_delete_reasons')
@endsection
@section('after_scripts')
    @include('account.post.inc.post-js')
@endsection
