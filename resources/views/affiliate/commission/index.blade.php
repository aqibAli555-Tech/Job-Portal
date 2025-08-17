@extends('affiliate.layouts.master')
@section('content')
@include('common.spacer')
<div class="main-container">
    <div class="container">
        @include('affiliate/inc/breadcrumbs')
        <div class="row">
            <div class="col-md-3 page-sidebar">
                @include('affiliate.inc.sidebar')
            </div>
            <div class="col-md-8 page-content">
                @include('flash::message')
                    @if (isset($errors) and $errors->any())
                    <?php $errorMessage = '' ?>
                        @if(!empty($errorMessage))
                            <script>
                                Swal.fire({
                                    html: '<?= $errorMessage ?>',
                                    icon: "error",
                                    confirmButtonText: "<u>Ok</u>",
                                });
                            </script>
                        @endif
                    @endif
                <div class="inner-box">
                    <h2 class=""> {{ t('Commissions') }}</h2>
                    <p style="background: #615583;color: #fff;padding: 5px;text-align: left;float: right;">
                        {!! t('commission will be calculated on every first of the month for previous month') !!}
                    </p>
                    <hr style="clear: both;">
                    <div class="row">                    
                        <div class="col-md-3">
                            <div class="form-group">
                                <select name="month" id="month" class="form-control">
                                        <option value="">Select Month</option>    
                                        <option value="January">January</option>
                                        <option value="February">February</option>
                                        <option value="March">March</option>
                                        <option value="April">April</option>
                                        <option value="May">May</option>
                                        <option value="June">June</option>
                                        <option value="July">July</option>
                                        <option value="August">August</option>
                                        <option value="September">September</option>
                                        <option value="October">October</option>
                                        <option value="November">November</option>
                                        <option value="December">December</option>
                                </select>                           
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select name="year" id="year" class="form-control">
                                    <option value="">Select Year</option>
                                    <?php foreach (App\Helpers\Helper::getwithdrawYears() as $value => $name) : ?>
                                        <option value="<?= $value ?>"><?= $name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select name="status" id="status" class="form-control">
                                    <option value="">Select Status</option>
                                    <?php foreach (App\Helpers\Helper::getCommissionStatuses() as $value => $name) : ?>
                                        <option value="<?= $value ?>"><?= $name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <button type="button" class="btn btn-primary" onclick="resetFields()">Reset</button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-sm table-bordered datatables-commissions"
                            data-url="{{ url('affiliate/commissions_ajax') }}">
                            <thead>
                            <tr>
                                <th>{{ t('Details') }}</th>
                                <th>{{ t('Status') }}</th>
                                <th>{{ t('action') }}</th>
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
        document.getElementById('month').value = '';
        document.getElementById('year').value = '';
        document.getElementById('status').value = '';
        $('#month').trigger('change');
    }
</script>
@endsection