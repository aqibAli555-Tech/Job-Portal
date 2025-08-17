<!-- Category -->

<div id="catsList">
    <div class="block-title has-arrow sidebar-header">
        <h5 class="list-title collapsed categories-list-web" data-toggle="collapse" href="#categories" role="button"
            aria-expanded="false" aria-controls="categories">
            <span class="font-weight-bold">
                {{ t('All Skills Sets') }}
            </span> {!! $clearFilterBtn ?? '' !!}
            <span class="accicon"><i class="fas fa-angle-up rotate-icon"></i></span>
        </h5>
    </div>
    <div class="block-content list-filter categories-list categories-list-web collapse" id="categories">
        <ul class="list-unstyled">

            @if (isset($data['emp_skills']) && $data['emp_skills']->count() > 0)
            <li>
                <a href="<?=App\Helpers\Helper::replace_url_param('cat','');?>" title="All Skills" @if(empty(request()->get('cat')) ||  request()->get('cat') =='all') style="{{$bold}}" @endif>
                    <span class="title"> All Skills </span>
                </a>
            </li>
            @foreach ($data['emp_skills'] as $skills)
            <?php        
             $link= App\Helpers\Helper::replace_url_param('cat',$skills->skill);
            ?>
            <li>
                <a href="<?=$link?>" title="{{ $skills->skill }}">
                    <span class="title" @if(request()->get('cat')==$skills->skill) style="{{$bold}}"
                        @endif>{{ $skills->skill }}
                        ({{$skills->user_count}}) </span>
                </a>
            </li>
            @endforeach
            @endif
        </ul>
    </div>

</div>