@extends('layouts.master')

@section('content')
@include('common.spacer')

<style>
  
</style>
<div class="main-container">
    <div class="container">
        <div class="row">

            <div class="col-md-3 page-sidebar">
                @include('account.inc.sidebar')
            </div>
            <!--/.page-sidebar-->


            <!-- custome css code here -->
            <div class="col-md-9 page-content">
                <div class="alice-bg section-padding-bottom">
                    <div class="container no-gliters">
                        <div class="row no-gliters">
                            <div class="col">
                                <div class="dashboard-container">
                                    <div class="dashboard-content-wrapper">
                                        @include('flash::message')
                                        <div class="manage-job-container">
                                            <div style="text-align: right">
                                                <!-- <button class="mybtn" onclick="toggle('Applied')" style="width: auto">Applied {{$applied}}
                                                        </button>
                                                        <button class="mybtn" onclick="toggle('Interviews')" style="width: auto">
                                                            Interviews {{$interview}}
                                                        </button>
                                                        <button class="mybtn" onclick="toggle('Haired')" style="width: auto">Hired {{$haired}}</button>
                                                <button class="mybtn" onclick="toggle('Rejected')" style="width: auto">Rejected {{$rejected}}
                                                        </button> -->

                                                <!-- Pills Apply Here -->
                                                <div class="row display-inblock">
                                                    <ul class="nav nav-pills mb-3 m-3" id="pills-tab" role="tablist"
                                                        style="text-align: right">
                                                        <button type="button" onclick="toggle('Applied')"
                                                                class="btn btn-warning activeBtnClass">
                                                            Applied <span
                                                                    class="badge badge-light">{{$applied}}</span>
                                                        </button>
                                                    </ul>

                                                    <ul class="nav nav-pills mb-3 m-3" id="pills-tab" role="tablist"
                                                        style="text-align: right">
                                                        <button type="button" onclick="toggle('Interviews')"
                                                                class="btn btn-dark activeBtnClass">
                                                            Interview <span
                                                                    class="badge badge-light">{{$interview}}</span>
                                                        </button>
                                                    </ul>

                                                    <ul class="nav nav-pills mb-3 m-3" id="pills-tab" role="tablist"
                                                        style="text-align: right">
                                                        <button type="button" onclick="toggle('Haired')"
                                                                class="btn btn-success activeBtnClass">
                                                            Hired <span class="badge badge-light">{{$haired}}</span>
                                                        </button>
                                                    </ul>

                                                    <ul class="nav nav-pills mb-3 m-3" id="pills-tab" role="tablist"
                                                        style="text-align: right">
                                                        <button type="button" onclick="toggle('Rejected')"
                                                                class="btn btn-danger activeBtnClass">
                                                            Rejected <span
                                                                    class="badge badge-light">{{$rejected}}</span>
                                                        </button>
                                                    </ul>
                                                </div>

                                            </div>
                                            <table class="table">
                                                {!! csrf_field() !!}
                                                <thead>
                                                <tr>
                                                    <th data-type="numeric" data-sort-initial="true">Title
                                                    </th>
                                                    <th data-type="numeric" data-sort-initial="true">Image
                                                    </th>
                                                    <th data-sort-ignore="true">Name</th>
                                                    <th data-type="numeric">Date</th>
                                                    <th class="action">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody id="Applied">
                                                <?php

                                                use App\Models\Unlock;

                                                $appliedCounter = 0;
                                                foreach ($data as $key => $item) {
                                                    if ($item->status == 'applied') {
                                                        $appliedCounter++;
                                                        ?>
                                                        <tr class="job-items">
                                                            <td class="title">{{$item->post['title']}}</td>
                                                            <?php
                                                            if (empty($item->user->file)) {
                                                                $logo_show = 'app/default/picture.jpg';
                                                            } else {
                                                                if (file_exists(public_path('/') . 'storage/' . $item->user->file)) {
                                                                    $logo_show = $item->user->file;
                                                                } else {
                                                                    $logo_show = 'app/default/picture.jpg';
                                                                }
                                                            }
                                                            ?>
                                                            <td>
                                                                <img style="width: 60px"
                                                                     src="{{url('/public/storage/')}}/{{$logo_show}}"
                                                                     alt="...">
                                                            </td>
                                                            <td class="title">{{$item->name}}</td>
                                                            <td class="title">{{ $item->created_at->format('d-M-Y') }}</td>
                                                            <td class="title">
                                                                <div class="action">
                                                                    <?php
                                                                    $temp = Unlock::where('user_id', $item->user->id)->where('to_user_id', auth()->user()->id)->first();
                                                                    if ($temp == null) {
                                                                        $isUnlock = 0;
                                                                    } else {
                                                                        $isUnlock = 1;
                                                                    }
                                                                    ?>
                                                                    @if($isUnlock===1)
                                                                    <a data-toggle="modal" data-target="#modal-view{{$key}}"
                                                                       href="#" class="mybtn"
                                                                       style="width: auto;text-align: center;color: #fff;">
                                                                        <i data-feather="eye"></i>
                                                                        View Details</a>
                                                                    @else
                                                                    <a data-toggle="modal"
                                                                       data-target="#modal-unlockk{{$key}}"
                                                                       href="#" class="mybtn"
                                                                       style="width: auto;text-align: center;color: #fff;">
                                                                        <i data-feather="eye"></i>
                                                                        View Details</a>
                                                                    @endif

                                                                </div>
                                                            </td>
                                                        </tr>

                                                        <div class="modal fade modal-delete" id="modal-unlockk{{$key}}" style="z-index: 111111111;" tabindex="1" role="dialog" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-body">
                                                                        <button type="button" class="close myClose" data-dismiss="modal">
                                                                            &times;
                                                                        </button>
                                                                        <h4><i data-feather="calendar"></i>Confirmation</h4>
                                                                        <p>Are you sure you would like to view contact card?</p>
                                                                        <small class="text-warning"><b>Note :</b> It will take 1 credit from you account.</small>
                                                                        <br><br>
                                                                        <div style="text-align: right">
                                                                            <a href="{{ url('/UnlockProfile/') }}/{{$item->user->id}}" class="btn btn-danger" style="padding: 12px;font-size: 13px;margin-right: 10px">
                                                                                Yes
                                                                            </a>
                                                                            <a href="#" class="mybtn" data-dismiss="modal" style="width: auto;text-align: center;color: #fff;">
                                                                                No
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="modal fade modal-delete" id="modal-view{{$key}}"
                                                             tabindex="1" role="dialog" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-body">
                                                                        <button type="button" class="close myClose"
                                                                                data-dismiss="modal">
                                                                            &times;
                                                                        </button>
                                                                        <h4>
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                 width="24" height="24" viewBox="0 0 24 24"
                                                                                 fill="none" stroke="currentColor"
                                                                                 stroke-width="2" stroke-linecap="round"
                                                                                 stroke-linejoin="round"
                                                                                 class="feather feather-calendar"
                                                                                 data-toggle="tooltip" data-placement="top"
                                                                                 title="Create private note"
                                                                                 style="color: #695f8c;width: 14px;">
                                                                                <rect x="3" y="4" width="18" height="18"
                                                                                      rx="2" ry="2"></rect>
                                                                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                                                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                                                                <line x1="3" y1="10" x2="21" y2="10"></line>
                                                                            </svg>
                                                                            View Details
                                                                        </h4>
                                                                        <div style="width: 100%;text-align: center;">
                                                                            <img src="{{url('/public/storage')}}/{{$item->user->file}}"
                                                                                 style="width: 30%; display: block;margin: auto;">
                                                                            <a href="{{url('/profile')}}/{{$item->user->id}}"
                                                                               style="width: 13px;text-align: center;color: #fff;background: #61f1f9;
                                                                            font-size: 14px;border-radius: 8px;padding: 0px 5px;">
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                     width="24" height="24"
                                                                                     viewBox="0 0 24 24" fill="none"
                                                                                     stroke="currentColor" stroke-width="2"
                                                                                     stroke-linecap="round"
                                                                                     stroke-linejoin="round"
                                                                                     class="feather feather-eye"
                                                                                     style="width: 13px;">
                                                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                                                    <circle cx="12" cy="12" r="3"></circle>
                                                                                </svg>
                                                                                View Profile</a>
                                                                        </div>
                                                                        <br>
                                                                        <div>
                                                                            <small><b>Name: </b> {{$item->name}} </small>
                                                                            <br>
                                                                            <small>
                                                                                @foreach ($item->user->user_skill_experience as $key => $a)
                                                                                <b>{{$a->skill->name}}</b>
                                                                                {{$a->experience->name}} Experience
                                                                                <br>
                                                                                @endforeach
                                                                            </small>
                                                                            <hr>
                                                                            <small><b>Title: </b> {{$item->post['title']}}
                                                                            </small>
                                                                            <br>
                                                                            <small><b>Applied
                                                                                    at: </b> {{ $item->created_at }}
                                                                            </small>
                                                                            <hr>
                                                                        </div>

                                                                        @if($item->unlocked == 0)
                                                                        <div style="text-align: center">

                                                                            <!-- Start Items for icon and text of Applied User when user is locked-->
                                                                            <!-- Remember, display only 4 points when user is locked, -->

                                                                            <span style="margin: 0px 10px">
                                                                                    <!-- <a href="#" onclick="unlockMeBtn('{{$item->id}}')"
                                                                 class="index">unlock<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download" style="width: 14px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                                             </a> -->

                                                                                        <a href="#"
                                                                                           onclick="unlockMeBtn('{{$item->id}}')"
                                                                                           class="index">
                                                                                        <img src="https://img.icons8.com/external-becris-lineal-color-becris/64/000000/external-interview-business-situations-becris-lineal-color-becris.png"
                                                                                             style="height:25px;width:30px"> Interview
                                                                                    </a>
                                                                                    <a href="#"
                                                                                       onclick="unlockMeBtn('{{$item->id}}')"
                                                                                       class="index">
                                                                                        <img src="https://img.icons8.com/ios-filled/50/000000/teacher-hirring.png"
                                                                                             style="height:25px;width:30px"> Hired

                                                                                    </a>
                                                                                </span>

                                                                            <span style="margin: 0px 10px">
                                                                                    <a data-toggle="tooltip"
                                                                                       data-placement="top"
                                                                                       title="You are Rejected"
                                                                                       href="{{ lurl('account/Applicants/rejected/'.$item->id .'') }}"
                                                                                       class="remove">
                                                                                        <img src="https://img.icons8.com/fluency/48/000000/unfriend-female.png"
                                                                                             style="height:25px;width:30px"> Rejected
                                                                                    </a> &nbsp;&nbsp;&nbsp;

                                                                                    <a data-toggle="modal"
                                                                                       data-target="#modal-note"
                                                                                       onclick="applicantCall('<?= $item->id ?>')"
                                                                                       href="#" class="index">
                                                                                        <img src="https://img.icons8.com/ios-filled/48/000000/private-lock.png"
                                                                                             style="height:25px;width:30px"> Private Notes
                                                                                    </a>
                                                                                </span>
                                                                        </div>

                                                                        <!-- code start for Applied User When user isunloacked -->
                                                                        @else
                                                                        <div style="text-align: center">
                                                                                <span style="margin: 0px 10px">
                                                                                    <a data-toggle="tooltip"
                                                                                       data-placement="top"
                                                                                       title="Download the resume"
                                                                                       onclick="downloadCall('<?= $item->id ?>')"
                                                                                       href="{{url('/public/storage/')}}/{{$item->file}}"
                                                                                       target="_blank" class="index">Download
                                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                                             width="24" height="34"
                                                                                             viewBox="0 0 24 24"
                                                                                             fill="none"
                                                                                             stroke="currentColor"
                                                                                             stroke-width="2"
                                                                                             stroke-linecap="round"
                                                                                             stroke-linejoin="round"
                                                                                             class="feather feather-download"
                                                                                             style="width: 14px;">
                                                                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                                                            <polyline
                                                                                                    points="7 10 12 15 17 10"></polyline>
                                                                                            <line x1="12" y1="15"
                                                                                                  x2="12" y2="3"></line>
                                                                                        </svg>
                                                                                    </a>
                                                                                    <a data-toggle="tooltip"
                                                                                       data-placement="top"
                                                                                       title="Send Interview Request"
                                                                                       href="{{ lurl('account/Applicants/interview/'.$item->id) }}"
                                                                                       class="index">Interview
                                                                                        <img src="https://img.icons8.com/external-becris-lineal-color-becris/64/000000/external-interview-business-situations-becris-lineal-color-becris.png"
                                                                                             style="height:25px;width:30px">  &nbsp;&nbsp;
                                                                                    </a>

                                                                                    <a data-toggle="tooltip"
                                                                                       data-placement="top"
                                                                                       id="disabled"
                                                                                       title="You are hired"
                                                                                       href="{{ lurl('account/Applicants/haired/'.$item->id .'') }}"
                                                                                       class="index">Hired
                                                                                        <img src="https://img.icons8.com/ios-filled/50/000000/teacher-hirring.png"
                                                                                             style="height:25px;width:30px">  &nbsp;&nbsp;
                                                                                    </a>
                                                                                </span>
                                                                            <span style="margin: 0px 10px">
                                                                                    <a data-toggle="tooltip"
                                                                                       data-placement="top"
                                                                                       title="You are Rejected"
                                                                                       href="{{ lurl('account/Applicants/rejected/'.$item->id .'') }}"
                                                                                       class="remove">Rejected
                                                                                        <img src="https://img.icons8.com/fluency/48/000000/unfriend-female.png"
                                                                                             style="height:25px;width:30px">
                                                                                    </a>
                                                                                    <a data-toggle="modal"
                                                                                       data-target="#modal-note"
                                                                                       onclick="applicantCall('<?= $item->id ?>')"
                                                                                       href="#" class="index">Private Note
                                                                                        <img src="https://img.icons8.com/ios-filled/48/000000/private-lock.png"
                                                                                             style="height:25px;width:30px">
                                                                                    </a>
                                                                                </span>
                                                                        </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php }
                                                } ?>
                                                <!-- End Code Items for icon and text for Applied User -->


                                                <!-- Start code for Module Interview List -->
                                                @if($appliedCounter == 0)
                                                <tr class="job-items">
                                                    <td colspan=7>
                                                        <h6 class="text-muted" style="text-align: center">You
                                                            don't have any applicants yet!</h6>
                                                    </td>
                                                </tr>
                                                @endif
                                                </tbody>
                                                <tbody id="Interviews" style="display:none ;">
                                                <?php
                                                $appliedCounter = 0;
                                                foreach ($data as $key => $item) {
                                                    if ($item->status == 'interview') {
                                                        $appliedCounter++;

                                                        ?>
                                                        <tr class="job-items">
                                                            <td class="title">{{$item->post['title']}}</td>
                                                            <td>
                                                                <img style="width: 60px"
                                                                     src="{{url('/public/storage/')}}/{{$item->user->file}}"
                                                                     alt="...">
                                                            </td>
                                                            <td class="title">{{$item->name}}</td>
                                                            <td class="title">{{ $item->created_at->format('d-M-Y') }}</td>
                                                            <td class="title">

                                                                <div class="action">
                                                                    <a data-toggle="modal" data-target="#modal-view{{$key}}"
                                                                       href="#" class="mybtn"
                                                                       style="width: auto;text-align: center;color: #fff;">
                                                                        <i data-feather="eye"></i>
                                                                        View Details</a>

                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <div class="modal fade modal-delete" id="modal-view{{$key}}"
                                                             tabindex="1" role="dialog" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-body">
                                                                        <button type="button" class="close myClose"
                                                                                data-dismiss="modal">
                                                                            &times;
                                                                        </button>


                                                                        <h4>
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                 width="24" height="24" viewBox="0 0 24 24"
                                                                                 fill="none" stroke="currentColor"
                                                                                 stroke-width="2" stroke-linecap="round"
                                                                                 stroke-linejoin="round"
                                                                                 class="feather feather-calendar"
                                                                                 data-toggle="tooltip" data-placement="top"
                                                                                 title="Create private note"
                                                                                 style="color: #695f8c;width: 14px;">
                                                                                <rect x="3" y="4" width="18" height="18"
                                                                                      rx="2" ry="2"></rect>
                                                                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                                                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                                                                <line x1="3" y1="10" x2="21" y2="10"></line>
                                                                            </svg>
                                                                            View Details
                                                                        </h4>
                                                                        <div style="width: 100%;text-align: center;">
                                                                            <img src="{{url('/public/storage')}}/{{$item->user->file}}"
                                                                                 style="width: 30%; display: block;margin: auto;">
                                                                            <a href="{{url('/profile')}}/{{$item->user->id}}"
                                                                               style="width: 13px;text-align: center;color: #fff;background: #61f1f9;
                         font-size: 14px;border-radius: 8px;padding: 0px 5px;">
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                     width="24" height="24"
                                                                                     viewBox="0 0 24 24" fill="none"
                                                                                     stroke="currentColor" stroke-width="2"
                                                                                     stroke-linecap="round"
                                                                                     stroke-linejoin="round"
                                                                                     class="feather feather-eye"
                                                                                     style="width: 13px;">
                                                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                                                    <circle cx="12" cy="12" r="3"></circle>
                                                                                </svg>
                                                                                View Profile</a>
                                                                        </div>
                                                                        <br>
                                                                        <div>
                                                                            <small><b>Name: </b> {{$item->name}} </small>
                                                                            <br>
                                                                            <small>
                                                                                @foreach ($item->user->user_skill_experience as $key => $a)
                                                                                <b>{{$a->skill->name}}</b>
                                                                                {{$a->experience->name}} Experience
                                                                                <br>
                                                                                @endforeach
                                                                            </small>
                                                                            <hr>
                                                                            <small><b>Title: </b> {{$item->post['title']}}
                                                                            </small>
                                                                            <br>
                                                                            <small><b>Applied
                                                                                    at: </b> {{ $item->created_at->format('d-M-Y') }}
                                                                            </small>
                                                                            <hr>
                                                                        </div>


                                                                        <!-- Icons with images when contact card is locked start here for Module Interview List -->
                                                                        <!-- Remember when lock only 4 point show -->
                                                                        @if($item->unlocked == 0)
                                                                        <div style="text-align: center">

                                                                                <span style="margin: 0px 10px">

                                                                                    <a href="#"
                                                                                       onclick="unlockMeBtn('{{$item->id}}')"
                                                                                       class="index">Interview
                                                                                        <img src="https://img.icons8.com/external-becris-lineal-color-becris/64/000000/external-interview-business-situations-becris-lineal-color-becris.png"
                                                                                             style="height:25px;width:30px">
                                                                                    </a>
                                                                                    <a href="#"
                                                                                       onclick="unlockMeBtn('{{$item->id}}')"
                                                                                       class="index">Hired
                                                                                        <img src="https://img.icons8.com/ios-filled/50/000000/teacher-hirring.png"
                                                                                             style="height:25px;width:30px">
                                                                                    </a>
                                                                                </span>
                                                                            <span style="margin: 0px 10px">
                                                                                    <a data-toggle="tooltip"
                                                                                       data-placement="top"
                                                                                       title="You are Rejected"
                                                                                       href="{{ lurl('account/Applicants/rejected/'.$item->id .'') }}"
                                                                                       class="remove"> Rejected
                                                                                        <img src="https://img.icons8.com/fluency/48/000000/unfriend-female.png"
                                                                                             style="height:25px;width:30px">
                                                                                    </a>

                                                                                    <a data-toggle="modal"
                                                                                       data-target="#modal-note"
                                                                                       onclick="applicantCall('<?= $item->id ?>')"
                                                                                       href="#" class="index">Private Note
                                                                                        <img src="https://img.icons8.com/ios-filled/48/000000/private-lock.png"
                                                                                             style="height:25px;width:30px">
                                                                                    </a>
                                                                                </span>
                                                                        </div>
                                                                        @else


                                                                        <!-- code for Module Interview List when user is unlocked and 5 point are show including download button -->
                                                                        <div style="text-align: center">
                                                                                <span style="margin: 0px 10px">
                                                                                    <a data-toggle="tooltip"
                                                                                       data-placement="top"
                                                                                       title="Download the resume"
                                                                                       onclick="downloadCall('<=$item->id?>')"
                                                                                       href="{{ $item->file }}"
                                                                                       target="_blank" class="index">Download
                                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                                             width="24" height="24"
                                                                                             viewBox="0 0 24 24"
                                                                                             fill="none"
                                                                                             stroke="currentColor"
                                                                                             stroke-width="2"
                                                                                             stroke-linecap="round"
                                                                                             stroke-linejoin="round"
                                                                                             class="feather feather-download"
                                                                                             style="width: 14px;">
                                                                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                                                            <polyline
                                                                                                    points="7 10 12 15 17 10"></polyline>
                                                                                            <line x1="12" y1="15"
                                                                                                  x2="12" y2="3"></line>
                                                                                        </svg>
                                                                                    </a>

                                                                                    <a data-toggle="tooltip"
                                                                                       data-placement="top"
                                                                                       title="Send Interview Request"
                                                                                       href="{{ lurl('account/Applicants/interview/'.$item->id) }}"
                                                                                       class="index">Interview
                                                                                        <img src="https://img.icons8.com/external-becris-lineal-color-becris/64/000000/external-interview-business-situations-becris-lineal-color-becris.png"
                                                                                             style="height:25px;width:30px">
                                                                                    </a>

                                                                                    <a data-toggle="tooltip"
                                                                                       data-placement="top"
                                                                                       id="disabled"
                                                                                       title="You are hired"
                                                                                       href="{{ lurl('account/Applicants/haired/'.$item->id .'') }}"
                                                                                       class="index">Hired
                                                                                        <img src="https://img.icons8.com/ios-filled/50/000000/teacher-hirring.png"
                                                                                             style="height:25px;width:30px">
                                                                                    </a>
                                                                                </span>
                                                                            <span style="margin: 0px 10px">
                                                                                    <a data-toggle="tooltip"
                                                                                       data-placement="top"
                                                                                       title="You are Rejected"
                                                                                       href="{{ lurl('account/Applicants/rejected/'.$item->id .'') }}"
                                                                                       class="remove">Rejected
                                                                                        <img src="https://img.icons8.com/fluency/48/000000/unfriend-female.png"
                                                                                             style="height:25px;width:30px">
                                                                                    </a>
                                                                                    <a data-toggle="modal"
                                                                                       data-target="#modal-note"
                                                                                       onclick="applicantCall('<?= $item->id ?>')"
                                                                                       href="#" class="index">Private Note
                                                                                        <img src="https://img.icons8.com/ios-filled/48/000000/private-lock.png"
                                                                                             style="height:25px;width:30px">
                                                                                    </a>
                                                                                </span>
                                                                        </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php }
                                                } ?>

                                                <!-- End code for Module Interview List -->


                                                <!-- Start code for Module Hired  List -->
                                                @if($appliedCounter == 0)
                                                <tr class="job-items">
                                                    <td colspan=7>
                                                        <h6 class="text-muted" style="text-align: center">You
                                                            don't have any applicant yet!</h6>
                                                    </td>
                                                </tr>
                                                @endif
                                                </tbody>
                                                <tbody id="Haired" style="display:none ;">
                                                <?php
                                                $appliedCounter = 0;
                                                foreach ($data as $key => $item) {
                                                    if ($item->status == 'haired') {
                                                        $appliedCounter++;

                                                        ?>
                                                        <tr class="job-items">
                                                            <td class="title"><?= !empty($item->post['title']) ? $item->post['title'] : "" ?></td>
                                                            <td>
                                                                <img style="width: 60px"
                                                                     src="{{url('/public/storage/')}}/{{$item->user->file}}"
                                                                     alt="...">
                                                            </td>
                                                            <td class="title">{{$item->name}}</td>
                                                            <td class="title">{{ $item->created_at->format('d-M-Y') }}</td>
                                                            <td class="title">
                                                                <div class="action">


                                                                    <a data-toggle="modal"
                                                                       data-target="#modal-view{{$key}}"
                                                                       href="#" class="mybtn"
                                                                       style="width: auto;text-align: center;color: #fff;">
                                                                        <i data-feather="eye"></i>
                                                                        View Details</a>


                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <div class="modal fade modal-delete" id="modal-view{{$key}}"
                                                             tabindex="1" role="dialog" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-body">
                                                                        <button type="button" class="close myClose"
                                                                                data-dismiss="modal">
                                                                            &times;
                                                                        </button>
                                                                        <h4>
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                 width="24" height="24" viewBox="0 0 24 24"
                                                                                 fill="none" stroke="currentColor"
                                                                                 stroke-width="2" stroke-linecap="round"
                                                                                 stroke-linejoin="round"
                                                                                 class="feather feather-calendar"
                                                                                 data-toggle="tooltip" data-placement="top"
                                                                                 title="Create private note"
                                                                                 style="color: #695f8c;width: 14px;">
                                                                                <rect x="3" y="4" width="18" height="18"
                                                                                      rx="2" ry="2"></rect>
                                                                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                                                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                                                                <line x1="3" y1="10" x2="21" y2="10"></line>
                                                                            </svg>
                                                                            View Detiles
                                                                        </h4>
                                                                        <div style="width: 100%;text-align: center;">
                                                                            <img src="{{url('/public/storage')}}/{{$item->user->file}}"
                                                                                 style="width: 30%; display: block;margin: auto;">
                                                                            <a href="{{url('/profile')}}/{{$item->user->id}}"
                                                                               style="width: 13px;text-align: center;color: #fff;background: #61f1f9;
                         font-size: 14px;border-radius: 8px;padding: 0px 5px;">
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                     width="24" height="24"
                                                                                     viewBox="0 0 24 24" fill="none"
                                                                                     stroke="currentColor" stroke-width="2"
                                                                                     stroke-linecap="round"
                                                                                     stroke-linejoin="round"
                                                                                     class="feather feather-eye"
                                                                                     style="width: 13px;">
                                                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                                                    <circle cx="12" cy="12" r="3"></circle>
                                                                                </svg>
                                                                                View Profile</a>
                                                                        </div>
                                                                        <br>
                                                                        <div>
                                                                            <small><b>Name: </b> {{$item->name}} </small>
                                                                            <br>
                                                                            <small>
                                                                                @foreach ($item->user->user_skill_experience as $key => $a)
                                                                                <b>{{$a->skill->name}}</b>
                                                                                {{$a->experience->name}} Experience
                                                                                <br>
                                                                                @endforeach
                                                                            </small>
                                                                            <hr>
                                                                            <small><b>Title: </b><?= !empty($item->post['title']) ? $item->post['title'] : "" ?>
                                                                            </small>
                                                                            <br>
                                                                            <small><b>Applied
                                                                                    at: </b> {{ $item->created_at->formatLocalized(config('settings.app.default_datetime_format')) }}
                                                                            </small>
                                                                            <hr>
                                                                        </div>


                                                                        <!-- code for Module Hired List when user is locked and 4 point are show without download button -->

                                                                        @if($item->unlocked == 0)
                                                                        <div style="text-align: center">

                                                                                <span style="margin: 0px 10px">
                                                                                    <!-- <a href="#" onclick="unlockMeBtn('{{$item->id}}')"
                         class="index">Download
                         <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download" style="width: 14px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>

                        </a> -->

                                                                                        <a href="#"
                                                                                           onclick="unlockMeBtn('{{$item->id}}')"
                                                                                           class="index">Interview
                                                                                        <img src="https://img.icons8.com/external-becris-lineal-color-becris/64/000000/external-interview-business-situations-becris-lineal-color-becris.png"
                                                                                             style="height:25px;width:30px">
                                                                                    </a>
                                                                                    <a href="#"
                                                                                       onclick="unlockMeBtn('{{$item->id}}')"
                                                                                       class="index">Hired
                                                                                        <img src="https://img.icons8.com/ios-filled/50/000000/teacher-hirring.png"
                                                                                             style="height:25px;width:30px">
                                                                                    </a>
                                                                                </span>
                                                                            <span style="margin: 0px 10px">
                                                                                    <a data-toggle="tooltip"
                                                                                       data-placement="top"
                                                                                       title="You are Rejected"
                                                                                       href="{{ lurl('account/Applicants/rejected/'.$item->id .'') }}"
                                                                                       class="remove">Rejected
                                                                                        <img src="https://img.icons8.com/fluency/48/000000/unfriend-female.png"
                                                                                             style="height:25px;width:30px">
                                                                                    </a>
                                                                                    <a data-toggle="modal"
                                                                                       data-target="#modal-note"
                                                                                       onclick="applicantCall('<?= $item->id ?>')"
                                                                                       href="#" class="index">Private Note
                                                                                        <img src="https://img.icons8.com/ios-filled/48/000000/private-lock.png"
                                                                                             style="height:25px;width:30px">
                                                                                    </a>
                                                                                </span>
                                                                        </div>
                                                                        @else
                                                                        <div style="text-align: center">
                                                                                <span style="margin: 0px 10px">
                                                                                    <!-- <a data-toggle="tooltip" data-placement="top"
            title="Download the resume"
            onclick="downloadCall('<=$item->id?>')" //add '?' at start in php code.
            href="{{ $item->file }}"
            target="_blank"
            class="index"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download" style="width: 14px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
        </a> -->

                                                                                    <!-- Icon with images for Hired Code is Below Start -->

                                                                                    <a data-toggle="tooltip"
                                                                                       data-placement="top"
                                                                                       title="Send Interview Request"
                                                                                       href="{{ lurl('account/Applicants/interview/'.$item->id) }}"
                                                                                       class="index">Interview
                                                                                        <img src="https://img.icons8.com/external-becris-lineal-color-becris/64/000000/external-interview-business-situations-becris-lineal-color-becris.png"
                                                                                             style="height:25px;width:30px">
                                                                                    </a>

                                                                                    <a data-toggle="tooltip"
                                                                                       data-placement="top"
                                                                                       id="disabled"
                                                                                       title="You are hired"
                                                                                       href="{{ lurl('account/Applicants/haired/'.$item->id .'') }}"
                                                                                       class="index">Hired
                                                                                        <img src="https://img.icons8.com/ios-filled/50/000000/teacher-hirring.png"
                                                                                             style="height:25px;width:30px">
                                                                                    </a>
                                                                                </span>
                                                                            <span style="margin: 0px 10px">
                                                                                    <a data-toggle="tooltip"
                                                                                       data-placement="top"
                                                                                       title="You are Rejected"
                                                                                       href="{{ lurl('account/Applicants/rejected/'.$item->id .'') }}"
                                                                                       class="remove">Rejected
                                                                                        <img src="https://img.icons8.com/fluency/48/000000/unfriend-female.png"
                                                                                             style="height:25px;width:30px">
                                                                                    </a>
                                                                                    <a data-toggle="modal"
                                                                                       data-target="#modal-note"
                                                                                       onclick="applicantCall('<?= $item->id ?>')"
                                                                                       href="#" class="index">Private Note
                                                                                        <img src="https://img.icons8.com/ios-filled/48/000000/private-lock.png"
                                                                                             style="height:25px;width:30px">
                                                                                    </a>
                                                                                </span>
                                                                        </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php }
                                                } ?>
                                                <!-- End code for Module Hired List -->


                                                @if($appliedCounter == 0)
                                                <tr class="job-items">
                                                    <td colspan=7>
                                                        <h6 class="text-muted" style="text-align: center">You
                                                            don't have any applicant yet!</h6>
                                                    </td>
                                                </tr>
                                                @endif
                                                </tbody>
                                                <tbody id="Rejected" style="display:none ;">
                                                <?php
                                                $appliedCounter = 0;
                                                foreach ($data as $key => $item) {
                                                    if ($item->status == 'rejected') {
                                                        $appliedCounter++;
                                                        ?>
                                                        <tr class="job-items">
                                                            <td class="title"><?= !empty($item->post['title']) ? $item->post['title'] : "" ?></td>
                                                            <td>
                                                                <img style="width: 60px"
                                                                     src="{{url('/public/storage/')}}/{{$item->user->file}}"
                                                                     alt="...">
                                                            </td>
                                                            <td class="title">{{$item->name}}</td>
                                                            <td class="title">{{ $item->created_at->format('d-M-Y') }}</td>
                                                            <td class="title">
                                                                <div class="action">
                                                                    <a data-toggle="modal" data-target="#modal-view{{$key}}"
                                                                       href="#" class="mybtn"
                                                                       style="width: auto;text-align: center;color: #fff;">
                                                                        <i data-feather="eye"></i>
                                                                        View Details</a>

                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <div class="modal fade modal-delete" id="modal-view{{$key}}"
                                                             tabindex="1" role="dialog" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-body">
                                                                        <button type="button" class="close myClose"
                                                                                data-dismiss="modal">
                                                                            &times;
                                                                        </button>
                                                                        <h4>
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                 width="24" height="24" viewBox="0 0 24 24"
                                                                                 fill="none" stroke="currentColor"
                                                                                 stroke-width="2" stroke-linecap="round"
                                                                                 stroke-linejoin="round"
                                                                                 class="feather feather-calendar"
                                                                                 data-toggle="tooltip" data-placement="top"
                                                                                 title="Create private note"
                                                                                 style="color: #695f8c;width: 14px;">
                                                                                <rect x="3" y="4" width="18" height="18"
                                                                                      rx="2" ry="2"></rect>
                                                                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                                                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                                                                <line x1="3" y1="10" x2="21" y2="10"></line>
                                                                            </svg>
                                                                            View Detailes
                                                                        </h4>
                                                                        <div style="width: 100%;text-align: center;">
                                                                            <img src="{{url('/public/storage')}}/{{$item->user->file}}"
                                                                                 style="width: 30%; display: block;margin: auto;">
                                                                            <a href="{{url('/profile')}}/{{$item->user->id}}"
                                                                               style="width: 13px;text-align: center;color: #fff;background: #61f1f9;
                         font-size: 14px;border-radius: 8px;padding: 0px 5px;">
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                     width="24" height="24"
                                                                                     viewBox="0 0 24 24" fill="none"
                                                                                     stroke="currentColor" stroke-width="2"
                                                                                     stroke-linecap="round"
                                                                                     stroke-linejoin="round"
                                                                                     class="feather feather-eye"
                                                                                     style="width: 13px;">
                                                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                                                    <circle cx="12" cy="12" r="3"></circle>
                                                                                </svg>
                                                                                View Profile</a>
                                                                        </div>
                                                                        <br>
                                                                        <div>
                                                                            <small><b>Name: </b> {{$item->name}} </small>
                                                                            <br>
                                                                            <small>

                                                                                @foreach ($item->user->user_skill_experience as $key => $a)
                                                                                <b>{{$a->skill->name}}</b>
                                                                                {{$a->experience->name}} Experience
                                                                                <br>
                                                                                @endforeach
                                                                            </small>
                                                                            <hr>
                                                                            <small><b>Title: </b> <?= !empty($item->post['title']) ? $item->post['title'] : "" ?>
                                                                            </small>
                                                                            <br>
                                                                            <small><b>Applied
                                                                                    at: </b> {{ $item->created_at->formatLocalized(config('settings.app.default_datetime_format')) }}
                                                                            </small>
                                                                            <hr>
                                                                        </div>


                                                                        <!-- Start code for Module Rejected List -->
                                                                        @if($item->unlocked == 0)

                                                                        <div style="text-align: center">

                                                                            <!-- Icons with image code start here for Rejected Module -->
                                                                            <span style="margin: 0px 10px">

                                                                                    <!-- Code of Downlaod Button,here we are hide this due to some reason -->

                                                                                <!-- <a href="#" onclick="unlockMeBtn('{{$item->id}}')"
                     class="index">
                     <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download" style="width: 14px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                 </a> -->

                                                                                <a href="#"
                                                                                   onclick="unlockMeBtn('{{$item->id}}')"
                                                                                   class="index"> Interview
                                                                                        <img src="https://img.icons8.com/external-becris-lineal-color-becris/64/000000/external-interview-business-situations-becris-lineal-color-becris.png"
                                                                                             style="height:25px;width:30px">
                                                                                    </a>

                                                                                    <a href="#"
                                                                                       onclick="unlockMeBtn('{{$item->id}}')"
                                                                                       class="index">Hired
                                                                                        <img src="https://img.icons8.com/ios-filled/50/000000/teacher-hirring.png"
                                                                                             style="height:25px;width:30px">
                                                                                    </a>
                                                                                </span>
                                                                            <span style="margin: 0px 10px">
                                                                                    <a data-toggle="tooltip"
                                                                                       data-placement="top"
                                                                                       title="You are Rejected"
                                                                                       href="{{ lurl('account/Applicants/rejected/'.$item->id .'') }}"
                                                                                       class="remove">Rejected
                                                                                        <img src="https://img.icons8.com/fluency/48/000000/unfriend-female.png"
                                                                                             style="height:25px;width:30px">
                                                                                    </a>
                                                                                    <a data-toggle="modal"
                                                                                       data-target="#modal-note"
                                                                                       onclick="applicantCall('<?= $item->id ?>')"
                                                                                       href="#" class="index">Private Note
                                                                                        <img src="https://img.icons8.com/ios-filled/48/000000/private-lock.png"
                                                                                             style="height:25px;width:30px">
                                                                                    </a>
                                                                                </span>
                                                                        </div>
                                                                        @else
                                                                        <div style="text-align: center">
                                                                                <span style="margin: 0px 10px">
                                                                                    <!-- <a data-toggle="tooltip" data-placement="top"
            title="Download the resume"
            onclick="downloadCall('<=$item->id?>')" yahan pe start me'?'add krna h.
            href="{{ $item->file }}"
            target="_blank"
            class="index"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download" style="width: 14px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
        </a> -->

                                                                                    <!-- Start code for icons with images for Rejected List -->
                                                                                    <a data-toggle="tooltip"
                                                                                       data-placement="top"
                                                                                       title="Send Interview Request"
                                                                                       href="{{ lurl('account/Applicants/interview/'.$item->id) }}"
                                                                                       class="index">Interview
                                                                                        <img src="https://img.icons8.com/external-becris-lineal-color-becris/64/000000/external-interview-business-situations-becris-lineal-color-becris.png"
                                                                                             style="height:25px;width:30px">

                                                                                    </a>

                                                                                    <a data-toggle="tooltip"
                                                                                       data-placement="top"
                                                                                       id="disabled"
                                                                                       title="You are hired"
                                                                                       href="{{ lurl('account/Applicants/haired/'.$item->id .'') }}"
                                                                                       class="index">Hired
                                                                                        <img src="https://img.icons8.com/ios-filled/50/000000/teacher-hirring.png"
                                                                                             style="height:25px;width:30px">
                                                                                    </a>

                                                                                </span>
                                                                            <span style="margin: 0px 10px">
                                                                                    <a data-toggle="tooltip"
                                                                                       data-placement="top"
                                                                                       title="You are Rejected"
                                                                                       href="{{ lurl('account/Applicants/rejected/'.$item->id .'') }}"
                                                                                       class="remove">Rejected
                                                                                        <img src="https://img.icons8.com/fluency/48/000000/unfriend-female.png"
                                                                                             style="height:25px;width:30px">
                                                                                    </a>
                                                                                    <a data-toggle="modal"
                                                                                       data-target="#modal-note"
                                                                                       onclick="applicantCall('<?= $item->id ?>')"
                                                                                       href="#" class="index">Private Note
                                                                                        <img src="https://img.icons8.com/ios-filled/48/000000/private-lock.png"
                                                                                             style="height:25px;width:30px">
                                                                                    </a>
                                                                                </span>
                                                                        </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php }
                                                } ?>
                                                <!-- End code for Rejected List -->

                                                @if($appliedCounter == 0)
                                                <tr class="job-items">
                                                    <td colspan=7>
                                                        <h6 class="text-muted" style="text-align: center">You
                                                            don't have any applicant yet!</h6>
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
    <button id="modalUnlockOpen" data-toggle="modal" data-target="#modal-unlock" hidden
            style="display: none"></button>
    <div class="modal fade modal-delete" id="modal-note" tabindex="1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close myClose" data-dismiss="modal">
                        &times;
                    </button>
                    <h4>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                             stroke-linejoin="round" class="feather feather-calendar" data-toggle="tooltip"
                             data-placement="top" title="Create private note" style="color: #695f8c;width: 14px;">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        Private Note
                    </h4>
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
                            <button type="submit" name="noteSent"
                                    style="border: 0;border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;outline: none;font-family: 'Poppins', sans-serif;font-weight: 600;font-size: 1.4rem;cursor: pointer;padding: 15px 30px;margin-right: 5px;margin-bottom: 10px;background: rgba(238, 238, 238, 0.15);-webkit-transition: all .3s ease;-o-transition: all .3s ease;transition: all .3s ease;color: #61f1f9;">
                                Create
                            </button>
                            <button type="reset" class="delete-button" data-dismiss="modal"
                                    style="border: 0;border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;outline: none;font-family: 'Poppins', sans-serif;font-weight: 600;font-size: 1.4rem;cursor: pointer;padding: 15px 30px;margin-right: 5px;margin-bottom: 10px;background: rgba(238, 238, 238, 0.15);-webkit-transition: all .3s ease;-o-transition: all .3s ease;transition: all .3s ease;background: #ff3366;color: #ffffff;">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade modal-delete" id="modal-unlock" style="z-index: 111111111;" tabindex="1" role="dialog"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close myClose" data-dismiss="modal">
                        &times;
                    </button>
                    <h4>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                             stroke-linejoin="round" class="feather feather-calendar" data-toggle="tooltip"
                             data-placement="top" title="Create private note" style="color: #695f8c;width: 14px;">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        Confirmation
                    </h4>
                    <p>Are you sure you would like to view contact card?</p>
                    <small class="text-warning"><b>Note :</b> It will take 1 credit from you account.</small>
                    <br><br>
                    <div style="text-align: right">
                        <a href="#" class="btn btn-danger" onclick="unlockMe()" id="hideMe"
                           style="padding: 12px;font-size: 13px;margin-right: 10px">
                            Yes
                        </a>
                        <a href="#" class="mybtn" data-dismiss="modal"
                           style="width: auto;text-align: center;color: #fff;">
                            No
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var unlockId = 0;

        function unlockMeBtn(id) {
            unlockId = id;
            console.log(id);
            $('#modalUnlockOpen').click();
        }

        function unlockMe() {
            $('#hideMe').hide();
            window.location.href = "{{ lurl('account/Applicants/unlock/') }}/" + unlockId;
        }

        function applicantCall(id) {
            document.getElementById('applicant_id').value = id;
        }

        function downloadCall(id) {
            var URL = '<?= lurl('account/save_resume_add') ?>';
            $.ajax({
                type: "POST",
                url: URL,
                data: {
                    id: id,
                    page: 'Applicant Page'
                },
                success: function (result) {
                }
            });
        }
    </script>
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
                        <input id="subjuct" name="subjuct" type="text" maxlength="60" class="form-control">
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
    var unlockId = 0;

    function unlockMeBtn(id) {
        unlockId = id;
        console.log(id);
        $('#modalUnlockOpen').click();
    }

    function unlockMe() {
        $('#hideMe').hide();
        window.location.href = "{{ lurl('account/Applicants/unlock/') }}/" + unlockId;
    }

    function applicantCall(id) {
        document.getElementById('applicant_id').value = id;
    }

    $('#SMSForm').submit(function (e) {
        e.preventDefault();
        alert('Under Development');
    });
    $('#form').submit(function (e) {
        e.preventDefault();
        var AjaxURL = '<?= lurl('account/resumes/sendResumeByEmail') ?>';
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

<div style="clear:both"></div>

</div>
</div>
<!--/.page-content-->

</div>
<!--/.row-->
</div>
<!--/.container-->
</div>
<!-- /.main-container -->
@endsection
@section('after_scripts')
<script>
    function toggle(index) {
        if (index === 'Applied') {
            $('#Applied').show();
            $('#Interviews').hide();
            $('#Haired').hide();
            $('#Rejected').hide();
        }
        if (index === 'Interviews') {
            $('#Applied').hide();
            $('#Interviews').show();
            $('#Haired').hide();
            $('#Rejected').hide();
        }
        if (index === 'Haired') {
            $('#Applied').hide();
            $('#Interviews').hide();
            $('#Haired').show();
            $('#Rejected').hide();
        }
        if (index === 'Rejected') {
            $('#Applied').hide();
            $('#Interviews').hide();
            $('#Haired').hide();
            $('#Rejected').show();
        }
    }
</script>
@endsection