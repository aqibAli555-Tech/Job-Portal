@extends('admin.layouts.master')
@section('content')
    <style>
        td {
            font-size: 12px;
        }
    </style>
    @if (Session::has('flash_notification'))
        <div class="col-xl-12">
            @include('flash::message')
        </div>
    @endif
    <div class="card">
        <div class="card-header">
            <h4>{{ trans('admin.affiliates_commission_slot') }} List</h4>
        </div>
        <div class="card-body">
            <div class="card-header">
                <div class="d-flex justify-content-center mb-3">
                    <strong class="text-primary fs-5 fw-bold bg-light p-2 rounded">
                        Name: {{ $affiliate_name }}
                    </strong>
                </div>
                <div class="card-title">
                    <form action="{{ admin_url('update_affiliated_commission_slot') }}" method="post">
                    @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="min_amount">{{ trans('admin.min_amount') }} ($)</label>
                                    <input id="min_amount" name="min_amount" class="form-control" type="number" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="max_amount">{{ trans('admin.max_amount') }} ($)</label>
                                    <input id="max_amount" name="max_amount" class="form-control" type="number" min="1" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="commission">{{ trans('admin.commission') }} (%)</label>
                                    <input id="commission" name="commission" class="form-control" type="number" min="1" required>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id='affiliate_id' name='affiliate_id' value="{{$id}}">
                        <input type="hidden" id='slot_id' name='slot_id' value="">
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                {{ trans('admin.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <br>
            <div class="card-header">
                <div class="table-responsive">
                    <table class="table table-striped  table-sm table-bordered">
                        <thead>
                        <tr>
                            <th>{{ trans('admin.min_amount') }}</th>
                            <th>{{ trans('admin.max_amount') }}</th>
                            <th>{{ trans('admin.commission') }}</th>
                            <th>{{ trans('admin.action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($affiliates_commission_slots as $affiliates_commission_slot)
                            <tr>
                                <td>${{ number_format($affiliates_commission_slot->min_amount) }}</td>
                                <td>${{ number_format($affiliates_commission_slot->max_amount) }}</td>
                                <td>{{ $affiliates_commission_slot->commission }}%</td>
                                <td>
                                    <a class="btn btn-sm btn-primary" href="javascript:void(0)" onclick="slot_detail({{ $affiliates_commission_slot->id }})">
                                        <i class="fas fa-edit"></i> Edit Slot
                                    </a>
                                    <a class="btn btn-sm btn-danger" href="javascript:void(0)" onclick="delete_slot({{ $affiliates_commission_slot->id }})">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('after_scripts')
<script>
        function slot_detail(id) {
            var url = '{{admin_url("edit_affiliated_commission_slot")}}/' + id;
            $.ajax({
                url: url,
                type: "GET",
                dataType: 'json',
                success: function(response) {
                    if (response.success && response.data) {
                        var slot = response.data;
                        $('#slot_id').val(id);
                        $('#min_amount').val(slot.min_amount);
                        $('#max_amount').val(slot.max_amount);
                        $('#commission').val(slot.commission);
                    } else {
                        Swal.fire('Error!', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error!', 'Something went wrong.', 'error');
                }
            });
        }

        function delete_slot(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    var url = "{{ admin_url('delete_affiliated_commission_slot') }}/" + id;
                    $.ajax({
                        url: url,
                        type: "GET",
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('Deleted!', response.message, 'success')
                                    .then(() => location.reload());
                            } else {
                                Swal.fire('Error!', response.message, 'error');
                            }
                        },
                        error: function(xhr) {
                            Swal.fire('Error!', 'Something went wrong.', 'error');
                        }
                    });
                }
            })
        }
</script>
@endsection