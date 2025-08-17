<div class="modal fade parent" id="sendbulkemail" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ trans('Send Bulk Email') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form id="send_email_post" accept-charset="UTF-8" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="subject-name" class="col-form-label">{{ trans('Select Post') }}</label>
                        <select id="post" name="posts" class="form-control">
                            <?php
                            if (!empty($posts)) { foreach ($posts as $key => $value) {?>
                            <option value="<?= $value->id ?>"><?= $value->title ?> - ({{ $value->company->name }})
                            </option>
                            <?php }
                            } ?>
                        </select>
                    </div>

                    <input type="hidden" id="user_ids" name="user_ids">
                    <div class="modal-footer">
                        <button type="button" id="sendemail" class="btn btn-primary">{{ trans('Send Email') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#sendemail').click(function (e) {
            e.preventDefault();
            const formData = new FormData($('#send_email_post')[0]);
            $.ajax({
                url: "{{ URL('/') }}/admin/send_bulk_email",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $('#sendemail').prop('disabled', true).text('{{ trans('Sending...') }}');
                },
                success: function (response) {
                    showSwalAlert('Great!', 'Email has been sent successfully', 'success', 'Ok')
                    $('#sendemail').prop('disabled', false).text('{{ trans('Send Email') }}');
                    $('#sendbulkemail').modal('hide');
                },
                error: function (xhr, status, error) {
                    showSwalAlert('Great!', 'Error while sending emails.', 'success', 'Ok')
                    $('#sendemail').prop('disabled', false).text('{{ trans('Send Email') }}');
                }
            });
        });
    });
</script>
