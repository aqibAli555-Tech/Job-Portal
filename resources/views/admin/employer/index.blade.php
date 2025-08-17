@extends('admin.layouts.master')
@section('content')
    <?php
    $search = !empty(request('search')) ? request('search') : '';
    $verified_phone = !empty(request('verified_phone')) ? request('verified_phone') : '';
    $verified_email = !empty(request('verified_email')) ? request('verified_email') : '';
    $limit = !empty(request('limit')) ? request('limit') : '';
    $current_subscription_users =request()->get('current_subscription_users');
    $filter = !empty(request('filter')) ? request('filter') : '';
    $daterange = request('daterange') ?? '';

    if (empty($daterange) && !empty($filter)) {
        if ($filter === 'current_month') {
            $start = now()->startOfMonth()->format('m/d/Y');
            $end = now()->endOfMonth()->format('m/d/Y');
            $daterange = "$start - $end";
        } elseif ($filter === 'last_month') {
            $start = now()->subMonth()->startOfMonth()->format('m/d/Y');
            $end = now()->subMonth()->endOfMonth()->format('m/d/Y');
            $daterange = "$start - $end";
        }
    }

    ?>
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
            <h4 class="card-title">Employer</h4>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="search">{{ trans('admin.Search') }}</label>
                        <input type="search" id="search" value="<?= $search ?>" class="form-control" name="search">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="last_login">Last Login</label>
                        <select class="form-control select1" name="last_login" id="last_login">
                            <option value="">Select Option</option>
                            <option value="1">Newest</option>
                            <option value="2">Oldest</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="last_login">Active Subscription Users</label>
                        <select class="form-control select1" name="subscription_user" id="subscription_user">
                            <option value="">Select Option</option>
                            <option value="1" <?php if(!empty($current_subscription_users)){echo 'selected';}?>>Yes</option>
                        </select>
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
                <div class="col-md-3 mb-4 mt-4">
                    <label>Created At</label>
                    <div class="input-group">
                        <input name="daterange" id="daterange" autocomplete="off" class="form-control" type="text" 
                        placeholder="Select date range" value="{{ $daterange }}">
                    </div>
                </div>
                <input type="hidden" id="filter" value="<?= $filter ?>" name="filter">
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="form-group">
                        <a class="btn btn-primary" style="display: none;" id="send_bulk_email_btn" href="#"
                           onclick="send_bulk_email()">{{trans('Send Bulk Email')}}</a>
                    </div>
                </div>

            </div>

            <div class="table-responsive">
                <table class="table table-striped  table-sm table-bordered datatables-employer"
                       data-url="{{admin_url('employer-ajax')}}">
                    <thead>
                    <tr>
                        <th><input type="checkbox" name="checkAll" id="checkAll" class="checkbox"
                                   onclick="toggleCheckbox(this)" value=""></th>
                        <th>Detail</th>
                        <th>{{ trans('admin.Featured') }}</th>
                        <th>{{ trans('admin.Type') }}</th>
                        <th>{{ trans('admin.action') }}</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    @includeFirst([
    config('larapen.core.customizedViewPath') . 'admin.models.bulk_email_employer',
    'admin.models.bulk_email_employer',
    ])

    @includeFirst([
    config('larapen.core.customizedViewPath') . 'admin.models.subscription_cancel',
    'admin.models.subscription_cancel',
    ])

    @include('admin.models.password_reset_modal')
    @includeFirst([
    config('larapen.core.customizedViewPath') . 'layouts.inc.social.horizontal',
    'layouts.inc.social.horizontal',
    ])
    @includeFirst([
    config('larapen.core.customizedViewPath') . 'admin.models.model',
    'admin.models.model',
    ])

@endsection

@section('after_scripts')
    <script type="text/javascript">

        $(function () {

            $('input[name="daterange"]').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            });
            $('input[name="daterange"]').on('apply.daterangepicker', function (ev, picker) {
                console.log($(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY')));
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
            });
            $('input[name="daterange"]').on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
            });
            $('input[name="daterange"]').attr('autocomplete', 'off');

        })
        function delete_employee(id) {
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
                    var url = "<?= url('admin/delete_employee_all_records') ?>";
                    showConfirmation(id, url);
                }
            })
        }

        function submitForm() {
            document.getElementById('myform').submit();
        }

        function verify_employer_phone(id, status) {
            if (confirm("Are you sure you want to change Phone Status ?")) {
                $.ajax({
                    url: "<?= url('admin/verify_employer_phone') ?>",
                    data: "id=" + id + "&status=" + status,
                    method: "get",
                    success: function () {
                        window.location.reload();
                    }
                })
            } else {
                return false;
            }
        }

        function verify_employer_email(id, status) {
            if (confirm("Are you sure you want to change Email Status ?")) {
                $.ajax({
                    url: "<?= url('admin/verify_employer_email') ?>",
                    data: "id=" + id + "&status=" + status,
                    method: "get",
                    success: function () {
                        window.location.reload();
                    }
                })
            } else {
                return false;
            }
        }

        function cancel_sub(id) {
            $.ajax({
                url: "<?= url('admin/get_user_current_subscribed_packages') ?>",
                data: "id=" + id,
                method: "get",
                success: function (response) {
                    if (response.status == 1) {
                        var packageDetails = response.package_details;
                        var packageSelect = document.getElementById('package_id');
                        packageSelect.innerHTML = '<option value="">Select Package</option>';
                        packageDetails.forEach(function (package) {
                            var option = document.createElement('option');
                            option.textContent = package.package_name + ' ( Expire date: ' + package.package_expire_date + ' )';
                            option.value = package.id;
                            packageSelect.appendChild(option);
                        });
                        $('#user_id_package').val(id);
                        $('#subscription_cancel').modal('show');

                    } else {
                        Swal.fire({
                            title: "OOPS!",
                            text: 'Not package found',
                            icon: "error",
                            button: "Ok",
                        });
                    }
                }
            })
        }

        function add_feature(id, status) {
            if (confirm("Are you sure you want to change Feature status?")) {
                $.ajax({
                    url: "<?= url('admin/add_feature') ?>",
                    data: "id=" + id + "&status=" + status,
                    method: "get",
                    success: function () {
                        table_employer.draw();
                    }
                })
            } else {
                return false;
            }
        }

        $(document).ready(function () {

            $('#check_password').on('click', function (event) {
                if (confirm('Are you sure you want to cancel subscription of this user?')) {
                    var userInput = prompt("Please enter the PIN code:");
                    if (userInput == 'hungry') {
                        $('#overlay').show();
                        $('#subscription_cancel_form').submit();
                    } else {
                        Swal.fire({
                            title: "OOPS!",
                            text: 'Password not matched',
                            icon: "error",
                            button: "Ok",
                        });
                        event.preventDefault();
                    }
                } else {
                    event.preventDefault();
                }
            });
        });

        function disabledbutton() {
            var btn = document.getElementById('sendemail');
            btn.disabled = true;
        }

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
                $('#send_bulk_email_btn').show();
                ids = checkedboxes.toString();
            } else {
                $('#send_bulk_email_btn').hide();

            }

            $('#user_ids').val(ids);
        }

        function send_bulk_email() {
            $('#sendbulkemail').modal('show');
        }
    </script>
@endsection
