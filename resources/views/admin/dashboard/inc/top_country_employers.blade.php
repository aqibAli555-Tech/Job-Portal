<div class="col-lg-4 col-md-12">
    <div class="card">
        <div class="card-body" style="height: 550px;overflow-y: auto;">
            <div id="TopCountriesEmployers" class="mt-2" style="height:283px; width:100%;"></div>
            <ul class="list-style-none mb-0">
                <?php
                $colors = [];
                ?>
                @if ($top_country_employers->count() > 0)
                @foreach ($top_country_employers as $key => $user)
                <?php
                $color = \App\Helpers\Helper::rand_color($key);
                $colors[] = $color;
                ?>
                <li class="mt-3">
                    <i class="fas fa-circle font-10 me-2"  style="color: <?=$color?>"></i>
                    <span class="text-muted">
                            <img data-toggle="tooltip" data-placement="top"
                                 title="{{ $user->country->name }}"
                                 src="{{ url()->asset('images/flags/16/' . strtolower($user->country_code) . '.png') }}">
                        {{ $user->country->name }}
                        </span>
                    <span class="text-dark float-end font-weight-medium">{{ $user->users }}</span>
                </li>
                <?php

                $chart2[] = [$user->country->name,$user->users]; ?>
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
            <a href="{{ admin_url('get_top_country_employer') }}" class="btn btn-primary btn-sm text-white d-inline-block"><strong>View All</strong></a>
        </div>
    </div>
</div>
<script>
    var chart2 = <?=json_encode($chart2)?>;
    var color2 = <?=json_encode($colors)?>;
</script>