@extends('layouts.master')

@section('search')
    @parent
@endsection
@section('content')
    <div class="main-container" id="homepage">

        @if (Session::has('message'))
            @includeFirst([config('larapen.core.customizedViewPath') . 'common.spacer', 'common.spacer'])
                <?php $paddingTopExists = true; ?>
            <div class="container">
                <div class="row">
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {{ session('message') }}
                    </div>
                </div>
            </div>
        @endif


        @if (Session::has('flash_notification'))
            @includeFirst([config('larapen.core.customizedViewPath') . 'common.spacer', 'common.spacer'])
                <?php $paddingTopExists = true; ?>
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        @include('flash::message')
                    </div>
                </div>
            </div>
        @endif

        @includeFirst([config('larapen.core.customizedViewPath') . 'home.inc.latest', 'home.inc.latest'])
        @includeFirst([config('larapen.core.customizedViewPath') . 'home.inc.categories', 'home.inc.categories'])
        <br><br><br>
        @includeFirst([config('larapen.core.customizedViewPath') . 'home.inc.job-statistics', 'home.inc.job-statistics'])
        @includeFirst([config('larapen.core.customizedViewPath') . 'home.inc.companies', 'home.inc.companies'])

    </div>
@endsection

@section('after_scripts')
    <script>
        $(document).ready(function () {
            if (companies_count && companies_count > 5) {
                companies_count = 5
            }
            if ($('#slider').length) {
                $('#slider').owlCarousel({
                    loop: true,
                    autoplay: true,
                    autoplayHoverPause: false,
                    autoplaySpeed: 3000,
                    animateOut: 'fadeOut',
                    animateIn: 'fadeIn',
                    items: companies_count,
                    responsive: {
                        0: {
                            items: 1
                        },
                        600: {
                            items: 2
                        },
                        1000: {
                            items: companies_count
                        }
                    }
                });
            }
            if ($('#employee-slider-home').length) {
                $('#employee-slider-home').owlCarousel({
                    loop: true,
                    autoplay: true,
                    autoplayHoverPause: false,
                    autoplaySpeed: 15000,
                    animateOut: 'fadeOut',
                    animateIn: 'fadeIn',
                    items: 1,
                    responsive: {
                        0: {
                            items: 1
                        },
                        600: {
                            items: 1
                        },
                        1000: {
                            items: 1
                        }
                    }
                });
            }
            if ($('#jobs-slider-home').length) {
                $('#jobs-slider-home').owlCarousel({
                    loop: true,
                    autoplay: true,
                    autoplayTimeout: 15000,
                    autoplayHoverPause: false,
                    animateOut: 'fadeOut',
                    animateIn: 'fadeIn',
                    items: 1,
                    responsive: {
                        0: {
                            items: 1
                        },
                        600: {
                            items: 1
                        },
                        1000: {
                            items: 1
                        }
                    }
                });
            }
            page_count('home_page');
        });

        window.onload = function() {
            var observer = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        var $userImage = $(entry.target);
                        var imgSrc = $userImage.attr('data-lazysrc');

                        if (imgSrc) {
                            $userImage.attr('src', imgSrc);
                        }
                        observer.unobserve(entry.target);
                    }
                });
            });

            // Select all images for lazy loading
            $('.employees-hungry-for-jobs-image').each(function() {
                observer.observe(this);
            });
        };
    </script>
@endsection