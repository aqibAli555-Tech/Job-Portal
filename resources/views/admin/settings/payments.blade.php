@extends('admin.layouts.master')

@section('content')

<?php
    $limit = !empty(request('limit')) ? request('limit') : '';
?>

<div class="row">
    @if (Session::has('flash_notification'))
        <div class="col-xl-12">
            @include('flash::message')
        </div>
    @endif
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ ('Payments') }}</h4>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="search">{{ trans('admin.Search') }}</label>
                            <input type="search" id="search" value="" class="form-control"
                                   name="search">
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-sm table-bordered datatables-payments" data-url="{{admin_url('payments-ajax')}}" style="width:100%">
                        <thead>
                            <th>#</th>
                            <th>{{ trans('admin.Date') }}</th>
                            <th>{{ trans('admin.Package') }}</th>
                            <th>{{ trans('admin.user_name') }}</th>
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