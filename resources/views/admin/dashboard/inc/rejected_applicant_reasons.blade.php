<div class="col-lg-6 col-md-12">
    <div class="card">
        <div class="card-body" style="height: 300px;overflow-y: auto">
            <h4 class="card-title">
                {{ trans('admin.Rejection Reasons') }}
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

                    @if($rejected_reasons->count() > 0)
                    @foreach ($rejected_reasons as $obj)
                    <tr>
                        <td class="td-nowrap">{{ $obj->title }}</td>
                        <td class="td-nowrap">{{ !empty($obj->percentage)? number_format($obj->percentage,2):0}} %</td>
                    </tr>
                    @endforeach

                    @else
                    <tr>
                        <td colspan="5">
                            {{ trans('admin.No data found') }}
                        </td>
                    </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>