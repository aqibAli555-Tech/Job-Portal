<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <!-- HTML Meta Tags -->
      <title>Hire or Get Hired in Hotels | Resort Job Listing in Kuwait</title>
      
      <!-- Google / Search Engine Tags -->
      <meta name="author" content="Hungry For Jobs">
      <meta name="title" content="Hire or Get Hired in Hotels | Resort Job Listing in Kuwait">
      <meta name="description" content="Looking for hotel and resort jobs in Kuwait or hiring skilled hospitality talent? Hungry For Jobs connects top employers with qualified professionals. Check now!">
      <meta name="keywords" content="hospitality jobs, food industry jobs, restaurant jobs, chef jobs, barista jobs, waiter jobs, job near me, hotel jobs">
      <meta name="robots" content="index, follow">
      <meta name="language" content="English">
      <meta name="revisit-after" content="7 days">
      <meta name="geo.region" content="PK">
      <meta name="geo.placename" content="Pakistan">
      <meta name="geo.position" content="30.3753;69.3451">
      <meta name="ICBM" content="30.3753, 69.3451">
      
      <!-- Canonical URL -->
      <link rel="canonical" href="{{ url()->current() }}">
      
      <!-- Favicon -->
      <link rel="shortcut icon" href="{{ imgUrl(config('settings.app.favicon'), 'favicon') }}">
      <meta name="image" content="{{ imgUrl(config('settings.app.logo'), 'logo') }}">
      <meta property="og:site_name" content="Hungry For Jobs">
      <meta property="og:title" content="Find Top Hotel & Resort Jobs or Talent in Kuwait's Hospitality Sector">
      <meta property="og:description" content=" Connect with leading hotel and resort opportunities in Kuwait or discover top hospitality professionals to elevate your team.">
      <meta property="og:type" content="website">
      <meta property="og:url" content="https://hungryforjobs.com/industries/hospitality-leisure/hotels-resorts">
      
      <!-- Twitter Meta Tags -->
      <meta name="twitter:card" content="summary_large_image">
      <meta name="twitter:title" content="{!! !empty($title) ? $title : config('settings.app.name', 'Hungry For Jobs') !!}">
      <meta name="twitter:description" content="{!! !empty($description) ? $description : 'Find a job near you in the hospitality, food & beverage industries.' !!}">
      <meta name="twitter:image" content="{{ imgUrl(config('settings.app.logo'), 'logo') }}">
      <meta name="twitter:site" content="@hungryforjobs">
      <meta name="twitter:creator" content="@hungryforjobs">
      
      <!-- LinkedIn Meta Tags -->
      <meta property="article:author" content="Hungry For Jobs">
      <meta property="article:publisher" content="https://www.linkedin.com/company/hungryforjobs/">
      
      <!-- PWA Meta Tags -->
      <link rel="manifest" href="{{ asset('manifest.json') }}">
      <meta name="theme-color" content="#ff5722">
      <meta name="mobile-web-app-capable" content="yes">
      <meta name="apple-mobile-web-app-capable" content="yes">
      <meta name="apple-mobile-web-app-title" content="Hungry For Jobs">
      <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
      <link rel="apple-touch-icon" sizes="192x192" href="{{ asset('public/storage/icons/icon-192x192.png') }}">
      <link rel="apple-touch-icon" sizes="512x512" href="{{ asset('public/storage/icons/icon-512x512.png') }}">
      
      @stack('before_styles_stack')
      @yield('before_styles')
      <link href="{{ url()->asset('css/app.css') }}?version={{VERSION}}" rel="stylesheet">
      @includeFirst([
      config('larapen.core.customizedViewPath') . 'layouts.inc.tools.style',
      'layouts.inc.tools.style',
      ])
      @stack('after_styles_stack')
      @yield('after_styles')
      <script src="{{ url()->asset('js/jquery.min.js') }}"></script>
      <script src="{{ url()->asset('js/popper.min.js') }}"></script>
      <script src="{{ url()->asset('js/bootstrap.bundle.min.js') }}"></script>
      <style>
          .navbar.navbar-site{
              box-shadow: 0 0 22px 1px #000;
          }
      </style>
   </head>
   <body class="{{ config('app.skin') }}">
      <div id="wrapper">
         @section('header')
         @includeFirst([
         config('larapen.core.customizedViewPath') . 'static.layouts.inc.header',
         'static.layouts.inc.header',
         ])
         @show
         <div id="main-content-div" style="background: #f4f4f4;">
            @yield('content')
         </div>
         @section('info')
         @show
         @section('footer')
         @includeFirst([config('larapen.core.customizedViewPath') . 'static.layouts.inc.footer', 'static.layouts.inc.footer'])
         @show
      </div>
      <style>
         .cookie-consent__agree {
         background: #78DAE7 !important;
         }
      </style>
      @include('cookieConsent::index')
      <script type="application/ld+json"> 
         { 
             "@context": "https://schema.org", 
             "@type": "EmploymentAgency", 
             "name": "Hungry For Jobs", 
             "slogan":"Find a job near you", 
             "description": "Find top jobs or top talent with Hungry For Jobs. Whether you're hiring or job hunting, we make the process fast and simple.", 
             "image": "https://hungryforjobs.com/public/assets/icon/logo.png", 
             "url": "https://hungryforjobs.com/", 
             "sameAs": [ 
                 "https://www.facebook.com/Hungry-For-Jobs-726772877757699", 
                 "https://www.instagram.com/HungryForJobs/", 
                 "https://www.tiktok.com/@hungryforjobs", 
                 "https://www.linkedin.com/company/hungryforjobs"
             ]  
         } 
      </script>
      @stack('before_scripts_stack')
      @yield('before_scripts')
      @stack('after_scripts_stack')
      @yield('after_scripts')
   </body>
</html>