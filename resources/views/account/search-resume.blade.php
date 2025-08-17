@extends('layouts.master')

@section('content')
@include('common.spacer')
@include('pages.inc.compose-message')
<?php 

$limit = !empty(request('limit')) ? request('limit') : '';
$keyword = !empty(request('keyword')) ? request('keyword') : '';
$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

?>
<style>
.user-image-div {
    width: 50px;
    height: 50px;
    background: #f2f2f2;
    border-radius: 30px;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
}
.table thead th{
    width:100px !important;
}
</style>

<div class="main-container">
    <div class="container">
        @include('account/inc/breadcrumbs')

        <div class="row">
            @include('account.searchcv.sidebar')
            <div class="col-md-9 page-content">

                <div class="inner-box" style="width:102%">
                    <h2 class="title-2"><i class=""></i> {{ t('Search CV') }}
                        <i class="fas fa-question-circle" hidden title="List of all employee resume"
                            data-toggle="tooltip" data-placement="top"></i>
                    </h2>
                    @if (Session::has('flash_notification'))
                    <div class="col-xl-12">
                        <div class="row">
                            <div class="col-xl-12">
                                @include('flash::message')
                            </div>
                        </div>
                    </div>
                    @endif
                    <div style="clear:both"></div>
                    <div class="alice-bg section-padding-bottom">
                        <div class="container no-gliters">
                            <div class="row no-gliters">
                                <div class="col">
                                    <div class="dashboard-container">
                                        @if(!empty(request('cat')) || !empty(request('country')) || !empty(request('city')) || !empty(request('keyword')) || !empty(request('nationality')) || !empty(request('sort'))|| !empty(request('send')))
                                            <a class="btn btn-primary" href="{{url('search-resumes?cat=&country=&city=&keyword=&limit=&offset=0&send=&nationality=&sort=')}}">&nbsp;{{t('Reset Filters')}}</a>
                                        @endif
                                        <div class="dashboard-content-wrapper">
                                            <div class="search-row-wrapper" style="border-radius: 7px;">
                                                <div class="container">
                                                    <form class="dashboard-form" method="" action="">
                                                        <input type="hidden" value="0" name="offset" id="offset">
                                                        <input type="hidden" value="{{$actual_link}}" name="url"
                                                            id="url">

                                                        <div class="row">

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <input name="keyword" id="keyword"
                                                                        class="form-control" type="text"
                                                                        placeholder="{{t('Search by Name')}} "
                                                                        style="background: transparent !important;font-size:12px;"
                                                                        value="{{$keyword}}" id="keyword"
                                                                       >
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3 ">

                                                                <select class="form-control" name="limit" id="limit"
                                                                    onchange="submitFrpmData()">
                                                                    <option value="50"
                                                                        <?php if ($limit == 50) { echo "selected"; } ?>>
                                                                        50 Per Page</option>
                                                                    <option value="100" <?php if ($limit == 100) {
                                                                            echo "selected";
                                                                        } ?>>100 Per Page</option>
                                                                    <option value="200" <?php if ($limit == 200) {
                                                                            echo "selected";
                                                                        } ?>>200 Per Page</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <button
                                                                        class="btn btn-primary btn-block mt-3 mt-sm-0"
                                                                        type="button" onclick="submitFrpmData()">
                                                                        <i class="fa fa-search"></i>
                                                                        <strong>{{ t('Find') }}</strong>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                          
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="manage-job-container">

                                                <table id="employee_data" style="width: 100%;" class="table table-responsive text-left">
                                                    <thead>
                                                        <tr>
                                                            <th >{{ t('Image') }}</th>
                                                            <th >{{ t('name') }}</th>
                                                            <th >{{ t('City') }}</th>
                                                            <th >{{ t('Country') }}</th>
                                                            <th >{{ t('Nationality') }}</th>
                                                            <th >{{ t('Skills Sets')}}</th>
                                                            @if(!empty(auth()->user()->user_type_id) &&
                                                            auth()->user()->user_type_id== 1)
                                                            <th class="action">{{ t('Action')}}</th>
                                                            @endif
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @if(!empty($data['search_cv']))
                                                        @foreach ($data['search_cv'] as $key => $item)

                                                            @if($item->verified_email== 1 && $item->verified_phone== 1)
                                                                    <?php
                                                                    $is_unlocked = '';
                                                                    if (auth()->check() && auth()->user()->user_type_id == 1) {
                                                                        $is_unlocked = \App\Models\Unlock::get_unlocked_by_user_id($item->id);
                                                                    }
                                                                    ?>
                                                                <tr class="job-items"
                                                                    style="">
                                                                    <td class="title">

                                                                        <div class="user-image-div lazy"
                                                                             data-src="{{ \App\Helpers\Helper::getImageOrThumbnailLink($item) }}">

                                                                        </div>
                                                                    </td>
                                                                    <td class="title"><?= ucwords($item->name) ?>
                                                                        @if($is_unlocked)
                                                                            <span class="badge badge-success">CV Opened</span>
                                                                        @endif
                                                                    </td>
                                                                    @if(!empty($item->cityData->name))
                                                                        <td class="title">{{$item->cityData->name}}</td>
                                                                    @else
                                                                        <td class="title"></td>
                                                                    @endif
                                                                    @if(!empty($item->country->name))

                                                                        <td class="title">{{ $item->country->name}}</td>
                                                                    @else
                                                                        <td class="title"></td>
                                                                    @endif
                                                                    <td class="title">
                                                                        {{ (!empty($item->nationalityData))?$item->nationalityData->name:'' }}
                                                                    </td>
                                                                    @if(!empty($item->skill_set))
                                                                        <td class="title">
                                                                                <?php
                                                                                $skill_sets = $item->skill_set;
                                                                                $skill_sets = str_replace(',', ', ', $skill_sets);
                                                                                echo $skill_sets;
                                                                                ?>
                                                                        </td>
                                                                    @else
                                                                        <td class="title"></td>
                                                                        @endif
                                                                        </td>
                                                                        @if(!empty(auth()->user()->user_type_id) &&
                                                                        auth()->user()->user_type_id== 1)
                                                                            <td class="title">
                                                                                <a href="{{url('/profile/')}}/{{$item->id}}"
                                                                                   class="btn btn-primary btn-sm"
                                                                                   style="width: 115px;text-align:center">
                                                                                    {{ t('View Details')}}</a>

                                                                            </td>
                                                                        @endif
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="7">
                                                                <h6 class="text-muted" style="text-align: center">
                                                                    There is no job seeker resume yet
                                                                    yet.</h6>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    </tbody>
                                                </table>
                                                {!! $data['search_cv']->links() !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('after_scripts')
<script type="text/javascript" src="{{url()->asset('lazyload/jquery.lazy.min.js')}}"></script>
@endsection