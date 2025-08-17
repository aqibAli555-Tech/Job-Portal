
@extends('layouts.master')
@section('content')
@includeFirst([config('larapen.core.customizedViewPath') . 'common.spacer', 'common.spacer'])
<div class="main-container">
    <div class="container">
        {{-- @include('account/inc/breadcrumbs')--}}
        <div class="row">
            @if (Session::has('flash_notification'))
            <div class="col-xl-12">
                <div class="row">
                    <div class="col-xl-12">
                        @include('flash::message')
                    </div>
                </div>
            </div>
            @endif

            <div class="col-md-4 page-sidebar">
                @includeFirst([config('larapen.core.customizedViewPath') . 'account.inc.sidebar', 'account.inc.sidebar'])
            </div>
            <!--/.page-sidebar-->

            <!-- add custom css code and also add the icon -->
            <div class="col-md-8 page-content">
                <div class="inner-box">
                    <div>
                        <div class="row">
                            <div class="col-6">
                                <h2>{{ t('Staff permissions') }} [{{ $userPermissions->name }}]</h2>
                            </div>
                        </div>
                    </div>
                    <form action="{{ url('account/staff/permissions') }}" method="POST" class="form-horizontal" role="form">
                        @csrf
                        <input type="hidden" name="id" value="{{ $userPermissions->id }}">
                        <div class="table-responsive">
                            <table id="addManageTable" class="table company-table table demo"
                                   data-filter="#filter" data-filter-text-only="true">
                                <thead>
                                <tr>
                                    <th>  Choose which pages this user can see </th>
                                    <th> Click below which pages you would like this user to have access too</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($permissions as $permission)
                                <?php $checked = ""; ?>
                                @if(in_array($permission['id'],explode(",",$userPermissions->permissions)))
                                <?php $checked = "checked"; ?>
                                @endif
                                <tr>
                                    <td > {{ $permission['name'] }}</td>
                                    <td style="text-align: center;"><input type="checkbox" name="permissions[]" value="{{$permission['id'] }}" {{$checked}}></td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" class="btn btn-primary pull-right">{{ t('Update') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('after_scripts')
<!-- include custom script for ads table [select all checkbox]  -->
<script>
    $(document).keypress(
        function (event) {
            if (event.which == '13') {
                event.preventDefault();
            }
        });
</script>
@endsection
