@extends('admin.layouts.master')

@section('content')

<div class="row">
    @if (Session::has('flash_notification'))
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="col-xl-12">
                    @include('flash::message')
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ trans('Titles') }}</h4>
                <button type="button" class="btn btn-primary" onclick="addmodal()">Add title</button>
                <div class="table-responsive pt-4">
                    <table class="table table-striped table-sm table-bordered datatables-genders" data-url="{{admin_url('genders-ajax')}}" style="width:100%">
                        <thead>
                            <th>{{ trans('admin.Name') }}</th>
                            <th>{{ trans('admin.action') }}</th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@includeFirst([
    config('larapen.core.customizedViewPath') . 'admin.models.gender_model',
    'admin.models.gender_model',
])
@endsection

@section('after_scripts')
<script>
    function editmodal(id, name) {
        $('#id').val(id); 
        $('#name').val(name); 
        $('#post_title').modal('show');
        $('#post_title').modal('show');
    }

    function addmodal(){
        $('#post_title').modal('show');
    }
</script>

@endsection