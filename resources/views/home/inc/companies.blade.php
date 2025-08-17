<script>
    var companies_count = 0;
</script>
<br>
@if(!empty($featuredCompanies))
<div class="companies-listing">
    <div class="companies-listing-inner">
        <div class="container">
            <h2 class="company-name-heading" style="text-align:center">
                {!! $featuredCompanies->title !!}
            </h2>
            <br>
            <div id="slider" class="owl-carousel owl-theme">
                <?php $i = 1; ?>
                @foreach($featuredCompanies->companies as $iCompany)
                    <?php
                        $companyUrl = \App\Helpers\UrlGen::company(null, $iCompany->id);
                        $user_logo_url = \App\Helpers\Helper::getImageOrThumbnailLink($iCompany,true);
                    ?>

                    @if ($i == 1 || fmod($i, 2) == 1)
                        <div class="group">
                    @endif

                    <div class="slide">
                        <a href="{{ $companyUrl }}">
                            <img class="img-fluid image{{ $i }}" src="{{ $user_logo_url }}">
                        </a>
                    </div>

                    @if ($i == 1 || fmod($i, 2) == 1)
                        <br>
                    @endif

                    @if (fmod($i, 2) == 0)
                        </div>
                    @endif
                    <?php $i++; ?>

                    @foreach($iCompany->employer_logos as $logo)
                        @if ($i == 1 || fmod($i, 2) == 1)
                            <div class="group">
                        @endif

                        <?php
                            $logo_show = !empty($logo->logo) ? url('public/' . $logo->logo) : 'app/default/picture.jpg';
                        ?>

                        <div class="slide">
                            <a href="{{ $companyUrl }}">
                                <img class="img-fluid image{{ $i }}" src="{{ $logo_show }}">
                            </a>
                        </div>

                        @if ($i == 1 || fmod($i, 2) == 1)
                            <br>
                        @endif

                        @if (fmod($i, 2) == 0)
                            </div>
                        @endif

                        <?php $i++; ?>
                    @endforeach
                @endforeach
            </div>
        </div>
        <br>
        <center>
            <a class="employees-hungry-for-jobs-view-more" href="{{ url('companies')  }}">
                {{ t('VIEW ALL COMPANIES') }}
            </a>
        </center>
        <br>
        <br>
    </div>
</div>
@endif
<script>
    companies_count = <?= count(!empty($featuredCompanies->companies) ? $featuredCompanies->companies : []) ?>;
</script>



