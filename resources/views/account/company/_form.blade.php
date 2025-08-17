<?php
// From Company's Form
$classLeftCol = 'col-md-3';
$classRightCol = 'col-md-9';

if (isset($originForm)) {
    // From User's Form
    if ($originForm == 'user') {
        $classLeftCol = 'col-md-3';
        $classRightCol = 'col-md-7';
    }

    // From Post's Form
    if ($originForm == 'post') {
        $classLeftCol = 'col-md-3';
        $classRightCol = 'col-md-8';
    }
}

?>
<style>
    .fileinput-remove {
        display: none;
    }
</style>
<div id="companyFields">
    <!-- name -->
    <div class="form-group row required">
        <label class="{{ $classLeftCol }} control-label" for="company.name">{{ t('Company Name') }} <sup>*</sup></label>
        <div class="{{ $classRightCol }}">
            <input name="company[name]" placeholder="{{ t('Company Name') }}" class="form-control inputs" type="text" value="{{ old('company.name', (isset($company->name) ? $company->name : '')) }}" required>
        </div>
    </div>

    <!-- email -->
  
    <div class="form-group row required">
        <label class="{{ $classLeftCol }} control-label" for="company.email">Company {{ t('Email') }} <sup>*</sup></label>
        <div class="input-group {{ $classRightCol }}">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="icon-mail"></i></span>
            </div>
            <input name="company[email]" type="email" class="form-control" placeholder="" value="{{ old('company.email', (isset($company->email) ? $company->email : '')) }}" required>
        </div>
    </div>

    <!-- phone -->

    <div class="form-group row required">
        <label class="{{ $classLeftCol }} control-label" for="company.phone">{{ t('Mobile') }} <sup>*</sup></label>
        <div class="input-group {{ $classRightCol }}">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="icon-phone-1"></i></span>
            </div>
            <?php $countryCodeString = '+'.config('country.phone'); ?>

            <input name="company[phone]" inputmode="numeric" pattern="[0-9+,\.]+" oninput="handleInput(this,'{{$countryCodeString}}')" type="text" class="form-control" placeholder="" value="{{ (isset($company->phone) ? $company->phone :  '+'.config('country.phone')) }}" required >
        </div>
    </div>

    <!-- logo -->

    <div class="form-group row <?php if (empty($company->logo)) {
                                    echo "required";
                                } ?>" required>
        <label class="{{ $classLeftCol }} control-label" for="company.logo"> {{ t('Logo') }} <sup>*</sup></label>
        <div class="{{ $classRightCol }}">
            <div {!! (config('lang.direction')=='rtl' ) ? 'dir="rtl"' : '' !!} class="file-loading mb10">
                <input id="logo" <?php if (empty($company->logo)) {
                                        echo "required";
                                    } ?> name="company[logo]" accept="image/png, image/jpeg, image/jpg" type="file" class="file">
            </div>
            <small id="" class="form-text text-muted">
                {{ t('File types', ['file_types' => showValidFileTypes('image')]) }}
                and size 100X100
            </small>
        </div>
    </div>

    <!-- description -->
   
    <div class="form-group row required">
        <label class="{{ $classLeftCol }} control-label" for="company.description">{{ t('Company Description') }}
            <sup>*</sup></label>
        <div class="{{ $classRightCol }}">
            <textarea class="form-control inputs" name="company[description]" rows="10" maxlength="1000" required>{{ old('company.description', (isset($company->description) ? $company->description : '')) }}</textarea>
            <small id="" class="form-text text-muted">
                {{ t('Describe the company') }} - ({{ t('N characters maximum', ['number' => 1000]) }})
            </small>
        </div>
    </div>
    <!-- country_code -->
   
    <div class="form-group row required">
        <label class="{{ $classLeftCol }} control-label" for="company.country_code">{{ t('Country') }}<sup>*</sup></label>
        <div class="{{ $classRightCol }}">
            <select id="countryCode1" onchange="get_cities()" required name="company[country_code]" class="form-control select1 sselecter">
                <option value="0" {{ (!old('company.country_code') or old('company.country_code')==0) ? 'selected="selected"' : '' }}> {{ t('select_a_country') }} </option>
                @foreach ($countries as $item)
                <option value="{{ $item['code'] }}" {{ (old('company.country_code',
                            (isset($company->country_code) ? $company->country_code : ((!empty(config('country.code'))) ? config('country.code') : 0)))==$item['code']) ? 'selected="selected"' : '' }}>
                    {{ $item['name'] }}
                </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- city_id -->
    
    <div class="form-group row required">
        <label class="{{ $classLeftCol }} control-label" for="company.city_id">{{ t('City') }}<sup>*</sup></label>
        <div class="{{ $classRightCol }}">
            <select id="cityId1" name="company[city_id]" class="form-control select1 sselecter">
                </option>
            </select>
            <small style="color: red;display:none" id="iderror">*Please select a country first</small>
        </div>
    </div>

</div>

@section('after_styles')
@parent
<style>
    #companyFields .select2-container {
        width: 100% !important;
    }

    .file-loading:before {
        content: " {{ t('Loading') }}...";
    }

    .krajee-default.file-preview-frame .kv-file-content {
        height: auto;
    }

    .krajee-default.file-preview-frame .file-thumbnail-footer {
        height: 30px;
    }
</style>
@endsection

@section('after_scripts')
@parent
<?php
if (!empty($company->city_id)) {
    $city_id = $company->city_id;
} else {
    $city_id = '';
}
?>
<script>
    $(document).ready(function() {
        get_cities();
    });

    function handleInput(input, countryCode) {
        const inputValue = input.value;
        const countryCodeLength = countryCode.length;

        if (inputValue.length < countryCodeLength) {
            input.value = countryCode;
        } else if (!inputValue.startsWith(countryCode)) {
            input.value = countryCode + inputValue;
        }
    }


    function get_cities() {
        var countryCode = $('#countryCode1').val();
        var url = siteUrl + '/ajax/get_city_by_country/' + countryCode;
        var appenddata1 = "";
        $.ajax({
            type: "GET",
            url: url,
            dataType: 'json',
            beforeSend: function() {
                // setting a timeout
                $("#cityId1").empty();
            },
            success: function(c) {
                $(c.items).each(function(key, value) {
                    var name = value.text.split(',')[0];
                    var slelectd_city = '{{$city_id }}';
                    if (value.id == slelectd_city) {
                        var selected = 'selected';
                    } else {
                        selected = '';
                    }
                    appenddata1 += "<option value = '" + value.id + " ' " + selected + ">" + name + "  </option>";
                });
                // cityId1
                $("#cityId1").append(appenddata1);
            },
        });
    };
</script>
<script>
     /* Initialize with defaults (logo) */
     $('#logo').fileinput(
            {
                theme: "fa", 
                language: '{{ config('app.locale') }}',
                @if (config('lang.direction') == 'rtl')
                rtl: true,
                @endif
                dropZoneEnabled: false,
                showPreview: true,
                previewFileType: 'image',
                allowedFileExtensions: {!! getUploadFileTypes('image', true) !!},
                showUpload: false,
                showRemove: false,
                minFileSize: {{ (int)config('settings.upload.min_image_size', 0) }}, {{-- in KB --}}
                maxFileSize: {{ (int)config('settings.upload.max_image_size', 1000) }}, {{-- in KB --}}
                @if (isset($company) and !empty($company->logo))
                /* Retrieve Existing Logo */
                initialPreview: [
                    '<img src="{{ \App\Helpers\Helper::get_company_logo_AWS($company) }}" class="file-preview-image">',
                ],
                @endif
                /* Remove Drag-Drop Icon (in footer) */
                fileActionSettings: {dragIcon: '', dragTitle: ''},
                layoutTemplates: {
                    /* Show Only Actions (in footer) */
                    footer: '<div class="file-thumbnail-footer pt-2">{actions}</div>',
                    /* Remove Delete Icon (in footer) */
                    actionDelete: ''
                }
            });
</script>

<script>
    /* Translation */
    var lang = {
        'select': {
            'country': "{{ t('select_a_country') }}",
            'admin': "{{ t('Select a location') }}",
            'city': "{{ t('Select a city') }}"
        }
    };

    /* Locations */
    var countryCode = '{{ old('
    company.country_code ', (isset($company) ? $company->country_code : 0)) }}';
    var adminType = 0;
    var selectedAdminCode = 0;
    var cityId = '{{ old('
    company.city_id ', (isset($company) ? $company->city_id : 0)) }}';
</script>




@endsection