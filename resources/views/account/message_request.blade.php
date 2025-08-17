@extends('layouts.master')

@section('content')
@include('common.spacer')
<div class="main-container">
    <div class="container">
        @include('account/inc/breadcrumbs')
        <style>
        .header-title {
            display: -webkit-inline-box;
        }

        .swal-wide {
            width: 650px !important;

        }

        .swal2-html-container {
            text-align: left !important;
        }
        </style>

        <div class="row">

            <div class="col-md-3 page-sidebar">
                @include('account.inc.sidebar')
            </div>
            <!--/.page-sidebar-->
            <div class="col-md-9 page-content">
                <div class="inner-box">
                    <div class="alice-bg section-padding-bottom">
                        <div class="container no-gliters">
                            <div class="row no-gliters">
                                <div class="col">
                                    <div class="dashboard-container">
                                        <div class="dashboard-content-wrapper">
                                            @include('flash::message')

                                            <!--....................... Below We are adding the icons........................ -->
                                            <div class="dashboard-section basic-info-input header-title">
                                                <h2>{{ t('bulk_chat_requests') }}
                                                </h2>



                                            </div>
                                            <button class="btn btn-primary m-1" data-toggle="modal" data-placement="top"
                                                data-target="#message_request"
                                                style="float: right;">{{t('Create Bulk Chat')}}</button>
                                            <div class="manage-job-container">
                                                <div class="table table-responsive">
                                                    {{-- class="table-responsive" --}}
                                                    <table class="table text-left ">
                                                        <thead>
                                                            <tr>
                                                                <th data-sort-ignore="true">{{ t('job_post_title') }}
                                                                </th>
                                                                <th data-sort-ignore="true">{{ t('skill_set') }}</th>
                                                                <th data-type="numeric" style="text-align: center;">
                                                                    {{ t('Number Of Employees (Job Seekers)') }}
                                                                </th>
                                                                <th data-sort-ignore="true">{{ t('Status') }}</th>
                                                                <th class="action" id="actionHeader">{{ t('Action') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($message_request as $key => $item)

                                                            <td class="title">{{ $item->title }}</td>
                                                            <td class="title">{{ $item->skill_set }}</td>
                                                            <td class="title" style="text-align: center;">
                                                                @if($item->status == 'approved')
                                                                <a
                                                                    href="{{url('account/messages/track_bulk_request')}}/{{$item->id}}">
                                                                    {{ $item->number_of_employee }} </a>
                                                                @else
                                                                {{ $item->number_of_employee }}
                                                                @endif
                                                            </td>
                                                            <td class="title">
                                                                @php $statusMap = ['pending' => ['Pending',
                                                                'btn-warning'],'approved' => ['Approved',
                                                                'btn-success'],'rejected' => ['Rejected',
                                                                'btn-danger']]; @endphp
                                                                <span
                                                                    class="badge {{ $statusMap[$item->status][1] }}">{{ $statusMap[$item->status][0] }}</span>
                                                            </td>
                                                            @if($item->status == 'pending')
                                                            <td class="title">
                                                                <div class="action row">
                                                                    <a href="{{ url('account/messages/delete_message_request/' . $item->id) }}"
                                                                        class="btn  btn-danger btn-sm view_button mx-1">{{ t('Remove') }}</a>
                                                                </div>
                                                            </td>
                                                            @endif
                                                            <script>$("#actionHeader").toggle("{{$item->status}}" === "pending");</script>                                                            
                                                            </tr>
                                                            @endforeach
                                                            @if (count($message_request) <= 0) <tr class="job-items">
                                                                <td colspan="5">
                                                                    <h6 class="text-muted" style="text-align: center">
                                                                        {{ t('not_request_found') }}
                                                                    </h6>
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
                    <div class="modal fade" id="message_request" tabindex="-1" role="dialog"
                        aria-labelledby="message_requestLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="message_requestLabel">
                                        {{ t('create_bulk_chat_request') }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form class="form-horizontal" method="POST"
                                        action="{{ url('account/messages/message_request_post') }}" id="bulk_request_form"
                                        enctype="multipart/form-data">
                                        {!! csrf_field() !!}
                                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}"
                                            class="form-control">
                                        <input type="hidden" name="package_id" value="{{ auth()->user()->package_id }}"
                                            class="form-control">
                                        <div class="form-group">
                                            <label for="recipient-name"
                                                class="col-form-label">{{ t('select_post') }}:</label>
                                            <select class="form-control" name="post_id" required>
                                                @if (!empty($posts))
                                                @foreach ($posts as $item)
                                                <option value="{{ $item->id }}">{{ $item->title }}
                                                </option>
                                                @endforeach
                                                @endif
                                            </select>
                                            <p id="requiredPost" class="error-message-class">Please Select the Post to
                                                Send Message.</p>
                                        </div>
                                        <div class="form-group">
                                            <label for="recipient-name"
                                                class="col-form-label">{{ t('select_skill_set') }}:</label>
                                            <select class="form-control" name="skill_set" id="skill_set">
                                                @if (!empty($employeeSkill))
                                                @foreach ($employeeSkill as $obj)
                                                <option value="{{ $obj->skill }}">{{ $obj->skill }}
                                                </option>
                                                @endforeach
                                                @endif
                                            </select>
                                            <p id="requiredSkill" class="error-message-class">Please Select Skill.</p>

                                        </div>

                                        {{--  --}}
                                        <div class="form-group">
                                            <label for="recipient-name"
                                                class="col-form-label">{{ t('Number Of Employees (Job Seekers)') }}:</label>
                                            <input type="number" id="number_of_employee" name="number_of_employee"
                                                class="form-control">
                                            <p id="requiredNumberOfEmployee" class="error-message-class">This filed is
                                                required.</p>
                                        </div>
                                        {{--  --}}
                                        <div class="form-group">
                                            <label for="recipient-name"
                                                class="col-form-label">{{ t('What Message Would You Like To Send As A Bulk Chat To Employees (Job Seekers)?') }}:</label>
                                            <textarea type="text" name="message" id="message" class="form-control"
                                                rows="10"></textarea>
                                            <p id="requiredMessage" class="error-message-class">This filed is required.
                                            </p>
                                        </div>
                                        {{--  --}}

                                        <div class="modal-footer">
                                            <button type="button" onclick="show_confirm_model()"
                                                class="btn btn-primary">Save</button>
                                        </div>

                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
function show_confirm_model() {
    var post_id = $('#post_id').val();
    var number_of_employee = $('#number_of_employee').val();
    var message = $('#message').val();
    var skill_set = $('#skill_set').val();
    if (post_id != '') {
        $("#requiredPost").hide();
    } else {
        $("#requiredMessage").show();
        return false;
    }
    if (number_of_employee != '') {
        $("#requiredNumberOfEmployee").hide();
    } else {
        $("#requiredNumberOfEmployee").show();
        return false;
    }
    if (message != '') {
        $("#requiredMessage").hide();
    } else {
        $("#requiredMessage").show();
        return false;
    }
    if (skill_set != '') {
        $("#requiredSkill").hide();
    } else {
        $("#requiredSkill").show();
        return false;
    }
    $('#message_request').modal('hide');

    var message =
        'Your bulk chat request has been sent to the Hungry For Jobs team.<br><br>Once approved, your message will be automatically sent to the number of employees (job seekers) you`ve chosen and would have also unlocked their Contact Cards.<br><br>If the message is not approved, the Hungry For Jobs team will get in contact with you soon.';
    const config = {
        html: true,
        title: 'Attention',
        html: message,
        type: "info",
        customClass: 'swal-wide',
        confirmButtonText: 'I Agree',
        showCancelButton: true,
    };
    Swal.fire(config).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            document.getElementById('bulk_request_form').submit();
        } else if (result.isDenied) {
            return false;
        }
    });
}
</script>