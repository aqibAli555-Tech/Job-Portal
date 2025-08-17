@extends('admin.layouts.master')

@section('content')

<?php
    $search = !empty(request('search')) ? request('search') : '';
?>

<div class="row">
    @if (Session::has('flash_notification'))
        <div class="col-xl-12">
            @include('flash::message')
        </div>
    @endif
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title">{{ trans('admin.email_logs') }}</h4>
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <div class="form-group">
                            <label for="search">{{ trans('admin.Search') }}</label>
                            <input type="search" id="search" value="<?= $search ?>" class="form-control" name="search">
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered datatables-email-logs" data-url="{{admin_url('email_logs_ajax')}}" style="width:100%">
                        <thead>
                            <th>{{trans('admin.id')}}</th>
                            <th>{{trans('admin.Status')}}</th>
                            <th>{{trans('admin.To')}}</th>
                            <th>{{trans('admin.Subject')}}</th>
                            <th>{{trans('admin.created_at')}}</th>
                            <!-- <th>{{trans('admin.send_on')}}</th> -->
                            <th>{{trans('admin.action')}}</th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@include('admin.models.view_email_modal')
@endsection
@section('after_scripts')
<script>
    function view_email(id) {
        $.ajax({
            url: `view_email/${id}`,
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success' && response.data) {
                    const data = response.data;
                    document.getElementById('email_subject').innerText = data.subject;
                    $('#view_email_modal .modal-body').html(data.body);
                    $('#view_email_modal .modal-body > div:first').css('min-height', '60vh');
                    $('#view_email_modal').modal('show');
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function (xhr) {
                let message = 'Unable to view email.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire(
                    'Error!',
                    message,
                    'error'
                );
            }
        });
    }

    function resend_email(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, resend it!'
        }).then((result) => {
            if (result.isConfirmed) {
                var formData = new FormData();
                var url = '{{admin_url("resend_email")}}';
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('id', id);
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        if (response.status === 'success') {
                            Swal.fire(
                                'Sended!',
                                response.message,
                                'success'
                            ).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                response.message || 'Something went wrong while resending the email.',
                                'error'
                            );
                        }
                    },
                    error: function (xhr) {
                        let message = 'Something went wrong while resending the email.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        Swal.fire(
                            'Error!',
                            message,
                            'error'
                        );
                    }
                });
            }
        });
    }

    function change_email_status(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you really want to change the status?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, change it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `change_email_status/${id}`,
                    method: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        if (response.status === 'success') {
                            Swal.fire(
                                'Changed!',
                                response.message,
                                'success'
                            ).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function (xhr) {
                        let message = 'Unable to view email.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        Swal.fire(
                            'Error!',
                            message,
                            'error'
                        );
                    }
                });
            }
        });
    }
</script>
@endsection

