<div class="modal fade parent" id="rejectRequestModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    {{ trans('Reject Withdraw Request') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form action="{{ admin_url('withdraw_request_status_change') }}" method="post" accept-charset="UTF-8"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="rejected_reason" class="col-form-label">{{ trans('Reject Reason') }}</label>
                        <textarea class="form-control" id="rejected_reason" name="rejected_reason" rows="3" placeholder="Enter Reason" required></textarea>
                    </div>
                    <input type="hidden" id="id" value="" name="id">
                    <input type="hidden" id="status" value="" name="status">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{trans('admin.Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ trans('Reject') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>