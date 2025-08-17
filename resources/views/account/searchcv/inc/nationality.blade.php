<?php
$actual_link=\Request::fullUrl();

?>
<!-- City -->
<div class="block-title has-arrow sidebar-header">
    <h5 class="list-title collapsed" data-toggle="collapse" href="#nationalities" role="button" aria-expanded="false"
        aria-controls="nationalities">
        <span class="font-weight-bold">
            {{ t('All Nationalities') }}
        </span>
        <span class="accicon"><i class="fas fa-angle-up rotate-icon"></i></span>
    </h5>
</div>
<div class="block-content list-filter locations-list collapse" id="nationalities">
    <ul class="browse-list list-unstyled long-list">
        @if (isset($data['nationalities']) && $data['nationalities']->count() > 0)
        <li>
            <a href="<?= App\Helpers\Helper::replace_param_in_url('nationality','') ?>" title="All nationalities" @if(empty(request()->get('nationality'))) style="{{$bold}}" @endif>
                All Nationalities
            </a>
        </li>
        @foreach ($data['nationalities'] as $nationality)
        <?php 
         $link= App\Helpers\Helper::replace_param_in_url('nationality',$nationality->id);
        ?>
        <li>
            <a href="<?= $link ?>" title="{{ $nationality->name }}" @if(request()->get('nationality')==$nationality->id)
                style="{{$bold}}" @endif>
                {{ $nationality->name }} ({{$nationality->count}})
            </a>
        </li>

        @endforeach

        @endif
    </ul>
</div>
<div style="clear:both"></div>