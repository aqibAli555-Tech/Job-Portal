<style>
    .td-nowrap {
        white-space: normal !important;
    }
</style>

<div class="col-lg-6 col-md-12">
    <div class="card">
        <div class="card-body" style="height: 300px;overflow-y: auto">
            <h4 class="card-title font-weight-bold" style="color:#000 !important">
                <span class="lstick d-inline-block align-middle"></span>{{ trans('admin.post_archived_or_delete_reasons') }}
            </h4>
            <div class="table-responsive mt-3 no-wrap">
                <table class="table v-middle mb-0 table-striped table-sm">
                    <thead>
                    <tr>
                        <th class="border-0">{{ trans('admin.Reason') }}</th>
                        <th class="border-0">{{ trans('admin.Percentage') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($post_archived_reasons as $obj)
                        <tr>
                            <td class="td-nowrap">{{ $obj->title }}</td>
                            <td class="td-nowrap">
                                {{ number_format(!empty($obj->percentage) ? $obj->percentage : 0, 2) }} %
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('dashboard_styles')

@endpush

@push('dashboard_scripts')
@endpush