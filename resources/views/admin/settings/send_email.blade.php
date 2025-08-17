@extends('admin.layouts.master')

@section('content')
<?php
    $user_type = !empty(request('user_type')) ? request('user_type') : '';
?>
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
        @endif
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ trans('admin.send email') }}</h4>
                <form action="{{admin_url('send_email_post')}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group pt-4">
                                <label for="user_type">User Type</label>
                                <select type="text" class="form-control select1"  id="user_type" onchange="window.location.replace('<?= admin_url() . '/send_email?user_type=' ?>'+this.value)" name="type" required="required">
                                    <option value="1" <?php if ($user_type == 1) {echo 'selected';} ?>>Employer</option>
                                    <option value="2" <?php if ($user_type == 2) {echo 'selected';} ?>>Job Seeker</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 pt-4">
                            <div class="form-group">
                                <label for="users">Select User</label>
                                <select type="text" class="form-control select1" id="users" name="users[]" multiple="multiple" required="required">
                                    <option value="0">Send to all</option>
                                    @foreach($data['users'] as $user)
                                    @if($user->id!=1)
                                    <option value="{{$user->id}}"> {{$user->name}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 pt-4">
                            <div class="form-group">
                                <label for="subject">Subject</label>
                                <input type="text" id="subject" class="form-control" name="subject" required="required" placeholder="Subject">
                            </div>
                        </div>
                        <div class="col-md-12 pt-4">
                            <div class="form-group">
                                <label for="message">Message</label>
                                <textarea type="text" class="form-control" id="message" name="message" rows="10" required="required" placeholder="Message"></textarea>
                            </div>
                        </div>
                        <br>
                        <br>
                        <div class="col-md-12 pt-4">
                            <button type="submit" name="send"
                                    class="btn btn-primary ladda-button">{{ trans('admin.send email') }}
                            </button>
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