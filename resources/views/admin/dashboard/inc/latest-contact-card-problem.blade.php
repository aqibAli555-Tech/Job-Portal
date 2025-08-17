<div class="col-lg-6 col-md-12">
    <div class="card">
        <div class="card-body" style="height: 300px;overflow-y: auto">
            <h4 class="card-title font-weight-bold">
                {{ trans('admin.Latest Contact Card Problems') }}
            </h4>
            <div class="table-responsive mt-3 no-wrap">
                <table class="table v-middle mb-0 table-striped table-sm">
                    <thead>
                    <tr>
                        <th class="border-0">{{ trans('admin.ID') }}</th>
                        <th class="border-0">{{ trans('admin.Name') }}</th>
                        <th class="border-0">{{ trans('admin.Company') }}</th>
                        <th class="border-0">{{ trans('admin.Created At') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($latestcontactcardproblems->count() > 0)
                    @foreach($latestcontactcardproblems as $user)
                    <tr>
                        <td class="td-nowrap">{{ $user->id }}</td>
                        <td class="td-nowrap">{{ $user->name}}</td>
                        <td class="td-nowrap">{{ $user->company }}</td>
                        <td class="td-nowrap">{{ $user->created_at }}</td>

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
            <a href="{{ admin_url('Contact_Card_Problems') }}" class="btn btn-primary btn-sm text-white d-inline-block"><strong>View All</strong></a>
        </div>
    </div>
</div>