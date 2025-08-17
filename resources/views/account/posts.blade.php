@extends('layouts.master')

@section('content')
    @include('common.spacer')

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
                <!--/.page-sidebar-->
                <div class="col-md-9 page-content">
                    <div class="inner-box">
                        @if ($pagePath=='my-posts')
                            <!-- Add icon Here -->

                        @elseif ($pagePath=='archived')
                            <h2 class="title-2"><i class=""></i> {{ t('Archived ads') }}

                            </h2>
                        @elseif ($pagePath=='favourite')
                            <h2 class="title-2"><i class=""></i> {{ t('Favourite jobs') }}

                            </h2>

                            <!-- Add new Path like above -->
                        @elseif ($pagePath=='search-resumes')
                            <h2 class="title-2"><i class="icon-hourglass"></i> {{ t('search-resumes') }} </h2>
                        @elseif ($pagePath=='companies')
                            <h2 class="title-2"><i class="icon-hourglass"></i> {{ t('companies') }} </h2>
                        @elseif ($pagePath=='pending-approval')
                            <h2 class="title-2"><i class="icon-hourglass"></i> {{ t('Pending approval') }} <i
                                        class="fas fa-question-circle"
                                        title="If Company Email is not varified then Jobs will be here  untill Email is varified by User or Admin."
                                        data-toggle="tooltip" data-placement="top"></i></h2>
                        @else
                            <h2 class="title-2"><i class="icon-docs"></i> {{ t('Posts') }} </h2>
                        @endif
                        <div class="modal fade" id="pendingapproval" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        {{t('Discription')}}
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                                    </div>
                                    <div class="modal-body">
                                        <p>{{t('If Company Email is not varified then Jobs will be here untill Email is varified by User or Admin')}}
                                            .</p>
                                    </div>

                                </div>

                            </div>
                        </div>

                        <div class="modal fade" id="archivedjobs" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        {{t('Description')}}
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                                    </div>
                                    <div class="modal-body">
                                        <p>{{t('If You make Your Job Archive from My Jobs Page, It will show only here (Noton Main page) Until You Repost it')}}
                                            .</p>
                                    </div>
                                    <div class="modal-footer">
                                        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                                    </div>
                                </div>

                            </div>
                        </div>


                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    @if(auth()->user()->user_type_id==1)
                                        <a class="btn btn-primary company_post" onclick="check_subscription()"
                                           href="javascript:void(0)">
                                            <i class="fa fa-plus"></i> {{ t('Create a Job ad') }}
                                        </a>
                                    @endif
                                </div>
                                <div class="col-sm-2 text-right">
                                    <label class="control-label text-right">{{ t('Search') }}
                                    </label>
                                </div>
                                <div class="col-sm-4 searchpan">
                                    <input type="text" class="form-control" id="filter">
                                    <a title="clear filter" class="clear-filter" href="#clear"><i
                                                class="fa fa-times"></i></a>
                                </div>
                            </div>
                        </div>
                        <form name="listForm" method="POST" action="{{ url('account/' . $pagePath . '/delete') }}">
                            {!! csrf_field() !!}
                            <div class="table-action " style="display: none">
                                <label for="checkAll">
                                    <input type="checkbox" id="checkAll">
                                    {{ t('Select') }}: {{ t('All') }} |
                                    <button type="submit" class="btn btn-sm btn-default delete-action">
                                        <i class="fa fa-trash"></i> {{ t('Delete') }}
                                    </button>
                                </label>
                                <div class="table-search pull-right col-sm-7">

                                </div>
                            </div>
                            <tr>
                                <td colspan="4">
                                    <?php if (strpos(url()->current(), 'archived') !== false) { ?>
                                    <h6 class="text-muted"
                                        style="text-align: center">{{t("When your 30 day subscription ends, your job posts become archived here. You can resubscribe and repost your archived jobs again from here at anytime. Please keep in mind, after 3 months of being archived, your job posts will be deleted automatically")}}</h6>
                                    <?php } ?>
                                </td>
                            </tr>
                            <div class="table-responsive">
                                <table id="addManageTable" class="table add-manage-table my-jobs-table table demo"
                                       data-filter="#filter" data-filter-text-only="true">
                                    <thead>
                                    <tr>
                                        <th data-sort-ignore="true"> {{ t('Ads Details') }}</th>
                                        @if(auth()->user()->user_type_id != 2)
                                            <th data-sort-ignore="true" class=" text-center">
                                                {{ t('Views') }}
                                            </th>
                                        @endif
                                        @if($pagePath == 'pending-approval')
                                            <th data-type="numeric">Resend Link</th>
                                        @endif
                                        <th data-type="numeric">{{t('Salary Range')}}</th>

                                        @if($pagePath !='favourite')
                                            <th data-type="numeric" class=" text-center">{{t('Applicants')}}</th>
                                        @endif
                                        @if($pagePath =='favourite')
                                            <th data-type="numeric" class=" text-center">{{t('Company Name')}}</th>
                                        @endif
                                        <th> {{ t('Option') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <?php
                                    if (isset($posts) && $posts->count() > 0) :

                                    foreach ($posts as $key => $post) :

                                        if ($pagePath == 'favourite') {
                                            if (isset($post->post)) {
                                                if (!empty($post->post)) {
                                                    $post = $post->post;
                                                } else {
                                                    continue;
                                                }
                                            } else {
                                                continue;
                                            }
                                        }
                                        // Fixed 2
                                        if (!$countries->has($post->country_code)) continue;
                                        // Get Post's URL
                                        $postUrl = \App\Helpers\UrlGen::post($post);
                                        // Get country flag
                                        $countryFlagPath = 'public/images/flags/16/' . strtolower($post->country_code) . '.png';
                                        ?>
                                    <tr>
                                        <td class="items-details-td">
                                            <strong>
                                                <a href="{{ $postUrl }}"
                                                   title="{{ $post->title }}">{{ \Illuminate\Support\Str::limit($post->title, 40) }}</a>
                                            </strong>
                                            @if (in_array($pagePath, ['my-posts', 'archived', 'pending-approval']))
                                                @if (isset($post->latestPayment) and !empty($post->latestPayment))
                                                    @if (isset($post->latestPayment->package) and !empty($post->latestPayment->package))
                                                            <?php
                                                            if ($post->featured == 1) {
                                                                $color = $post->latestPayment->package->ribbon;
                                                                $packageInfo = '';
                                                            } else {
                                                                $color = '#ddd';
                                                                $packageInfo = ' (' . t('Expired') . ')';
                                                            }
                                                            ?>
                                                        <i class="fa fa-check-circle tooltipHere"
                                                           style="color: {{ $color }};" title="" data-placement="bottom"
                                                           data-toggle="tooltip"
                                                           data-original-title="{{$packageInfo }}"></i>
                                                    @endif
                                                @endif
                                            @endif
                                            <br>
                                            @if(auth()->user()->user_type_id == 1)
                                                    <?php if ($post->is_approved == 0){ ?>
                                                <div class="badge badge-danger float-right">Not Approved</div>
                                                <?php } else { ?>
                                                <div class="badge badge-primary float-right">Approved</div>
                                                <?php } ?>
                                            @endif

                                                <?php
                                                $days_Rem = \App\Helpers\Helper::calculate_remaining_days_of_post($post);
                                                ?>
                                            @if($days_Rem)
                                                <div class="badge badge-warning float-right"> {{\App\Helpers\Helper::calculate_remaining_days_of_post($post)}}</div>
                                            @endif
                                            <br>
                                            @if ($pagePath == 'favourite')
                                                <strong><i class="icon-clock" title="Favourite On"></i></strong>&nbsp;
                                                {!! date('d-M-Y', strtotime($post->created_at)) !!}
                                            @else
                                                &nbsp; {{ t('Posted On') }}
                                                : {!! date('d-M-Y', strtotime($post->created_at)) !!}
                                            @endif
                                            @if (file_exists(public_path($countryFlagPath)))
                                                <img src="{{ url($countryFlagPath) }}" data-toggle="tooltip"
                                                     title="{{ $post->country->name }}">
                                            @endif
                                        </td>
                                        @if(auth()->user()->user_type_id != 2)
                                            <td>
                                                <div class="visitor-container text-center mr-4">
                                                    <strong class="content-center">
                                                        <i class="icon-eye " title="{{ t('Visitors') }}"></i>
                                                    </strong>

                                                    {{ $post->postMeta->visits ?? 0 }}
                                                </div>
                                            </td>
                                        @endif
                                        @if($pagePath=="pending-approval")
                                            <td><a class="btn btn-warning"
                                                   href="{{url('posts/resendemail/'.$post->id)}}">Re-send</a></td>
                                        @endif
                                        <td class="price-td">
                                            <div>
                                                <strong>
                                                    @if(auth()->user()->user_type_id==2 && $post->hide_salary == 1)
                                                        {{t('Salary Hidden by Employer')}}
                                                    @else
                                                        @if ($post->salary_min > 0)
                                                            {!! App\Helpers\Number::money($post->salary_min, $post->country_code) !!}
                                                            -{!! App\Helpers\Number::money($post->salary_max, $post->country_code)!!}
                                                        @else
                                                            {!! App\Helpers\Number::money($post->salary_min, $post->country_code) !!}
                                                            -{!! App\Helpers\Number::money($post->salary_max, $post->country_code) !!}
                                                        @endif
                                                    @endif
                                                </strong>
                                            </div>
                                        </td>
                                        @if($pagePath !='favourite')
                                            <td class="text-center">
                                                @if(collect(request()->segments())->last() == 'archived')
                                                    <span class="badge badge-pill"><a
                                                                href="{{ url('/account/Archive_applicants') }}"> {{$post->post_count}}</a> <span>
                                                            @else
                                                                <span class="badge badge-pill"><a
                                                                            href="{{ url('/account/applied_applicants?post_id='.$post->id) }}"> {{$post->post_count}}</a> <span>
                                                @endif

                                            </td>
                                        @endif
                                        @if($pagePath == 'favourite')
                                            <td class="text-center"><a
                                                        href="{{url('companyprofile/'.$post->user_id)}}">{{ $post->company_name }}</a>
                                            </td>
                                        @endif
                                        <td class="action-td">
                                            @if (in_array($pagePath, ['my-posts', 'pending-approval']) and $post->user_id==auth()->user()->id and $post->archived==0)
                                                <a class="btn btn-primary btn-sm eidit "
                                                   href="{{ \App\Helpers\UrlGen::editPost($post) }}">
                                                    <i class="fa fa-edit"></i> {{ t('Edit') }}
                                                </a>
                                            @endif
                                            @if ($post->archived==0 && auth()->user()->user_type_id==1)

                                                    <a class="btn btn-sm confirm-action removehoverarchive btn-archive"
                                                       onclick="open_reason_model('{{$post->id}}','{{ url('account/add_archived/' . $post->id . '/offline') }}')"
                                                       href="javascript:void(0)">
                                                        <i class="icon-eye-off"></i> {{ t('Archive') }}
                                                    </a>
                                            @endif
                                            {{-- @if (in_array($pagePath, ['archived']) and $post->user_id==auth()->user()->id and $post->archived==1)--}}
                                            @if (in_array($pagePath, ['archived']))
                                                <a class="btn btn-info btn-sm confirm-action removehoverarchive"
                                                   onclick="repost('{{$post->id}}')" href="javascript:void(0)">
                                                    <i class="fa fa-recycle"></i> {{ t('Repost') }}
                                                </a>
                                            @endif
                                            @if ($pagePath == 'favourite')
                                                <a class="btn btn-primary btn-sm save-job" id="save-{{ $post->id }}"
                                                   style="background-color: white;" href="javascript:void(0)"
                                                   onclick="savePost1(this,<?= $post->id ?>)"><span
                                                            class="fa fa-heart btn-heart"></span></a> <a
                                                        class="btn btn-primary-dark btn-sm email-job"
                                                        data-toggle="modal" data-id="{{ $post->id }}"
                                                        href="#sendByEmail" id="email-{{ $post->id }}"><span
                                                            class="fa fa-share"></span> {{t('Share job')}} </a>
                                            @else
                                                    <a class="btn btn-danger btn-sm removehover"
                                                       href="javascript:void(0)"
                                                       onclick="open_reason_model({{$post->id}},'{{ url('account/' . $pagePath . '/' . $post->id . '/delete') }}')">
                                                        <i class="fa fa-trash"></i> {{ t('Delete') }}
                                                    </a>
                                            @endif

                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php else : ?>
                                    <tr>
                                        <td colspan="4">
                                                <?php if (strpos(url()->current(), 'archived') !== false) { ?>

                                            <?php } else if (strpos(url()->current(), 'favourite') !== false) { ?>
                                            <h6 class="text-muted"
                                                style="text-align: center">{{t("No jobs have been favorited, go to Apply To Jobs and favorite as many jobs as you like!")}}
                                                .</h6>
                                            <?php } else { ?>
                                            <h6 class="text-muted"
                                                style="text-align: center">{{t("You haven't posted any jobs yet")}}
                                                .</h6>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <?php endif; ?>

                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>

                    <nav>
                    {{ (isset($posts)) ? $posts->links() : '' }}
                </div>

            </div>
        </div>
    </div>
    </div>
    </div>
@endsection
<!-- Modal -->

@section('modal_location')
    @parent
    @include('layouts.inc.modal.send-by-email')
    @include('modals.post_archived_or_delete_reasons')

@endsection
@section('after_styles')
    <style>
        .action-td p {
            margin-bottom: 5px;
        }
    </style>
@endsection


@section('after_scripts')
    <script src="{{ asset('js/footable.js?v=2-0-1') }}" type="text/javascript"></script>
    <script src="{{ asset('js/footable.filter.js?v=2-0-1') }}" type="text/javascript"></script>
    <script type="text/javascript">

        function repost(id) {
            if (confirm("Are you sure you want to repost ?")) {
                var myurl = '{{ url('account/'.$pagePath.'/') }}/' + id + '/repost';
                $.ajax({
                    url: myurl,
                    data: "id=" + id,
                    method: "get",
                    success: function (responce) {
                        var url = '<?= url('account/upgrade') ?>';
                        if (responce == 0) {
                            var message = '<?= t("You have reached the maximum amount of Contact"); ?>';
                            const config = {
                                html: true,
                                title: 'Attention',
                                html: message,
                                icon: 'error',
                                allowOutsideClick: false,
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
                        } else if (responce == 1) {
                            var message = '<?= t('Post not found'); ?>';
                            const config = {
                                html: true,
                                title: 'Error',
                                html: message,
                                icon: 'error',
                                allowOutsideClick: false,
                                confirmButtonText: 'ok',
                                showCancelButton: true,
                            };
                            Swal.fire(config);
                            window.location.reload();
                        } else if (responce == 2) {
                            var message = '<?= t("The repost has done successfully"); ?>';
                            const config = {
                                html: true,
                                title: 'Great',
                                html: message,
                                icon: 'success',
                                allowOutsideClick: false,
                                confirmButtonText: 'ok',
                            };
                            Swal.fire(config).then((value) => {
                                window.location.reload();
                            });

                        } else if (responce == 3) {

                            var message = '<?= t("The repost has failed"); ?>';
                            const config = {
                                html: true,
                                title: 'Error',
                                html: message,
                                icon: 'error',
                                allowOutsideClick: false,
                                confirmButtonText: 'ok',

                            };
                            Swal.fire(config).then((value) => {
                                window.location.reload();
                            });
                        } else if (responce == 4) {

                            var message = '<?= t("The ad is already online"); ?>';
                            const config = {
                                html: true,
                                title: 'Error',
                                html: message,
                                icon: 'error',
                                allowOutsideClick: false,
                                confirmButtonText: 'ok',

                            };
                            Swal.fire(config).then((value) => {
                                window.location.reload();
                            });
                        } else {
                            var message = "Please Try Again";
                            const config = {
                                html: true,
                                title: 'Error',
                                html: message,
                                icon: 'error',
                                allowOutsideClick: false,
                                confirmButtonText: 'ok',

                            };
                            Swal.fire(config).then((value) => {
                                window.location.reload();
                            });
                        }

                    }
                })
            } else {

                return false;
            }
        }

        $(function () {
            $('#addManageTable').footable().bind('footable_filtering', function (e) {
                var selected = $('.filter-status').find(':selected').text();
                if (selected && selected.length > 0) {
                    e.filter += (e.filter && e.filter.length > 0) ? ' ' + selected : selected;
                    e.clear = !e.filter;
                }
            });

            $('.clear-filter').click(function (e) {
                e.preventDefault();
                $('.filter-status').val('');
                $('table.demo').trigger('footable_clear_filter');
            });

            $('#checkAll').click(function () {
                checkAll(this);
            });


            $('a.delete-action, button.delete-action').click(function (e) {
                var checkboxes = $('.check');
                var checkedboxes = [];
                $.each(checkboxes, function () {
                    if (this.checked) {
                        checkedboxes.push($(this).val());
                    }
                });

                if (checkedboxes.length) {
                    e.preventDefault(); /* prevents the submit or reload */
                    var confirmation = confirm("{{ t('confirm_this_action') }}");
                    if (confirmation) {
                        if ($(this).is('a')) {
                            var url = $(this).attr('href');
                            if (url !== 'undefined') {
                                redirect(url);
                            }
                        } else {
                            $('form[name=listForm]').submit();
                        }

                    }
                } else {
                    Swal.fire({
                        title: "OOPS!",
                        text: "Please select atleast one Option",
                        icon: "error",
                        button: "Ok",
                    });

                    // alert('Please select atleast one company')
                    e.preventDefault();
                    return false;
                }
            });
        });

        function checkAll(bx) {
            var chkinput = document.getElementsByTagName('input');
            for (var i = 0; i < chkinput.length; i++) {
                if (chkinput[i].type == 'checkbox') {
                    chkinput[i].checked = bx.checked;
                }
            }
        }

        function alertbox() {
            var DetailModal
            document.getElementById('DetailModal');
            $documen
        }

        var lang = {
            labelSavePostSave: "{!! t('Favourite Job') !!}",
            labelSavePostRemove: "{{ t('Favourited Job') }}",
            loginToSavePost: "{!! t('Please log in to save the Ads') !!}",
            loginToSaveSearch: "{!! t('Please log in to save your search') !!}",
            confirmationSavePost: "{!! t('Post saved in favorites successfully') !!}",
            confirmationRemoveSavePost: "{!! t('Post deleted from favorites successfully') !!}",
            confirmationSaveSearch: "{!! t('Search saved successfully') !!}",
            confirmationRemoveSaveSearch: "{!! t('Search deleted successfully') !!}"
        };


        function savePost1(elmt, id) {
            var postId = id;
            $.ajax({
                method: 'POST',
                url: siteUrl + '/ajax/save/post',
                data: {
                    'postId': postId,
                    '_token': $('input[name=_token]').val()
                }
            }).done(function (data) {
                if (typeof data.logged == "undefined") {
                    return false;
                }
                if (data.logged == 0) {
                    $('#quickLogin').modal();
                    return false;
                }
                if (data.status == 1) {
                    Swal.fire({
                        text: lang.confirmationSavePost,
                        icon: "success",
                        button: "Ok",
                    }).then(function () {
                        window.location.reload();
                    });

                } else {
                    Swal.fire({
                        text: lang.confirmationRemoveSavePost,
                        icon: "success",
                        button: "Ok",
                    }).then(function () {
                        location.reload();
                    });
                }
                return false;
            });
            return false;
        }

        function open_reason_model(id, next_url) {
            $('#post_id_for_Reason').val(id);
            $('#url').val(next_url);
            $('#post-archived-reason').modal('show');
        }

        $(document).ready(function () {
            /* Get Post ID */
            $('.email-job').click(function () {
                var postId = $(this).attr("data-id");
                $('input[type=hidden][name=post]').val(postId);
                var url = siteUrl + '/posts/get_post_data/' + postId;
                $.ajax({
                    type: "GET",
                    url: url,
                    dataType: 'json',
                    success: function (c) {
                        if (c) {
                            var fb = document.getElementById(
                                'fb_share'); //or grab it by tagname etc
                            fb.href = "https://www.facebook.com/sharer.php?u=" + c +
                                "&t=HFJ Job";

                            var twitter = document.getElementById(
                                'twitter_share'); //or grab it by tagname etc
                            twitter.href = "https://twitter.com/intent/tweet?url=" + c;

                            var whatsapp = document.getElementById(
                                'whatsapp_share'); //or grab it by tagname etc
                            whatsapp.href = "https://api.whatsapp.com/send?text=" + c;

                            var telegram = document.getElementById(
                                'telegram_share'); //or grab it by tagname etc
                            telegram.href = "https://telegram.me/share/url?url=" + c +
                                "&text=HFJ JOB";


                        }
                    }
                });

            });


            $('.applybutton').click(function (e) {
                var post_id = $(this).data('id');
                var post_url = $(this).data('url');
                var post_body = $(this).data('body');
                $('#post_id').val(post_id);
                $('#post_url').val(post_url);
                $('#post_body').val(post_body);
                $('#applynowform').attr('action', "{{ url('account/apply_post') }}" + "/" + post_id);
                $('#applynowform').submit();
                $('#overlay').show();
            });

            @if(isset($errors) && $errors -> any())
            @if(old('sendByEmailForm') == '1')
            $('#sendByEmail').modal();
            @endif
            @endif
        });
    </script>
@endsection