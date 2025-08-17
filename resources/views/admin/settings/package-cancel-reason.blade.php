@extends('admin.layouts.master')

@section('content')
<style>
    .select2-container--open{
            z-index: 9999 !important;
        }
</style>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
            	@if (Session::has('flash_notification'))
	                <div class="col-xl-12">
	                    @include('flash::message')
	                </div>
                @endif
                <h4 class="card-title">Add new</h4>
                <form action="{{admin_url('/package_cancel_reasons/post')}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-6">
                            <input type="text" name="title" id="" class="form-control" placeholder="Enter your Title" required>
                        </div>
                        <div class="form-group col-md-3">
                            <select name="status" id="status" class="form-control select1" required>
                                <option value="">{{trans('admin.Select')}}</option>
                                <option value="1">{{trans('admin.active')}}</option>
                                <option value="0">{{trans('admin.Inactive')}}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" name="send" class="btn btn-primary ladda-button">{{ trans('admin.add') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ trans('admin.package_cancel_reasons') }}</h4>
                <div class="table-responsive">
                    <table class="table table-striped table-sm table-bordered datatables-package-cancel-reasons" data-url="{{admin_url('package-cancel-reasons-ajax')}}" style="width:100%">
                        <thead>
                            <td>#</td>
                            <td> {{ trans('admin.Title') }}</td>
                            <td> {{ trans('admin.Status') }}</td>
                            <td> {{ trans('admin.created_at') }}</td>
                            <td> {{ trans('admin.Actions') }}</td>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@includeFirst([config('larapen.core.customizedViewPath') . 'admin.models.package_cancel_reason','admin.models.package_cancel_reason',])
@endsection

@section('after_scripts')

<script>
    function RejectedReasonEdit(id, title, status) {
        document.getElementById('availabilityId').value = id;
        document.getElementById('availabilityVal').value = title;
        $("#reason_status").val(status).trigger('change');
        $('#packagecancelmodal').model('show');
    }
</script>

<script>
    function CancelReasonEdit(id, title, status) {
        document.getElementById('availabilityId').value = id;
        document.getElementById('availabilityVal').value = title;
        // document.getElementById('reason_status').value = status;
        $('#reason_status').val(status).trigger('change');
        $('#packagecancelmodal').modal('show');
    }

    document.addEventListener('DOMContentLoaded', function () {
        var deleteLinks = document.querySelectorAll('.delete-link');

        deleteLinks.forEach(function (link) {
            link.addEventListener('click', function (event) {
                event.preventDefault();
                var url = this.getAttribute('href');

                Swal.fire({
                    title: "Confirmation",
                    text: "Are you sure you want to delete",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "Confirm",
                    cancelButtonText: "Cancel"
                }).then((result) => {
                    if (result.isConfirmed) {
                        table_package_cancel_reasons.draw();
                    }
                });
            });
        });
    });
</script>

@endsection