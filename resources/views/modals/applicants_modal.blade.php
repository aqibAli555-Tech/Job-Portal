<div class="modal fade" id="unlock_applicants_modal" data-backdrop="static" role="dialog" data-dismiss="modal"
    aria-labelledby="unlock_applicants_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                {{ t('Unlocked Applicants') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="unlock_applicants_modal_body"></div>
            </div>
        </div>
    </div>
</div>
@include('account.inc.modal')