<div class="modal fade parent" id="send-message" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Send Message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form action="{{ URL('/') }}/admin/messages/send" method="post" accept-charset="UTF-8"
                      enctype="multipart/form-data" id="send_email_post" onsubmit="disableButton()">
                    @csrf
                    <div class="mb-3">
                        <label for="subject-name" class="col-form-label">{{ trans('Message') }}</label>
                        <textarea class="form-control" required name="message" rows="6"></textarea>
                    </div>
                    <input type="hidden" id="user_id_message" name="send_user_id">
                    <div class="modal-footer">
                        <button type="submit"
                                class="btn btn-primary">Send Message
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
