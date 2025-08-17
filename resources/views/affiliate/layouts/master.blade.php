<?php

use App\Models\Permission;
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- HTML Meta Tags -->
    <title>{!! !empty($title) ? $title : '' !!}</title>

        <!-- Google / Search Engine Tags -->
    <meta name="name" content="Hungry For Jobs">
    <meta name="description" content="{!! !empty($description) ? $description : 'Welcome to Hungry For Jobs : Find a job near you - In the hospitality, food & beverage industries. ' !!}">
    <meta name="keywords" content="{!! !empty($description) ? $description : 'Welcome to Hungry For Jobs : Find a job near you - In the hospitality, food & beverage industries. ' !!}">
    <meta name="image" content="{{ imgUrl(config('settings.app.favicon'), 'favicon') }}">
    <link rel="shortcut icon" href="{{ imgUrl(config('settings.app.favicon'), 'favicon') }}">

        <!-- Facebook Meta Tags -->
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{!! !empty($title) ? $title : '' !!}">
    <meta property="og:description" content="{!! !empty($description) ? $description : 'Welcome to Hungry For Jobs : Find a job near you - In the hospitality, food & beverage industries. ' !!}">

    <meta property="og:image" content="{{ imgUrl(config('settings.app.favicon'), 'favicon') }}">


    <!-- Twitter Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{!! !empty($title) ? $title : '' !!}">
    <meta name="twitter:description" content="{!! !empty($description) ? $description : 'Welcome to Hungry For Jobs : Find a job near you - In the hospitality, food & beverage industries. ' !!}">
    <meta name="twitter:image" content="{{ imgUrl(config('settings.app.favicon'), 'favicon') }}">
    
    <!-- Google Tag Manager -->
    <script>(function (w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start':
                    new Date().getTime(), event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-T4TS45JH');</script>
    <!-- End Google Tag Manager -->

    @stack('before_styles_stack')
    @yield('before_styles')

    <link href="{{ url()->asset('css/select2.min.css') }}" rel="stylesheet"/>
    <link href="{{ url()->asset('css/app.css') }}?version={{VERSION}}" rel="stylesheet">
    <link href="{{ url()->asset('css/custom.css') }}?version={{VERSION}}" rel="stylesheet">
    <link href="{{ url()->asset('css/affiliate.css') }}?version={{VERSION}}" rel="stylesheet">
    <link rel="stylesheet" href="{{ url()->asset('adminlite/libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
    <link href="https://cdn.datatables.net/v/bs5/dt-2.1.2/datatables.min.css" rel="stylesheet">

    @includeFirst([
    config('larapen.core.customizedViewPath') . 'layouts.inc.tools.style',
    'layouts.inc.tools.style',
    ])
    @stack('after_styles_stack')
    @yield('after_styles')

    <script>
        paceOptions = {
            elements: true
        };
        var companies_count = 0;
    </script>

    <script src="{{ url()->asset('js/jquery.min.js') }}"></script>
    <script src="{{ url()->asset('js/toaster.min.js') }}"></script>
    <script src="{{ url()->asset('js/owl.carousel.min.js') }}"></script>
    <script src="{{ url()->asset('js/popper.min.js') }}"></script>
    <script src="{{ url()->asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url()->asset('js/select2.min.js') }}"></script>
    <script src="{{ url()->asset('js/sweetalert.min.js') }}"></script>
    <script src="{{ url()->asset('js/sweetalert2@1.js') }}"></script>
    <script src="{{ url()->asset('js/footable.js') }}" type="text/javascript"></script>
    <script src="{{ url()->asset('js/footable.filter.js') }}" type="text/javascript"></script>
    <script src="{{ url()->asset('plugins/bootstrap-fileinput/js/plugins/sortable.min.js') }}" type="text/javascript"></script>
    <script src="{{ url()->asset('plugins/bootstrap-fileinput/js/fileinput.min.js') }}" type="text/javascript"></script>
    <script src="{{ url()->asset('plugins/bootstrap-fileinput/themes/fa/theme.js') }}" type="text/javascript"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <script src="{{ url()->asset('adminlite/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

</head>

<body class="{{ config('app.skin') }}">
<!-- Google Tag Manager (noscript) -->
<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T4TS45JH"
            height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->
<div id="overlay">
    <div id="text">
        <img src="{{ url('public/assets/icon/logo.png') }}" style="max-width: 300px; width: 100%;display: block"
             class="loader" alt="loading">
        <div class="dot-pulse"></div>
    </div>
</div>
<div id="wrapper">
    @section('header')
        @includeFirst([
        config('larapen.core.customizedViewPath') . 'affiliate.inc.header',
        'affiliate.inc.header',
        ])
    @show
    @includeFirst([
    config('larapen.core.customizedViewPath') . 'home.inc.search_login',
    'home.inc.search_login',
    ])
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

    @section('footer')
        @includeFirst([config('larapen.core.customizedViewPath') . 'affiliate.inc.footer', 'affiliate.inc.footer'])
    @show
</div>
@section('modal_location')
@show
@section('modal_message')
@show
@includeWhen(!auth()->check(), 'layouts.inc.modal.login')
@includeFirst([
config('larapen.core.customizedViewPath') . 'layouts.inc.modal.change-country',
'layouts.inc.modal.change-country',
])
@includeFirst([
config('larapen.core.customizedViewPath') . 'layouts.inc.modal.error',
'layouts.inc.modal.error',
])
<style>
    .cookie-consent__agree {
        background: #78DAE7 !important;
    }
</style>
@include('cookieConsent::index')
@include('modals.applicants_modal')
<script>
    var siteUrl = "{{ url('/') }}";
    var languageCode = '<?php echo config('app.locale'); ?>';
    var countryCode = '<?php echo config('country.code', 0); ?>';
    var timerNewMessagesChecking = <?php echo (int)config('settings.other.timer_new_messages_checking', 0); ?>;
    var isLogged = <?php echo auth()->check() ? 'true' : 'false'; ?>;
    var isLoggedAdmin = <?php echo auth()->check() &&
    auth()
        ->user()
        ->can(Permission::getStaffPermissions())
        ? 'true'
        : 'false'; ?>;
    var stateOrRegionKeyword = "{{ t('area') }}";
    var errorText = {
        errorFound: "{{ t('error_found') }}"
    };
</script>

@stack('before_scripts_stack')
@yield('before_scripts')

@stack('after_scripts_stack')
@yield('after_scripts')

<script>
    function togglePasswordVisibility(eyeIcon, elmt) {
        if (eyeIcon.classList.contains("fa-eye-slash")) {
            eyeIcon.classList.remove("fa-eye-slash");
            eyeIcon.classList.add("fa-eye");
        } else {
            eyeIcon.classList.remove("fa-eye");
            eyeIcon.classList.add("fa-eye-slash");
        }

        if (elmt.attr('type') === 'password') {
            elmt.prop('type', 'text');
            $(this).find('.eyeOfPwd').html('<i class="far fa-eye"></i>');
        } else {
            elmt.prop('type', 'password');
            $(this).find('.eyeOfPwd').html('<i class="far fa-eye-slash"></i>');
        }
    }

    function showPwd() {
        var elmt = $('.show-pwd-group #mPassword, .show-pwd-group #password, .show-pwd-group #password_1');
        var eyeIcon = document.getElementById("eyeIcon");
        togglePasswordVisibility(eyeIcon, elmt);
    }

    function showconfirmdPwd() {
        var elmt = $('.show-pwd-group #mPassword, .show-pwd-group #password_confirmation');
        var eyeIcon = document.getElementById("eyeIcon2");
        togglePasswordVisibility(eyeIcon, elmt);
    }

    function oldshowPwd() {
        var elmt = $('.show-pwd-group #mPassword, .show-pwd-group #old_password');
        var eyeIcon = document.getElementById("eyeIcon_old");
        togglePasswordVisibility(eyeIcon, elmt);
    }

    function hideOverlay() {
        // Get the overlay element
        var overlay = document.getElementById('overlay');

        // Hide the overlay by setting its display property to 'none'
        overlay.style.display = 'none';
    }

    // Add an event listener to wait for the page to finish loading
    window.addEventListener('load', hideOverlay);


    $(document).ready(function () {
        $('html, body').animate({
            scrollTop: $("#main-content-div").offset().top - 80
        }, 1);
    });


    function upgrade_account() {
        var url = '<?= url('account/upgrade') ?>';
        var message = '<?= t('You have reached the maximum amount of Contact') ?>';
        const config = {
            html: true,
            title: 'Error',
            html: message,
            icon: 'error',
            allowOutsideClick: false,
            confirmButtonText: 'Subscribe',
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
    $('.validatePhoneCheck').keyup(function (e) {
        if (/\D/g.test(this.value)) {
            this.value = this.value.replace(/[^0-9-]/g, '');
            this.value = '+' + this.value;
            // alert(this.value);
        } else {
            this.value = '+' + this.value;
        }
    });
    $('.number-without-icon').keyup(function (e) {
        if (/\D/g.test(this.value)) {
            this.value = this.value.replace(/\D/g, '');
        }
    });
</script>

@if (!empty($messageData))
    @if ($messageData != '<ul></ul>')
        <script>
            Swal.fire({
                html: '<?= !empty($messageData) ? $messageData : '' ?>',
                icon: "error",
                confirmButtonText: "<u>Ok</u>",
            });
        </script>
    @endif
@endif


<?php $user_type_id_select = !empty(request()->get('user_type_id')) ? request()->get('user_type_id') : ''; ?>
<script>
    $(document).ready(function () {
        $('.select1').select2({
            width: "100%",
        });
        var userTypeSelection = '<?php echo $user_type_id_select; ?>';
        if (userTypeSelection == 5) {
            $('#check_user_type').css('display', '');
            var name = '<?= t('full_name') ?>';
            $('.usertypename').html(name + ' <sup>*</sup>');
            $("#registerName").attr("placeholder", name);
        } else {
            $('#check_user_type').css('display', 'none');
        }
    });

    function page_count(page, quary_parameter = '') {

        var server = "{{ json_encode(request()->server()) }}";
        var from = "{{ url()->previous() }}";

        $.ajax({
            type: 'POST',
            url: "{{url('page_count')}}",
            data: {
                'page': page,
                'quary_parameter': quary_parameter,
                'server': server,
                'from': from,
            },
            // Replace with your data
            success: function (response) {
                // You can perform additional actions here.
            },
            error: function (error) {
                console.error(error);
            }
        });
    }
</script>
<script>
    $('.modal').on('show.bs.modal', function () {
        $('#affiliate-form')[0].reset();
        $('.modal').not($(this)).each(function () {
            $(this).modal('hide');
        });
    });
</script>
<script src="{{ url()->asset('adminlite/js/pages/datatable/datatable-basic.init.js') }}?v={{time()}}"></script>
</body>
</html>