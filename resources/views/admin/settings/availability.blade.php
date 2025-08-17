@extends('admin.layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
            	@if (Session::has('flash_notification'))
	                <div class="col-xl-12">
	                    @include('flash::message')
	                </div>
                @endif
                <form action="{{admin_url('/availability/availabilityAdd')}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-6">
                            <input type="text" name="name" id="" class="form-control" placeholder="Enter yur Availability Period." required>
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
                <h4 class="card-title">{{ trans('Availability') }}</h4>
                <div class="table-responsive">
                    <table class="table table-striped table-sm table-bordered datatables-availability" data-url="{{admin_url('availability-ajax')}}" style="width:100%">
                        <thead>
                            <th>#</th>
                            <th> {{ trans('admin.Name') }}</th>
                            <th> {{ trans('admin.Status') }}</th>
                            <th> {{ trans('admin.created_at') }}</th>
                            <th> {{ trans('admin.Actions') }}</th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@includeFirst([
    config('larapen.core.customizedViewPath') . 'admin.models.availability_edit_model',
    'admin.models.availability_edit_model',
])

@section('after_scripts')
<script>

    function availabilityEdit(id, name, status) {
        $('#availabilityId').val(id);
        $('#availabilityVal').val(name);
        $("#availabilitystatus").val(status).trigger('change');
        $('#edit-availability-modal').modal('show');
    }

    function update_status(id) {
        if (confirm("Are you sure you want to change Status ?")) {
            $.ajax({
                url: "<?= admin_url('/availability/update_status') ?>",
                data: "id=" + id,
                method: "post",
                success: function () {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Update Status Successfully.',
                        icon: 'success'
                    }).then(() => {
                        table_availability.draw();

                    });
                }

            })
        } else {
            return false;
        }
    }
</script>
@endsection
