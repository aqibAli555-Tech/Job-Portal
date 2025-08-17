<style>
    .country_image {
        display: inline-block !important;
        width: auto !important;
    }

    .img-caption-heading {
        position: absolute;
        bottom: 10px;
        left: 0;
        right: 0;
        margin: auto;
        color: #fff;
        text-align: center;
    }

</style>
<?php

use App\Helpers\Localization\Country as CountryLocalization;
use Illuminate\Support\Facades\Storage;

if (!isset($cacheExpiration)) {
    $cacheExpiration = (int)config('settings.optimization.cache_expiration');
}
$hideOnMobile = '';
if (isset($latestOptions, $latestOptions['hide_on_mobile']) and $latestOptions['hide_on_mobile'] == '1') {
    $hideOnMobile = ' hidden-sm';
}
?>



@if (isset($latest) && !empty($latest) && $latest->posts->count() > 0)
    @includeFirst([config('larapen.core.customizedViewPath') . 'home.inc.spacer', 'home.inc.spacer'], ['hideOnMobile' =>
    $hideOnMobile])

<style>
    @media (max-width: 767px) {
        .col_image_icon {
            flex: 0 0 33.33%; /* Each column takes up 33.33% width */
            max-width: 33.33%; /* Optional: Limit the maximum width of each column */
            display: block; /* Ensure columns stack vertically */
        }
    }
    .icon-image{
        max-width: 70%;
        margin-bottom: 35px;
    }
    .image-container_icon h4,.image-container_icon h3{
        font-weight:500;
    }
    .image-container_icon h3{
        font-size:17px;
        line-height: 20px;
    }

</style>
    <div class="container{{ $hideOnMobile }}">
        <h2 class="company-name-heading">
            {{ t('Why Should Companies Register With') }} 
            <span style="color:#78DAE7">{{ t('Hungry For Jobs') }}</span>
            <span style="color:#615583">?</span>
        </h2>
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-md-2 col-sm-4 col-6">
                    <div class="image-container_icon">
                        <img class="icon-image" src="{{url()->asset('home_icons/1.png')}}">
                        <h3>0% <br>AGENCY FEES</h3>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-6">
                    <div class="image-container_icon">
                        <img class="icon-image" src="{{url()->asset('home_icons/2.png')}}">
                        <h3>THOUSANDS <br>OF EMPLOYEES</h3>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-6">
                    <div class="image-container_icon">
                        <img class="icon-image" src="{{url()->asset('home_icons/3.png')}}">
                        <h3>UNLIMITED <br>JOBS POSTING</h3>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-6">
                    <div class="image-container_icon">
                        <img class="icon-image" src="{{url()->asset('home_icons/4.png')}}">
                        <h3>FREE CV <br>SEARCH</h3>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center text-center mt-4">
                <div class="col-md-2 col-sm-4 col-6">
                    <div class="image-container_icon">
                        <img class="icon-image" src="{{url()->asset('home_icons/5.png')}}">
                        <h3>AFFORDABLE <br>SUBSCRIPTION FEES</h3>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-6">
                    <div class="image-container_icon">
                        <img class="icon-image" src="{{url()->asset('home_icons/6.png')}}">
                        <h3>HIRE LOCALLY & INTERNATIONALLY</h3>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 col-6">
                    <div class="image-container_icon">
                        <img class="icon-image" src="{{url()->asset('home_icons/affiliate-new.png')}}">
                        <h3>PARTNER UP & EARN MONEY*</h3>
                    </div>
                </div>
            </div>

            <p style="font-size: 16px; text-align: center; margin-top: 20px;">
                <!--*Only available on certain subscription packages <br>-->
                *Sign Up to our Affiliate program
            </p>
        </div>
        
        @includeFirst([config('larapen.core.customizedViewPath') . 'home.inc.industries', 'home.inc.industries'])
        <br>
        @includeFirst([config('larapen.core.customizedViewPath') . 'home.inc.employees', 'home.inc.employees'])
        <br>
        <h2 class="company-name-heading">
            Featured Jobs
        </h2>

        <div class="">
            @if (!empty($latest->posts) && $latest->posts->count() > 0 && auth()->check() && auth()->user()->user_type_id == 2)
                <form id="applynowform" role="form" method="POST" action="" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                    <input id="from_phone" type="hidden" name="from_phone"
                           value="{{ old('from_phone', auth()->check() ? auth()->user()->phone : '') }}">
                    <input type="hidden" name="from_email" value="{{ auth()->user()->email }}">
                    <input type="hidden" name="from_name" value="{{ auth()->user()->name }}">
                    <input type="hidden" id="post_url" name="url" value="">
                    <input type="hidden" name="body" id="post_body" value="">
                    <input type="hidden" name="country_code" value="{{ config('country.code') }}">
                    <input type="hidden" name="post_id" id="post_id" value="">
                    <input type="hidden" name="messageForm" value="1">
                    <input type="hidden" name="from_main" value="1">
                </form>
            @endif
                <div id="jobs-slider-home" class="owl-carousel owl-theme">

                        <?php
                        $postCount = count($latest->posts);
                    foreach ($latest->posts as $key => $post) {
                        $country = CountryLocalization::getCountryInfo($post->country->code);
                        $post_time = date('M d, Y', strtotime($post->created_at));
                        $logo_show = \App\Helpers\Helper::get_post_logo($post->company);
                        $premiumClass = '';
                        $premiumBadge = '';

                        if (isset($post->featured) && $post->featured == 1) {
                            if (isset($post->latestPayment, $post->latestPayment->package) && !empty($post->latestPayment->package)) {
                                $premiumClass = ' premium-post';
                                $premiumBadge = ' <span class="badge badge-dark pull-right"></span>';
                            }
                        }

                        if ($key == 0 || $key % 2 == 0) {
                            echo '<div class="slide" style="text-align: left;">';
                        }
                        ?>

                    @include('home.inc.posts_cards')

                        <?php
                        if ($key % 2 == 1 || $key == $postCount - 1) {
                            echo '</div>';
                        }
                        ?>
                    <?php } ?>
                </div>

                <br>
            <center>
                <a class="employees-hungry-for-jobs-view-more" href="{{ \App\Helpers\UrlGen::search() }}">
                    <i class="icon-briefcase"></i> {{ t('VIEW ALL JOBS') }}
                </a>
            </center>
            <br>

        </div>


    </div>

@endif

@section('modal_location')
    @parent
    @includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.modal.send-by-email',
    'layouts.inc.modal.send-by-email'])
@endsection

@section('after_scripts')
    @parent
    <script>

        $(document).ready(function () {
            /* Get Post ID */
            $('.email-job').click(function () {
                var postId = $(this).attr("data-id");
                $('input[type=hidden][name=post]').val(postId);
                var url = siteUrl + '/posts/get_post_data/' + postId;
                $.ajax({
                    type: "GET",
                    url: url,
                    dataType: 'json',
                    success: function (c) {
                        if (c) {
                            var fb = document.getElementById(
                                'fb_share'); //or grab it by tagname etc
                            fb.href = "https://www.facebook.com/sharer.php?u=" + c +
                                "&t=HFJ Job";

                            var twitter = document.getElementById(
                                'twitter_share'); //or grab it by tagname etc
                            twitter.href = "https://twitter.com/intent/tweet?url=" + c;

                            var whatsapp = document.getElementById(
                                'whatsapp_share'); //or grab it by tagname etc
                            whatsapp.href = "https://api.whatsapp.com/send?text=" + c;

                            var telegram = document.getElementById(
                                'telegram_share'); //or grab it by tagname etc
                            telegram.href = "https://telegram.me/share/url?url=" + c +
                                "&text=HFJ JOB";


                        }
                    }
                });

            });

            $('.applybutton').click(function (e) {
                var post_id = $(this).data('id');
                var post_url = $(this).data('url');
                var post_body = $(this).data('body');
                $('#post_id').val(post_id);
                $('#post_url').val(post_url);
                $('#post_body').val(post_body);
                $('#applynowform').attr('action', "{{ url('account/apply_post') }}" + "/" + post_id);
                $('#applynowform').submit();
                $('#overlay').show();
            });

            @if(isset($errors) && $errors -> any())
            @if(old('sendByEmailForm') == '1')
            $('#sendByEmail').modal();
            @endif
            @endif
        });
    </script>
    <script>
        /* Favorites Translation */
        var lang = {
            labelSavePostSave: "{!! t('Save Job') !!}",
            labelSavePostRemove: "{{ t('Saved Job') }}",
            loginToSavePost: "{!! t('Please log in to save the Ads') !!}",
            loginToSaveSearch: "{!! t('Please log in to save your search') !!}",
            confirmationSavePost: "{!! t('Post saved in favorites successfully') !!}",
            confirmationRemoveSavePost: "{!! t('Post deleted from favorites successfully') !!}",
            confirmationSaveSearch: "{!! t('Search saved successfully') !!}",
            confirmationRemoveSaveSearch: "{!! t('Search deleted successfully') !!}"
        };

        function savePost1(elmt) {
            var postId = $(elmt).closest('li').attr('id');

            $.ajax({
                method: 'POST',
                url: siteUrl + '/ajax/save/post',
                data: {
                    'postId': postId,
                    '_token': $('input[name=_token]').val()
                }
            }).done(function (data) {
                if (typeof data.logged == "undefined") {
                    return false;
                }

                /* Guest Users - Need to Log In */
                if (data.logged == 0) {
                    $('#quickLogin').modal();
                    return false;
                }

                /* Logged Users - Notification */
                if (data.status == 1) {
                    $(elmt).html('<span class="fa fa-heart"></span> ' + lang.labelSavePostRemove);
                    // }
                    Swal.fire({
                        text: lang.confirmationSavePost,
                        icon: "success",
                        button: "Ok",
                    }).then(function () {
                        location.reload();
                    });

                } else {

                    Swal.fire({
                        text: lang.confirmationRemoveSavePost,
                        icon: "success",
                        button: "Ok",
                    }).then(function () {
                        location.reload();
                    });
                }

                return false;
            });

            return false;
        }

        $(document).ready(function () {
            /* Get Post ID */
            $('.email-job').click(function () {
                var postId = $(this).attr("data-id");
                $('input[type=hidden][name=post]').val(postId);
            });
            document.addEventListener("DOMContentLoaded", function () {
                $('#quickLogin').modal('show');
            });


        });
    </script>

@endsection