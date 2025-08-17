@extends('layouts.master')

@section('content')
@include('common.spacer')
<style>
    .select2-container{
        width:100% !important;
    }
</style>
    <link rel="stylesheet" type="text/css" href="<?= url()->asset('css/applicants.css?version='.time()) ?>"/>
    <div class="main-container">
        <div class="container">
            @include('account/inc/breadcrumbs')
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="tab1-tab" data-bs-toggle="tab"
                                        data-bs-target="#tab-applicants"
                                        type="button" role="tab" aria-controls="tab1" aria-selected="true">Applicants
                                    ({{ !empty($total_applicants) ? $total_applicants : 0 }})
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tab2-tab" data-bs-toggle="tab"
                                        data-bs-target="#tab-applicants-archived" type="button" role="tab"
                                        aria-controls="tab2"
                                        aria-selected="false">Archived Applicants ({{!empty( $total_archived_applicants) ? $total_archived_applicants : 0 }})
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>


            <br>
            @include('account.applicants.inc.filters')
            <hr>
            <div class="tab-content mt-3" id="myTabContent">
                <div class="tab-pane fade show active" id="tab-applicants" role="tabpanel" aria-labelledby="tab1-tab">

                    <p style="text-align: start;">
                        If at any point you open a Contact Card and the employee’s CV doesn’t match the Skills Sets they
                        put
                        for themselves,
                        please contact us by
                        <a data-toggle="modal" data-target="#exampleModal" href="" data-toggle="tooltip"
                           data-placement="top">
                            {{t('clicking HERE')}}</a>
                        and provide us with their name and we will credit back to you a free Contact Card if your
                        request is
                        approved.
                    </p>

                </div>
                <div class="tab-pane fade" id="tab-applicants-archived" role="tabpanel" aria-labelledby="tab2-tab">

                    <p style="text-align: start;">
                        All employees that applied to your job posts will show up here after your 30 day subscription
                        expires. We moved them for you from the Applicants tab to the Archived Applicants tab. To access
                        their CV’s you will have to resubscribe to one our 30 day packages.
                        <br>
                        Please keep in mind, after 3 months of being archived, your applicants will be erased from this
                        page.
                        <br>
                        You can contact the Hungry For Jobs team for any inquiries through chatting with us on your My
                        Profile page.
                    </p>
                </div>
            </div>
            <hr>
            <p><strong>Drag and drop an applicant into the desired status box (e.g., "Interview," "Rejected,"
                    "Hired"). Their status will automatically update based on the box you place them
                    in.</strong>
            </p>
            <hr>
            @include('account.applicants.inc.applicants_data')
        </div>
    </div>

    @include('account.inc.modals.applicant_detail')
    @include('account.inc.modals.reject_reason')
    @include('pages.inc.contact-cardpopup')
@endsection
@section('after_scripts')
    <script src="{{ url()->asset('js/jquery-ui.min.js') }}"></script>
    <script src="{{ url()->asset('js/bootstrap5.min.js') }}"></script>
    <script src="{{ url()->asset('js/jquery.ui.touch-punch.min.js') }}"></script>

    <script>
        let ask_swal_popup = true;
        let baseUrl = `{{ url('/') }}`;
        let applicantsData = [];
        let selectedTabIndex = '#tab-applicants'; // Default to first tab

        document.addEventListener('DOMContentLoaded', function () {


            function get_query_prams() {
                let skillSet = $('select[name="skill_set"]').val();
                let postId = $('select[name="post_id"]').val();
                let show_not_accurate_employee = $('select[name="show_not_accurate_employee"]').val();
                let showUnlockFromCv = $('select[name="show_unlock_from_cv"]').val();
                let search = $('input[name="search"]').val();
                let queryParams = new URLSearchParams({
                    skill_set: skillSet || '',
                    post_id: postId || '',
                    show_not_accurate_employee: show_not_accurate_employee || '',
                    show_unlock_from_cv: showUnlockFromCv || '',
                    search: search || ''
                });
                return queryParams;
            }


            $('.skill_set, .post, #not_accurate select, #show_unlock_from_cv select, #search').on('change keyup', function () {
                load_applicant_data();
            });

            $('.reset_filters').on('click', function () {

                $('.skill_set, .post, #not_accurate select, #show_unlock_from_cv select, #search').val('');
                $('.skill_set, .post, #not_accurate select, #show_unlock_from_cv select').val(null).trigger('change');
                load_applicant_data();
            });


            $('#myTab button[data-bs-toggle="tab"]').on('shown.bs.tab', function (event) {
                selectedTabIndex = $(event.target).data('bs-target');
                load_applicant_data();
            });


            load_applicant_data();

            function load_applicant_data() {
                let url_fetch = '';
                if (selectedTabIndex === '#tab-applicants') {
                    url_fetch = `{{ url('account/applicants_ajax') }}`;
                } else {
                    url_fetch = `{{ url('account/archived_applicants_ajax') }}`;
                }

                let queryParams = get_query_prams();
                fetch(`${url_fetch}?${queryParams.toString()}`)
                    .then(response => response.json())
                    .then(data => {
                        $('#applied-tasks, #interview-tasks, #rejected-tasks, #hired-tasks').empty();
                        $('#applied_count').text(data.applicant_count.applied_count);
                        $('#interview_count').text(data.applicant_count.interview_count);
                        $('#rejected_count').text(data.applicant_count.rejected_count);
                        $('#hired_count').text(data.applicant_count.hired_count);


                        $('select[name="show_not_accurate_employee"]').select2('destroy');

                        $('select[name="show_not_accurate_employee"]').empty();

                        $('select[name="show_not_accurate_employee"]').append(
                            `<option value="">Show “Not Accurate” Applicants (${data.not_accurate_employee_count})</option>`
                        );
                        $('select[name="show_not_accurate_employee"]').append(
                            `<option value="Yes">${'Yes, Allow Me To See “Not Accurate” Applicants'}</option>`
                        );
                        $('select[name="show_not_accurate_employee"]').append(
                            `<option value="No">${'No, Don’t Allow Me To See “Not Accurate” Applicants'}</option>`
                        );
                        $('select[name="show_not_accurate_employee"]').select2();


                        applicantsData = data.applicant_data
                        generate_applicant_html(applicantsData)

                        const skillSetDropdown = $('.skill_set');
                        skillSetDropdown.empty(); // Clear existing options
                        skillSetDropdown.append('<option value="">Search by Skills Sets</option>');

                        if (data.skill_sets_array_list && data.skill_sets_array_list.length > 0) {
                            data.skill_sets_array_list.forEach(skill => {
                                skillSetDropdown.append(`<option value="${skill}">${skill}</option>`);
                            });
                        }

                    });
            }


            function getUser(id) {
                return applicantsData.find(applicant => applicant.id === id);
            }

            function generate_applicant_html(applicantsData) {

                applicantsData.forEach((applicant, key) => {
                    var type = '';
                    var card_type = applicant.skill_accuracy === 'Very Accurate' ? 'card-success' : applicant.skill_accuracy === 'Accurate' ? 'card-warning' : applicant.skill_accuracy === 'Not Accurate' ? 'card-danger' : '';
                    var type = '';
                    if (applicant.status === 'applied') {
                        type = 'Applied on';
                    } else if (applicant.status === 'interview') {
                        type = 'Interviewed on';
                    } else if (applicant.status === 'rejected') {
                        type = 'Rejected on';
                    } else if (applicant.status === 'hired') {
                        type = 'Hired on';
                    }
                    let date = new Date(applicant.updated_at);
                    let formattedDate = date.toLocaleDateString();
                    let applicantImage = applicant.image_url;
                    let applicantElement = `
                            <div class="card ${card_type}  user-card mb-2 task" data-id="${applicant.id}">
                                                                            <div class="card-block">
                                            <div class="row">
                                            <div class="col-4">
                                            <div class="user-image">
                                            <img src="${applicantImage}" class="img-radius img-fluid" alt="${applicant.user.name}">
                                            </div>
                                            </div>
                                            <div class="col-6 pl-0 text-left">
                                            <h5 class="pb-0">${applicant.user.name}</h5>
                                            <p class="text-muted font-10 mb-0"> ${applicant.user.skill_set ? applicant.user.skill_set.replace(/,/g, ', ') : ''} <br>
                                            <span class="${applicant.skill_accuracy === 'Very Accurate' ? 'badge badge-success' : (applicant.skill_accuracy === 'Accurate' ? 'badge badge-warning' : (applicant.skill_accuracy === 'Not Accurate' ? 'badge badge-danger' : ''))}">
                                            ${applicant.skill_accuracy || ""}</span>
                                            </p>
                                            </div>
                                            <div class="col-2 pl-0 text-center">
                                            <a href="${baseUrl}/profile/${applicant.user.id}" class="btn btn-primary btn-sm"><i class="fa fa-user-tie"></i></a>
                                            <a href="javascript:void(0)" class="btn btn-info btn-sm mt-1 mb-1" onclick='open_applicant_detail_modal(${applicant.id})'><i class="fa fa-arrow-right"></i></a>
                                            </div>
                                            </div>
                                            <p class="text-muted text-left font-10 mb-0">Applied for : <strong>${applicant.contact_unlock && applicant.contact_unlock == 1 ? 'Unlocked This Contact Through CV Search Page' : applicant.post.title}</strong>
                                            <br>
                                                Nationality: <strong>${applicant.user.nationality_data ? applicant.user.nationality_data.name : ''}</strong>
                                            <br>
                                            <strong>${type}:</strong> ${formattedDate || ''}
                                              <br>
                                              Contact Card Opened? : <strong>    ${applicant.user_unlocked == 1 ? '✅' : '❌'}</strong>
                                            </p>
                                           </div>
                    </div>`;
                    if (applicant.status === 'applied') {
                        $('#applied-tasks').append(applicantElement);
                    } else if (applicant.status === 'interview') {
                        $('#interview-tasks').append(applicantElement);
                    } else if (applicant.status === 'rejected') {
                        $('#rejected-tasks').append(applicantElement);
                    } else if (applicant.status === 'hired') {
                        $('#hired-tasks').append(applicantElement);
                    }
                });
            }

            $('.task-column').sortable({
                connectWith: '.task-column',
                receive: function (event, ui) {
                    let taskId = ui.item.data('id');
                    let newStatus = $(this).attr('id').split('-')[0];

                    if (newStatus === 'hired') {
                        const config = {
                            title: 'Attention',
                            text: '',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No',
                            html: `
                                <p> Are you sure you would like to Hire this applicant? An automatic email will be sent to congratulate them with their new position  with you - please only click Yes if you will actually be hiring them.</p>

                            `,
                        };

                        Swal.fire(config).then((result) => {
                            if (result.isConfirmed) {
                                send_update_status_request(taskId, newStatus);
                            } else {
                                $(ui.sender).sortable('cancel');
                            }
                        });
                    } else {
                        send_update_status_request(taskId, newStatus);
                    }
                }
            }).disableSelection();


            function send_update_status_request(taskId, newStatus) {
                if (newStatus === 'rejected') {
                    $('#rejected_modal').data('task-id', taskId);
                    $('#rejected_modal').data('new-status', newStatus);
                    $('#rejected_modal').modal('show');
                } else {
                    update_status(taskId, newStatus, null);
                }
            }

            function update_status(taskId, newStatus, rejectedReason) {
                let queryParams = get_query_prams();
                console.log(queryParams.toString());
                const data = {
                    applicant_id: taskId,
                    status: newStatus,
                    type: selectedTabIndex,
                };

                if (rejectedReason) {
                    data.rejected_reason = rejectedReason;
                }
                let url = `{{ url('account/Applicants/update_applicant_status_ajax') }}?${queryParams.toString()}`;

                $.post(url, data, function (response) {
                    if (response.status) {

                        $('#applied_count, #interview_count, #rejected_count, #hired_count').empty();
                        $('#applied_count').text(response.data.applied_count);
                        $('#interview_count').text(response.data.interview_count);
                        $('#rejected_count').text(response.data.rejected_count);
                        $('#hired_count').text(response.data.hired_count);

                        toastr.success(response.message, 'Applicant Status');
                    } else {
                        toastr.error(response.message, 'Applicant Status');
                    }
                });
            }

            $('#rejected_modal').on('click', '.btn-primary', function () {
                const taskId = $('#rejected_modal').data('task-id');
                const newStatus = $('#rejected_modal').data('new-status');
                const rejectedReason = $('select[name="rejected_reason"]').val();

                if (!rejectedReason) {
                    showSwalAlert('Error!', 'Please select rejection reason first', 'warning', 'Ok');
                    return false;
                }
                update_status(taskId, newStatus, rejectedReason);
                $('#rejected_modal').modal('hide');
            });

            window.open_applicant_detail_modal = function (applicantid) {
                let applicant = getUser(applicantid);
                var type = '';
                if (applicant.status === 'applied') {
                    type = 'Applied on';
                } else if (applicant.status === 'interview') {
                    type = 'Interviewed on';
                } else if (applicant.status === 'rejected') {
                    type = 'Rejected on';
                } else if (applicant.status === 'hired') {
                    type = 'Hired on';
                }

                // Format the updated date
                let date = new Date(applicant.updated_at);
                let formattedDate = date.toLocaleDateString();
                let applicantImage = applicant.image_url;
                let flagimage = '{{ url()->asset('images/flags/16') }}/' + applicant.user.country_code.toLowerCase() + '.png';

                // Build the card design with the applicant data
                let htmlContent = `<div class="user-image">
                        <img src="${applicantImage}" class="img-radius img-fluid" alt="User Image">
                    </div>
                    <h6 class="f-w-600 m-t-25 m-b-10">${applicant.user.name}</h6>
                    <br>
                    <p class="m-t-15">${applicant.user.city_data.name}, ${applicant.user.country.name} <img src="${flagimage}"/>
</p>
                    <p class="m-t-15">Nationality: ${applicant.user.nationality_data ? applicant.user.nationality_data.name : ''}</p>
                        <p class="m-t-15">Skills: ${applicant.user.skill_set ? applicant.user.skill_set.replace(/,/g, ', ') : ''} <br>
                    <p class="text-muted"><strong>Post Title:</strong> ${applicant.contact_unlock && applicant.contact_unlock == 1 ?
                    'Unlocked This Contact Through CV Search Page' :
                    applicant.post.title || 'N/A'}
                    </p>
                    <p class="text-muted"><strong>${type}:</strong> ${formattedDate || ''}</p>
                    <p class="text-muted"><strong>Skill Accuracy:</strong>
                           <span class="badge
                                        ${applicant.skill_accuracy === 'Very Accurate' ? 'badge-success' :
                    (applicant.skill_accuracy === 'Accurate' ? 'badge-warning' :
                        (applicant.skill_accuracy === 'Not Accurate' ? 'badge-danger' : ''))}">
                                        ${applicant.skill_accuracy || ""}
                           </span>
                      </p>
                         <a href="${baseUrl}/profile/${applicant.user.id}" class="btn btn-primary btn-sm">View Profile <i class="fa fa-user-tie"></i></a>

                        `;
                $('#applicant-detail-body').html(htmlContent);
                $('#applicant_detail').modal('show');
            }


        });

    </script>

@endsection
