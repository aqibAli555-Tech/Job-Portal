@extends('admin.layouts.master')
@section('content')
    <?php
    $search = !empty(request('search')) ? request('search') : '';
    $limit = !empty(request('limit')) ? request('limit') : '';
    ?>
    <style>
        td {
            font-size: 14px;
        }

        .form-switch .form-check-input {
            border: 1px solid;
        }
    </style>
    @if (Session::has('flash_notification'))
        <div class="col-xl-12">
            @include('flash::message')
        </div>
    @endif
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">{{ $title }}</h4>

            <div class="row align-items-end">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="search">{{ trans('admin.Search') }}</label>
                        <input type="search" id="search" value="{{ $search }}" class="form-control" name="search">
                    </div>
                </div>
                <div class="col-md-8 text-md-end mt-md-0 mt-2">
                    <button id="exportExcel" class="btn btn-primary">Export Excel</button>
                </div>
            </div>


        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-striped table-sm table-bordered datatables-whatsApp"
                    data-url="{{ admin_url('whatsapp_ajax') }}">
                    <thead>
                        <tr>
                            <th>{{ trans('admin.ID') }}</th>
                            <th>{{ trans('admin.name') }}</th>
                            <th>{{ trans('admin.email') }}</th>
                            <th>{{ trans('admin.whatsApp_number') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>

        </div>
    </div>
@endsection
@section('after_scripts')
<script>
    $('#exportExcel').on('click', function () {
        let search = $('#search').val();
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to export this?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, export!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ admin_url('whatsapp_export') }}" + '?search=' + encodeURIComponent(search);
            }
        });
    });
</script>
@endsection