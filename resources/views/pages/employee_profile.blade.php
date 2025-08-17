@extends('layouts.master')

@section('content')
    <style>
        @media (max-width: 768px) {
            .profile_button {
                margin-top: 30px !important;
            }
        }

    </style>

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
        </div>
        <div class="row">
            <div class="col-md-3 page-sidebar">
                @include('account.inc.sidebar')
            </div>
            <!--/.page-sidebar-->
            <div class="col-md-9 page-content">
                <div class="inner-box">
                    <div class="row">
                        <div class="col-2 col-md-2" style="margin-left: 15px;">
                           
                            <a href="{{  \App\Helpers\Helper::getImageOrThumbnailLink($data['user_data'], true); }}" target="_blank">
                                <div class="user-image-div" data-lazysrc="{{  \App\Helpers\Helper::getImageOrThumbnailLink($data['user_data'], true); }}" style="background-image:url('{{  \App\Helpers\Helper::getImageOrThumbnailLink($data['user_data']); }}')">
                                </div>
                            </a>
                        </div>
                        <div class="job-seeker-profile">
                            <div class="row">
                                <h3><?= $data['user_data']['name'] ?>


                                    @if ($data)
                                    <img src="{{ url('public/storage/app/default/ico/blue_tick.png') }}" style="width: 12px; margin-bottom: 3px;" class="img-fluid" alt="">
                                    @endif

                                </h3>
                            </div>

                            <p class="job-seeker-profile-location">
                                {{ !empty($data['user_data']->cityData->name)?$data['user_data']->cityData->name:'' }},&nbsp;{{ !empty($data['user_data']->country->name)?$data['user_data']->country->name:'' }} <img src="{{ url()->asset('images/flags/16/' . strtolower($data['user_data']->country_code) . '.png') }}"/>
                            </p>
                            <div class="job-seeker-profile-actions profile_button">
                                <a class="btn btn-primary btn-sm" href="{{url('/account/profile')}}">
                                    {{t('Edit Profile')}}
                                </a>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            @auth
                            @if(auth()->user()->user_type_id == 2 )
                                    <p
                                            style="background: #615583;background: #615583;color: #fff;padding:10px;margin-top: 20px; margin-bottom: 10px;">
                                        {{t('msg-employer-profile-1')}}
                                        <br>
                                        {{t('msg-employer-profile-2')}}
                                        <br>
                                        {{t('msg-employer-profile-3')}}
                                    </p>
                            @endif
                            @endauth
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-md-6">

                            <p class="m-0"><span class="job-seeker-profile-heading">{{t('Skills sets')}}: </span>
                            </p>
                            <p class="m-0">
                                <?php
                                $skill_sets = $data['user_data']->skill_set;
                                $skill_sets = str_replace(',', ', ', $skill_sets);
                                echo $skill_sets;
                                ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="m-0"><span class="job-seeker-profile-heading">{{t('Availability')}}: </span>
                            </p>
                            <p class="m-0">

                                <?= $data['user_data']->availabilityData->name ?>
                            </p>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <p class="m-0"><span class="job-seeker-profile-heading">{{t('Experience')}}: </span>
                            </p>
                            <p class="m-0">
                                @if(!empty($data['user_data']->experiences))
                                {{$data['user_data']->experiences}}
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="m-0"><span class="job-seeker-profile-heading">{{t('Nationality')}}: </span>
                            </p>
                            <p class="m-0">

                                @if (!empty($data['user_data']->nationalityData))
                                {{$data['user_data']->nationalityData->name}}
                                @endif
                            </p>
                        </div>
                        
                        
                    </div>
                      <div class="row mt-3">
                            <div class="col-md-6">
                                @if (!empty($data['user_data']->visa))
                                    <p class="m-0"><span class="job-seeker-profile-heading">{{ t('Work Visa') }}:
                                        </span>
                                    </p>
                                    <p class="m-0">
                                        {{ $data['user_data']->visa }}


                                    </p>
                                @endif
                            </div>
                                @if (!empty($data['user_data']->country_work_visa) && $data['user_data']->country_work_visa == 'KW')

                            <div class="col-md-6 ">
                                    <p class="m-0"><span class="job-seeker-profile-heading">{{ t('Work Visa Type') }}:
                                        </span>
                                    </p>
                                    <p class="m-0">
                                        @if (!empty($data['user_data']->visa_number) && $data['user_data']->visa == 'Yes, I HAVE a visa')
                                            {{ $data['user_data']->visa_number }}
                                        @endif

                                    </p>
                               
                            </div>
                             @endif
                            <div class="col-md-6  mt-3">
                                @if (!empty($data['user_data']->countryVisa->name) && $data['user_data']->visa == 'Yes, I HAVE a visa')
                                    <p class="m-0"><span
                                            class="job-seeker-profile-heading">{{ t('Country of Work Visa') }}: </span>
                                    </p>
                                    <p class="m-0">
                                        {{ $data['user_data']->countryVisa->name }}

                                    </p>
                                @endif
                            </div>



                        </div>

                </div>
            </div>
        </div>
    </div>
</div>
</div>
 @include('modals.user_setting_modal');
@endsection
@section('after_scripts')
<script>
      $(document).ready(function() {
        var $userImageDiv = $('.user-image-div');
        var bg = $userImageDiv.attr('data-lazysrc');
        
        setTimeout(function() {
            // Check if the actual image is loaded
            var img = new Image();
            img.onload = function() {
                $userImageDiv.css('background-image', 'url("' + bg + '")');
            };
            img.onerror = function() {
                console.log('Image failed to load');
            };
            img.src = bg;
        }, 5000); // 5000 milliseconds = 5 seconds

        $.ajax({
            url: "{{ url('account/user_setting_ajax') }}",
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if(response.success){
                    const $select = $('.skill_set_entities');
                    $select.empty();

                    $.each(response.skills, function(index, item) {
                        $select.append('<option value="' + item.skill + '">' + item.skill + '</option>');
                    });

                    $('#user_setting_modal').modal('show');
                }
            },
            error: function(error) {
                console.error('An error occurred:', error);
            }
        });
    });
    $(".skill_set_entities").select2({
        width: "100%"
    });

    $(".global-select").select2({
        width: "100%",
    });
</script>
@endsection