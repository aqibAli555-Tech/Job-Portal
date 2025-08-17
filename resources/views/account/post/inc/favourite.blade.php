
<div class="table-responsive">
    <table id="addManageTable" class="table add-manage-table my-jobs-table table demo" data-filter="#filter"
           data-filter-text-only="true">
        <thead>
        <tr>
            <th data-sort-ignore="true"> {{ t('Ads Details') }}</th>
            <th data-type="numeric">{{ t('Salary Range') }}</th>
            <th data-type="numeric" class=" text-center">{{ t('Company Name') }}</th>
            <th> {{ t('Option') }}</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if (isset($posts) && $posts->count() > 0) :

        foreach ($posts as $key => $post) :

            if (!$countries->has($post->country_code)) ;
            $postUrl = \App\Helpers\UrlGen::post($post);
            $countryFlagPath = 'public/images/flags/16/' . strtolower($post->country_code) . '.png';
            ?>
        <tr>
            <td class="items-details-td">
                <strong><a href="{{ $postUrl }}"
                           title="{{ $post->title }}">{{ \Illuminate\Support\Str::limit($post->title, 40) }}</a></strong>

                <br>

                <br>
                {!! date('d-M-Y', strtotime($post->created_at)) !!}
            </td>

            <td class="price-td">
                <div>
                    <strong>
                        @if ($post->hide_salary == 1)
                            {{ t('Salary Hidden by Employer') }}
                        @else
                            @if ($post->salary_min > 0)
                                {!! App\Helpers\Number::money($post->salary_min, $post->country_code) !!}
                                -{!! App\Helpers\Number::money($post->salary_max, $post->country_code) !!}
                            @else
                                {!! App\Helpers\Number::money($post->salary_min, $post->country_code) !!}
                                -{!! App\Helpers\Number::money($post->salary_max, $post->country_code) !!}
                            @endif
                        @endif
                    </strong>
                </div>
            </td>
            <td class="text-center"><a
                        href="{{url('companyprofile/'.$post->user_id)}}">{{ $post->company_name }}</a>
            </td>
            <td class="action-td">
                <a class="btn btn-primary btn-sm save-job"
                   id="save-{{ $post->id }}" style="background-color: white;"
                   href="javascript:void(0)"
                   onclick="favouritePost(this,<?= $post->id ?>)"><span
                            class="fa fa-heart btn-heart"></span></a> <a
                        class="btn btn-primary-dark btn-sm email-job"
                        data-toggle="modal"
                        data-id="{{ $post->id }}" href="#sendByEmail"
                        id="email-{{ $post->id }}"><span class="fa fa-share"></span>
                    {{ t('Share job') }} </a>

            </td>
        </tr>
        <?php endforeach; ?>
        <?php else : ?>
        <tr>
            <td colspan="4">
                <h6 class="text-muted" style="text-align: center">
                    {{ t('No jobs have been favorited, go to Apply To Jobs and favorite as many jobs as you like!') }}
                    .
                </h6>
            </td>
        </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

{{ isset($posts) ? $posts->links() : '' }}



