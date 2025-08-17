@extends('layouts.master')

@section('content')
@include('common.spacer')

<div class="main-container">
    <div class="container">
        <div style="border: 1px solid #ddd; border-radius: 5px; overflow: hidden; height: 600px;">
            <iframe 
                src="{{ url('public/pdfjs/web/viewer.html') }}?file={{ $fileUrl }}"
                width="100%"
                height="100%"
                style="border: none;">
            </iframe>
        </div>
    </div>
</div>
@endsection

 