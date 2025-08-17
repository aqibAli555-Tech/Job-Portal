@extends('affiliate.layouts.master')
@section('content')
<?php
    $id = !empty(request('id')) ? request('id') : '';
    $date = !empty(request('date')) ? request('date') : '';
?>
@include('common.spacer')
<div class="main-container">
    <div class="container">
        @include('affiliate/inc/breadcrumbs')
        <div class="row">
            <!--/.page-sidebar-->
            <div class="col-md-3 page-sidebar">
                @include('affiliate.inc.sidebar')
            </div>
            <div class="col-md-8 page-content">
                <div class="inner-box">
                    <h2 class="title-2"><i class=""></i> {{ t('user_purchase') }}</h2>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <select name="package" id="package" class="form-control">
                                        <option value="">Select Package</option>
                                        @foreach ($packages as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                </select>                           
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="date" id="date" value="<?= $date ?>" class="form-control" name="date" placeholder="Select Date">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <button type="button" class="btn btn-primary" onclick="resetFields()">Reset</button>
                            </div>
                        </div>
                    </div>
                    <br>
                    <input type="hidden" id="id" value="<?= $id ?>" class="form-control" name="id">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm table-bordered datatables-package-commissions"
                            data-url="{{ url('affiliate/user_purchase_ajax') }}">
                            <thead>
                            <tr>
                                <th>{{ t('Package') }}</th>
                                <th>{{ t('Amount') }}</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <div style="clear:both"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('after_scripts')
<script>
    function resetFields() {
        document.getElementById('package').value = '';
        document.getElementById('date').value = '';
        $('#package').trigger('change');
    }
</script>
@endsection