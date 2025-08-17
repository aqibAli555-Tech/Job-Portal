<div id="experience_edit_modal" class="modal fade parent" tabindex="-1" role="dialog"aria-labelledby="experience_editLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4>{{ trans('admin.update_skill') }}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form action="{{admin_url('/skillExperience/experienceEdit')}}" method="get">
                <div class="modal-body">
                    <input type="text" hidden="hidden" id="ExpeId" name="id">
                    <input type="text" id="ExpeVall" name="name" required="required" style="width: 100%;border-radius: 3px;padding: 5px;    font-size: 15px;    color: #222d32;border: 1px solid #dbdbdb;height: 33px">
                </div>
    	        <div class="modal-footer">
                    <button type="submit" name="send" class="btn btn-primary ladda-button">{{ trans('admin.update') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>