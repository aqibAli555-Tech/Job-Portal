@extends('admin.layouts.master')

@section('content')

<div class="card">
    <div class="card-body">
        <h4 class="card-title">{{ $title }}</h4>
        @if (Session::has('flash_notification'))
            <div class="col-xl-12">
                @include('flash::message')
            </div>
        @endif
        <form id="affiliate-settings-form" action="{{admin_url('affiliate_settings')}}" method="post">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-2">
                    <div class="form-group">
                        <label for="name">{{ trans('admin.package_discount_value') }}</label>
                        <input type="text" id="package_discount_value" name="package_discount_value" class="form-control" placeholder="{{ isset($affiliate_settings) ? '' : 'Enter Package Discount' }}" 
                        value="{{ isset($affiliate_settings) ? $affiliate_settings->package_discount_value : '' }}" required oninput="numericInput(event)">
                    </div>
                </div>
                <div class="col-md-6 mb-2">
                    <div class="form-group">
                        <label for="package_discount_type">{{ trans('admin.package_discount_type') }}</label>
                        <select name="package_discount_type" id="package_discount_type" class="form-control" required>
                            <option value="">Select Type</option>
                            <option value="percentage" {{ isset($affiliate_settings) && $affiliate_settings->package_discount_type === 'percentage' ? 'selected' : '' }}>Percentage</option>
                            <option value="fixed" {{ isset($affiliate_settings) && $affiliate_settings->package_discount_type === 'fixed' ? 'selected' : '' }}>Fixed</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 mb-2">
                    <div class="form-group">
                        <label for="affiliate_to_affiliate_commission_value">{{ trans('admin.affiliate_to_affiliate_commission') }}</label>
                        <input type="text" id="affiliate_to_affiliate_commission_value" name="affiliate_to_affiliate_commission_value" class="form-control" placeholder="{{ isset($affiliate_settings) ? '' : 'Affiliate To Affiliate Commission' }}" 
                        value="{{ isset($affiliate_settings) ? $affiliate_settings->affiliate_to_affiliate_commission_value : '' }}" required oninput="numericInput(event)">
                    </div>
                </div>
                <div class="col-md-6 mb-2">
                    <div class="form-group">
                        <label for="affiliate_to_affiliate_commission_type">{{ trans('admin.affiliate_to_affiliate_commission_type') }}</label>
                        <select name="affiliate_to_affiliate_commission_type" id="affiliate_to_affiliate_commission_type" class="form-control" required>
                            <option value="">Select Type</option>
                            <option value="percentage" {{ isset($affiliate_settings) && $affiliate_settings->affiliate_to_affiliate_commission_type === 'percentage' ? 'selected' : '' }}>Percentage</option>
                            <option value="fixed" {{ isset($affiliate_settings) && $affiliate_settings->affiliate_to_affiliate_commission_type === 'fixed' ? 'selected' : '' }}>Fixed</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">{{ trans('admin.save') }}</button>
            </div>
        </form>
    </div>
</div>
    

@endsection

@section('after_scripts')
<script>
    document.getElementById('affiliate-settings-form').addEventListener('submit', function(event) {
        var packageDiscountValue = document.getElementById('package_discount_value').value;
        var affiliateCommissionValue = document.getElementById('affiliate_to_affiliate_commission_value').value;

        if (packageDiscountValue == '0') {
            event.preventDefault();
            Swal.fire('Error!', 'Package discount must not be 0.', 'error');
        }
        if (affiliateCommissionValue == '0') {
            event.preventDefault();
            Swal.fire('Error!', 'Affiliate to affiliate commission must not be 0.', 'error');
        }
    });
    function numericInput(event) {
        const input = event.target;
        const value = input.value;
        const cleanedValue = value.replace(/[^0-9]/g, '');
        input.value = cleanedValue;
    }
</script>
@endsection