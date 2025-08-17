<div class="modal fade parent" id="sendbulkemail" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ trans('Send Bulk Email') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form action="{{ URL('/') }}/admin/send_bulk_email_employer" method="post" accept-charset="UTF-8"
                      enctype="multipart/form-data" id="send_email_post" onsubmit="disableButton()">
                    @csrf

                    <div class="mb-3">
                        <label for="subject" class="col-form-label">{{ trans('Subject') }}</label>
                        <input type="text" required class="form-control" name="subject">
                    </div>
                    <div class="mb-3">
                        <label for="subject-name" class="col-form-label">{{ trans('Message') }}</label>
                        <textarea class="form-control" required name="message" rows="6"></textarea>
                    </div>
                    <input type="hidden" id="user_ids" name="user_ids">
                    <div class="modal-footer">
                        <button type="submit"
                                class="btn btn-primary">{{ trans('Send Email') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
