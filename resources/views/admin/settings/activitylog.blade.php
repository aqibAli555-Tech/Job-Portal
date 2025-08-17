@extends('admin.layouts.master')

@section('content')

<?php
    $search = !empty(request('search')) ? request('search') : '';
    $limit = !empty(request('limit')) ? request('limit') : '';
    $company_filter = !empty(request('company')) ? request('company') : '';
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

                <h4 class="card-title">{{ trans('admin.Activity log') }}</h4>

                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="search">{{ trans('admin.Search') }}</label>
                            <input type="search" id="search" value="<?= $search ?>" class="form-control"
                                   name="search">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="search">{{ trans('Search By Company') }}</label>
                            <select class="form-control select1" name="company" id="company">
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-bordered datatables-logs" data-url="{{admin_url('get_logs_ajax')}}" style="width:100%">
                        <thead>
                            <th>{{trans('admin.id')}}</th>
                            <th>{{trans('admin.Date')}}</th>
                            <th>{{trans('admin.Description')}}</th>
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
    $.ajax({
        type: "GET",
        url: "{{URL('/')}}/admin/get_company_filter_data",
        success: function (response) {       
            $.each(response.data.companies, (i, val) => {
                const selected = (val.id == '{{ $company_filter }}') ? 'selected' : '';
                $('#company').append(`<option value="${val.user_id}" ${selected}> ${val.name} </option>`);
            });
            
        },
        error: function (response) {
            showSwalAlert('Error!', 'Something went wrong. Please try again', 'error', 'Ok')
        }
    });
</script>
@endsection
