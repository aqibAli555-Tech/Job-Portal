
@extends('affiliate.layouts.master')

@section('content')
    @includeFirst([config('larapen.core.customizedViewPath') . 'common.spacer', 'common.spacer'])
    <div class="main-container">
        <div class="container">
            @include('affiliate/inc/breadcrumbs')

            <div class="row">
                <div class="col-md-3 page-sidebar">
                    @includeFirst([config('larapen.core.customizedViewPath') . 'affiliate.inc.sidebar', 'affiliate.inc.sidebar'])
                </div>

                <div class="col-md-9 page-content">
                    <div class="inner-box">
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="title mb-0">
                                <i class=""></i> {{ t('inbox') }}
                            </h2>
                            <a href="javascript:void(0)" data-toggle="modal" data-target="#modal_chat_with_admin" class="btn btn-primary btn-sm m-1">
                                Questions? Start Chat With Hungry For Jobs Team
                            </a>
                        </div>
                        <hr>

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
                                <div class="col-md-12 col-lg-12">
                                    <div class="message-list">
                                        <div id="listThreads">
                                            @include('affiliate.messenger.threads.threads')
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                    
                    </div>
                </div>
            </div>
        </div>
    </div>
@include('modals.affiliate_chat_with_admin');
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
        var confirmMessage = '{{ t('confirm_chat_deletion') }}';
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
    <script src="{{ url()->asset('js/app/messenger.js') }}?version=<?= time() ?>" type="text/javascript"></script>
@endsection