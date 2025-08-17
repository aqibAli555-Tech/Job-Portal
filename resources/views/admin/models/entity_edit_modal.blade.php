<div id="entity_edit_modal" class="modal fade parent" tabindex="-1" role="dialog"aria-labelledby="entity_edit_modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4>{{ trans('admin.update_entities') }} :</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form action="{{admin_url('/entityCauses/entityEdit')}}" method="get">
                <div class="modal-body">
                    <input type="text" hidden="hidden" id="entityId" placeholder="Add a new Entity" name="id">
                    <div style="width: 70%;float: left;">
                        <input class="form-control" type="text" id="entityVall" name="name" required="required" >
                    </div>
                </div>
    	        <div class="modal-footer">
                    <button type="submit" name="send" class="btn btn-primary ladda-button">{{ trans('admin.update') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>