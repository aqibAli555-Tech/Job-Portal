<?php
$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
?>
<!-- City -->
<div class="block-title has-arrow sidebar-header">
    <h5 class="list-title collapsed" data-toggle="collapse" href="#cities" role="button" aria-expanded="false"
        aria-controls="cities">
        <span class="font-weight-bold">
            {{ t('All Cities') }}
        </span>
        <span class="accicon"><i class="fas fa-angle-up rotate-icon"></i></span>
    </h5>
</div>
<div class="block-content list-filter locations-list collapse" id="cities">
    <ul class="browse-list list-unstyled long-list">
        @if (isset($cities) && $cities->count() > 0)

        @foreach ($cities as $iCity)
        <?php $cityName = json_decode($iCity->name);
              if(!empty($cityName->en)){
                $city_name=$cityName->en;
              }else{
                $city_name=$iCity->name;
              }
              if(str_contains($actual_link, '&l=')) {
                 $link=str_replace('&l='. request()->get('l'), '&l=' .$iCity->id, $actual_link);
                }else{
                    $link=$actual_link;
                }
        ?>
        <li>
            <a href="{{$link}}" title="{{ $city_name }}"
                @if(request()->get('l')==$iCity->id) style="{{$bold}}" @endif>
                {{ $city_name }} ({{ $iCity->post_count}})
            </a>
        </li>

        @endforeach
        @else
        <li>
            <a>
                {{  t('choose_country_first')}}

            </a>
        </li>
        @endif
    </ul>
</div>
<div style="clear:both"></div>