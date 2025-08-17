@extends('affiliate.layouts.master')
@section('content')
    @include('common.spacer')

    <div class="main-container">
        <div class="container">
            @include('affiliate/inc/breadcrumbs')
            <div class="row">
                <div class="col-md-3 page-sidebar">
                    @include('affiliate.inc.sidebar')
                </div>
                <div class="col-md-8 page-content">
                    @include('flash::message')
                    @if (isset($errors) and $errors->any())
                    <?php $errorMessage = '' ?>
                        @if(!empty($errorMessage))
                            <script>
                                Swal.fire({
                                    html: '<?= $errorMessage ?>',
                                    icon: "error",
                                    confirmButtonText: "<u>Ok</u>",
                                });
                            </script>
                        @endif
                    @endif

                    <div class="inner-box">
                    <h2 class=""><i class=""></i> {{ t('bank_details') }}</h2>
                        <p style="background: #615583;color: #fff;padding: 5px;text-align: left;float: right;">
                            {!! t('All payments are paid out through PayPal') !!}
                        </p>
                        <hr style="clear: both;">
                        <div id="accordion" class="panel-group">
                            <div class="card card-default">
                                <div class="card-body">
                                    
                                    <form name="details" method="POST" action="{{ url('/affiliate/bank_details') }}" enctype="multipart/form-data" class="dashboard-form">
                                        {!! csrf_field() !!}
                                        <div class="form-group row required">
                                            <label class="col-md-3 col-form-label">{{ t('service_type') }} <sup>*</sup></label>
                                            <div class="col-md-9">
                                                <select id="service_type" class="form-control" name="service_type" required>
                                                    <option value="">{{ t('select_service_type') }}</option>
                                                    @foreach(App\Helpers\Helper::getPaymentServices() as $value => $name)
                                                        <option value="{{ $value }}" {{ $value === 'paypal' ? 'selected' : '' }}>{{ $name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div id="paypalFields" style="display: none;">
                                            <div class="form-group row">
                                                <label class="col-md-3 col-form-label">{{ t('Email') }} <sup>*</sup></label>
                                                <div class="col-md-9">
                                                    <input name="email" id="email" type="email" class="form-control" placeholder="{{ t('Email') }}" value="{{ old('email', $data->email ?? '') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="bankFields" style="display: none;">
                                            <div class="form-group row">
                                                <label class="col-md-3 col-form-label">{{ t('beneficiary_name') }} <sup>*</sup></label>
                                                <div class="col-md-9">
                                                    <input name="beneficiary_name" id="beneficiary_name" type="text" class="form-control" placeholder="{{ t('beneficiary_name') }}" value="{{ old('beneficiary_name', $data->beneficiary_name ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 col-form-label">{{ t('Address') }} <sup>*</sup></label>
                                                <div class="col-md-9">
                                                    <input name="address" id="address" type="text" class="form-control" placeholder="{{ t('Address') }}" value="{{ old('address', $data->address ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 col-form-label">{{ t('Country') }} <sup>*</sup></label>
                                                <div class="col-md-9">
                                                    <select id="countryCode1" class="form-control select1" name="country_code" onchange="get_cities()">
                                                        <option value="">{{ t('Select Country') }}</option>
                                                        @foreach ($data['countries'] as $item)
                                                            <option value="{{ $item['code'] }}" {{ !empty($data->country_code) && $data->country_code == $item['code'] ? 'selected' : '' }}>
                                                                {{ $item['name'] }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 col-form-label">{{ t('City') }} <sup>*</sup></label>
                                                <div class="col-md-9">
                                                    <select id="cityId1" class="form-control select1" name="city">
                                                        <option value="">{{ t('select city') }}</option>
                                                        @foreach ($data['cities'] as $item)
                                                            <option value="{{ $item->id }}" {{ !empty($data->city) && $data->city == $item->id ? 'selected' : '' }}>
                                                                {{ $item->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>                                            
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 col-form-label">{{ t('state') }} <sup>*</sup></label>
                                                <div class="col-md-9">
                                                    <input name="state" id="state" type="text" class="form-control" placeholder="{{ t('state') }}" value="{{ old('state', $data->state ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 col-form-label">{{ t('IBAN') }} <sup>*</sup></label>
                                                <div class="col-md-9">
                                                    <input name="IBAN" id="IBAN" type="text" class="form-control" placeholder="{{ t('IBAN') }}" value="{{ old('IBAN', $data->IBAN ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 col-form-label">{{ t('bank_name') }} <sup>*</sup></label>
                                                <div class="col-md-9">
                                                    <input name="bank_name" id="bank_name" type="text" class="form-control" placeholder="{{ t('bank_name') }}" value="{{ old('bank_name', $data->bank_name ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 col-form-label">{{ t('bank_address') }} <sup>*</sup></label>
                                                <div class="col-md-9">
                                                    <input name="bank_address" id="bank_address" type="text" class="form-control" placeholder="{{ t('bank_address') }}" value="{{ old('bank_address', $data->bank_address ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 col-form-label">{{ t('swift_code') }} <sup>*</sup></label>
                                                <div class="col-md-9">
                                                    <input name="swift_code" id="swift_code" type="text" class="form-control" placeholder="{{ t('swift_code') }}" value="{{ old('swift_code', $data->swift_code ?? '') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 text-right">
                                            <button type="submit" onchange="this.form.submit()" class="btn btn-primary">{{ t('Save') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    @include('affiliate.inc.email_settings')
                    <br>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('after_scripts')
<script>
    function get_cities() {

        var countryCode = $('#countryCode1').val();
        var url = '{{url("/ajax/get_city_by_country/")}}/' + countryCode

        $.ajax({
            type: "GET",
            url: url,
            dataType: 'json',
            beforeSend: function () {
                $("#cityId1").empty();
            },
            success: function (c) {
                $("#cityId1").empty().append('<option value="">{{ t("select city") }}</option>');
                $(c.items).each(function (key, value) {
                    var name = value.text.split(',')[0];
                    var selectedCity = '{{ $data["city_id"] ?? "" }}';
                    var selected = value.id == selectedCity ? 'selected' : '';
                    $("#cityId1").append("<option value='" + value.id + "' " + selected + ">" + name + "</option>");
                });
            },
        });
    }

    $(document).ready(function () {
        togglePaymentFields();

        $('#service_type').on('change', function () {
            togglePaymentFields();
        });
    });
    
    const paypalEmail = document.getElementById("email");
    const beneficiaryName = document.getElementById("beneficiary_name");
    const address = document.getElementById("address");
    const countryCode1 = document.getElementById("countryCode1");
    const cityId1 = document.getElementById("cityId1");
    const state = document.getElementById("state");
    const IBAN = document.getElementById("IBAN");
    const bankName = document.getElementById("bank_name");
    const bankAddress = document.getElementById("bank_address");
    const swiftCode = document.getElementById("swift_code");

    function togglePaymentFields() {
        let type = $('#service_type').val();
        if (type === 'paypal') {
            $('#paypalFields').show();
            $('#bankFields').hide();
            paypalEmail.setAttribute("required", "required");
            beneficiaryName.removeAttribute("required");
            address.removeAttribute("required");
            countryCode1.removeAttribute("required");
            cityId1.removeAttribute("required");
            state.removeAttribute("required");
            IBAN.removeAttribute("required");
            bankName.removeAttribute("required");
            bankAddress.removeAttribute("required");
            swiftCode.removeAttribute("required");
        } else if (type === 'bank') {
            $('#paypalFields').hide();
            $('#bankFields').show();
            paypalEmail.removeAttribute("required");
            beneficiaryName.setAttribute("required", "required");
            address.setAttribute("required", "required");
            countryCode1.setAttribute("required", "required");
            cityId1.setAttribute("required", "required");
            state.setAttribute("required", "required");
            IBAN.setAttribute("required", "required");
            bankName.setAttribute("required", "required");
            bankAddress.setAttribute("required", "required");
            swiftCode.setAttribute("required", "required");
        } else {
            $('#paypalFields').hide();
            $('#bankFields').hide();
        }
    }
</script>
@endsection

