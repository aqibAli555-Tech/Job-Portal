<div class="col-lg-12 col-md-12">
    <div class="card">
        <div class="card-body" style="height: 400px;overflow-y: auto">
            <h4 class=" card-title font-weight-bold">
                {{ trans('admin.Latest Contact Us Messages') }}
            </h4>
            <div class="table-responsive">
                <table class="table v-middle mb-0 table-striped table-sm">
                    <thead>
                    <tr>
                        <th>{{ trans('admin.Name') }}</th>
                        <th>{{ trans('admin.Message') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if ($latestContacts->count() > 0)
                    @foreach($latestContacts as $user)
                    <tr>
                        <td>
                            <span class="text-dark font-weight-bold">{{ $user->first_name}} {{$user->last_name}}</span>
                            <br>{{ $user->email }}
                            <br>{{ $user->phone }}
                        </td>
                        <td >{{ $user->message }}</td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="5" class="text-center">
                            <img src="{{url('public/assets/images/norecord.png')}}" width="200px">
                            <p>No Record found</p>
                        </td>
                    </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer text-center">
            <a href="{{ admin_url('contact_us') }}"  class=" btn btn-primary btn-sm text-white d-inline-block"><strong>View All</strong></a>
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
