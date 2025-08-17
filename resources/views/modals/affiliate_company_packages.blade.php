<div class="modal fade modal-delete" id="modal_company_packages" style="z-index: 111111111;" tabindex="1"
     role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close myClose" data-dismiss="modal">
                    &times;
                </button>
                <h2><i data-feather="calendar"></i>Company Packages </h2>

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
                    <div class="white-bg">
                        <div class="row justify-content-center">
                            <?php

                            use App\Helpers\Helper;
                            use App\Models\CompanyPackages;

                            $today = date('Y-m-d');
                            foreach ($data['packages'] as $key => $item) {
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

                                    <li class="my-1">

                                        @if ((float) $item->price == 0.0)
                                            <h3 class="price_usd mt-3">
                                                {{ t('FREE') }}
                                            </h3>
                                        @else
                                            <?php
                                                $affiliate_setting = App\Models\AffiliateSetting::first();
                                            ?>
                                            @if(auth()->user()->affiliate_id == 0 && $affiliate_setting)
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
                                                        $discount = 'You Save $' . $discount_value . ' Referral Discount';
                                                    }else{
                                                        $price_package_monthly = $item->price - ($item->price * $discount_value / 100);
                                                        $price_package_yearly = $item->yearly_price - ($item->yearly_price * $discount_value / 100);
                                                        $referral_discount = 'You Save ' . $discount_value . '% Referral Discount';
                                                        $discount = 'You Save ' . $discount_value . ' % Referral Discount';
                                                    }
                                                ?>
                                                @if((float) $item->price == 425.0)
                                                    <span class="monthly_saving" style="color:red">{{ $discount }}</span>
                                                    <span style="color:red; display:none;" class="yearly_price">{{ $referral_discount }}</span>
                                                @elseif((float) $item->price == 980.0)
                                                    <span class="monthly_saving" style="color:red">{{ $discount }}</span>
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

                                    <li style="display: none;">
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
                                    </li>
                                </ul>
                            </div>
                                <?php }
                            }
                            ?>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>