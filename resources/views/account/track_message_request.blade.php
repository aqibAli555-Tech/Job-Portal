
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
                                                <h2><i data-feather="save"></i>{{t('track_message_request')}}
                                                    <i class="fas fa-question-circle" hidden title="{{t('Check all save Resume')}}" data-toggle="tooltip" data-placement="top"></i>
                                                </h2>
                                            </div>
                                            <div class="manage-job-container">
                                                <div class="table">
                                                    {{-- class="table-responsive" --}}
                                                <table class="table text-left table-responsive">
                                                        <thead>
                                                        <tr>
                                                            <th data-sort-ignore="true">#</th>
                                                            <th data-sort-ignore="true">{{t('Image')}}</th>
                                                            <th data-sort-ignore="true">{{t('job_seeker')}}</th>
                                                           
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php $i=1; @endphp
                                                        @foreach ($data as $key => $item)
                                                       
                                                        <?php if (!empty($item->user)) : ?>
                                                            <tr class="job-items">
                                                                
                                                                <td class="title">{{ $i }}</td>
                                                                <td class="title">
                                                                    <div class="user-image-div" style="background-image:url('{{  \App\Helpers\Helper::getImageOrThumbnailLink($item->user); }}')">
                                                                    </div>
                                                                </td>
                                                                <td class="title">{{ $item->name}}</td>
                                                               
                                                                
                                                            </tr>
                                                            @php $i++; @endphp
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
                                                    {{$data->links()}}
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