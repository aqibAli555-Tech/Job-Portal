@extends('layouts.master')

@section('content')
@include('common.spacer')
<!-- Breadcrumb -->
<style>
    .show {
        display: block;
    }

    .alert-success {
        position: absolute !important;
        width: 24%;
        right: 7% !important;
        top: 10px !important;
    }
</style>
<div class="alice-bg padding-top-70 padding-bottom-70">
    <div class="col-xl-12">
        <div class="row">
            <div class="col-xl-12">
                <div class="alert alert-success" id="alert-success" style="text-align: center;display: none"
                     role="alert">
                    Resume successfully sent
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="breadcrumb-area">
                    <h1>{{ t('Saved searches') }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('/account')}}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Search Resume</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-md-6">
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->
<div class="alice-bg section-padding-bottom">
    <div class="container no-gliters">
        <div class="row no-gliters">
            <div class="col">

                <div class="dashboard-container">
                    <div class="dashboard-content-wrapper">
                        <div class="search-row-wrapper"
                             style="padding: 20px 10px 40px 10px; background: #EEEEEE; border-radius: 7px;">
                            <div style="width: 100%;">
                                <a style="text-align: right;cursor: pointer;width: 100%;position: relative;top: -10px;"
                                   id="cliced">Filter &nbsp;<i
                                            class="fa fa-align-center" aria-hidden="true"></i>
                                </a>
                                <div class="dropdown" style="float: right;position: relative;left: 0px;width: 222px;">
                                    <ul class="dropdown-menu" role="menu" style="padding: 10px;width: 100%"
                                        id="Filter"
                                        aria-labelledby="menu1">
                                        <form name="filter_form"
                                              action="{{url('/')}}/account/search_resumes" method="post">
                                            <div class="more-option terms">
                                                {{-- Keyword --}}
                                                <div class="mt-0 terms" style="margin: 7px;">
                                                    <input class="custom-radio"
                                                           type="checkbox"
                                                           name="k"
                                                           id="Keyword">
                                                    <label for="Keyword" style="width: 100%;font-size: 13px;">
                                                        <span class="dot"></span>By keyword
                                                    </label>
                                                </div>

                                                {{-- Skill --}}
                                                <div class="mt-0 terms" style="margin: 7px;">
                                                    <input class="custom-radio"
                                                           type="checkbox"
                                                           name="s"
                                                           id="Skill">
                                                    <label for="Skill" style="width: 100%;font-size: 13px;">
                                                        <span class="dot"></span>By skill
                                                    </label>
                                                </div>

                                                {{-- Email --}}
                                                <div class="mt-0 terms" style="margin: 7px;">
                                                    <input class="custom-radio"
                                                           type="checkbox"
                                                           name="e"
                                                           id="Email">
                                                    <label for="Email" style="width: 100%;font-size: 13px;">
                                                        <span class="dot"></span>By email
                                                    </label>
                                                </div>

                                                {{-- Phone --}}
                                                <div class="mt-0 terms" style="margin: 7px;">
                                                    <input class="custom-radio"
                                                           type="checkbox"
                                                           name="p"
                                                           id="Phone">
                                                    <label for="Phone" style="width: 100%;font-size: 13px;">
                                                        <span class="dot"></span>By phone
                                                    </label>
                                                </div>

                                                {{-- City --}}
                                                <div class="mt-0 terms" style="margin: 7px;">
                                                    <input class="custom-radio"
                                                           type="checkbox"
                                                           name="c"
                                                           id="City">
                                                    <label for="City" style="width: 100%;font-size: 13px;">
                                                        <span class="dot"></span>By city
                                                    </label>
                                                </div>
                                                {{-- Tags --}}
                                                <div class="mt-0 terms" style="margin: 7px;">
                                                    <input class="custom-radio"
                                                           type="checkbox"
                                                           name="t"
                                                           id="Tags">
                                                    <label for="Tags" style="width: 100%;font-size: 13px;">
                                                        <span class="dot"></span>By tags
                                                    </label>
                                                </div>

                                                <input type="hidden" name="Fkey" id="Fkey" value="">
                                                <input type="hidden" name="Fcat" id="Fcat" value="">
                                            </div>
                                            <br>
                                            <button class="mybtn" name="filter" onclick="filterMe()"
                                                    style="width: 100% !important;font-size: 14px;">
                                                <i class="fa fa-search" style="margin-right: 5px;"></i>Filter
                                            </button>
                                        </form>
                                    </ul>
                                </div>

                            </div>
                            <div class="container">
                                <form class="dashboard-form" name="search"
                                      action="{{url('/')}}/account/search_resumes" method="post">
                                    <input name="_method" type="hidden" value="POST">
                                    <div class="form-group row">
                                        <div class="col-sm-5">
                                            <select required class="form-control" name="cat" id="Scat"
                                                    style="background: transparent !important;">
                                                @foreach ($data['cat'] as $item)
                                                <option value="{{$item->name}}">{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-5">
                                            <input required name="keyword" class="form-control" type="text"
                                                   placeholder="Keyword, title, skills, email, phone, etc..."
                                                   style="background: transparent !important;"
                                                   id="Skey"
                                                   data-original-title="">
                                        </div>
                                        <div class="col-sm-2">
                                            <button class="mybtn" name="send" style="width: 100% !important;">
                                                <i class="fa fa-search"></i> <strong>{{ t('Find') }}</strong>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>


                        @if($data['found'] == 1)
                        <br><br>
                        <hr>
                        <div class="manage-job-container">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Field of interest</th>
                                    <th>Phone</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach ($data['result'] as $key => $item):
                                    ?>
                                    <tr class="job-items">
                                        <td>{{$item->name}}</td>
                                        <td>{{$item->email}}</td>
                                        <td>{{$item->interest}}</td>
                                        <td>{{$item->phone}}</td>
                                        <td>
                                            <script>
                                                function sendData(valueId, views) {
                                                    var URL = '<?= lurl('account/save_resume_add')?>';
                                                    $.ajax({
                                                        type: "POST",
                                                        url: URL,
                                                        data: {id: valueId, page: 'Search Page'},
                                                        success: function (result) {
                                                        }
                                                    });
                                                    var u = '<?= lurl('account/resumes/resumeViews')?>';
                                                    $.ajax({
                                                        type: "POST",
                                                        url: u,
                                                        data: {id: valueId, views: views}
                                                    });
                                                }
                                            </script>
                                            <?php
                                            if ($item->videoLink == '0') {
                                                ?>
                                                <a style="margin: 5px;" href="#sendByMessage" data-toggle="modal"
                                                   data-target="#sendByMessage"
                                                   data-toggle="tooltip" data-placement="top"
                                                   title="Send Message"
                                                   class="download"><i data-feather="message-square"
                                                                       style="width: 16px;position: relative;top: -2px;"></i>
                                                </a>
                                                <a style="margin: 5px;"
                                                   onclick="sendMail('<?= fileUrl($item->filename) ?>')"
                                                   href="#sendByEmail" data-toggle="modal"
                                                   data-target="#sendByEmail"
                                                   class="download"><i data-feather="share-2"
                                                                       style="width: 16px;position: relative;top: -2px;"></i>
                                                </a>
                                            <?php } else {
                                                ?>

                                                <a style="margin: 5px;" href="#sendByMessage" data-toggle="modal"
                                                   data-target="#sendByMessage"
                                                   data-toggle="tooltip" data-placement="top"
                                                   title="Send Message"
                                                   class="download"><i data-feather="message-square"
                                                                       style="width: 16px;position: relative;top: -2px;"></i>
                                                </a>
                                                <a style="margin: 5px;"
                                                   onclick="sendMail('<?= $item->videoLink ?>')"
                                                   href="#sendByEmail" data-toggle="modal"
                                                   data-target="#sendByEmail"
                                                   class="download"><i data-feather="share-2"
                                                                       style="width: 16px;position: relative;top: -2px;"></i>
                                                </a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        @else
                        <br><br>
                        <hr>
                        <div class="manage-job-container">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Name</th>
                                    <th>View</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach ($data['resume'] as $key => $item):
                                    ?>
                                    <tr class="job-items">
                                        <td>{{$item->name}}</td>
                                        <td>{{$item->fullName}}</td>
                                        <td>{{$item->views}}</td>
                                        <td>
                                            <script>
                                                function sendData(valueId, views) {
                                                    var URL = '<?= lurl('account/save_resume_add')?>';
                                                    $.ajax({
                                                        type: "POST",
                                                        url: URL,
                                                        data: {id: valueId, page: 'Search Page'},
                                                        success: function (result) {
                                                        }
                                                    });
                                                    var u = '<?= lurl('account/resumes/resumeViews')?>';
                                                    $.ajax({
                                                        type: "POST",
                                                        url: u,
                                                        data: {id: valueId, views: views}
                                                    });
                                                }
                                            </script>
                                            <?php
                                            if ($item->videoLink == '0') {
                                                ?>
                                                <a style="margin: 5px;"
                                                   onclick="sendData('<?= $item->id ?>' , '<?= $item->views ?>')"
                                                   href="{{ fileUrl($item->filename) }}"
                                                   target="_blank"
                                                   class="download"><i
                                                            data-feather="download"
                                                            style="width: 16px;position: relative;top: -2px;"></i>
                                                </a>
                                                <a style="margin: 5px;" href="#sendByMessage" data-toggle="modal"
                                                   data-target="#sendByMessage"
                                                   data-toggle="tooltip" data-placement="top"
                                                   title="Send Message"
                                                   class="download"><i data-feather="message-square"
                                                                       style="width: 16px;position: relative;top: -2px;"></i>
                                                </a>
                                                <a style="margin: 5px;"
                                                   onclick="sendMail('<?= fileUrl($item->filename) ?>')"
                                                   href="#sendByEmail" data-toggle="modal"
                                                   data-target="#sendByEmail"
                                                   class="download"><i data-feather="share-2"
                                                                       style="width: 16px;position: relative;top: -2px;"></i>
                                                </a>
                                            <?php } else {
                                                ?>

                                                <a style="margin: 5px;"
                                                   onclick="sendData('<?= $item->id ?>' , '<?= $item->views ?>')"
                                                   href="{{ $item->videoLink }}"
                                                   target="_blank"
                                                   class="download">
                                                    <i data-feather="download"
                                                       style="width: 16px;position: relative;top: -2px;"></i>
                                                </a>
                                                <a style="margin: 5px;" href="#sendByMessage" data-toggle="modal"
                                                   data-target="#sendByMessage"
                                                   data-toggle="tooltip" data-placement="top"
                                                   title="Send Message"
                                                   class="download"><i data-feather="message-square"
                                                                       style="width: 16px;position: relative;top: -2px;"></i>
                                                </a>
                                                <a style="margin: 5px;"
                                                   onclick="sendMail('<?= $item->videoLink ?>')"
                                                   href="#sendByEmail" data-toggle="modal"
                                                   data-target="#sendByEmail"
                                                   class="download"><i data-feather="share-2"
                                                                       style="width: 16px;position: relative;top: -2px;"></i>
                                                </a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        @endif
                    </div>
                    @include('account.inc.sidebar')
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade modal-delete" id="sendByEmail" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" id="dismiss" class="close myClose" data-dismiss="modal">
                    &times;
                </button>
                <h4><i data-feather="upload"></i>{{ t('Send by Email') }}</h4>
                <p></p>
                <form name="form" id="form" method="POST" role="form">
                    <input name="_method" type="hidden" value="POST">
                    <input name="pdf" id="pdf_url" type="hidden" value="">
                    <div class="form-group required">
                        <label for="recipient_email" class="control-label">{{ t('Recipient Email') }}
                            <sup>*</sup></label>
                        <input id="recipient_email" name="recipient_email" type="text" maxlength="60"
                               class="form-control" value="">
                    </div>
                    <div class="form-group required">
                        <label for="subjuct" class="control-label">Subjuct
                            <sup>*</sup></label>
                        <input id="subjuct" name="subjuct" type="text" maxlength="60"
                               class="form-control">
                    </div>
                    <div class="form-group required">
                        <label for="subjuct" class="control-label">Body
                            <sup>*</sup></label>
                        <textarea class="form-control" id="body" name="body" style="height: 230px;"></textarea>
                    </div>
                    <div class="buttons">
                        <button id="sendMailsend" name="sendMailsend">{{ t('Send') }}</button>
                        <button type="reset" class="delete-button" data-dismiss="modal">
                            {{ t('Cancel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade modal-delete" id="sendByMessage" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" id="dismiss" class="close myClose" data-dismiss="modal">
                    &times;
                </button>
                <h4><i data-feather="message-square"></i>Send Message</h4>
                <p></p>
                <form id="SMSForm">
                    <input name="_method" type="hidden" value="POST">
                    <input name="pdf" id="pdf_url" type="hidden" value="">
                    <div class="form-group required">
                        <label for="subjuct" class="control-label">Body
                            <sup>*</sup></label>
                        <textarea class="form-control" id="body" name="body" style="height: 130px;"></textarea>
                    </div>
                    <div class="buttons">
                        <button id="sendMailsend" onclick="SMSsubmit()" name="sendMailsend">{{ t('Send') }}</button>
                        <button type="reset" class="delete-button" data-dismiss="modal">
                            {{ t('Cancel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    function applicantCall(id) {
        document.getElementById('applicant_id').value = id;
    }

    $('#SMSForm').submit(function (e) {
        e.preventDefault();
        // alert('Under Development');

    });
    $('#form').submit(function (e) {
        e.preventDefault();
        var AjaxURL = '<?= lurl('account/resumes/sendResumeByEmail')?>';
        $.ajax({
            type: "POST",
            url: AjaxURL,
            data: $('#form').serialize(),
            beforeSend: function () {
                $("#dismiss").click();
            },
            success: function (result) {
                document.getElementById("alert-success").style.display = "block";
            }
        });
    });

    function sendMail(url) {
        document.getElementById('pdf_url').value = url;
    }
</script>
<script>
    var check1 = '0';
    $(document).ready(function () {
        $('#cliced').click(function () {
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
</script>
@endsection

@section('after_scripts')
<script src="{{url('new_assets/js/custom.js')}}"></script>
<script src="{{url('new_assets/dashboard/js/dashboard.js')}}"></script>
<script src="{{url('new_assets/dashboard/js/upload-input.js')}}"></script>
@endsection
