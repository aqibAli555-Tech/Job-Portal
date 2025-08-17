<style>
    #rejected_modal {
        z-index: 999999;
    }
</style>

<div class="modal fade parent" id="rejected_modal" data-backdrop="static" role="dialog" data-dismiss="modal"
     aria-labelledby="rejected_modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                {{ t('Why Have You Rejected This Employee (Job Seeker)?') }}
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label style="float: left"></label>
                    <div class="col-md-12">
                        <select name="rejected_reason" class="form-control select1" required>
                            <option value="">Select Reasons</option>

                            @if (!empty($rejected_reasons))
                            @endif
                            @foreach ($rejected_reasons as $key => $value)
                                <option value="{{ $value->id }}">
                                    {{ $value->title }}
                                </option>
                            @endforeach

                        </select>
                    </div>
                    <input type="hidden" name="id" id="id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">{{t('confirm')}}</button>
                </div>
            </div>

        </div>
    </div>
</div>