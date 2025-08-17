<div class="row">
    <?php
    $net_profit = 0;
    $active = 'active';
    $inactive = 'inactive';
    $stats = [];
    if (isset($data['total_referral_users'])) {
        $stats[] = ['title' => 'Lifetime Referral Companies', 'link' => url('/affiliate/referral_users'), 'counter' => $data['total_referral_users'], 'icon_color'=>'white', 'icon' => 'icon-users', 'background_color' => '#5F567F;', 'text_color' => 'white'];
    }
    if (isset($data['current_month_referral_users'])) {
        $stats[] = ['title' => 'Current Month Referral Companies', 'link' => url('/affiliate/referral_users'), 'counter' => $data['current_month_referral_users'], 'icon_color'=>'black', 'icon' => 'icon-users', 'background_color' => '#FFFFF', 'text_color' => 'black'];
    }
    if (isset($data['last_month_referral_users'])) {
        $stats[] = ['title' => 'Last Month Referral Companies', 'link' => url('/affiliate/referral_users'), 'counter' => $data['last_month_referral_users'], 'icon_color'=>'white', 'icon' => 'icon-users', 'background_color' => '#5F567F;', 'text_color' => 'white'];
    }
    if (isset($data['total_referral_affiliates'])) {
        $stats[] = ['title' => 'Lifetime Referral Affiliates', 'link' => url('/affiliate/referral_users'), 'counter' => $data['total_referral_affiliates'], 'icon_color'=>'black', 'icon' => 'icon-users', 'background_color' => '#FFFFF;', 'text_color' => 'black'];
    }
    if (isset($data['current_month_referral_affiliates'])) {
        $stats[] = ['title' => 'Current Month Referral Affiliates', 'link' => url('/affiliate/referral_users'), 'counter' => $data['current_month_referral_affiliates'], 'icon_color'=>'white', 'icon' => 'icon-users', 'background_color' => '#5F567F', 'text_color' => 'white'];
    }
    if (isset($data['last_month_referral_affiliates'])) {
        $stats[] = ['title' => 'Last Month Referral Affiliates', 'link' => url('/affiliate/referral_users'), 'counter' => $data['last_month_referral_affiliates'], 'icon_color'=>'black', 'icon' => 'icon-users', 'background_color' => '#FFFFF;', 'text_color' => 'black'];
    }
    if (isset($data['my_lifetime_revenue'])) {
        $stats[] = ['title' => 'Referral Companies Lifetime Revenue', 'link' => 'javascript:void(0)', 'counter' => '$'.$data['my_lifetime_revenue'], 'icon_color'=>'black', 'icon' => 'icon-money', 'background_color' => '#FFFFF', 'text_color' => 'black'];
    }
    if (isset($data['affiliated_lifetime_revenue'])) {
        $stats[] = ['title' => 'Referral Affiliates Lifetime Revenue', 'link' => 'javascript:void(0)', 'counter' => '$'.$data['affiliated_lifetime_revenue'], 'icon_color'=>'black', 'icon' => 'icon-money', 'background_color' => '#D2FFF7;', 'text_color' => 'black'];
    }
    if (isset($data['total_revenue'])) {
        $stats[] = ['title' => 'Total Revenue', 'link' => 'javascript:void(0)', 'counter' => '$'.$data['total_revenue'], 'icon_color'=>'black', 'icon' => 'icon-money', 'background_color' => '#FFFFF', 'text_color' => 'black'];
    }
    if (isset($data['my_lifetime_commission'])) {
        $stats[] = ['title' => 'Referral Companies Lifetime Commission', 'link' => 'javascript:void(0)', 'counter' => '$'.$data['my_lifetime_commission'], 'icon_color'=>'black', 'icon' => 'icon-briefcase', 'background_color' => '#D2FFF7;', 'text_color' => 'black'];
    }
    if (isset($data['affiliated_lifetime_commission'])) {
        $stats[] = ['title' => 'Referral Affiliates Lifetime Commission', 'link' => 'javascript:void(0)', 'counter' => '$'.$data['affiliated_lifetime_commission'], 'icon_color'=>'black', 'icon' => 'icon-briefcase', 'background_color' => '#FFFFF;', 'text_color' => 'black'];
    }
    if (isset($data['total_commission'])) {
        $stats[] = ['title' => 'Total Commission', 'link' => 'javascript:void(0)', 'counter' => '$'.$data['total_commission'], 'icon_color'=>'black', 'icon' => 'icon-briefcase', 'background_color' => '#D2FFF7;', 'text_color' => 'black'];
    }
    if (isset($data['my_current_month_revenue'])) {
        $stats[] = ['title' => 'Referral Companies Current Month Revenue', 'link' => 'javascript:void(0)', 'counter' => '$'.$data['my_current_month_revenue'], 'icon_color'=>'black', 'icon' => 'icon-money', 'background_color' => '#FFFFF', 'text_color' => 'black'];
    }
    if (isset($data['affiliated_current_month_revenue'])) {
        $stats[] = ['title' => 'Referral Affiliates Current Month Revenue', 'link' => 'javascript:void(0)', 'counter' => '$'.$data['affiliated_current_month_revenue'], 'icon_color'=>'white', 'icon' => 'icon-money', 'background_color' => '#91bf33;', 'text_color' => 'white'];
    }
    if (isset($data['total_current_month_revenue'])) {
        $stats[] = ['title' => 'Total Current Month Revenue', 'link' => 'javascript:void(0)', 'counter' => '$'.$data['total_current_month_revenue'], 'icon_color'=>'black', 'icon' => 'icon-money', 'background_color' => '#FFFFF', 'text_color' => 'black'];
    }
    if (isset($data['my_current_month_commission'])) {
        $stats[] = ['title' => 'Referral Companies Current Month Commission', 'link' => 'javascript:void(0)', 'counter' => '$'.$data['my_current_month_commission'], 'icon_color'=>'white', 'icon' => 'icon-briefcase', 'background_color' => '#91bf33;', 'text_color' => 'white'];
    }
    if (isset($data['affiliated_current_month_commission'])) {
        $stats[] = ['title' => 'Referral Affiliates Current Month Commission', 'link' => 'javascript:void(0)', 'counter' => '$'.$data['affiliated_current_month_commission'], 'icon_color'=>'black', 'icon' => 'icon-briefcase', 'background_color' => '#FFFFF;', 'text_color' => 'black'];
    }
    if (isset($data['total_current_month_commission'])) {
        $stats[] = ['title' => 'Total Current Month Commission', 'link' => 'javascript:void(0)', 'counter' => '$'.$data['total_current_month_commission'], 'icon_color'=>'white', 'icon' => 'icon-briefcase', 'background_color' => '#91bf33;', 'text_color' => 'white'];
    }
    if (isset($data['my_last_month_revenue'])) {
        $stats[] = ['title' => 'Referral Companies Last Month Revenue', 'link' => 'javascript:void(0)', 'counter' => '$'.$data['my_last_month_revenue'], 'icon_color'=>'black', 'icon' => 'icon-money', 'background_color' => '#FFFFF;', 'text_color' => 'black'];
    }
    if (isset($data['affiliated_last_month_revenue'])) {
        $stats[] = ['title' => 'Referral Affiliates Last Month Revenue', 'link' => 'javascript:void(0)', 'counter' => '$'.$data['affiliated_last_month_revenue'], 'icon_color'=>'white', 'icon' => 'icon-money', 'background_color' => '#7ecfdd;', 'text_color' => 'white'];
    }
    if (isset($data['total_last_month_revenue'])) {
        $stats[] = ['title' => 'Total Last Month Revenue', 'link' => 'javascript:void(0)', 'counter' => '$'.$data['total_last_month_revenue'], 'icon_color'=>'black', 'icon' => 'icon-money', 'background_color' => '#FFFFF', 'text_color' => 'black'];
    }
    if (isset($data['my_last_month_commission'])) {
        $stats[] = ['title' => 'Referral Companies Last Month Commission', 'link' => 'javascript:void(0)', 'counter' => '$'.$data['my_last_month_commission'], 'icon_color'=>'white', 'icon' => 'icon-briefcase', 'background_color' => '#7ecfdd;', 'text_color' => 'white'];
    }
    if (isset($data['affiliated_last_month_commission'])) {
        $stats[] = ['title' => 'Referral Affiliates Last Month Commission', 'link' => 'javascript:void(0)', 'counter' => '$'.$data['affiliated_last_month_commission'], 'icon_color'=>'black', 'icon' => 'icon-briefcase', 'background_color' => '#FFFFF;', 'text_color' => 'black'];
    }
    if (isset($data['total_last_month_commission'])) {
        $stats[] = ['title' => 'Total Last Month Commission', 'link' => 'javascript:void(0)', 'counter' => '$'.$data['total_last_month_commission'], 'icon_color'=>'white', 'icon' => 'icon-briefcase', 'background_color' => '#7ecfdd;', 'text_color' => 'white'];
    }

    $counter = 0;
    $titled_groups = [
        'Referral Users',
        'Lifetime Commission And Revenue',
        'Current Month Commission And Revenue',
        'Last Month Commission And Revenue',
    ];
    $chunks = array_chunk($stats, 6);
    ?>

    @foreach ($chunks as $index => $group)
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="card-title mb-4">{{ $titled_groups[$index] ?? 'Stats Group ' . ($index + 1) }}</h4>
                <div class="row">
                    @foreach ($group as $stat)
                        <div class="col-lg-4 col-xs-6 mb-3" style="cursor: pointer;" onclick="window.location.href = '{{ $stat['link'] }}'">
                            <div class="card border-end h-100">
                                <div class="card-body" style="min-height: 158px; background: <?= $stat['background_color'] ?>">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <a href="{{ $stat['link'] }}" class="d-inline-flex align-items-center">
                                                <h2 style="color: <?= $stat['text_color'] ?> !important;" class="mb-1 font-weight-medium">
                                                    {{ $stat['counter'] }}
                                                </h2>
                                            </a>
                                            <h6 style="color: <?= $stat['text_color'] ?> !important;" class="text-muted font-12">
                                                {{ $stat['title'] }}
                                            </h6>
                                        </div>
                                        <div class="ms-auto mt-md-4" onclick="window.location.href = '{{ $stat['link'] }}'">
                                            <span style="color: <?= $stat['text_color'] ?> !important; margin-left:45px" class="opacity-7 text-muted">
                                                <i class="<?= $stat['icon'] ?>" style="color: <?= $stat['icon_color'] ?>"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endforeach
</div>
<style>
    .hidden {
        display: none;
    }
</style>