@extends('admin.layouts.master')

@section('content')


<div class="row">
    @if (Session::has('flash_notification'))
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="col-xl-12">
                    @include('flash::message')
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="col-12">
        <div class="card">
            @if (isset($user))
            <form action="<?php echo admin_url('update_employee'); ?>" accept-charset="UTF-8" method="post" enctype="multipart/form-data" id="update_employee">
            <div class="card-body">
                <h4 class="card-title">{{ trans('admin.Edit Employee') }}</h4>
                @csrf
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>{{ trans('admin.Type') }}</label>
                        <select name="user_type_id" style="width: 100%"
                            class="form-control select2_from_array">
                            <option value="1" <?php if (!empty($user->user_type_id) && $user->user_type_id == 1) {
                                echo 'selected';
                            } ?>>{{ trans('admin.Employer') }}
                            </option>
                            <option value="2" <?php if (!empty($user->user_type_id) && $user->user_type_id == 2) {
                                echo 'selected';
                            } ?>>{{ trans('admin.Job seeker') }}
                            </option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label>{{ trans('admin.Email') }}</label>
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text"><i
                                        class="ti-email"></i></span></div>
                            <input type="email" name="email" value="<?php if (!empty($user->email)) {
                                echo $user->email;
                            } ?>"
                                placeholder="{{ trans('admin.Email') }}" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6 pt-4">
                        <label>{{ trans('admin.Name') }}</label>
                        <input type="text" name="name" value="<?php if (!empty($user->name)) {
                            echo $user->name;
                        } ?>"
                            placeholder="{{ trans('admin.Name') }}" class="form-control">
                    </div>

                    <div class="form-group col-md-6 pt-4">
                        <label for="skill_set">{{ t('skill Set') }}
                            <sup>*</sup>
                        </label>
                        <?php $skill_set = explode(',', $user->skill_set); ?>
                        <select class="form-control select1" multiple name="skill_set[]" max="5"
                            required>
                            @foreach ($employee_skills as $item)
                                <?php $skill = rtrim($item->skill); ?>
                                <option value="{{ $item->skill }}" <?php if (in_array($skill, $skill_set)) {
                                    echo 'selected';
                                } ?>>
                                    {{ $item->skill }}
                                </option>
                            @endforeach
                        </select>

                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6 pt-4">
                        <label for="email">{{ t('availability') }}
                            <sup>*</sup>
                        </label>

                        <select class="form-control select1 for_employee_input" name="availability"
                            required>
                            @if ($availability)
                                @foreach ($availability as $item)
                                    <option value="{{ $item->id }}"
                                        {{ !empty($user->availability) && $user->availability == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>

                    </div>

                    <div class="form-group col-md-6 pt-4">
                        <label for="nationality">{{ t('Nationality') }}
                            <sup>*</sup></label>

                        <select class="form-control for_employee_input select1" name="nationality"
                            required>
                            @if ($nationality)
                                @foreach ($nationality as $key => $value)
                                    @if ($key !== 'Any')
                                        <option value="{{ $value }}"
                                            {{ !empty($user->nationality) && $user->nationality == $value ? 'selected' : '' }}>
                                            {{ $key }}
                                        </option>
                                    @endif
                                @endforeach
                            @endif
                        </select>

                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6 pt-4">
                        <label for="experience">{{ t('Work Experience') }} <sup
                                style="color:red">*</sup></label>

                        <select id="experience" name="experiences" class="form-control select1 "
                            required>
                            <option value="0-1 years"
                                {{ !empty($user->experiences) && $user->experiences == '0-1 years' ? 'selected' : '' }}>
                                {{ t('0-1 years') }}
                            </option>
                            <option value="1-3 years"
                                {{ !empty($user->experiences) && $user->experiences == '1-3 years' ? 'selected' : '' }}>
                                {{ t('1-3 years') }}
                            </option>
                            <option value="3-5 years"
                                {{ !empty($user->experiences) && $user->experiences == '3-5 years' ? 'selected' : '' }}>
                                {{ t('3-5 years') }}
                            </option>
                            <option value="5-10 years"
                                {{ !empty($user->experiences) && $user->experiences == '5-10 years' ? 'selected' : '' }}>
                                {{ t('5-10 years') }}
                            </option>
                            <option value="10-20 years"
                                {{ !empty($user->experiences) && $user->experiences == '10-20 years' ? 'selected' : '' }}>
                                {{ t('10-20 years') }}
                            </option>
                            <option value="20+ years"
                                {{ !empty($user->experiences) && $user->experiences == '20+ years' ? 'selected' : '' }}>
                                {{ t('20+ years') }}
                            </option>
                        </select>

                    </div>

                    <div class="form-group col-md-6 pt-4">
                        <label>{{ trans('admin.Country') }}</label>
                        <select name="country_code" id="country_code" style="width: 100%"
                            class="form-control select1">
                            <option value="">-</option>
                            <?php if (!empty($country)) {
                            foreach ($country as $value) {
                        ?>
                            <option value="{{ $value->code }}" <?php if (!empty($user->country_code) && $user->country_code == $value->code) {
                                echo 'selected';
                            } ?>>
                                <?php echo $value->name; ?></option>
                            <?php }
                        } ?>
                        </select>
                    </div>
                </div>
                <div class="row">

                    <div class="form-group col-md-6 pt-4" id="visa_div" style="display: none">
                        <label for="visa">{{ t('Work Visa') }} <sup
                                style="color:red">*</sup></label>
                        <select id="work_visa" name="visa" class="form-control select1 work_visa"
                            onchange="show_visa_box(this)">
                            <option value="">{{ t('Choose an Option') }}
                            </option>
                            <option value="No, I don’t NEED a visa"
                                {{ !empty($user->visa) && in_array($user->visa, ['No, I dont NEED a visa', "No, I don't NEED a visa"]) ? 'selected' : '' }}>
                                {{ t('No, I don’t NEED a visa') }}</option>
                            <option value="No, I don’t HAVE a visa"
                                {{ !empty($user->visa) && in_array($user->visa, ['No, I dont HAVE a visa', "No, I don't HAVE a visa"]) ? 'selected' : '' }}>
                                {{ t('No, I don’t HAVE a visa') }}</option>
                            <option value="Yes, I HAVE a visa"
                                {{ !empty($user->visa) && $user->visa == 'Yes, I HAVE a visa' ? 'selected' : '' }}>
                                {{ t('Yes, I HAVE a visa') }}</option>

                        </select>


                    </div>
                    <div class="form-group col-md-6 pt-4 country_of_work_visa_div"
                        id="country_of_work_visa_div" style="display:none"">
                        <label for="country_of_work_visa_div">{{ t('Country of Work Visa') }}
                            <sup style="color:red">*</sup></label>

                        <select class="form-control select1" name="country_work_visa"
                            id="country_work_visa" onchange="work_visa_country(this)">

                            @if (!empty($country))
                                <option value="">Choose a Country</option>
                                @foreach ($country as $item)
                                    @if($item->code=="KW")
                                    <option value="{{ $item->code }}"
                                        {{ !empty($user->country_work_visa) && $user->country_work_visa == $item->code ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                    @endif
                                @endforeach
                            @endif
                        </select>

                    </div>
                    <div class="form-group col-md-6 pt-4 visa_number_div" id="visa_number_div"
                        style="display:none">
                        <label for="visa">{{ t('Work Visa Type') }} <sup
                                style="color:red">*</sup></label>

                        <select id="work_visa_number" name="visa_number" class="form-control select1">
                            <option value="">{{ t('Choose an Option') }}
                            </option>
                            <?php foreach ($visa_types as $visa) : ?>
                            <?php foreach ($visa as $value => $label) : ?>
                            <option value="<?php echo $value; ?>"
                                {{ !empty($user->visa_number) && $user->visa_number == $value ? 'selected' : '' }}>
                                <?php echo $label; ?></option>
                            <?php endforeach; ?>
                            <?php endforeach; ?>




                        </select>

                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6 pt-4">
                        <label>{{ trans('admin.City') }}</label>
                        <select name="city_code" id="city_name" style="width: 100%"
                            class="form-control select1" required>
                            <option value="">-</option>
                            <?php if (!empty($city)) {
                            foreach ($city as $value) {
                        ?>
                            <option value="{{ $value->id }}" <?php if (!empty($user->city == $value->id)) {
                                echo 'selected';
                            } ?>>
                                <?php echo $value->slug; ?></option>
                            <?php }
                        } ?>
                        </select>
                    </div>
                    <input type="hidden" name="id" value="<?php if (!empty($user->id)) {
                        echo $user->id;
                    } ?>">
                </div>
            </div>
            <div class="card-footer">
                <div id="saveActions" class="form-group">
                    <input type="hidden" name="save_action" value="save_and_back">
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary shadow">
                            <span class="fa fa-save" role="presentation" aria-hidden="true"></span>
                            &nbsp;
                            <span data-value="save_and_back">Save and back</span>
                        </button>
                    </div>
                    <a href="<?php echo admin_url('get_employee'); ?>" class="btn btn-secondary shadow"><span
                            class="fa fa-ban"></span> &nbsp;Cancel</a>
                </div>
            </div>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection

@section('after_scripts')
<script>
    $(document).ready(function() {
        var visa = $('#work_visa').val();
        var country_work_visa = $('#country_work_visa').val();
        if (country_work_visa == 'KW') {
            $('.visa_number_div').show();
        } else {
            $('.visa_number_div').hide();
        }
        if (visa == 'Yes, I HAVE a visa') {
            $('.country_of_work_visa_div').show();
        } else {
            $('.country_of_work_visa_div').hide();
        }
        show_hide_visa_options("{{ $user->country_code}}");
    });

    $('#country_code').on('change', function() {
            var country_name = $(this).val();
            show_hide_visa_options(country_name);

            $.ajax({
                url: "{{ route('city_change') }}",
                method: "POST",
                dataType: 'json',
                data: {
                    'country_name': country_name
                },
                success: function(city) {
                    $('#city_name').html('');
                    $('#city_name').html('<option value="">-- Select City --</option>');
                    $.each(city, function(key, value) {
                        $("#city_name").append('<option value="' + value.id +
                            '" >' + value.slug + '</option>');

                    });
                }
            })
        });

    function show_visa_box(obj) {
        removeRequired();
        $('#work_visa_number').prop('required', false);
        var visa = $(obj).val();
        if (visa == 'Yes, I HAVE a visa') {
            $('.country_of_work_visa_div').show();
            // $('#country_work_visa').prop('required', true);
            addRequired();

        } else {

            $('.country_of_work_visa_div').hide();
            // $('#country_work_visa').prop('required', false);
            $('.visa_number_div').hide();
            removeRequired();

        }
    }


    function work_visa_country(obj){
    var country = $(obj).val();
        if (country == 'KW') {
            $('.visa_number_div').show();
            $('#work_visa_number').prop('required', true);
        } else {
            $('.visa_number_div').hide();
            $('#work_visa_number').prop('required', false);
            $('#work_visa_number').val('').trigger('change');
        }
    }

// Function to add the "required" attribute to input elements
function addRequired() {
    $('#country_work_visa').prop('required', true);
}

// Function to remove the "required" attribute from input elements
function removeRequired() {
    $('#country_work_visa').prop('required', false);
    $('#country_work_visa').val('').trigger('change');
    $('#work_visa_number').val('').trigger('change');
}


function show_hide_visa_options(countryCode) {
        if(countryCode == 'KW'){
            $('#visa_div').show();
            $('.work_visa').prop('required', true);
        }else{
            $('#visa_div').hide();
            $('.country_of_work_visa_div').hide();
            $('.visa_number_div').hide();
            $('.country_work_visa').prop('required', false);
            $('.work_visa').prop('required', false);
            $('.work_visa').val(null).trigger('change');
            $('.country_work_visa').val('').trigger('change');
            $('.work_visa_number').val('').trigger('change');
        }
    }

</script>
@endsection
