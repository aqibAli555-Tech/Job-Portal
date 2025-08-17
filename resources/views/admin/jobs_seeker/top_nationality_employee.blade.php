@extends('admin.layouts.master')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Top Nationalities Employees</h4>
                <div class="table-responsive">
                    <table id="default_order" class="table table-striped table-sm table-bordered no-wrap">
                        <thead>
                        <tr>
                            <th class="border-0">{{ trans('admin.Nationality') }}</th>
                            <th class="border-0">{{ trans('admin.Number of users') }}</th>
                        </tr>
                        </thead>
                        <tbody>

                        @if (!empty($top_nationality_employees))
                        @foreach ($top_nationality_employees as $user)
                        <?php $employee_url = admin_url() . '/job-seekers?nationality=' . $user->id; ?>

                        <tr>
                            <td class="td-nowrap">
                                <a href='<?php echo $employee_url; ?>'>
                                    {{ $user->name }}
                                </a>
                            </td>
                            <td class="td-nowrap">
                                <a href='<?php echo $employee_url; ?>'>
                                    {{ $user->count }}
                                </a>
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
        </div>
    </div>
</div>
@endsection
