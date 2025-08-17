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
            <h4 class="card-title">{{ $title }}</h4>

            <form id="affiliate-form" action="{{ admin_url('store_affiliate') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6 pt-4">
                        <div class="form-group">
                            <label for="name">{{ trans('admin.full_name') }} <sup
                            style="color:red">*</sup></label>
                            <input type="text" class="form-control" name="name" id="name" required>
                        </div>
                    </div>
                    <div class="col-md-6 pt-4">
                        <div class="form-group">
                            <label for="email">{{ trans('admin.email') }} <sup
                            style="color:red">*</sup></label>
                            <input type="email" class="form-control" name="email" id="email" required>
                        </div>
                    </div>
                    <div class="col-md-6 pt-4">
                        <div class="form-group">
                            <label for="phone">{{ trans('admin.phone') }} <sup
                            style="color:red">*</sup></label>
                            <input type="text" class="form-control" name="phone" id="phone" required oninput="validatePhoneInput(event)">
                        </div>
                    </div>
                    <div class="col-md-6 pt-4">
                        <div class="form-group">
                            <label for="password">{{ trans('admin.password') }} <sup style="color:red">*</sup></label>
                            <div class="input-container">
                                <input type="password" class="form-control" name="password" id="password" required style="padding-right: 35px;">
                                <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 pt-4">
                        <div class="form-group">
                            <label for="password">{{ trans('admin.password_confirmation') }} <sup style="color:red">*</sup></label>
                            <div class="input-container">
                                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" required style="padding-right: 35px;">
                                <span toggle="#password_confirmation" class="fa fa-fw fa-eye field-icon toggle-password-confirmation"></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 pt-4">
                        <div class="form-group">
                            <label for="country">{{ trans('admin.Country') }} <sup style="color:red">*</sup></label>
                            <select name="country_code" id="country_code" class="select1 form-control" required>
                                <option value="">{{ trans('admin.select_country') }}</option>
                                @foreach ($country_list as $country)
                                    <option value="{{ $country->id }}"> {{ $country->name }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 pt-4">
                        <div class="form-group">
                            <label for="city">{{ trans('admin.City') }} <sup style="color:red">*</sup></label>
                            <select name="city" id="city_code" class="select1 form-control" required>
                                <option value=""></option>
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