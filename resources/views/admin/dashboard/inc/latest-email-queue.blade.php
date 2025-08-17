<div class="col-lg-6 col-md-12">
    <div class="card">
        <div class="card-body" style="height: 300px;overflow-y: auto">
            <h4 class="card-title font-weight-bold">
                {{ trans('admin.Latest Emails') }}
            </h4>
            <div class="table-responsive mt-3 no-wrap">
                <table class="table v-middle mb-0 table-striped table-sm">
                    <thead>
                    <tr>
                        <th class="border-0">{{ trans('admin.Created At') }}</th>
                        <th class="border-0">{{ trans('admin.Emails') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($latestemailqueue->count() > 0)
                    @foreach ($latestemailqueue as $email)
                    <tr>
                        <td class="td-nowrap">{{ $email->date }}</td>
                        <td class="td-nowrap">{{ $email->count}}</td>
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
        <div class="card-footer text-center">
            <a href="{{ admin_url('Contact_Card_Problems') }}" class="btn btn-primary btn-sm text-white d-inline-block"><strong>View All</strong></a>
        </div>
    </div>
</div>