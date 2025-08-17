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
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"> {{ $title }}</h4>

            <form id="affiliate-form" action="{{ admin_url('update_affiliate/'.$affiliateUser->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <div class="form-group">
                            <label for="name">{{ trans('admin.full_name') }} <sup style="color:red">*</sup></label>
                            <input type="text" class="form-control" name="name" id="name" required value="{{ $affiliateUser->name }}">
                        </div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="form-group">
                            <label for="email">{{ trans('admin.email') }} <sup style="color:red">*</sup></label>
                            <input type="text" class="form-control" name="email" id="email" required value="{{ $affiliateUser->email }}">
                        </div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="form-group">
                            <label for="phone">{{ trans('admin.phone') }} <sup style="color:red">*</sup></label>
                            <input type="text" class="form-control" name="phone" id="phone" required value="{{ $affiliateUser->phone }}" oninput="validatePhoneInput(event)">
                        </div>
                    </div>

                    <div class="col-md-6 mb-2">
                        <div class="form-group">
                            <label for="country">{{ trans('admin.Country') }} <sup style="color:red">*</sup></label>
                            <select name="country_code" id="country_code" style="width: 100%"
                            class="form-control select1">
                            <option value=""></option>
                            <?php if (!empty($countryList)) {
                            foreach ($countryList as $value) {
                            ?>
                                <option value="{{ $value->code }}" <?php if (!empty($affiliateUser->country_code) && $affiliateUser->country_code == $value->code) {
                                    echo 'selected';
                                } ?>>
                                    <?php echo $value->name; ?></option>
                                <?php }
                            } ?>
                        </select>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="form-group">
                            <label for="country">{{ trans('admin.City') }} <sup style="color:red">*</sup></label>
                            <select name="city" id="city_code" style="width: 100%"
                            class="form-control select1">
                            <option value=""></option>
                            <?php if (!empty($city)) {
                            foreach ($city as $value) {
                            ?>
                                <option value="{{ $value->id }}" <?php if (!empty($affiliateUser->city) && $affiliateUser->city == $value->id) {
                                    echo 'selected';
                                } ?>>
                                    <?php echo $value->name; ?></option>
                                <?php }
                            } ?>
                        </select>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="form-group">
                            <label for="password">{{ trans('admin.password') }}</label>
                            <div class="input-container">
                                <input type="password" class="form-control" name="password" id="password" style="padding-right: 35px;">
                                <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="form-group">
                            <label for="password">{{ trans('admin.password_confirmation') }}</label>
                            <div class="input-container">
                                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" style="padding-right: 35px;">
                                <span toggle="#password_confirmation" class="fa fa-fw fa-eye field-icon toggle-password-confirmation"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
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
</script>
@endsection