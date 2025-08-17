<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{!! 'Join HungryForJobs Affiliate Program & Earn Commission | HFJ' !!}</title>

    <!-- Canonical -->
    <link rel="canonical" href="https://hungryforjobs.com/affiliate-program" />

    <!-- Google / Search Engine Tags -->
    <meta name="name" content="Hungry For Jobs">
    <meta name="description" content="{!! 'Turn your network into revenue with our affiliate program. Earn commissions for every company you refer, with no limits on how much you can make. Start now!' !!}">
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

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="Earn Commissions Worldwide with Hungry for Jobs Affiliate Program!">
    <meta property="og:site_name" content="HugryForJobs">
    <meta property="og:url" content="https://hungryforjobs.com/affiliate-program"> 
    <meta property="og:description" content="Join Hungry for Jobs’ affiliate program and start earning today. Refer companies and earn commissions on their subscriptions, with payouts up to 50%.">


    
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

    <script type="application/ld+json"> 
        { 
            "@context": "http://schema.org", 
            "@type": "Organization", 
            "logo":{
                "@type": "ImageObject",
                "url": "https://hungryforjobs.com/public/assets/icon/logo.png",
                
                "description": "HungryForJobs is a leading online job portal connecting employers with skilled talent in the hospitality, food, and beverage industries. Our platform offers free access to job seekers looking for flexible roles and employers seeking urgent staffing solutions. Whether you’re hiring staff for restaurants, cafes, or bistros, or searching for your next career opportunity, HungryForJobs streamlines recruitment with ease and efficiency."
            },
            
            "name": "HungryForJobs",
            
            "url": "https://hungryforjobs.com/",
            
            "image": "https://hungryforjobs.com/public/assets/icon/logo.png",
            
            "sameAs":["https://www.facebook.com/Hungry-For-Jobs-726772877757699","https://www.instagram.com/HungryForJobs/","https://www.tiktok.com/@hungryforjobs","https://www.linkedin.com/company/hungryforjobs/"]  	      	 
            
        }
    </script>
    
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "FAQPage", 

            "mainEntity": [{ 

                    "@type": "Question", 

                    "name": "How do I sign up for the HungryForJobs Affiliate Program?", 

                "acceptedAnswer": { 

                    "@type": "Answer", 

                    "text": "To sign up for the HungryForJobs Affiliate Program, simply visit our website and complete the affiliate registration form. Once registered, you’ll receive a unique affiliate link on your dashboard to start promoting." 

                    } 

            }, 

            { 

                    "@type": "Question", 

                    "name": "How do I promote HungryForJobs?", 

                "acceptedAnswer": { 

                    "@type": "Answer", 

                    "text": "Once you're registered, you can promote HungryForJobs online by copy pasting your affiliate link from your dashboard. You can share this link on your website, social media, or via email campaigns. Additionally, you can reach out to us for marketing materials, or feel free to use your own methods for promotion." 

                    } 

            }, 

            { 

                    "@type": "Question", 

                    "name": "How do I track my sales and commissions?", 

                "acceptedAnswer": { 

                    "@type": "Answer", 

                    "text": "You can track all of your referrals and commissions via your real-time affiliate dashboard. It provides detailed insights into your performance, allowing you to monitor the progress of your referrals and earnings." 

                    } 

            },{ 

                    "@type": "Question", 

                    "name": "What are the commission rates for the HungryForJobs Affiliate Program?", 

                "acceptedAnswer": { 

                    "@type": "Answer", 

                    "text": "We offer commissions in a tier system ranging from 10%-50%, depending on the sales you generate. The more you sell, the higher your commission percentage will be. You’ll need to maintain a certain monthly sales value to retain your commission tier. The higher your monthly sales, the higher your commission rate. Commission tiers and percentages are mentioned on your dashboard." 

                    } 

            },{ 

                    "@type": "Question", 

                    "name": "How and when will I be paid?", 

                "acceptedAnswer": { 

                    "@type": "Answer", 

                    "text": "Commissions are paid monthly, and payments will be processed via PayPal or direct bank transfer. You’ll receive your earnings for the previous month once your earnings meet the payment threshold. Please note that we will deduct any transfer fees from the initial commission amount." 

                    } 

            },{ 

                    "@type": "Question", 

                    "name": "What if a company asks for a refund within 14 days of subscribing?", 

                "acceptedAnswer": { 

                    "@type": "Answer", 

                    "text": "If a company requests a refund within 14 days of subscribing but hasn’t used our services (e.g., posting a job or opening CVs), the affiliate commission for that referral will be reversed. This refund will also be reflected on the affiliate’s dashboard." 

                    } 

            }, 

            { 

                    "@type": "Question", 

                    "name": "How do I know if my referral was successful?", 

                "acceptedAnswer": { 

                    "@type": "Answer", 

                    "text": "Once a company you’ve referred registers on our website and subscribes to one of our packages, the referral will appear in your affiliate dashboard, and you’ll earn a commission for that sale. You can track all your successful referrals and earnings in real time." 

                    } 

            }, 

            { 

                    "@type": "Question", 

                    "name": "How do I request a payout?", 

                "acceptedAnswer": { 

                    "@type": "Answer", 

                    "text": "To request a payout, simply log into your affiliate dashboard and click the Request Payout button. Please note that there is a minimum waiting period of 30 days from the end of the month before you can request a payout. This ensures that all transactions are confirmed and commissions are accurate." 

                    } 

            },{ 

                    "@type": "Question", 

                    "name": "What if a company cancels or doesn't renew their subscription?", 

                "acceptedAnswer": { 

                    "@type": "Answer", 

                    "text": "Affiliates earn commissions as long as the referred company remains subscribed and paying. If the company cancels or doesn’t renew their subscription, the affiliate will no longer receive commissions for that company." 

                    } 

            },{ 

                    "@type": "Question", 

                    "name": "Can I use HungryForJobs promotional materials?", 

                "acceptedAnswer": { 

                    "@type": "Answer", 

                    "text": "Yes! You can check out our Instagram account at instagram.com/hungryforjobs, our website at hungryforjobs.com, or our LinkedIn page at linkedin.com/company/hungryforjobs for marketing materials. If you need more resources, feel free to reach out to us, and we’ll be happy to assist." 

                    } 

            },{ 

                    "@type": "Question", 

                    "name": "Are there any restrictions on how I can promote HungryForJobs?", 

                "acceptedAnswer": { 

                    "@type": "Answer", 

                    "text": "We ask that all affiliates adhere to legal marketing practices and guidelines. You are free to promote HungryForJobs as you see fit, but please avoid misleading claims or unethical practices. Our team can assist you with recommendations to ensure you stay within acceptable marketing practices." 

                    } 

            }, 

            { 

                    "@type": "Question", 

                    "name": "How do I get paid if I reach the next tier in commissions?", 

                "acceptedAnswer": { 

                    "@type": "Answer", 

                    "text": "If you achieve a higher tier based on your monthly sales, your commission rate will automatically increase, and your next payment will reflect the updated rate. Be sure to maintain your sales performance to keep your tier." 

                    } 

            },{ 
                "@type": "Question",
                "name": "Is HungryForJobs available globally?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Yes! HungryForJobs is a global platform. Any user can register with us, and companies from anywhere in the world can subscribe and pay for our services. Our platform serves the hospitality, food and beverage industries worldwide."
                }
            }
            ]
        } 
</script>
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