@extends('affiliate.layouts.master')
@section('content')
@includeFirst([config('larapen.core.customizedViewPath') . 'common.spacer', 'common.spacer'])
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <style>
        #signupForm .form-control {
            border: none;
            box-shadow: none;
            border-bottom: 1px solid #615583 !important;
            border-radius: 0px;
        }

        #signupBtn {
            background: #22d3fd;
            border-color: #22d3fd;
        }

        .form-control:focus {
            box-shadow: none !important;
            border: 1px solid #f1f1f1;
            outline: none 0px;
        }

        .form-control {
            border: 1px solid #f1f1f1;
        }

        #signupForm .form-control , .select2-container--default {
            border: 1px solid #f1f1f1 !important;
            border-bottom: 1px solid #f1f1f1 !important;
            border-radius: 6px;
        }

        .custom-select.is-invalid:focus, .form-control.is-invalid:focus, .was-validated .custom-select:invalid:focus, .was-validated .form-control:invalid:focus {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 .2rem rgba(220, 53, 69, .25) !important;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-5px); }
            40%, 80% { transform: translateX(5px); }
        }

        .shake {
            animation: shake 0.4s;
        }

        @media (max-width: 700px) {
            .btn-register {
                max-width: 100% !important;
            }
            .form-group {
                margin-bottom: 0 !important;
            }
        }
    </style>
<?php
$country_select = !empty(request()->get('country')) ? request()->get('country') : '';
$user_type_id_select = 5;
?>
<div class="main-container">
    <div class="container">
        <div class="row">
            @if (isset($errors) and $errors->any())
            <?php $errorMessage = ''; ?>
            @if (!empty($errorMessage))
            <script>
                Swal.fire({
                    html: '<?= $errorMessage ?>',
                    icon: "error",
                    confirmButtonText: "<u>Ok</u>",
                });
            </script>
            @endif
            @endif
            @if (Session::has('flash_notification'))
            <div class="col-xl-12">
                <div class="row">
                    <div class="col-lg-12">
                        @include('flash::message')
                    </div>
                </div>
            </div>
            @endif
            <div class="col-md-8 page-content">
                <div class="inner-box">
                    <h2 class="title-2">
                        <strong><i class="icon-user-add"></i> {{ t('Register for Affiliation Program!') }}</strong>
                    </h2>
                    <div class="row">
                        <div class="col-xl-12">
                            <form id="signupForm" class="form-horizontal" method="POST" action="{{ url('store_affiliate') }}"
                                  enctype="multipart/form-data" novalidate>
                                {!! csrf_field() !!}
                                <input type="hidden" name="user_type_id" id="user_type_id" value="<?= $user_type_id_select ?>">
                                <input name="b_value" type="hidden" id="b_value" value="">
                                <fieldset>
                                    <div id="check_user_type" style="display:none">
                                        <?php $nameError = (isset($errors) and $errors->has('name')) ? ' is-invalid' : ''; ?>            
                                        <div class="form-group row required">
                                            <label class="col-md-4 col-form-label usertypename">{{ t('Name') }}
                                                <sup>*</sup></label>
                                            <div class="col-md-6">
                                                <input name="name" id="registerName" placeholder="{{ t('Name') }}" class="form-control input-md{{ $nameError }}" type="text" required value="{{ old('name') }}">
                                            </div>
                                        </div>
                                        
                                        @if (empty(config('country.code')))
                                        <?php $countryCodeError = (isset($errors) and $errors->has('country_code')) ? ' is-invalid' : ''; ?>
                                        <div class="form-group row required">
                                            <label class="col-md-4 col-form-label{{ $countryCodeError }}" for="country_code">{{ t('Your Country') }} <sup>*</sup></label>
                                            <div class="col-md-6">
                                                <select id="countryCode" name="country_code" class="form-control sselecter{{ $countryCodeError }} global-select" required>
                                                    <option value="0" {{ (!old('country_code') or old('country_code') == 0) ? 'selected="selected"' : '' }}>{{ t('Select') }}</option>
                                                    @foreach ($countries as $code => $item)
                                                    <option value="{{ $code }}" {{ old('country_code', !empty(config('ipCountry.code')) ? config('ipCountry.code') : 0) == $code
                                                    ? 'selected="selected"'
                                                    : '' }}>
                                                    {{ $item->get('name') }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        @else
                                        <input id="countryCode" name="country_code" type="hidden" value="{{ config('country.code') }}">
                                        @endif
                                        <?php $phoneError = (isset($errors) and $errors->has('phone')) ? ' is-invalid' : ''; ?>
                                        <div class="form-group row">
                                            <label class="col-md-4 col-form-label">{{ t('Phone') }}
                                                <sup>*</sup>
                                            </label>
                                            <?php $countryCodeString = str_replace('+', '', config('country.phone')); ?>
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span id="phoneCountry" style="color:#474b51 !important" class="input-group-text">{!! getPhoneIcon(old('country', config('country.code'))) !!}
                                                            +{{ $countryCodeString }}
                                                        </span>
                                                    </div>
                                                    <input name="phone" pattern="[-+]?\d*" id="phone_number" placeholder="{{ t('Phone') }}" class="form-control input-md{{ $phoneError }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" type="text" minlength="8" value="{{ old('phone') }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <?php $emailError = (isset($errors) and $errors->has('email')) ? ' is-invalid' : ''; ?>
                                        <div class="form-group row required">
                                            <label class="col-md-4 col-form-label" for="email">{{ t('Email') }}<sup>*</sup>
                                            </label>
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="icon-mail" style="color: #474b51"></i></span>
                                                    </div>
                                                    <input id="email" name="email" type="email" required class="form-control{{ $emailError }}" placeholder="{{ t('Email') }}" value="{{ old('email') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-4 col-form-label" for="email">{{ t('city_Of_Where_You_live_Right_Now') }}
                                                <sup>*</sup>
                                            </label>
                                            <div class="col-md-6">
                                                <select class="form-control global-select" name="city" required>
                                                    @foreach ($cities as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <?php $passwordError = (isset($errors) and $errors->has('password')) ? ' is-invalid' : ''; ?>
                                        <div class="form-group row required">
                                            <label class="col-md-4 col-form-label" for="password">{{ t('Password') }}
                                                <sup>*</sup></label>
                                            <div class="col-md-6">
                                                <div class="input-group show-pwd-group">
                                                    <input id="password_1" name="password" minlength="8" type="password" class="form-control{{ $passwordError }}" placeholder="{{ t('Password') }}" autocomplete="off" required>
                                                    <span class="icon-append show-pwd">
                                                        <button type="button" class="eyeOfPwd" onclick="showPwd()">
                                                            <i id="eyeIcon" class="far fa-eye-slash" style="color:#474b51"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                                <br>
                                                <div class="input-group show-pwd-group">
                                                       <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" placeholder="{{ t('Password Confirmation') }}" autocomplete="off" minlength="8" required>
                                                       <span class="icon-append show-pwd">
                                                        <button type="button" class="eyeOfPwd" onclick="showconfirmdPwd()">
                                                            <i id="eyeIcon2" class="far fa-eye-slash" style="color:#474b51"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                                <small id="" class="form-text text-muted">
                                                    {{ t('At least X characters', ['num' => config('larapen.core.passwordLength.min', 6)]) }}
                                                </small>
                                            </div>
                                        </div>

                                        {{-- referral_code --}}
                                        @if(!empty($referral_code))
                                            <div class="form-group row">
                                                <label class="col-md-4 col-form-label"
                                                        for="email">{{ t('referred_by') }}</label>
                                                <div class="col-md-6">
                                                    <input type="hidden" class="form-control" name="referral_code"
                                                            value="{{ ($referral_code)?$referral_code:old('referral_code') }}">

                                                    <input id="refer_by" name="refer_by" type="text"
                                                            class="form-control"
                                                            placeholder="{{ t('referred_by') }}"
                                                            value="{{ ($refer_by)?$refer_by:old('refer_by') }}"
                                                            readonly>
                                                </div>
                                            </div>
                                        @endif

                                        {{-- referral_code --}}
                                        <!-- accept_terms -->
                                        <?php $acceptTermsError = (isset($errors) and $errors->has('accept_terms')) ? ' is-invalid' : ''; ?>
                                        <div class="form-group row required">
                                            <label class="col-md-4 col-form-label"></label>
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input name="accept_terms" id="acceptTerms"
                                                           class="form-check-input{{ $acceptTermsError }}" required
                                                           value="1" type="checkbox"
                                                           {{ old('accept_terms') == '1' ? 'checked="checked"' : '' }}>

                                                    <label class="form-check-label" for="acceptTerms"
                                                           style="font-weight: normal;">
                                                        {!! t('accept_terms_label', ['attributes' => getUrlPageByType('terms')]) !!}
                                                    </label>
                                                </div>
                                                <div style="clear:both"></div>
                                            </div>
                                        </div>
                                        <div class="form-group row required">
                                            <label class="col-md-4 col-form-label"></label>
                                            <div class="col-md-6">
                                                <div class="g-recaptcha" data-sitekey="{{ G_RECAPTCHA_SITE_KEY }}"></div>
            
                                                @if ($errors->has('g-recaptcha-response'))
                                                    <span class="text-danger">{{ $errors->first('g-recaptcha-response') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="form-group row">
                                            <label class="col-md-4 col-form-label"></label>
                                            <div class="col-md-8">
                                                <button id="signupBtn" class="btn btn-primary btn-lg">
                                                    <span class="btn-text">{{ t('Register') }}</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div style="margin-bottom: 30px;"></div>

                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 reg-sidebar">
                <div class="reg-sidebar-inner text-center">
                    <div class="promo-text-box"></i>
                        <img class="icon-image" src="{{url()->asset('home_icons/promote1.png')}}" style="width: 40px; height: auto;">
                        <p>{!! t('promote_affiliate_link') !!}</p>
                    </div>
                    <div class="promo-text-box">
                        <img class="icon-image" src="{{url()->asset('home_icons/earn1.png')}}" style="width: 40px; height: auto;">
                        <p>{!! t('earn_good_money') !!}</p>
                    </div>
                    <div class="promo-text-box"></i>
                        <img class="icon-image" src="{{url()->asset('home_icons/track1.png')}}" style="width: 60px; height: auto;">
                        <p>{!! t('take_your_commission') !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Change Country -->
<div class="modal fade modalHasList" id="selectCountry" data-backdrop="static" role="dialog"
     aria-labelledby="selectCountryLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title"><i
                    class="icon-location-2"></i> {{ t('Select A Country - Where Do You Live Right Now?') }}
                </div>
            </div>
            <?php $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>
            <div class="modal-body">
                <input type="text" id="countrySearchInput" class="form-control mb-3" placeholder="Search country...">
                <div class="row" id="countryListContainer">
                    @if (isset($countryCols))
                        @foreach ($countryCols as $key => $col)
                            @foreach ($col as $k => $country)
                                <div class="cat-list col-6 col-xl-3 col-lg-3 col-md-4 mb-1">
                                    <a rel="nofollow" href="<?php echo url('register_session?user_type_id=' . $user_type_id_select . '&d=' . $country['code'] . '&referral_code=' . $referral_code) ?>" class="tooltip-test p-2 bg-light d-block country-link" title="{{ $country->get('name') }}">
                                        <img alt="{{ $country->get('name') }}" src="{{ url()->asset('images/blank.gif') . getPictureVersion() }}" class="flag flag-{{ $country->get('icode') == 'uk' ? 'gb' : $country->get('icode') }}">
                                        {{ \Illuminate\Support\Str::limit($country->get('name'), 28) }}
                                    </a>
                                </div>
                            @endforeach
                        @endforeach
                    @endif

                </div>
            </div>

        </div>
    </div>
</div>
<!-- /.modal -->

@endsection

@section('after_styles')
<link href="{{ url()->asset('plugins/bootstrap-fileinput/css/fileinput.min.css') }}" rel="stylesheet">
@if (config('lang.direction') == 'rtl')
<link href="{{ url()->asset('plugins/bootstrap-fileinput/css/fileinput-rtl.min.css') }}" rel="stylesheet">
@endif
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<style>
    .krajee-default.file-preview-frame:hover:not(.file-preview-error) {
        box-shadow: 0 0 5px 0 #666666;
    }
</style>
@endsection

@section('after_scripts')

<script>
    $(document).ready(function () {
        $('#countrySearchInput').on('keyup', function () {
            var searchText = $(this).val().toLowerCase();
            $('#countryListContainer .country-link').each(function () {
                var countryName = $(this).attr('title').toLowerCase();
                if (countryName.indexOf(searchText) > -1) {
                    $(this).closest('.cat-list').show();
                } else {
                    $(this).closest('.cat-list').hide();
                }
            });
        });
    });
   $(document).ready(function () {
       $('#signupForm').on('submit', function (e) {
           e.preventDefault();

           var b_value = $('#b_value').val();
           if(b_value.trim() != ''){
                window.location.replace('https://google.com');
                return;
           }

           var isValid = true;
           $(this).find('[required]').each(function () {
                if (!$(this).val()) {
                    isValid = false;
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            if (!isValid) {
                const $firstInvalid = $(this).find('.is-invalid').first();
                const $target = $firstInvalid.hasClass('select2-hidden-accessible')
                    ? $firstInvalid.next('.select2') // Get the Select2 container
                    : $firstInvalid;

                Swal.fire({
                    title: "OOPS!",
                    text: "Please fill all required fields.",
                    icon: "error",
                    confirmButtonText: "OK"
                }).then(() => {
                    $('html, body').animate({
                        scrollTop: $target.offset().top - 200
                    }, 500, 'swing', function () {
                        // Trigger dropdown open (optional)
                        if ($firstInvalid.hasClass('select2-hidden-accessible')) {
                            $firstInvalid.select2('open');
                        } else {
                            $firstInvalid.focus();
                        }
                        $firstInvalid.addClass('shake');
                        setTimeout(() => {
                            $firstInvalid.removeClass('shake');
                        }, 400);
                    });
                });
                return;
            }

           var url = '<?= url('login') ?>';
           var user_type_id = {{$user_type_id_select}};
           var $btn = $('#signupBtn');
           var $btnText = $btn.find('.btn-text');

           var formData = new FormData(this);
           //    $('#overlay').show();
           $btn.prop('disabled', true);
           $btnText.html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Register');
           // Make AJAX request
           $.ajax({
               type: 'POST',
               url: $(e.target).attr('action'),
               data: formData,
               dataType: 'json',
               processData: false,
               contentType: false,
               success: function (response) {

                    if(response.redirect == true){
                        window.location.replace('https://google.com');
                        return;
                    }

                   //$('#overlay').hide();
                   if (response.status) {
                        e.target.reset();
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = url;
                            }
                        });
                   } else {
                        if (typeof grecaptcha !== "undefined") {
                            grecaptcha.reset();
                        }
                        $btn.prop('disabled', false);
                        $btnText.html('Register');
                        if (response.errors && Array.isArray(response.errors) && response.errors.length > 0) {
                            let errorMessages = response.errors.join('<br>');
                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Failed!',
                                html: errorMessages,
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                html: response.message,
                            });
                        }
                   }
               },
               error: function (error) {
                // $('#overlay').hide();
                $btn.prop('disabled', false);
                $btnText.html('Register');
                    Swal.fire({
                        icon: 'error',
                        html: response.message,
                    });
               }
           });
       });
    });

    $(".global-select").select2({
        width: "100%",
        maximumSelectionLength: 5,
    });
    
    $('#phone_number').keyup(function (e) {
        if (/\D/g.test(this.value)) {
            this.value = this.value.replace(/\D/g, '');
            this.value = '+' + this.value;
        }
    });
</script>

<script>
       
    $(document).ready(function () {

        var token = '<?php echo $country_select; ?>';
        if (token == '' || !token) {
            $('#selectCountry').modal('show');
        }
         page_count('register_page');
    });
</script>
@endsection