@extends('layouts.master')

@section('content')
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

                            <p class="upgrade-heading"><b>RECURRING BILLING, CANCEL ANYTIME. </b></p>
                            <p style="text-align: center;">
                                Contact Cards give you access to an employee’s CV which includes their email, number and<br>
                                direct message chat through their profile.</p>
                            <p style="text-align: center;">
                                When you subscribe to any one of our 3 paid packages, your subscription will be charged
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
                    <?php
                    if (auth()->user()->country_code == 'KW') {
                        $col = 'col-md-3';
                    } else {
                        $col = 'col-md-4';
                    }
                    ?>


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

                                $subscrived_package = CompanyPackages::get_all_subscibed_packages(auth()->user()->id, $item->id);
                                if (!empty($subscrived_package)) {
                                    $remaning_days = Helper::calculate_remaining_days($subscrived_package->package_expire_date);
                                } else {
                                    $remaning_days = 0;
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
                                        @if ((float) $item->price == 980.0)
                                            .
                                            <br><span>- Have the ability to hide your company name & logo on job
                                                posts</span>
                                            <br><span>- Have the ability to chat with employees (job seekers) in bulk, you
                                                can
                                                send messages to multiple users at once</span>
                                     @endif
                                            @if ((float) $item->price == 4500.0)
                                                <br><span>- All the upgrades from other packages available</span>
                                                <br><span>- HungryForJobs team will handle your Dashboard, post jobs on your behalf, find the right candidates for your business and provide you with CV’s via email or WhatsApp so you can contact them for Interviews</span>
                                                <br><span>- Local & International Hires Included</span>
                                                <br><span>- 0% agency fees</span>
                                    @endif

                                    <li style="min-height: 60px; ">

                                        @if ((float) $item->price == 0.0)
                                            <h3 class="price_usd mt-3">
                                                {{ t('FREE') }}
                                            </h3>
                                        @else
                                            <h3 class="price_usd">
                                                {{ $item->currency_code }} ${{ number_format($item->price, 0) }}
                                            </h3>
                                        @endif

                                        <span style="text-align:center;">
                                            @if ($item->id == 4)
                                                ($99.5 per job post)
                                            @endif

                                            @if ($item->id == 1)
                                                ($85 per job post)
                                            @endif

                                            @if ($item->id == 6)
                                                ($70 per job post)
                                            @endif
                                        </span>

                                        <br>

                                        @if ((float) $item->price == 425.0)
                                            <span style="color:red">You Save 14.5 % !</span>
                                        @endif
                                        @if ((float) $item->price == 980.0)
                                            <span style="color:red">You Save 30 % !</span>
                                        @endif


                                    </li>
                                    <li>
                                        @if ((float) $item->price == 0.0)
                                            <a style="{{ $colorPicked }}" class="btn btn-primary my-1"
                                               href="{{ url('/account/paymentFree/?id=' . $item->id) }}">
                                                {{ t('Buy Now') }}</a>
                                        @else
                                                <?php if ($remaning_days > 1) { ?>
                                            <span class="d-flex flex-column">
                                                <span>Remaining Days {{ $remaning_days }}</span>
                                            </span>
                                            <?php } ?>

                                            <a style="{{ $colorPicked }}" class="btn btn-primary my-1"
                                               href="{{ url('/account/credentials/' . $item->id) }}">{{ t('Buy Now') }}</a>
                                        @endif

                                    </li>
                                </ul>
                            </div>
                                <?php
                            }
                            }
                            ?>
                        </div>
                    </div>
                </div>
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
    <style>

    </style>
@endsection


@section('after_scripts')
@endsection
