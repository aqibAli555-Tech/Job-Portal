@extends('admin.layouts.master')
@section('content')
    <style>
        td {
            font-size: 12px;
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
                        <label for="year">Select Month</label>
                        <select name="month" id="month" class="form-control">
                                <option value="">Select Option</option>    
                                <option value="January">January</option>
                                <option value="February">February</option>
                                <option value="March">March</option>
                                <option value="April">April</option>
                                <option value="May">May</option>
                                <option value="June">June</option>
                                <option value="July">July</option>
                                <option value="August">August</option>
                                <option value="September">September</option>
                                <option value="October">October</option>
                                <option value="November">November</option>
                                <option value="December">December</option>
                        </select>                           
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="year">Select Year</label>
                        <select name="year" id="year" class="form-control">
                            <option value="">Select Option</option>
                            <?php foreach (App\Helpers\Helper::getwithdrawYears() as $value => $name) : ?>
                                <option value="<?= $value ?>"><?= $name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="month">Select Status</label>
                        <select name="filter_status" id="filter_status" class="form-control">
                            <option value="">Select Option</option>
                            <?php foreach (App\Helpers\Helper::getWithdrawStatuses() as $value => $name) : ?>
                                <option value="<?= $value ?>"><?= $name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <br>

            <div class="table-responsive">
                <table class="table table-striped  table-sm table-bordered datatables-withdraw-requests"
                    data-url="{{ admin_url('withdraw-requests-ajax') }}">
                    <thead>
                        <tr>
                            <th>{{ trans('admin.affiliate_user') }}</th>
                            <th>Details</th>
                            <th>{{ trans('admin.status') }}</th>
                            <th>{{ trans('admin.action') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>

        </div>
    </div>
    @include('admin.models.withdraw_request_change_status')
    @include('admin.models.paypal_payout_modal')
@endsection

@section('after_scripts')
<script>
    function request_to_paypal(id){
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, request it!'
        }).then((result) => {
            if (result.isConfirmed) {
                var formData = new FormData();
                var status = 'paypal_requested';
                var url = '{{admin_url("withdraw_request_status_change")}}';
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('id', id);
                formData.append('status', status);
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        if (response.status === 'success') {
                            Swal.fire(
                                'Requested!',
                                response.message,
                                'success'
                            ).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                response.message || 'Something went wrong.',
                                'error'
                            );
                        }
                    },
                    error: function (xhr) {
                        let message = 'There was a problem sending the request to PayPal.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        Swal.fire(
                            'Error!',
                            message,
                            'error'
                        );
                    }
                });
            }
        });
    }
    function reject_request(id) {
        $('#payment_payout_modal').modal('hide');
        var status = 'rejected';
        $('#id').val(id);
        $('#status').val(status);
        $('#rejected_reason').val('');
        $('#rejectRequestModal').modal('show');
    }
    function approve_request(id) {
        $('#payment_payout_modal').modal('hide');
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, approve it!'
        }).then((result) => {
            if (result.isConfirmed) {
                var formData = new FormData();
                var status = 'approved';
                var url = '{{admin_url("withdraw_request_status_change")}}';
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('id', id);
                formData.append('status', status);
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        if (response.status === 'success') {
                            Swal.fire(
                                'Approved!',
                                response.message,
                                'success'
                            ).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                response.message || 'Something went wrong.',
                                'error'
                            );
                        }
                    },
                    error: function (xhr) {
                        let message = 'There was a problem approving the request.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        Swal.fire(
                            'Error!',
                            message,
                            'error'
                        );
                    }
                });
            }
        });
    }
    
    function view_payout(id) {
        $.ajax({
            url: `check_payout_status/${id}`,
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success' && response.data) {
                    const data = response.data;
                    const item = data.items[0];
                    const completedTime = new Date(data.batch_header.time_completed);
                    const formattedTime = completedTime.toLocaleString('en-US', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: 'numeric',
                        minute: '2-digit',
                        hour12: true
                    });
                    let html = `
                        <div class="d-flex justify-content-end mb-3">
                            <button class="btn btn-primary me-2" onclick="approve_request(${id})">
                                <i class="fas fa-check-circle"></i> Approve
                            </button>
                            <button class="btn btn-danger" onclick="reject_request(${id})">
                                <i class="fas fa-times-circle"></i> Reject
                            </button>
                        </div>
                        <div class="card mb-3 shadow-sm border-primary">
                            <div class="card-header bg-primary text-white">
                                PayPal Request Summary
                            </div>
                            <div class="card-body">
                                <p><strong>Batch Payment ID:</strong> ${data.batch_header.payout_batch_id}</p>
                                <p><strong>Status:</strong> ${data.batch_header.batch_status}</p>
                                <p><strong>Time Completed:</strong> ${formattedTime}</p>
                                <p><strong>Amount:</strong> ${data.batch_header.amount.value} ${data.batch_header.amount.currency}</p>
                                <p><strong>Paypal Fees:</strong> ${data.batch_header.fees.value} ${data.batch_header.fees.currency}</p>
                            </div>
                        </div>

                        <div class="card mb-3 shadow-sm">
                            <div class="card-header bg-secondary text-white">
                                Transaction Details
                            </div>
                            <div class="card-body">
                                <p><strong>Receiver Email:</strong> ${item.payout_item.receiver}</p>
                                <p><strong>Transaction ID:</strong> ${item.transaction_id}</p>
                                <p><strong>Transaction Status:</strong> ${item.transaction_status}</p>
                                <p><strong>Note:</strong> ${item.payout_item.note}</p>
                            </div>
                        </div>`;

                    if (item.errors && item.errors.name) {
                        html += `
                            <div class="card border-danger">
                                <div class="card-header bg-danger text-white">
                                    Error Information
                                </div>
                                <div class="card-body">
                                    <p><strong>Error Name:</strong> ${item.errors.name}</p>
                                    <p><strong>Error Message:</strong> ${item.errors.message}</p>
                                </div>
                            </div>`;
                    }

                    $('#payment_payout_modal .modal-body').html(html);
                    $('#payment_payout_modal').modal('show');

                } else {
                    Swal.fire('Error', response.message || 'Unable to check PayPal payout status.', 'error');
                }
            },
            error: function (xhr) {
                let message = 'Unable to check PayPal payout status.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire(
                    'Error!',
                    message,
                    'error'
                );
            }
        });
    }

</script>
@endsection