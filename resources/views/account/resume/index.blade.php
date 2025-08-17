@extends('layouts.master')
@section('content')
@includeFirst([config('larapen.core.customizedViewPath') . 'common.spacer', 'common.spacer'])
<div class="main-container">
    <div class="container">
        @include('account/inc/breadcrumbs')
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

            <div class="col-md-3 page-sidebar">
                @includeFirst([config('larapen.core.customizedViewPath') . 'account.inc.sidebar',
                'account.inc.sidebar'])
            </div>
            <!--/.page-sidebar-->

            <div class="col-md-9 page-content">
                <div class="inner-box">
                    <h2 class="title-2"> {{ t('my_cv') }}
                        <i class="fas fa-question-circle" hidden data-toggle="tooltip" data-placement="top"
                            title="Resume to apply for jobs"></i>
                    </h2>

                    @if(@empty($resume->employee_cv))
                    <div class="row">
                        <div class="col-md-7">
                            <label class="">
                                {{ t('Begin by creating your cv, it’ll take you a couple minutes. Please keep in mind if you dont create your cv no company can search you and you cannot apply to jobs.in my cv page') }}</label>
                        </div>
                        <div class="col-md-4">
                            <a class="btn btn-primary-dark btn-sm" href="#" data-toggle="modal"
                                data-target="#employee_cv_modal">
                                <i class="fa fa-edit"></i> {{ t('Upload') }}
                            </a>
                        </div>
                    </div>
                    @endif
                    <br>
                    <div class="">
                        @if(!empty($resume->employee_cv))
                        <table id="" class="table" data-filter="#filter" data-filter-text-only="true">
                            <?php
                            ?>
                            <tr>
                                <td class="add-img-td" style="width: 49%;">
                                    <h2>{{$resume->name }}</h2>
                                    @if(!empty($new_resume))

                                        @if($new_resume->is_approved==0)
                                            <label class=""> {{ t('Your updated CV is currently undergoing a review process. Once approved, you can use your new CV for applications. Until then, your old CV will continue to be utilized for job applications.') }}</label>
                                        @elseif($new_resume->is_approved==1)
                                            <label class="" style="color: green">{{ t('Your recently updated CV has been successfully approved and is now authorized for utilization in job applications.') }}</label>
                                        @elseif($new_resume->is_approved==2)
                                            <label class="" style="color: red">{{ t('Your latest CV has been rejected. Please upload a valid one promptly, as the previous version will continue to be used for job applications.') }}</label>
                                        @endif
                                    @endif

                                </td>
                                <td><b>Last Updated:</b>
                                    @if(!empty($new_resume) && $new_resume->is_approved==1)
                                        {{ date('d M-Y', strtotime($new_resume->updated_at))}}
                                    @else
                                        {{ date('d M-Y', strtotime($resume->updated_at))}}
                                    @endif

                                </td>

                                @if(empty($new_resume) || $new_resume->is_approved==1 || $new_resume->is_approved==2 )

                                <td class="action-td">
                                    <a class="btn btn-primary-dark btn-sm" href="#" data-toggle="modal"
                                       data-target="#employee_cv_modal">
                                        <i class="fa fa-edit"></i> {{ t('reupload') }}
                                    </a>
                                </td>
                                @endif
                                <td>
                                    <a class="btn btn-primary-dark btn-sm"
                                       href="{{url('account/resumes/show_cv/'.$resume->id)}}" target="_blank">
                                        <i class="fa fa-download"></i> {{ t('Download') }}
                                    </a>
                                </td>
                                <td>
                                    <a class="btn btn-primary-dark btn-sm"
                                       href="{{url('account/resumes/show_cv/'.$resume->id.'?type=preview')}}" target="_blank">
                                        <i class="fa fa-eye"></i> {{ t('preview') }}
                                    </a>
                                </td>

                            </tr>
                        </table>
                        @endif

                    </div>


                </div>
            </div>
        </div>
    </div>
</div>

@include('pages.inc.modal')
@endsection
@section('after_scripts')
@endsection