<div id="approve_new_cv" class="modal fade parent" tabindex="-1" role="dialog"aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="approve_new_cva">{{trans('Approve CV')}}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form action="{{URL('/')}}/admin/approve_new_cv" method="post" accept-charset="UTF-8" enctype="multipart/form-data" id="approvecv">
                    @csrf
                    <div class="mb-3">
                        <label for="subject-name"
                               class="col-form-label">{{trans('Select Option')}}</label>
                        <select id="cv_status" name="cv_status" class="form-control">
                            <option value="1">Approve</option>
                            <option value="2">Reject</option>
                        </select>
                    </div>

                    <input type="hidden" id="cv_id" name="cv_id">
                    <div class="modal-footer">

                        <button type="submit" id="approve_cv" onclick="disabledbutton()"
                                class="btn btn-primary">{{trans('Approve CV')}}</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
