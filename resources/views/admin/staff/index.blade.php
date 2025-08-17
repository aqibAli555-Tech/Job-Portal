@extends('admin.layouts.master')

@section('content')
    <?php
    $search = !empty(request('search')) ? request('search') : '';
    $limit = !empty(request('limit')) ? request('limit') : '';
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
                        <div class="row">
                            <div class="col-md-2">
                                <label>&nbsp;Number of rows</label>
                                <select class="form-control select1" name="limit">
                                    <option value="30">30</option>
                                    <option value="50">50</option>
                                    <option value="100">100
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="search">{{ trans('admin.Search') }}</label>
                                <input type="search" id="search" value="<?= $search ?>" class="form-control"
                                       name="search">
                            </div>
                        </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Staff</h4>
                    <div class="table-responsive">
                        <table class="table table-striped table-sm table-bordered no-wrap datatables-staff"
                               data-url="{{admin_url('staff-ajax')}}" style="width:100%">
                            <thead>
                            <tr role="row">
                                <td> {{ trans('admin.name') }}</td>
                                <td> {{ trans('admin.email') }}</td>
                                <td> {{ trans('admin.phone') }}</td>
                                <td> Company email</td>
                                <td> Company name</td>
                                <td> {{ trans('admin.created_at') }}</td>
                                <td> {{ trans('admin.action') }}</td>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.models.password_reset_modal')

@endsection
<script>
    function reset_pass(user_id) {
        $('#user_id').val(user_id);
        $('#resetModal').modal('show');
    }
</script>
@section('after_scripts')
@endsection