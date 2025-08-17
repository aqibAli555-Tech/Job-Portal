<div class="modal fade parent" id="approved_applicants" tabindex="-1" aria-labelledby="approved_applicantsLabel"
         aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approved_applicantsLabel">{{ trans('admin.approved_applicants') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form action="{{ URL('/') }}/admin/approved_applicants" method="post" accept-charset="UTF-8"
                      enctype="multipart/form-data" id="send_email_post">
                    @csrf

                    <div class="mb-3">
                        <label for="skill_accuracy"
                               class="col-form-label">{{ trans('admin.select_skill_accuracy') }}</label>
                        <select id="skill_accuracy" name="skill_accuracy" class="form-control">
                            <option value="">{{ trans('admin.select_skill_accuracy') }}</option>
                            <option value="Not Accurate">{{ trans('admin.not_accurate') }}</option>
                            <option value="Accurate">{{ trans('admin.accurate') }}</option>
                            <option value="Very Accurate">{{ trans('admin.very_accurate') }}</option>

                        </select>
                    </div>

                    <input type="hidden" id="applicants_id" name="applicants_id">
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{ trans('admin.approved') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>