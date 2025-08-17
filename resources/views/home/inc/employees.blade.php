<br>
<h2 class="company-name-heading">
    Employees Hungry for Jobs
</h2>
<style>
    .job-data{
        font-family: 'Roboto', sans-serif;
        font-size: 14px;
        line-height: 20px;
        font-weight:700;
    }
    #employee-slider-home .owl-item img.employees-hungry-for-jobs-image {
        display: inline-block !important;
        width: 120px !important;
    }
    #employee-slider-home .owl-item .country_image {
        display: inline-block !important;
        width: auto !important;
    }

    #employee-slider-home .slide {
        text-align: left !important;
    }
    .employee-slider-item{
        padding: 20px;
        margin-bottom: 20px;

    }
    @media (max-width: 767px) {
        .employees-hungry-for-jobs-name {
            font-size: 13px;
        }
        #employee-slider-home .owl-item img.employees-hungry-for-jobs-image  {
            width: 80px !important;
            height: 80px;
            display: block;
            margin: 0 auto;
        }
    }
</style>
<div id="employee-slider-home" class="owl-carousel owl-theme">
    @foreach($employees as $key => $employee)
            <?php if ($key == 0 || (($key) % 4) == 0){ ?>

        <div class="slide">
            <div class="row <?= $key?>">
                <?php } ?>
                <div class="col-md-6">
                    <div class="employee-slider-item">
                    <div class="row">
                        <div class="col-4">
                            <img class="employees-hungry-for-jobs-image lazy"
                                 src="{{ \App\Helpers\Helper::getImageOrThumbnailLink($employee) }}"
                                 data-lazysrc="{{ \App\Helpers\Helper::getImageOrThumbnailLink($employee, true) }}"
                                 alt="Employee Picture" />


                        </div>
                        <div class="col-8">
                            <h4 class="employees-hungry-for-jobs-name">{{ $employee['name'] }}</h4>
                                <div class="job-data">
                                    City: {{ $employee->cityData->name }}
                                    <br>
                                    Country: {{ $employee->country->name }} <img class="country_image"
                                            src="{{ url()->asset('images/flags/16/' . strtolower($employee->country_code) . '.png') }}"/>
                                    <br>
                                    Nationality: {{ $employee->nationalityData->name }}
                                    <br>
                                    Skills Sets: <?php
                                                     $employee->skill_set = str_replace(',', ', ', $employee->skill_set);
                                                     echo $employee->skill_set;
                                                     ?>
                                </div>
                                                 <br>
                            @if(auth()->check() && auth()->user()->user_type_id==1)
                                <a class="employees-hungry-for-jobs-view-more" href="{{url('profile/'.$employee->id)}}">View
                                    More</a>
                            @elseif(!auth()->check())
                                <a href="{{ url('login') }}" class="employees-hungry-for-jobs-view-more">Login To View Details</a>
                            @endif
                        </div>
                    </div>
                    </div>
                </div>
                    <?php if ($key > 2 && (($key + 1) % 4) == 0){ ?>
            </div>
        </div>
        <?php } ?>

    @endforeach
</div>
<br>
<center><a class="employees-hungry-for-jobs-view-more" href="{{url('search-resumes?cat=&country=&city=&keyword=&limit=&offset=0&send=&nationality=&sort=')}}">View
    All Employees</a></center>
<br>





