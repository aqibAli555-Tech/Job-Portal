@extends('affiliate.layouts.master')

@section('content')

<div class="main-container">

    <div class="container">
        @include('affiliate/inc/breadcrumbs')
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
                @include('affiliate.inc.sidebar')
            </div>
            <!--/.page-sidebar-->
            <div class="col-md-9 page-content">
    <div class="inner-box">
        <div class="row align-items-center">
            <!-- User Image -->
            <div class="col-3 col-md-2 text-center">
                <a href="{{  \App\Helpers\Helper::getImageOrThumbnailLink($data['user_data'],true); }}" target="_blank">
                    <div class="user-image-div rounded-circle mx-auto" style="background-image:url('{{  \App\Helpers\Helper::getImageOrThumbnailLink($data['user_data']); }}'); width:70px; height:70px; background-size:cover; background-position:center;">
                    </div>
                </a>
            </div>

            <!-- Name and Location -->
            <div class="col-9 col-md-5">
                <h5 style="padding-bottom: 0px;">
                    <?= $data['user_data']['name'] ?>
                    @if ($data)
                    <img src="{{ url('public/storage/app/default/ico/blue_tick.png') }}" style="width: 12px; margin-bottom: 3px;" class="img-fluid" alt="">
                    @endif
                </h5>
                <p class="small mb-0 text-muted">
                    {{ !empty($data['user_data']->cityData->name)?$data['user_data']->cityData->name:'' }}, 
                    {{ !empty($data['user_data']->country->name)?$data['user_data']->country->name:'' }}
                    <img src="{{ url()->asset('images/flags/16/' . strtolower($data['user_data']->country_code) . '.png') }}"/>
                </p>
            </div>

            <!-- Buttons -->
            <div class="col-12 col-md-5 mt-2 mt-md-0">
                <div class="d-flex">
                    <a class="btn btn-primary btn-sm m-1" href="{{url('/affiliate/profile')}}">
                        {{t('Edit Profile')}}
                    </a>
                    <a href="javascript:void(0)" data-toggle="modal" data-target="#modal_chat_with_admin" class="btn btn-primary btn-sm m-1">
                        Questions? Start Chat With Hungry For Jobs Team
                    </a>
                </div>
            </div>
        </div>

        <hr>

        <!-- Affiliate Links -->
        <div class="row">
            <div class="col-12 col-md-12 mt-2">
                <a class="btn btn-primary btn-sm copy_affiliate_url m-1" href="javascript:void(0);" data-affiliate-url="{{ url('register') . '?user_type_id=1&referral_code=' . $data['user_data']->referral_code }}">
                    {{ t('Copy My Affiliate URL Link & Give 10% Discount To Companies That Subscribe') }}
                </a>
                <a class="btn btn-primary btn-sm copy_affiliate_url m-1" href="javascript:void(0);" data-affiliate-url="{{ url('affiliate-register') . '?user_type_id=5&referral_code=' . $data['user_data']->referral_code }}">
                    {{ t('Copy My REFERRAL Affiliate link & Earn 5% Revenue From Them Everytime') }}
                </a>
                <a href="javascript:void(0)" data-toggle="modal" data-target="#modal_company_packages" class="btn btn-primary btn-sm m-1">
                    {{ t('View What Subscription Packages Companies Can Pay') }}
                </a>
                <a href="{{ url('public/affiliate/hungry_for_jobs_company_profile.pdf') }}" class="btn btn-primary btn-sm m-1" target="_blank">
                    {{ t('View & Download Hungry For Jobs Company Profile') }}
                </a>
            </div>
            <div class="col-12 col-md-5 mt-2">
                <p class="bg-primary text-white p-2 small" style="background: #615583 !important;color: #fff;padding: 5px;text-align: left;float: right;">
                    {!! t('Simply click the above button to copy paste your affiliate URL link into social media posts') !!}
                </p>
            </div>
            <div class="col-12 col-md-5 mt-2">
                <p class="bg-primary text-white p-2 small" style="background: #615583 !important;color: #fff;padding: 5px;text-align: left;float: right;">
                    {!! t('Simply click the above button to copy paste your REFERRAL affiliate URL link to have more affiliates register on HungryForJobs') !!}
                </p>
            </div>
        </div>

        <hr>

        <!-- Email and Phone -->
        <div class="row">
            <div class="col-12 col-md-6">
                <p class="mb-1"><strong>{{ t('Email') }}:</strong></p>
                <p class="text-muted">
                    @if(!empty($data['user_data']->email))
                        {{$data['user_data']->email}}
                    @endif
                </p>
            </div>
            <div class="col-12 col-md-6">
                <p class="mb-1"><strong>{{ t('Phone') }}:</strong></p>
                <p class="text-muted">
                    @if(!empty($data['user_data']->phone))
                        {{$data['user_data']->phone}}
                    @endif
                </p>
            </div>
        </div>

    </div>
</div>

        </div>
    </div>
</div>
</div>
@include('modals.affiliate_chat_with_admin');
@include('modals.affiliate_company_packages');
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
    });
    $(document).on('click', '.copy_affiliate_url', function() {
        const affiliateUrl = $(this).data('affiliate-url');
        navigator.clipboard.writeText(affiliateUrl)
            .then(() => 
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Affiliate URL copied to clipboard!',
                        })
                )
            .catch(() => 
                Swal.fire({
                    icon: 'error',
                    html: 'Failed to copy the URL.',
                })
        );
    });
    $(document).ready(function() {
        handlePlanChange();
    });
    function handlePlanChange() {
        const selectedPlan = document.querySelector('input[name="switchPlan"]:checked').value;

        if (selectedPlan == 'Yearly') {
            $('.monthly_price').hide();
            $('.yearly_price').show();
            $('.monthly_saving').hide();
            $('.yearly_saving').show();
            $('.yearly_url').show();
            $('.monthly_url').hide();

            $('.yearly_days').show();
            $('.monthly_days').hide();
        } else {
            $('.monthly_price').show();
            $('.yearly_price').hide();
            $('.monthly_saving').show();
            $('.yearly_saving').hide();
            $('.monthly_url').show();
            $('.yearly_url').hide();

            $('.monthly_days').show();
            $('.yearly_days').hide();
        }
    }
</script>
@endsection