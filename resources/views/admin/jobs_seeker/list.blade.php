@extends('admin.layouts.master')
@section('content')
    <?php
    $search = !empty(request('search')) ? request('search') : '';
    $limit = !empty(request('limit')) ? request('limit') : '';
    $employyeskill = !empty(request('employyeskill')) ? request('employyeskill') : '';
    $verified_phone = !empty(request('verified_phone')) ? request('verified_phone') : '';
    $verified_email = !empty(request('verified_email')) ? request('verified_email') : '';
    $country_code = !empty(request('country_code')) ? request('country_code') : '';
    $nationality_filter = !empty(request('nationality')) ? request('nationality') : '';
    ?>

    <style>
        td, .btn-sm {
            font-size: 14px !important;
            vertical-align: middle;
        }

        .text-truncate {

            color: black !important;:
        }
    </style>
    @if (Session::has('flash_notification'))
        <div class="col-xl-12">
            @include('flash::message')
        </div>
    @endif
    @php
        $cv_filter=request()->get('no_contact_cv');

    @endphp
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-md-4">
                    <div class="card border-end">
                        <div class="card-body bg-primary">
                            <div class="d-flex align-items-center">
                                <div>
                                    <div class="d-inline-flex align-items-center">
                                        <div class="spinner-border spinner-border-sm text-muted loading" role="status"></div>
                                        <h2 class="text-dark mb-1 font-weight-medium" id="total_cv"></h2>
                                    </div>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Total CVs
                                    </h6>
                                </div>
                                <div class="ms-auto mt-md-3 mt-lg-0">
                                    <span class="opacity-7 text-muted"><i data-feather="file-text"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-end">
                        <div class="card-body bg-warning">
                            <div class="d-flex align-items-center">
                                <div>
                                    <div class="d-inline-flex align-items-center">
                                    <div class="spinner-border spinner-border-sm text-muted loading" role="status"></div>
                                        <h2 class="text-dark mb-1 font-weight-medium" id="inprocess"></h2>
                                    </div>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">In-process
                                    </h6>
                                </div>
                                <div class="ms-auto mt-md-3 mt-lg-0">
                                    <span class="opacity-7 text-muted"><i data-feather="rotate-cw"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-end">
                        <div class="card-body bg-secondary">
                            <div class="d-flex align-items-center">
                                <div>
                                    <div class="d-inline-flex align-items-center">
                                    <div class="spinner-border spinner-border-sm text-muted loading" role="status"></div>
                                        <h2 class="text-dark mb-1 font-weight-medium" id="pending_no_contact_cv"></h2>
                                    </div>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Pending
                                    </h6>
                                </div>
                                <div class="ms-auto mt-md-3 mt-lg-0">
                                    <span class="opacity-7 text-muted"><i data-feather="download"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-end">
                        <div class="card-body bg-light">
                            <div class="d-flex align-items-center">
                                <div>
                                    <div class="d-inline-flex align-items-center">
                                        <div class="spinner-border spinner-border-sm text-muted loading" role="status"></div>
                                        <h2 class="text-dark mb-1 font-weight-medium" id="qa_pending"></h2>
                                    </div>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">QA Pending
                                    </h6>
                                </div>
                                <div class="ms-auto mt-md-3 mt-lg-0">
                                    <span class="opacity-7 text-muted"><i data-feather="alert-circle"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-end">
                        <div class="card-body bg-danger-subtle">
                            <div class="d-flex align-items-center">
                                <div>
                                    <div class="d-inline-flex align-items-center">
                                        <div class="spinner-border spinner-border-sm text-muted loading" role="status"></div>
                                        <h2 class="text-dark mb-1 font-weight-medium" id="pending_approved"></h2>
                                    </div>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Approval Pending
                                    </h6>
                                </div>
                                <div class="ms-auto mt-md-3 mt-lg-0">
                                    <span class="opacity-7 text-muted"><i data-feather="alert-circle"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-end">
                        <div class="card-body bg-success">
                            <div class="d-flex align-items-center">
                                <div>
                                    <div class="d-inline-flex align-items-center">
                                        <div class="spinner-border spinner-border-sm text-muted loading" role="status"></div>
                                        <h2 class="text-dark mb-1 font-weight-medium" id="approved_no_contact_cv"></h2>
                                    </div>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Approved
                                    </h6>
                                </div>
                                <div class="ms-auto mt-md-3 mt-lg-0">
                                    <span class="opacity-7 text-muted"><i data-feather="check-circle"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-end">
                        <div class="card-body bg-danger">
                            <div class="d-flex align-items-center">
                                <div>
                                    <div class="d-inline-flex align-items-center">
                                        <div class="spinner-border spinner-border-sm text-muted loading" role="status"></div>
                                        <h2 class="text-dark mb-1 font-weight-medium" id="rejected_no_contact_Cv"></h2>
                                    </div>
                                    <h6 class="font-weight-normal mb-0 w-100 text-truncate">Rejected
                                    </h6>
                                </div>
                                <div class="ms-auto mt-md-3 mt-lg-0">
                                    <span class="opacity-7 text-muted"><i data-feather="slash"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Job Seeker List</h4>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="search">{{ trans('admin.Search') }}</label>
                                <input type="search" id="search" value="<?= $search ?>" class="form-control"
                                       name="search">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="employyeskill">{{ trans('Skills Sets') }}</label>
                                <select class="form-control select1" name="employyeskill" id="employyeskill">
                                    <option value="">Select</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="search">{{ trans('Countries') }}</label>
                                <select class="form-control select1" name="country_code" id="country_code">
                                    <option value="">Select</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="search">{{ trans('Nationality') }}</label>
                                <select class="form-control select1" name="nationality" id="nationality">
                                    <option value="">Select</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="no_contact_cv">No contact CV</label>
                                <select class="form-control select1" name="no_contact_cv" id="no_contact_cv">
                                    <option value="">Select Option</option>
                                    <option value="1">Approved</option>
                                    <option value="2">Rejected</option>
                                    <option value="3" <?php if ($cv_filter == 'no') {
                                        echo 'selected';
                                    } ?>>Pending
                                    <option value="5">QA Approval Pending</option>
                                    <option value="0">Approval Pending</option>

                                    </option>
                                </select>
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
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12 pt-4">
                            <div class="form-group">
                                @if(!empty(request('updated_skill')))
                                    <a class="btn btn-primary"
                                       href="<?php echo url('admin/set_skill_status_as_Read'); ?>">{{ trans('admin.Mark as Read Skill') }}</a>
                                @endif
                                @if(auth()->user()->user_type_id==4)
                                    <a class="btn btn-primary" href="<?php echo url('admin/bulk_download_cv'); ?>">Bulk
                                        Download CV</a>
                                @endif
                                <a class="btn btn-primary" style="display: none;" id="send_bulk_email_btn"
                                   href="javascript:void(null)"
                                   onclick="send_bulk_email()">{{trans('Send Bulk Email')}}</a>
                            </div>
                        </div>
                    </div>
                    <a class="btn btn-purple mt-3" target="_blank" href="{{ admin_url('compare-cv?type=admin') }}" ><i class="fa fa-columns"></i>&nbsp; Compare CV For Admin</a>
                    <a class="btn btn-purple mt-3" target="_blank" href="{{ admin_url('compare-cv?type=QA') }}" ><i class="fa fa-columns"></i>&nbsp; Compare CV For QA</a>
                    @if(auth()->user()->user_type_id==4)
                        <a class="btn btn-purple mt-3" target="_blank" href="{{ admin_url('bulk_upload_hidden_detail_cv') }}" ><i class="fas fa-allergies"></i>&nbsp; Bulk Upload Hidden Contact CV</a>
                    @endif
                    <br><br>
                    <div class="table-responsive">
                        <table class="table table-striped  table-sm table-bordered datatables-job-seekers"
                               data-url="{{admin_url('job-seekers-ajax')}}" style="width:100%">
                            <thead>
                            <tr>
                                <th><input type="checkbox" name="checkAll" id="checkAll" class="checkbox"
                                           onclick="toggleCheckbox(this)" value=""></th>
                                <th>Detail</th>
                                <th></th>
                                <th>Action</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.models.password_reset_modal')
    @includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.social.horizontal','layouts.inc.social.horizontal',])
    @includeFirst([config('larapen.core.customizedViewPath') . 'admin.models.model','admin.models.model',])

    @includeFirst([config('larapen.core.customizedViewPath') . 'admin.models.upload_no_contact_cv','admin.models.upload_no_contact_cv',])

    @includeFirst([config('larapen.core.customizedViewPath') . 'admin.models.approve_new_cv','admin.models.approve_new_cv',])
    @includeFirst([config('larapen.core.customizedViewPath') . 'admin.models.approve_new_skills','admin.models.approve_new_skills',])
    @includeFirst([config('larapen.core.customizedViewPath') . 'admin.models.approve_no_contact_cv','admin.models.approve_no_contact_cv',])

    @includeFirst([config('larapen.core.customizedViewPath') . 'admin.models.bulk_email_employee','admin.models.bulk_email_employee',])

@endsection

@section('after_scripts')
    <script>
        
        // $(document).ready(function(){
            $.ajax({
                type: "GET",
                url: "{{URL('/')}}/admin/get_filters_data",
                success: function (response) {
                    $.each(response.data.posts, (i, val) => {
                        $('#post').append(`<option value="${val.id}"> ${val.title} - (${val.company.name}) </option>`);
                    });

                    $.each(response.data.skill_sets, (i, val) => {
                        const selected = (val.skill.trim() === '{{ $employyeskill }}'.trim()) ? 'selected' : '';
                        $('#employyeskill').append(`<option value="${val.skill}" ${selected}> ${val.skill} - (${val.user_count}) </option>`);
                    });
                    
                    $.each(response.data.nationality, (i, val) => {
                        const selected = (val.id == '{{ $nationality_filter }}') ? 'selected' : '';
                        $('#nationality').append(`<option value="${val.id}" ${selected}> ${val.name} - (${val.count}) </option>`);
                    });
                    $.each(response.data.countries, (i, val) => {
                        const selected = (val.code == '{{ $country_code }}') ? 'selected' : '';
                        $('#country_code').append(`<option value="${val.code}" ${selected}> ${val.country_name} - (${val.count}) </option>`);
                    });
                },
                error: function (response) {
                    showSwalAlert('Error!', 'Something went wrong. Please try again', 'error', 'Ok')
                }
            });
            
            $.ajax({
                type: "GET",
                url: "{{URL('/')}}/admin/get_static_data",
                success: function (response) {
                    $('.loading').hide();
                    $("#total_cv").text(response.data.total_cv);
                    $("#inprocess").text(response.data.inprocess);
                    $("#pending_no_contact_cv").text(response.data.pending_no_contact_cv);
                    $("#qa_pending").text(response.data.qa_pending);
                    $("#pending_approved").text(response.data.pending_approved);
                    $("#approved_no_contact_cv").text(response.data.approved_no_contact_cv);
                    $("#rejected_no_contact_Cv").text(response.data.rejected_no_contact_Cv);
                },
                error: function (response) {
                    showSwalAlert('Error!', 'Something went wrong. Please try again', 'error', 'Ok')
                }
            });

            
        // });

        function send_bulk_email() {

            $('#sendbulkemail').modal('show');
        }

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
                    var url = "{{url('/')}}/admin/delete_employee_all_records";
                    showConfirmation(id, url);
                }
            })
        }

        function delete_no_contact_cv(id) {
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
                    var url = "{{url('/')}}/admin/delete_no_contact_cv";
                    showConfirmation(id, url);
                }
            })
        }

        function approve_cv(id) {
            $('#approve_new_cv').modal('show');
            $('#cv_id').val(id);
        }

        function approve_cv_no_contact(id) {
            $('#user_id_no_contact_cv').val(id);
            $('#approve_cv_no_contact').modal('show');
            toggleRejectedReason();
        }

        function toggleRejectedReason(thisv) {
            var rejectedReasonContainer = $('#rejected_reason_container');
            var rejectedReasonTextarea = $('#rejected_reason');
            if (thisv.value === '2') {
                rejectedReasonContainer.show();
                $(rejectedReasonTextarea).prop('required', true);
            } else {
                rejectedReasonContainer.hide();
                rejectedReasonTextarea.val('');
                $(rejectedReasonTextarea).prop('required', false);

            }
        }

        function approve_skill(id) {
            $('#approve_new_skills').modal('show');
            $('#skill_id').val(id);
        }

        function disabledbutton() {
            document.getElementById('send_email_post').submit();
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


        function upload_Cv(id) {
            $('#upload_Cv_model').modal('show');
            $('#cv_user_id').val(id);
        }

        function submitForm() {
            document.getElementById('myform').submit();
        }

        function verified_employee_phone(id, status) {
            if (confirm("Are you sure you want to change Phone Status ?")) {
                $.ajax({
                    url: "<?= url('admin/verify_employee_phone') ?>",
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

        function verified_employee_email(id, status) {
            if (confirm("Are you sure you want to change Email Status ?")) {

                $.ajax({
                    url: "<?= url('admin/verify_employee_email') ?>",
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

        function update_cv_status(id) {
            showSwalConfirmation("You won't be able to revert this!", 'warning', 'Are you sure?', 'Yes, Do it!', function () {
                $.ajax({
                    url: "<?= url('admin/update_cv_status') ?>",
                    data: {id: id, status: 4},
                    method: "post",
                    success: function (e) {
                        if (e.status) {
                            $('.datatables-job-seekers').DataTable().ajax.reload(null, false);
                            showSwalAlert('Great!', e.message, 'success', 'Ok')
                        } else {
                            showSwalAlert('Error!', e.message, 'error', 'Ok')
                        }
                    }
                });
            });
        }


        $('#approvecvnocontact').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            $('#approve_cv_no_contact_button').prop('disabled', true);

            $.ajax({
                type: "POST",
                url: "{{URL('/')}}/admin/approve_cv_no_contact",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    // if (response.status) {
                    //     showSwalAlert('Great!', response.message, 'success', 'Ok')
                    // } else {
                    //     showSwalAlert('Error!', response.message, 'error', 'Ok')
                    // }
                    $('#approve_cv_no_contact_button').prop('disabled', false);
                    $('.datatables-job-seekers').DataTable().ajax.reload(null, false);
                    $('#approve_cv_no_contact').modal('hide');
                },
                error: function (response) {
                    showSwalAlert('Error!', 'Something went wrong. Please try again', 'error', 'Ok')
                    $('#approve_cv_no_contact_button').prop('disabled', false);
                }
            });
        });


    </script>
@endsection