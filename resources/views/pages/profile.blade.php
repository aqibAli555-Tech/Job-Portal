@extends('layouts.master')

@section('content')
@include('common.spacer')

<div class="main-container">
    <style>

    </style>
    <div class="container">
        @include('account/inc/breadcrumbs')
        <div class="row">
            @if (Session::has('flash_notification'))
            <div class="col-xl-12">
                <div class="row">
                    <div class="col-xl-12">
                        @include('flash::message')
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="row">
            <div class="col-md-3 page-sidebar">
                @include('account.inc.sidebar')
            </div>
            <!--/.page-sidebar-->
            <div class="col-md-9 page-content">
                <div class="inner-box">
                    <div class="row">
                        <div class="col-2 col-md-2" style="margin-left: 15px;">

                        
                        <a href="{{  \App\Helpers\Helper::getImageOrThumbnailLink($data['user'], true); }}" target="_blank">
                                <div class="user-image-div" data-lazysrc="{{  \App\Helpers\Helper::getImageOrThumbnailLink($data['user'], true); }}"
                                    style="background-image:url('{{  \App\Helpers\Helper::getImageOrThumbnailLink($data['user']); }}')">
                                </div>
                            </a>

                        </div>
                        <div class="job-seeker-profile">
                            <div class="row">
                            <h3><?= !empty($data['user']->name)?$data['user']->name:'' ?></h3>

                                <p class="job-seeker-profile-experiences ml-2">

                                    @if($data)
                                    <img src="{{ url('public/storage/app/default/ico/blue_tick.png') }}"
                                        style="width: 12px; margin-bottom: 3px;" class="img-fluid" alt="">
                                    @endif

                                </p>
                            </div>
                            <p class="job-seeker-profile-location">
                                {{ !empty($data['city_data']->name)?$data['city_data']->name:'' }},&nbsp;{{ !empty($data['country_data']->name)?$data['country_data']->name:'' }} <img src="{{ url()->asset('images/flags/16/' . strtolower($data['user']->country_code) . '.png') }}"/>
                              <br>
                                <span class="badge badge-primary">  Last Login: {{$data['user']->last_login_at}}</span>
                            </p>
                            @if (auth()->user()->user_type_id == 2)
                            <div class="job-seeker-profile-actions">
                                <a class="btn btn-primary btn-sm" href="{{ url('/account/profile') }}">
                                    {{ t('Edit Profile') }}
                                </a>
                            </div>
                            @endif

                            @if (auth()->user()->user_type_id == 1)
                            <div class="job-seeker-profile-actions">
                                @php
                                    $previousUrl = url()->previous();
                                @endphp
                                @if (str_contains($previousUrl, 'search-resumes'))
                                    <a class="btn btn-primary-dark btn-sm" href="javascript:void(0)" onclick="window.history.back()" id="backButton">
                                        <i class="fa fa-backward" style="color: white;"></i> Back
                                    </a>
                                @endif

                                @if (empty($data['fav_data']))
                                <a class="btn-default btn btn-sm heart-action"
                                    href="{{ url('account/add_to_favorite/') }}/{{ $data['user']->id }}">
                                    <i class="fa fa-heart" style="color: white;"></i>
                                </a>
                                @else
                                <a class="btn-default btn btn-sm heart-action" style="background: #22d3fd !important"
                                    href="{{ url('account/add_to_favorite/') }}/{{ $data['user']->id . '?remove=1' }}">
                                    <i class="fa fa-heart" style="color: white;"></i>
                                </a>
                                @endif

                                @if ($data['isUnlock'])
                                <a class="btn btn-primary btn-sm" hidden href="#DetailModal" data-toggle="modal"
                                    data-target="#DetailModal">
                                    {{ t('Contact Card: View CV + Get In Touch') }}
                                </a>
                                @else
                                @if ( $data['remainig_count'] > 0)
                                <?php
                                $today = date('Y-m-d');
                                if ( $data['remainig_count'] > 0 ) { ?>
                                     <div class="position-relative d-inline-block" id="popupWrapper">
                                        <button type="button" class="btn btn-primary btn-sm" id="popupToggleBtn">
                                            {{ t('Contact Card: View CV + Get In Touch') }}
                                        </button>

                                        <div id="popupBox" class="popup-box d-none">
                                            @if(!empty($data['user']->cv_no_contact) && $data['user']->is_approved_no_contact_cv == 1 )
                                                <a href="javascript:void(0);" class="btn btn-primary btn-sm mb-1 d-block view-no-contact-cv"
                                                    data-user-id="{{ $data['user']->id }}">
                                                    {{ t('No Contact Details CV - View For Free') }}
                                                </a>
                                            @endif
                                            <a href="#modal-unlock" data-toggle="modal" data-target="#modal-unlock" class="btn btn-primary btn-sm d-block">
                                                {{ t('Full CV With Contact Details + Chat  - View By Using 1 Contact Credit') }}
                                            </a>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                <a href="javascript:void(0)" onclick="unlock_profile()" class="btn btn-primary btn-sm">
                                    {{ t('Contact Card: View CV + Get In Touch') }}</a>
                                <?php } ?>
                                @else
                                <a href="javascript:void(0)" onclick="upgrade_account()"
                                    class="btn btn-primary btn-sm change">
                                    {{ t('Contact Card: View CV + Get In Touch') }}</a>
                                @endif
                                @endif

                            </div>

                            @if ($data['isUnlock'] == 1)
                            <div class="job-seeker-profile-actions-2">
                                <a class="btn btn-primary-dark btn-sm" id="Apply_Online" data-toggle="modal"
                                    data-target="#apply-popup-id">
                                    <i class="fa fa-envelope"></i>&nbsp;&nbsp;{{ t('chat') }}
                                </a>
                                <a class="btn btn-primary-dark btn-sm" target="_blank"
                                    href="{{ url('account/resumes/show_cv/' . $data['user']->id) }}">
                                    <i class="fa fa-download"></i>&nbsp;&nbsp;{{ 'Download CV' }}
                                </a>

                                @if (empty($data['save_cv_data']))
                                <a class="btn btn-primary-dark btn-sm"
                                    onclick="sendData('{{ $data['user']->id }}' ,'{{ $data['user']->id }}' , '{{ $data['user']->views }}')">
                                    <i class="fa fa-file-pdf"></i>&nbsp;&nbsp;{{ 'Save CV' }}
                                </a>
                                @else
                                <a class="btn btn-primary btn-sm noHover" style="background: #22d3fd;"
                                    onclick="sendData('{{ $data['user']->id }}' ,'{{ $data['user']->id }}' , '{{ $data['user']->views }}')">
                                    <i class="fa fa-file-pdf" style="color: white;"></i>&nbsp;&nbsp;{{ 'Save CV' }}
                                </a>
                                @endif

                            </div>
                            @endif
                            @endif

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            @auth
                            @if (auth()->user()->user_type_id == 2)
                            <p
                                style="background: #615583;background: #615583;color: #fff;padding:10px;margin-top: 20px; margin-bottom: 10px;">
                                Please keep in mind, if your CV doesnt match the Skills Sets you have chosen for
                                yourself - then Companies will NOT contact you & hire you! Make sure the Skills Sets
                                you
                                choose are accurate!</p>
                            @endif
                            @endauth
                        </div>
                    </div>

                    <hr>


                    <div class="row">
                        <div class="col-md-6">

                            <p class="m-0"><span class="job-seeker-profile-heading">{{ t('Skills Set') }}: </span>
                            </p>
                            <p class="m-0">
                                <?php
                                $skill_sets = $data['user']->skill_set;
                                $skill_sets = str_replace(',', ', ', $skill_sets);
                                echo $skill_sets;
                                ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="m-0"><span class="job-seeker-profile-heading">{{ t('availability') }}: </span>
                            </p>
                            <p class="m-0">

                                <?= $data['user']->availabilityData->name ?>
                            </p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <p class="m-0"><span class="job-seeker-profile-heading">{{ t('Experience') }}: </span>
                            </p>
                            <p class="m-0">
                                @if (!empty($data['user']->experiences))
                                {{ $data['user']->experiences }}
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="m-0"><span class="job-seeker-profile-heading">{{ t('Nationality') }}: </span>
                            </p>
                            <p class="m-0">

                                @if (!empty($data['user']->nationalityData->name))
                                {{ $data['user']->nationalityData->name }}
                                @endif
                            </p>
                        </div>
                        
                            <div class="col-md-6  mt-3">
                                @if (!empty($data['user']->visa))
                                    <p class="m-0"><span class="job-seeker-profile-heading">{{ t('Work Visa') }}:
                                        </span>
                                    </p>
                                    <p class="m-0">
                                        {{ $data['user']->visa }}


                                    </p>
                                @endif
                            </div>
                                @if (!empty($data['user']->country_work_visa) && $data['user']->country_work_visa == 'KW')

                            <div class="col-md-6  mt-3">
                                    <p class="m-0"><span class="job-seeker-profile-heading">{{ t('Work Visa Type') }}:
                                        </span>
                                    </p>
                                    <p class="m-0">
                                        @if (!empty($data['user']->visa_number) && $data['user']->visa == 'Yes, I HAVE a visa')
                                            {{ $data['user']->visa_number }}
                                        @endif

                                    </p>
                               
                            </div>
                             @endif
                               @if (!empty($data['user']->countryVisa->name) && $data['user']->visa == 'Yes, I HAVE a visa')
                            <div class="col-md-6  mt-3">
                              
                                    <p class="m-0"><span
                                            class="job-seeker-profile-heading">{{ t('Country of Work Visa') }}: </span>
                                    </p>
                                    <p class="m-0">
                                        {{ $data['user']->countryVisa->name }}

                                    </p>
                               
                            </div>
                             @endif
                      
                    </div>
                        
                  

                </div>

            </div>
        </div>
    </div>
</div>

@include('pages.inc.compose-message')
@include('modals.no_contact_cv');


<div class="modal fade modal-delete" id="modal-unlock" tabindex="1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close myClose" data-dismiss="modal">
                    &times;
                </button>
                <h4><i data-feather="calendar"></i>{{ t('Confirmation') }}</h4>
                <p>{{ t('Are you sure you want to view contact card?') }}</p>
                <small
                    class="text-danger"><b>{{ t('Your subscription package expires every 30 days job post credits and contact card credits also expire with the subscription package') }}</small><br>
                <small
                    class="text-danger"><b>{{ t('Viewing this Contact Card will remove 1 credit from your balance') }}</small>
                <br><br>
                <div style="text-align: right">
                    <style>
                    .yes:focus,
                    .yes:hover {
                        color: white !important;
                        background-color: #6f42c1;
                    }
                    </style>
                    <a href="{{ url('/UnlockProfile/') }}/{{ $data['user']->id }}" class="btn btn-primary" style="">
                        {{ t('Yes') }}
                    </a>
                    <a href="#" class="btn btn-primary" data-dismiss="modal" style="">
                        {{ t('No') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade contact-form-modal" id="saveResumeModal" tabindex="-1" role="dialog" aria-hidden="true">

    <div class="modal-dialog" role="document" class="close" data-dismiss="alert" aria-label="Close">
        <div class="modal-content">
            <div class="modal-body">
                <h4>&nbsp; {{ t('Your Resume Saved') }}</h4>
            </div>

        </div>

    </div>

</div>


<div class="modal fade modal-delete" id="sendByEmail" style="z-index: 111111111;" tabindex="-1" role="dialog"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" id="dismiss" class="close myClose" data-dismiss="modal">
                    &times;
                </button>
                <h4><i data-feather="upload"></i>{{ t('Send by Email') }}</h4>
                <p></p>
                <form name="form" id="_form" role="form">
                    <input name="_method" type="hidden" value="POST">
                    <input name="pdf" id="pdf_url" type="hidden" value="{{ $data['user']->pdf }}">
                    <div class="form-group required">
                        <label for="recipient_email" class="control-label">Recipient Email
                            <sup>*</sup></label>
                        <input id="recipient_email" name="recipient_email" type="text" maxlength="60"
                            class="form-control" value="">
                    </div>
                    <div class="form-group required">
                        <label for="subjuct" class="control-label">Subject
                            <sup>*</sup></label>
                        <input id="subjuct" name="subjuct" type="text" maxlength="60" class="form-control">
                    </div>
                    <div class="form-group required">
                        <label for="subjuct" class="control-label">Message
                            <sup>*</sup></label>
                        <textarea class="form-control" id="body" name="body" style="height: 80px;"></textarea>
                    </div>
                    <div class="buttons">
                        <button class="btn btn-info" id="sendMailsend" type="submit" name="sendMailsend">{{ t('Send') }}
                        </button>
                        <button class="btn btn-primary" type="reset" class="delete-button" data-dismiss="modal">
                            {{ t('Cancel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection
@section('after_scripts')
<script>
      $(document).ready(function() {
        var $userImageDiv = $('.user-image-div');
        var bg = $userImageDiv.attr('data-lazysrc');
        
        setTimeout(function() {
            // Check if the actual image is loaded
            var img = new Image();
            img.onload = function() {
                $userImageDiv.css('background-image', 'url("' + bg + '")');
            };
            img.onerror = function() {
                console.log('Image failed to load');
            };
            img.src = bg;
        }, 5000); // 5000 milliseconds = 5 seconds
    });
function sendData(valueId, applicant_id, views) {

    var URL = '<?= lurl('account/save_resume_add') ?>';
    $.ajax({
        type: "POST",
        url: URL,
        data: {
            id: valueId,
            user_id: applicant_id,
            page: 'Search Page'
        },
        success: function(result) {

            result = result.replace(/[0-9]/g, '');
            console.log(result);
            Swal.fire({
                title: "",
                text: result,
                icon: "success",
                button: "Ok",
            });
            window.location.reload();
            // return;
            // $('#succ').show();
            // $('#succ').text(result);
        }
    });
    var u = '<?= lurl('account/resumes/resumeViews') ?>';
    $.ajax({
        type: "POST",
        url: u,
        data: {
            id: valueId,
            views: views
        }
    });
}

function applicantCall(id) {
    document.getElementById('applicant_id').value = id;
}

$('#SMSForm').submit(function(e) {
    e.preventDefault();
    swal({
        title: "OOPS!",
        text: "Under Development",
        icon: "error",
        button: "Ok",
    });

});
$('#_form').submit(function(e) {
    e.preventDefault();
    var AjaxURL = '<?= lurl('account/resumes/sendResumeByEmail') ?>';
    $.ajax({
        type: "POST",
        url: AjaxURL,
        data: $('#_form').serialize(),
        beforeSend: function() {
            $("#dismiss").click();
        },
        success: function(result) {
            console.log(result);
            document.getElementById("alert-success").style.display = "block";
        }
    });
});

function sendMail(url) {
    document.getElementById('pdf_url').value = url;
}

var check1 = '0';
$(document).ready(function() {

    $('#cliced').click(function() {
        if (check1 == '0') {
            $("#Filter").addClass('show');
            check1 = '1';
        } else {
            $("#Filter").removeClass('show');
            check1 = '0';
        }
    });
});

function filterMe() {
    var scat = document.getElementById('Scat').value;
    var skey = document.getElementById('Skey').value;
    document.getElementById('Fcat').value = scat;
    document.getElementById('Fkey').value = skey;
}

function unlock_profile() {
    var url = '<?= url('account/upgrade') ?>';
    var message = '<?= t('You have reached the maximum amount of Contact') ?>';
    const config = {
        html: true,
        title: 'Attention',
        html: message,
        icon: 'error',

        confirmButtonText: 'Subscribe',
        showCancelButton: true,
    };
    Swal.fire(config).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            window.location.replace(url);
        } else if (result.isDenied) {
            return false;
        }
    });
}

const toggleBtn = document.getElementById('popupToggleBtn');
const popupBox = document.getElementById('popupBox');

toggleBtn.addEventListener('click', function (e) {
    popupBox.classList.toggle('d-none');
});

function hidePopup() {
    popupBox.classList.add('d-none');
}

document.addEventListener('click', function (e) {
    if (!document.getElementById('popupWrapper').contains(e.target)) {
        popupBox.classList.add('d-none');
    }
});

$(document).on('click', '.view-no-contact-cv', function () {
    let userId = $(this).data('user-id');
    url = '{{url("account/resumes/no-contact-cv/")}}/' + userId;
    $.ajax({
        url: url,
        type: 'GET',
        success: function (response) {
            if (response.fileUrl) {
                let iframeUrl = "{{ url('public/pdfjs/web/viewer.html') }}?file=" + response.fileUrl;
                $('#cvIframeContainer').html(`
                    <iframe src="${iframeUrl}" width="100%" height="100%" style="border: none;"></iframe>
                `);
                $('#view_no_contact_cv').modal('show');
            }
        },
        error: function (xhr) {
            Swal.fire('Error', xhr.responseJSON?.error || 'Something went wrong', 'error');
        }
    });
});
</script>
@endsection