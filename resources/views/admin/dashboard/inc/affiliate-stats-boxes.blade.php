<div class="row">
    <?php
    $net_profit = 0;
    $active = 'active';
    $inactive = 'inactive';
    $stats = [];
    if (isset($number_of_affiliates)) {
        $stats[] = ['title' => '# of Affiliates', 'link' => admin_url('affiliates'), 'counter' => $number_of_affiliates, 'icon' => 'user-plus', 'background_color' => '#FFFFFF', 'text_color' => 'black'];
    }
    $stats[] = ['title' => 'Chats With Affiliates', 'link' => admin_url('affiliate_messages'), 'counter' => $unread_affiliate_threads, 'icon' => 'message-circle', 'background_color' => '#5F567F', 'text_color' => 'white'];
    $stats[] = ['title' => 'Total Withdraw Requests', 'link' => admin_url('withdraw_requests'), 'counter' => $total_withdraw_requests, 'icon' => 'briefcase', 'background_color' => '#FFFFFF', 'text_color' => 'black'];
    $stats[] = ['title' => 'Lifetime Referral Companies', 'link' => admin_url('employer?filter=life_time'), 'counter' => $total_referral_users, 'icon' => 'users', 'background_color' => '#5F567F', 'text_color' => 'white'];
    $stats[] = ['title' => 'Current Month Referral Companies', 'link' => admin_url('employer?filter=current_month'), 'counter' => $current_month_referral_users, 'icon' => 'users', 'background_color' => '#FFFFFF', 'text_color' => 'black'];
    $stats[] = ['title' => 'Last Month Referral Companies', 'link' => admin_url('employer?filter=last_month'), 'counter' => $last_month_referral_users, 'icon' => 'users', 'background_color' => '#5F567F', 'text_color' => 'white'];
    $stats[] = ['title' => 'Lifetime Referral Affiliates', 'link' => admin_url('affiliates?filter=life_time'), 'counter' => $total_referral_affiliates, 'icon' => 'users', 'background_color' => '#FFFFFF', 'text_color' => 'black'];
    $stats[] = ['title' => 'Current Month Referral Affiliates', 'link' => admin_url('affiliates?filter=current_month'), 'counter' => $current_month_referral_affiliates, 'icon' => 'users', 'background_color' => '#5F567F', 'text_color' => 'white'];
    $stats[] = ['title' => 'Last Month Referral Affiliates', 'link' => admin_url('affiliates?filter=last_month'), 'counter' => $last_month_referral_affiliates, 'icon' => 'users', 'background_color' => '#FFFFFF', 'text_color' => 'black'];
    $stats[] = ['title' => 'Referral Companies Lifetime Revenue', 'link' => 'javascript:void(0)', 'counter' => $my_lifetime_revenue, 'icon' => 'dollar-sign', 'background_color' => '#FFFFFF', 'text_color' => 'black'];
    $stats[] = ['title' => 'Referral Affiliates Lifetime Revenue', 'link' => 'javascript:void(0)', 'counter' => $affiliated_lifetime_revenue, 'icon' => 'dollar-sign', 'background_color' => '#D2FFF7', 'text_color' => 'black'];
    $stats[] = ['title' => 'Total Revenue', 'link' => 'javascript:void(0)', 'counter' => $total_revenue, 'icon' => 'dollar-sign', 'background_color' => '#FFFFFF', 'text_color' => 'black'];
    $stats[] = ['title' => 'Referral Companies Lifetime Commission', 'link' => 'javascript:void(0)', 'counter' => $my_lifetime_commission, 'icon' => 'dollar-sign', 'background_color' => '#D2FFF7', 'text_color' => 'black'];
    $stats[] = ['title' => 'Referral Affiliates Lifetime Commission', 'link' => 'javascript:void(0)', 'counter' => $affiliated_lifetime_commission, 'icon' => 'dollar-sign', 'background_color' => '#FFFFFF', 'text_color' => 'black'];
    $stats[] = ['title' => 'Total Commission', 'link' => 'javascript:void(0)', 'counter' => $total_commission, 'icon' => 'dollar-sign', 'background_color' => '#D2FFF7', 'text_color' => 'black'];
    $stats[] = ['title' => 'Referral Companies Current Month Revenue', 'link' => 'javascript:void(0)', 'counter' => $my_current_month_revenue, 'icon' => 'dollar-sign', 'background_color' => '#FFFFFF', 'text_color' => 'black'];
    $stats[] = ['title' => 'Referral Affiliates Current Month Revenue', 'link' => 'javascript:void(0)', 'counter' => $affiliated_current_month_revenue, 'icon' => 'dollar-sign', 'background_color' => '#91bf33', 'text_color' => 'white'];
    $stats[] = ['title' => 'Total Current Month Revenue', 'link' => 'javascript:void(0)', 'counter' => $total_current_month_revenue, 'icon' => 'dollar-sign', 'background_color' => '#FFFFFF', 'text_color' => 'black'];
    $stats[] = ['title' => 'Referral Companies Current Month Commission', 'link' => 'javascript:void(0)', 'counter' => $my_current_month_commission, 'icon' => 'dollar-sign', 'background_color' => '#91bf33', 'text_color' => 'white'];
    $stats[] = ['title' => 'Referral Affiliates Current Month Commission', 'link' => 'javascript:void(0)', 'counter' => $affiliated_current_month_commission, 'icon' => 'dollar-sign', 'background_color' => '#FFFFFF', 'text_color' => 'black'];
    $stats[] = ['title' => 'Total Current Month Commission', 'link' => 'javascript:void(0)', 'counter' => $total_current_month_commission, 'icon' => 'dollar-sign', 'background_color' => '#91bf33', 'text_color' => 'white'];
    $stats[] = ['title' => 'Referral Companies Last Month Revenue', 'link' => 'javascript:void(0)', 'counter' => $my_last_month_revenue, 'icon' => 'dollar-sign', 'background_color' => '#FFFFFF', 'text_color' => 'black'];
    $stats[] = ['title' => 'Referral Affiliates Last Month Revenue', 'link' => 'javascript:void(0)', 'counter' => $affiliated_last_month_revenue, 'icon' => 'dollar-sign', 'background_color' => '#7ecfdd', 'text_color' => 'white'];
    $stats[] = ['title' => 'Total Last Month Revenue', 'link' => 'javascript:void(0)', 'counter' => $total_last_month_revenue, 'icon' => 'dollar-sign', 'background_color' => '#FFFFFF', 'text_color' => 'black'];
    $stats[] = ['title' => 'Referral Companies Last Month Commission', 'link' => 'javascript:void(0)', 'counter' => $my_last_month_commission, 'icon' => 'dollar-sign', 'background_color' => '#7ecfdd', 'text_color' => 'white'];
    $stats[] = ['title' => 'Referral Affiliates Last Month Commission', 'link' => 'javascript:void(0)', 'counter' => $affiliated_last_month_commission, 'icon' => 'dollar-sign', 'background_color' => '#FFFFFF', 'text_color' => 'black'];
    $stats[] = ['title' => 'Total Last Month Commission', 'link' => 'javascript:void(0)', 'counter' => $total_last_month_commission, 'icon' => 'dollar-sign', 'background_color' => '#7ecfdd', 'text_color' => 'white'];

    $counter = 0;
    $groupTitles = [
        'Top Overview Stats',
        'Lifetime Commission And Revenue',
        'Current Month Commission And Revenue',
        'Last Month Commission And Revenue',
    ];

    $custom_chunks = array_merge(
        [array_slice($stats, 0, 9)],
        array_chunk(array_slice($stats, 9), 6)
    );

    foreach ($custom_chunks as $index => $chunk) {
        $title = $groupTitles[$index] ?? 'Statistics';
    ?>

    <div class="card mb-4">
        <div class="card-body">
            <h4 class="card-title"><?= $title ?></h4>
            <div class="row">

            <?php

    foreach ($chunk as $stat) {
        $class = '';
        $hideRevenue = '';
        if ($stat['title'] == 'Referral Companies Lifetime Revenue') {
            $class = 'lifetime_revenue';
            $hideRevenue = 'hidden lifetime_revenue';
        }
        if ($stat['title'] == 'Referral Companies Lifetime Commission') {
            $class = 'lifetime_commission';
            $hideRevenue = 'hidden lifetime_commission';
        }
        if ($stat['title'] == 'Referral Affiliates Lifetime Revenue') {
            $class = 'affiliated_lifetime_revenue';
            $hideRevenue = 'hidden affiliated_lifetime_revenue';
        }
        if ($stat['title'] == 'Referral Affiliates Lifetime Commission') {
            $class = 'affiliated_lifetime_commission';
            $hideRevenue = 'hidden affiliated_lifetime_commission';
        }
        if ($stat['title'] == 'Total Revenue') {
            $class = 'total_revenue';
            $hideRevenue = 'hidden total_revenue';
        }
        if ($stat['title'] == 'Total Commission') {
            $class = 'total_commission';
            $hideRevenue = 'hidden total_commission';
        }
        if ($stat['title'] == 'Referral Companies Current Month Revenue') {
            $class = 'current_month_revenue';
            $hideRevenue = 'hidden current_month_revenue';
        }
        if ($stat['title'] == 'Referral Companies Current Month Commission') {
            $class = 'current_month_commission';
            $hideRevenue = 'hidden current_month_commission';
        }
        if ($stat['title'] == 'Referral Affiliates Current Month Revenue') {
            $class = 'affiliated_current_month_revenue';
            $hideRevenue = 'hidden affiliated_current_month_revenue';
        }
        if ($stat['title'] == 'Referral Affiliates Current Month Commission') {
            $class = 'affiliated_current_month_commission';
            $hideRevenue = 'hidden affiliated_current_month_commission';
        }
        if ($stat['title'] == 'Total Current Month Revenue') {
            $class = 'total_current_month_revenue';
            $hideRevenue = 'hidden total_current_month_revenue';
        }
        if ($stat['title'] == 'Total Current Month Commission') {
            $class = 'total_current_month_commission';
            $hideRevenue = 'hidden total_current_month_commission';
        }
        if ($stat['title'] == 'Referral Companies Last Month Revenue') {
            $class = 'last_month_revenue';
            $hideRevenue = 'hidden last_month_revenue';
        }
        if ($stat['title'] == 'Referral Companies Last Month Commission') {
            $class = 'last_month_commission';
            $hideRevenue = 'hidden last_month_commission';
        }
        if ($stat['title'] == 'Referral Affiliates Last Month Revenue') {
            $class = 'affiliated_last_month_revenue';
            $hideRevenue = 'hidden affiliated_last_month_revenue';
        }
        if ($stat['title'] == 'Referral Affiliates Last Month Commission') {
            $class = 'affiliated_last_month_commission';
            $hideRevenue = 'hidden affiliated_last_month_commission';
        }
        if ($stat['title'] == 'Total Last Month Revenue') {
            $class = 'total_last_month_revenue';
            $hideRevenue = 'hidden total_last_month_revenue';
        }
        if ($stat['title'] == 'Total Last Month Commission') {
            $class = 'total_last_month_commission';
            $hideRevenue = 'hidden total_last_month_commission';
        }
        $class_affiliate_counter = '';
        if ($stat['title'] == 'Chats With Affiliates') {
            $class_affiliate_counter = 'affiliate_message_counter';
        }
        $with_draw_request_class = '';
        if ($stat['title'] == 'Total Withdraw Requests') {
            $with_draw_request_class = 'withdraw_request_counter';
        }
        ?>
                <div class="col-lg-4 col-xs-6" style="cursor: pointer;" onclick="window.location.href = '{{ $stat['link'] }}'">
                    <div class="card border-end">
                        <div class="card-body" style="min-height: 104px;background: <?= $stat['background_color'] ?>;">
                            <div class="d-flex align-items-center">
                                <div>
                                    <a href="{{ $stat['link'] }}" class="d-inline-flex align-items-center">
                                        <h2 style="color: <?= $stat['text_color'] ?> !important;"
                                            class="text-dark mb-1 font-weight-medium <?= $hideRevenue ?> <?= $class_affiliate_counter ?> <?= $with_draw_request_class ?>">
                                            @if($stat['icon'] == 'dollar-sign')
                                                <i data-feather="dollar-sign"></i>
                                            @endif
                                            {{ $stat['counter'] }}
                                        </h2>
                                    </a>
                                    <h6 style="color: <?= $stat['text_color'] ?> !important;"
                                        class="text-muted font-weight-normal mb-0 font-12 {{$class}}">
                                        {{ $stat['title'] }}
                                    </h6>
                                </div>
                                <div class="ms-auto mt-md-3 mt-lg-0" onclick="window.location.href = '{{ $stat['link'] }}'">


                        <span style="color: <?= $stat['text_color'] ?> !important;" class="opacity-7 text-muted">
                            @if($stat['title'] == 'Referral Companies Lifetime Revenue')
                                <i onclick="showLTRevenue(event)" id="toggle-lifetime-revenue-icon" data-feather="eye-off"></i>
                            @endif
                            @if($stat['title'] == 'Referral Companies Lifetime Commission')
                                <i onclick="showLTCommission(event)" id="toggle-lifetime-commission-icon" data-feather="eye-off"></i>
                            @endif
                            @if($stat['title'] == 'Referral Affiliates Lifetime Revenue')
                                <i onclick="showALTRevenue(event)" id="toggle-affiliated-lifetime-revenue-icon" data-feather="eye-off"></i>
                            @endif
                            @if($stat['title'] == 'Referral Affiliates Lifetime Commission')
                                <i onclick="showALTCommission(event)" id="toggle-affiliated-lifetime-commission-icon" data-feather="eye-off"></i>
                            @endif
                            @if($stat['title'] == 'Total Revenue')
                                <i onclick="showRevenue(event)" id="toggle-total-revenue-icon" data-feather="eye-off"></i>
                            @endif
                            @if($stat['title'] == 'Total Commission')
                                <i onclick="showCommission(event)" id="toggle-total-commission-icon" data-feather="eye-off"></i>
                            @endif
                            @if($stat['title'] == 'Referral Companies Current Month Revenue')
                                <i onclick="showCMRevenue(event)" id="toggle-current-month-revenue-icon" data-feather="eye-off"></i>
                            @endif
                            @if($stat['title'] == 'Referral Companies Current Month Commission')
                                <i onclick="showCMCommission(event)" id="toggle-current-month-commission-icon" data-feather="eye-off"></i>
                            @endif
                            @if($stat['title'] == 'Referral Affiliates Current Month Revenue')
                                <i onclick="showACMRevenue(event)" id="toggle-affiliated-current-month-revenue-icon" data-feather="eye-off"></i>
                            @endif
                            @if($stat['title'] == 'Referral Affiliates Current Month Commission')
                                <i onclick="showACMCommission(event)" id="toggle-affiliated-current-month-commission-icon" data-feather="eye-off"></i>
                            @endif
                            @if($stat['title'] == 'Total Current Month Revenue')
                                <i onclick="showTCMRevenue(event)" id="toggle-total-current-month-revenue-icon" data-feather="eye-off"></i>
                            @endif
                            @if($stat['title'] == 'Total Current Month Commission')
                                <i onclick="showTCMCommission(event)" id="toggle-total-current-month-commission-icon" data-feather="eye-off"></i>
                            @endif
                            @if($stat['title'] == 'Referral Companies Last Month Revenue')
                                <i onclick="showLMRevenue(event)" id="toggle-last-month-revenue-icon" data-feather="eye-off"></i>
                            @endif
                            @if($stat['title'] == 'Referral Companies Last Month Commission')
                                <i onclick="showLMCommission(event)" id="toggle-last-month-commission-icon" data-feather="eye-off"></i>
                            @endif
                            @if($stat['title'] == 'Referral Affiliates Last Month Revenue')
                                <i onclick="showALMRevenue(event)" id="toggle-affiliated-last-month-revenue-icon" data-feather="eye-off"></i>
                            @endif
                            @if($stat['title'] == 'Referral Affiliates Last Month Commission')
                                <i onclick="showALMCommission(event)" id="toggle-affiliated-last-month-commission-icon" data-feather="eye-off"></i>
                            @endif
                            @if($stat['title'] == 'Total Last Month Revenue')
                                <i onclick="showTLMRevenue(event)" id="toggle-total-last-month-revenue-icon" data-feather="eye-off"></i>
                            @endif
                            @if($stat['title'] == 'Total Last Month Commission')
                                <i onclick="showTLMCommission(event)" id="toggle-total-last-month-commission-icon" data-feather="eye-off"></i>
                            @endif
                            @if($stat['icon'] != 'dollar-sign')
                                <i data-feather="<?= $stat['icon'] ?>"></i>
                            @endif

                        </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
        </div>
        </div>
    </div>
<?php } ?>
</div>
        <script>
            function showLTRevenue(event) {
                event.preventDefault();
                event.stopPropagation();
                const iconElement = document.getElementById('toggle-lifetime-revenue-icon');
                const revenueElement = document.querySelector('.lifetime_revenue');
                if (revenueElement.classList.contains('hidden')) {
                    revenueElement.classList.remove('hidden');
                    iconElement.setAttribute('data-feather', 'eye');
                } else {
                    revenueElement.classList.add('hidden');
                    iconElement.setAttribute('data-feather', 'eye-off');
                }
                feather.replace();
            }
            function showLTCommission(event) {
                event.preventDefault();
                event.stopPropagation();
                const iconElement = document.getElementById('toggle-lifetime-commission-icon');
                const revenueElement = document.querySelector('.lifetime_commission');
                if (revenueElement.classList.contains('hidden')) {
                    revenueElement.classList.remove('hidden');
                    iconElement.setAttribute('data-feather', 'eye');
                } else {
                    revenueElement.classList.add('hidden');
                    iconElement.setAttribute('data-feather', 'eye-off');
                }
                feather.replace();
            }
            function showALTRevenue(event) {
                event.preventDefault();
                event.stopPropagation();
                const iconElement = document.getElementById('toggle-affiliated-lifetime-revenue-icon');
                const revenueElement = document.querySelector('.affiliated_lifetime_revenue');
                if (revenueElement.classList.contains('hidden')) {
                    revenueElement.classList.remove('hidden');
                    iconElement.setAttribute('data-feather', 'eye');
                } else {
                    revenueElement.classList.add('hidden');
                    iconElement.setAttribute('data-feather', 'eye-off');
                }
                feather.replace();
            }
            function showALTCommission(event) {
                event.preventDefault();
                event.stopPropagation();
                const iconElement = document.getElementById('toggle-affiliated-lifetime-commission-icon');
                const revenueElement = document.querySelector('.affiliated_lifetime_commission');
                if (revenueElement.classList.contains('hidden')) {
                    revenueElement.classList.remove('hidden');
                    iconElement.setAttribute('data-feather', 'eye');
                } else {
                    revenueElement.classList.add('hidden');
                    iconElement.setAttribute('data-feather', 'eye-off');
                }
                feather.replace();
            }
            function showRevenue(event) {
                event.preventDefault();
                event.stopPropagation();
                const iconElement = document.getElementById('toggle-total-revenue-icon');
                const revenueElement = document.querySelector('.total_revenue');
                if (revenueElement.classList.contains('hidden')) {
                    revenueElement.classList.remove('hidden');
                    iconElement.setAttribute('data-feather', 'eye');
                } else {
                    revenueElement.classList.add('hidden');
                    iconElement.setAttribute('data-feather', 'eye-off');
                }
                feather.replace();
            }
            function showCommission(event) {
                event.preventDefault();
                event.stopPropagation();
                const iconElement = document.getElementById('toggle-total-commission-icon');
                const revenueElement = document.querySelector('.total_commission');
                if (revenueElement.classList.contains('hidden')) {
                    revenueElement.classList.remove('hidden');
                    iconElement.setAttribute('data-feather', 'eye');
                } else {
                    revenueElement.classList.add('hidden');
                    iconElement.setAttribute('data-feather', 'eye-off');
                }
                feather.replace();
            }
            function showCMRevenue(event) {
                event.preventDefault();
                event.stopPropagation();
                const iconElement = document.getElementById('toggle-current-month-revenue-icon');
                const revenueElement = document.querySelector('.current_month_revenue');
                if (revenueElement.classList.contains('hidden')) {
                    revenueElement.classList.remove('hidden');
                    iconElement.setAttribute('data-feather', 'eye');
                } else {
                    revenueElement.classList.add('hidden');
                    iconElement.setAttribute('data-feather', 'eye-off');
                }
                feather.replace();
            }
            function showCMCommission(event) {
                event.preventDefault();
                event.stopPropagation();
                const iconElement = document.getElementById('toggle-current-month-commission-icon');
                const revenueElement = document.querySelector('.current_month_commission');
                if (revenueElement.classList.contains('hidden')) {
                    revenueElement.classList.remove('hidden');
                    iconElement.setAttribute('data-feather', 'eye');
                } else {
                    revenueElement.classList.add('hidden');
                    iconElement.setAttribute('data-feather', 'eye-off');
                }
                feather.replace();
            }
            function showACMRevenue(event) {
                event.preventDefault();
                event.stopPropagation();
                const iconElement = document.getElementById('toggle-affiliated-current-month-revenue-icon');
                const revenueElement = document.querySelector('.affiliated_current_month_revenue');
                if (revenueElement.classList.contains('hidden')) {
                    revenueElement.classList.remove('hidden');
                    iconElement.setAttribute('data-feather', 'eye');
                } else {
                    revenueElement.classList.add('hidden');
                    iconElement.setAttribute('data-feather', 'eye-off');
                }
                feather.replace();
            }
            function showACMCommission(event) {
                event.preventDefault();
                event.stopPropagation();
                const iconElement = document.getElementById('toggle-affiliated-current-month-commission-icon');
                const revenueElement = document.querySelector('.affiliated_current_month_commission');
                if (revenueElement.classList.contains('hidden')) {
                    revenueElement.classList.remove('hidden');
                    iconElement.setAttribute('data-feather', 'eye');
                } else {
                    revenueElement.classList.add('hidden');
                    iconElement.setAttribute('data-feather', 'eye-off');
                }
                feather.replace();
            }
            function showTCMRevenue(event) {
                event.preventDefault();
                event.stopPropagation();
                const iconElement = document.getElementById('toggle-total-current-month-revenue-icon');
                const revenueElement = document.querySelector('.total_current_month_revenue');
                if (revenueElement.classList.contains('hidden')) {
                    revenueElement.classList.remove('hidden');
                    iconElement.setAttribute('data-feather', 'eye');
                } else {
                    revenueElement.classList.add('hidden');
                    iconElement.setAttribute('data-feather', 'eye-off');
                }
                feather.replace();
            }
            function showTCMCommission(event) {
                event.preventDefault();
                event.stopPropagation();
                const iconElement = document.getElementById('toggle-total-current-month-commission-icon');
                const revenueElement = document.querySelector('.total_current_month_commission');
                if (revenueElement.classList.contains('hidden')) {
                    revenueElement.classList.remove('hidden');
                    iconElement.setAttribute('data-feather', 'eye');
                } else {
                    revenueElement.classList.add('hidden');
                    iconElement.setAttribute('data-feather', 'eye-off');
                }
                feather.replace();
            }
            function showLMRevenue(event) {
                event.preventDefault();
                event.stopPropagation();
                const iconElement = document.getElementById('toggle-last-month-revenue-icon');
                const revenueElement = document.querySelector('.last_month_revenue');
                if (revenueElement.classList.contains('hidden')) {
                    revenueElement.classList.remove('hidden');
                    iconElement.setAttribute('data-feather', 'eye');
                } else {
                    revenueElement.classList.add('hidden');
                    iconElement.setAttribute('data-feather', 'eye-off');
                }
                feather.replace();
            }
            function showLMCommission(event) {
                event.preventDefault();
                event.stopPropagation();
                const iconElement = document.getElementById('toggle-last-month-commission-icon');
                const revenueElement = document.querySelector('.last_month_commission');
                if (revenueElement.classList.contains('hidden')) {
                    revenueElement.classList.remove('hidden');
                    iconElement.setAttribute('data-feather', 'eye');
                } else {
                    revenueElement.classList.add('hidden');
                    iconElement.setAttribute('data-feather', 'eye-off');
                }
                feather.replace();
            }
            function showALMRevenue(event) {
                event.preventDefault();
                event.stopPropagation();
                const iconElement = document.getElementById('toggle-affiliated-last-month-revenue-icon');
                const revenueElement = document.querySelector('.affiliated_last_month_revenue');
                if (revenueElement.classList.contains('hidden')) {
                    revenueElement.classList.remove('hidden');
                    iconElement.setAttribute('data-feather', 'eye');
                } else {
                    revenueElement.classList.add('hidden');
                    iconElement.setAttribute('data-feather', 'eye-off');
                }
                feather.replace();
            }
            function showALMCommission(event) {
                event.preventDefault();
                event.stopPropagation();
                const iconElement = document.getElementById('toggle-affiliated-last-month-commission-icon');
                const revenueElement = document.querySelector('.affiliated_last_month_commission');
                if (revenueElement.classList.contains('hidden')) {
                    revenueElement.classList.remove('hidden');
                    iconElement.setAttribute('data-feather', 'eye');
                } else {
                    revenueElement.classList.add('hidden');
                    iconElement.setAttribute('data-feather', 'eye-off');
                }
                feather.replace();
            }
            function showTLMRevenue(event) {
                event.preventDefault();
                event.stopPropagation();
                const iconElement = document.getElementById('toggle-total-last-month-revenue-icon');
                const revenueElement = document.querySelector('.total_last_month_revenue');
                if (revenueElement.classList.contains('hidden')) {
                    revenueElement.classList.remove('hidden');
                    iconElement.setAttribute('data-feather', 'eye');
                } else {
                    revenueElement.classList.add('hidden');
                    iconElement.setAttribute('data-feather', 'eye-off');
                }
                feather.replace();
            }
            function showTLMCommission(event) {
                event.preventDefault();
                event.stopPropagation();
                const iconElement = document.getElementById('toggle-total-last-month-commission-icon');
                const revenueElement = document.querySelector('.total_last_month_commission');
                if (revenueElement.classList.contains('hidden')) {
                    revenueElement.classList.remove('hidden');
                    iconElement.setAttribute('data-feather', 'eye');
                } else {
                    revenueElement.classList.add('hidden');
                    iconElement.setAttribute('data-feather', 'eye-off');
                }
                feather.replace();
            }

            setInterval(function () {
                $.ajax({
                    url: `getAffiliateUnreadMessage`,
                    method: 'GET',
                    data: {},
                    success: function (response) {
                        if (response > 0) {
                            toastr.options = {
                                "timeOut": "10000",
                                "extendedTimeOut": "10000",
                                "escapeHtml": false
                            };
                            $('.affiliate_message_counter').text(response);
                            toastr.success('You have received a new message from the Affiliate! Please check in <a href="{{admin_url('affiliate_messages')}}" style="color: #FFF; text-decoration: underline;">Chats with Affiliates Section</a>', 'New Message');
                        }
                    },
                    error: function (xhr) {
                        console.error('An error occurred:', xhr);
                    }
                });
            }, 30000);

            setInterval(function () {
                $.ajax({
                    url: `getUnseenWithdrawRequest`,
                    method: 'GET',
                    data: {},
                    success: function (response) {
                        if (response > 0) {
                            toastr.options = {
                                "timeOut": "10000",
                                "extendedTimeOut": "10000",
                                "escapeHtml": false
                            };
                            $('.withdraw_request_counter').text(response);
                            toastr.success('You have received a new withdraw request from the Affiliate! Please check in <a href="{{admin_url('withdraw_requests')}}" style="color: #FFF; text-decoration: underline;">Withdraw Requests Section</a>', 'New Request');
                        }
                    },
                    error: function (xhr) {
                        console.error('An error occurred:', xhr);
                    }
                });
            }, 30000);
        </script>

        <style>
            .hidden {
                display: none;
            }
        </style>
