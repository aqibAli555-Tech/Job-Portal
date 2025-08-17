@extends('affiliate.layouts.master')
@section('content')
    @include('common.spacer')
    <div class="main-container">
        <div class="container">
            <input type="hidden" id="success" value="{{ $success }}">
            <input type="hidden" id="message" value="{{ $message }}">
        </div>
    </div>
@endsection
@section('after_scripts')
<script>
    $(document).ready(function() {
        var success = $('#success').val();
        var message = $('#message').val();
        if(success){
            Swal.fire({
                html: message,
                icon: 'success',
                confirmButtonText: '<u>Ok</u>',
            });
        }else{
            Swal.fire({
                html: message,
                icon: 'error',
                confirmButtonText: '<u>Ok</u>',
            });
        }
    });
</script>
@endsection

