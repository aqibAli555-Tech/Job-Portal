<?php

use App\Models\Availability;
?>
@extends('layouts.master')

@section('content')
@include('common.spacer')

<div class="main-container">
    <div class="container">
        @include('account/inc/breadcrumbs')

        <div class="row">

            <div class="col-md-3 page-sidebar">
                @include('account.inc.sidebar')
            </div>
            <!--/.page-sidebar-->
            <div class="col-md-9 page-content">
                <div class="inner-box">

                    <h2 class="title-2"><i class=""></i> {{ t('Unlocked Contact Cards') }}
                        <i class="fas fa-question-circle" hidden title="{{ t('List Of All Unlock Employee Contact Card') }}" data-toggle="tooltip" data-placement="top"></i>
                    </h2>

                    <div class="alice-bg section-padding-bottom">
                        <div class="container no-gliters">
                            <div class="row no-gliters">
                                <div class="col">
                                    <div class="dashboard-container">
                                        <div class="dashboard-content-wrapper">
                                            @include('flash::message')
                                            <div class="manage-job-container">
                                                <p>
                                                    If at any point you open a Contact Card and the employee’s CV doesn’t match the Skills Sets they put for themselves, please contact us by
                                                    <a data-toggle="modal" data-target="#exampleModal" href="" data-toggle="tooltip" data-placement="top">
                                                        {{t('clicking HERE')}}</a>
                                                    </a>
                                                    and provide us with their name and we will credit back to you a free Contact Card if your request is approved.
                                                    <br><br>
                                                         Important Notice: The most recent Contact Card you opened will be #1 on the below list
                                                </p>
                                                {!! csrf_field() !!}

                                                <form action="{{url('account/Unlocked-Contact-Cards')}}" method="get">

                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <select class="form-control post select1" name="post_id" required>
                                                                <option value=""> {{t('search_by_job_post')}}</option>
                                                                @if(!empty($posts))
                                                                    @foreach($posts as $item)
                                                                        <option value="{{$item->id}}" <?php if (request('post_id') == $item->id) {
                                                                            echo "selected";
                                                                        } ?>>{{$item->title}}
                                                                        </option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <select class="form-control" id="status"
                                                                    name="status">
                                                                <option value="">All Status</option>
                                                                <?php $status_array = array('applied', 'interview', 'hired', 'rejected');
                                                                foreach ($status_array as $key => $value) {
                                                                    ?>
                                                                <option value="<?= $value ?>" <?php if (request('status') == $value) {
                                                                    echo 'selected';
                                                                } ?>>
                                                                    {{t($value)}}
                                                                </option>
                                                                <?php } ?>

                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="submit" class="btn btn-primary btn-block">Search</button>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button class="btn btn-primary btn-block" onclick="window.location.href('{{url('account/Unlocked-Contact-Cards')}}')">Reset</button>
                                                        </div>

                                                    </div>
                                                </form>

                                                <br>

                                                 <div class="table-responsive">
                                                <table id="employee_data" style="width: 100%;" class="table">
                                                    <thead>
                                                        <tr>
                                                            <th data-sort-ignore="true">#</th>
                                                             <th data-type="numeric" data-sort-initial="true">
                                                                    {{ t('Image') }}
                                                                </th>
                                                                <th data-type="numeric" data-sort-initial="true">
                                                                    {{ t('Username') }}
                                                                </th>
                                                            <th data-sort-ignore="true">{{t('Skills Sets')}}</th>
                                                            <th data-sort-ignore="true">{{t('availability')}}</th>
                                                            <th data-sort-ignore="true">{{ t('Nationality') }}</th>
                                                            <th data-type="numeric">{{t('Date')}}</th>
                                                            <th class="action">{{t('chat')}}</th>
                                                            <th class="action">{{t('Profile')}}</th>

                                                            {{--
                                                        <th class="action">chat</th>
                                                        --}}
                                                        </tr>
                                                    </thead>
                                                    <tbody id="Applied">
                                                        <?php

                                                        $count = 0 ?>
                                                        @foreach($data as $key => $item)
                                                        <?php $count++;
                                                        $availability_name = '';
                                                        if(!empty($item->user)){
                                                        if (!empty($item->user->availabilityData)) {

                                                            if (!empty($item->user->availabilityData)) {
                                                                $availability_name = $item->user->availabilityData->name;
                                                            } else {
                                                                $availability_name = '';
                                                            }
                                                        }
                                                        ?>

                                                        <tr class="job-items">
                                                            <td>
                                                                {{$count}}
                                                            </td>
                                                             <td>

                                                                        <div class="user-image-div-message"
                                                                            style="background-image:url('{{ \App\Helpers\Helper::getImageOrThumbnailLink($item->user) }}')">
                                                                        </div>
                                                                    </td>
                                                                    <td class="title"><a
                                                                            href="{{ url('/profile/') }}/{{ $item->user->id }}"
                                                                            class="text-capitalize">{{ $item->user->name }}</a>
                                                                    </td>

                                                            <td class="title" style="width:100px">

                                                                <?php

                                                                $skill_sets = $item->user->skill_set;
                                                                $skill_sets = str_replace(',', ', ', $skill_sets);
                                                                echo $skill_sets;
                                                                ?>
                                                            </td>


                                                            <td class="title">{{$availability_name}}</td>
                                                            <td class="title"><?= $item->user->nationalityData->name??'' ?></td>

                                                            <td class="title"><?= date('d-M-Y', strtotime($item->created_at)) ?></td>
                                                            <td class="title">
                                                                <a id="Apply_Online" onclick="changeMessageUserId(<?= $item->user->id ?>)" data-toggle="modal" data-target="#apply-popup-id" href="" class="save-btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top" title="Click me to Send Message">
                                                                    {{t('chat')}}</a>
                                                                </a>
                                                            </td>
                                                            <td class="title">
                                                                <a href="{{url('/profile/')}}/{{$item->user->id}}" class="save-btn btn-sm btn-primary">{{t('Profile')}}</a><br>

                                                                <a  class="save-btn btn-sm btn-primary d-block mt-2" onclick="track_applicant('{{$item->user->id}}')">{{trans('admin.track_applicant')}}</a>
                                                            </td>


                                                        </tr>
                                                        <?php } ?>
                                                        @endforeach
                                                        @if (count($data) < 1 && empty(request('post_id')))
                                                            <tr class="job-items">
                                                            <td colspan=7>
                                                                <h6 class="text-muted" style="text-align: center">You haven't unlocked any contact cards yet - click on employee (job seeker) profiles to unlock their contact cards and view their contact information</h6>
                                                            </td>
                                                            </tr>
                                                            @endif
                                                    </tbody>
                                                </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @include('pages.inc.compose-message')
    @include('pages.inc.contact-cardpopup')
    @include('account.inc.modal')
    @endsection

    @section('after_scripts')

    <script>
        function changeMessageUserId(id) {
            // alert(id);
            $('#send_user_id').val(id);
        }

        function track_applicant(id) {
            var userTable = $('#userTable tbody');
            userTable.empty();
            //   $('#overlay').show();
            $.ajax({
                url: "{{ url('track_applicant_in_employer') }}" + "/" + id, // URL to the API endpoint
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    //   $('#overlay').hide();
                    var sn = 1;
                    userTable.empty();

                    $.each(response, function(index, user) {
                        if (user.post != null) {
                            var createdDate = new Date(user.post.created_at);
                            var formattedDate = createdDate.toLocaleDateString('en-US', {
                                day: '2-digit',
                                month: 'short',
                                year: 'numeric'
                            }).replace(/\//g, '-');
                        }

                        var updated_at = new Date(user.updated_at);
                        var updated_at = updated_at.toLocaleDateString('en-US', {
                            day: '2-digit',
                            month: 'short',
                            year: 'numeric'
                        }).replace(/\//g, '-')



                        var row = $('<tr>');
                        row.append($('<td>').text(sn));
                        if (user.post != null) {
                            row.append($('<td>').html(user.post.title + "<br>" + formattedDate));
                        } else {
                            row.append($('<td>').text(
                                "{{ t('Unlocked This Contact Through CV Search Page') }}"));
                        }
                        row.append($('<td>').text(user.company_data.name));
                        var statusBadge = $('<span>').addClass('badge');

                        if (user.status == 'applied') {
                            statusBadge.addClass('badge-warning').text('Applied');
                        } else if (user.status == 'interview') {
                            statusBadge.addClass('badge-dark').text('Interview');
                        } else if (user.status == 'hired') {
                            statusBadge.addClass('badge-success').text('Hired');
                        } else if (user.status == 'rejected') {
                            statusBadge.addClass('badge-danger').text('Rejected');
                        } else if (user.status == 'pending') {
                            statusBadge.addClass('badge-warning').text('Pending');
                        } else {
                            statusBadge.text('Unknown');
                        }
                        const updatedAtElement = $('<span>').text(updated_at);

                        const statusCell = $('<td>').append(statusBadge).append('<br>').append(updatedAtElement);

                        row.append(statusCell);

                        var actionDropdown = $(
                            '<select class="options" onchange="change_applicants_status(this.value)">'
                        ); // Corrected the <select> element



                        if (user.status == 'applied') {
                            actionDropdown.append('<option>Select option</option>');
                            actionDropdown.append('<option value="account/Applicants/haired/' + user
                                    .id +
                                '" style="background-color: #2ecc71;color: white;" id="hired">Hired</option>'
                            );
                            actionDropdown.append('<option value="account/Applicants/interview/' + user
                                    .id +
                                '" style="background-color: black;color: white;">Interview</option>'
                            );
                            actionDropdown.append('<option value="account/Applicants/rejected/' + user
                                    .id +
                                '" style="background-color: #d9534f;color: white;">Rejected</option>'
                            );
                        } else if (user.status == 'rejected') {
                            actionDropdown.append('<option>Select option</option>');
                            actionDropdown.append('<option value="account/Applicants/haired/' + user
                                    .id +
                                '" style="background-color: #2ecc71;color: white;" id="hired">Hired</option>'
                            );
                            actionDropdown.append('<option value="account/Applicants/interview/' + user
                                    .id +
                                '" style="background-color: black;color: white;">Interview</option>'
                            );

                        } else if (user.status == 'hired') {
                            actionDropdown.append('<option>Select option</option>');
                            actionDropdown.append('<option value="account/Applicants/interview/' + user
                                    .id +
                                '" style="background-color: black;color: white;">Interview</option>'
                            );
                            actionDropdown.append('<option value="account/Applicants/rejected/' + user
                                    .id +
                                '" style="background-color: #d9534f;color: white;">Rejected</option>'
                            );
                        } else if (user.status == 'interview') {
                            actionDropdown.append('<option>Select option</option>');
                            actionDropdown.append('<option value="account/Applicants/haired/' + user
                                    .id +
                                '" style="background-color: #2ecc71;color: white;" id="hired">Hired</option>'
                            );
                            actionDropdown.append('<option value="account/Applicants/rejected/' + user
                                    .id +
                                '" style="background-color: #d9534f;color: white;">Rejected</option>'
                            );
                        } else {
                            // Add other options or handle different status cases
                            actionDropdown.append('<option>No actions available</option>');
                        }
                        row.append($('<td>').append(actionDropdown));
                        userTable.append(row);
                        sn = sn + 1;
                    });
                    $('#track_applicant_modal').modal('show');
                },
                error: function(error) {
                    // Handle errors
                    console.error('An error occurred:', error);
                }
            });
        }


        function change_applicants_status(myurl) {
            $('#track_applicant_modal').modal('hide');
            var url = "<?php echo url()->to('/') ?>/" + myurl;
            if (url.indexOf("interview") != -1) {
                var icons = '';
                var message =
                    'Are you sure you would like to Interview this applicant? An automatic email will be sent to them stating your potential interest and that you could be contacting them.';
            }
            if (url.indexOf("haired") != -1) {
                var icons = 'success';
                var message =
                    'Are you sure you would like to Hire this applicant? An automatic email will be sent to congratulate them with their new position  with you - please only click Yes if you will actually be hiring them.';
            }
            if (url.indexOf("rejected") != -1) {
                var icons = '';
                var message =
                    'Are you sure you want to reject this applicant? You can change their status anytime in the future.';
            }
            if (url.indexOf("applied") != -1) {
                var icons = '';
                var message = 'Are you sure you would like to Applied this applicant?.';
            }
            const config = {
                html: true,
                title: 'Attention',
                html: message,
                icon: icons,
                confirmButtonText: 'Yes',
                showCancelButton: true,
            };
            Swal.fire(config).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {

                    if (url.indexOf("rejected") != -1) {
                        var urlParts = url.split("/");
                        var id = urlParts[urlParts.length - 1];
                        $('#rejected_modal').modal('show');
                        $('#id').val(id);
                    } else {
                        window.location.replace(url);
                    }

                } else if (result.isDenied) {
                    return false;
                }
            });
        }
    </script>

    @endsection