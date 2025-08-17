<div id="post_type_modal" class="modal fade parent" tabindex="-1" role="dialog"aria-labelledby="post_type_modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4>{{ trans('Update Post Type') }}</h4><br>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form action="{{admin_url('/post_type_post')}}" method="POST">
                <div class="modal-body">
                    <input type="hidden"  id="post_type_id" name="post_type_id">
                    <div class="form-group col-md-12">
                        <input class="form-control" type="text" id="post_type_name" name="post_type_name" required="required">
                    </div>
                    <div class="form-group col-md-12 pt-4">
                        <select name="status" id="post_type_status" class="form-control select1" required>
                            <option value="" selected>Select option</option>
                            <option value="1" >{{trans('admin.active')}}</option>
                            <option value="0" >{{trans('admin.Inactive')}}</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>