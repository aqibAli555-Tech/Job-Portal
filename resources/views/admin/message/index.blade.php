@extends('admin.layouts.master')
@section('content')
    <link href="{{ url()->asset('css/message.css') }}?version=<?= time() ?>" rel="stylesheet">

    @if (Session::has('flash_notification'))
        <div class="col-xl-12">
            @include('flash::message')
        </div>
    @endif
    <div class="container">
        <a class="btn btn-primary" style="margin-left: 7%;" href="#" onclick="start_new_conversation()"> Start A New Conversation</a>
        <div class="row">
            @include('admin.message.threads')
            <div class="chat col-12 col-md-7 col-lg-8">
                <br>
                <h4>Please select a Conversation to chat</h4>
            </div>
            <input type="hidden" id="thread_id">
        </div>
        <div class="row">
            <form id="chatForm" role="form" method="POST" action="" enctype="multipart/form-data">
                <div class="type-chat" style="display: none;">
                    <div class="input-container p-3 border rounded"
                         style="background: #F9FBFD !important;box-shadow: 0px 8px 10px rgba(0, 0, 0, 0.20);">
                        <div class="row">
                            <div class="col-12 col-md-8 offset-md-4">
                                <div class="input-group">
                                <textarea class="form-control write-message" name="body" id="body" placeholder="Type your message here" required="" rows="4" style="height: 120px;"></textarea>
                                    <div class="input-group-append">
                                        <label class="input-group-text" for="addFile"
                                               style="display:inline-block; cursor: pointer; height: 42px;">
                                            <i class="fa fa-paperclip" aria-hidden="true"></i>
                                        </label>
                                        <input type="file" id="addFile" name="filename" class="d-none">
                                        <button type="submit" id="sendMessage" class="btn btn-primary">Send <i
                                                    class="fa fa-arrow-circle-right" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12 col-md-8 offset-md-4">
                                <div class="input-group">
                                    <p class="d-none d-lg-block" style="font-size: 12px; margin-bottom: 0; margin-left:20px;">{{ t('Expand this text box to write more by dragging the two grey lines down on the right.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        @includeFirst([config('larapen.core.customizedViewPath') . 'admin.models.start_new_conversation','admin.models.start_new_conversation'])
        @endsection
        @section('before_scripts')
            <script src="{{ url()->asset('js/AdminMessenger.js') }}?version=<?= time() ?>"></script>
@endsection
