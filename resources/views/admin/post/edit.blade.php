@extends('admin.layouts.master')

@section('content')

@if (Session::has('flash_notification'))
<div class="row">
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
            <form action="<?php echo admin_url('update_posts') ?>" accept-charset="UTF-8" method="post"
                    enctype="multipart/form-data" id="update_posts">
            <div class="card-body">
                <h4 class="card-title">{{ trans('admin.Edit_Job_Posts') }}</h4>
                @if (isset($post))
                    @csrf
                    <input type="hidden" name="id" id="id" value="<?php echo $post->id ?>">
                    <div class="form-group col-md-12">
                        <label>{{trans('admin.Category')}}</label>
                        <select name="category_id" style="width: 100%"
                            class="form-control select2-selection__rendered">
                            <?php
                            if (!empty($employee_skill)) {
                                foreach ($employee_skill as $value) { ?>
                            <option value="<?php echo $value->id ?>" <?php if ($value->id == $post->category_id) {
                                        echo "selected";
                                    } ?>><?php echo $value->skill; ?></option>
                            <?php }
                            } ?>
                        </select>
                    </div>
                    <div class="form-group col-md-12 pt-4">
                        <label>{{trans('admin.Company Name')}}</label>
                        <input type="text" disabled name="company_name" value="<?php
                        if (!empty($post->company_name)) {
                            echo $post->company_name;
                        } ?>" placeholder="Company Name" class="form-control">
                    </div>
                    <div class="form-group col-md-12 pt-4">
                        <label>{{trans('admin.Description')}}</label>
                        <textarea id="tinymce-description" name="description" placeholder="Description"
                            id="description" rows="10" class="form-control tinymce"><?php if (!empty($post->description)) {
                                echo strip_tags($post->description);
                            } ?></textarea>
                    </div>
                    <div class="form-group col-md-12 pt-4">
                        <label>{{trans('admin.Title')}}</label>
                        <input type="text" name="title" value="<?php if (!empty($post->title)) {
                            echo $post->title;
                        } ?>" placeholder="Title" class="form-control">
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4 pt-4">
                            <label for="salary_min">{{trans('admin.Salary')}} (min)</label>
                            <input type="number" name="salary_min" id="salary_min" value="<?php if (!empty($post->salary_min)) {
                                echo $post->salary_min;
                            } ?>" min="0" step="0.01" placeholder="Salary (min)" class="form-control">
                        </div>
                        <div class="form-group col-md-4 pt-4">
                            <label for="salary_max">{{trans('admin.Salary')}} (max)</label>
                            <input type="number" name="salary_max" id="salary_max" value="<?php if (!empty($post->salary_max)) {
                                echo $post->salary_max;
                            } ?>" min="0" step="0.01" placeholder="Salary (max)" class="form-control">
                        </div>
                        <div class="form-group col-md-4 pt-4">
                            <label>{{trans('admin.Salary Type')}}</label>
                            <select name="salary_type_id" style="width: 100%"
                                class="form-control select2_from_array">
                                <?php if (!empty($salary_type)) {
                                    foreach ($salary_type as $type) {

                                        ?>
                                <option value="{{$type->id}}" <?php if (!empty($post->salary_type_id == $type->id)) {
                                            echo "selected";
                                        } ?>><?php echo $type->name ?></option>
                                <?php }
                                } ?>

                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6 pt-4" style="margin-top: 20px;">
                            <div class="radio">
                                <label>{{trans('admin.Overtime Pay available?')}}</label>
                                <label style="margin-left: 20px;" for="overtime1">
                                    <input type="radio" value="1" name="overtime" id="overtime1" <?php if (!empty($post->postDetail->overtime_pay) && $post->postDetail->overtime_pay == 1) {
                                        echo "checked";
                                    } ?>>&nbsp;&nbsp;{{trans('admin.yes')}}
                                </label>
                                <label style="margin-left: 20px;" for="overtime2">
                                    <input type="radio" value="0" name="overtime" id="overtime2" <?php if ($post->postDetail->overtime_pay == 0) {
                                        echo "checked";
                                    } ?>>&nbsp;&nbsp;{{trans('admin.no')}}
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-md-6 pt-4" style="margin-top: 20px;">
                            <div class="radio">
                                <label for="housing">{{trans('admin.Housing Available? ')}}</label>
                                <label style="margin-left: 20px;">
                                    <input type="radio" value="1" id="housing" name="housing" <?php if (!empty($post->postDetail->housing_available) && $post->postDetail->housing_available == 1) {
                                        echo "checked";
                                    } ?>>&nbsp;&nbsp;{{trans('admin.yes')}}
                                </label>
                                <label style="margin-left: 20px;">
                                    <input type="radio" value="0" id="housing" name="housing" <?php if ($post->postDetail->housing_available == 0) {
                                        echo "checked";
                                    } ?>>&nbsp;&nbsp;{{trans('admin.no')}}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6 pt-4" style="margin-top: 20px;">
                            <div class="radio">
                                <label for="transport">{{trans('admin.Transportation Available?')}}</label>
                                <label style="margin-left: 20px;">
                                    <input type="radio" value="1" id="transport" name="transportation" <?php if (!empty($post->postDetail->transportation_available) && $post->postDetail->transportation_available == 1) {
                                        echo "checked";
                                    } ?>>&nbsp;&nbsp;{{trans('admin.yes')}}
                                </label>
                                <label style="margin-left: 20px;">
                                    <input type="radio" value="0" id="transport" name="transportation" <?php if ($post->postDetail->transportation_available == 0) {
                                        echo "checked";
                                    } ?>>&nbsp;&nbsp;{{trans('admin.no')}}
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-md-6 pt-4" style="margin-top: 20px;">
                            <div class="checkbox">
                                <label for="negotiable">
                                    <input type="hidden" name="negotiable" value="0">
                                    <input type="checkbox" value="1" id="negotiable" name="negotiable" <?php if (!empty($post->negotiable) && $post->negotiable == 1) {
                                        echo "checked";
                                    } ?>> {{trans('admin.Negotiable Salary')}}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6 pt-4" hidden>
                            <label>{{trans('admin.User Name')}}</label>
                            <input type="text" name="contact_name" value="<?php if (!empty($post->contact_name)) {
                                echo $post->contact_name;
                            } ?>" placeholder="User Name" class="form-control">
                        </div>
                        <div class="form-group col-md-6 pt-4">
                            <label>{{trans('admin.User Email')}}</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i
                                            class="ti-email"></i></span></div>
                                <input type="text" name="email" value="<?php if (!empty($post->email)) {
                                    echo $post->email;
                                } ?>" placeholder="User Email" class="form-control">
                            </div>
                        </div>
                        <div class="form-group col-md-6 pt-4">
                            <label>{{trans('admin.User Phone')}}</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i
                                            class="ti-mobile"></i></span></div>
                                <input type="text" name="phone" value="<?php if (!empty($post->phone)) {
                                    echo $post->phone;
                                } ?>" placeholder="User Phone" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-6 pt-4">
                        <label>{{trans('admin.Post Type')}}</label>
                        <select name="post_type_id" style="width: 100%"
                            class="form-control select2_from_array" ><?php 
                            if (!empty($post_type)) {
                            foreach ($post_type as $ptype) {
                                ?> <option value="{{$ptype->id}}" <?php if (!empty($post->post_type_id == $ptype->id)) {
                                    echo "selected";
                                } ?>> {{$ptype->name}}
                            </option>
                            <?php }
                        } ?>
                        </select>
                    </div>
                    <div class="form-group col-md-6 pt-4" style="margin-top: 20px;">
                        <div class="checkbox">
                            <label for="archived">
                                <input type="hidden" name="archived" value="0">
                                <input type="checkbox" id="archived" value="1" name="archived" <?php if (!empty($post->archived)) {
                                    echo "checked";
                                } ?>> {{trans('admin.Archived')}}
                            </label>
                        </div>
                    </div>
                @endif
            </div>
            <div class="card-footer">
                <div id="saveActions" class="form-group">
                    <input type="hidden" name="save_action" value="save_and_back">
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary shadow">
                            <span class="fa fa-save" role="presentation" aria-hidden="true"></span>
                            &nbsp;
                            <span data-value="save_and_back">Save and back</span>
                        </button>
                    </div>
                    <a href="<?php echo admin_url('get_posts') ?>" class="btn btn-secondary shadow"><span
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