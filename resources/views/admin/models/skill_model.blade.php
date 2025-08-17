

<div id="skillmodal" class="modal fade parent" tabindex="-1" role="dialog"aria-labelledby="skillmodalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4>{{ trans('admin.update_skill') }} :</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form action="{{ admin_url('/employeeSkill/skillEdit') }}" method="post"
                    enctype="multipart/form-data">
                    <input type="text" hidden="hidden" id="skillId" name="id">
                    <input type="text" hidden="hidden" id="old_image" name="old_image">
                    <div class="form-group">
                        <label for="skill">Skill:</label>
                        <input type="text" id="skillVall" name="skill" class="form-control" required="required">
                    </div>
                    <div class="form-group">
                        <label for="status">Status:</label>
                        <select name="status" id="status" class="form-control select1" required>
                            <option value="">{{trans('admin.Select')}}</option>
                            <option value="1">{{trans('admin.active')}}</option>
                            <option value="0">{{trans('admin.Inactive')}}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="imageInput">Skill Image:<span class="text-danger">Image dimensions required: (380x568)</span></label>
                        <input type="file" class="form-control-file" name="image" id="imageInput">
                    </div>

                    <div class="mt-3">

                        <img id="image_preview" src="" style="height: 100px; width:100px">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="send"
                        class="btn btn-primary ladda-button">{{ trans('admin.update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>