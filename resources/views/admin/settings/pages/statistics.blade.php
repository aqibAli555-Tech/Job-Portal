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
            <div class="card-body">
                <h4 class="card-title">Edit Statistics</h4>
            </div>
            <form accept-charset="UTF-8" method="post" enctype="multipart/form-data" action="<?php echo admin_url('update_statistics'); ?>">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label>Employees</label>
                            <input type="number" name="employees" placeholder="Name"
                                   class="form-control" value="<?= $statistics->employees ?>">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Companies</label>
                            <div class="input-group">
                                <input type="number" name="companies" value="<?= $statistics->companies ?>"
                                       placeholder="Email" class="form-control">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Jobs</label>
                            <div class="input-group">
                                <input type="number" name="jobs" placeholder="Jobs"
                                       class="form-control" value="<?= $statistics->jobs ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('after_scripts')
@endsection
