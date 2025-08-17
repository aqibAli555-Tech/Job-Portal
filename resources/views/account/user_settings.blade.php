@extends('layouts.master')
@section('content')
    @include('common.spacer')

    <div class="main-container">
        <div class="container">
            @include('account/inc/breadcrumbs')
            <div class="row">
                <div class="col-md-3 page-sidebar">
                    @include('account.inc.sidebar')
                </div>
                <div class="col-md-9 page-content">
                    @include('flash::message')
                    @if (isset($errors) and $errors->any())
                            <?php $errorMessage = '' ?>
                        <div class="col-xl-12" style="display:none;">
                            <div class="alert alert-danger">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;
                                </button>
                                <h5><strong>{{ t('oops_an_error_has_occurred') }}</strong></h5>
                                <ul class="list list-check">
                                    @foreach ($errors->all() as $error)
                                            <?php $errorMessage .= "<li>" . $error . "</li>" ?>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
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
                    <div class="inner-box default-inner-box">
                        
                        <div id="accordion" class="panel-group">
                            <!-- USER -->
                             <p style="background: #615583;color: #fff; padding: 5px;">
                                {!! t('You will receive new job post alerts on your verified WhatsApp number') !!}
                            </p>
                            <div class="card card-default">
                                <div class="card-header">
                                    <h4 class="card-title" data-toggle="collapse" data-parent="#accordion">
                                        {{ t('Settings') }}
                                    </h4>
                                </div>
                                <div class="panel-collapse collapse {{ (old('panel')=='' or old('panel')=='userPanel') ? 'show' : '' }}"
                                     id="userPanel">
                                    <div class="card-body">
                                        <form name="details" id="updateprofile" method="POST"
                                              action="{{ url('/account/user_setting_update') }}"
                                              enctype="multipart/form-data"
                                              class="dashboard-form">
                                            {!! csrf_field() !!}

                                        <div class="form-group row required">
                                            <label for="Whatsapp Phone Number" class="col-md-3 col-form-label">
                                                {{ t('Whatsapp Phone Number') }}
                                            </label>
                                            <div class="input-group col-md-9">
                                                @php 
                                                    use Illuminate\Support\Str;

                                                    $countryCodeString = '+' . config('country.phone');
                                                    $savedNumber = $user_setting->whatsapp_number ?? '';
                                                    $whatsappNumber = '';

                                                    if (!empty($savedNumber) && Str::startsWith($savedNumber, $countryCodeString)) {
                                                        $whatsappNumber = $savedNumber;
                                                    }else{
                                                        $whatsappNumber = substr($savedNumber, strlen($countryCodeString));
                                                    }
                                                @endphp
                                                    <div class="input-group-prepend">
                                                        <i class="icon-phone-1 border icon-phone-profile p-2"></i>
                                                    </div>

                                                    <input name="whatsapp_number" inputmode="numeric" pattern="[0-9+,\.]+"
                                                            oninput="handleInput(this,'{{$countryCodeString}}')"
                                                            class="phone_number form-control"
                                                            placeholder="{{t('Mobile Phone Number')}}"
                                                            value="{{ old('whatsapp_number', $whatsappNumber) }}">
                                            </div>
                                        </div>  
                                        <div class="form-group row required for_employee_div">
                                            <label class="col-md-3 col-form-label" for="skill_set">{{ t('Skill set') }}
                                                <sup>*</sup>
                                            </label>
                                            <div class="input-group col-md-9">

                                                    <?php $skill_set = $user_setting ? explode(',', $user_setting->skills_set) : []; ?>
                                                <select class="skill_set" multiple name="skills_set[]" required>
                                                    @foreach($employee_skills as $item)
                                                            <?php $skill = rtrim($item->skill); ?>
                                                        <option value="{{$item->skill}}"
                                                                <?php if (in_array($skill, $skill_set)) {
                                                            echo "selected";
                                                        } ?>>
                                                            {{$item->skill}}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                        </div>
                                        <div class="col-md-9">
                                            <button type="submit" onchange="this.form.submit()" id="updatepro"
                                                    class="btn btn-primary">{{ t('Update') }}</button>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/.row-box End-->
            </div>
        </div>
        <!--/.page-content-->
    </div>
    <!-- /.main-container -->
@endsection
@section('after_scripts')
    <script>
        function handleInput(input, countryCode) {
            const inputValue = input.value;
            const countryCodeLength = countryCode.length;

            if (inputValue.length < countryCodeLength) {
                input.value = countryCode;
            } else if (!inputValue.startsWith(countryCode)) {
                input.value = countryCode + inputValue;
            }
        }
        $(document).ready(function () {
            $(".skill_set").select2({
                width: '100%',
                placeholder: '{{t("Employee Skills(you can choose more than one)")}}',
            })
        });
    </script>
@endsection