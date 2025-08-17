<?php

use App\Http\Controllers\PageController;

if (auth()->check()) {
    $companies = PageController::userCompanies();
}
?>
<style type="text/css">
    .modal-dialog {
        max-width: 100%;

        display: flex;
    }
</style>
<div class="apply-popup">
    <div class="modal fade bd-example-modal-lg" id="apply-popup-id" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i data-feather="edit"></i>{{ t('Contact Employee') }} (Job Seeker)</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form role="form" id="form2" action="{{ url('account/messages/messagesend/0') }}" method="post">
                        <div class="form-group">
                            <select class="form-control" name="compamy" id="company" required="required" style="display:none;">
                                @if(!empty($companies))
                                @foreach($companies as $key => $item)
                                <option value="{{$item}}">{{$item->name}}</option>
                                @endforeach
                                @endif
                            </select>
                            <input type="hidden" name="send_user_id" id="send_user_id" value="{{ Request::segment(2) }}">
                            <textarea style="height:300px" id="message" name="message" maxlength="2000" class="form-control"
                                      placeholder="Message"
                                      rows="20" required>{{ old('message') }}</textarea>
                            <small>{{ t('Message') }} (2000 words maximum) <sup style="color: red">*</sup></small>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block" id="sendBtn">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

