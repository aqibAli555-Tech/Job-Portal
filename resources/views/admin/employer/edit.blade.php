@extends('admin.layouts.master')
@section('content')
<div class="row">
    <div class="col-12">
    	<form action="<?php echo admin_url('update_employer'); ?>" accept-charset="UTF-8" method="post" enctype="multipart/form-data" id="update_employee">
        <div class="card">
            <div class="card-body">
            	<h4 class="card-title">{{ trans('admin.Edit Employer') }}</h4>
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
                            } ?>>
                                {{ trans('admin.Job seeker') }}
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
                        <label>{{ trans('admin.Phone') }}</label>
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text"><i
                                        class="ti-mobile"></i></span></div>
                            <input type="text" name="phone" value="<?php if (!empty($user->phone)) {
                                echo $user->phone;
                            } ?>"
                                placeholder="{{ trans('admin.Phone') }}" class="form-control">
                        </div>
                    </div>
                </div>
				<div class="row">                    
                    <div class="form-group col-md-6 pt-4">
                        <label>{{ trans('admin.Country') }}</label>
                        <select name="country_code" id="country_code" style="width: 100%"
                            class="form-control select2_field">
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
                    <div class="form-group col-md-6 pt-4">
                        <label>{{ trans('admin.City') }}</label>
                        <select name="city_id" id="city_name" style="width: 100%"
                            class="form-control select2_field">
                            <?php if (!empty($city)) {
	                            foreach ($city as $value) {
	                                ?>
	                                <option value="{{ $value->id }}" <?php if (!empty($user->city_id == $value->id)) {
	                                    echo 'selected';
	                                } ?>>
	                                    <?php echo $value->name; ?></option>
	                                <?php }
	                        } ?>
	                            </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6" style="margin-top: 20px;">
                        <div class="checkbox">
                            <label>
                                <input type="hidden" name="add_feature" value="0">
                                <input type="checkbox" value="1" name="add_feature"
                                    <?php if (!empty($user->add_feature) && $user->add_feature == 1) {
                                        echo 'checked';
                                    } ?>> {{ trans('admin.Featured') }}
                            </label>


                        </div>
                    </div>
                </div>
                <input type="hidden" name="id" value="<?php if (!empty($user->id)) {
                    echo $user->id;
                } ?>">
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
                    <a href="<?php echo admin_url('employer'); ?>" class="btn btn-secondary shadow"><span
                            class="fa fa-ban"></span> &nbsp;Cancel</a>
                </div>
            </div>
        </div>
        </form>
    </div>
    @includeFirst([
        config('larapen.core.customizedViewPath') . 'layouts.inc.social.horizontal',
        'layouts.inc.social.horizontal',
    ])
</div>
@endsection
@section('after_scripts')
<script>
    $('#category_id').select2();
    	$(document).ready(function() {
    });
</script>
<script>
    $(document).ready(function() {
        $('#country_code').on('change', function() {
            var country_name = $(this).val();
            $.ajax({
                url: "{{ route('country_change') }}",
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
    });
</script>
@endsection