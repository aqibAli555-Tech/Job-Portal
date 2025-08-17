@extends('admin.layouts.master')

@section('content')
<style>
    .input-group-text{
        height: 100%;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ trans('Staff') }}</h4>
                <form method="post" action="<?php echo admin_url('/staff/edit_post'); ?>">
                    @csrf
                    <div class="card border-top border-primary">
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>Name</label>
                                    <input type="text" name="name" value="{{ $staff->name}}" placeholder="Name"
                                        class="form-control" value="">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Email</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i
                                                    class="ti-email"></i></span></div>
                                        <input type="email" name="email" value="{{ $staff->email}}"
                                            placeholder="Email" class="form-control">
                                    </div>
                                </div>
                                
                                <div class="form-group col-md-4">
                                    <label>Phone</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i
                                                    class="ti-mobile"></i></span></div>
                                        <input type="text" name="phone" value="{{ $staff->phone}}"
                                            placeholder="Phone" class="validatePhoneCheck form-control" min="6">
                                    </div>
                                </div>
                                <input type="hidden" name="id" value="{{ $staff->id}}" class="form-control">
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('after_scripts')
@endsection
