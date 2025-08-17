@extends('admin.layouts.master')
@section('content')
@includeFirst([config('larapen.core.customizedViewPath') . 'common.spacer', 'common.spacer'])
<?php

use App\Helpers\UrlGen;
use App\Models\Company;
use App\Models\User;
?>
<style>
    .my-text {
        width: 180px;
        word-wrap: break-word;
        text-align: center;
    }
</style>
<div class="section-content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header ">
                    <h3 class="title-1">
                        <strong>{{ trans('admin.track_message_request') }}</strong>
                    </h3>
                </div>
                <div class="card-body">
                    <br>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>Job Seeker</th>
                            </tr>
                            <?php $i = 1; ?>
                            @if (!empty($data['track_request_data']))
                            @foreach ($data['track_request_data'] as $key => $item)
                            <tr>
                           
                                <td><?= $i ?></td>
                                <td class="title">
                                <img alt="<?= $item->name ?>" width='50' height='50' src="{{  \App\Helpers\Helper::getImageOrThumbnailLink($item->user); }}" /></td>
                                <td><a href="<?= admin_url('job-seekers?search=' . $item->name) ?>"><?= $item->name ?></a>
                                </td>
                            </tr>
                            <?php $i++; ?>
                            @endforeach
                            @else
                            <tr>
                                <strong>{{ t('data_not_found') }}</strong>
                            </tr>
                            @endif
                        </table>
                        {!! $data['track_request_data']->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection