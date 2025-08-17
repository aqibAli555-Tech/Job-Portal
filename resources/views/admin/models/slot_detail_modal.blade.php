<div id="slotDetail" class="modal fade" tabindex="-1" role="dialog"aria-labelledby="addSkillLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">{{ trans('Affiliate Commission Slot') }}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>

            <div class="modal-body">
                <form action="{{ admin_url('update_affiliated_commission_slot') }}" method="post" enctype="multipart/form-data">
                    <!-- Min Amount input -->
                    <div class="form-group">
                        <label for="min_amount">{{ trans('Min Amount') }}:</label>
                        <input type="text" class="form-control mb-2" id="min_amount" value="" name="min_amount" required>
                    </div>
                    <!-- Max Amount input -->
                    <div class="form-group">
                        <label for="max_amount">{{ trans('Max Amount') }}:</label>
                        <input type="text" class="form-control mb-2" id="max_amount" value="" name="max_amount" required>
                    </div>
                    <!-- Commission title input -->
                    <div class="form-group">
                        <label for="commission_title">{{ trans('Commission') }}:</label>
                        <input type="text" class="form-control mb-2" id="commission" value="" name="commission" required>
                    </div>

                    <input type="hidden" id="slot_id" name="slot_id">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{trans('admin.Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ trans('Update') }}</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function slot_detail(id) {
        var url = '{{admin_url("edit_affiliated_commission_slot")}}/' + id;
        $.ajax({
            url: url,
            type: "GET",
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    var slot = response.data;
                    $('#slot_id').val(id);
                    $('#min_amount').val(slot.min_amount);
                    $('#max_amount').val(slot.max_amount);
                    $('#commission').val(slot.commission);
                } else {
                    Swal.fire('Error!', response.message, 'error');
                }
                $('#slotDetail').modal('show');
            },
            error: function(xhr) {
                Swal.fire('Error!', 'Something went wrong.', 'error');
            }
        });
    }
</script>