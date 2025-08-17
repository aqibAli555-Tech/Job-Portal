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
    <div class="col-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ trans('admin.entities') }}</h4>
                <form action="{{admin_url('/entityCauses/entityAdd')}}" method="get">
                    <div class="row">
                        <div class="col-lg-9">
                            <input type="text" name="name" required="required" placeholder="Add a new Entity" class="form-control">
                        </div>
                        <div class="col-lg-3">
                            <button type="submit" class="btn btn-primary ladda-button">{{ trans('admin.add') }}</button>
                        </div>
                    </div>
                </form>
                <div class="table-responsive pt-4">
                    <table id="crudTable" class="table table-bordered table-striped display dt-responsive nowrap dataTable dtr-inline collapsed"
                           role="grid" aria-describedby="crudTable_info">
                        <thead>
                        <tr role="row">
                            <th data-orderable="false" class="dt-checkboxes-cell dt-checkboxes-select-all sorting_disabled"
                                style="width: 10%;  padding-right: 10px;">
                                #
                            </th>

                            <th data-orderable="true" class="sorting" style="width: 40%;">
                                {{ trans('admin.name') }}
                            </th>
                            <th data-orderable="true" class="sorting" style="width: 20%">
                                {{ trans('admin.created_at') }}
                            </th>
                            <th data-orderable="true" class="sorting" style="width: 30%">
                                {{ trans('admin.actions') }}
                            </th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php
                        foreach ($data[0] as $key => $item) {
                            if ($key % 2 == 0) {
                                $class = 'even';
                            } else {
                                $class = 'odd';
                            }
                            ?>
                            <tr role="row" class="{{$class}}">
                                <td>
                                    {{$key+1}}
                                </td>
                                <td>{{$item->name}}</td>
                                <td>
                                    {{$item->created_at->format('Y-m-d') }}
                                </td>

                                <td>
                                    <div class="btn-group" role="group">
                                        <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Action
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
                                            <button class="dropdown-item" onclick="entityEdit('{{$item->id}}' , '{{$item->name}}')" title="" data-replacement="right"><i class="far fa-edit"></i> {{ trans('admin.edit') }}</button>
                                            <a href="{{admin_url('/entityCauses/entityDelete')}}/{{$item->id}}" class="dropdown-item delete-link"><i class="fa fa-trash"></i>
                                                {{ trans('admin.delete') }}
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ trans('admin.cuisines') }}</h4>
                <form action="{{admin_url('/entityCauses/causesAdd')}}" method="get">
                    <div class="row">
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="name" required="required" placeholder="Add a new Cuisine">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" name="send" class="btn btn-primary ladda-button">{{ trans('admin.add') }}</button>
                        </div>
                    </div>
                </form>
                <div class="table-responsive pt-4">
                    <table id="crudTable"
                           class="table table-bordered table-striped display dt-responsive nowrap dataTable dtr-inline collapsed"
                           role="grid" aria-describedby="crudTable_info" style="width: 100%;">
                        <thead>
                        <tr role="row">
                            <th data-orderable="false" class="dt-checkboxes-cell dt-checkboxes-select-all sorting_disabled"
                                style="width: 10%;  padding-right: 10px;">
                                #
                            </th>
                            <th data-orderable="true" class="sorting" style="width: 40%;">
                                {{ trans('admin.name') }}
                            </th>
                            <th data-orderable="true" class="sorting" style="width:20%;">
                                {{ trans('admin.created_at') }}
                            </th>
                            <th data-orderable="true" class="sorting" style="width: 30%">
                                {{ trans('admin.actions') }}
                            </th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php
                        foreach ($data[1] as $key => $item) {
                            if ($key % 2 == 0) {
                                $class = 'even';
                            } else {
                                $class = 'odd';
                            }
                            ?>
                            <tr role="row" class="{{$class}}">
                                <td>
                                    {{$key+1}}
                                </td>
                                <td>{{$item->name}}</td>
                                <td>
                                    {{$item->created_at->format('Y-m-d') }}
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Action
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
                                            <button class="dropdown-item" onclick="cuisinesEdit('{{$item->id}}','{{$item->name}}')" title="" data-replacement="right"><i class="far fa-edit"></i> {{ trans('admin.edit') }}</button>
                                            <a href="{{admin_url('/entityCauses/causesDelete')}}/{{$item->id}}" class="dropdown-item delete-link"><i class="fa fa-trash"></i>
                                                {{ trans('admin.delete') }}
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@includeFirst([
    config('larapen.core.customizedViewPath') . 'admin.models.cuisines_edit_modal',
    'admin.models.cuisines_edit_modal',
])

@includeFirst([
    config('larapen.core.customizedViewPath') . 'admin.models.entity_edit_modal',
    'admin.models.entity_edit_modal',
])

@endsection

@section('after_scripts')
<script>
    function entityEdit(id, name) {
        $('#entityId').val(id);
        $('#entityVall').val(name);
        $('#entity_edit_modal').modal('show');
    }

    function cuisinesEdit(id, name) {
        $('#CauseId').val(id);
        $('#CauseVall').val(name);
        $('#cuisines_edit_modal').modal('show');
    }
</script>
@endsection
