<div class="col-lg-4 col-md-12">
    <div class="card">
        <div class="card-body" style="height: 550px;overflow-y: auto">
            <div id="TopNationalitiesEmployees" class="mt-2" style="height:283px; width:100%;"></div>
            <ul class="list-style-none mb-0">
                <?php
                $colors = [];
                $chart3 = [];
                ?>
                @if (!empty($top_nationality_employees))
                @foreach ($top_nationality_employees as $key => $user)
                @if(!empty($user->nationalityData))
                <?php
                $color = \App\Helpers\Helper::rand_color($key);
                $colors[] = $color;
                ?>
                <li class="mt-3">
                    <i class="fas fa-circle font-10 me-2" style="color: <?=$color?>"></i>
                    <span class="text-muted">{{ $user->nationalityData->name }}</span>
                    <span class="text-dark float-end font-weight-medium">{{ $user->users }}</span>
                </li>
                <?php $chart3[] = [$user->nationalityData->name,$user->users]; ?>
                @endif
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
            <a href="{{ admin_url('top-nationality-job-seekers') }}" class="float-right btn btn-primary btn-sm text-white d-inline-block"><strong>View All</strong></a>
        </div>
    </div>
</div>
<script>
    var chart3 = <?=json_encode($chart3)?>;
    var color3 = <?=json_encode($colors)?>;
</script>
