
<div class="col-lg-4 col-md-12">
    <div class="card">
        <div class="card-body" style="height: 550px;overflow-y: auto">
            <div id="Top-Countries-Employees" class="mt-2" style="height:283px; width:100%;"></div>
            <ul class="list-style-none mb-0">
                <?php
                $colors = [];
                ?>
                @if ($top_country_employees->count() > 0)
                    @foreach ($top_country_employees as $key  => $user)
                            <?php
                            $color = \App\Helpers\Helper::rand_color($key);
                            $colors[] = $color;
                            ?>
                        <li class="mt-3">
                            <i class="fas fa-circle font-10 me-2" style="color: <?=$color?>"></i>
                            <span class="text-muted">
                            <img data-toggle="tooltip" data-placement="top"
                                 title="{{ $user->country->name }}"
                                 src="{{ url()->asset('images/flags/16/' . strtolower($user->country_code) . '.png') }}">
                            {{ $user->country->name }}
                                    <?php $chart[] = [$user->country->name, $user->users] ?>
                        </span>
                            <span class="text-dark float-end font-weight-medium">{{ $user->users }}</span>
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
            <a href="{{ admin_url('get_top_country_employee') }}"
               class=" btn btn-primary btn-sm text-white d-inline-block"><strong>View All</strong></a>
        </div>
    </div>
</div>
<style>
    .c3-legend-item-Revenue {
        margin-top: 30px; /* Adjust as needed */
    }

</style>
@section('after_scripts')
    <script>
        $(function () {
            c3.generate({
                bindto: "#Top-Countries-Employees",
                data: {
                    columns: <?= json_encode($chart) ?>,
                    type: "donut",
                    tooltip: {show: !0}
                },
                donut: {label: {show: !1}, title: "Top Countries Employees", width: 18},
                legend: {hide: !0},
                color: {pattern: <?= json_encode($colors) ?>}
            }), d3.select("#Top-Countries-Employees .c3-chart-arcs-title").style("font-family", "Rubik");

            if (chart2.length) {
                c3.generate({
                    bindto: "#TopCountriesEmployers",
                    data: {
                        columns: ['data1', 767, 989, 9898, 9898, 989, 989, 9898, 8787],
                        type: "donut",
                        tooltip: {show: !0}
                    },
                    donut: {label: {show: !1}, title: "Top Countries Employers", width: 18},
                    legend: {hide: !0},
                    color: {pattern: color2}
                }), d3.select("#TopCountriesEmployers .c3-chart-arcs-title").style("font-family", "Rubik");
            }

            if (chart3.length) {
                c3.generate({
                    bindto: "#TopNationalitiesEmployees",
                    data: {
                        columns: chart3,
                        type: "donut",
                        tooltip: {show: !0}
                    },
                    donut: {label: {show: !1}, title: "Top Nationalities Employees", width: 18},
                    legend: {hide: !0},
                    color: {pattern: color3}
                }), d3.select("#TopNationalitiesEmployees .c3-chart-arcs-title").style("font-family", "Rubik");
            }
        });
    </script>
@endsection
