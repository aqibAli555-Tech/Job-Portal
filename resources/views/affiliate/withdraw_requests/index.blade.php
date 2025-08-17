@extends('affiliate.layouts.master')
@section('content')
@include('common.spacer')
<div class="main-container">

<div class="container">
        @include('affiliate/inc/breadcrumbs')
        <div class="row">
            @if (Session::has('flash_notification'))
                <div class="col-xl-12">
                    <div class="row">
                        <div class="col-xl-12">
                            @include('flash::message')
                        </div>
                    </div>
                </div>
            @endif
            <!--/.page-sidebar-->
            <div class="col-md-3 page-sidebar">
                @include('affiliate.inc.sidebar')
            </div>
             <div class="col-md-8 page-content">
                <div class="inner-box">
                <h2 class=""><i class=""></i> {{ t('withdraw_requests') }}</h2>
                    <p style="background: #615583;color: #fff;padding: 5px;text-align: left;float: right;">
                        {!! t('All payments are paid out through PayPal') !!}
                    </p>
                    <hr style="clear: both;">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <select name="month" id="month" class="form-control">
                                        <option value="">Select Month</option>    
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
                                <select name="year" id="year" class="form-control">
                                    <option value="">Select Year</option>
                                    <?php foreach (App\Helpers\Helper::getwithdrawYears() as $value => $name) : ?>
                                        <option value="<?= $value ?>"><?= $name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select name="filter_status" id="filter_status" class="form-control">
                                    <option value="">Select Status</option>
                                    <?php foreach (App\Helpers\Helper::getWithdrawStatuses() as $value => $name) : ?>
                                        <option value="<?= $value ?>"><?= $name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <button type="button" class="btn btn-primary" onclick="resetFields()">Reset</button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-sm table-bordered datatables-withdraw-requests"
                            data-url="{{ url('affiliate/withdraw_requests_ajax') }}">
                            <thead>
                            <tr>
                                <th>{{ t('Details') }}</th>
                                <th>{{ t('Status') }}</th>
                                <!-- <th>{{ t('Action') }}</th> -->
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <div style="clear:both"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('after_scripts')
<script>
    function numericInput(event) {
        const input = event.target;
        const value = input.value;
        const cleanedValue = value.replace(/[^0-9]/g, '');
        input.value = cleanedValue;
    }
    function delete_withdraw_request(requestId) {
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
                var formData = new FormData();
                var url = '{{url("affiliate/delete_withdraw_request")}}/' + requestId;
                formData.append('_token', '{{ csrf_token() }}');
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        Swal.fire(
                            'Deleted!',
                            'Your Request has been deleted.',
                            'success'
                        );
                        if (response.success) {
                            window.location.reload()
                        } else {
                            alert(response.msg);
                        }

                    },
                    error: function (xhr) {
                        Swal.fire(
                            'Error!',
                            'There was a problem deleting your message.',
                            'error'
                        );
                    }
                });
            }
        });
    }
    function resetFields() {
        document.getElementById('month').value = '';
        document.getElementById('year').value = '';
        document.getElementById('filter_status').value = '';
        $('#month').trigger('change');
    }
</script>
@endsection