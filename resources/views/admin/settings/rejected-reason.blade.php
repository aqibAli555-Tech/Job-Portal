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
                <h4 class="card-title">Add New</h4>
                <form action="{{admin_url('/rejected_reasons/post')}}" method="post">
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
                <h4 class="card-title">{{ trans('admin.rejected_reasons') }}</h4>
                <div class="table-responsive">
                    <table class="table table-striped table-sm table-bordered datatables-rejected-reason" data-url="{{admin_url('rejected-reasons-ajax')}}" style="width:100%">
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

@includeFirst([
    config('larapen.core.customizedViewPath') . 'admin.models.availability_modal',
    'admin.models.availability_modal',
    ])
@endsection

@section('after_scripts')

<script>
    function RejectedReasonEdit(id, title, status) {
        document.getElementById('availabilityId').value = id;
        document.getElementById('availabilityVal').value = title;
        $("#reason_status").val(status).trigger('change');
        $('#availability-modal').modal('show');
    }
</script>

@endsection
