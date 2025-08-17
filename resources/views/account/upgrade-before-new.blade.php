@extends('layouts.master')

@section('content')
    <style>

        .action-td p {
            margin-bottom: 5px;
        }

        :root {
            --switches-bg-color: #615583;
            --switches-label-color: white;
            --switch-bg-color: white;
            --switch-text-color: black;
        }

        .switches-container {
            width: 16rem;
            position: relative;
            display: flex;
            padding: 0;
            position: relative;
            background: var(--switches-bg-color);
            line-height: 3rem;
            border-radius: 3rem;
            margin-left: auto;
            margin-right: auto;
        }

        .switches-container input {
            visibility: hidden;
            position: absolute;
            top: 0;
        }

        .switches-container label {
            width: 50%;
            padding: 0;
            margin: 0;
            text-align: center;
            cursor: pointer;
            color: var(--switches-label-color);
        }

        .switch-wrapper {
            position: absolute;
            top: 0;
            bottom: 0;
            width: 50%;
            padding: 0.15rem;
            z-index: 3;
            transition: transform .5s cubic-bezier(.77, 0, .175, 1);
            /* transition: transform 1s; */
        }

        /* switch box highlighter */
        .switch {
            border-radius: 3rem;
            background: var(--switch-bg-color);
            height: 100%;
        }

        /* switch box labels
            - default setup
            - toggle afterwards based on radio:checked status
        */
        .switch div {
            width: 100%;
            text-align: center;
            opacity: 0;
            display: block;
            color: var(--switch-text-color);
            transition: opacity .2s cubic-bezier(.77, 0, .175, 1) .125s;
            will-change: opacity;
            position: absolute;
            top: 0;
            left: 0;
        }

        /* slide the switch box from right to left */
        .switches-container input:nth-of-type(1):checked ~ .switch-wrapper {
            transform: translateX(0%);
        }

        /* slide the switch box from left to right */
        .switches-container input:nth-of-type(2):checked ~ .switch-wrapper {
            transform: translateX(100%);
        }

        /* toggle the switch box labels - first checkbox:checked - show first switch div */
        .switches-container input:nth-of-type(1):checked ~ .switch-wrapper .switch div:nth-of-type(1) {
            opacity: 1;
        }

        /* toggle the switch box labels - second checkbox:checked - show second switch div */
        .switches-container input:nth-of-type(2):checked ~ .switch-wrapper .switch div:nth-of-type(2) {
            opacity: 1;
        }
        .price_usd{
            padding-top: 10px;
            padding-bottom: 10px !important;
        }
    </style>
    @include('common.spacer')
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
                    @include('account/inc/sidebar')
                </div>
                <!--/.page-sidebar-->
                <div class="col-md-9 page-content">
                    <div class="jumbotron card jumbotron-card">
                        <div class="container">
                            <p class="upgrade-heading"><b>Access All Subscription Features</b></p>
                            <p style="text-align: left;">
                                •  &nbsp<b> 0% agency fees — hire directly with no additional charges when job seekers are recruited through our platform.</b></br>
                                •  &nbsp Unlimited job postings are included with all monthly and yearly packages.</br>
                                •  &nbsp CVs can be viewed for free, excluding contact information. To access a job seeker's full CV with contact details, you’ll need to use one Contact Card credit, available through our subscription packages.</br>
                                •  &nbsp We offer both local and international hiring options, allowing companies to specify whether job postings are open to local job seekers, international job seekers, or both.</br>
                                •  &nbsp Once a job seeker applies to your job post, our team rates them as Accurate, Very Accurate, or Not Accurate—so you can make informed hiring decisions.</br>
                                •  &nbsp View the last login date of each job seeker on their profile.</br>
                                •  &nbsp Option to hide your company name and logo on job postings.</br>
                                •  &nbsp Ability to send bulk messages and chat with multiple job seekers simultaneously.</br>
                                •  &nbsp Add multiple staff members to manage your dashboard and job postings with enhanced security features.</br>
                                •  &nbsp Manage multiple companies across different countries using a single account with one username and password.
                            </p>
                        </div>
                    </div>


                    <?php
                    $col = 'col-md-3';
                    ?>


                    <div class="w-md-75 w-xl-50 mx-auto">
                        <div class="text-center space-bottom-1">
                            <h2 class="h3 text-dark">Choose a plan that's right for you.</h2>
                        </div>
                    </div>
                    <div class="switches-container">
                        <input type="radio" id="switchMonthly" name="switchPlan" value="Monthly" checked="checked"
                               onchange="handlePlanChange()"/>
                        <input type="radio" id="switchYearly" name="switchPlan" value="Yearly"
                               onchange="handlePlanChange()"/>

                        <label for="switchMonthly">Monthly</label>
                        <label for="switchYearly">Yearly</label>
                        <div class="switch-wrapper">
                            <div class="switch">
                                <div>Monthly</div>
                                <div>Yearly</div>
                            </div>
                        </div>

                    </div>
                    <br>
                    <div class="w-md-75 w-xl-50 mx-auto">
                        <div class="text-center space-bottom-1">
                    <strong style="color:red">Choose a yearly plan and enjoy 1.5 months of savings — efficient, cost-effective hiring starts here.</strong>
                        </div>
                    </div>

                    <br>
                    <div class="white-bg">
                        <div class="row justify-content-center">
                            <?php

                            use App\Helpers\Helper;
                            use App\Models\CompanyPackages;

                            $today = date('Y-m-d');
                            foreach ($data as $key => $item) {
                                if (auth()->user()->country_code == 'KW' && (float)$item->price == 0.0) {
                                    continue;
                                }

                                $colorPicked = "";
                            if ($item->active == 1) {

                                ?>
                            <div class="col-xs-12 col-sm-6 {{$col}} ">
                                <ul class="pricing {{ $colorPicked }}">
                                    <li>
                                        <b>{{ $item->name }}</b>
                                    </li>
                                    <li style="text-align: center" class="">
                                        @foreach (explode(';', $item->description) as $info)
                                                <?php
                                                $explodeInfo = explode('.', $info);
                                                ?>
                                            @if (!empty($explodeInfo[0]))
                                                    <?php $htmldecode = html_entity_decode($explodeInfo[0], ENT_COMPAT, 'UTF-8'); ?>
                                                {!! $htmldecode !!}
                                            @endif
                                            @if (!empty($explodeInfo[1]))
                                                {{ $explodeInfo[1] }}
                                            @endif
                                        @endforeach
                                        @if ((float) $item->price == 4500.0)
                                            <span>Short on time? No problem!</span>
                                            <br><span>HungryForJobs fully manages your dashboard — from unlimited job postings and filtering job seeker CVs to delivering the most relevant profiles directly to your email or WhatsApp. This service is an excellent choice for employers with multiple open vacancies looking to save time and streamline their hiring process.</span>
                                    @endif

                                    <li>
                                        @if ((float) $item->price == 0.0)
                                            <h3 class="price_usd mt-3">
                                                {{ t('FREE') }}
                                            </h3>
                                        @else
                                            <?php
                                                $affiliate_setting = App\Models\AffiliateSetting::first();
                                            ?>
                                            @if(auth()->user()->affiliate_id != 0 && $affiliate_setting)
                                                <h3 class="price_usd monthly_price">
                                                    <s>{{ $item->currency_code }} ${{ number_format($item->price, 0) }}</s>
                                                </h3>
                                                <h3 style="display:none;" class="price_usd yearly_price">
                                                    <s>{{ $item->currency_code }} ${{ number_format($item->yearly_price, 0) }}</s>
                                                </h3>
                                                <?php
                                                    $discount_value = $affiliate_setting->package_discount_value;
                                                    $discount_type = $affiliate_setting->package_discount_type;
                                                    if($discount_type === 'fixed'){
                                                        $price_package_monthly = $item->price - $discount_value;
                                                        $price_package_yearly = $item->yearly_price - $discount_value;
                                                        $referral_discount = 'You Save $' . $discount_value . ' Referral Discount';
                                                        $discount_14 = 'You Save 14.5 % and an additional $' . $discount_value . ' Referral Discount';
                                                        $discount_30 = 'You Save 30 % and an additional $' . $discount_value . ' Referral Discount';
                                                    }else{
                                                        $price_package_monthly = $item->price - ($item->price * $discount_value / 100);
                                                        $price_package_yearly = $item->yearly_price - ($item->yearly_price * $discount_value / 100);
                                                        $referral_discount = 'You Save ' . $discount_value . ' % Referral Discount';
                                                        $discount_14 = 'You Save 14.5 % and an additional ' . $discount_value . ' % Referral Discount';
                                                        $discount_30 = 'You Save 30 % and an additional ' . $discount_value . ' % Referral Discount';
                                                    }
                                                ?>
                                                @if((float) $item->price == 425.0)
                                                    <span class="monthly_saving" style="color:red">{{ $discount_14 }}</span>
                                                    <span style="color:red; display:none;" class="yearly_price">{{ $referral_discount }}</span>
                                                @elseif((float) $item->price == 980.0)
                                                    <span class="monthly_saving" style="color:red">{{ $discount_30 }}</span>
                                                    <span style="color:red; display:none;" class="yearly_price">{{ $referral_discount }}</span>
                                                @else
                                                    <span style="color:red">{{ $referral_discount }}</span>
                                                @endif
                                                <h3 class="price_usd monthly_price">
                                                    {{ $item->currency_code }} ${{ number_format($price_package_monthly, 0) }}
                                                </h3>
                                                <h3 style="display:none;" class="price_usd yearly_price">
                                                    {{ $item->currency_code }} ${{ number_format($price_package_yearly, 0) }}
                                                </h3>
                                            @else
                                                <h3 class="price_usd monthly_price">
                                                    {{ $item->currency_code }} ${{ number_format($item->price, 0) }}
                                                </h3>
                                                <h3 style="display:none;" class="price_usd yearly_price">
                                                    {{ $item->currency_code }} ${{ number_format($item->yearly_price, 0) }}
                                                </h3>
                                            @endif
                                        @endif
                                    </li>

                                    <li>
                                        @if ((float) $item->price == 0.0)
                                            <a style="{{ $colorPicked }}" class="btn btn-primary my-1"
                                               href="{{ url('/account/paymentFree/?id=' . $item->id) }}">
                                                {{ t('Buy Now') }}</a>
                                        @else

                                            @php
                                                $subscrived_package = \App\Models\CompanyPackages::get_all_subscibed_packages(auth()->user()->id, $item->id);
                                                $yearly_remaning_days=0;
                                                $monthly_remaning_days=0;
                                                 if (!empty($subscrived_package)) {
                                                     if ($subscrived_package->package_type == 'yearly') {
                                                         $yearly_remaning_days = App\Helpers\Helper::calculate_remaining_days($subscrived_package->yearly_package_expire_date);
                                                     } else {
                                                         $monthly_remaning_days = App\Helpers\Helper::calculate_remaining_days($subscrived_package->package_expire_date);
                                                     }
                                                 }
                                            @endphp
                                            @if($yearly_remaning_days > 1)
                                                <span class="yearly_days" >
                                                 Remaining Days {{ $yearly_remaning_days }}
                                               </span>
                                            @endif
                                            @if($monthly_remaning_days > 1)
                                                <span class="monthly_days">
                                                  Remaining Days {{ $monthly_remaning_days }}
                                               </span>
                                            @endif

                                            <a style="{{ $colorPicked }}" class="monthly_url btn btn-primary my-1"
                                               href="{{ url('/account/credentials/' . $item->id) }}">{{ t('Buy Now') }}</a>

                                            <a style="{{ $colorPicked }} display: none;"   class="yearly_url btn btn-primary my-1"
                                               href="{{ url('/account/credentials/' . $item->id.'?type=yearly') }}">{{ t('Buy Now') }}</a>
                                        @endif
                                    </li>
                                </ul>
                            </div>
                                <?php }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="jumbotron card jumbotron-card">
                        <div class="container">

                            <p class="upgrade-heading"><b>RECURRING BILLING, CANCEL ANYTIME. </b></p>
                            <p style="text-align: center;">
                                Contact Cards give you access to an employee’s CV which includes their email, number and<br>
                                direct message chat through their profile.</p>
                            <p style="text-align: center;">
                                When you subscribe to any one of our 4 paid packages, your subscription will be charged
                                every 30 days<br>
                                to your credit card and auto-renews unless disabled one day before the renewal date.<br>
                                You can cancel anytime by going to the Transactions Page and clicking the cancel button.
                            </p>
                            <p style="text-align: center"><i>*All paid packages expire after 30 days and renew
                                    automatically</i><br>
                                <b>*Only Credit Cards Accepted </b>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection


<script>
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
@section('after_scripts')
@endsection
