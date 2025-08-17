@extends('layouts.master')

@section('content')

    @include('common.spacer')
    <style>
        .numbercounter a {
            display: inline-table !important;
        }
        .question.active, .question:active, .question:focus, .question:hover{
            background-color: #22D3FD !important;
            border-color: #22D3FD !important;
            color: #fff !important;
        }
    </style>
    <div class="main-container">
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

                <div class="col-md-3 page-sidebar">
                    @include('account/inc/sidebar')
                </div>
                <div class="col-md-9 page-content">
                    <div class="inner-box">
                        <div class="alice-bg padding-top-60 section-padding-bottom">
                            <div class="container">
                                <div class="row">
                                    <div class="col">
                                        <div class="company-details">
                                            <div class="row">
                                                <div class="col-2 col-md-2" style="margin-left: 1px;">
                                                    @php
                                                        $imageUrl = \App\Helpers\Helper::getImageOrThumbnailLink(auth()->user(), true);
                                                    @endphp
                                                    <a href="{{ $imageUrl }}" target="_blank">
                                                        <img src="{{ $imageUrl }}" alt="User Image">
                                                    </a>
                                                </div>

                                                <div class="col-8 col-md-3 p-0">
                                                    <h4 class="company-heading">
                                                        <?= !empty(auth()->user()->name) ? auth()->user()->name : "" ?>
                                                        @if(auth()->user()->verified_email==1 &&
                                                        auth()->user()->verified_phone==1)
                                                            <img src="{{url('public/storage/app/default/ico/blue_tick.png')}}"
                                                                 style="width:13px" class="img-fluid" alt="">
                                                        @endif
                                                        <br>
                                                        @if(!empty(auth()->user()->parent_id))
                                                            <span class="company-type"><i
                                                                        data-feather="user"></i><?= !empty($data['city_data']->name) ? $data['city_data']->name : ""; ?>,
                                                        <?= !empty($data['country_data']->name) ? $data['country_data']->name : ""; ?></span>
                                                            <img src="{{ url()->asset('images/flags/16/' . strtolower(auth()->user()->country_code) . '.png') }}"/>
                                                        @endif
                                                    </h4>
                                                    <div class="edit-button-mobile">
                                                        <a href="{{url('/account/profile')}}"
                                                           class="btn btn-primary btn-sm btn-right float-right"><i
                                                                    class="icon-pencil"></i> {{t('Edit')}}</a>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6 text-right company-edit">
                                                    <div class="edit-button-desktop">
                                                        @if(auth()->user()->parent_id == auth()->user()->id)
                                                            <a href="#" data-toggle="modal"
                                                               data-target="#exampleModalCenter"
                                                               class="btn btn-primary btn-sm">
                                                                {{t('Upload Logos For Multiple Brands')}}</a>
                                                        @endif
                                                        <a href="{{url('/account/profile')}}"
                                                           class="btn btn-primary btn-sm"><i class="icon-pencil"></i>
                                                            {{t('Edit')}}</a>

                                                        <br>
                                                    </div>
                                                    @if(!empty(auth()->user()->parent_id))
                                                        <div class="numbercounter">
                                                            <a style="cursor: context-menu;">
                                                                <div class="circle-counter">
                                                                    @if($data['remaning_days'] < 0)
                                                                        0
                                                                    @else
                                                                        {{ $data['remaning_days'] }}
                                                                    @endif </div>
                                                                <div class="circle-text DaysRemainingOfYourSubscription"
                                                                     style="width: 65px;">
                                                                    @if(!empty($data['latest_package']->package_id) && $data['latest_package']->package_id == 5)
                                                                        @if(!empty($data['latest_package']->package_expire_date) && date('Y-m-d',strtotime($data['latest_package']->package_expire_date)) == date('Y-m-d'))
                                                                            {{t('Subscription Hours Remaining')}}
                                                                        @else
                                                                            Free Trial Days Remaining
                                                                        @endif
                                                                    @else

                                                                        @if(!empty($data['latest_package']->package_expire_date) && date('Y-m-d',strtotime($data['latest_package']->package_expire_date)) == date('Y-m-d'))
                                                                            {{t('Subscription Hours Remaining')}}
                                                                        @else
                                                                            {{t('Days Remaining Of Your Subscription')}}
                                                                        @endif
                                                                    @endif
                                                                </div>

                                                            </a>
                                                            &nbsp;
                                                            <a style="cursor: context-menu;">
                                                                    <?php $style = '' ?>
                                                                @if($data['credits'] > 100 || $data['remaining_credits'] > 100)
                                                                        <?php
                                                                        $style = 'style=font-size:x-large !important'
                                                                        ?>
                                                                @endif

                                                                <div class="circle-counter" {{$style}}>
                                                                    @if(!empty($data['latest_package']->unlimited_credit))
                                                                        <span>  {{ $data['remaining_credits'] }}
                                                        @else
                                                                                {{ \App\Helpers\Number::short( $data['credits'] - $data['remaining_credits']) }}
                                                                                / {{$data['credits']}}
                                                                    @endif

                                                                </div>

                                                                <div class="circle-text">
                                                                    {{t('Contact Cards Opened')}}
                                                                </div>
                                                            </a>
                                                            &nbsp;

                                                            <a style="cursor: context-menu;">

                                                                <div class="circle-counter">
                                                                    @if(!empty($data['latest_package']->unlimited))
                                                                        {{ $data['remaining_post'] }}
                                                                    @else
                                                                        {{ \App\Helpers\Number::short($data['remaining_post']) }}
                                                                    @endif

                                                                </div>
                                                                <div class="circle-text">
                                                                    @if(!empty($data['latest_package']->package_id) && $data['latest_package']->package_id == 6)
                                                                        <p>{{t('Job Posts Created')}}</p>
                                                                    @else
                                                                        <p>{{t('Job Posts Remaining')}}</p>
                                                                    @endif
                                                                </div>
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="col-md-6">
                                                    <a href="javascript:void(0)" style="float:left" data-toggle="modal"
                                                       data-target="#modal_chat_with_admin"
                                                       class="btn btn-primary btn-sm question">
                                                        Questions? Start Chat With Hungry For Jobs Team
                                                    </a>
                                                </div>
                                                <div class="col-md-6">
                                                    @if( $data['packages_count'] > 1)
                                                        <button class="btn btn-primary btn-sm custom-margin"
                                                                onclick="show_package_details()"> Click Here To
                                                            View More Details On Your Multiple Subscriptions
                                                        </button>
                                                    @endif
                                                </div>


                                                @if(!empty(auth()->user()->parent_id))
                                                    <div class="col-md-12 mt-1">
                                                         <p
                                                         style="background: #615583;color: #fff;padding: 5px;text-align: left;float: right;">
                                                             {!! t('Your subscription package expires every 30 days job post credits and contact card credits also expire with the subscription package') !!}
                                                         </p>
                                                    </div>
                                                @endif
                                            </div>


                                            <hr>
                                            @if(!empty(auth()->user()->parent_id))
                                                <h3>
                                                    {{t('Company Description')}}
                                                </h3>
                                                <p><?= !empty($data['company_data']->description) ? $data['company_data']->description : ""; ?>
                                                </p>
                                                <hr>
                                                <div class="row">

                                                    <div class="col-md-12">
                                                        <p class="company-detail-p">
                                                                <?php
                                                                $entiteis = '';
                                                                $count_en = '';
                                                                if (!empty($data['company_data']->entities)) {
                                                                    $entiteis = explode(',', $data['company_data']->entities);
                                                                    $count_en = array_key_last($entiteis);
                                                                }

                                                                ?>
                                                            <b>{{t('Entities')}}:</b>
                                                            @if(!empty($entiteis))
                                                                @foreach($entiteis as $key => $entitei)

                                                                        <?= trim($entitei) ?>@if($key < $count_en)
                                                                        ,&nbsp;
                                                                    @endif
                                                                @endforeach
                                                            @endif </p>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p class="company-detail-p">
                                                        @if(!empty(auth()->user()->parent_id))
                                                            <b>{{t('Company Email')}}: </b>
                                                                <?= !empty(auth()->user()->email) ? auth()->user()->email : ""; ?>
                                                        @else
                                                            <b>{{t('Parent Company Email')}}: </b>
                                                                <?= !empty(auth()->user()->email) ? auth()->user()->email : ""; ?>
                                                        @endif
                                                    </p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p class="company-detail-p">
                                                        @if(!empty(auth()->user()->parent_id))
                                                            <b>{{t('Phone')}} Number: </b>
                                                                <?= !empty(auth()->user()->phone) ? auth()->user()->phone : ""; ?>
                                                        @else
                                                            <b>{{t('Parent Phone number')}}
                                                                : </b>
                                                                <?= !empty(auth()->user()->phone) ? auth()->user()->phone : ""; ?>
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                            <br>
                                            <br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    @if(!empty(auth()->user()->parent_id) && !empty($data['logoData']))
                        <div class="inner-box mt-2">
                            <div class="alice-bg padding-top-60 section-padding-bottom">
                                <div class="container">
                                    <div class="row">
                                        <div class="col">

                                            <div class="row">
                                                <h4>{{t('Logos')}}</h4>
                                                <br>
                                                    <?php if (!empty($data['logoData'])) {
                                                foreach ($data['logoData'] as $key => $value) {
                                                    if (file_exists(base_path('public/' . $value->logo))) {
                                                        $path = url('public/' . $value->logo);
                                                    } else if (file_exists(base_path($value->logo))) {
                                                        $path = url($value->logo);
                                                    } else {
                                                        $path = '';
                                                    }
                                                    ?>
                                                    <?php if (!empty($path)){ ?>
                                                <div class="col-2">
                                                    <a href="<?php echo $path ?>" target="_blank"> <img
                                                                src="<?php echo $path ?>" width="100"
                                                                height="100"></a>
                                                    <br>
                                                    <a class="btn btn-danger btn-sm"
                                                       href="{{url('/delete_employee_logo/'.$value->id)}}"
                                                       onclick="return confirm('Are you sure you want to delete this?')"><i
                                                                class="fa fa-trash"></i></a>
                                                </div>
                                                <?php } ?>
                                                <?php }
                                                } ?>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>


    <!-- The Modal -->
    <div class="modal" id="exampleModalCenter">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{t('Upload Logos For Multiple Brands')}}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="{{url('/upload_logo')}}" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <label for="Logos">{{t('Logos')}}</label>
                        <br/>
                        <input type="file" class="form-control" name="logos[]" multiple
                               accept="image/x-png,image/gif,image/jpeg" required/>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">{{t('Uploads')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade bd-example-modal-lg" id="subscribe_package_details" data-backdrop="static" role="dialog"
         data-dismiss="modal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{t('Multiple Subscription Package Details')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="track_company_package">
                            <thead>
                            <tr role="row">
                                <th>#</th>
                                <th>{{ t('Package_name') }} </th>
                                <th>{{ t('remaining_posts') }}</th>
                                <th>{{ t('Remaining_credits') }}</th>
                                <th>{{ t('Expire_date') }}</th>
                                <th>{{ t('remaining_days') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('modals.chat_with_admin');

@endsection
@section('after_styles')
    <style>
        .action-td p {
            margin-bottom: 5px;
        }
    </style>
@endsection


@section('after_scripts')
    <script>
        function show_package_details() {
            var track_company_package = $('#track_company_package tbody');
            track_company_package.empty();
            //   $('#overlay').show();
            $.ajax({
                url: "{{url('account/track_company_package_details')}}", // URL to the API endpoint
                method: 'GET',
                dataType: 'json',
                success: function (response) {
                    //   $('#overlay').hide();
                    var sn = 1;
                    track_company_package.empty();
                    $.each(response, function (index, item) {

                        var row = $('<tr>');
                        row.append($('<td>').text(sn));
                        row.append($('<td>').text(item.name));
                        if (item.unlimited === 1) {
                            row.append($('<td>').text('ꝏ'));
                            row.append($('<td>').text('ꝏ'));
                        } else {
                            row.append($('<td>').text(item.remaining_post));
                            row.append($('<td>').text(item.remaining_credits));
                        }


                        row.append($('<td>').text(item.package_expire_date));
                        row.append($('<td>').text(item.remaining_days));

                        track_company_package.append(row);
                        sn = sn + 1;
                    });
                    $('#subscribe_package_details').modal('show');
                },
                error: function (error) {
                    // Handle errors
                    console.error('An error occurred:', error);
                }
            });
        }

        $(document).ready(function () {
            var $userImageDiv = $('.user-image-div-not-round');
            var bg = $userImageDiv.attr('data-lazysrc');

            setTimeout(function () {
                // Check if the actual image is loaded
                var img = new Image();
                img.onload = function () {
                    $userImageDiv.css('background-image', 'url("' + bg + '")');
                };
                img.onerror = function () {
                    console.log('Image failed to load');
                };
                img.src = bg;
            }, 5000); // 5000 milliseconds = 5 seconds
        });


        $(window).on('load', function() {
            $.ajax({
                url: '{{url('account/update_subscription_ajax')}}',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    console.log('Success:', data);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });

    </script>
@endsection