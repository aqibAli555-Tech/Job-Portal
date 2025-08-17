<?php
use App\Models\Company;
use App\Models\Post;
?>
@extends('layouts.master')
<style>
    table th {
        text-align: center;
    }

    td {
        text-align: center;
    }
</style>

@section('content')
@include('common.spacer')

<div class="main-container">
    <div class="container">
        @include('account/inc/breadcrumbs')

        <div class="row">
            <div class="col-md-3 page-sidebar">
                @include('account.inc.sidebar')
            </div>

            <div class="col-md-9 page-content">
                <div class="inner-box">
                    <h2 class="title-2"> {{ t('Applied Jobs') }}
                        &nbsp &nbsp <a href="{{ url('latest-jobs?q=&l=') }}" class="btn btn-sm btn-primary">Apply To Jobs</a>
                    </h2>

                    <br>
                    <div class="table-responsive">
                        <table class="table">
                            {!! csrf_field() !!}
                            <thead>
                            <tr>
                                <th data-type="numeric"
                                    data-sort-initial="true">{{t('Image')}}
                                </th>
                                <th data-type="numeric"
                                    data-sort-initial="true">{{t('Company')}}
                                </th>
                                <th data-type="numeric"
                                    data-sort-initial="true">{{t('Post Title')}}
                                </th>
                                <!-- <th data-sort-ignore="true">Resume</th> -->
                                <th data-type="numeric">{{t('Date Applied')}}</th>
                                <th class="action">{{t('Status')}}</th>
                                <th class="action">{{t('Cv Status')}}</th>
                                <th class="action">{{t('Details')}}</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php
                                 
                            foreach ($data as $key => $item) {
                          
                               $company_name=(!empty($item->companyData->name)?$item->companyData->name:'');
                                ?>
                                <tr class="job-items ">
                                    <?php
                                  
                                    if (empty($item->post)) {
                                      $logo_show=App\Helpers\Helper::get_company_logo_AWS($item->companyData);
                                    } else {
                                        $logo_show=App\Helpers\Helper::get_post_logo($item->post);
                                    }
                                    ?>
                                    <td>
                                        <div class="user-image-div-message" style="background-image:url('{{$logo_show}}')">
                                        </div>
                                    </td>

                                    <td class="title"><a href="{{url('/companyprofile')}}/{{$item->to_user_id}}"> {{$company_name}}</a></td>
                                   


                                    @if(!empty($item->contact_unlock) && ($item->contact_unlock == 1))
                                    <td class="title">{{t('Unlocked This Contact Through CV Search Page')}}</td>
                                    @else
                                    <td class="title">{{!empty($item->post->title)?$item->post->title:''}}</td>
                                    @endif
                                    <td class="title">{{ $item->created_at->format('d-M-Y') }}</td>
                                    <td class="title">
                                        <div class="action">
                                            @if($item->status == 'rejected')
                                            <span class="badge badge-danger">
                                               {{t($item->status)}}
                                            </span>
                                            @endif
                                            @if($item->status == 'hired')
                                            <span class="badge badge-success">
                                               {{t($item->status)}}
                                            </span>
                                            @endif
                                            @if($item->status == 'interview')
                                            <span class="badge badge-dark">
                                               {{t($item->status)}}
                                            </span>
                                            @endif
                                            @if($item->status == 'applied')
                                            <span class="badge badge-warning" >
                                               {{t($item->status)}}
                                            </span>
                                            @endif
                                            @if($item->status == 'pending')
                                           <span class="badge badge-warning" style="">
                                               {{t('applied')}}
                                            {{-- {{ ucfirst('applied') }}--}} 
                                            </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>

                                        <?php $lock = App\Models\Unlock::where('user_id', $item->user_id)->where('to_user_id', $item->to_user_id)->first();
                                        if (!empty($lock)) {
                                            ?>
                                            <a href="{{url('account/cv-viewed')}}">{{t('Viewed')}}</a>
                                        <?php } else {
                                            ?>
                                            {{t('Not Viewed')}}
                                        <?php } ?>
                                    </td>
                                    <td class="title">
                                        <div class="action">
                                            @if(!empty($item->post))
                                            @if($item->post['archived']==0 && $item->post['is_deleted']==0 && $item->post['is_active']==1 )
                                            @if(!empty($item->post))
                                            <a target="_blank" href="{{ \App\Helpers\UrlGen::post($item->post) }}"
                                               class="btn btn-sm btn-primary"
                                               style="width: auto;text-align: center;">
                                                <i data-feather="eye-off"> </i>
                                                {{t('View Post')}}
                                            </a>
                                            @endif
                                            @else
                                            <button disabled
                                               class="btn btn-sm btn-danger"
                                               style="width: auto;text-align: center;">
                                                <i data-feather="eye-off"> </i>
                                                {{t('Disabled Post')}}
                                            </button>
                                            @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                            @if(empty($data))
                            <tr class="job-items">
                                <td colspan="6">
                                    <h6 class="text-muted" style="text-align: center">{{t('You have not applied for any jobs yet')}}.</h6>
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


@endsection
