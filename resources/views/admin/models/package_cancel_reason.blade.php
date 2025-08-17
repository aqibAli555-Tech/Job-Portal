<div id="packagecancelmodal" class="modal fade parent" tabindex="-1" role="dialog"aria-labelledby="packagecancelmodalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4>{{ trans('Update Ca Reason') }}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form action="{{admin_url('/package_cancel_reasons/post')}}" method="POST">
                <div class="modal-body">
                    <input type="text" hidden="hidden" id="availabilityId" name="id">
                    <div class="form-group col-md-12">
                        <input class="form-control" type="text" id="availabilityVal" name="title" required="required">
                    </div>
                    <div class="form-group col-md-12 pt-4">
                        <select name="status" id="reason_status" class="form-control select1" required>
                            <option value="" selected>Select option</option>
                            <option value="1" >{{trans('admin.active')}}</option>
                            <option value="0">{{trans('admin.Inactive')}}</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <div style="text-align: center;float: right">
                        <button type="submit" name="send" class="btn btn-primary ladda-button">{{ trans("admin.update") }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>