<div class="modal fade parent" id="update_applicant_status" tabindex="-1" aria-labelledby="approve_new_cva"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="update_applicant_status1">{{trans('Update Applicant Status')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form action="{{admin_url('update_applicant_status')}}" method="post" accept-charset="UTF-8"
                      enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="subject-name"
                               class="col-form-label">{{trans('Select Option')}}</label>
                        <select id="status" name="status" class="form-control" onchange="check_type(this.value)">
                            <option value="applied">Applied</option>
                            <option value="interview">Interview</option>
                            <option value="hired">Hired</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>

                    <input type="hidden" id="status_applicant_id" name="applicant_id">
                    <div class="modal-footer">

                        <button type="submit" onclick="disabledbutton()"
                                class="btn btn-primary">{{trans('Update')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<div class="modal fade parent" id="rejected_modal" data-backdrop="static" role="dialog" data-dismiss="modal"
     aria-labelledby="rejected_modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                {{ t('Why Have You Rejected This Employee (Job Seeker)?') }}
            </div>
            <div class="modal-body">
                <form action="{{admin_url('update_applicant_status')}}" method="post">
                    <div class="form-group">
                        <label style="float: left"></label>
                        <div class="col-md-12">
                            <select id="rejected_reason" name="rejected_reason" class="form-control select1" required>
                                <option value="">Select Reasons</option>
                                @foreach ($data['rejected_reasons'] as $key => $value)
                                    <option value="{{ $value->id }}">
                                        {{ $value->title }}
                                    </option>
                                @endforeach

                            </select>
                        </div>
                        <input type="hidden" id="rejectedt_applicant_id" name="applicant_id">
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{t('confirm')}}</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>



