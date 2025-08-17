<?php

use App\Helpers\UrlGen;
use App\Models\AffiliationCommission;
use App\Models\Country;

if (
    config('settings.other.ios_app_url') ||
    config('settings.other.android_app_url') ||
    config('settings.social_link.facebook_page_url') ||
    config('settings.social_link.twitter_url') ||
    config('settings.social_link.linkedin_url') ||
    config('settings.social_link.instagram_url')
) {
    $colClass1 = 'col-lg-3 col-md-3 col-sm-3 col-xs-6';
    $colClass2 = 'col-lg-3 col-md-3 col-sm-3 col-xs-6';
    $colClass3 = 'col-lg-2 col-md-2 col-sm-2 col-xs-12';
    $colClass4 = 'col-lg-4 col-md-4 col-sm-4 col-xs-12';
} else {
    $colClass1 = 'col-lg-4 col-md-4 col-sm-4 col-xs-6';
    $colClass2 = 'col-lg-4 col-md-4 col-sm-4 col-xs-6';
    $colClass3 = 'col-lg-4 col-md-4 col-sm-4 col-xs-12';
    $colClass4 = 'col-lg-4 col-md-4 col-sm-4 col-xs-12';
}

$image_path = url("public/storage/Login_popup.jpeg");
$country_list = Country::all();
?>

<footer class="main-footer">
    <div class="footer-content">
        <div class="container">
            <div class="row">
                @if (!config('settings.footer.hide_links'))
                <div class="{{ $colClass1 }}">
                    <div class="footer-col">
                        <h4 class="footer-title">{{ t('About us') }}</h4>
                        <ul class="list-unstyled footer-nav">

                            @if (isset($pages) and $pages->count() > 0)
                                @foreach($pages as $page)
                                    @if($page->name != 'Anti-Scam')
                                        <li>
                                            <?php
                                            $linkTarget = '';
                                            if ($page->target_blank == 1) {
                                                $linkTarget = 'target="_blank"';
                                            } ?>
                                            @if (!empty($page->external_link))
                                            <a href="{!! $page->external_link !!}" rel="nofollow" {!! $linkTarget !!}> {{ $page->name }} </a>
                                            @else
                                            <a href="{{ UrlGen::page($page) }}" {!! $linkTarget !!}> {{ $page->name }} </a>
                                            @endif
                                        </li>
                                    @endif
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>

                <div class="{{ $colClass2 }}">
                    <div class="footer-col">
                        <h4 class="footer-title">{{ t('Contact and Sitemap') }}</h4>
                        <ul class="list-unstyled footer-nav">
                            <li><a href="{{ UrlGen::contact() }}" onclick="page_count('contact_us')"> {{ t('Contact') }} </a></li>
                            <li><a href="{{ UrlGen::company() }}"> {{ t('Companies') }} </a></li>
                            <li><a href="{{ UrlGen::sitemap() }}"> {{ t('Sitemap') }} </a></li>
                            @if (isset($countries) && $countries->count() > 1)
                            <li><a href="{{ UrlGen::countries() }}"> {{ t('countries') }} </a></li>
                            @endif
                        </ul>
                    </div>
                </div>

                <div class="{{ $colClass2 }}">
                    <div class="footer-col">
                        <h4 class="footer-title">{{ t('My Account') }}</h4>
                        <ul class="list-unstyled footer-nav">
                            @if (!auth()->user())
                                <li>
                                    <a href="{{ UrlGen::login() }}"> {{ t('Log In') }} </a>
                                </li>
                                <li>
                                    <a href="{{ url('registration') }}" class="nav-link">{{ t('Register') }}</a>
                                </li>
                                <li><a href="<?= url('affiliate-register') ?>"> {{ t('Become An Affiliate And Make Money') }} </a></li>
                            @else
                            <li><a href="{{ url('account') }}"> {{ t('Personal Home') }} </a></li>
                            @if (isset(auth()->user()->user_type_id))
                                @if (in_array(auth()->user()->user_type_id, [1]))
                                    <li><a href="{{ url('account/my-posts') }}"> {{ t('My ads') }} </a></li>
                                    <li><a href="{{ url('account/companies') }}"> {{ t('My companies') }} </a>
                                    </li>
                                @endif
                            @if (in_array(auth()->user()->user_type_id, [2]))
                                <li>
                                    <a href="{{ url('account/resumes/user_resume') }}"> {{ t('My resumes') }} </a>
                                </li>
                                <li><a href="{{ url('account/favourite') }}"> {{ t('Favourite jobs') }} </a>
                                </li>
                            @endif
                            @endif
                            @endif
                        </ul>
                    </div>
                </div>

                @if (
                config('settings.other.ios_app_url') or
                config('settings.other.android_app_url') or
                config('settings.social_link.facebook_page_url') or
                config('settings.social_link.twitter_url') or
                config('settings.social_link.linkedin_url') or
                config('settings.social_link.instagram_url')
                )
                <div class="{{ $colClass2 }}">
                    <div class="footer-col row">
                        <?php
                        $footerSocialClass = '';
                        $footerSocialTitleClass = '';
                        ?>
                        {{-- @todo: API Plugin --}}
                        @if (config('settings.other.ios_app_url') or config('settings.other.android_app_url'))
                        <div class="col-sm-12 col-xs-6 col-xxs-12 no-padding-lg">
                            <div class="mobile-app-content">
                                <h4 class="footer-title">{{ t('Mobile Apps') }}</h4>
                                <div class="row ">
                                    @if (config('settings.other.ios_app_url'))
                                    <div class="col-xs-12 col-sm-6">
                                        <a class="app-icon" target="_blank" href="{{ config('settings.other.ios_app_url') }}">
                                            <span class="hide-visually">{{ t('iOS app') }}</span>
                                            <img src="{{ url()->asset('images/site/app-store-badge.svg') }}" alt="{{ t('Available on the App Store') }}">
                                        </a>
                                    </div>
                                    @endif
                                    @if (config('settings.other.android_app_url'))
                                    <div class="col-xs-12 col-sm-6">
                                        <a class="app-icon" target="_blank" href="{{ config('settings.other.android_app_url') }}">
                                            <span class="hide-visually">{{ t('Android App') }}</span>
                                            <img src="{{ url()->asset('images/site/google-play-badge.svg') }}" alt="{{ t('Available on Google Play') }}">
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <?php
                        $footerSocialClass = 'hero-subscribe';
                        $footerSocialTitleClass = 'no-margin';
                        ?>
                        @endif

                        @if (
                        config('settings.social_link.facebook_page_url') or
                        config('settings.social_link.twitter_url') or
                        config('settings.social_link.linkedin_url') or
                        config('settings.social_link.instagram_url')
                        )
                        <div class="col-sm-12 col-xs-6 col-xxs-12 no-padding-lg">
                            <div class="{!! $footerSocialClass !!}">
                                <h4 class="footer-title {!! $footerSocialTitleClass !!}">{{ t('Follow us on') }}</h4>
                                <ul class="list-unstyled list-inline footer-nav social-list-footer social-list-color footer-nav-inline">
                                    @if (config('settings.social_link.facebook_page_url'))
                                    <li>
                                        <a class="icon-color fb" title="" data-placement="top" onclick="page_count('facebook_link_click')" target="_blank" data-toggle="tooltip" href="{{ config('settings.social_link.facebook_page_url') }}" data-original-title="Facebook">
                                            <i class="fab fa-facebook-f"></i>
                                        </a>
                                    </li>
                                    @endif
                                    @if (config('settings.social_link.twitter_url'))
                                    <li>
                                        <a class="icon-color tw" title="" data-placement="top" target="_blank" data-toggle="tooltip" onclick="page_count('twitter_link_click')" href="{{ config('settings.social_link.twitter_url') }}" data-original-title="Twitter">
                                            <i class="fab fa-twitter"></i>
                                        </a>
                                    </li>
                                    @endif
                                    @if (config('settings.social_link.instagram_url'))
                                    <li>
                                        <a class="icon-color pin" title="" data-placement="top" onclick="page_count('instagram_link_click')" target="_blank" data-toggle="tooltip" href="{{ config('settings.social_link.instagram_url') }}" data-original-title="Instagram">
                                            <i class="fab fa-instagram"></i>
                                        </a>
                                    </li>
                                    @endif
                                    @if (config('settings.social_link.google_plus_url'))
                                    <script src="https://code.iconify.design/iconify-icon/1.0.0/iconify-icon.min.js"></script>

                                    <li>
                                        <a class="icon-color gp" title="" data-placement="top " onclick="page_count('tiktok_link_click')" target="_blank" data-toggle="tooltip" href="{{ config('settings.social_link.google_plus_url') }}" data-original-title="Google+">
                                            <iconify-icon icon="fa-brands:tiktok"></iconify-icon>
                                        </a>
                                    </li>
                                    @endif
                                    @if (config('settings.social_link.linkedin_url'))
                                    <li>
                                        <a class="icon-color lin" title="" data-placement="top" onclick="page_count('linkedin_link_click')" target="_blank" data-toggle="tooltip" href="{{ config('settings.social_link.linkedin_url') }}" data-original-title="Linkedin">
                                            <i class="fab fa-linkedin-in"></i>
                                        </a>
                                    </li>
                                    @endif
                                    @if (config('settings.social_link.pinterest_url'))
                                    <li>
                                        <a class="icon-color pin" title="" data-placement="top" onclick="page_count('pinterest_link_click')" target="_blank" data-toggle="tooltip" href="{{ config('settings.social_link.pinterest_url') }}" data-original-title="Pinterest">
                                            <i class="fab fa-pinterest-p"></i>
                                        </a>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
                <div style="clear: both"></div>
                @endif

                <div class="col-xl-12">

                    @if (!config('settings.footer.hide_links'))
                    <hr>
                    @endif
                    <div class="copy-info d-inline-block">
                        © {{date("Y")}} {{ config('settings.app.app_name') }}. {{ t('All Rights Reserved') }}.
                        @if (!config('settings.footer.hide_powered_by'))
                        @if (config('settings.footer.powered_by_info'))
                        {{ t('Powered by') }} {!! config('settings.footer.powered_by_info') !!}
                        @endif
                        @endif
                    </div>
                    <div class="footer-payment-icons float-right d-inline-block">
                        <img src="{{url()->asset('images/visa.svg')}}" alt="visa" class="lazyload" width="50" height="30">
                        <img src="{{url()->asset('images/mastercard.svg')}}" alt="mastercard" class="lazyload" width="50" height="30">
                        <img src="{{url()->asset('images/apple.jpg')}}" alt="apple pay" class="lazyload" width="50" height="30">
                    </div>
                </div>

            </div>
        </div>
    </div>
</footer>

<div class="modal fade" id="register_popup" tabindex="-1" data-toggle="modal" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Registration</div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <a href="<?= url('register') ?>?user_type_id=2" class="btn btn-block btn btn-success btn-register " style="color:white;font-size: 14px; width:auto;"><i class="icon-user"></i>Register as Employee
                        (Job Seeker)</a>

                </div>
                <div class="mb-3">
                    <a href="<?= url('register') ?>?user_type_id=1" class="btn btn-block btn btn-success btn-register" style="color:white;font-size: 14px; width:auto;"><i class="icon-town-hall"></i>Register as
                        Employer (Company)</a>
                </div>


            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="become_affiliate_popup" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <form role="form" id="affiliate-form" method="POST" action="{{ url('store_affiliate') }}" enctype="multipart/form-data">
                        @csrf                            
                        <div class="quickLoginLeft">
                            <h3>{{ t('Become An Affiliate And Make Money') }}</h3>
                            <div class="form-group">
                                <label for="name" class="control-label">{{ t('Name') }} <sup>*</sup></label> 
                                <div class="input-group">
                                    <input id="name" name="name" type="text" class="form-control" placeholder="{{ t('Name') }}" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email" class="control-label">{{ t('Email') }} <sup>*</sup></label> 
                                <div class="input-group">
                                    <input id="email" name="email" type="email" class="form-control" placeholder="{{ t('Email') }}" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="phone" class="control-label">{{ t('Phone') }} <sup>*</sup></label> 
                                <div class="input-group">
                                    <input id="phone" name="phone" type="text" class="form-control" placeholder="{{ t('Phone') }}" required oninput="validatePhoneInput(event)">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="country">{{ t('Country') }} <sup>*</sup></label>
                                <select name="country_code" id="country_code" class="select1 form-control" required>
                                    <option value="">{{ trans('admin.select_country') }}</option>
                                    @foreach ($country_list as $country)
                                        <option value="{{ $country->id }}"> {{ $country->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div id="become_affiliate"></div>
                            <div class="form-group">
                                <label for="city">{{ t('City') }} <sup>*</sup></label>
                                <select name="city_code" id="city_code" class="select1 form-control" required>
                                    <option value="">{{ trans('admin.select_city') }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="Password" class="control-label">{{ t('Password') }} <sup>*</sup></label> 
                                <div class="input-group show-pwd-group">
                                    <input id="password_affiliation" name="password" minlength="6" type="password" class="form-control" placeholder="{{ t('Password') }}" autocomplete="off" required>
                                    <span class="icon-append show-pwd">
                                        <button type="button" class="eyeOfPwd" onclick="showPwdAffiliation()">
                                            <i id="eyeIconAffiliation" class="far fa-eye-slash" style="color:#474b51"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="Confirm Password" class="control-label">{{ t('Password Confirmation') }} <sup>*</sup></label> 
                                <div class="input-group show-pwd-group">
                                    <input id="password_confirmation_affiliation" name="password_confirmation" minlength="6" type="password" class="form-control" placeholder="{{ t('Password Confirmation') }}" autocomplete="off" required>
                                    <span class="icon-append show-pwd">
                                        <button type="button" class="eyeOfPwd" onclick="showConfirmPwdAffiliation()">
                                            <i id="eyeIcon2Affiliation" class="far fa-eye-slash" style="color:#474b51"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">{{ trans('admin.save') }}</button>
                        </div>
                        </form>
                    </div>
                    <div class="col-md-6 login-background"
                        style="background-image: url('<?= $image_path ?>');background-size: contain">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">{{ t('Close') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$('#become_affiliate_popup').on('shown.bs.modal', function () {
    $("#country_code,#city_code").select2({
        dropdownParent: $("#become_affiliate"),
    });
});
$(document).on('submit', '#affiliate-form', (e) => {
    e.preventDefault();
    const password = $('#password_affiliation').val();
    const confirmPassword = $('#password_confirmation_affiliation').val();
    if (password !== confirmPassword) {
        Swal.fire({
            icon: 'error',
            title: 'Password Mismatch',
            text: 'Password and confirmation password do not match.',
        });
        return;
    }
    const formData = new FormData(e.target);
    $.ajax({
        url: $(e.target).attr('action'),
        type: 'POST',
        data: formData,
        dataType: 'json',
        processData: false,
        contentType: false,
        beforeSend: () => {
            $('#overlay').show();
        },
        success: (response) => {
            $('#become_affiliate_popup').modal('hide');
            e.target.reset();
            $('#country_code').val('').trigger('change');
            $('#city_code').val('').trigger('change');
            $('#overlay').hide();
            if(response.status){
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message,
                });
            }else{
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
        error: (xhr, status, error) => {
            $('#overlay').hide();
            Swal.fire({
                icon: 'error',
                html: response.message,
            });
        },
        complete: () => {
            $('#overlay').hide();
            console.log('Request completed.');
        },
    });
});
$('#country_code').on('change', function() {
    var country_name = $(this).val();

    $.ajax({
        url: "{{ route('city_change_global') }}",
        method: "POST",
        dataType: 'json',
        data: {
            'country_name': country_name
        },
        success: function(city) {
            $('#city_code').html('');
            $('#city_code').html('<option value="">-- Select City --</option>');
            $.each(city, function(key, value) {
                $("#city_code").append('<option value="' + value.id +
                    '" >' + value.slug + '</option>');

            });
        }
    })
});
function showPwdAffiliation() {
    var elmt = $('#password_affiliation');
    var eyeIcon = document.getElementById("eyeIconAffiliation");
    togglePasswordVisibility(eyeIcon, elmt);
}

function showConfirmPwdAffiliation() {
    var elmt = $('#password_confirmation_affiliation');
    var eyeIcon = document.getElementById("eyeIcon2Affiliation");
    togglePasswordVisibility(eyeIcon, elmt);
}
function validatePhoneInput(event) {
    const input = event.target;
    const value = input.value;
    const cleanedValue = value.replace(/[^0-9+]/g, '');
    input.value = cleanedValue;
}
</script>