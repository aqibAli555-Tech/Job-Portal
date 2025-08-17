@extends('admin.layouts.master')
@section('content')
    <style>
        .font-24 {
            font-size: 24px;
        }
    </style>
    @php
        $post_Type=request()->get('type');
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
                        <div class="col-md-4">
                            <label for="employee_skill">{{ trans('admin.Search by Skills Sets') }}</label>
                            <select class="form-control skill_set select1" id="employee_skill" name="employyeskill">
                                <option value="">{{ trans('admin.Search by Skills Sets') }}</option>
                                @if (!empty($skill_sets))
                                    @foreach ($skill_sets as $item)
                                        <option value="{{ $item->id }}">{{ $item->skill }} - ({{ $item->post_count }})
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="status">Status</label>
                            <select class="form-control select1" id="status" name="status">
                                <option value="">All</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="status">Post Type</label>
                            <select class="form-control select1" id="post_type" name="post_type">
                                <option value="">All</option>
                                <option value="current_posts" <?php if (!empty($post_Type == 'current_posts')) {
                                    echo 'selected';
                                } ?>>Current Active Post
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="search">Search</label>
                            <input type="search" id="search" class="form-control" name="search">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ trans('admin.Job Posts') }}</h4>
                    <div class="table-responsive">
                        <table class="table table-striped  table-sm table-bordered datatables-job-post"
                               data-url="{{admin_url('job-post-ajax')}}">
                            <thead>
                            <tr>
                                <th>Details</th>
                                <th></th>
                                <th>{{ trans('admin.Featured') }}</th>
                                <th>{{ trans('admin.Status') }}</th>
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
@endsection

@section('after_scripts')
    <script>
        $(function () {
            $('#checkAll').click(function () {
                if ($(this).prop('checked')) {
                    $('.checkbox').prop('checked', true);
                } else {
                    $('.checkbox').prop('checked', false);
                }
            });

            $('.checkbox , #checkAll').click(function () {
                var ids = "";
                var checkboxes = $('.checkbox');
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
                    $('#bulkDeleteBtn').show();
                    ids = checkedboxes.toString();
                } else {
                    $('#bulkDeleteBtn').hide();

                }
                $('#post_id').val(ids);

            });

        });

        function delete_selected_item(id = null) {

            var selectedIds = $('#post_id').val();
            if (confirm("Are you sure ?. You want to delete.")) {
                $.ajax({
                    url: "<?= url('admin/delete_post') ?>",
                    data: "selectedIds=" + selectedIds,
                    method: "POST",
                    success: function () {
                        table_job_post.draw();
                    }
                })
            } else {
                return false;
            }


        }

        function delete_item(id) {
            if (confirm("Are you sure. You want to delete ?")) {

                $.ajax({
                    url: "<?= url('admin/delete_item') ?>",
                    data: "id=" + id,
                    method: "get",
                    success: function () {
                        Swal.fire(
                            'Deleted!',
                            'Post has been deleted successfully.',
                            'success'
                        ).then(() => {
                            table_job_post.draw();
                        });
                    }
                })
            } else {

                return false;
            }
        }

        function add_feature(id, status) {
            Swal.fire({
                title: "Are you sure?",
                text: "Are you sure you want to change feature status?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                showLoaderOnConfirm: true,
                preConfirm: async () => {
                    try {
                        const url = '<?= url('admin/add_post_feature') ?>' + "?id=" + id + "&status=" + status;
                        return await fetch(url);
                    } catch (error) {
                        Swal.showValidationMessage(`Request failed: ${error}`);
                    }
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        timer: 2000,
                        title: 'Success!',
                        text: 'Post updated successfully.',
                        icon: 'success'
                    });
                    table_job_post.draw();
                }
            });
        }


        function update_status(id, status) {
            Swal.fire({
                title: "Are you sure?",
                text: "Are you sure you want to change status?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                showLoaderOnConfirm: true,
                preConfirm: async () => {
                    try {
                        const url = '<?= url('admin/update_status') ?>' + "?id=" + id + "&status=" + status;
                        return await fetch(url);
                    } catch (error) {
                        Swal.showValidationMessage(`Request failed: ${error}`);
                    }
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        timer: 2000,
                        title: 'Success!',
                        text: 'Status updated successfully.',
                        icon: 'success'
                    });
                    table_job_post.draw();
                }
            });
        }
    </script>
@endsection