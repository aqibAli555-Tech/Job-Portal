<div class="modal fade parent" id="subscription_cancel" tabindex="-1" aria-labelledby="subscription_cancel1"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="subscription_cancel1">
                    Subscription Cancel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form action="{{url('admin/cancel_subscription')}}"  method="post" accept-charset="UTF-8"
                      enctype="multipart/form-data" id="subscription_cancel_form">
                    @csrf
                    <select name="package_id" class="form-control" id="package_id">
                        <option value="">Select Package</option>

                    </select>
                    <input type="hidden" id="user_id_package" name="user_id_package">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{trans('admin.Close') }}</button>
                        <button type="submit" id="check_password"
                                class="btn btn-primary">Submit
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
