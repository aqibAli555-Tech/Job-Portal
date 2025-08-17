<?php
$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

?>
<!-- City -->
<div class="block-title has-arrow sidebar-header">
    <h5 class="list-title collapsed" data-toggle="collapse" href="#countries" role="button" aria-expanded="false"
        aria-controls="countries">
        <span class="font-weight-bold">
            {{ t('All Countries') }}
        </span>
        <span class="accicon"><i class="fas fa-angle-up rotate-icon"></i></span>
    </h5>
</div>
<div class="block-content list-filter locations-list collapse" id="countries">
    <ul class="browse-list list-unstyled long-list">
        @if (isset($countries) && $countries->count() > 0)
        @foreach ($countries as $iCountry)
        <?php 
         if (str_contains($actual_link, '&country_code=')) {
            $link = str_replace('&country_code=' . request()->get('country_code'), '&country_code=' . $iCountry->code, $actual_link);
        }else{
            $link=$actual_link;
        }
        ?>

        <li>
            <strong>

                <a href="{{$link}}" 
                    title="{{ $iCountry->name }}">
                    {{ $iCountry->name }}
                    <span class="count"><?= $iCountry->count ?></span>
                </a>
            </strong>

        </li>

        @endforeach

        @endif
    </ul>
</div>
<div style="clear:both"></div>
