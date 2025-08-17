@extends('admin.layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ trans('admin.Edit Pages') }}</h4>
                <form action="<?php echo admin_url('update_pages') ?>" accept-charset="UTF-8" method="post"
                    enctype="multipart/form-data" id="update_employee">
                    @csrf
                    <div class="card-body">
                        <div class="">
                            <div class="">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>{{trans('admin.Name')}}</label>
                                        <input type="text" name="name" value="<?php if (!empty($pages->name)) {
                                            echo $pages->name;
                                        } ?>" placeholder="{{trans('admin.Name')}}" class="form-control">
                                    </div>
                                    <div class="form-group col-md-4 pt-4">
                                        <label>{{trans('admin.slug')}}</label>
                                        <input type="text" name="slug" value="<?php if (!empty($pages->slug)) {
                                            echo $pages->slug;
                                        } ?>" placeholder="{{trans('admin.slug')}}" class="form-control">
                                    </div>
                                    <div class="form-group col-md-4 pt-4">
                                        <label>{{trans('admin.external_link')}}</label>
                                        <input type="text" name="external_link" value="<?php if (!empty($pages->external_link)) {
                                            echo $pages->external_link;
                                        } ?>" placeholder="{{trans('admin.external_link')}}" class="form-control">
                                    </div>
                                    
                                </div>
                                <div class="row">

                                    <div class="form-group col-md-4 pt-4">
                                        <label>{{trans('admin.title')}}</label>
                                        <input type="text" name="title" value="<?php if (!empty($pages->title)) {
                                            echo $pages->title;
                                        } ?>" placeholder="{{trans('admin.title')}}" class="form-control">
                                    </div>

                                    <div class="form-group col-md-4 pt-4">
                                        <label>Type</label>
                                        <select name="type" class="form-control">
                                            <option value="standard" @if($pages->type == 'standard') selected="selected" @endif>standard</option>
                                            <option value="terms"  @if($pages->type == 'terms') selected="selected" @endif>terms</option>
                                            <option value="privacy"  @if($pages->type == 'privacy') selected="selected" @endif>privacy</option>
                                            <option value="tips"  @if($pages->type == 'tips') selected="selected" @endif>tips</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-4" style="padding-top: 52px;">
                                        <div class="checkbox">
                                            <label>
                                                <input type="hidden" name="active" value="0">
                                                <input type="checkbox" value="1" name="active" @if($pages->active == 1) checked="checked" @endif> Active
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-12 pt-4">
                                        <label>{{trans('admin.Content')}}</label>
                                        <textarea name="content" placeholder="{{trans('admin.Company Description')}}"
                                            rows="15" class="form-control"><?php if (!empty($pages->content)) {
                                                echo $pages->content;
                                            } ?></textarea>
                                    </div>
                                </div>
                                
                                <input type="hidden" name="id" value="<?php if (!empty($pages->id)) {
                                    echo $pages->id;
                                } ?>" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div id="saveActions" class="form-group">
                            <input type="hidden" name="save_action" value="save_and_back">
                            <div class="btn-group">
                                <button type="submit" class="btn btn-primary shadow">
                                    <span class="fa fa-save" role="presentation" aria-hidden="true"></span> &nbsp;
                                    <span data-value="save_and_back">Updates</span>
                                </button>
                            </div>
                            <a href="{{admin_url('pages')}}" class="btn btn-secondary shadow"><span
                                    class="fa fa-ban"></span> &nbsp;Cancel</a>
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
