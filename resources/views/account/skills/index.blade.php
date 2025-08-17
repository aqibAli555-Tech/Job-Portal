@extends('layouts.master')

@section('content')
    @includeFirst([config('larapen.core.customizedViewPath') . 'common.spacer', 'common.spacer'])
   
    <div class="main-container">
        <div class="container">
            {{-- @include('account/inc/breadcrumbs') --}}
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
                    @includeFirst([
                        config('larapen.core.customizedViewPath') . 'account.inc.sidebar',
                        'account.inc.sidebar',
                    ])
                </div>

                <div class="col-md-9 page-content">
                    <div class="inner-box">
                            <div class="row">
                                <div class="col-6">
                                    <button type="button" class="btn btn-primary " data-toggle="modal"
                                        data-target="#myModal">
                                        <i class="icon-plus"></i> {{ t('Create Skills Sets') }}
                                    </button>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <form method="GET" action="{{ url('account/skill_set') }}">
                                            <div class="row">
                                              <div class="col-md-2 control-label text-right search pt-2 d-none d-md-block">
                                                <label>{{ t('Search') }}</label>
                                              </div>
                                              <div class="col-12 col-md-10">
                                                <input type="text" name="search" placeholder="Search Skills Sets" class="form-control filter-new-company" id="filter">
                                                <a title="clear filter" class="clear-filter"><i class="fa fa-times"></i></a>
                                              </div>
                                            </div>
                                          </form>
                                    </div>
                                </div>
                            </div>
                        <div class="table-responsive">
                            <table id="addManageTable" class="table company-table table demo" data-filter="#filter"
                                data-filter-text-only="true">
                                <thead>
                                    <tr>
                                        <th> {{ t('skill_set') }}</th>
                                        <th data-sort-ignore="true"> {{ t('Status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!empty($EmployeeSkill))
                                        @foreach ($EmployeeSkill as $Skill)
                                            <tr class="job-items">
                                                <td>{{ $Skill->skill }}</td>
                                                <td>
                                                    @if ($Skill->status == 0)
                                                        <span class="badge badge-warning">
                                                            Pending
                                                        </span>
                                                    @endif
                                                    @if ($Skill->status == 1)
                                                        <span class="badge badge-success">
                                                            Approved
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr class="job-items">
                                            <td colspan="5">
                                                <h6 class="text-muted" style="text-align: center">
                                                    {{ t('You don’t have any skills sets yet') }}</h6>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="pagination-bar text-center">
                            {{-- {{ (isset($EmployeeSkill)) ? $EmployeeSkill->links() : '' }} --}}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form name="listForm" method="POST" action="{{ url('account/skill_set') }}">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">{{ t('Create Skills Sets') }}</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <input type="text" name="skill" class="form-control" placeholder="Enter Skills Sets">
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-success" >Save</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
          </div>
            </form>
        </div>
      </div>
@endsection

@section('after_scripts')
    <script src="{{ url('assets/js/footable.js?v=2-0-1') }}" type="text/javascript"></script>
    <script src="{{ url('assets/js/footable.filter.js?v=2-0-1') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $(function() {
            $('#addManageTable').footable().bind('footable_filtering', function(e) {
                var selected = $('.filter-status').find(':selected').text();
                if (selected && selected.length > 0) {
                    e.filter += (e.filter && e.filter.length > 0) ? ' ' + selected : selected;
                    e.clear = !e.filter;
                }
            });

            $('.clear-filter').click(function(e) {
                e.preventDefault();
                $('.filter-status').val('');
                $('table.demo').trigger('footable_clear_filter');
            });

            $('#checkAll').click(function() {
                checkAll(this);
            });


            $(".delete").click(function() {
                if (!confirm("Are you sure you want to delete your company?")) {
                    return false;
                }
            });


            $('a.delete-action, button.delete-action').click(function(e) {
                var checkboxes = $('.check');
                var checkedboxes = [];
                $.each(checkboxes, function() {
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
                beforeSend: function() {
                    // $('#overlay').show();
                },
                success: function(c) {
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
                            } else {}
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
            function(event) {
                if (event.which == '13') {
                    event.preventDefault();
                }
            });
    </script>
@endsection
