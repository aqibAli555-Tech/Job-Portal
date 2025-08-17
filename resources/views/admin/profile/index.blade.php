@extends('admin.layouts.master')

@section('content')

<div class="row">
    @if (Session::has('flash_notification'))
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="col-xl-12">
                    @include('flash::message')
                </div>
            </div>
        </div>
    </div>
     @endif
    <div class="col-12">
        <div class="card">
            <form action="<?php echo admin_url('update_account') ?>" accept-charset="UTF-8" method="post" id="update_account">
            @csrf
            <div class="card-body">
                <h4 class="card-title">Profile</h4>
                <div class="row">
                    <div class="form-group col-md-4 pt-4">
                        <label>User Type</label>
                        <select name="user_type_id" style="width: 100%" class="form-control select1">
                            <?php
                            if (!empty($userType)) {
                                foreach ($userType as $key => $value) { ?>
                                    <option value="<?php echo $key ?>" <?php if ($key == $userData['user_type_id']) { echo "selected";} ?>><?php echo $value; ?></option>
                                <?php }
                            } ?>
                        </select>
                    </div>
                    <div class="form-group col-md-4 pt-4">
                        <label>Gender</label>
                        <select name="gender_id" style="width: 100%" class="form-control select1">
                            <?php
                            if (!empty($gender)) {
                                foreach ($gender as $key => $value) { ?>
                                    <option value="<?php echo $key ?>" <?php if ($key == $userData['gender_id']) { echo "selected";} ?>><?php echo $value; ?></option>
                                <?php }
                            } ?>
                        </select>
                    </div>
                    <div class="form-group col-md-4 pt-4">
                        <label>Name</label>
                        <input type="text" name="username" value="<?php if (!empty($userData['username'])) { echo $userData['username'];} ?>" placeholder="Name" class="form-control">
                    </div>
                    <div class="form-group col-md-4 pt-4">
                        <label>Email</label>
                        <div class="input-group">         
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i data-feather="mail"></i></span>
                            </div>
                            <input type="text" readonly name="email" value="<?php if (!empty($userData['email'])) { echo $userData['email'];} ?>" placeholder="Email" class="form-control">
                        </div>
                    </div>
                    <div class="form-group col-md-4 pt-4">
                        <label>Phone</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i data-feather="phone"></i>
                                </span>
                            </div>
                            <input type="text" name="phone" value="<?php if (!empty($userData['phone'])) { echo $userData['phone'];} ?>" placeholder="Phone" class="form-control">
                        </div>
                    </div>
                    <div class="form-group col-md-4 pt-4">
                        <label>Country</label>
                        <select name="country_code" style="width: 100%" class="form-control select1">
                            <?php
                            if (!empty($countries)) {
                                foreach ($countries as $key => $value) { ?>
                                    <option value="<?php echo $key ?>" <?php if ($key == $userData['country_code']) { echo "selected";} ?>><?php echo $value; ?></option>
                                <?php }
                            } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div id="saveActions" class="form-group">
                    <input type="hidden" name="save_action" value="save_and_back">
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary shadow">
                            <span class="fa fa-save" role="presentation" aria-hidden="true"></span>
                            &nbsp;
                            <span data-value="save_and_back">Save</span>
                        </button>
                    </div>
                    <a href="<?php echo admin_url('dashboard'); ?>" class="btn btn-secondary shadow"><span
                            class="fa fa-ban"></span> &nbsp;Cancel</a>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('after_scripts')
@endsection
