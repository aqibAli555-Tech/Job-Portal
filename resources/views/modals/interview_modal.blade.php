<div class="modal fade" id="interview_applicants_modal" data-backdrop="static" role="dialog" data-dismiss="modal"
    aria-labelledby="unlock_applicants_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="interview_applicants_modalq">{{ t('You have an applicants in the Interview status for more than two weeks.') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="interview_applicants_modal_body"></div>
            </div>
        </div>
    </div>
</div>

@include('account.inc.modal')