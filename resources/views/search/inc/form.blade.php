<?php

// Keywords

$keywords = rawurldecode(request()->get('q') ?? '');

// Category
$qCategory = (isset($cat) and !empty($cat)) ? $cat->id : request()->get('c');

// Location
if (isset($city) and !empty($city)) {
    $qLocationId = (isset($city->id)) ? $city->id : 0;
    $qLocation = $city->name;
    $qAdmin = request()->get('r');
} else {
    $qLocationId = request()->get('l');
    $qLocation = (request()->filled('r')) ? t('area') . rawurldecode(request()->get('r')) : request()->get('location');
    $qAdmin = request()->get('r');
}
?>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <h1 style="margin-bottom: 0;color:#000;line-height: 0;margin-top: 26px;font-weight: 900;">
                {{t('Job Listings')}}</h1>
        </div>
        <div class="col-md-9">
            <div class="search-row-wrapper rounded" hidden>
                <form id="seach" name="search" action="{{ \App\Helpers\UrlGen::search() }}" method="GET">
                    <div class="row m-0">
                        <div class="col-md-5 col-sm-12 mb-1 mb-xl-0 mb-lg-0 mb-md-0 search-col relative">

                            <select name="q" id="select-a-skill" class="form-control keyword has-icon"
                                placeholder="{{ t('What') }}" value="" style="border: 2px solid #22d3fd !important;">

                                <option style="background: #fff !important;" value="">All Skills</option>
                                @foreach($emp_skills as $skills)
                                <?php $data = request()->get('q');
                                        if (!empty(request('q'))) {
                                            $slected = request('q');
                                        } else {
                                            $slected = '';
                                        }
                                        ?>
                                <option style="background: #fff !important;" value="{{$skills->id}}" <?php if ($slected == $skills->id) {
                                        echo "selected";
                                    }?>> {{$skills->skill}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <?php
                        
                        ?>

                        <div class="col-md-5 col-sm-12 search-col relative locationicon">
                            <select name="l" class="form-control" style="border: 2px solid #22d3fd !important;"
                                id="select-a-location">
                                <option style="background: #fff !important;" value="">All Cities</option>
                              
                                @foreach($city_data as $city)
                                <?php
                                        if (!empty(request('l'))) {
                                            $slected = request('l');
                                        } else {
                                            $slected = '';
                                        }
                                        ?>
                                <option style="background: #fff !important;" value="{{$city->id}}" <?php if ($slected == $city->id) {
                                        echo "selected";
                                    } ?>>{{$city->name}}
                                </option>

                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 col-sm-12 search-col">
                            <button class="btn btn-primary btn-search btn-block">
                                <i class="icon-search"></i> {{ t('Find') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

@section('after_scripts')
@parent
<script>
$(document).ready(function() {
    $('#locSearch').on('change', function() {
        if ($(this).val() == '') {
            $('#lSearch').val('');
            $('#rSearch').val('');
        }
    });
});
</script>
@endsection