@extends('admin.layouts.master')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ t('Top Countries Employees') }}</h4>
                <div class="table-responsive">
                    <table id="default_order" class="table table-striped  table-sm table-bordered no-wrap">
                        <thead>
                        <tr>
                            <th class="border-0">{{ trans('admin.Country') }}</th>
                            <th class="border-0">{{ trans('admin.Number of users') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if ($top_country_employees->count() > 0)
                        @foreach ($top_country_employees as $user)
                        <tr>
                            <td class="td-nowrap">

                                <?php
                                    $country=\App\Models\Country::get_country_by_code($user->code);
                                    $employee_url = admin_url() . '/job-seekers?country_code=' . $country->id; ?>
                                <a href='<?php echo $employee_url; ?>'>
                                    <img data-toggle="tooltip" data-placement="top"
                                         title="{{ $user->name }}"
                                         src="{{ url()->asset('images/flags/16/' . strtolower($user->code) . '.png') }}">

                                    {{ $user->name }}
                                </a>
                            </td>
                            <td class="td-nowrap"><a href='<?php echo $employee_url; ?>'>{{ $user->count }}</a></td>
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
