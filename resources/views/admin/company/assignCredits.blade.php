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
                <h4 class="card-title">{{ trans('Assign Credits') }}</h4>
                <form action="{{admin_url('/assignCredits/search')}}" method="get">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="email" class="form-control" name="email" required="required"
                                    placeholder="Search user by email address"
                                    value="@if(isset($data['email'])){{$data['email']}}@endif" style="">
                            </div>
                        </div>

                        <div class="col-md-6 pb-4">
                            <button type="submit" name="send" class="btn btn-primary btn-sm">Search
                            </button>
                        </div>
                    </div>
                </form>

                @if(!empty($data['user']))
                <div class="card">
                    <div class="card-body">
                        <div style="width: 100%" class="pt-4"> 
                            <h4 style="text-align: center" class="card-title">USER DETAILS</h4>  
                            <hr>
                                <img src="{{storage_path('/')}}/{{$data['user']->file}}" style="width: 100px;"
                                    alt="">
                                <br>
                                <br>
                                <p><b>User name :</b> {{$data['user']->name}}</p>
                                <p><b>Email Address :</b> {{$data['user']->email}}</p>
                                <p><b>Phone Number :</b> {{$data['user']->phone}}</p>
                                <p><b>Contact card credits :</b>
                                    {{!empty($data['remaining_credits'])?$data['remaining_credits']:0}}
                                </p>
                                <p><b>Post jobs credits:</b>
                                    {{ !empty($data['remaining_post'])?$data['remaining_post']:0}}</p>

                                <form method="post" action="{{admin_url('assignCredits/add')}}" style="width: 100%">
                                    <input type="hidden" name="email" value="{{$data['user']->email}}">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="">Contact card credits</label>
                                            <input type="number" name="credits" required="required"
                                                placeholder="Enter Contact credits" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="">Post Job credits</label>
                                            <input type="number" name="post_credits"
                                                placeholder="Enter Post credits" class="form-control">

                                        </div>
                                    
                                        <div class="col-md-6">
                                            <button type="submit"
                                                class="btn btn-primary" style="margin-top: 10px;">Add
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            
                            <br><br>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('after_scripts')
<script>
$(function() {
    $('.select1').select2({
        width: '100%'
    });
});
</script>
@endsection