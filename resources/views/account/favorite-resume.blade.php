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
            <div class="col-md-9 page-content">
                <div class="inner-box">

                    <div style="clear:both"></div>
                    <div class="alice-bg section-padding-bottom">
                        <div class="container no-gliters">
                            <div class="row no-gliters">
                                <div class="col">
                                    <div class="dashboard-container">
                                        <div class="dashboard-content-wrapper">
                                            <div class="dashboard-section basic-info-input">
                                                <h2><i data-feather="save"></i>{{t('Favorite Employee')}}
                                                    <i class="fas fa-question-circle" hidden
                                                        title="{{t('Check all Favorite Resume')}}" data-toggle="tooltip"
                                                        data-placement="top"></i>
                                                </h2>
                                            </div>
                                            <div class="manage-job-container">

                                                <div class="container">

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
                                                    <div class="manage-job-container">
                                                      
                                                           <table id="employee_data" style="width: 100%;" class="table">
                                                                <thead>
                                                                    <tr>

                                                                        <th data-sort-ignore="true" id="tooltip-style">#
                                                                        </th>
                                                                        <th data-sort-ignore="true" id="tooltip-style">
                                                                            {{t('Image')}}</th>
                                                                        <th data-sort-ignore="true" id="tooltip-style">
                                                                            {{t('Name')}}</th>
                                                                        <th data-type="numeric" width="100px"
                                                                            id="tooltip-style">
                                                                            {{t('Date joined')}}</th>
                                                                        <th data-sort-ignore="true" id="tooltip-style">
                                                                            {{t('City')}}</th>
                                                                        <th data-sort-ignore="true" id="tooltip-style">
                                                                            {{t('Country')}}</th>
                                                                        <th data-sort-ignore="true" id="tooltip-style">
                                                                            {{t('Skills Sets')}}
                                                                        </th>
                                                                        <th class="action" id="tooltip-style">
                                                                            {{t('Action')}}</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php 
                                                            $count = 0 ?>
                                                                    @if(!$data['resume']->isEmpty())
                                                                    @foreach ($data['resume'] as $key => $item)
                                                                    <?php
                                                            $count++
                                                            ?>
                                                                    <tr class="job-items">

                                                                        <td class="title">{{$count}}</td>
                                                                        <td class="title">
                                                                            <div class="user-image-div-message"
                                                                                style="background-image:url('{{  \App\Helpers\Helper::getImageOrThumbnailLink($item->user); }}')">
                                                                            </div>
                                                                        </td>
                                                                        <td class="title"><a href="{{ url('profile/'.$item->user->id) }}"  class="text-capitalize"> {{$item->user->name}} </a></td>
                                                                        <td class="title">
                                                                            {{$item->created_at->format('d M Y') }}</td>
                                                                        @if(!empty($item->user->cityData->name))
                                                                        <td class="title">
                                                                            {{$item->user->cityData->name}}
                                                                        </td>
                                                                        @else
                                                                        <td class="title"></td>
                                                                        @endif
                                                                        @if(!empty($item->user->country->name))

                                                                        <td class="title">
                                                                            {{ $item->user->country->name}}
                                                                        </td>
                                                                        @else
                                                                        <td class="title"></td>
                                                                        @endif

                                                                         <td class="title" style="width:100px">

                                                                        <?php
        
                                                                        $skill_sets = $item->user->skill_set;
                                                                        $skill_sets = str_replace(',', ', ', $skill_sets);
                                                                        echo $skill_sets;
                                                                        ?>
                                                                    </td>
                                                                        <td class="title">
                                                                            <div class="action row">
                                                                                <a href="{{url('/profile/')}}/{{$item->user->id}}"
                                                                                    class="btn btn-primary btn-sm mx-1">{{t('View Details')}}</a>

                                                                                <a onclick="change_applicants_status({{$item->user->id}})"
                                                                                    class="btn-default btn btn-sm heart-action  mx-1 mt-1">
                                                                                    <i class="fa fa-heart"
                                                                                        style="color: white;padding:2.5px"></i>
                                                                                </a>
                                                                        </td>

                                                                    </tr>
                                                                    @endforeach
                                                                    @else
                                                                    <style>
                                                                    #tooltip-style {
                                                                        padding-right: 36px;
                                                                    }
                                                                    </style>
                                                                    <tr>
                                                                        <td colspan="8">
                                                                            <h6 class="text-muted"
                                                                                style="text-align: center">
                                                                                You can favorite employees by going to
                                                                                their profile and clicking the heart
                                                                                icon..</h6>
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

                        $('#SMSForm').submit(function(e) {
                            e.preventDefault();
                            alert('Under Development');
                        });
                        $('#form').submit(function(e) {
                            e.preventDefault();
                            var AjaxURL = '<?= lurl('account/resumes/sendResumeByEmail')?>';
                            $.ajax({
                                type: "POST",
                                url: AjaxURL,
                                data: $('#form').serialize(),
                                beforeSend: function() {
                                    $("#dismiss").click();
                                },
                                success: function(result) {
                                    document.getElementById("alert-success").style.display =
                                        "block";
                                }
                            });
                        });

                        function sendMail(url) {
                            document.getElementById('pdf_url').value = url;
                        }
                        </script>
                        <script>
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
                        </script>

                        <div style="clear:both"></div>

                    </div>
                </div>
            </div>
        </div>

    </div>
    @endsection

    @section('after_scripts')
    @endsection
    <script>
    function change_applicants_status(url) {
        var myurl = "<?= URL('account/add_to_favorite') ?>";
        var icons = '';
        var message = 'Are you sure you want to unfavorite this employee?';
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
                window.location.replace(myurl + '/' + url + '?remove=1');
            } else if (result.isDenied) {
                return false;
            }
        });
    }
    </script>