<div id="salary_type_edit_modal" class="modal fade parent" tabindex="-1" role="dialog"aria-labelledby="salary_type_editLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{admin_url('/salary_type_post')}}" method="POST">
                <div class="modal-header">
                    <h4>{{ trans('Update Post Type') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden"  id="salary_type_id" name="salary_type_id">
                    <div class="form-group col-md-12">
                        <input class="form-control" type="text" id="salary_type_name" name="salary_type_name" required="required">
                    </div>
                    <div class="form-group col-md-12 pt-4">
                        <select name="status" id="salary_type_status" class="form-control select1" required>
                            <option value="" selected>Select option</option>
                            <option value="1" >{{trans('admin.active')}}</option>
                            <option value="0" >{{trans('admin.Inactive')}}</option>
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