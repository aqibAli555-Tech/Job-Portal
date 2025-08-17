<div class="modal fade parent" id="approve_cv_no_contact" tabindex="-1" aria-labelledby="approve_cv_contacta"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approve_cv_contacta">Approve CV No Contact</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form id="approvecvnocontact" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="subject-name" class="col-form-label">{{trans('Select Option')}}</label>
                        <select id="cv_status" name="cv_status" class="form-control" onchange="toggleRejectedReason(this)">
                            <option value="1">Approve</option>
                            <option value="2">Reject</option>
                        </select>
                        <div id="rejected_reason_container" style="display: none">
                            <label for="rejected_reason">Rejected Reason:</label>
                            <textarea id="rejected_reason" name="rejected_reason" class="form-control"></textarea>
                        </div>
                    </div>

                    <input type="hidden" id="user_id_no_contact_cv" name="user_id_no_contact_cv">

                    <div class="modal-footer">
                        <button type="submit" id="approve_cv_no_contact_button" class="btn btn-primary">{{trans('Approve CV')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


