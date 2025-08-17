<?php

use App\Models\Resume;

$hideOnMobile = '';
if (isset($statsOptions, $statsOptions['hide_on_mobile']) and $statsOptions['hide_on_mobile'] == '1') {
    $hideOnMobile = ' hidden-sm';
}
$count_reusme = Resume::count();
?>
@if (isset($countPosts) and isset($countUsers) and isset($countCities))
@includeFirst([config('larapen.core.customizedViewPath') . 'home.inc.spacer', 'home.inc.spacer'], ['hideOnMobile' => $hideOnMobile])
<div class="padding-top-90 padding-bottom-60 fact-bg" hidden>
    <div class="container{{ $hideOnMobile }}">
        <div class="page-info page-info-lite rounded">
            <div class="text-center section-promo">
                <div class="row">

                    @if (isset($count_reusme))
                    <div class="col-sm-3 col-xs-6 col-xxs-6">
                        <div class="iconbox-wrap">
                            <div class="iconbox">
                                <div class="iconbox-wrap-content">
                                    <i class="icon icon-attach mb-4"></i>
                                    <h5><span>{{ $count_reusme }}</span></h5>
                                    <div class="iconbox-wrap-text">{{ t('Resume') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if (isset($countPosts))
                    <div class="col-sm-3 col-xs-6 col-xxs-6">
                        <div class="iconbox-wrap">
                            <div class="iconbox">
                                <div class="iconbox-wrap-content">
                                    <i class="icon icon-docs mb-4"></i>
                                    <h5><span>{{ $countPosts }}</span></h5>
                                    <div class="iconbox-wrap-text">{{ t('Jobs') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if (isset($countUsers))
                    <div class="col-sm-3 col-xs-6 col-xxs-6">
                        <div class="iconbox-wrap">
                            <div class="iconbox">
                                <div class="iconbox-wrap-content">
                                    <i class="icon icon-group mb-4"></i>
                                    <h5><span>{{ $countUsers }}</span></h5>
                                    <div class="iconbox-wrap-text">{{ t('Users') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if (isset($countCities))
                    <div class="col-sm-3 col-xs-6  col-xxs-6">
                        <div class="iconbox-wrap">
                            <div class="iconbox">
                                <div class="iconbox-wrap-content">
                                    <i class="icon icon-map mb-4"></i>
                                    @if($countCities<=0)
                                    <h5><span>{{ $countCities}}</span></h5>
                                    @else
                                    <h5><span>{{ $countCities . '+' }}</span></h5>
                                    @endif
                                    <div class="iconbox-wrap-text"> {{ t('Job locations') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endif
