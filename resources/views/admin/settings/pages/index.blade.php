@extends('admin.layouts.master')

@section('content')

<div class="row">
    @if (Session::has('flash_notification'))
        <div class="col-xl-12">
            @include('flash::message')
        </div>
    @endif
    
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ trans('admin.Pages') }}</h4>
                <div class="table-responsive">
                    <table class="table table-striped table-sm table-bordered datatables-pages" data-url="{{admin_url('pages-ajax')}}" style="width:100%">
                        <thead>
                            <th>{{trans('admin.Name')}}</th>
                            <th>{{trans('admin.title')}}</th>
                            <th>{{trans('admin.status')}}</th>
                            <th>{{trans('admin.action')}}</th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('after_scripts')

<script>
function update_status(id) {
    if (confirm("Are you sure you want to change Status ?")) {
        $.ajax({
            url: "<?= admin_url('/update_status/pages') ?>",
            data: "id=" + id,
            method: "post",
            success: function() {
                Swal.fire({
                    title: 'Success!',
                    text: 'Update Status Successfully.',
                    icon: 'success'
                }).then(() => {
                    table_pages.draw();
                });
            }

        })
    } else {

        return false;
    }
}
</script>

@endsection
