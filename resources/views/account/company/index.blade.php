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
                                        <a href="{{ url('account/companies/create') }}"
                                           class="btn btn-primary create-company"><i
                                                    class="icon-plus"></i> {{ t('Create company') }}</a>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-2 control-label text-right search pt-2 d-none d-md-block">
                                                    <label>{{ t('Search') }}</label>
                                                </div>
                                                <div class="col-12 col-md-10">
                                                    <input type="text" placeholder="Search Companies"
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
                        <p style="text-align: center;">{{ t('employer_company_page_text') }}</p>
                        <div class="table-responsive">
                            <table id="addManageTable" class="table company-table table demo" data-filter="#filter"
                                   data-filter-text-only="true">
                                <thead>
                                <tr>
                                    <th> {{ t('Logo') }}</th>
                                    <th data-sort-ignore="true"> {{ t('Company Name') . ' / ' . t('Description') }}</th>
                                    <th data-type="numeric">{{ t('Ads') }}</th>
                                    <th> {{ t('Option') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if (isset($companies) && $companies->count() > 0) :
                                foreach ($companies as $key => $company) :
                                    ?>
                                <tr>

                                    <td class="add-img-td">
                                            <?php if (Session::get('impersonate') == 1) { ?>
                                        <a href=" {{ url('impersonate/leave/?user_id='.$company->c_id.'&from_parent_company=1') }}">
                                                <?php
                                                $logo_show = App\Helpers\Helper::get_company_logo_AWS($company);
                                                ?>
                                            <div class="user-image-div"
                                                 style="background-image:url('{{$logo_show}}')">
                                        </a>
                                        <?php } else { ?>
                                        <a href=" {{ url('impersonate/leave/?user_id='.$company->c_id.'&from_parent_company=1') }}">
                                                <?php
                                                $logo_show = App\Helpers\Helper::get_company_logo_AWS($company);
                                                ?>
                                            <img class="img-thumbnail img-fluid" style="width: 85px"
                                                 src="{{$logo_show}}" alt="img">
                                        </a>
                                        <?php } ?>
                                    </td>
                                    <td style="width:48%" class="items-details-td">
                                        <strong>
                                                <?php
                                            if (Session::get('impersonate') == 1) {
                                                ?>
                                            <a href="{{ url('impersonate/leave/?user_id='.$company->c_id.'&from_parent_company=1') }}">
                                                {{ \Illuminate\Support\Str::limit($company->name, 40) }}
                                            </a>
                                            <?php } else {
                                                ?>
                                            <a href="{{ url('impersonate/leave/?user_id='.$company->c_id.'&from_parent_company=1') }}">
                                                {{ \Illuminate\Support\Str::limit($company->name, 40) }}
                                            </a>
                                            <?php } ?>

                                        </strong>
                                        <br>
                                        {{ \Illuminate\Support\Str::limit($company->description, 100) }}
                                    </td>
                                    <td style="width:16%" class="price-td">
                                        <div>
                                            <strong>
                                                <a href="{{ \App\Helpers\UrlGen::company(null, $company->id) }}">
                                                    {{ $company->posts()->count() }}
                                                </a>
                                            </strong>
                                        </div>
                                    </td>
                                    <td style="width:20%" class="action-td" nowrap>
                                        <div>
                                            @if ($company->user_id==$user->id)
                                                <a class="btn btn-primary btn-sm"
                                                   onclick="getCompanydetail('{{$company->id}}')"
                                                   href="javascript:void(null)">
                                                    <i class="fa fa-plus"></i> {{ t('Post a Job') }}
                                                </a>
                                                <a class="btn btn-primary btn-sm"
                                                   href="{{ url('account/companies/' . $company->id . '/edit') }}">
                                                    <i class="fa fa-edit"></i> {{ t('Edit') }}
                                                </a>
                                                @if(auth()->user()->id != $company->c_id)
                                                    <a class="btn btn-danger btn-sm delete"
                                                       href="{{ url('account/companies/' . $company->id . '/delete') }}">
                                                        <i class="fa fa-trash"></i> {{ t('Delete') }}
                                                    </a>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php else : ?>
                                <tr class="job-items">
                                    <td colspan="5">
                                        <h6 class="text-muted"
                                            style="text-align: center">{{t('You don’t have any companies yet')}}</h6>
                                    </td>
                                </tr>
                                <?php endif; ?>

                                </tbody>
                            </table>
                        </div>
                        <div class="pagination-bar text-center">
                            {{-- {{ (isset($companies)) ? $companies->links() : '' }} --}}
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
                if (!confirm("Are you sure you want to delete your company?")) {
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
                        text: "Please select atleast one company",
                        icon: "error",
                        button: "Ok",
                    });
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>

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
                                var myurl = siteUrl + '/account/upgrade';
                                window.location.replace(myurl);
                            } else {
                            }
                        }
                    } else {
                        var myurl = siteUrl + '/posts/create/?company=' + companyid;
                        window.location.replace(myurl);
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