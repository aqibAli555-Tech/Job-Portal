<div class="col-lg-4 col-md-12">
    <div class="card">
        <div class="card-body" style="height: 300px;overflow-y: auto">
            <h4 class="card-title">{{ trans('admin.Top Jobs by Skills Sets') }}</h4>
            <ul class="list-style-none mb-0">
                @if (!empty($top_skill_posts))
                @foreach ($top_skill_posts as $posts)
                <li class="mt-2">
                    <i class="fas fa-circle text-danger font-10 me-2"></i>
                    <span class="text-muted">{{ $posts->skill }}</span>
                    <span class="text-dark float-end font-weight-medium">{{ $posts->totalskills }}</span>
                </li>
                @endforeach
                @else
                <li>
                    <i class="fas fa-circle text-primary font-10 me-2"></i>
                    <span class="text-muted">
                            No data found.
                        </span>
                </li>
                @endif
            </ul>
        </div>
        <div class="card-footer  text-center">
            <a href="{{ admin_url('get_posts?type=life_time_posts') }} }}" class=" btn btn-primary btn-sm text-white d-inline-block"><strong>View All</strong></a>
        </div>
    </div>
</div>
