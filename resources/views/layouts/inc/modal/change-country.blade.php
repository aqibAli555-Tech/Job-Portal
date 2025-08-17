<!-- Modal Change Country -->
<div class="modal fade modalHasList" id="selectCountry" tabindex="-1" role="dialog" aria-labelledby="selectCountryLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <div class="modal-title uppercase font-weight-bold" id="selectCountryLabel">
                    <i class="icon-location-2"></i> {{ t('Select A Country - Where Do You Live Right Now?') }}
                </div>

                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">{{ t('Close') }}</span>
                </button>
            </div>
            <?php $actual_link=\Request::fullUrl(); ?>
            <div class="modal-body">
                <input type="text" id="countrySearchInput" class="form-control mb-3" placeholder="Search country...">
                <div class="row" id="countryListContainer">
                    @if (isset($countryCols))
                        @foreach ($countryCols as $key => $col)
                            @foreach ($col as $k => $country)
                                <div class="cat-list col-6 col-xl-3 col-lg-3 col-md-4 mb-1">
                                    <a rel="nofollow" href="<?php echo url('?d=' . $country['code']) ?>" class="tooltip-test p-2 bg-light d-block country-link" title="{{ $country->get('name') }}">
                                        <img alt="{{ $country->get('name') }}" src="{{ url()->asset('images/blank.gif') . getPictureVersion() }}" class="flag flag-{{ ($country->get('icode')=='uk') ? 'gb' : $country->get('icode') }}">
                                        {{ \Illuminate\Support\Str::limit($country->get('name'), 28) }}
                                    </a>
                                </div>
                            @endforeach                    
                        @endforeach
                    @endif

                </div>
            </div>

        </div>
    </div>
</div>
<!-- /.modal -->
