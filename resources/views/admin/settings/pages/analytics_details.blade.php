@extends('admin.layouts.master')

@section('content')
<?php 
   $limit = !empty(request('limit')) ? request('limit') : '';
   $type = !empty(request('type')) ? request('type') : '';
   $url = admin_url('analytics-detail-ajax?type=').$type;
?>
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
                <form method="get" id='myform'>
                    <div class="row">
                        <div class="col-md-4">
                            <label>&nbsp;Number of rows</label>
                            <select class="form-control select1" name="limit" onchange="submitForm()">
                                <option value="100" <?php if ($limit == 100) {
                                                        echo 'selected';
                                                    } ?>>100
                                </option>
                                <option value="250" <?php if ($limit == 250) {
                                                        echo 'selected';
                                                    } ?>>250
                                </option>
                                <option value="500" <?php if ($limit == 500) {
                                                        echo 'selected';
                                                    } ?>>500
                                </option>

                            </select>
                            <input type="hidden" name="type" value="{{$page_title}}" >
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ t($page_title) }}</h4>
                <div class="table-responsive">
                    <table class="table table-striped table-sm table-bordered datatables-analytics-detail-ajax" data-url="{{ $url }}" style="width:100%">
                        <thead>
                                <th>#</th>
                                <th>{{ trans('admin.date') }}</th>
                                <th>{{ trans('admin.Visitors') }}</th>
                                @if($page_title == 'search_cv' || $page_title == 'search_jobs' || $page_title == 'applied_users' || $page_title == 'post_details')
                                    <th> {{ trans('admin.queryParameter') }}</th>
                                @endif
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('after_scripts')
<script>
    function submitForm() {
        document.getElementById('myform').submit();
    }
</script>
@endsection
