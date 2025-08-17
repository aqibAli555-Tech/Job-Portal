@extends('admin.layouts.master')

@section('content')

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
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            @if (isset($company))
            <form action="<?php echo admin_url('update_company') ?>" accept-charset="UTF-8" method="post" enctype="multipart/form-data" id="update_employee">
                <div class="card-body">
                    <h4 class="card-title">{{ trans('admin.Edit Company') }}</h4>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                @csrf
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>{{trans('admin.Company Name')}}</label>
                                        <input type="text" name="name" value="<?php if (!empty($company->name)) {
                                            echo $company->name;
                                        } ?>" placeholder="{{trans('admin.Company Name')}}" class="form-control">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>{{trans('admin.Phone')}}</label>
                                        <input type="text" name="phone" value="<?php if (!empty($company->phone)) {
                                            echo $company->phone;
                                        } ?>" placeholder="{{trans('admin.Phone')}}" class="form-control">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>{{trans('admin.User Email')}}</label>
                                        <input type="text" name="email" value="<?php if (!empty($company->email)) {
                                            echo $company->email;
                                        } ?>" placeholder="{{trans('admin.User Email')}}" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group col-md-12 pt-4">
                                    <label>{{trans('admin.Address')}}</label>
                                    <input type="text" name="address" value="<?php if (!empty($company->address)) {
                                        echo $company->address;
                                    } ?>" placeholder="{{trans('admin.Address')}}" class="form-control">
                                </div>
                                <div data-preview="#logo" data-aspectRatio="0" data-crop="" class="form-group col-md-12 image" hidden>
                                    <div>
                                        <label>Logo (Supported file extensions: jpg, jpeg, png, gif)</label>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6" style="margin-bottom: 20px;">
                                            <img id="mainImage" src="http://localhost/hungryforjobsnew/public/storage/pictures/kw/7/1666694281download.png">
                                        </div>
                                    </div>
                                    <div class="btn-group">
                                        <label class="btn btn-primary btn-file">
                                            Choose file <input type="file" accept="image/*" id="uploadImage" class="hide">
                                            <input type="hidden" id="hiddenImage" name="logo">
                                        </label>
                                        <button class="btn btn-danger" id="remove" type="button"><i class="fa fa-trash"></i></button>
                                    </div>
                                </div>
                                <div class="form-group col-md-12  pt-4">
                                    <label>{{trans('admin.Company Description')}}</label>
                                    <textarea name="description" placeholder="{{trans('admin.Company Description')}}" rows="10" class="form-control"><?php if (!empty($company->description)) {
                                            echo $company->description;
                                        } ?></textarea>
                                </div> 
                                <input type="hidden" name="id" value="<?php if (!empty($company->id)) {
                                    echo $company->id;
                                } ?>" class="form-control">
                    
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div id="saveActions" class="form-group">
                        <input type="hidden" name="save_action" value="save_and_back">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary shadow">
                                <span class="fa fa-save" role="presentation" aria-hidden="true"></span> &nbsp;
                                <span data-value="save_and_back">Save and back</span>
                            </button>
                        </div>
                        <a href="{{admin_url('companies')}}" class="btn btn-secondary shadow"><span class="fa fa-ban"></span> &nbsp;Cancel</a>
                    </div>
                </div>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection

@section('after_scripts')
<script>
    $('#category_id').select2();
    $(document).ready(function () {
    });
</script>
@endsection