@extends('admin.layouts.master')

@section('content')

<?php
    $search = !empty(request('search')) ? request('search') : '';
    $country = !empty(request('country')) ? request('country') : '';
?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
            	@if (Session::has('flash_notification'))
	                <div class="col-xl-12">
	                    @include('flash::message')
	                </div>
                @endif
                <h4 class="card-title">Filters</h4>
                <form method="get">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="search">{{trans('admin.Search')}}</label>
                            <input type="search" id="search" value="<?= $search ?>" class="form-control"
                                   name="search">
                        </div>

                        <div class="col-md-3">
                            <label for="search">{{trans('admin.Country')}}</label>
                            <select id="country" value="<?= $country ?>" class="form-control select1"
                                    name="country">
                                <option value="">All Country</option>
                                <?php if (!empty($countries)) {
                                    foreach ($countries as $country_object) {
                                        ?>
                                        <option value="{{$country_object->code}}" <?php echo $country_object->code == $country ? "selected" : ""; ?>
                                            >{{$country_object->name}}</option>

                                    <?php }
                                } ?>

                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ t('Companies') }}</h4>
                <div class="table-responsive">
                    <table class="table table-striped table-sm table-bordered datatables-company" data-url="{{admin_url('company-ajax')}}" style="width:100%">
                        <thead>
                            <th style="width: 50vh">Details</th>
                            <th style="width: 20vh">{{trans('admin.No of Jobs')}}</th>
                            <th >{{trans('admin.Parent Company')}}</th>
                            <th>{{trans('admin.Description')}}</th>
    
                            <th>{{trans('admin.action')}}</th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('after_scripts')
@endsection