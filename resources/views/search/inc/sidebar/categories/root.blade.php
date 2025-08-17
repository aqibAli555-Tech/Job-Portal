<!-- Category -->
<?php

$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

?>
<div id="catsList">
    <div class="block-title has-arrow sidebar-header">
        <h5 class="list-title collapsed" data-toggle="collapse" href="#categories" role="button" aria-expanded="false"
            aria-controls="categories">
            <span class="font-weight-bold">
                {{ t('All Skills') }}
            </span> {!! $clearFilterBtn ?? '' !!}
            <span class="accicon"><i class="fas fa-angle-up rotate-icon"></i></span>
        </h5>
    </div>
    <div class="block-content list-filter categories-list collapse" id="categories">
        <ul class="list-unstyled">
            @if (isset($emp_skills) && $emp_skills->count() > 0)
            @foreach ($emp_skills as $skills)
            <?php
                    if (str_contains($actual_link, '&q=')) {
                        $link=str_replace('&q='. request()->get('q'), '&q=' .$skills->id, $actual_link);
                    }else{
                         $link=$actual_link;
                    }
            ?>
            <li>
                <a href="{{$link}}" title="{{ $skills->skill }}">
                    <span class="title">{{ $skills->skill }} - ({{$skills->post_count}})</span>
                </a>
            </li>
            @endforeach
            @endif
        </ul>
    </div>
</div>