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
                        <th>{{ trans('admin.Country') }}</th>
                        <th >{{ trans('admin.Number of users') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if ($top_country_employers ->count() > 0)
                    @foreach($top_country_employers as $user)
                    <tr><?php $company_url = admin_url() . '/employer?country_code=' . $user->code; ?>
                        <td>
                            <a href='<?php echo $company_url; ?>'>
                                <img data-toggle="tooltip" data-placement="top" title="{{$user->name}}" src="{{ url()->asset('images/flags/16/' . strtolower($user->code) . '.png') }}">
                         
                            {{ $user->name }}
                            </a>
                        </td>
                        <td> <a href='<?php echo $company_url; ?>'>{{ $user->count }}</a></td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="2">
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
</div>
@endsection