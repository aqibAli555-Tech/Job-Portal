@extends('admin.layouts.master')

@section('content')

<?php
$total_sent = 0;
$total_deliverd = 0;
$total_opened = 0;
$total_clicked = 0;
$total_failed = 0;
$EmailStatsColor = [];
foreach ($email_stats->getStats() as $key => $email_stat) {
    $total_sent += $email_stat->getAccepted()['total'];
    $total_deliverd += $email_stat->getDelivered()['total'];
    $total_opened += $email_stat->getOpened()['total'];
    $total_clicked += $email_stat->getClicked()['total'];
    $total_failed += $email_stat->getFailed()['permanent']['total'];
}

//                $emailStats[] = ['Total Sent',$total_sent];
$emailStats[] = ['Delivered', $total_deliverd];
$emailStats[] = ['Opened', $total_opened];
$emailStats[] = ['Clicked', $total_clicked];
$emailStats[] = ['Failed', $total_failed];
//                $EmailStatsColor[] = '#6076e8';
$EmailStatsColor[] = '#02cbf1';
$EmailStatsColor[] = '#22ca80';
$EmailStatsColor[] = '#fdc16a';
$EmailStatsColor[] = '#ff4f70';
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                @if (Session::has('flash_notification'))
                <div class="col-xl-12">
                    @include('flash::message')
                </div>
                @endif
                <div class="card card-hover">
                    <div class="p-2 bg-primary text-center">
                        <h1 class="font-light text-white">{{$total_sent}}</h1>
                        <h6 class="text-white">Total Sent</h6>
                    </div>
                </div>
                    <div class="row">
                        <?php foreach ($emailStats as $key => $stats) {
                            $percentage = round(((int)$stats[1] / (int)$total_sent) * 100, 2);
                            $percentage = min($percentage, 100); // Ensure percentage doesn't exceed 100
                            ?>
                        <div class="col-3 text-center">
                            <div class="p-2 text-center" style="background:<?= $EmailStatsColor[$key]; ?>">
                                <h1 class="font-light text-white">
                                        <?= $stats[1] ?>
                                    <small class="font-14 font-weight-bold">
                                        ( <?= $percentage ?>% )
                                    </small>
                                </h1>
                                <h6 class="text-white"><?= $stats[0] ?></h6>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
            </div>
            <div class="card-footer">
                <a href="{{admin_url('mailgun-failed-emails')}}" class="btn btn-danger"> Failed Emails Data</a>
            </div>
        </div>
    </div>
</div>

<div class="col-12">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">{{ trans('admin.email stats') }}</h4>
            <table class="table table-striped table-sm table-bordered" id="zero_config">
                <thead>
                <tr>
                    <th>#</th>
                    <th>{{ trans('admin.Accepted') }}</th>
                    <th>{{ trans('admin.Delivered') }}</th>
                    <th>{{ trans('admin.Opened') }}</th>
                    <th>{{ trans('admin.Clicked') }}</th>
                    <th>{{ trans('admin.Failed') }}</th>
                    <th>{{ trans('admin.date') }}</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($sorted_stats as $key => $item) {
                    echo '<tr>';
                    $counter = $key + 1;
                    echo '<td>' . $counter . '</td>';
                    echo '<td><span class="badge badge-success"> Incoming: ' . $item->getAccepted()['incoming'] . '  </span>&nbsp;<span class="badge text-bg-dark"> Outgoing: ' . $item->getAccepted()['outgoing'] . ' </span>&nbsp;<span class="badge text-bg-warning">   Total: ' . $item->getAccepted()['total'] . '  </span></td>';
                    echo '<td>' . $item->getDelivered()['total'] . '</td>';
                    echo '<td>' . $item->getOpened()['total'] . '</td>';
                    echo '<td>' . $item->getClicked()['total'] . '</td>';
                    echo '<td>' . $item->getFailed()['permanent']['total'] . '</td>';
                    echo '<td>' . date('Y-m-d H:i:s', $item->getTime()->getTimestamp()) . '</td>';
                    echo '</tr>';
                } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
@endsection