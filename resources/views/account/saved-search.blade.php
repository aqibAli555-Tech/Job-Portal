@extends('layouts.master')

@section('content')
@include('common.spacer')
<div class="main-container">
    <div class="container">
        @include('account/inc/breadcrumbs')
        <style>
            .table td, .table th {
                white-space: nowrap;
            }
        </style>
        <div class="row">

            <div class="col-md-3 page-sidebar">
                @include('account.inc.sidebar')
            </div>
            <!--/.page-sidebar-->
            <div class="col-md-9 page-content">
                <div class="inner-box">
                    <div class="alice-bg section-padding-bottom">
                        <div class="container no-gliters">
                            <div class="row no-gliters">
                                <div class="col">
                                    <div class="dashboard-container">
                                        <div class="dashboard-content-wrapper">
                                            @include('flash::message')

                                            <!--....................... Below We are adding the icons........................ -->
                                            <div class="dashboard-section basic-info-input">
                                                <h2><i data-feather="save"></i>{{t('Saved Resume')}}
                                                    <i class="fas fa-question-circle" hidden title="{{t('Check all save Resume')}}" data-toggle="tooltip" data-placement="top"></i>
                                                </h2>
                                            </div>
                                            <div class="manage-job-container">
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                        <tr>
                                                            <th data-sort-ignore="true">{{t('Image')}}</th>
                                                            <th data-sort-ignore="true">{{t('Name')}}</th>
                                                            <th data-type="numeric">{{t('Date Saved')}}</th>
                                                            <th class="action">{{t('Action')}}</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach ($data as $key => $item)

                                                        <?php use App\Models\User;

                                                        $user = User::where('id', $item->applicant_id)->first(); ?>

                                                        @if(!empty($user->employee_cv))

                                                        <tr class="job-items">
                                                            <td class="title">
                                                                <img style="width: 40px;" src="{{  \App\Helpers\Helper::getImageOrThumbnailLink($user,true); }}" alt="not show">
                                                            </td>
                                                            <td class="title">{{$user->name}}</td>
                                                            <td class="title">{{ date_format($item->created_at,"Y-m-d") }}</td>
                                                            <td class="title">
                                                                <div class="action">
                                                                    <a title="Download the resume" href="{{url()->asset('storage/'.$user->employee_cv)}}" target="_blank" class="btn btn-sm btn-primary">{{t('Download')}}
                                                                       
                                                                    </a>

                                                                    <a href="{{ url('profile/'.$item->user_id) }}" class="btn btn-sm btn-primary view_button">{{t('View')}}
                                                                     
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        @endif
                                                        @endforeach


                                                        @if(count($data) <= 0)
                                                        <tr class="job-items">
                                                            <td colspan="5">
                                                                <h6 class="text-muted" style="text-align: center">{{t('You have not saved any resumes yet')}}</h6>
                                                            </td>
                                                        </tr>
                                                        @endif
                                                        </tbody>
                                                    </table>
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
</div>
@endsection