@extends('layouts.master')
@section('content')
@include('common.spacer')

<div class="main-container">
    <div class="container">
        @include('account/inc/breadcrumbs')
        <div class="row">
            <div class="col-md-3 page-sidebar">
                @include('account.inc.sidebar')
            </div>
            <div class="col-md-9 page-content">
                <div class="inner-box">
                    <h2 class="title-2"> {{ t('Who viewed your cv') }}</h2>
                    <br>
                    <div class="table-responsive">
                        <table class="table">
                            {!! csrf_field() !!}
                            <thead>
                                <tr>
                                    <th data-type="numeric" data-sort-initial="true">{{t('Description')}}
                                    </th>
                                    <th data-type="numeric" data-sort-initial="true"> {{t('Date')}}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($data['unlock_users']))
                                    <?php foreach ($data['unlock_users'] as $value) { ?>
                                        <tr class="job-items">
                                            <td class="title"><?php echo str_replace("?", "’", $value->description); ?></td>
                                            <td class="title">{{$value->created_at->format('Y-m-d')}}</td>
                                        </tr>
                                    <?php } ?>
                                @endif
                                @if(empty($data['unlock_users']->count()))
                                <tr class="job-items">
                                    <td colspan="6">
                                        <h6 class="text-muted" style="text-align: center">{{t('So far no employers (companies) have viewed your CV, make sure you apply to jobs to show interest so employers (companies) may choose to view your CV and contact you')}}.</h6>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                        {!! $data['unlock_users']->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection