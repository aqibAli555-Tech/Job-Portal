@extends('layouts.master')

@section('content')
@include('common.spacer')
<div class="main-container">
    <div class="container">
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
                @include('account/inc/sidebar')
            </div>
            <div class="col-md-9 page-content">
                <div class="inner-box company-profile-page">
                    <div class="alice-bg padding-top-60 section-padding-bottom">
                        <div class="container">
                            <div class="row">
                                <div class="col">
                                    <div class="company-details">
                                        <div class="row">
                                            <div class="col-4 col-md-3">
                                                <?php
                                                    $logo_show = \App\Helpers\Helper::get_company_logo_AWS($data['user']);
                                                ?>
                                                <img src="{{ $logo_show }}"
                                                    class="img-fluid" alt="">
                                            </div>
                                            <div class="col-8 col-md-5 p-0">
                                                <h4 class="company-heading">
                                                    <?= !empty($data['user']->name) ? $data['user']->name : "" ?>
                                                    @if(!empty($data['user']->companyData))
                                                    @if($data['user']->companyData->verified_email==1 &&
                                                    $data['user']->companyData->verified_phone==1)
                                                    <img src="{{url()->asset('storage/app/default/ico/blue_tick.png')}}"
                                                        style="width:13px" class="img-fluid" alt="">
                                                    @endif
                                                    @endif
                                                    <br>
                                                    <span class="company-type"><i
                                                            data-feather="user"></i><?= !empty($data['user']->city->name) ? $data['user']->city->name : ""; ?>,
                                                        <?= !empty($data['user']->country->name) ? $data['user']->country->name : ""; ?></span>
                                                </h4>
                                            </div>
                                            <div class="col-12 col-md-4 text-right company-edit">
                                                <div class="float-right">
                                                    <a href="{{url('companies/'.$data['user']->id.'/jobs')}}"
                                                        class="btn btn-primary btn-right float-right">
                                                        {{t('View Job Posts')}}</a>
                                                </div>
                                                @if(!empty(auth()->user()->user_type_id==1) && $data['user']->id ==
                                                auth()->user()->id)
                                                <div class="numbercounter">
                                                    <a>
                                                        <div class="circle-counter">
                                                            {{ \App\Helpers\Number::short(auth()->user()->credits - auth()->user()->remaining_credits) }}/{{auth()->user()->credits}}
                                                        </div>
                                                        <div class="circle-text">
                                                            {{t('Contact Card Remaining')}}
                                                        </div>
                                                    </a>
                                                    <a>
                                                        <div class="circle-counter">
                                                            {{ \App\Helpers\Number::short(auth()->user()->posts - auth()->user()->remaining_posts) }}/{{auth()->user()->posts}}

                                                        </div>
                                                        <div class="circle-text">
                                                            <p>{{t('Job Posts Remaining')}}</p>
                                                        </div>
                                                    </a>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        <hr>
                                        <h3>
                                            {{t('Company Description')}}
                                        </h3>
                                        <p><?= !empty($data['user']->description) ? $data['user']->description : ""; ?>
                                        </p>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p class="company-detail-p">
                                                    <?php
                                                    $entiteis = '';
                                                    $count_en = '';
                                                    if (!empty($data['user']->entities)) {
                                                        $entiteis = explode(',', $data['user']->entities);
                                                        $count_en = array_key_last($entiteis);
                                                    }
                                                    ?>
                                                    <b>{{t('Entities')}} : </b>
                                                    @foreach($entiteis as $key => $entitei)
                                                    <?= trim($entitei) ?>@if($key < $count_en),&nbsp;@endif @endforeach
                                                        </p>
                                            </div>

                                        </div>
                                        <br>
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                @if(!empty($data['all_companies'][0]))
                <div class="inner-box company-profile-page">
                    <div class="alice-bg padding-top-60 section-padding-bottom">
                        <h3>Companies</h3>
                        <div class="row">
                            @foreach($data['all_companies'] as $all_company)
                            
                            <div class="col-md-3" style="">
                                <a href="{{url("companyprofile/$all_company->id")}}"><img
                                        style="width: 90%; height: 93%!important;"
                                        src="{{ \App\Helpers\Helper::getImageOrThumbnailLink($all_company) }}" 
                                        class="img-fluid"
                                        alt="img">
                                    <center><label>{{$all_company->name}}</label></center>

                                </a>
                            </div>
                            @endforeach

                        </div>
                    </div>
                </div>
                @endif
                
                   @if(!empty($data['logoData']))
                <div class="col-lg-12 box-title no-border">
                    <div class="inner">
                        <h2>
                            <small>{{ t('Sub Companies') }}</small>
                        </h2>
                    </div>
                </div>
                <div class="inner-box">
                    <div class="row">
                        @foreach($data['logoData'] as $logo)

                        <div class="col-md-3" style="">
                              <a href="{{ url('/public').'/' .$logo->logo}}" target="_blank">
                            <img style="    width: 74%; height: 93%!important;" src="{{ url('/public').'/' .$logo->logo}}" class="img-fluid" alt="img">
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection
<!-- Modal -->


@section('after_styles')
<style>
.action-td p {
    margin-bottom: 5px;
}
</style>
@endsection


@section('after_scripts')

@endsection