
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
                <!--/.page-sidebar-->
                
                <div class="col-md-9 page-content">
                    <div class="inner-box">
                        <h2 class="title-2">
                            <i class="icon-mail"></i> {{ t('inbox') }}
                        </h2>
    
                        @if (Session::has('flash_notification'))
                            <div class="row">
                                <div class="col-xl-12">
                                    @include('flash::message')
                                </div>
                            </div>
                        @endif
                        
                        @if (isset($errors) and $errors->any())
                            <div class="alert alert-danger">
                                <ul class="list list-check">
                                    @foreach($errors->all() as $error)
                                        <li class="mb-0">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
    
                        <div id="successMsg" class="alert alert-success hide" role="alert"></div>
                        <div id="errorMsg" class="alert alert-danger hide" role="alert"></div>
                        
                        <div class="inbox-wrapper">
                            <div class="row">
                                <div class="col-md-12 col-lg-12">
                                    <div class="user-bar-top">
                                        <div class="user-top">
                                            <p>
                                                <a href="{{ url('affiliate/messages') }}">
                                                    <i class="fas fa-inbox"></i>
                                                </a>&nbsp;
                                                <?php

                                                $ThreadParticipant = \App\Models\ThreadParticipant::where('thread_id', $thread->id)->where('user_id','!=',auth()->user()->id)->first();
                                                $thread_user_data = \App\Models\User::withoutGlobalScopes([\App\Models\Scopes\VerifiedScope::class, \App\Models\Scopes\ReviewedScope::class])->where('id', $ThreadParticipant->user_id)->first();
                                                if($thread_user_data->user_type_id==2){
                                                   
                                                    $url=url('profile').'/'.$thread_user_data->id;
                                                }else{
                                                    $url=url('companyprofile').'/'.$thread_user_data->id;
                                                }
                                                ?>
                                                <a href="#user">
                                                    <strong>

                                                        <a href="{{ $url }}">
                                                            {{ $thread_user_data->name }}
                                                        </a>
                                                    </strong>
                                                </a>

                                                <?php if(!empty($thread->post->title)) {?>
                                                    <strong>{{ t('Contact request about') }}</strong> <a href="{{ \App\Helpers\UrlGen::post($thread->post) }}">{{ $thread->post->title }}</a>
                                                <?php } ?>
                                            </p>
                                        </div>
    
                                        <div class="message-tool-bar-right pull-right call-xhr-action">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ url('affiliate/messages/'.$thread->id . '/actions?type=delete') }}"
                                                   class="btn btn-secondary"
                                                   data-toggle="tooltip"
                                                   data-placement="top"
                                                   title=""
                                                   data-original-title="{{ t('Delete') }}">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12 col-lg-12 chat-row">
                                    <div class="message-chat p-2 rounded">
                                        <div id="messageChatHistory" class="message-chat-history">
                                            <div id="linksMessages" class="text-center">
                                                {!! $linksRender !!}
                                            </div>
                                            @include('affiliate.messenger.messages.messages')
                                         </div>
                                        <div class="type-message">
                                            <div class="type-form">
                                                <?php $updateUrl = url('affiliate/messages/' . $thread->id); ?>
                                                <form id="chatForm" role="form" method="POST" action="{{ $updateUrl }}" enctype="multipart/form-data">
                                                    {!! csrf_field() !!}
                                                    <input name="_method" type="hidden" value="PUT">
                                                    <textarea id="body"
                                                          name="body"
                                                          maxlength="500"
                                                          rows="3"
                                                          class="input-write form-control"
                                                          placeholder="{{ t('Type a message') }}"
                                                          style="{{ (config('lang.direction')=='rtl') ? 'padding-left' : 'padding-right' }}: 75px;"
                                                    ></textarea>
                                                    <div class="button-wrap">
                                                        <input id="addFile" name="filename" type="file" file="true">
                                                        <button id="sendChat" class="btn btn-primary" type="submit">
                                                            <i class="fas fa-paper-plane" aria-hidden="true"></i>
                                                        </button>
                                                    </div>
                                                </form>
                                                <p class="d-none d-lg-block" style="font-size: 12px;">{{ t('Expand this text box to write more by dragging the two grey lines down on the right.') }}</p>

                                            </div>
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
@endsection

@section('after_styles')
    @parent
    <link href="{{ url()->asset('plugins/bootstrap-fileinput/css/fileinput.min.css') }}" rel="stylesheet">
    @if (config('lang.direction') == 'rtl')
        <link href="{{ url()->asset('plugins/bootstrap-fileinput/css/fileinput-rtl.min.css') }}" rel="stylesheet">
    @endif
    <style>
        .file-input {
            display: inline-block;
        }
    </style>
@endsection

@section('after_scripts')
    @parent

    <script>
        var loadingImage = '{{ url('images/loading.gif') }}';
        var loadingErrorMessage = '{{ t('Threads could not be loaded') }}';
        var confirmMessage = '{{ t('confirm_chat_deletion') }}';
        var actionErrorMessage = '{{ t('This action could not be done') }}';
        var title = {
            'seen': '{{ t('Mark as read') }}',
            'notSeen': '{{ t('Mark as unread') }}',
            'important': '{{ t('Mark as important') }}',
            'notImportant': '{{ t('Mark as not important') }}',
        };
    </script>
    <script src="{{ url()->asset('js/app/messenger.js') }}?version=<?= time() ?>" type="text/javascript"></script>
    <script src="{{ url()->asset('js/app/messenger-chat.js') }}" type="text/javascript"></script>
    
    <script src="{{ url()->asset('plugins/bootstrap-fileinput/js/plugins/sortable.min.js') }}" type="text/javascript"></script>
    <script src="{{ url()->asset('plugins/bootstrap-fileinput/js/fileinput.min.js') }}" type="text/javascript"></script>
    <script src="{{ url()->asset('plugins/bootstrap-fileinput/themes/fa/theme.js') }}" type="text/javascript"></script>
    <script>
        /* Initialize with defaults (filename) */
        $('#addFile').fileinput(
        {
            theme: "fa",
            language: '{{ config('app.locale') }}',
            @if (config('lang.direction') == 'rtl')
            rtl: true,
            @endif
            allowedFileExtensions: {!! getUploadFileTypes('file', true) !!},
            maxFileSize: {{ (int)config('settings.upload.max_file_size', 1000) }},
            browseClass: 'btn btn-primary',
            browseIcon: '<i class="fas fa-paperclip" aria-hidden="true"></i>',
            layoutTemplates: {
                main1: '{browse}',
                main2: '{browse}',
                btnBrowse: '<div tabindex="500" class="{css}"{status}>{icon}</div>',
            }
        });
    </script>
@endsection