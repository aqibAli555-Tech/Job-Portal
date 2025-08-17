@extends('admin.layouts.master')


@section('content')
    <style>
        .tooltip-inner {
            background-color: #5f76e8 !important;
            color: white !important;
        }

        .tooltip .arrow::before {
            border-top-color: #5f76e8 !important;
        }
        .datatables-applicant td:nth-child(8),
        .datatables-applicant th:nth-child(8) {
            width: 150px;
            min-width: 150px;
            max-width: 200px;
        }
    </style>
    @php
        $status=request()->get('status');
        $post_id=request()->get('post');
        $search=request()->get('search');
        $unlock_applicant=request()->get('unlock_applicant');
        $total_unlocked=request()->get('total_unlocked');
    @endphp
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if (Session::has('flash_notification'))
                        <div class="col-xl-12">
                            @include('flash::message')
                        </div>
                    @endif
                    <h4 class="card-title">Filters</h4>
                    <div class="row">
                        <div class="col-md-2">
                            <label for="status">Status</label>
                            <select class="form-control select1" id="status" name="status">
                                <option value="">All</option>
                                <option value="applied" <?php if ($status == 'applied') {
                                    echo 'selected';
                                } ?>>Applied - ({{$data['applied']??0}})
                                </option>
                                <option value="interview" <?php if ($status == 'interview') {
                                    echo 'selected';
                                } ?>>Interview - ({{$data['interview']??0}})
                                </option>
                                <option value="hired" <?php if ($status == 'hired') {
                                    echo 'selected';
                                } ?>>Hired - ({{$data['hired']??0}})
                                </option>
                                <option value="rejected" <?php if ($status == 'rejected') {
                                    echo 'selected';
                                } ?>>Rejected - ({{$data['rejected']??0}})
                                </option>
                                <option value="pending" <?php if ($status == 'pending') {
                                    echo 'selected';
                                } ?>>Pending - ({{$data['pending']??0}})
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="status">Is Deleted
                                <a href="javascript:void(null)" data-toggle="tooltip"
                                   title="Applicants are deleted from the frontend 3 months after the job post they applied to expires">
                                    <i class="fa fa-question-circle"></i>
                                </a>
                            </label>
                            <select class="form-control select1" id="is_deleted" name="is_deleted">
                                <option value="">All</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="company">Employer</label>
                            <select class="form-control select1" id="company" name="company">
                                <option value="">All</option>
                                @foreach ($data['companies'] as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="post">Posts</label>
                            <select class="form-control select1" id="post" name="post">
                                <option value="">All</option>
                                @foreach ($data['posts'] as $item)
                                    <option value="{{ $item->id }}" <?php if (!empty($post_id) && $post_id == $item->id) {
                                        echo 'selected';
                                    } ?>>{{ $item->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="post">Unlock Applicants</label>
                            <select class="form-control select1" id="unlock_applicant" name="unlock_applicant">
                                <option value="">All</option>
                                <option value="total_unlocked" <?php if (!empty($total_unlocked)) {
                                    echo 'selected';
                                } ?>>Applicant
                                </option>
                                <option value="unlock_applicant" <?php if (!empty($unlock_applicant)) {
                                    echo 'selected';
                                } ?>>Unlock From Search Cv
                                </option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Date Range</label>
                            <div class="input-group">
                                <input name="daterange" id="daterange" autocomplete="off" class="form-control" type="text"
                                       placeholder="Select date range" value="">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="search">Search</label>
                            <input type="search" id="search" class="form-control" name="search" value="<?= $search?>">
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">

                        <div class="col-md-2">

                            <a href="{{ admin_url() . '/job-seekers' }}"
                               class="btn btn-primary btn-block text-white">{{ trans('admin.Job Seekers') }}</a>

                        </div>
                        <div class="col-md-2">

                            <form action="{{url('admin/reject_bulk_applicant')}}" method="post" id="reject-bulk-form">
                                @csrf
                                <input type="hidden" id="bulkapplicant_ids" name="applicant_ids">
                                <button type="submit" style="display:none" id="move_to_rejected"
                                        class="btn btn-primary btn-block">Reject Applicants
                                </button>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ trans('admin.Applicants') }}</h4>
                    @if(!empty($status))
                        @if( $status == 'pending')
                        <p id="pending-applicants">Total Applicants in {{ t($status) }}: {{$data['counts'] ?? 0}}</p>
                        @else
                        <p> Total Applicants in {{ t($status) }}: {{$data['counts']??0}} </p>
                        @endif
                    @endif
                    <div class="table-responsive">
                        <table class="table table-striped table-sm table-bordered datatables-applicant"
                               data-url="{{admin_url('applicant-ajax')}}" style="width:100%">
                            <thead>
                            <tr>
                                <th><input type="checkbox" name="checkAll" id="checkAll" class="checkbox"
                                           onclick="toggleCheckbox(this)" value=""></th>

                                <th>Details</th>
                                <th>{{ trans('admin.skills_sets') }}</th>
                                <th>{{ trans('admin.Company name') }}</th>
                                <th>{{ trans('admin.Post Title') }}</th>
                                <th>{{ trans('admin.Status') }}</th>
                                <th>{{ trans('admin.reasons') }}</th>
                                <th class="my-text">{{ trans('admin.skill_accuracy') }}</th>
                                <th>{{ trans('admin.action') }}</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @includeFirst([
    config('larapen.core.customizedViewPath') . 'layouts.inc.social.horizontal',
    'layouts.inc.social.horizontal',
    ])

    @includeFirst([
    config('larapen.core.customizedViewPath') . 'admin.models.update_applicant_status',
    'admin.models.update_applicant_status',
    ])

    @includeFirst([
    config('larapen.core.customizedViewPath') . 'admin.models.approved_applicants',
    'admin.models.approved_applicants',
    ])
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


        function approved_model(id) {
            $('#applicants_id').val(id);
            // $("#status").val(status).trigger('change');
            $('#approved_applicants').modal('show');
        }

        function update_status_model(id) {
            $('#status_applicant_id').val(id);
            $('#update_applicant_status').modal('show');
        }

        function check_type(value) {
            if (value === 'rejected') {
                $('#update_applicant_status').modal('hide');
                $('#rejectedt_applicant_id').val($('#status_applicant_id').val());
                $('#rejected_modal').modal('show');
            } else {
                $('#rejected_modal').modal('hide');
            }
        }

        function edit_approved_model(id, status) {
            $('#applicants_id').val(id);
            $("#skill_accuracy").val(status).trigger('change');
            $('#approved_applicants').modal('show');
        }


        function submitForm() {
            document.getElementById('myform').submit();
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
                $('#move_to_rejected').show();
                ids = checkedboxes.toString();
            } else {
                $('#move_to_rejected').hide();

            }

            $('#bulkapplicant_ids').val(ids);
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
                $('#move_to_rejected').show();
                ids = checkedboxes.toString();
            } else {
                $('#move_to_rejected').hide();
            }

            $('#bulkapplicant_ids').val(ids);
        }


        document.getElementById('move_to_rejected').addEventListener('click', function (event) {
            event.preventDefault(); // Prevent the default button behavior
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, reject applicants!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('reject-bulk-form').submit();
                }
            });
        });
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });


        function updateSkillStatus(id, status) {
            url = '{{admin_url("approved_applicants")}}'
            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    applicants_id: id,
                    skill_accuracy: status
                },
                success: function (response) {
                    if (response.success) {
                        if (typeof table_applicants !== 'undefined') {
                            table_applicants.draw(false);
                        }
                                
                        if ($('#pending-applicants').length) {
                            $('#pending-applicants').text(`Total Applicants in Pending: ${response.pending}`);
                        }
                        let btnGroup = $('#status-wrapper-' + id + ' .btn');
                        btnGroup.text(function(index, text) {
                            return index === 0 ? status : text;
                        });
                        btnGroup.removeClass('btn-success btn-danger btn-warning')
                                .addClass(response.color_class);
                    } else {
                        Swal.fire('Error!', response.message, 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error!', 'Request failed.', 'error');
                }
            });
        }

    </script>

@endsection
