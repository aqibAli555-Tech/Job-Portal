@extends('admin.layouts.master')
@section('content')
    <?php
    $search = !empty(request('search')) ? request('search') : '';
    $verified_phone = !empty(request('verified_phone')) ? request('verified_phone') : '';
    $verified_email = !empty(request('verified_email')) ? request('verified_email') : '';
    $limit = !empty(request('limit')) ? request('limit') : '';
    ?>
    <style>
        td {
            font-size: 14px;
        }

        .form-switch .form-check-input {
            border: 1px solid;
        }
    </style>
    @if (Session::has('flash_notification'))
        <div class="col-xl-12">
            @include('flash::message')
        </div>
    @endif
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">{{ $title }}</h4>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="search">{{ trans('admin.Search') }}</label>
                        <input type="search" id="search" value="<?= $search ?>" class="form-control" name="search">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="last_login">Referred By</label>
                        <select class="form-control select1" name="affiliate_id" id="affiliate_id">
                            <option value="">Select Option</option>
                            @foreach ($affiliates as $affiliate)
                                <option value="{{ $affiliate->id }}" {{ ($affiliate->id == $affiliate_id)?'selected':'' }} >{{ $affiliate->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <div class="d-flex justify-content-between">
                    <h4>{{ $title }} List</h4>
                    <a href="{{ admin_url('create_affiliate') }}" class="btn btn-primary">
                        {{ trans('admin.Add_Affiliate') }}
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped  table-sm table-bordered datatables-affiliates"
                    data-url="{{ admin_url('affiliates-ajax') }}">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="checkAll" id="checkAll" class="checkbox"
                                    onclick="toggleCheckbox(this)" value=""></th>
                            <th>Detail</th>
                            <th>Commission Slots</th>
                            <th>{{ trans('admin.status') }}</th>
                            <th>{{ trans('admin.action') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>

        </div>
    </div>
@endsection

@section('after_scripts')
    <script type="text/javascript">
        function submitForm() {
            document.getElementById('myform').submit();
        }

        function disabledbutton() {
            var btn = document.getElementById('sendemail');
            btn.disabled = true;
        }

        function toggleCheckbox(value) {
            var checkbox = document.getElementsByClassName("checkbox");
            checkbox.checked = !checkbox.checked;
            var checkboxes = $('.checkbox');

            if (value.checked) {
                $.each(checkboxes, function() {
                    checkboxes.prop('checked', true);
                });
            } else {
                $.each(checkboxes, function() {
                    checkboxes.prop('checked', false);
                });
            }
            var ids = "";
            var checkedboxes = [];
            $.each(checkboxes, function() {
                if (this.checked) {
                    checkedboxes.push($(this).val());
                }
            });
            if (checkedboxes.length == checkboxes.length) {
                $('#checkAll').prop('checked', true);
            } else {
                $('#checkAll').prop('checked', false);
            }

            if (checkedboxes.length) {
                $('#send_bulk_email_btn').show();
                ids = checkedboxes.toString();
            } else {
                $('#send_bulk_email_btn').hide();

            }

            $('#user_ids').val(ids);
        }

        function SingletoggleCheckbox(value) {
            var checkbox = document.getElementsByClassName("checkbox");
            checkbox.checked = !checkbox.checked;
            var checkboxes = $('.checkbox');

            if (value.checked) {

                $.each(checkboxes, function() {
                    $(value).prop('checked', true);
                });
            } else {
                $.each(checkboxes, function() {
                    $(value).prop('checked', false);
                });
            }
            var ids = "";
            var checkedboxes = [];
            $.each(checkboxes, function() {
                if (this.checked) {
                    checkedboxes.push($(this).val());
                }
            });
            if (checkedboxes.length == checkboxes.length) {
                $('#checkAll').prop('checked', true);
            } else {
                $('#checkAll').prop('checked', false);
            }

            if (checkedboxes.length) {
                $('#send_bulk_email_btn').show();
                ids = checkedboxes.toString();
            } else {
                $('#send_bulk_email_btn').hide();

            }

            $('#user_ids').val(ids);
        }

        $(document).on('click', '.copy_affiliate_url', function() {
            const affiliateUrl = $(this).data('affiliate-url');
            navigator.clipboard.writeText(affiliateUrl)
                .then(() => 
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Affiliate URL copied to clipboard!',
                            })
                    )
                .catch(() => 
                    Swal.fire({
                        icon: 'error',
                        html: 'Failed to copy the URL.',
                    })
            );
        });
    </script>
<script>
    function status_change(t, message = '') {
        let url = $(t).data('url');
        let currentStatus = $(t).text().trim().toLowerCase(); // 'activate' or 'deactivate'
    
        if(currentStatus == 'active'){
            var alert_status = 'Deactive';
        }else{
            var alert_status = 'Active';
        }
        let status = currentStatus === 'active' ? 0 : 1;

        if (message === '') {
            message = 'Do you want to ' + alert_status + ' this affiliate?';
        }

        Swal.fire({
            title: 'Are you sure?',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4F2183',
            cancelButtonColor: 'default',
            cancelButtonText: 'No',
            confirmButtonText: 'Yes',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        status: status
                    },
                    success: function(data) {
                        if (data.status) {
                            if (alert_status == 'Deactive') {
                                $(t).text(alert_status).removeClass('btn-success').addClass('btn-danger');
                            } else {
                                $(t).text(alert_status).removeClass('btn-danger').addClass('btn-success');
                            }

                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: data.message,
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                html: data.message,
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            html: 'Status change failed',
                        });
                    }
                });
            }
        });
    }


    function delete_affiliate(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                var url = "{{url('/')}}/admin/delete_affiliate";
                showConfirmation(id, url);
            }
        })
    }
</script>

@endsection