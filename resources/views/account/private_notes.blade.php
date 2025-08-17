{{--
* JobClass - Job Board Web Application
* Copyright (c) BedigitCom. All Rights Reserved
*
* Website: http://www.bedigit.com
*
* LICENSE
* -------
* This software is furnished under a license and may be used and copied
* only in accordance with the terms of such license and with the inclusion
* of the above copyright notice. If you Purchased from Codecanyon,
* Please read the full License from here - http://codecanyon.net/licenses/standard
--}}
@extends('layouts.master')

@section('content')
@include('common.spacer')
<!-- Breadcrumb -->
<div class="alice-bg padding-top-70 padding-bottom-70">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="breadcrumb-area">
                    <h1>Private Notes</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('/account')}}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Private Notes</li>
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
                        <div class="dashboard-section basic-info-input">
                            <h4><i data-feather="calendar"></i>Private Notes</h4>
                        </div>
                        <div class="col-lg-12">
                            <div class="tab-content faq-tab-content" id="myTabContent">
                                <div class="tab-pane fade active show"
                                     id="feature" role="tabpanel"
                                     aria-labelledby="feature-tab">
                                    <div class="accordion"
                                         id="accordionExample">
                                        <?php
                                        foreach ($data as $key => $item) {
                                            ?>
                                            <div class="card">
                                                <div class="card-header"
                                                     id="heading{{$item->id}}">
                                                    <h5 class="mb-0">

                                                        <button class="btn btn-link collapsed"
                                                                type="button"
                                                                data-toggle="collapse"
                                                                data-target="#collapse{{$item->id}}"
                                                                aria-expanded="false"
                                                                aria-controls="collapse{{$item->id}}">
                                                            {{$item->applicant['name']}} <span
                                                                    style="font-size: 14px;"
                                                                    class="text-muted"> ( {{$item['title']}} ) </span>
                                                        </button>
                                                    </h5>
                                                </div>
                                                <div id="collapse{{$item->id}}"
                                                     class="collapse"
                                                     aria-labelledby="heading{{$item->id}}"
                                                     data-parent="#accordionExample">
                                                    <div class="card-body">
                                                        <p><b>Note :</b>
                                                            {{$item->note}}
                                                            <br>
                                                            <i style="width: 14px;margin: 0px 5px;color: #61f1f9;" data-feather="clock"></i> Created at : {{$item->created_at->format('Y-m-d')}}
                                                            <a data-toggle="modal" data-target="#modal-note"
                                                               onclick="applicantCall('<?= $item->applicant['id'] ?>')"
                                                               href="#">
                                                                <i data-toggle="tooltip"
                                                                   data-placement="top"
                                                                   title="Create private note"
                                                                   style="width: 14px;margin: 0px 5px;color: #695f8c;"
                                                                   data-feather="calendar"></i>
                                                            </a>
                                                            <a href="{{url('/account/Private-Notes/delete/').'/'.$item->id}}">
                                                                <i data-toggle="tooltip"
                                                                   data-placement="top"
                                                                   title="Clear notes of applicant"
                                                                   style="width: 14px;margin: 0px 5px;color: #ff3366;"
                                                                   data-feather="trash-2"></i>
                                                            </a>
                                                        </p>
                                                    </div>
                                                </div>
                                                <?php
                                                foreach ($item['sub_private_note'] as $key => $sub_item) {
                                                    ?>
                                                    <div id="collapse{{$item->id}}"
                                                         class="collapse"
                                                         aria-labelledby="heading{{$item->id}}"
                                                         data-parent="#accordionExample">
                                                        <div class="card-body">
                                                            <p><b>{{$sub_item->title}} :</b>
                                                                {{$sub_item->note}}
                                                                <br>
                                                                <i style="width: 14px;margin: 0px 5px;color: #61f1f9;" data-feather="clock"></i>Created at : {{$sub_item->created_at->format('Y-m-d')}}
                                                                <a data-toggle="modal" data-target="#modal-note"
                                                                   onclick="applicantCall('<?= $item->applicant['id'] ?>')"
                                                                   href="#">
                                                                    <i data-toggle="tooltip"
                                                                       data-placement="top"
                                                                       title="Create private note"
                                                                       style="width: 14px;margin: 0px 5px;color: #695f8c;"
                                                                       data-feather="calendar"></i>
                                                                </a>
                                                                <a href="{{url('/account/Private-Notes/s_delete/').'/'.$sub_item->id}}">
                                                                    <i data-toggle="tooltip"
                                                                       data-placement="top"
                                                                       title="Clear this note"
                                                                       style="width: 14px;margin: 0px 5px;color: #ff3366;"
                                                                       data-feather="trash-2"></i>
                                                                </a>
                                                            </p>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- How It Work End -->
                        @if(count($data) <= 0)
                        <h6 class="text-muted" style="text-align: center">You have not any private notes yet
                            !</h6>
                        @endif
                    </div>
                    @include('account.inc.sidebar')
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function applicantCall(id) {
        document.getElementById('applicant_id').value = id;
    }
</script>
<div class="modal fade modal-delete" id="modal-note" tabindex="1" role="dialog"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close myClose" data-dismiss="modal">
                    &times;
                </button>
                <h4><i data-feather="calendar"></i>Private Note</h4>
                <p></p>
                <style>
                    .form-control:after {
                        outline: none !important;
                    }
                </style>
                <form method="post" action="{{url('/account/Private-Note-Create')}}">
                    <div class="form-group">
                        <input type="text" placeholder="Title or subject" class="form-control" style="margin-bottom:20px;outline: none !important;
                        border: none; border-bottom: 1px solid #61f1f9;font-size: 14px;
                        height: 54px;" name="title">
                    </div>
                    <input type="hidden" id="applicant_id" name="applicant_id" value="" hidden>
                    <div class="form-group">

                        <textarea name="note" style="margin-bottom:20px;outline: none !important;
                        border: none; border-bottom: 1px solid #61f1f9;height: 270px !important;font-size: 14px;"
                                  class="form-control"
                                  placeholder="Private Notes are not visible to public, and are added like the messages."></textarea>
                    </div>
                    <div class="buttons">
                        <button type="submit" name="noteSent">Create</button>
                        <button type="reset" class="delete-button" data-dismiss="modal">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection
