@extends('admin.layouts.master')
@section('content')
<style>
    .input-container {
        position: relative;
    }

    .field-icon {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        z-index: 2;
    }

    .form-control {
        padding-right: 35px;
    }
</style>
    @if (Session::has('flash_notification'))
            <div class="col-xl-12">
                @include('flash::message')
            </div>
    @endif
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"> {{ $title }}</h4>
            <form id="bank-detail-form" action="{{ admin_url('edit_bank_detail') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-12 pt-4">
                        <div class="form-group">
                            <label for="service_type">{{ trans('admin.service_type') }} <sup style="color:red">*</sup></label>
                            <select name="service_type" style="width: 100%" id="service_type"
                                class="form-control" required>
                                <option value="">{{ trans('admin.select_service_type') }}</option>
                                @foreach(App\Helpers\Helper::getPaymentServices() as $value => $name)
                                    <option value="{{ $value }}" {{ $value === 'paypal' ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 pt-4" id="paypalFields" style="display: none;">
                        <div class="form-group">
                            <label for="email">{{ trans('admin.email') }} <sup style="color:red">*</sup></label>
                            <input type="email" class="form-control" name="email" id="email" required value="{{ old('email', $bank_detail->email ?? '') }}" placeholder="{{ trans('admin.email') }}">
                        </div>
                    </div>
                    <div class="col-12" id="bankFields" style="display: none;">
                        <div class="row">
                            <div class="col-md-6 pt-4">
                                <div class="form-group">
                                    <label for="beneficiary_name">{{ trans('admin.beneficiary_name') }} <sup style="color:red">*</sup></label>
                                    <input type="text" class="form-control" name="beneficiary_name" id="beneficiary_name" required value="{{ old('beneficiary_name', $bank_detail->beneficiary_name ?? '') }}" placeholder="{{ trans('admin.beneficiary_name') }}">
                                </div>
                            </div>
                            <div class="col-md-6 pt-4">
                                <div class="form-group">
                                    <label for="address">{{ trans('admin.Address') }} <sup style="color:red">*</sup></label>
                                    <input type="text" class="form-control" value="{{ old('address', $bank_detail->address ?? '') }}" name="address" id="address" placeholder="{{ trans('admin.Address') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6 pt-4">
                                <div class="form-group">
                                    <label for="country">{{ trans('admin.Country') }} <sup style="color:red">*</sup></label>
                                    <select name="country_code" style="width: 100%" id="country_code"
                                        class="form-control select1" required>
                                        <option value="">{{ trans('admin.select_country') }}</option>
                                        <?php if (!empty($countryList)) {
                                        foreach ($countryList as $value) {
                                        ?>
                                            <option value="{{ $value->code }}" <?php if (!empty($bank_detail->country_code) && $bank_detail->country_code == $value->code) {
                                                echo 'selected';
                                            } ?>>
                                                <?php echo $value->name; ?></option>
                                            <?php }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 pt-4">
                                <div class="form-group">
                                    <label for="city">{{ trans('admin.City') }} <sup style="color:red">*</sup></label>
                                    <select name="city" id="city_code" style="width: 100%"
                                        class="form-control select1" required>
                                        <option value="">{{ trans('admin.select_city') }}</option>
                                        <?php if (!empty($city)) {
                                        foreach ($city as $value) {
                                        ?>
                                            <option value="{{ $value->id }}" <?php if (!empty($bank_detail->city) && $bank_detail->city == $value->id) {
                                                echo 'selected';
                                            } ?>>
                                                <?php echo $value->name; ?></option>
                                            <?php }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 pt-4">
                                <div class="form-group">
                                    <label for="state">{{ trans('admin.state') }} <sup style="color:red">*</sup></label>
                                    <input type="text" class="form-control" value="{{ old('state', $bank_detail->state ?? '') }}" name="state" id="state" placeholder="{{ trans('admin.state') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6 pt-4">
                                <div class="form-group">
                                    <label for="IBAN">{{ trans('admin.IBAN') }} <sup style="color:red">*</sup></label>
                                    <input type="text" class="form-control" name="IBAN" id="IBAN"  required value="{{ old('IBAN', $bank_detail->IBAN ?? '') }}" placeholder="{{ trans('IBAN') }}">
                                </div>
                            </div>
                            <div class="col-md-6 pt-4">
                                <div class="form-group">
                                    <label for="bank_name">{{ trans('admin.bank_name') }} <sup style="color:red">*</sup></label>
                                    <input type="text" class="form-control" value="{{ old('bank_name', $bank_detail->bank_name ?? '') }}" name="bank_name" id="bank_name" placeholder="{{ trans('admin.bank_name') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6 pt-4">
                                <div class="form-group">
                                    <label for="bank_address">{{ trans('admin.bank_address') }} <sup style="color:red">*</sup></label>
                                    <input type="text" class="form-control" value="{{ old('bank_address', $bank_detail->bank_address ?? '') }}" name="bank_address" id="bank_address" placeholder="{{ trans('admin.bank_address') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6 pt-4">
                                <div class="form-group">
                                    <label for="swift_code">{{ trans('admin.swift_code') }} <sup style="color:red">*</sup></label>
                                    <input type="text" class="form-control" value="{{ old('swift_code', $bank_detail->swift_code ?? '') }}" name="swift_code" id="swift_code" placeholder="{{ trans('admin.swift_code') }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="bank_detail_user_id" name="bank_detail_user_id" value="{{$user_id}}">
                <div class="d-flex justify-content-end mt-2">
                    <button type="submit" class="btn btn-primary">{{ trans('admin.update') }}</button>
                </div>

            </form>
        </div>
    </div>
@endsection

@section('after_scripts')
<script>
    $('#country_code').on('change', function() {
        var country_name = $(this).val();

        $.ajax({
            url: "{{ route('city_change') }}",
            method: "POST",
            dataType: 'json',
            data: {
                'country_name': country_name
            },
            success: function(city) {
                $('#city_code').html('');
                $('#city_code').html('<option value="">-- Select City --</option>');
                $.each(city, function(key, value) {
                    var name = value.slug.split(',')[0];
                    name = name.charAt(0).toUpperCase() + name.slice(1);
                    $("#city_code").append('<option value="' + value.id +
                        '" >' + name + '</option>');

                });
            }
        })
    });
    $(document).ready(function () {
        togglePaymentFields();

        $('#service_type').on('change', function () {
            togglePaymentFields();
        });
    });

    const paypalEmail = document.getElementById("email");
    const beneficiaryName = document.getElementById("beneficiary_name");
    const address = document.getElementById("address");
    const countryCode1 = document.getElementById("country_code");
    const cityId1 = document.getElementById("city_code");
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