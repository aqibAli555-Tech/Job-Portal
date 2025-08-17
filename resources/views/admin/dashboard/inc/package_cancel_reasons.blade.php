<div class="col-lg-6 col-md-12">
    <div class="card">
        <div class="card-body" style="height: 300px;overflow-y: auto">
            <h4 class="card-title">
                {{ trans('admin.package_cancel_reasons') }}
            </h4>
            <div class=" mt-3">
                <table class="table v-middle mb-0 table-striped table-sm">
                    <thead>
                    <tr>
                        <th>{{ trans('admin.Reason') }}</th>
                        <th>{{ trans('admin.Percentage') }}</th>
                    </tr>
                    </thead>
                    <tbody>

                    @if($package_cancel_reasons->count() > 0)
                    @foreach ($package_cancel_reasons as $obj)
                    <tr>
                        <td>{{ $obj->cancel_reason }}</td>
                        <td >{{ !empty($obj->percentage)? number_format($obj->percentage,2):0}} %</td>
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