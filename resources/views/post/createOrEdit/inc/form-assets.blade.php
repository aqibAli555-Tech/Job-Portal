@push('after_styles_stack')
@include('layouts.inc.tools.wysiwyg.css')

<link href="{{ url()->asset('plugins/bootstrap-fileinput/css/fileinput.min.css') }}" rel="stylesheet">
@if (config('lang.direction') == 'rtl')
<link href="{{ url()->asset('plugins/bootstrap-fileinput/css/fileinput-rtl.min.css') }}" rel="stylesheet">
@endif

{{-- Multi Steps Form --}}
@if (config('settings.single.publication_form_type') == '1')
<style>
    .krajee-default.file-preview-frame:hover:not(.file-preview-error) {
        box-shadow: 0 0 5px 0 #666666;
    }
</style>
@endif

{{-- Single Step Form --}}
@if (config('settings.single.publication_form_type') == '2')
<style>
    .krajee-default.file-preview-frame:hover:not(.file-preview-error) {
        box-shadow: 0 0 5px 0 #666666;
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
@endif

<link href="{{ url()->asset('plugins/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
@endpush

@push('after_scripts_stack')
@include('layouts.inc.tools.wysiwyg.js')

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/jquery.validate.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.payment/1.2.3/jquery.payment.min.js"></script>
@if (file_exists(public_path() . '/assets/plugins/forms/validation/localization/messages_'.config('app.locale').'.min.js'))
<script src="{{ url()->asset('plugins/forms/validation/localization/messages_'.config('app.locale').'.min.js') }}" type="text/javascript"></script>
@endif

<script src="{{ url()->asset('plugins/bootstrap-fileinput/js/plugins/sortable.min.js') }}" type="text/javascript"></script>
<script src="{{ url()->asset('plugins/bootstrap-fileinput/js/fileinput.min.js') }}" type="text/javascript"></script>
<script src="{{ url()->asset('plugins/bootstrap-fileinput/themes/fa/theme.js') }}" type="text/javascript"></script>
<script src="{{ url()->asset('plugins/momentjs/moment.min.js') }}" type="text/javascript"></script>

<script src="{{ url()->asset('plugins/bootstrap-daterangepicker/daterangepicker.js') }}" type="text/javascript"></script>

<?php
$postCompanyId = isset($postCompany, $postCompany->id) ? $postCompany->id : 0;
$countryCode = (isset($post, $post->country_code) and !empty($post->country_code)) ? $post->country_code : config('country.code', 0);
$selectedAdminCode = (isset($admin) and !empty($admin)) ? $admin->code : 0;
$cityId = isset($post, $post->city_id) ? (int)$post->city_id : 0;
?>

<script>
    /* Translation */
    var lang = {
        'select': {
            'country': "{{ t('select_a_country') }}",
            'admin': "{{ t('Select a location') }}",
            'city': "{{ t('Select a city') }}"
        },
        'price': "{{ t('Price') }}",
        'salary': "{{ t('Salary') }}",
        'nextStepBtnLabel': {
            'next': "{{ t('Next') }}",
            'submit': "{{ t('Update') }}"
        }
    };

    var stepParam = 0;

    /* Company */
    var postCompanyId = "{{old('company_id', $postCompanyId)}}";

    getCompany(postCompanyId);

    /* Locations */
    var countryCode = '{{ old('
    country_code
    ', $countryCode) }}';
    var adminType = '{{ config('
    country.admin_type
    ', 0) }}';
    var selectedAdminCode = '{{ old('
    admin_code
    ', $selectedAdminCode) }}';
    var cityId = '{{ old('
    city_id
    ', $cityId) }}';

    /* Packages */
    var packageIsEnabled = false;
    @if(isset($packages, $paymentMethods) and$packages->count() > 0 and $paymentMethods->count() > 0)
    packageIsEnabled = true;
    @endif
    function getCompany(companyId) {

if (companyId === 0 || companyId === '0' || companyId === '') {
    $('#logoField').hide();
    $('#logoFieldValue').html('');
    $('#companyFields').show();
} else {
    $('#companyFields').hide();
    var selectedCompanyLogo = $('#companyId').find('option:selected').data('logo');
    $('#logoFieldValue').html('<img src="' + selectedCompanyLogo + '">');
    var companyFormLinkElement = $('#companyFormLink');
    var companyFormLinkHrefValue = companyFormLinkElement.attr('href');
    companyFormLinkHrefValue = companyFormLinkHrefValue.replace(/companies\/([0-9]+)\/edit/, 'companies/' + companyId + '/edit');
    companyFormLinkElement.attr('href', companyFormLinkHrefValue);
    
    $('#logoField').show();
}
}
</script>
<script>
  

    function getCompany2(companyId) {

        if (companyId === 0 || companyId === '0' || companyId === '') {
            $('#logoField').hide();
            $('#logoFieldValue').html('');
            $('#companyFields').show();
            $(".inputs").prop('required', true);
        } else {
            $(".inputs").prop('required', false);
            $('#companyFields').hide();
            var selectedCompanyLogo = $('#companyId').find('option:selected').data('logo');
            var newlogo = '';
            var xhr = new XMLHttpRequest();
            xhr.open('HEAD', selectedCompanyLogo, false);
            xhr.send();
            if (xhr.status == "404") {
                newlogo = "{{ url(' / public/storage/') }}/{{ 'app/default/picture.jpg' }}";
            } else {
                newlogo = selectedCompanyLogo;
            }
            $('#logoFieldValue').html('<img src="' + newlogo + '">');
            var companyFormLinkElement = $('#companyFormLink');
            var companyFormLinkHrefValue = companyFormLinkElement.attr('href');
            companyFormLinkHrefValue = companyFormLinkHrefValue.replace(/companies\/([0-9]+)\/edit/, 'companies/' + companyId + '/edit');
            companyFormLinkElement.attr('href', companyFormLinkHrefValue);

            $('#logoField').show();
        }
    }

    $(function () {
        /*
         * start_date field
         * https://www.daterangepicker.com/#options
         */
        let dateEl = $('#postForm .cf-date');
        dateEl.daterangepicker({
            autoUpdateInput: false,
            autoApply: true,
            showDropdowns: true,
            // minYear: parseInt(moment().format('YYYY')),
            // maxYear: parseInt(moment().format('YYYY')) + 10,
            locale: {
                format: '{{ t('datepicker_format') }}',
                applyLabel: "{{ t('datepicker_applyLabel') }}",
                cancelLabel: "{{ t('datepicker_cancelLabel') }}",
                fromLabel: "{{ t('datepicker_fromLabel') }}",
                toLabel: "{{ t('datepicker_toLabel') }}",
                customRangeLabel: "{{ t('datepicker_customRangeLabel') }}",
                weekLabel: "{{ t('datepicker_weekLabel') }}",
                daysOfWeek: [
                    "{{ t('datepicker_sunday') }}",
                    "{{ t('datepicker_monday') }}",
                    "{{ t('datepicker_tuesday') }}",
                    "{{ t('datepicker_wednesday') }}",
                    "{{ t('datepicker_thursday') }}",
                    "{{ t('datepicker_friday') }}",
                    "{{ t('datepicker_saturday') }}"
                ],
                monthNames: [
                    "{{ t('January') }}",
                    "{{ t('February') }}",
                    "{{ t('March') }}",
                    "{{ t('April') }}",
                    "{{ t('May') }}",
                    "{{ t('June') }}",
                    "{{ t('July') }}",
                    "{{ t('August') }}",
                    "{{ t('September') }}",
                    "{{ t('October') }}",
                    "{{ t('November') }}",
                    "{{ t('December') }}"
                ],
                firstDay: 1
            },
            singleDatePicker: true,
            startDate: moment().format('{{ t('datepicker_format') }}')
    })

        dateEl.on('apply.daterangepicker', function (ev, picker) {
            if (picker.startDate.format('YYYYMMDD') >= parseInt(moment().format('YYYYMMDD'))) {
                $(this).val(picker.startDate.format("{{ t('datepicker_format') }}"))

            } else {
                swal({
                    title: "OOPS!",
                    text: '{{ t("date_cannot_be_in_the_past") }}',
                    icon: "error",
                    button: "Ok",
                });
                $(this).val('');
            }
        });
    });
</script>

@endpush

