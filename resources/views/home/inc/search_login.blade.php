<?php

// Using data base model of country and employee skill for using values in search box
use App\Models\Country;
use App\Models\HomeSection;
use App\Models\Post;

$posts = Post::get_all_latest_post();
$countries = Country::get_all_country_with_postCount();
$countrie_name = Country::all();
$selected_post = !empty(request('post')) ? request('post') : '';
$country_code = !empty(request('country_code')) ? request('country_code') : '';
$actual_link=url('latest-jobs?post=&country_code=&q=&l=&min_salary=&max_salary=&type[]=');




// Init.
$sForm = [
    'enableFormAreaCustomization' => '0',
    'hideTitles' => '0',
    'title' => t('Find a job near you'),
    'subTitle' => t('IN THE FOOD, BEVERAGE & HOSPITALITY INDUSTRIES'),
    'bigTitleColor' => '', // 'color: #FFF;',
    'subTitleColor' => '', // 'color: #FFF;',
    'backgroundColor' => '', // 'background-color: #444;',
    'backgroundImage' => '', // null,
    'height' => '', // '450px',
    'parallax' => '0',
    'hideForm' => '0',
    'formBorderColor' => '', // 'background-color: #7324bc;',
    'formBorderSize' => '', // '5px',
    'formBtnBackgroundColor' => '', // 'background-color: #7324bc; border-color: #7324bc;',
    'formBtnTextColor' => '', // 'color: #FFF;',
];

?>

<div class="h-spacer"></div>
<div id="homepage">
    <div class="wide-intro">
        <div class="dtable hw100" style="background: rgba(0,0,0,0.5)">
            <div class="dtable-cell hw100">
                <div class="container text-center">

                    <h1 class="intro-title animated fadeInDown">{{t('Find a job')}} <span
                            style="color:#22d3fd">{{t('near you ')}}</span>
                    </h1>
                    <h2 class="sub animateme fittext3 animated fadeIn" style="color:#fff;">
                        {!! $sForm['subTitle'] !!}
                    </h2>


                    <div class="search-row animated fadeInUp rounded">
                        <form id="search" name="search">
                            <div class="row m-0">
                                <div class="col-md-5 col-sm-12 mb-1 mb-xl-0 mb-lg-0 mb-md-0 search-col relative">
                                    <select name="post" id="select-a-skill" class="form-control keyword"
                                        placeholder="{{ t('What') }}" value="">
                                        <option value="">{{t('All Jobs')}}</option>
                                        @if(!empty($posts))
                                        @foreach($posts as $post)
                                        <option value="{{$post->id}}"
                                            <?php if($selected_post == $post->id){echo "selected";}?>>{{$post->title}}
                                        </option>
                                        @endforeach
                                        @endif
                                    </select>

                                </div>
                                <div class="col-md-5 col-sm-12 search-col relative locationicon">
                                    <select name="country_code" class="form-control" id="select-a-location">
                                        <option value="">{{t('All Countries')}}</option>
                                        @if (isset($countries) && $countries->count() > 0)
                                        @foreach($countries as $iCountry)

                                        <option value="{{$iCountry->code}}"
                                            <?php if($country_code == $iCountry->code){echo "selected";}?>>
                                            {{$iCountry->name}}{{"   (".$iCountry->count.")"}}</option>
                                        @endforeach
                                        @endif
                                    </select>

                                </div>
                                <input type="hidden" value="{{$actual_link}}" name="post_url" id="post_url">

                                <div class="col-md-2 col-sm-12 search-col">
                                    <button type="button" class="btn btn-primary btn-search btn-block"
                                        onclick="submitPostForm()">
                                        <i class="icon-search"></i> {{ t('Find') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="diagonal-banner"></div>
<?php
$publicDisk = Storage::disk(config('filesystems.default'));

$post = HomeSection::withoutGlobalScopes()
    ->where('id', 1)
    ->first();
$backgroun_image = $publicDisk->url($post->value['background_image']);
?>

<style>
#select-a-skill option,
#select-a-location option {
    background: #000 !important;
}

.wide-intro {
    background-image: url("{{ url()->asset('images/header.jpeg') }}");
    background-size: cover;
}
</style>

<script>
function submitPostForm() {
    var urlmy = $('#post_url').val();
 
    var url = new URL(urlmy);
    var search_params = url.searchParams;
    var post = $('#select-a-skill').val();
    var country_code = $('#select-a-location').val();
    search_params.set('post', post);
    search_params.set('country_code', country_code);
    // change the search property of the main url
    url.search = search_params.toString();
    // the new url string
    var new_url = url.toString();
    window.location.replace(new_url);
}
</script>