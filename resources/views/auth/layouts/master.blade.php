<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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

    <link href="{{ url()->asset('css/app.css') }}?version={{VERSION}}" rel="stylesheet">
    <link href="{{ url()->asset('css/custom.css') }}?version={{VERSION}}" rel="stylesheet">
    <script src="{{ url()->asset('js/jquery.min.js') }}"></script>
    <script src="{{ url()->asset('js/sweetalert2@1.js') }}"></script>
    <link href="{{ url()->asset('css/affiliate.css') }}?version={{VERSION}}" rel="stylesheet">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body class="{{ config('app.skin') }}">
<!-- Google Tag Manager (noscript) -->
<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T4TS45JH" height="0" width="0" style="display:none;visibility:hidden"></iframe>
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
        config('larapen.core.customizedViewPath') . 'layouts.inc.header',
        'layouts.inc.header',
        ])
    @show
    <div id="main-content-div">
        @yield('content')
        @if (app()->environment('local', 'development'))
        <div style="position: fixed; bottom: 0; width: 100%; background: rgba(0, 0, 0, 0.9); color: white; padding: 10px; z-index: 9999;">
            <button class="btn btn-sm btn-primary btn-block" style="position: absolute; right: 10px; top: -30px;"
                    data-toggle="collapse" data-target="#debugQueryLog">
                Toggle Queries ({{ $queryCount }})
            </button>

            <div id="debugQueryLog" class="collapse">
                <div class="p-2">
                    <h5 class="text-warning">Executed Queries ({{ $queryCount }})</h5>
                    <ul style="list-style: none; padding-left: 0; max-height: 400px; overflow-y: auto;">
                        @foreach ($groupedQueries as $query)
                        <li class="text-light" style="border-bottom: 1px solid gray; padding: 5px 0;">
                            <code>{{ $query['query'] }}</code>
                            <span class="badge badge-warning">x{{ $query['count'] }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@includeWhen(!auth()->check(), 'layouts.inc.modal.login')
@includeFirst([
config('larapen.core.customizedViewPath') . 'layouts.inc.modal.change-country',
'layouts.inc.modal.change-country',
])
<script>
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
        var elmt = $('.show-pwd-group #mPassword, .show-pwd-group #password');
        var eyeIcon = document.getElementById("eyeIcon");
        togglePasswordVisibility(eyeIcon, elmt);
    }

    function hideOverlay() {
        var overlay = document.getElementById('overlay');
        overlay.style.display = 'none';
    }
    window.addEventListener('load', hideOverlay);
</script>


</body>
</html>