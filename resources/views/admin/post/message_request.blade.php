@extends('admin.layouts.master')
@section('content')
    @includeFirst([config('larapen.core.customizedViewPath') . 'common.spacer', 'common.spacer'])
    <?php
    
    use App\Helpers\UrlGen;
    use App\Models\Company;
    use App\Models\User;
    
    $search = !empty(request('search')) ? request('search') : '';
    $status = !empty(request('status')) ? request('status') : '';
    $company = !empty(request('company')) ? request('company') : '';
    $post = !empty(request('post')) ? request('post') : '';
    $limit = !empty(request('limit')) ? request('limit') : '';
    ?>
    <style>
        .my-text {
            width: 180px;
            word-wrap: break-word;
            text-align: center;
        }

        .user-image-div {
            width: 50px;
            height: 50px;
            background: #f2f2f2;
            border-radius: 30px;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>
    <div class="row page-titles">
        <div class="col-md-6 col-12 align-self-center">
    
        </div>
    </div>
    <div class="section-content">
        <h3 class="title-1">
            <strong>{{ trans('admin.bulk_chat_requests') }}</strong>
        </h3>
        @if (Session::has('flash_notification'))
            <div class="col-xl-12">
                <div class="row">
                    <div class="col-xl-12">
                        @include('flash::message')
                    </div>
                </div>
            </div>
        @endif
        <div class="row">
            @if (isset($data['MessageRequest']))
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header ">
                            <div class="col-md-2">
                                <button style="display:none; margin-top: 15px;" id="bulkDeleteBtn" type="button"
                                    class="btn btn-danger shadow" onclick="delete_selected_item()"><i
                                        class="fas fa-times"></i>
                                    Delete
                                    Selected Items
                                </button>
                                <input type="hidden" name="post_id" id="post_id">
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="get" id="myform">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label>&nbsp;Number of rows </label>
                                        <select class="form-control select1" name="limit" onchange="submitForm()">
                                            <option value="30" <?php if ($limit == 30) {
                                                echo 'selected';
                                            } ?>>30
                                            </option>
                                            <option value="50" <?php if ($limit == 50) {
                                                echo 'selected';
                                            } ?>>50
                                            </option>
                                            <option value="100" <?php if ($limit == 100) {
                                                echo 'selected';
                                            } ?>>100
                                            </option>
                                        </select>
                                    </div>


                                    <div class="col-md-3">
                                        <label for="search">Search</label>
                                        <input type="search" id="search" value="<?= $search ?>" class="form-control"
                                            name="search">
                                    </div>
                                    <div class="col-md-3 mt-4">
                                        <button type="submit" class="btn btn-primary btn-block">Search</button>
                                    </div>

                                </div>
                            </form>
                            <br>
                            <div class="table-responsive">

                                <table class="table table-bordered table-striped">
                                    <th>Details</th>
                                    <th>{{ trans('admin.job_post_title') }}</th>
                                    <th>{{ trans('admin.Message') }}</th>
                                    <th>{{ trans('admin.number of user') }}</th>
                                    <th>{{ trans('admin.Status') }}</th>
                                    <th>{{ trans('admin.action') }}</th>
                                    </tr>

                                    @foreach ($data['MessageRequest'] as $key => $item)
                                        <tr>
                                            <td class="d-flex flex-column">
                                                <div class="user-image-div"
                                                    style="background-image:url('{{ \App\Helpers\Helper::get_company_logo_AWS($item->company) }}">
                                                </div>
                                                <span><strong class="font-weight-bolder">{{ trans('admin.company_name') }}:  </strong><small><a
                                                    href="<?= admin_url('get_employer?search=' . $item->email) ?>"><?= !empty($item->company->name) ? $item->company->name : '' ?></a></small></span>
                                            </td>
                                            <td> <a
                                                    href="<?php echo UrlGen::post($item->post); ?>"><?= !empty($item->post->title) ? $item->post->title : '' ?></a>
                                            </td>
                                            <td><?= $item->message ?></td>
                                            <td><?= $item->number_of_employee ?></td>
                                            <td>
                                                @if ($item->status == 'pending')
                                                    <button type="button" class="btn btn-sm btn-block btn-secondary"
                                                        onclick="edit_approved_model('<?= $item->id ?>','<?= $item->status ?>')">Pending</button>
                                                @elseif($item->status == 'approved')
                                                    <button type="button"
                                                        class="btn btn-success btn-sm activeBtnClass same btn-block">Approved</button>
                                                @elseif($item->status == 'rejected')
                                                    <button type="button"
                                                        class="btn btn-danger btn-sm activeBtnClass btn-block"
                                                        onclick="edit_approved_model('<?= $item->id ?>','<?= $item->status ?>')">Rejected</button>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($item->status == 'approved')
                                                    <a href="<?php echo admin_url('track_message_request/' . $item->id); ?>" class="btn btn-sm btn-primary m-1">View
                                                        Details</a>
                                                @endif
                                                @if ($item->status == 'pending')
                                                    <button type="button" class="btn btn-sm btn-block btn-primary"
                                                        onclick="approved_model('<?= $item->id ?>')">{{ trans('admin.approved') }}
                                                    </button>
                                                @elseif($item->status == 'rejected')
                                                    <button type="button" class="btn btn-sm btn-block btn-primary"
                                                        onclick="approved_model('<?= $item->id ?>')">{{ trans('admin.approved') }}
                                                    </button>
                                                @endif
                                            </td>

                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        @else
                            <strong>{{ t('data_not_found') }}</strong>
            @endif
        </div>
    </div>
    </div>
    </div>
    @includeFirst([
        config('larapen.core.customizedViewPath') . 'layouts.inc.social.horizontal',
        'layouts.inc.social.horizontal',
    ])
    </div>
    <div class="modal fade" id="approved_request" tabindex="-1" aria-labelledby="approved_request" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approved_requestLabel">{{ trans('admin.approved_request') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ URL('/') }}/admin/approved_request" method="post" accept-charset="UTF-8"
                        enctype="multipart/form-data" id="approved_request">
                        @csrf

                        <div class="mb-3">
                            <label for="status" class="col-form-label">{{ trans('admin.approved_request') }}</label>
                            <select id="status" name="status" class="form-control">
                                <option value="">{{ trans('admin.select_status') }}</option>
                                <option value="pending">{{ trans('admin.pending') }}</option>
                                <option value="approved">{{ trans('admin.approved') }}</option>
                                <option value="rejected">{{ trans('admin.rejected') }}</option>

                            </select>
                        </div>

                        <input type="hidden" id="request_id" name="request_id">
                        <div class="modal-footer">

                            <button type="submit" class="btn btn-primary">{{ trans('admin.approved') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endsection
@section('after_scripts')
<script>
    function approved_model(id) {
        $('#request_id').val(id);
        $("#status").val(status).trigger('change');
        $('#approved_request').modal('show');

    }

    function edit_approved_model(id, status) {
        $('#request_id').val(id);
        $("#status").val(status).trigger('change');
        $('#approved_request').modal('show');

    }

    function submitForm() {
        // Call submit() method on <form id='myform'>
        document.getElementById('myform').submit();
    }
</script>
@endsection
