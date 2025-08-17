<div class="modal fade parent" id="approve_new_skills" tabindex="-1" aria-labelledby="approve_new_skills"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approve_new_skills-title">{{trans('Approve Skill')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form action="{{URL('/')}}/admin/approve_new_skill" method="post"
                      accept-charset="UTF-8"
                      enctype="multipart/form-data" id="approveskill">
                    @csrf

                    <div class="mb-3">
                        <label for="subject-name"
                               class="col-form-label">{{trans('Select Option')}}</label>
                        <select id="skill_status" name="skill_status" class="form-control">
                            <option value="1">Approve</option>
                            <option value="2">Reject</option>
                        </select>
                    </div>

                    <input type="hidden" id="skill_id" name="skill_id">
                    <div class="modal-footer">

                        <button type="submit" id="approveskill_button" onclick="disabledbutton()"
                                class="btn btn-primary">{{trans('Approve Skill')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
