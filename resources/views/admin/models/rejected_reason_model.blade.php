<div id="rejected-reason-modal" class="modal fade parent" tabindex="-1" role="dialog"aria-labelledby="rejected-reason-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4>{{ trans('Update Rejected Reason') }}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form action="{{admin_url('/rejected_reasons/post')}}" method="POST">

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
                    
                    <div style="text-align: center;float: right" class="pt-4">
                        <button type="submit" name="send" class="btn btn-primary ladda-button">{{ trans("admin.update") }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>