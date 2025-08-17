<div id="edit-availability-modal" class="modal fade parent" tabindex="-1" role="dialog" aria-labelledby="edit-availabilityLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4>{{ trans('Update Availability') }}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form action="{{admin_url('/availability/availabilityEdit')}}" method="POST">
                <div class="modal-body">
                    <input type="text" hidden="hidden" id="availabilityId" name="id">
                    <div class="form-group col-md-12">
                        <input class="form-control" type="text" id="availabilityVal" name="name" required="required">
                    </div>
                    <div class="form-group col-md-12 pt-4">
                        <select name="status" class="form-control select1" required id="availabilitystatus">
                            <option value="" selected>Select option</option>
                            <option value="1">{{trans('admin.active')}}</option>
                            <option value="0">{{trans('admin.Inactive')}}</option>

                        </select>
                    </div>
                </div>
    	        <div class="modal-footer">
                    <button type="submit" name="send" class="btn btn-primary ladda-button">{{ trans("admin.update") }}</button>
                </div>
            </form>
        </div>
    </div>
</div>