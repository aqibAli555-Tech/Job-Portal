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
        @if (isset($data['cities']) && $data['cities']->count() > 0)
        <li>
            <a href="<?=App\Helpers\Helper::replace_param_in_url('city','')?>" title="All Cities" @if(empty(request()->get('city'))) style="{{$bold}}" @endif >
            All Cities
            </a>
        </li>
        @foreach ($data['cities'] as $iCity)
        <?php 
        $link= App\Helpers\Helper::replace_param_in_url('city',$iCity->id);
        ?>
        <li>
            <a href="<?=$link?>" title="{{ $iCity->name }}" @if(request()->get('city')==$iCity->id) style="{{$bold}}" @endif>
                {{ $iCity->name }} ({{ $iCity->count}})
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