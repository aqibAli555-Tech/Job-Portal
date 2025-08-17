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
                <form action="{{admin_url('/skillExperience/experienceAdd')}}" method="get">
                    <div class="row">
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="name" required="required" placeholder="Add new Experience">
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
                <h4 class="card-title">{{ trans('admin.skill_experience') }}</h4>
                <div class="table-responsive">
                    <table class="table table-striped table-sm table-bordered datatables-skill-experience" data-url="{{admin_url('skill-experience-ajax')}}" style="width:100%">
                        <thead>
                            <th>#</th>
                            <th>{{ trans('admin.name') }}</th>
                            <th>{{ trans('admin.created_at') }}</th>
                            <th>{{ trans('admin.actions') }}</th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@includeFirst([
    config('larapen.core.customizedViewPath') . 'admin.models.experience_edit_model',
    'admin.models.experience_edit_model',
])

@endsection

@section('after_scripts')

<script>
    function skillEdit(id, name) {
        $('#sillVall').val(name);
        $('#SillId').val(id);
        // $('#experience_edit_modal').modal('show');
    }

    function ExpeEdit(id, name) {
        $('#ExpeVall').val(name);
        $('#ExpeId').val(id);
        $('#experience_edit_modal').modal('show');
    }
</script>

@endsection
