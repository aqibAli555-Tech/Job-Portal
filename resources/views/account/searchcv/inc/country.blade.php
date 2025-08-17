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
        @if (isset($data['countries']) && $data['countries']->count() > 0)
        <li>
            <a href="<?= App\Helpers\Helper::replace_param_in_url('country','') ?>" title="All Countries" @if(empty(request()->get('country'))) style="{{$bold}}" @endif>
                All Countries
            </a>
        </li>
        @foreach ($data['countries'] as $iCountry)
        <?php 
         $link= App\Helpers\Helper::replace_param_in_url('country',$iCountry->code);
        ?>
        <li>
            <a href="<?= $link ?>" title="{{ $iCountry->name }}" @if(request()->get('country')==$iCountry->code)
                style="{{$bold}}" @endif>
                {{ $iCountry->name }} ({{$iCountry->count}})
            </a>
        </li>

        @endforeach

        @endif
    </ul>
</div>
<div style="clear:both"></div>