
<div class="col-lg-8 col-md-12">
    <div class="card">
        <div class="card-body" style="height: 300px;overflow-y: auto">
            <h4  class="card-title">{{ trans('admin.Latest Ads') }}</h4>
            <div class="table-responsive mt-3 no-wrap">
                <table class="table v-middle mb-0 table-striped table-sm">
                    <thead>
                        <tr>
                            <th class="border-0">{{ trans('admin.ID') }}</th>
                            <th class="border-0">{{ mb_ucfirst(trans('admin.title')) }}</th>
                            <th class="border-0">{{ mb_ucfirst(trans('admin.country')) }}</th>
                            <th class="border-0">{{ trans('admin.Status') }}</th>
                            <th class="border-0">{{ trans('admin.Date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($latestPosts->count() > 0)
                            @foreach ($latestPosts as $post)
                                <tr>
                                    <td class="td-nowrap">{{ $post->id }}</td>
                                    <td>{!! getPostUrl($post) !!}</td>
                                    <td class="td-nowrap">
                                        <img data-toggle="tooltip" data-placement="top"
                                            title="{{ $post->country->name }}"
                                            src="{{ url()->asset('images/flags/16/' . strtolower($post->country_code) . '.png') }}"> {{ $post->country->name }}
                                    </td>
                                    <td class="td-nowrap">
                                        @if ($post->is_active == 1)
                                            <span class="text-success">{{ trans('admin.Active') }}</span>
                                        @else
                                            <span
                                                class="text-danger">{{ trans('admin.Inactive') }}</span>
                                        @endif
                                    </td>
                                    <td class="td-nowrap">
                                        <div class="sparkbar" data-color="#00a65a" data-height="20">
                                            {{ \App\Helpers\Date::format($post->created_at, 'datetime') }}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5">
                                    {{ trans('admin.No ads found') }}
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer  text-center">
            <a href="{{ admin_url('get_posts?type=life_time_posts') }} }}" class=" btn btn-primary btn-sm text-white d-inline-block"><strong>View All</strong></a>
        </div>
    </div>
</div>
