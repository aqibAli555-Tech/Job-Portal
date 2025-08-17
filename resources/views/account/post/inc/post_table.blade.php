<div class="table-responsive">
    <table id="addManageTable" class="table add-manage-table my-jobs-table table demo" data-filter="#filter"
           data-filter-text-only="true">
        <thead>
        <tr>
            <th data-sort-ignore="true"> {{ t('Ads Details') }}</th>
            <th data-sort-ignore="true" class=" text-center">{{ t('Views') }}</th>
            <th data-type="numeric">{{ t('Salary Range') }}</th>
            <th data-type="numeric" class=" text-center">{{ t('Applicants') }}</th>
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
                @if (isset($post->latestPayment) and !empty($post->latestPayment))
                    @if (isset($post->latestPayment->package) and !empty($post->latestPayment->package))
                            <?php
                            if ($post->featured == 1) {
                                $color = $post->latestPayment->package->ribbon;
                                $packageInfo = '';
                            } else {
                                $color = '#ddd';
                                $packageInfo = ' (' . t('Expired') . ')';
                            }
                            ?>
                        <i class="fa fa-check-circle tooltipHere"
                           style="color: {{ $color }};" title=""
                           data-placement="bottom" data-toggle="tooltip"
                           data-original-title="{{ $packageInfo }}"></i>
                    @endif
                @endif

                <br>
                @if(auth()->user()->user_type_id == 1)
                        <?php if ($post->is_approved == 0){ ?>
                    <div class="badge badge-danger float-right">Not Approved</div>
                    <?php } else { ?>
                    <div class="badge badge-primary float-right">Approved</div>
                    <?php } ?>
                @endif
                    <?php $days_Rem = \App\Helpers\Helper::calculate_remaining_days_of_post($post); ?>
                @if($days_Rem)
                    <div class="badge badge-warning float-right"> {{\App\Helpers\Helper::calculate_remaining_days_of_post($post)}}</div>
                @endif
                <br>
                @if ($post->archived == 0)
                &nbsp; {{ t('Posted On') }}: {!! date('d-M-Y', strtotime($post->created_at)) !!}
                @else
                &nbsp; {{ t('Archived On') }}: {!! date('d-M-Y', strtotime($post->archived_at)) !!}
                @endif

                @if (file_exists(public_path($countryFlagPath)))
                    <img src="{{ url($countryFlagPath) }}" data-toggle="tooltip"
                         title="{{ $post->country->name }}">
                @endif

            </td>
            <td>
                <div class="visitor-container text-center mr-4">
                    <strong class="content-center">
                        <i class="icon-eye " title="{{ t('Visitors') }}"></i>
                    </strong>

                    {{ $post->postMeta->visits ?? 0 }}
                </div>
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
            <td class="text-center"><span class="badge badge-pill"> {{ $post->post_count }}</span></td>
            <td class="action-td">
                @if ($post->user_id == auth()->user()->id and $post->archived == 0)
                    <a class="btn btn-primary btn-sm eidit "
                       href="{{ \App\Helpers\UrlGen::editPost($post) }}">
                        <i class="fa fa-edit"></i> {{ t('Edit') }}
                    </a>
                @endif
                @if ($post->archived == 0)
                    <a class="btn btn-sm confirm-action removehoverarchive btn-archive"
                       onclick="open_reason_model('{{$post->id}}','archived')"
                       href="javascript:void(0)">
                        <i class="icon-eye-off"></i> {{ t('Archive') }}
                    </a>
                @endif
                @if ($post->archived == 1)
                    <a class="btn btn-info btn-sm confirm-action removehoverarchive"
                       onclick="repost('{{ $post->id }}')"
                       href="javascript:void(0)">
                        <i class="fa fa-recycle"></i> {{ t('Repost') }}
                    </a>
                @endif
                <a class="btn btn-danger btn-sm removehover"
                   href="javascript:void(0)" onclick="open_reason_model('{{$post->id}}','delete')">
                    <i class="fa fa-trash"></i> {{ t('Delete') }}
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php else : ?>
        <tr>
            <td colspan="4">
                <h6 class="text-muted" style="text-align: center">
                    @if($type=='archived_posts')
                        You haven’t archived any jobs yet.
                    @else
                        {{ t("You haven't posted any jobs yet") }}.
                    @endif

                </h6>
            </td>
        </tr>
         <?php endif; ?>
        </tbody>
    </table>
</div>

{{ isset($posts) ? $posts->links() : '' }}



