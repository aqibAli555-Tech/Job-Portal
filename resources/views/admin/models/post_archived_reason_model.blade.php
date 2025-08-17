<div class="modal fade parent" id="post_archived_reason_modal" tabindex="-1" aria-labelledby="approve_new_skills" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{trans('Update')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <form action="{{admin_url('/post_archived_reasons_post/post')}}" method="POST">

                        <input type="text" hidden="hidden" id="availabilityId" name="id">
                        <div class="form-group pt-4">
                            <input class="form-control" type="text" id="availabilityVal" name="title"
                                   required="required">
                        </div>
                        <div class="form-group pt-4">
                            <select name="status" id="reason_status" class="form-control select1" required>
                                <option value="" selected>Select option</option>
                                <option value="1">{{trans('admin.active')}}</option>
                                <option value="0">{{trans('admin.Inactive')}}</option>
                            </select>
                        </div>

                        <div style="width: 30%;text-align: center;float: right">
                            <button type="submit" name="send"
                                    class="btn btn-primary ladda-button">{{ trans("admin.update") }}</button>
                        </div>
                    </form>
                </div>
        </div>
    </div>
</div>
