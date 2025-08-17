
@extends('layouts.master')

@section('content')
    @includeFirst([config('larapen.core.customizedViewPath') . 'common.spacer', 'common.spacer'])
    <div class="main-container">
        <div class="container">
            @include('account/inc/breadcrumbs')

            <div class="row">
                <div class="col-md-3 page-sidebar">
                    @includeFirst([config('larapen.core.customizedViewPath') . 'account.inc.sidebar', 'account.inc.sidebar'])
                </div>
                <!--/.page-sidebar-->

                <div class="col-md-9 page-content">
                    <div class="inner-box">
                        <h2 class="title-2">
                            <i class=""></i> {{ t('inbox') }}

                        <!--......................... Here Adding the Icon with text .................-->

                        </h2>

                        @if (session()->has('flash_notification'))
                            <div class="row">
                                <div class="col-xl-12">
                                    @include('flash::message')
                                </div>
                            </div>
                        @endif
                        <div id="successMsg" class="alert alert-success hide" role="alert"></div>
                        <div id="errorMsg" class="alert alert-danger hide" role="alert"></div>

                        <div class="inbox-wrapper">
                            <div class="row">
                                <div class="col-md-3 col-lg-2">
                                    <div class="btn-group hidden-sm"></div>
                                </div>

                                <div class="col-md-9 col-lg-10">

                                    <div class="btn-group">
                                        <button type="button" class="btn btn-secondary">
                                            <div class="form-check p-0 m-0">
                                                <input type="checkbox" id="form-check-all">
                                            </div>
                                        </button>

                                        <button type="button" class="btn btn-secondary dropdown-toggle"
                                                data-toggle="dropdown">
                                            <span class="dropdown-menu-sort-selected">{{ t('action') }}</span>
                                        </button>

                                        {!! csrf_field() !!}
                                        <ul id="groupedAction" class="dropdown-menu dropdown-menu-sort" role="menu">
                                            <li class="dropdown-item selection">
                                                <a href="{{ url('account/messages/actions?type=markAsRead') }}">
                                                    {{  t('Mark as read') }}
                                                </a>
                                            </li>
                                            <hr class="p-0 m-0">

                                            <li class="dropdown-item selection">
                                                <a href="{{ url('account/messages/actions?type=markAsUnread') }}">
                                                    {{ t('Mark as unread') }}
                                                </a>
                                            </li>
                                            <hr class="p-0 m-0">
                                            <li class="dropdown-item selection">
                                                <a href="{{ url('account/messages/actions?type=markAsImportant') }}">
                                                    {{ t('Mark as important') }}
                                                </a>
                                            </li>
                                            <hr class="p-0 m-0">
                                            <li class="dropdown-item selection">
                                                <a href="{{ url('account/messages/actions?type=markAsNotImportant') }}">
                                                    {{ t('Mark as not important') }}
                                                </a>
                                            </li>
                                            <hr class="p-0 m-0">
                                            <li class="dropdown-item selection">
                                                <a href="{{ url('account/messages/actions?type=delete') }}">
                                                    {{ t('Delete') }}
                                                </a>
                                            </li>

                                        </ul>
                                    </div>


                                    <button hidden type="button" id="btnRefresh" class="btn btn-secondary hidden-sm"
                                            data-toggle="tooltip" title="{{ t('refresh') }}">
                                        <span class="fas fa-sync-alt"></span>
                                    </button>
                                    <div class="btn-group" hidden>
                                        <button type="button" class="btn btn-secondary dropdown-toggle"
                                                data-toggle="dropdown">
                                            {{ t('more') }}
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li class="dropdown-item">
                                                <a class="markAllAsRead">{{ t('Mark all as read') }}</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="message-tool-bar-right pull-right" id="linksThreads">
                                        @include('account.messenger.threads.links')

                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                @include('account.messenger.partials.sidebar')
                                <div class="col-md-9 col-lg-10">
                                    <div class="message-list">
                                        <div id="listThreads">

                                            @include('account.messenger.threads.threads')

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/. inbox-wrapper-->
                    </div>
                </div>
                <!--/.page-content-->
            </div>
            <!--/.row-->
        </div>
        <!--/.container-->
    </div>
    <!-- /.main-container -->

    <div class="modal fade modal-delete" id="modal-unlockk" style="z-index: 111111111;" tabindex="1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close myClose" data-dismiss="modal">
                        &times;
                    </button>
                    <h4><i data-feather="calendar"></i>Confirmation</h4>
                    <p>Are you sure you want to view contact card?</p>
                    <small class="text-danger"><b>Note:</b> {{t('It will take 1 credit from your account')}}.</small>
                    <br><br>

                    <div style="text-align: right">
                        <a href="" class="mybtn-contact conatct" style="margin-right: 3px">Yes</a>
                        <a href="#" class="mybtn-contact conatct" data-dismiss="modal" style="width: auto;text-align: center;color: #fff;">No</a>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection

@section('after_styles')
    <style>
        {{-- Center image related to the parent element --}}
        .loading-img {
            position: absolute;
            width: 32px;
            height: 32px;
            left: 50%;
            top: 50%;
            margin-left: -16px;
            margin-right: -16px;
            z-index: 100000;
        }
    </style>
@endsection

@section('after_scripts')
    <script>

        

        var loadingImage = '{{ url('images/loading.gif') }}';
        var loadingErrorMessage = '{{ t('Threads could not be loaded') }}';
        var confirmMessage = '{{ t('confirm_this_action') }}';
        var actionText = '{{ t('action') }}';

        var actionErrorMessage = '{{ t('This action could not be done') }}';
        var title = {
            'seen': '{{ t('Mark as read') }}',
            'notSeen': '{{ t('Mark as unread') }}',
            'important': '{{ t('Mark as important') }}',
            'notImportant': '{{ t('Mark as not important') }}',
        };
        $(function () {
            $('.checkContactCard123').on("click", function (e) {
                var thread_id =$(this).attr("data-id");
                e.preventDefault();
                e.stopPropagation();
                var a = thread_id;
                var url = '{{ url('account/messages/checkcontact/')}}';
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {a: a},
                    success: function (c){
                        var user_id = c.user_data.user_id;
                        var userurl = '{{ url('/UnlockProfile/') }}/' + user_id;
                        var redirect_url = '{{ url('account/messages/') }}/' + thread_id;
                        if (c.isUnlock == 0 || c.package==0) {
                            e.preventDefault();
                            e.stopPropagation();
                            var url='<?=url('account/upgrade')?>';
                            var text='<?= t("your_subscription_has_expired_to_chat_with_employees_follow_these_steps");?>';
                            var text1='<?= t("subscribe_to_one_our_packages_from_the_upgrade_accounts_page");?>';
                            var text2='<?= t("unlock_any_employees_ontact_card_through_their_profile_to_chat_with_them");?>';
                            var message=text+ '<br>'+text1+'<br>'+text2;
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
                            window.location.replace(redirect_url)
                        }
                    },
                });
            });
        });

    </script>
    <script src="{{ url()->asset('js/app/messenger.js') }}" type="text/javascript"></script>
@endsection