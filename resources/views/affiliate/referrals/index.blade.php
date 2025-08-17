@extends('affiliate.layouts.master')
@section('content')
<?php
    $searchNew = !empty(request('search_new')) ? request('search_new') : '';
?>
@include('common.spacer')
<div class="main-container">

<div class="container">
        @include('affiliate/inc/breadcrumbs')
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
            <!--/.page-sidebar-->
            <div class="col-md-3 page-sidebar">
                @include('affiliate.inc.sidebar')
            </div>
             <div class="col-md-8 page-content">
                <div class="inner-box">
                <h2 class="title-2"><i class=""></i> {{ t('referral_users') }}</h2>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <input type="search" id="search_new" value="<?= $searchNew ?>" class="form-control" name="search_new" placeholder="Search">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <select name="referral_affiliate_id" id="referral_affiliate_id" class="form-control">
                                    <option value="">Select Affiliate</option>
                                    <option value="referred_by_me">Reffered By Me</option>
                                    @foreach ($affiliates as $affiliate)
                                        <option value="{{ $affiliate->id }}" {{ ($affiliate->id == $affiliate_id)?'selected':'' }} >{{ $affiliate->name }}</option>
                                    @endforeach
                                </select>
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
                        <table class="table table-striped table-sm table-bordered datatables-referral-users"
                            data-url="{{ url('affiliate/referral_users_ajax') }}">
                            <thead>
                            <tr>
                                <th>{{ t('Details') }}</th>
                                <th>{{ t('Action') }}</th>
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
        document.getElementById('search_new').value = '';
        document.getElementById('referral_affiliate_id').value = '';
        $('#search_new').trigger('keyup');
    }
</script>
@endsection