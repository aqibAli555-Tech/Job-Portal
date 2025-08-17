<div class="item-list job-item{{ $premiumClass }}">
    <div class="row">
        <div class="col-lg-12 col-md-12  no-padding photobox">
            <div class="row">
                @if ($post->postDetail->hide_company_logo != 1)
                    <div class="col-2 no-padding"
                        onclick="window.location.href = '{{ \App\Helpers\UrlGen::post($post) }}'; return false;">
                        <a href="{{ \App\Helpers\UrlGen::post($post) }}">
                            <img class="img-thumbnail no-margin" src="{{ $logo_show }}"
                                alt="{{ $post->company_name }}">
                        </a>
                    </div>
                @endif
                <div class="col-6" onclick="window.location.href = '{{ \App\Helpers\UrlGen::post($post) }}'; return false;">
                    <h3 class="job-title">
                        <a
                            href="{{ \App\Helpers\UrlGen::post($post) }}">{{ \Illuminate\Support\Str::limit($post->title, 70) }}</a>{!!
                        $premiumBadge !!}
                    </h3>
                </div>
                <div class="col-4">
                    @if (!auth()->check())
                    <a href="{{ url('login') }}" class="btn btn-primary float-right apply-login-link">
                        <span class="btn-text">Apply Now</span>
                    </a>
                    @endif
                    @if (auth()->check())
                    @if(auth()->user()->user_type_id == 2)
                    <?php $check_applied = \App\Models\Applicant::check_applied_user($post->id); ?>
                    @if (empty($check_applied))
                    <button class="btn btn-primary  applybutton float-right" type="button" data-id="<?= $post->id ?>"
                        data-url="{{ \App\Helpers\UrlGen::post($post) }}"
                        data-body="Hi i am applying for this job title {{ $post->title }}">
                        <span class="btn-text">{{ t('Apply Now') }}</span>                    
                    </button>
                    @else
                    <button class="btn btn-primary-dark  appliedbutton float-right"
                        type="button">{{ t('Applied') }}</button>
                    @endif
                    @endif
                    @endif
                </div>
            </div>
        </div>
        <div class="col-12 no-padding">
            <div class="p-2"></div>
        </div>
        <div class="col-md-12 add-desc-box no-padding">
            <div class="add-details jobs-item">
                <div class="post-latest-search">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>{{ t('Company') }}: </strong>
                            <?php if (!empty($post->company_id)) {
                                    $companyData = \App\Models\Company::where('id', $post->company_id)->first();
                                } ?>
                            @if ($post->postDetail->hide_company_logo != 1)
                            <a href="{{ url('/companyprofile') }}/{{ $post->user_id }}"> <span
                                    class="item-location">{{ !empty($companyData->name) ? $companyData->name : '' }}</span></a>
                            @else
                            <span class="item-location">This company decided to hide it’s logo & name for this
                                job post</span>
                            @endif

                            <div class="spacer"></div>
                        </div>

                        <div class="col-md-6" onclick="window.location.href = '{{ \App\Helpers\UrlGen::post($post) }}'; return false;">
                            <strong>{{ t('Salary') }}: </strong>
                            <span class="salary">

                                @if ($post->postMeta->hide_salary == 0)
                                @if ($post->salary_min > 0 || $post->salary_max > 0)
                                @if ($post->salary_min > 0)
                                {!! \App\Helpers\Number::money($post->salary_min, $post->country_code) !!}
                                @endif
                                @if ($post->salary_max > 0)
                                @if ($post->salary_min > 0)
                                &nbsp;-&nbsp;
                                @endif
                                {!! \App\Helpers\Number::money($post->salary_max, $post->country_code) !!}
                                @endif
                                @else
                                {!! \App\Helpers\Number::money('--') !!}
                                @endif
                                @if (isset($post->salaryType) && !empty($post->salaryType))
                                {{ t('per') }} {{ $post->salaryType->name }}
                                @endif
                                @else
                                {{ t('Salary Hidden by Employer') }}
                                @endif
                            </span>
                            <div class="spacer"></div>
                        </div>

                    </div>
                    <div class="row" onclick="window.location.href = '{{ \App\Helpers\UrlGen::post($post) }}'; return false;">
                        <div class="col-md-6">

                            <strong>{{ t('Start Date') }}: </strong>
                            <span class="item-location">@if($post->as_soon == 1) {{t('As Soon As Possible')}} @else {{ date('M d, Y', strtotime($post->start_date)) }} @endif</span>
                            <div class="spacer"></div>
                        </div>
                        <div class="col-md-6">
                            <strong>{{ t('Negotiable') }}:</strong>
                            <span class="item-location">{{ !empty($post->negotiable) ? 'Yes' : 'No' }}</span>
                            <div class="spacer"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>{{ t('Location') }}: </strong>
                            <span class="item-location">
                                <a
                                    href="{{ url('latest-jobs?post=&country_code=&q=&l='.$post->postDetail->city->id.'&min_salary=&max_salary=&type[]=') }}">
                                    <span class="item-location">
                                        {{ $post->postDetail->city->name }},
                                    </span>
                                    <a
                                        href="{{ url('latest-jobs?post=&country_code='.$post->country->id.'&q=&l=&min_salary=&max_salary=&type[]=')}}">
                                        <span class="item-location">
                                            {{ $post->country->name }} &nbsp;&nbsp;
                                        </span>
                                    </a>
                                    <img data-toggle="tooltip" class="country_image" data-placement="top" title="{{$post->country->name}}"

                                        src="{{ url()->asset('images/flags/16/' . strtolower($post->country->code) . '.png') }}">
                                </a>
                                {{ isset($post->distance) ? '- ' . round($post->distance, 2) . getDistanceUnit() : '' }}
                            </span>
                            <div class="spacer"></div>
                        </div>
                        <div class="col-md-6">
                            <strong>{{ t('Job Type') }}: </strong>
                            <a
                                href="{{  url('latest-jobs?post=&country_code=&q=&l=&min_salary=&max_salary=&type[]='.$post->postType->id) }}">
                                <span class="item-location">{{ $post->postType->name }}</span>
                            </a>
                            <div class="spacer"></div>
                        </div>
                        <div class="col-md-6">
                            <strong>{{ t('Skill set') }}: </strong>
                            <?php
                                if (!empty($post->employeeskill->skill)) {

                                ?>
                            <a
                                href="{{ url('latest-jobs?post=&country_code=&q='.$post->employeeskill->id.'&l=&min_salary=&max_salary=&type[]=')  }}">
                                {{ $post->employeeskill->skill }}
                            </a>

                            <?php } ?>
                            <div class="spacer"></div>
                        </div>
                        <div class="col-md-6" onclick="window.location.href = '{{ \App\Helpers\UrlGen::post($post) }}'; return false;">
                            <strong>{{ t('Transportation Available') }}: </strong>
                            <?php if (!empty($post->postDetail->transportation_available)) {
                                    echo 'Yes';
                                } else {
                                    echo 'No';
                                } ?>

                            <div class="spacer"></div>
                        </div>
                        <div class="col-md-6" onclick="window.location.href = '{{ \App\Helpers\UrlGen::post($post) }}'; return false;">
                            <strong>{{ t('Overtime Pay available') }}: </strong>
                            <?php if (!empty($post->postDetail->overtime_pay)) {
                                    echo 'Yes';
                                } else {
                                    echo 'No';
                                } ?>

                            <div class="spacer"></div>
                        </div>

                        <div class="col-md-6" onclick="window.location.href = '{{ \App\Helpers\UrlGen::post($post) }}'; return false;">
                            <strong>{{ t('Housing Available') }}: </strong>
                            <?php if (!empty($post->postDetail->housing_available)) {
                                    echo 'Yes';
                                } else {
                                    echo 'No';
                                } ?>

                            <div class="spacer"></div>
                        </div>
                        <div class="col-md-6" onclick="window.location.href = '{{ \App\Helpers\UrlGen::post($post) }}'; return false;">
                            <strong>{{ t('Work Experience') }}: </strong>
                            <?php if (!empty($post->postDetail->experiences)) {
                                    echo t($post->postDetail->experiences);
                                } ?>

                            <div class="spacer"></div>
                        </div>

                        <div class="col-md-6" onclick="window.location.href = '{{ \App\Helpers\UrlGen::post($post) }}'; return false;">
                            <strong>{{ t('Gender Preference') }}: </strong>
                            <?php if (!empty($post->postDetail->gender)) {
                                    echo t($post->postDetail->gender);
                                } ?>
                            <div class="spacer"></div>
                        </div>
                        <?php
                            $nationality = '';
                            if (!empty($post->postDetail->nationality)) {
                                $nationalities = explode(',', $post->postDetail->nationality);
                                foreach ($nationalities as $key => $value) {
                                    $nationalityTableData = App\Models\Nationality::where('id', $value)
                                        ->first();

                                    if (empty($nationality)) {
                                        $nationality .= $nationalityTableData->name;
                                    } else {
                                        $nationality .= ', ' . $nationalityTableData->name;
                                    }
                                }
                            }
                            ?>
                        <div class="col-md-6" onclick="window.location.href = '{{ \App\Helpers\UrlGen::post($post) }}'; return false;">
                            <strong>{{ t('nationality') }}: </strong>
                            <?php if (!empty($nationality)) {
                                    echo $nationality;
                                } ?>
                            <div class="spacer"></div>
                        </div>





                            <div class="col-md-6" onclick="window.location.href = '{{ \App\Helpers\UrlGen::post($post) }}'; return false;">
                            @php

                            if($post->postDetail->post_type == 2){
                            $skill_sets = $post->postDetail->skills_set;
                            $skill_sets = str_replace(',', ', ', $skill_sets);
                            $who_can_apply=" Only These Skills Sets: ".$skill_sets;
                            }else{
                            $who_can_apply="All Skills Sets";
                            }
                            @endphp
                                <strong>{{t('Who Can Apply?')}}: </strong>
                                {{$who_can_apply}}

                                <div class="spacer"></div>
                            </div>

                            <div class="col-md-6" onclick="window.location.href = '{{ \App\Helpers\UrlGen::post($post) }}'; return false;">
                                <strong>{{t('Type Of Hiring?')}}: </strong>

                                @if($post->postDetail->who_can_apply == 1)
                                {{"Local Hire Only ".$post->country->name;}}
                                <img data-toggle="tooltip" class="country_image" data-placement="top" title="{{$post->country->name}}"
                                    src="{{ url()->asset('images/flags/16/' . strtolower($post->country->code) . '.png') }}">

                                @elseif($post->postDetail->who_can_apply == 2)
                                {{"International Hire Only 🌎"}}
                                @else
                                {{" Both local hire in ".$post->country->name;}}
                                <img data-toggle="tooltip" class="country_image" data-placement="top" title="{{$post->country->name}}"
                                    src="{{ url()->asset('images/flags/16/' . strtolower($post->country->code) . '.png') }}">
                                    {{"and International hire  🌎"}}
                                @endif
                                <div class="spacer"></div>
                            </div>

                    </div>
                </div>
                <div class="jobs-desc" onclick="window.location.href = '{{ \App\Helpers\UrlGen::post($post) }}'; return false;">
                    <strong>{{ t('Job Description') }}: </strong>
                    {!! \Illuminate\Support\Str::limit(strip_tags(strCleaner($post->description)), 180) !!}
                </div>
                <span class="info-row">
                    @if (!config('settings.listing.hide_dates'))
                    <span class="date">
                        <i class="icon-clock"></i> {!! $post->created_at_formatted !!}
                    </span>
                    @endif
                    <span class="category d-none">
                        <i class="icon-folder-circled"></i>&nbsp;
                        @if (isset($post->category->parent) && !empty($post->category->parent))
                        <a href="{!! \App\Helpers\UrlGen::category($post->category->parent, null, $city ?? null) !!}">
                            {{ $post->category->parent->name }}
                        </a>&nbsp;&raquo;&nbsp;
                        @endif
                    </span>
                    <br>
                </span>
            </div>
            <ul class="list-unstyled list-inline" style="float: right">
                @if (isset($post->savedByLoggedUser) && $post->savedByLoggedUser->count() > 0)
                <li class="saved-job" id="{{ $post->id }}">
                    <a class="btn btn-primary btn-sm save-job" id="save-{{ $post->id }}" href="javascript:void(0)"
                        onclick="savePost1(this)"><span class="fa fa-heart"></span></a>
                </li>
                <li>
                    <a class="btn btn-primary btn-sm email-job" style="padding: 4.3px;" data-toggle="modal"
                        data-id="{{ $post->id }}" href="#sendByEmail" id="email-{{ $post->id }}">
                        <i class="fa fa-envelope"></i>
                        {{ t('Share Job') }}
                    </a>
                </li>
                @else
                @if (!empty(auth()->user()))
                @if (auth()->user()->user_type_id != 1)
                <li id="{{ $post->id }}">
                    <a class="btn btn-primary btn-sm save-job" id="save-{{ $post->id }}"
                        style="background-color: white;" href="javascript:void(0)" onclick="savePost1(this)"><span
                            style="color: #22d3fd !important;" class="fa fa-heart"></span></a>
                </li>
                @endif
                <li>
                    <a class="btn btn-primary btn-sm email-job" style="padding: 4.3px;" data-toggle="modal"
                        data-id="{{ $post->id }}" href="#sendByEmail" id="email-{{ $post->id }}">
                        <i class="fa fa-envelope"></i>
                        {{ t('Share Job') }}
                    </a>
                </li>
                @endif
                @endif

                @if(!(auth()->user()))
                <div class="f-r">
                <a class="btn btn-primary btn-sm email-job" style="padding: 4.3px;" data-toggle="modal"
                                    data-id="{{ $post->id }}" href="#sendByEmail" id="email-{{ $post->id }}">
                                    <i class="fa fa-envelope"></i>
                                    {{ t('Share Job') }}
                                </a>
                </div>
                @endif
            </ul>
        </div>
    </div>
</div>
<script>
    $(document).on('click', '.applybutton', function () {
        var $btn = $(this);
        var $btnText = $btn.find('.btn-text');
        $btn.prop('disabled', true);
        $btnText.html('<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Apply Now');
    });

    $(document).on('click', '.apply-login-link', function (e) {
        var $link = $(this);
        var $btnText = $link.find('.btn-text');
        $link.addClass('disabled').css('pointer-events', 'none').css('opacity', '0.6');
        $btnText.html('<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Apply Now');
    });
</script>