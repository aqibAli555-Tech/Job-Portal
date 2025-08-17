<style>
.tooltip.top>.tooltip-arrow {
    border-top: 5px solid #615583 !important;
}
.select2-container--open{
    z-index: 1000000;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    text-align: left;
}
</style>

<div class="modal fade" id="rejected_modal" data-backdrop="static" role="dialog" data-dismiss="modal"
    aria-labelledby="rejected_modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                {{ t('Why Have You Rejected This Employee (Job Seeker)?') }}
            </div>
            <div class="modal-body">
                <form action="{{url('account/rejected')}}" method="post">
                    <div class="form-group">
                    <label style="float: left"></label>
                        <div class="col-md-12">
                            <select id="rejected_reason" name="rejected_reason" class="form-control select1" required>
                                <option value="">Select Reasons</option>

                                @if (empty($rejected_reasons))
                                        <?php $rejected_reasons=\App\Models\RejectedReason::get_all_rejected_reasons(); ?>
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
                        <button type="submit" class="btn btn-primary">{{t('confirm')}}</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>