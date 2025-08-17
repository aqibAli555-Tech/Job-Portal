<div class="modal fade parent" id="reject_cv_popup" tabindex="-1" aria-labelledby="approve_cv_contacta"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approve_cv_contacta">Reject CV No Contact</h5>
                <button type="button" class="close close_modal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <div id="rejected_reason_container">
                        <label for="rejected_reason">Rejected Reason:</label>
                        <select id="rejected_reason" name="rejected_reason" class="form-control">
                            <option value="Watermark issue">Watermark issue</option>
                            <option value="Contact Information not removed">Contact Information not removed</option>
                            <option value="CV Not Opening">CV Not Opening</option>
                            <option value="Incorrect CV">Incorrect CV</option>
                        </select>
                    </div>
                </div>

                <input type="hidden" id="user_id_no_contact_cv" name="user_id_no_contact_cv">

                <div class="modal-footer">
                    <button type="text" id="approve_cv_no_contact_button" onclick="reject_cv()" class="btn btn-primary">{{trans('Reject CV')}}</button>
                </div>
            </div>
        </div>
    </div>
</div>


