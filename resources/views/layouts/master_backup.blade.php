<?php

use App\Models\Permission;

$plugins = array_keys((array)config('plugins'));
$publicDisk = Storage::disk(config('filesystems.default'));
?>
<!DOCTYPE html>
<html lang="{{ ietfLangTag(config('app.locale')) }}" {!! (config('lang.direction')=='rtl') ? ' dir="rtl"' : '' !!}>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Hungry For Jobs</title>
    {!! MetaTag::tag('description') !!}
    {!! MetaTag::tag('keywords') !!}
    <link rel="shortcut icon" href="{{ imgUrl(config('settings.app.favicon'), 'favicon') }}">
    <meta name="author" content="Hungry For Jobs"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta property="og:locale" content="en-US"/>
    <meta property="og:site_name" content="Hungry For Jobs"/>
    <meta property="og:image" content="{{ imgUrl(config('settings.app.favicon'), 'favicon') }}"/>
    <meta property="og:image:width" content="200"/>
    <meta property="og:image:height" content="200"/>
    <meta property="og:type" content="website"/>
    <meta property="og:title" content="Hungry For Jobs"/>
    <meta property="og:description" content="FIND A JOB NEAR YOU - IN THE HOSPITALITY, FOOD & BEVERAGE INDUSTRIES"/>
    <meta property="og:url" content="https://hungryforjobs.com/"/>
    <meta property="og:image:alt" content="Hungry For Jobs"/>
    <meta property="fb:app_id" content="966242223397117"/>
    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:site" content="@Hungry For Jobs"/>
    <meta name="twitter:title" content="Hungry For Jobs"/>
    <meta name="twitter:description" content="FIND A JOB NEAR YOU - IN THE HOSPITALITY, FOOD & BEVERAGE INDUSTRIES"/>
    <meta name="apple-mobile-web-app-title" content="Hungry For Jobs">
    <link rel="apple-touch-icon" href="https://hungryforjobs.com/storage/app/default/ico/apple-touch-icon-iphone-60x60.png">
    <link rel="apple-touch-icon" sizes="60x60" href="https://hungryforjobs.com/storage/app/default/ico/apple-touch-icon-ipad-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="https://hungryforjobs.com/storage/app/default/ico/apple-touch-icon-iphone-retina-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="https://hungryforjobs.com/storage/app/default/ico/apple-touch-icon-ipad-retina-152x152.png">
    <link href="{{url()->asset('css/select2.min.css') }}" rel="stylesheet" />
   
   
    @includeFirst([config('larapen.core.customizedViewPath') . 'common.meta-robots', 'common.meta-robots'])
    <meta name="apple-mobile-web-app-title" content="Hungry For Jobs">
    <link rel="canonical" href="{{ request()->fullUrl() }}"/>
    {!! seoSiteVerification() !!}
    @if (file_exists(public_path('manifest.json')))
    <link rel="manifest" href="/manifest.json">
    @endif
    @stack('before_styles_stack')
    @yield('before_styles')
    @if (config('lang.direction') == 'rtl')
    
    <link href="{{ url()->asset('css/app.rtl.css') }}" rel="stylesheet">
    @else
    <link href="{{ url()->asset('css/app.css') }}?version=<?= time() ?>" rel="stylesheet">
    <link href="{{ url()->asset('css/custom.css') }}?version=<?= time() ?>" rel="stylesheet">
    @endif
    @if (config('plugins.detectadsblocker.installed'))
    <link href="{{ url()->asset('detectadsblocker/css/style.css') . getPictureVersion() }}" rel="stylesheet">
    @endif
    @includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.tools.style', 'layouts.inc.tools.style'])
    @stack('after_styles_stack')
    @yield('after_styles')
    @if (isset($plugins) and !empty($plugins))
    @foreach($plugins as $plugin)
    @yield($plugin . '_styles')
    @endforeach
    @endif

    @if (config('settings.style.custom_css'))
    {!! printCss(config('settings.style.custom_css')) . "\n" !!}
    @endif

    @if (config('settings.other.js_code'))
    {!! printJs(config('settings.other.js_code')) . "\n" !!}
    @endif

    <script>
        paceOptions = {
            elements: true
        };
        var companies_count = 0;
    </script>
    <script src="{{ url()->asset('js/pace.min.js') }}"></script>
    <script src="{{ url()->asset('plugins/modernizr/modernizr-custom.js') }}"></script>

    @section('recaptcha_scripts')
    @if (
    config('settings.security.recaptcha_activation')
    and config('recaptcha.site_key')
    and config('recaptcha.secret_key')
    )
    <style>
        .is-invalid .g-recaptcha iframe,
        .has-error .g-recaptcha iframe {
            border: 1px solid #f85359;
        }
    </style>
    @if (config('recaptcha.version') == 'v3')
    <script type="text/javascript">
        function myCustomValidation(token) {
            if ($('#gRecaptchaResponse').length) {
                $('#gRecaptchaResponse').val(token);
            }
        }
    </script>
    {!! recaptchaApiV3JsScriptTag([
    'action' => request()->path(),
    'custom_validation' => 'myCustomValidation'
    ]) !!}
    @else
    {!! recaptchaApiJsScriptTag() !!}
    @endif
    @endif
    @show
<script src="{{url()->asset('js/jquery.min.js')}}?version=<?= time() ?>"></script>
<script src="{{url()->asset('js/owl.carousel.min.js')}}?version=<?= time() ?>"></script>
<script src="{{url()->asset('js/popper.min.js')}}?version=<?= time() ?>"></script>
<script src="{{url()->asset('js/bootstrap.bundle.min.js')}}?version=<?= time() ?>"></script>
<script src="{{url()->asset('js/select2.min.js')}}?version=<?= time() ?>"></script>
</head>
<body class="{{ config('app.skin') }}">
<div id="overlay" hidden>
    <div id="text">
        <img src="{{url('public/assets/icon/logo.png')}}" style="max-width: 300px; width: 100%;display: block" class="loader" alt="loading">
        <div class="dot-pulse"></div>
    </div>
</div>
<div id="wrapper">
    @section('header')
    @includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.header', 'layouts.inc.header'])
    @show
    @includeFirst([config('larapen.core.customizedViewPath') . 'home.inc.search_login', 'home.inc.search_login'])
    @show
    @section('wizard')
    @show
    @if (isset($siteCountryInfo))
    <div class="h-spacer"></div>
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="alert alert-warning">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {!! $siteCountryInfo !!}
                </div>
            </div>
        </div>
    </div>
    @endif
    <div id="main-content-div" style="margin-top: -100px;padding-top: 50px;background: #f4f4f4;">
        @yield('content')
    </div>
    @section('info')
    @show

    @includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.advertising.auto', 'layouts.inc.advertising.auto'])

    @section('footer')
    @includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.footer', 'layouts.inc.footer'])
    @show

</div>

@section('modal_location')
@show
@section('modal_abuse')
@show
@section('modal_message')
@show

@includeWhen(!auth()->check(), 'layouts.inc.modal.login')
@includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.modal.change-country', 'layouts.inc.modal.change-country'])
@includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.modal.error', 'layouts.inc.modal.error'])
@include('cookieConsent::index')

@if (config('plugins.detectadsblocker.installed'))
@if (view()->exists('detectadsblocker::modal'))
@include('detectadsblocker::modal')
@endif
@endif

<script>
    {{--Init.Root Vars--}}
    var siteUrl = "{{ url('/') }}";
    var languageCode = '<?php echo config('app.locale'); ?>';
    var countryCode = '<?php echo config('country.code', 0); ?>';
    var timerNewMessagesChecking = <?php echo (int)config('settings.other.timer_new_messages_checking', 0); ?>;
    var isLogged = <?php echo (auth()->check()) ? 'true' : 'false'; ?>;
    var isLoggedAdmin = <?php echo (auth()->check() && auth()->user()->can(Permission::getStaffPermissions())) ? 'true' : 'false'; ?>;

    {{--Init.Translation Vars--}}
    var langLayout = {
        'hideMaxListItems': {
            'moreText': "{{ t('View More') }}",
            'lessText': "{{ t('View Less') }}"
        },
        'select2': {
            errorLoading: function () {
                return "{!! t('The results could not be loaded') !!}"
            },
            inputTooLong: function (e) {
                var t = e.input.length - e.maximum, n = "Please delete X character"

                return t != 1 && (n += 's'), n
            },
            inputTooShort: function (e) {
                var t = e.minimum - e.input.length, n = 'Please enter X or more characters'

                return n
            },
            loadingMore: function () {
                return "{!! t('Loading more results') !!}"
            },
            maximumSelected: function (e) {
                var t = 'You can only select N item'
                return e.maximum != 1 && (t += 's'), t
            },
            noResults: function () {
                return "{!! t('No results found') !!}"
            },
            searching: function () {
                return "{!! t('Searching') !!}"
            }
        }
    };
    var fakeLocationsResults = "{{ config('settings.listing.fake_locations_results', 0) }}";
    var stateOrRegionKeyword = "{{ t('area') }}";
    var errorText = {
        errorFound: "{{ t('error_found') }}"
    };
</script>
@stack('before_scripts_stack')
@yield('before_scripts')

{{--
<script src="{{ url()->asset('js/app.js') }}?version=<?= time() ?>"></script>
--}}
<!-- jQuery base library needed -->

<script src="{{ url()->asset('plugins/bootstrap-fileinput/js/plugins/sortable.min.js') }}?version=<?= time() ?>" type="text/javascript"></script>
<script src="{{ url()->asset('plugins/bootstrap-fileinput/js/fileinput.min.js') }}?version=<?= time() ?>" type="text/javascript"></script>
<script src="{{ url()->asset('plugins/bootstrap-fileinput/themes/fa/theme.js') }}?version=<?= time() ?>" type="text/javascript"></script>


@if (config('plugins.detectadsblocker.installed'))
<script src="{{ url()->asset('detectadsblocker/js/script.js') . getPictureVersion() }}?version=<?= time() ?>"></script>
@endif
<script>
    $(document).ready(function () {
        $('.selecter').select2({
            language: langLayout.select2,
            dropdownAutoWidth: 'true',
            minimumResultsForSearch: Infinity,
            width: '100%'
        });
        $('.sselecter').select2({
            language: langLayout.select2,
            dropdownAutoWidth: 'true',
            width: '100%'
        });

        $('.newCitySelected').select2({
            language: langLayout.select2,
            dropdownAutoWidth: 'true',
            width: '100%',
            containerCssClass: function (e) {
                return $(e).attr('required') ? 'required' : '';
            }
        });
    });
</script>
<script src="{{url()->asset('js/sweetalert.min.js')}}"></script>


@stack('after_scripts_stack')
@yield('after_scripts')
@if (isset($plugins) and !empty($plugins))
@foreach($plugins as $plugin)
@yield($plugin . '_scripts')
@endforeach
@endif

@if (config('settings.footer.tracking_code'))
{!! printJs(config('settings.footer.tracking_code')) . "\n" !!}
@endif


<script>

    function upgrade_account() {
        var url = '<?=url('account/upgrade')?>';
        var message = '<?= t("You have reached the maximum amount of Contact");?>';
        const config = {
            html: true,
            title: 'Error',
            html: message,
            icon: 'error',
            allowOutsideClick: false,
            confirmButtonText: 'Upgrade',
            showCancelButton: true,
        };
        Swal.fire(config).then((result) => {
            if (result.isConfirmed) {
                window.location.replace(url);
            } else if (result.isDenied) {
                return false;
            }
        });
    }

    $(document).ready(function () {

        if (companies_count && companies_count > 5) {
            companies_count = 5
        }
        if ($('#slider').length) {
            $('#slider').owlCarousel({
                loop: true,
                autoplay: true,
                autoplayHoverPause: false,
                autoplaySpeed: 3000,
                animateOut: 'fadeOut',
                animateIn: 'fadeIn',
                items: companies_count,
                responsive: {
                    0: {
                        items: 1
                    },
                    600: {
                        items: 2
                    },
                    1000: {
                        items: companies_count
                    }
                }
            });
        }


    });
</script>
<script>
    $('.validatePhoneCheck').keyup(function (e) {
        if (/\D/g.test(this.value)) {
            this.value = this.value.replace(/[^0-9-]/g, '');
            this.value = '+' + this.value;
            // alert(this.value);
        } else {
            this.value = '+' + this.value;
        }
    });
</script>
@if(!empty($messageData))

@if($messageData != '
<ul></ul>
')

<script>
    Swal.fire({
        html: '<?=!empty($messageData) ? $messageData : ""?>',
        icon: "error",
        confirmButtonText: "<u>Ok</u>",
    });
</script>
@endif
@endif
<script>
    $('.number-without-icon').keyup(function (e) {
        if (/\D/g.test(this.value)) {
            this.value = this.value.replace(/\D/g, '');
        }
    });
</script>

<script>
    $(document).ready(function () {
        var homepage = window.location.href;
        substring = 'https://hungryforjobs.com/?d=';
        if (homepage != "https://hungryforjobs.com/") {
            if (homepage.indexOf(substring) != 0) {
                $('html, body').animate({
                    scrollTop: $("#main-content-div").offset().top - 80
                }, 1);
            } else {
                setTimeout(function () {
                    window.scrollTo(0, 0);
                }, 100);
            }
        } else {
            setTimeout(function () {
                window.scrollTo(0, 0);
            }, 100);

        }
        $('#overlay').fadeOut(1000);
    });
</script>
<?php $user_type_id_select = !empty(request()->get('user_type_id')) ? request()->get('user_type_id') : ''; ?>
<script>
    $(document).ready(function () {
        var userTypeSelection = '<?php echo $user_type_id_select?>';
        if (userTypeSelection == 2) {
            $('#check_user_type').css('display', '');
            var name = '<?= t('full_name') ?>';
            $('.usertypename').html(name + ' <sup>*</sup>');
            $("#registerName").attr("placeholder", name);
        } else if (userTypeSelection == 1) {
            $('#check_user_type').css('display', '');
            var name = '<?= t('Company name') ?>';
            $("#registerName").attr("placeholder", name);
            $('.usertypename').html(name + ' <sup>*</sup>');
        } else {
            $('#check_user_type').css('display', 'none');
        }
    });
</script>

</body>
</html>