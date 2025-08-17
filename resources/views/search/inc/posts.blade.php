<style>
.job-actions {
    top: 0;
    position: absolute;
    right: 0;
}
</style>
<?php
if (!isset($cacheExpiration)) {
    $cacheExpiration = (int) config('settings.optimization.cache_expiration');
}

use App\Helpers\Localization\Country as CountryLocalization;
use Illuminate\Support\Facades\Storage;
?>
@if (!empty($posts) && $posts->count() > 0 && auth()->check() && auth()->user()->user_type_id == 2)
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

@if (isset($posts) && $posts->count() > 0)
<?php
if (!isset($cats)) {
    $cats = collect([]);
}

foreach ($posts as $key => $post) :
    $country = CountryLocalization::getCountryInfo($post->country->code);
    $post_time = date('M d, Y', strtotime($post->created_at));

    $country = CountryLocalization::getCountryInfo($post->country->code);
    $post_time = date('M d, Y', strtotime($post->created_at));
    $logo_show = \App\Helpers\Helper::get_post_logo($post->company);
    if (empty($post->postType) || empty($post->postDetail->city)) continue;
    // Get Package Info
    $premiumClass = '';
    $premiumBadge = '';
    if (isset($post->featured) && $post->featured == 1) {
        if (isset($post->latestPayment, $post->latestPayment->package) && !empty($post->latestPayment->package)) {
            $premiumClass = ' premium-post';
            $premiumBadge = ' <span class="badge badge-dark pull-right">' . $post->latestPayment->package->short_name . '</span>';
        }
    }

?>

@include('home.inc.posts_cards')

<!--/.job-item-->
<?php endforeach; ?>
@else
<div class="p-4" style="width: 100%;">
    @if (\Illuminate\Support\Str::contains(\Route::currentRouteAction(), 'Search\CompanyController'))
    {{ t('No jobs were found for this company') }}
    @else
    {{ t('no_result_refine_your_search') }}
    @endif
</div>
@endif

@section('modal_location')
@parent
@include('layouts.inc.modal.send-by-email')
@endsection

@section('after_scripts')
@parent
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
$(document).ready(function() {
    /* Get Post ID */
    $('.email-job').click(function() {
        var postId = $(this).attr("data-id");
        $('input[type=hidden][name=post]').val(postId);
        var url = siteUrl + '/posts/get_post_data/' + postId;
        $.ajax({
            type: "GET",
            url: url,
            dataType: 'json',
            success: function(c) {
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

    $('.applybutton').click(function(e) {
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
function savePost1(elmt) {
    var postId = $(elmt).closest('li').attr('id');

    $.ajax({
        method: 'POST',
        url: siteUrl + '/ajax/save/post',
        data: {
            'postId': postId,
            '_token': $('input[name=_token]').val()
        }
    }).done(function(data) {
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
            // if ($(elmt).hasClass('btn')) {
            //     $('#' + data.postId).removeClass('saved-job').addClass('saved-job');
            //     $('#' + data.postId + ' a').removeClass('save-job').addClass('saved-job');
            // } else {
            //     $(elmt).html('<span class="fa fa-heart"></span> ' + lang.labelSavePostRemove);
            // }
            Swal.fire({
                text: lang.confirmationSavePost,
                icon: "success",
                button: "Ok",
            }).then(function() {
                location.reload();
            });

        } else {
            // if ($(elmt).hasClass('btn')) {
            //     $('#' + data.postId).removeClass('save-job').addClass('save-job');
            //     $('#' + data.postId + ' a').removeClass('saved-job').addClass('save-job');
            // } else {
            //     $(elmt).html('<span class="far fa-heart"></span> ' + lang.labelSavePostSave);
            // }
            Swal.fire({
                text: lang.confirmationRemoveSavePost,
                icon: "success",
                button: "Ok",
            }).then(function() {
                location.reload();
            });
        }

        return false;
    });

    return false;
}
</script>
@endsection