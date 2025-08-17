<div class="modal fade" id="post-archived-reason" data-backdrop="static" role="dialog" data-dismiss="modal"
     aria-labelledby="unlock_applicants_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"
                    id="unlock_applicants_modal">{{t('Hungry For Jobs would like to know why you are Archiving or Deleting this job post, please select a reason below:')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            @csrf
            <div class="modal-body">
                <select class="form-control select1" id="reason_id" required name="reason">
                    <option value="">Select reason</option>
                    @if(!empty($get_all_reasons))
                        @foreach($get_all_reasons as $reason)
                            <option value="{{$reason->id}}">{{$reason->title}}</option>
                        @endforeach
                    @endif
                </select>
                <input type="hidden" name="post_id" id="post_id_for_Reason">
                <input type="hidden" name="url" id="url">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary"
                        onclick="save_reasons()">{{ trans('Submit') }}</button>
            </div>
        </div>
    </div>
</div>
<script>
    function save_reasons() {
        var postId = $('#post_id_for_Reason').val();
        var reason_id = $('#reason_id').val();
        var next_url = $('#url').val();

        if (reason_id == '' || reason_id == null) {
            $('#post-archived-reason').modal('hide');

            var message = "Please select reason first";
            const config = {
                html: true,
                title: 'Error',
                html: message,
                icon: 'error',
                allowOutsideClick: false,
                confirmButtonText: 'ok',

            };
            Swal.fire(config)
            return false;
        }
        $.ajax({
            method: 'POST',
            url: '{{url('account/add_reason_for_post_archived_or_delete')}}',
            data: {
                'postId': postId,
                'reason_id': reason_id,
            },
            dataType: 'json',
            success: function (c) {
                if (c.status == true) {
                    window.location.href = next_url+'?reason_id='+reason_id;
                } else {
                    $('#post-archived-reason').modal('hide');
                    var message = "Unable to complete this action";
                    const config = {
                        html: true,
                        title: 'Error',
                        html: message,
                        icon: 'error',
                        allowOutsideClick: false,
                        confirmButtonText: 'ok',

                    };
                    Swal.fire(config).then((value) => {

                    });
                }
            }
        });
    }
</script>
