@extends('admin.layouts.master')

@section('content')
<div class="row" id="analytics-container">
<div class="col-12">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">{{ trans('admin.analytics') }}</h4>
        </div>
    </div>
</div>
</div>
<div class="d-flex justify-content-center align-items-center">
    <div class="spinner-border text-muted loading" role="status"></div>
</div>
@endsection

@section('after_scripts')
<script>
$(document).ready(function() {
    var translations = @json(trans('global'));
    $.ajax({
        type: "GET",
        url: "{{URL('/')}}/admin/analytics-data",
        success: function(response) {
            $('.loading').hide();
            $.each(response.data, function(page, records) {
                var translatedPage = translations[page] || page;                
                var pageContent = `
                    <div class="col-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <i class="fas fa-info-circle"></i> ${translatedPage}
                                    <a href="{{ admin_url('get-page-analytics-details?type=') }}${page}" class="float-right btn btn-primary btn-sm text-white d-inline-block">
                                        <strong>View All</strong>
                                    </a>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="crudTable" class="table table-bordered table-striped display dt-responsive nowrap dataTable dtr-inline collapsed" role="grid" aria-describedby="crudTable_info">
                                        <thead>
                                            <tr role="row">
                                                <th data-orderable="false" class="dt-checkboxes-cell dt-checkboxes-select-all sorting_disabled" style="width: 10%; padding-right: 10px;">#</th>
                                                <th data-orderable="true" class="sorting" style="width: 40%;">{{ trans('admin.date') }}</th>
                                                <th data-orderable="true" class="sorting" style="width: 20%;">{{ trans('admin.Visitors') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>`;
                
                if (records.length > 0) {
                    $.each(records, function(index, item) {
                        var rowClass = (index % 2 === 0) ? 'even' : 'odd';
                        pageContent += `
                            <tr role="row" class="${rowClass}">
                                <td>${index + 1}</td>
                                <td>${new Date(item.date).toLocaleDateString()}</td>
                                <td>${item.count}</td>
                            </tr>`;
                    });
                } else {
                    pageContent += `
                        <tr>
                            <td colspan="3" class="text-center">{{ trans('admin.no_data_available') }}</td>
                        </tr>`;
                }

                pageContent += `
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>`;
                
                $('#analytics-container').append(pageContent);
            });
        },
        error: function() {
            alert('Error loading data');
        }
    });
});
</script>
@endsection
