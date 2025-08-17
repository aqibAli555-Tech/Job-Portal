<?php
use App\Models\User;
use App\Models\City;
use App\Models\Country;
?>
@extends('layouts.master')

@section('content')
@include('common.spacer')
<div class="main-container">
    <div class="container">
        @include('account/inc/breadcrumbs')
       
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
                                                <h2><i data-feather="save"></i>{{t('Saved CV')}}
                                                    <i class="fas fa-question-circle" hidden title="{{t('Check all save Resume')}}" data-toggle="tooltip" data-placement="top"></i>
                                                </h2>
                                            </div>
                                            <div class="manage-job-container">
                                                <div class="table">
                                                    {{-- class="table-responsive" --}}
                                                <table class="table">
                                                        <thead>
                                                        <tr>
                                                            <th data-sort-ignore="true">{{t('Image')}}</th>
                                                            <th data-sort-ignore="true">{{t('Name')}}</th>
                                                            <th data-sort-ignore="true">{{t('skill Set')}}</th>
                                                            <th data-type="numeric">{{t('Date Saved')}}</th>
                                                            <th data-sort-ignore="true">{{t('City')}}</th>
                                                            <th data-sort-ignore="true">{{t('Country')}}</th>
                                                            <th class="action">{{t('Action')}}</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach ($data as $key => $item)
                                                       
                                                        <?php if (!empty($item->user)) : ?>
                                                            <tr class="job-items">
                                                                <td class="title">

                                                                    <div class="user-image-div-message"
                                                                            style="background-image:url('{{  \App\Helpers\Helper::getImageOrThumbnailLink($item->user); }}')">
                                                                        </div>
                                                                </td>
                                                                <td class="title"><a href="{{ url('profile/'.$item->user->id) }}"  class="text-capitalize"> {{$item->user->name}} </a></td>
                                                                <td class="skill_sets" style=" overflow-wrap: anywhere;">

                                                                <?php
                                                                        $skill_sets=$item->user->skill_set;
                                                                        $skill_sets=str_replace(',',', ',$skill_sets);
                                                                        echo $skill_sets;
                                                                        ?>
                                                                </td>
                                                                <td class="title">{{ date_format($item->created_at,"Y-m-d") }}</td>
                                                                <td class="title">
                                                                    <?php if(!empty($item->user->cityData->name)){?>
                                                                    {{$item->user->cityData->name}}
                                                                    <?php }?>
                                                                </td>
                                                                <td class="title">
                                                                    <?php if(!empty($item->user->country->name)){?>
                                                                        {{$item->user->country->name}}
                                                                        <?php }?>
                                                                    </td>
                                                                
                                                                <td class="title">
                                                                    <div class="action row">
                                                                        @if($item->UnlockContact->is_unlock==1)
                                                                            <a title="Download the resume"
                                                                               href="{{url('account/resumes/show_cv/'.$item->user->id)}}"
                                                                               target="_blank"
                                                                               class="btn btn-sm btn-primary m-1">{{t('Download')}}</a>
                                                                        @endif

                                                                        <a href="{{ url('profile/'.$item->applicant_id) }}" class="btn btn-sm btn-primary view_button m-1">{{t('View Profile')}}
                                                                           
                                                                        </a>
                                                                        <a href="{{url('account/profile/'.$item->applicant_id . '/remove')}}" class="btn btn-sm btn-danger view_button m-1">{{t('Remove')}}</a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                        @endforeach

                                                        @if(count($data) <= 0)
                                                        <tr class="job-items">
                                                            <td colspan="5">
                                                                <h6 class="text-muted" style="text-align: center">{{t('You haven’t saved any CV’s yet. Unlock any employees Contact Card from their profile and save any CV you like')}}</h6>
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