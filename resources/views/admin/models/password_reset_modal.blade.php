<div class="modal fade parent" id="resetModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    {{ trans('Reset Password') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form action="{{ URL('/') }}/password/reset" method="post" accept-charset="UTF-8"
                    enctype="multipart/form-data" id="send_email">
                    @csrf
                    <div class="mb-3">
                        <label for="subject-name" class="col-form-label">{{ trans('Enter New Password') }}</label>
                        <input type="text" class="form-control" id="password" name="password" minlength="8" required>
                    </div>
                    <div class="mb-3">
                        <label for="subject-name" class="col-form-label">{{ trans('Confirm Password') }}</label>
                        <input type="text" class="form-control" id="cpassword" name="cpassword" minlength="8" required>
                    </div>

                    <input type="hidden" id="user_id" name="user_id">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{trans('admin.Close') }}</button>
                        <button type="submit" id="reset_pass" class="btn btn-primary">{{ trans('Reset Password') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function reset_pass(user_id) {
            $('#user_id').val(user_id);
            $('#resetModal').modal('show');
        }
    </script>