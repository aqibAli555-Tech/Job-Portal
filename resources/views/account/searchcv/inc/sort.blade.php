<?php
$actual_link=\Request::fullUrl();

?>
<!-- City -->
<div class="block-title has-arrow sidebar-header">
    <h5 class="list-title collapsed" data-toggle="collapse" href="#sort" role="button" aria-expanded="false"
        aria-controls="sort">
        <span class="font-weight-bold">
            {{ t('latest updated profile') }}
        </span>
        <span class="accicon"><i class="fas fa-angle-up rotate-icon"></i></span>
    </h5>
</div>
<div class="block-content list-filter locations-list collapse" id="sort">
    <ul class="browse-list list-unstyled long-list">
        <li>
            <a href="<?= App\Helpers\Helper::replace_param_in_url('sort','new_to_old') ?>" title="{{ t('new to old') }}" @if(empty(request()->get('sort'))) style="{{$bold}}" @endif>
            {{ t('new to old') }}
            
            </a>
        </li>
        <li>
            <a href="<?= App\Helpers\Helper::replace_param_in_url('sort','old_to_new') ?>" title="{{ t('old to new') }}" @if(empty(request()->get('sort'))) style="{{$bold}}" @endif>
            {{ t('old to new') }}
            
            </a>
        </li>
    </ul>
</div>
<div style="clear:both"></div>