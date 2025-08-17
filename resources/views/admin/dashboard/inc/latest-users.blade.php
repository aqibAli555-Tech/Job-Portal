<div class="col-lg-6 col-md-12">
    <div class="card border-dark">
        <div class="card-body">
            <h4 class="card-title">
                {{ trans('admin.Latest Employees') }}
            </h4>
            <div class="table-responsive">
                <table class="table v-middle mb-0 table-striped table-sm">
                    <thead>
                        <tr>
                            <th class="border-0">{{ trans('admin.ID') }}</th>
                            <th class="border-0">{{ trans('admin.Name') }}</th>
                            <th class="border-0">{{ mb_ucfirst(trans('admin.country')) }}</th>
                            <th class="border-0">{{ trans('admin.Status') }}</th>
                            <th class="border-0">{{ trans('admin.Date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($latestUsers->count() > 0)
                            @foreach ($latestUsers as $user)
                                <tr>
                                    <td class="td-nowrap">{{ $user->id }}</td>
                                    <td>
                                        {{ $user->name }}

                                    </td>

                                    <td class="td-nowrap">
                                        <img data-toggle="tooltip" data-placement="top"
                                            title="{{ $user->country->name }}"
                                            src="{{ url()->asset('images/flags/16/' . strtolower($user->country_code) . '.png') }}"> {{ $user->country->name }}
                                    </td>
                                    <td class="td-nowrap">
                                        @if (isVerifiedUser($user))
                                            <span class="text-success">{{ trans('admin.Activated') }}</span>
                                        @else
                                            <span
                                                class="text-danger">{{ trans('admin.Unactivated') }}</span>
                                        @endif
                                    </td>
                                    <td class="td-nowrap">
                                        <div class="sparkbar" data-color="#00a65a" data-height="20">
                                            {{ \App\Helpers\Date::format($user->created_at, 'datetime') }}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5">
                                    {{ trans('admin.No users found') }}
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer text-center">
            <a href="{{ admin_url('job-seekers') }}"
               class="btn btn-primary btn-sm text-white d-inline-block"><strong>View
                    All</strong></a>
        </div>
    </div>
</div>
<div class="col-lg-6 col-md-12">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                {{ trans('admin.Latest Employers') }}
            </h4>
            <div class="table-responsive">
                <table class="table v-middle mb-0 table-striped table-sm">
                    <thead>
                        <tr>
                            <th class="border-0">{{ trans('admin.ID') }}</th>
                            <th class="border-0">{{ trans('admin.Name') }}</th>
                            <th class="border-0">{{ mb_ucfirst(trans('admin.country')) }}</th>
                            <th class="border-0">{{ trans('admin.Status') }}</th>
                            <th class="border-0">{{ trans('admin.Date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($latestEmployeers->count() > 0)
                            @foreach ($latestEmployeers as $user)
                                <tr>
                                    <td class="td-nowrap">{{ $user->id }}</td>
                                    <td>
                                        {{ $user->name }}

                                    </td>

                                    <td class="td-nowrap">
                                        <img data-toggle="tooltip" data-placement="top"
                                            title="{{ $user->country->name }}"
                                            src="{{ url()->asset('images/flags/16/' . strtolower($user->country_code) . '.png') }}"> {{ $user->country->name }}
                                    </td>
                                    <td class="td-nowrap">
                                        @if (isVerifiedUser($user))
                                            <span class="text-success">{{ trans('admin.Activated') }}</span>
                                        @else
                                            <span
                                                class="text-danger">{{ trans('admin.Unactivated') }}</span>
                                        @endif
                                    </td>
                                    <td class="td-nowrap">
                                        <div class="sparkbar" data-color="#00a65a" data-height="20">
                                            {{ \App\Helpers\Date::format($user->created_at, 'datetime') }}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5">
                                    {{ trans('admin.No users found') }}
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer text-center">
            <a href="{{ admin_url('employer') }}" target="_blank" class="btn btn-primary btn-sm text-white d-inline-block"><strong>View All</strong></a>
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
