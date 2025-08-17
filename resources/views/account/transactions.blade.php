@extends('layouts.master')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<style>
    .form-control:disabled, .form-control[readonly]
    {
        background-color:#ffffff !important;
    }
</style>
<?php
    $searchNew = !empty(request('search_new')) ? request('search_new') : '';
    $searchDate = !empty(request('search_date')) ? request('search_date') : '';
?>
@include('common.spacer')
<div class="main-container">
    <div class="container">
        @include('account/inc/breadcrumbs')

        <div class="row">
            @if (Session::has('flash_notification'))
            <div class="col-xl-12">
                <div class="row">
                    <div class="col-xl-12">
                        @include('flash::message')
                    </div>
                </div>
            </div>
            @endif
            <div class="col-md-3 page-sidebar">
                @include('account.inc.sidebar')
            </div>


            <!--/.page-sidebar-->

            <div class="col-md-8 page-content">
                <div class="inner-box">
                    <h2 class="title-2"><i class=""></i> {{ t('Transactions') }}
                        <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="top" title="{{t('List of all payments')}}" hidden></i>
                    </h2>
                    @if(!empty(auth()->user()->save_card_id) && !empty(auth()->user()->tap_customer_id) && !empty(auth()->user()->tap_agreement_id) )
                    <div style="clear:both">
                        <a href="javascript:void(null)" class="btn btn-primary btn-sm" onclick="approve()" style="float: right;margin-bottom: 10px;">{{T('Cancel Subscription')}}</a>
                    </div>
                    @endif
                    <br><br><br>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="search" id="search_new" value="<?= $searchNew ?>" class="form-control" name="search_new" placeholder="Search By Amount">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <select class="form-control" name="package" id="package">
                                    <option value="">Select a package</option>
                                    @if (!empty($packages))
                                        @foreach ($packages as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <!--<input type="date" id="search_date" value="" class="form-control" name="search_date" placeholder="Search By Date">-->
                                <input type="text" id="search_date" class="form-control" name="search_date" placeholder="Search By Date">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <button type="button" class="btn btn-primary" onclick="resetFields()">Reset</button>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="table-responsive">
                        <table class="table table-striped table-sm table-bordered datatables-transaction"
                            data-url="{{ url('account/transaction-ajax') }}">
                            <thead>
                            <tr>
                                <th>{{ t('Sr') }}</th>
                                <th>{{ t('Package') }}</th>
                                <th>{{ t('Amount') }}</th>
                                <th>{{ t('Date') }}</th>
                                <th>{{ t('Action') }}</th>
                            </tr>
                            </thead>
                        </table>
                    </div>

                    <div style="clear:both"></div>

                </div>
            </div>
            <!--/.page-content-->

        </div>
        <!--/.row-->
    </div>
    <!--/.container-->
</div>
<!-- /.main-container -->

<style>
    #rejected_modal{
        z-index: 999999;
    }
</style>

<div class="modal fade" id="cancel_reason_modal" role="dialog" aria-labelledby="rejected_modal" aria-hidden="true" data-keyboard="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancel_reason_modal1">{{ t('Why Have You Cancel The Subscription?') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{url('account/cancel_subscription')}}" method="post">
                    <div class="form-group">
                        <label style="float: left"></label>
                        <div class="col-md-12">
                            <select id="rejected_reason" name="cancel_reason" class="form-control select-drop" required>
                                <option value="">Select Reasons</option>
                                @foreach ($cancel_reason as $key => $value)
                                    <option value="{{ $value->id }}">
                                        {{ $value->title }}
                                    </option>
                                @endforeach

                            </select>
                        </div>
                        <input type="hidden" name="id" id="id">
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{t('confirm')}}</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>


@endsection

@section('after_scripts')

<script>
    
    $(".select-drop").select2({
        maximumSelectionLength: 100,
        width: '100%'
    });
    
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr('#search_date', {
            dateFormat: 'Y-m-d',
        });
    });

    function approve() {
        const config = {
            html: true,
            title: 'Cancel Subscription',
            html: 'Do you want to cancel subscription',
            icon: 'warning',
            allowOutsideClick: false,
            confirmButtonText: 'Yes',
            showCancelButton: true,
        };
        Swal.fire(config).then(callback);

        function callback(result) {
            if (result.value) {
                $('#cancel_reason_modal').modal('show');
                {{--window.location.replace('{{url('account/cancel_subscription')}}'--}}
                {{--)--}}
            }
        }
    }
    function resetFields() {
        document.getElementById('search_new').value = '';
        document.getElementById('package').value = '';
        document.getElementById('search_date').value = '';
        $('#search_new, #package, #search_date').trigger('change');
    }
</script>
@endsection
