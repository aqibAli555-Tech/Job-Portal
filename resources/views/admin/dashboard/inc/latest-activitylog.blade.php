<div class="col-lg-12 col-md-12">
    <div class="card border-dark">
        <div class="card-body">
            <h4 class="card-title">{{ trans('admin.Latest Activity Logs') }}</h4>
            <div class="table-responsive mt-3 ">
                <table class="table v-middle mb-0 table-sm">
                    <tbody>
                    @if($latestactivitilog->count() > 0)
                        @foreach($latestactivitilog as $logs)
                            <tr style="background-color: {{ $logs->user_id != 0 ? '#D2FFF7' : '' }};">
                                <td># {{$logs->id}}</td>
                                <td>{{ $logs->created_at }}</td>
                                <td class="font-10"><?= $logs->description; ?></td>
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
        <div class="card-footer">
            <a href="{{ admin_url('get_logs') }}"
               class="float-right btn btn-primary btn-sm text-white d-inline-block"><strong>View All</strong></a>
        </div>
    </div>
</div>

@push('dashboard_styles')
    <style>
        .td-nowrap {
            width: 10px;
            white-space: nowrap;
        }
    </style>
@endpush

@push('dashboard_scripts')
@endpush