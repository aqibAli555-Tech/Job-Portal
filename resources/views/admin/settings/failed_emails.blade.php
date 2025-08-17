@extends('admin.layouts.master')

@section('content')
<?php
    $limit = !empty(request('limit')) ? request('limit') : '';
?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ trans('admin.email stats') }}</h4>
                <table class="table table-striped table-sm table-bordered datatables-mailgun-ajax" data-url="{{admin_url('mailgun-ajax')}}" style="width:100%">
                    <thead>
                            <th>#</th>
                            <th>{{ trans('admin.email') }}</th>
                            <th>{{ 'Reason' }}</th>
                            <th>{{ trans('admin.date') }}</th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('after_scripts')
@endsection
