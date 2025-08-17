@extends('admin.layouts.master')
@section('content')
    <style>
        td {
            font-size: 12px;
        }
    </style>
    @if (Session::has('flash_notification'))
        <div class="col-xl-12">
            @include('flash::message')
        </div>
    @endif
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">{{ trans('admin.referral_commission') }}</h4>
            <div class="row">
                <div class="col-md-3">
                    <label for="company">Search By Referrer</label>
                    <select class="form-control select1" id="referrer" name="referrer">
                        <option value="">All</option>
                        @foreach ($data['referrers'] as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="month">Select Month</label>
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
                        <label for="month">Select Year</label>
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
                        <select name="status" id="status" class="form-control">
                            <option value="">Select Option</option>
                            <?php foreach (App\Helpers\Helper::getCommissionStatuses() as $value => $name) : ?>
                                <option value="<?= $value ?>"><?= $name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <br>

            <div class="table-responsive">
                <table class="table table-striped  table-sm table-bordered datatables-referral-commission"
                       data-url="{{admin_url('referral-commission-ajax')}}">
                    <thead>
                    <tr>
                    <th><input type="checkbox" name="checkAll" id="checkAll" class="checkbox"
                        onclick="toggleCheckbox(this)" value=""></th>
                        <th>{{ trans('admin.affiliate_user') }}</th>
                        <th>Details</th>
                        <th>{{ trans('admin.status') }}</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

@endsection

@section('after_scripts')
<script>
    $(function () {
        $('input[name="daterange"]').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });
        $('input[name="daterange"]').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        });
        $('input[name="daterange"]').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
        });
        $('input[name="daterange"]').attr('autocomplete', 'off');

    })
    function toggleCheckbox(value) {
            var checkbox = document.getElementsByClassName("checkbox");
            checkbox.checked = !checkbox.checked;
            var checkboxes = $('.checkbox');

            if (value.checked) {
                $.each(checkboxes, function () {
                    checkboxes.prop('checked', true);
                });
            } else {
                $.each(checkboxes, function () {
                    checkboxes.prop('checked', false);
                });
            }
            var ids = "";
            var checkedboxes = [];
            $.each(checkboxes, function () {
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
                $('#move_to_paid').show();
                ids = checkedboxes.toString();
            } else {
                $('#move_to_paid').hide();

            }

            $('#commission_ids').val(ids);
        }

        function SingletoggleCheckbox(value) {
            var checkbox = document.getElementsByClassName("checkbox");
            checkbox.checked = !checkbox.checked;
            var checkboxes = $('.checkbox');

            if (value.checked) {

                $.each(checkboxes, function () {
                    $(value).prop('checked', true);
                });
            } else {
                $.each(checkboxes, function () {
                    $(value).prop('checked', false);
                });
            }
            var ids = "";
            var checkedboxes = [];
            $.each(checkboxes, function () {
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
                $('#move_to_paid').show();
                ids = checkedboxes.toString();
            } else {
                $('#move_to_paid').hide();
            }

            $('#commission_ids').val(ids);
        }
    </script>
    <script>
    function change_commission_status_to_paid(t, message = '') {
        let url = $(t).data('url');
        let status = $(t).text().trim() === 'Paid' ? 0 : 1;        
        if(status == 0){
            Swal.fire({
                icon: 'error',
                html: 'Status can not change to unpaid',
            });
        }else{
            if(message === ''){
                message = 'Want to change status paid';
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
                            console.log(data);
                            
                            if (data.status) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: data.message,
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
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
    }
</script>
@endsection