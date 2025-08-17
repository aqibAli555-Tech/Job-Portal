<div class="row">
    <div class="col-md-3">
        <select class="form-control skill_set select1" name="skill_set">

        </select>
    </div>
    <div class="col-md-3">
        <select class="form-control post select1" name="post_id">
            <option value=""> {{t('search_by_job_post')}}</option>
            @if(!empty($posts))
                @foreach($posts as $item)
                    <option value="{{$item->id}}">{{$item->title}}
                    </option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="col-md-3" id="not_accurate">
        <select class="form-control select1" name="show_not_accurate_employee">
            <option value="">
                @if(!empty($not_accurate_not_read_employee))
                    ({{$not_accurate_not_read_employee}})
                @endif {{t('Show “Not Accurate” Applicants?')}} </option>
            <option value="Yes" <?php if (\request()->get('show_not_accurate_employee') == 'Yes') {
                echo "selected";
            } ?>>{{t('Yes, Allow Me To See “Not Accurate” Applicants')}}
            </option>
            <option value="No" <?php if (\request()->get('show_not_accurate_employee') == 'No') {
                echo "selected";
            } ?>>{{t('No, Don’t Allow Me To See “Not Accurate” Applicants')}}
            </option>
        </select>
    </div>
    @if($check_user_package->isEmpty())
        <?php $margin='10px'; ?>
        <div class="col-md-3" id="show_unlock_from_cv">
            <select class="form-control select-show-search-contact select1" name="show_unlock_from_cv">
                <option value="">{{t('Show “Unlock Through Search Cv” Applicants?')}} </option>
                <option value="Yes">{{t('Yes, Allow Me To See “Unlock Through Search Cv” Applicants')}}
                </option>
                <option value="No">{{t('No, Don’t Allow Me To See “Unlock Through Search Cv” Applicants')}}
                </option>
            </select>
        </div>
    @endif
    <div class="col-md-3" style="margin-top:{{!empty($margin)?$margin:''}}">
        <input type="search" id="search" value="" class="form-control" name="search"
               placeholder="{{t('search_applicants')}}">
    </div>
    <br>
    <div class="col-md-3" style="margin-top: 10px;">
        <a href="javascript:void(0)" class="btn btn-primary reset_filters">Reset Filters</a>
    </div>
</div>
