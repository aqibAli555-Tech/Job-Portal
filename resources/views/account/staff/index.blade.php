{{--
* JobClass - Job Board Web Application
* Copyright (c) BedigitCom. All Rights Reserved
*
* Website: https://bedigit.com
*
* LICENSE
* -------
* This software is furnished under a license and may be used and copied
* only in accordance with the terms of such license and with the inclusion
* of the above copyright notice. If you Purchased from CodeCanyon,
* Please read the full License from here - http://codecanyon.net/licenses/standard
--}}
@extends('layouts.master')
@section('content')
@includeFirst([config('larapen.core.customizedViewPath') . 'common.spacer', 'common.spacer'])
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
                @includeFirst([config('larapen.core.customizedViewPath') . 'account.inc.sidebar', 'account.inc.sidebar'])
            </div>
            <!--/.page-sidebar-->

            <!-- add custom css code and also add the icon -->
            <div class="col-md-9 page-content">
                <div class="inner-box">
                    <form name="listForm" method="POST" action="{{ url('account/companies/delete') }}">
                        {!! csrf_field() !!}
                        <div>
                            <label for="checkAll" style="display: none">
                                <input type="checkbox" id="checkAll">
                                {{ t('Select') }}: {{ t('All') }} |
                                <button type="submit" class="btn btn-sm btn-default delete-action">
                                    <i class="fa fa-trash"></i> {{ t('Delete') }}
                                </button>
                            </label>
                            <div class="row">
                                <div class="col-6">
                                    <a href="{{ url('account/staff/create') }}"
                                       class="btn btn-primary create-company"><i
                                                class="icon-plus"></i> {{ t('Create staff') }}</a>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-2 control-label text-right search pt-2 d-none d-md-block">
                                                <label>{{ t('Search') }}</label>
                                            </div>
                                            <div class="col-12 col-md-10">
                                                <input type="text" placeholder="Search"
                                                       class="form-control filter-new-company" id="filter">
                                                <a title="clear filter" class="clear-filter" href="#"><i
                                                            class="fa fa-times"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table id="addManageTable" class="table company-table table demo"
                               data-filter="#filter" data-filter-text-only="true">
                            <thead>
                            <tr>
                                <th> {{ t('Name') }}</th>
                                <th data-sort-ignore="true"> {{ t('Email') }}</th>
                                <th data-type="numeric"> {{ t('Phone') }}</th>
                                <th> {{ t('Option') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($staffs) > 0)
                            @foreach($staffs as $staff)

                            <tr>
                                <td>{{ $staff->name }}</td>
                                <td>{{ $staff->email }}</td>
                                <td>{{ $staff->phone }}</td>
                                <td>
                                    <a class="btn btn-primary btn-sm" href="{{ url('account/staff/'.$staff->id.'/permissions') }}">
                                        <i class="fa fa-edit"></i> Permission
                                    </a>
                                    <a class="btn btn-primary btn-sm" href="{{ url('account/staff/'.$staff->id.'/edit') }}">
                                        <i class="fa fa-edit"></i> {{ t('Edit') }}
                                    </a>
                                    <a class="btn btn-danger btn-sm delete" href="{{ url('account/staff/'.$staff->id.'/delete') }}">
                                        <i class="fa fa-trash"></i> {{ t('Delete') }}
                                    </a>
                                    <a class="btn btn-primary btn-sm " href="{{ url('account/staff/'.$staff->id.'/change_password') }}">
                                        <i class="fa fa-edit"></i> {{ t('change_password') }}
                                    </a>
                                </td>
                            </tr>

                            @endforeach
                            @else
                            <tr class="job-items">
                                <td colspan="5">
                                    <h6 class="text-muted"
                                        style="text-align: center">{{t('You haven’t added any of your own staff yet.You can click Create Staff above to give your staff access to this account and choose what they can access and what they cannot access')}}</h6>
                                </td>
                            </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination-bar text-center">
                        {{ (isset($staffs)) ? $staffs->links() : '' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('after_scripts')

<script type="text/javascript">
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


        $(".delete").click(function () {
            if (!confirm("Are you sure you want to delete your staff?")) {
                return false;
            }
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
                // alert('Please select atleast one company')
                swal({
                    title: "OOPS!",
                    text: "Please select atleast one staff",
                    icon: "error",
                    button: "Ok",
                });
                e.preventDefault();
                return false;
            }
        });
    });

</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- include custom script for ads table [select all checkbox]  -->
<script>

    function getCompanydetail(id) {
        var companyid = id;
        var url = siteUrl + '/posts/get_company_by_id/' + companyid;
        $.ajax({
            type: "GET",
            url: url,
            dataType: 'json',
            beforeSend: function () {
                // $('#overlay').show();
            },
            success: function (c) {
                if (c.lowCredit == 1) {
                    const config = {
                        html: true,
                        title: 'Error',
                        html: c.lowCreditMessage,
                        icon: 'error',
                        allowOutsideClick: false,
                        confirmButtonText: 'Upgrade',
                        showCancelButton: true,
                    };
                    Swal.fire(config).then(callback);

                    function callback(result) {
                        if (result.value) {
                            var to_upgrade = true;
                            login_company(c.password, c.email, to_upgrade);
                        } else {
                        }
                    }
                } else {
                    window.location.replace(siteUrl + '/posts/create/?company=' + companyid);

                }

            },
        });
    }

    function checkAll(bx) {
        var chkinput = document.getElementsByTagName('input');
        for (var i = 0; i < chkinput.length; i++) {
            if (chkinput[i].type == 'checkbox') {
                chkinput[i].checked = bx.checked;
            }
        }
    }

    $(document).keypress(
        function (event) {
            if (event.which == '13') {
                event.preventDefault();
            }
        });
</script>
@endsection
