@extends('admin.layouts.master')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
            <p><strong>Important:</strong> By clicking the button below, your cache will be cleared. This action may help improve performance or resolve issues.</p>
                <div>
                    <button class="btn btn-xs btn-primary"
                            onclick="clear_cache()">{{ trans('admin.Cache Clear') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 

@section('after_scripts')
<script>
    function clear_cache() {
        Swal.fire({
        text: "Are you sure you want to clear cache?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "<?= url('admin/cache-clear/') ?>",
                method: 'GET',
                success: function (response) {
                    Swal.fire(
                        'Cleared!',
                        'Cache has been cleared.',
                        'success'
                    );
                },
                error: function (xhr) {
                    Swal.fire(
                        'Error!',
                        'There was a problem in clearing cache.',
                        'error'
                    );
                }
            });
        }
    });
    }
</script>
@endsection