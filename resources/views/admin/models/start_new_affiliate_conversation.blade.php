<div class="modal fade parent" id="start-new-conevrsation" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Start Conversation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form action="{{ URL('/') }}/admin/affiliate_messages/send" method="post" accept-charset="UTF-8"
                      enctype="multipart/form-data" id="send_email_post" onsubmit="disableButton()">
                    @csrf

                    <div class="form-group">
                        <label class="col-form-label">{{ trans('Affiliate') }}</label>
                        <select class="form-control select1 modal-select" name="send_user_id" required>
                            <option value="">Select Affiliate</option>
                            @foreach($affiliates as $affiliate)
                                <option value="{{$affiliate->id}}">{{$affiliate->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="subject-name" class="col-form-label">{{ trans('Message') }}</label>
                        <textarea class="form-control" required name="message" rows="6"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="submit"
                                class="btn btn-primary">Start Conversation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
