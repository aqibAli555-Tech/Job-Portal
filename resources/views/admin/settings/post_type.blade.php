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
                <form action="{{admin_url('/post_type_post')}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-6">
                            <input type="text" name="post_type_name" id="" class="form-control" placeholder="Enter Name" required>
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
                <h4 class="card-title">{{ trans('admin.post_type') }}</h4>
                <div class="table-responsive">
                    <table class="table table-striped table-sm table-bordered datatables-post-type" data-url="{{admin_url('post-type-ajax')}}" style="width:100%">
                        <thead>
                            <th>#</th>
                            <th>{{ trans('admin.Name') }}</th>
                            <th>{{ trans('admin.Status') }}</th>
                            <!-- <th>{{ trans('admin.created_at') }}</th> -->
                            <th>{{ trans('admin.Actions') }}</th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@includeFirst([
    config('larapen.core.customizedViewPath') . 'admin.models.post_type_model',
    'admin.models.post_type_model',
])

@endsection

@section('after_scripts')

<script>
    function post_type_edit(id, name, status) {
        $('#post_type_id').val(id);
        $('#post_type_name').val(name); 
        $("#post_type_status").val(status).trigger('change');
        $('#post_type_modal').modal('show');
    }
</script>

@endsection

