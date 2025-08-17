@extends('auth.layouts.affiliate.master')
@section('content')
<style>
    #wrapper, #main-content-div, #main-content-div > .row {
        height: 100%;
    }

    .make-money-image {
        min-height: 400px;
        width: 100%;
    }


    @media only screen and (max-width: 768px) {
        .quickLoginLeft {
            padding: 20px !important;
        }
    }

</style>
<div class="mt-lg-5">
    <div class="row">
        <div class="col-md-6">
            <div class="make-money-image">
                <img src="<?= url("storage/app/public/make_money.jpeg") ?>">
            </div>
        </div>
        <div class="col-md-6">
            <div class="main-container">
                <div class="container">
                    <div class="quickLoginLeft">
                        <h1><b>Join now to become a</b></h1>
                        <h1><b>Hungry for Jobs Affiliate</b></h1>
                        <p class="mt-3 mb-5"> Earn by promoting our recruitment services for the hospitality, food and beverage industries anywhere in the world.</p>
                        <a href="<?= url('affiliate-register') ?>" class="btn btn-primary btn-block makemoney-link" style="font-weight: bold;">
                            <span class="btn-text">{{ t('Join Now') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--How does it work-->
<div class="py-5 bg-light">
    <div class="container text-center">
        <h2 class="font-weight-bold mb-4">How it works</h2>
        <div class="row">
            <div class="col-md-4">
                <img src="{{url()->asset('home_icons/promote.png')}}" style="width: 40px; height: auto;" class="mb-3">
                <h2 class="font-weight-bold">Promote</h2>
                <p class="text-left">
                    Join our affiliate program for free and share your unique affiliate link to promote HungryForJobs.com. If you need marketing materials, feel free to check out our <a href="https://www.instagram.com/hungryforjobs/">Instagram account</a>, <a href="https://hungryforjobs.com">website</a>, or <a href="https://linkedin.com/company/hungryforjobs">our LinkedIn page</a>.
                </p>
                <p class="text-left">
                    Or feel free to use your own methods for promotion.
                </p>
            </div>
            <div class="col-md-4">
                <img src="{{url()->asset('home_icons/earn.png')}}" style="width: 40px; height: auto;" class="mb-3">
                <h2 class="font-weight-bold">Earn</h2>
                <p class="text-left">
                    Receive commissions for every new company you refer that registers on our website and then subscribes to one of our affordable monthly or yearly packages. There’s no cap on how much you can earn—more referrals mean more rewards. If companies renew their subscription with us, affiliates will continue to receive commissions as long as the company remains subscribed and paying.
                </p>
            </div>
            <div class="col-md-4">
                <img src="{{url()->asset('home_icons/track.png')}}" style="width: 50px; height: auto;" class="mb-1">
                <h2 class="font-weight-bold">Track</h2>
                <p class=" text-left">
                    Easily track your referrals and commissions through our real-time dashboard. Get detailed insights into your performance and optimize your marketing efforts with the help of our dedicated support team.
                </p>
            </div>
        </div>
        <div class="mt-4">
            <a href="<?= url('affiliate-register') ?>" class="btn btn-primary font-weight-bold makemoney-link">
                <span class="btn-text">{{ t('Join Now') }}</span>
            </a>
        </div>
    </div>
</div>
<div class="py-5 bg-light">
    <div class="container text-center">
        <div class="row">
            <div class="col-md-12">
                <h2 class="font-weight-bold">Why Join?</h2>
                <p class=" text-left">
                    Performance-Based Payouts: We offer commissions in a tier system ranging from 10%-50%, depending on the sales you generate. We have low, medium, and high-ticket value sales. The more you sell on a monthly basis, the higher your commission percentage will be. To retain your commission tier, you’ll need to maintain a certain monthly sales value. The higher your monthly sales, the higher your commission rate.
                </p>
            </div>
            <div class="col-md-12 mt-5">
                <h2 class="font-weight-bold">Support</h2>
                <p class="text-left">
                    As a HungryForJobs affiliate, you’ll receive personalized support to help you succeed. Our dedicated affiliate team is available to assist with anything from marketing strategy to technical questions. We’ll provide insights to optimize your campaigns and boost your sales. Whether you need help understanding your dashboard, accessing more marketing materials, or getting advice on the best marketing techniques, our team is here to provide tailored assistance. We offer ongoing support to help you maximize your earnings and ensure you stay on track with your affiliate goals.
                </p>
            </div>
        </div>
    </div>
</div>
<!--Affiliate Faqs-->
<div class="py-5">
    <div class="container text-center">
        <h2 class="font-weight-bold mb-4">Affiliate FAQs</h2>
        <div id="accordion" class="">
            <div class="card border-0">
                <div class="card-header bg-white" id="heading1">
                    <button class="btn btn-block text-left d-flex justify-content-between align-items-center collapsed" data-toggle="collapse" data-target="#collapse1" aria-expanded="false" aria-controls="collapse1" style="white-space: normal;">
                        <span>
                            How do I sign up for the HungryForJobs Affiliate Program?
                        </span>
                        <span class="ml-2 dropdown-arrow"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </div>
                <div id="collapse1" class="collapse" aria-labelledby="heading5" data-parent="#accordion">
                    <div class="card-body bg-white text-left">
                        To sign up for the HungryForJobs Affiliate Program, simply visit our website and complete the affiliate registration form. Once registered, you’ll receive a unique affiliate link on your dashboard to start promoting.
                    </div>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-header bg-white" id="heading2">
                    <button class="btn btn-block text-left d-flex justify-content-between align-items-center collapsed" data-toggle="collapse" data-target="#collapse2" aria-expanded="false" aria-controls="collapse2" style="white-space: normal;">
                        <span>
                            How do I promote HungryForJobs?
                        </span>
                        <span class="ml-2 dropdown-arrow"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </div>
                <div id="collapse2" class="collapse" aria-labelledby="heading5" data-parent="#accordion">
                    <div class="card-body bg-white text-left">
                        Once you're registered, you can promote HungryForJobs online by copying and pasting your affiliate link from your dashboard. You can share this link on your website, social media, or via email campaigns. Additionally, you can reach out to us for marketing materials, or feel free to use your own methods for promotion.
                    </div>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-header bg-white" id="heading3">
                    <button class="btn btn-block text-left d-flex justify-content-between align-items-center collapsed" data-toggle="collapse" data-target="#collapse3" aria-expanded="false" aria-controls="collapse3" style="white-space: normal;">
                        <span>
                            How do I track my sales and commissions?
                        </span>
                        <span class="ml-2 dropdown-arrow"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </div>
                <div id="collapse3" class="collapse" aria-labelledby="heading5" data-parent="#accordion">
                    <div class="card-body bg-white text-left">
                        You can track all of your referrals and commissions via your real-time affiliate dashboard. It provides detailed insights into your performance, allowing you to monitor the progress of your referrals and earnings.
                    </div>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-header bg-white" id="heading4">
                    <button class="btn btn-block text-left d-flex justify-content-between align-items-center collapsed" data-toggle="collapse" data-target="#collapse4" aria-expanded="false" aria-controls="collapse4" style="white-space: normal;">
                        <span>
                            What are the commission rates for the HungryForJobs Affiliate Program?
                        </span>
                        <span class="ml-2 dropdown-arrow"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </div>
                <div id="collapse4" class="collapse" aria-labelledby="heading5" data-parent="#accordion">
                    <div class="card-body bg-white text-left">
                        We offer commissions in a tier system ranging from 10%-50%, depending on the sales you generate. The more you sell, the higher your commission percentage will be. You’ll need to maintain a certain monthly sales value to retain your commission tier. The higher your monthly sales, the higher your commission rate. Commission tiers and percentages are mentioned on your dashboard.
                    </div>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-header bg-white" id="heading5">
                    <button class="btn btn-block text-left d-flex justify-content-between align-items-center collapsed" data-toggle="collapse" data-target="#collapse5" aria-expanded="false" aria-controls="collapse5" style="white-space: normal;">
                        <span>
                            How and when will I be paid?
                        </span>
                        <span class="ml-2 dropdown-arrow"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </div>
                <div id="collapse5" class="collapse" aria-labelledby="heading5" data-parent="#accordion">
                    <div class="card-body bg-white text-left">
                        Commissions are paid monthly, and payments will be processed via PayPal or direct bank transfer. You’ll receive your earnings for the previous month once your earnings meet the payment threshold. Please note that we will deduct any transfer fees from the initial commission amount.
                    </div>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-header bg-white" id="heading6">
                    <button class="btn btn-block text-left d-flex justify-content-between align-items-center collapsed" data-toggle="collapse" data-target="#collapse6" aria-expanded="false" aria-controls="collapse6" style="white-space: normal;">
                        <span>
                            What if a company asks for a refund within 14 days of subscribing?
                        </span>
                        <span class="ml-2 dropdown-arrow"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </div>
                <div id="collapse6" class="collapse" aria-labelledby="heading5" data-parent="#accordion">
                    <div class="card-body bg-white text-left">
                        If a company requests a refund within 14 days of subscribing but hasn’t used our services (e.g., posting a job or opening CVs), the affiliate commission for that referral will be reversed. This refund will also be reflected on the affiliate’s dashboard.
                    </div>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-header bg-white" id="heading7">
                    <button class="btn btn-block text-left d-flex justify-content-between align-items-center collapsed" data-toggle="collapse" data-target="#collapse7" aria-expanded="false" aria-controls="collapse7" style="white-space: normal;">
                        <span>
                            How do I know if my referral was successful?
                        </span>
                        <span class="ml-2 dropdown-arrow"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </div>
                <div id="collapse7" class="collapse" aria-labelledby="heading5" data-parent="#accordion">
                    <div class="card-body bg-white text-left">
                        Once a company you’ve referred registers on our website and subscribes to one of our packages, the referral will appear in your affiliate dashboard, and you’ll earn a commission for that sale. You can track all your successful referrals and earnings in real time.
                    </div>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-header bg-white" id="heading8">
                    <button class="btn btn-block text-left d-flex justify-content-between align-items-center collapsed" data-toggle="collapse" data-target="#collapse8" aria-expanded="false" aria-controls="collapse8" style="white-space: normal;">
                        <span>
                            How do I request a payout?
                        </span>
                        <span class="ml-2 dropdown-arrow"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </div>
                <div id="collapse8" class="collapse" aria-labelledby="heading5" data-parent="#accordion">
                    <div class="card-body bg-white text-left">
                        To request a payout, simply log into your affiliate dashboard and click the "Request Payout" button. Please note that there is a minimum waiting period of 30 days from the end of the month before you can request a payout. This ensures that all transactions are confirmed and commissions are accurate.
                    </div>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-header bg-white" id="heading9">
                    <button class="btn btn-block text-left d-flex justify-content-between align-items-center collapsed" data-toggle="collapse" data-target="#collapse9" aria-expanded="false" aria-controls="collapse9" style="white-space: normal;">
                        <span>
                            What if a company cancels or doesn’t renew their subscription?                        
                        </span>
                        <span class="ml-2 dropdown-arrow"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </div>
                <div id="collapse9" class="collapse" aria-labelledby="heading5" data-parent="#accordion">
                    <div class="card-body bg-white text-left">
                        Affiliates earn commissions as long as the referred company remains subscribed and paying. If the company cancels or doesn’t renew their subscription, the affiliate will no longer receive commissions for that company.
                    </div>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-header bg-white" id="heading10">
                    <button class="btn btn-block text-left d-flex justify-content-between align-items-center collapsed" data-toggle="collapse" data-target="#collapse10" aria-expanded="false" aria-controls="collapse10" style="white-space: normal;">
                        <span>
                            Can I use HungryForJobs promotional materials?                        
                        </span>
                        <span class="ml-2 dropdown-arrow"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </div>
                <div id="collapse10" class="collapse" aria-labelledby="heading5" data-parent="#accordion">
                    <div class="card-body bg-white text-left">
                        Yes! You can check out our Instagram account at instagram.com/hungryforjobs, our website at hungryforjobs.com, or our LinkedIn page at linkedin.com/company/hungryforjobs for marketing materials. If you need more resources, feel free to reach out to us, and we’ll be happy to assist.
                    </div>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-header bg-white" id="heading11">
                    <button class="btn btn-block text-left d-flex justify-content-between align-items-center collapsed" data-toggle="collapse" data-target="#collapse11" aria-expanded="false" aria-controls="collapse11" style="white-space: normal;">
                        <span>
                            Are there any restrictions on how I can promote HungryForJobs?                        
                        </span>
                        <span class="ml-2 dropdown-arrow"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </div>
                <div id="collapse11" class="collapse" aria-labelledby="heading5" data-parent="#accordion">
                    <div class="card-body bg-white text-left">
                        We ask that all affiliates adhere to legal marketing practices and guidelines. You are free to promote HungryForJobs as you see fit, but please avoid misleading claims or unethical practices. Our team can assist you with recommendations to ensure you stay within acceptable marketing practices.
                    </div>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-header bg-white" id="heading12">
                    <button class="btn btn-block text-left d-flex justify-content-between align-items-center collapsed" data-toggle="collapse" data-target="#collapse12" aria-expanded="false" aria-controls="collapse12" style="white-space: normal;">
                        <span>
                            How do I get paid if I reach the next tier in commissions?                        
                        </span>
                        <span class="ml-2 dropdown-arrow"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </div>
                <div id="collapse12" class="collapse" aria-labelledby="heading5" data-parent="#accordion">
                    <div class="card-body bg-white text-left">
                        If you achieve a higher tier based on your monthly sales, your commission rate will automatically increase, and your next payment will reflect the updated rate. Be sure to maintain your sales performance to keep your tier.
                    </div>
                </div>
            </div>
            <div class="card border-0">
                <div class="card-header bg-white" id="heading13">
                    <button class="btn btn-block text-left d-flex justify-content-between align-items-center collapsed" data-toggle="collapse" data-target="#collapse13" aria-expanded="false" aria-controls="collapse13" style="white-space: normal;">
                        <span>
                            Is HungryForJobs available globally?                        
                        </span>
                        <span class="ml-2 dropdown-arrow"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </div>
                <div id="collapse13" class="collapse" aria-labelledby="heading5" data-parent="#accordion">
                    <div class="card-body bg-white text-left">
                        Yes! HungryForJobs is a global platform. Any user can register with us, and companies from anywhere in the world can subscribe and pay for our services. Our platform serves the hospitality, food and beverage industries worldwide.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <script>
        $(document).on('click', '.makemoney-link', function (e) {
            var $link = $(this);
            var $btnText = $link.find('.btn-text');
            $link.addClass('disabled').css('pointer-events', 'none').css('opacity', '0.6');
            $btnText.html('<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Join Now');
        });    
    </script>
@section('footer')
    @includeFirst([config('larapen.core.customizedViewPath') . 'affiliate.inc.footer', 'affiliate.inc.footer'])
@show
@endsection
