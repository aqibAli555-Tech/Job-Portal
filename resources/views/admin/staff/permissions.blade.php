@extends('admin.layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Permissions</h4>
                <form action="{{ admin_url('staff/updatePermissions') }}" method="POST" class="form-horizontal" role="form">
                    @csrf
                    <div class="card border-top border-primary">
                        <div class="card-body">
                            <input type="hidden" name="id" value="{{ $userPermissions->id }}">
                            <div class="table-responsive">
                                <table id="addManageTable" class="table company-table table demo" data-filter="#filter" data-filter-text-only="true">
                                    <thead>
                                        <tr>
                                            <th> {{ t('Permission name') }}</th>
                                            <th> {{ t('Option') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($permissions as $permission)
                                        <?php $checked = ""; ?>
                                        @if(in_array($permission['id'],explode(",",$userPermissions->permissions)))
                                        <?php $checked = "checked"; ?>
                                        @endif
                                        <tr>
                                            <td> {{ $permission['name'] }}</td>
                                            <td><input type="checkbox" name="permissions[]" value="{{$permission['id'] }}" {{$checked}}></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <button type="submit" class="btn btn-primary pull-right">{{ t('Update') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('after_scripts')
@endsection
