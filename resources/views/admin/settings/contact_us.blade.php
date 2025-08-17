@extends('admin.layouts.master')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
            	@if (Session::has('flash_notification'))
	                <div class="col-xl-12">
	                    @include('flash::message')
	                </div>
                @endif
                <h4 class="card-title">Filters</h4>
                <form method="get" id='myform'>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="search">{{ trans('admin.Search') }}</label>
                            <input type="search" id="search" value="{{!empty(request('search')) ? request('search') : ''}}" class="form-control"
                                   name="search">
                        </div>
                        <div class="col-md-2 pt-4">
                            <label>&nbsp;</label>
                            <button type="submit"
                                    class="btn btn-primary btn-block ">{{ trans('admin.Search') }}
                            </button>
                        </div>
                    </div>
                </form>
                <br>
                <div class="row">
                    <div class="col-md-4">
                        <input type="hidden" id="contact_us_ids" value="">
                        <button class="btn btn-xs btn-danger" style="display: none" data-button-type="delete"
                                onclick="delete_items()" id="delete_items_button"><i class="far fa-trash-alt"></i> Delete
                        </button>
                   </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ trans('admin.Contact') }}</h4>
                <div class="table-responsive">
                    <table class="table table-striped table-sm table-bordered datatables-contact-us" data-url="{{admin_url('contact-us-ajax')}}" style="width:100%">
                        <thead>
                                <th><input type="checkbox" name="checkAll" id="checkAll" class="checkbox" value=""></th>
                                <th>Details</th>
                                <th>{{ trans('admin.email') }}</th>
                                <th>{{ trans('admin.user_type') }}</th>
                                <th>{{ t('Message') }}</th>
                                <th class="pr-5">{{ trans('admin.delete') }}</th>
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
    function delete_item(id) {
         Swal.fire({
             title: "Are you sure?",
             text: "You want to delete?",
             icon: "warning",
             showCancelButton: true,
             confirmButtonColor: "#3085d6",
             cancelButtonColor: "#d33",
             confirmButtonText: "Yes, delete it!",
         }).then((result) => {
             if (result.isConfirmed) {
                 $.ajax({
                     url: "<?= admin_url() . '/contactDelete/' ?>" + id,
                     method: "GET",
                     success: function () {
                         Swal.fire("Deleted!", "The contact has been deleted.", "success").then(() => {
                             window.location.reload();
                         });
                     },
                     error: function () {
                         Swal.fire("Error!", "An error occurred while deleting the contact.", "error");
                     },
                 });
             }
         });
    }

    $("#checkAll").click(function () {
        var checkboxes = $('.checkbox');
        if (this.checked) {
            $.each(checkboxes, function () {
                $(this).prop('checked', true);
            });
        } else {
            $.each(checkboxes, function () {
                $(this).prop('checked', false);
            });
        }
    });

    $('.checkbox , #checkAll').click(function () {
        var ids = "";
        var checkboxes = $('.checkbox');
        var checkedboxes = [];
        $.each(checkboxes, function () {
            if (this.checked) {
                checkedboxes.push($(this).val());
            }
        });
        if (checkedboxes.length == checkboxes.length) {
            $('#checkAll').prop('checked', true);
        } else {
            $('#checkAll').prop('checked', false);
        }

        if (checkedboxes.length) {
            $('#delete_items_button').show();
            ids = checkedboxes.toString();
        } else {
            $('#delete_items_button').hide();

        }

        $('#contact_us_ids').val(ids);
    });

    function delete_items() {
        var contact_us_ids = $('#contact_us_ids').val();
        if (confirm("Are you sure you want to delete items?")) {
            $.ajax({
                url: "<?= admin_url() . '/contactsDelete/' ?>",
                data: "contact_us_ids=" + contact_us_ids ,
                method: "get",
                success: function () {
                    // window.location.reload();
                    table_contact_us.draw();
                }
            })
        } else {
            return false;
        }
    }

</script>
@endsection
