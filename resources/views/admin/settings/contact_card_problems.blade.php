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
                <input type="hidden" id="contact_problems_ids" name="contact_problems_ids" value="">
                <div>
                    <button class="btn btn-xs btn-danger" data-button-type="delete"
                            onclick="delete_items()" id="delete_items_button" style="display:none"><i class="far fa-trash-alt"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ trans('admin.Contact Card Problems') }}</h4>
                <div class="table-responsive">
                    <table class="table table-striped table-sm table-bordered datatables-contact_card_problem" data-url="{{admin_url('Contact_Card_Problems_ajax')}}" style="width:100%">
                        <thead>
                            <th><input type="checkbox" name="checkAll" id="checkAll" class="checkbox" value=""></th>
                            <th class="dt-checkboxes-cell dt-checkboxes-select-all sorting_disabled" tabindex="0" colspan="1" aria-controls="massSelectAll" rowspan="1" style=" padding-right: 10px;width: 10%" data-col="0" aria-label="">#</th>
                            <th >{{ trans('admin.name') }}</th>
                            <th>{{ trans('admin.Company') }}</th>
                            <th>{{ trans('admin.created_at') }}</th>
                            <th>{{ trans('admin.action') }}</th>
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

        $('#contact_problems_ids').val(ids);
    });
    function delete_items() {
        var contact_problems_ids = $('#contact_problems_ids').val();
        if (confirm("Are you sure you want to delete items?")) {
            $.ajax({
                url: "<?= url('admin/Contact_multiple_delete/') ?>",
                data: "contact_problems_ids=" + contact_problems_ids,
                method: "post",
                success: function () {
                    table_contact_card_problem.draw();
                }
            })
        } else {

            return false;
        }
    }
</script>
@endsection