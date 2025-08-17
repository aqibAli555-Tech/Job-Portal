@extends('admin.layouts.master')
@section('content')
<?php
    $search = !empty(request('search')) ? request('search') : '';
?>
    <div class="card">


        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <button style="float: right;" type="button" class="btn btn-primary"
                            onclick="open_Add_skill_model()">Add New Skill
                    </button>
                </div>
            </div>
            <h4 class="card-title">{{ trans('admin.employee_skill') }}</h4>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="search">{{ trans('admin.Search') }}</label>
                        <input type="search" id="search" value="<?= $search ?>" class="form-control"
                                name="search">
                    </div>
                </div>
            </div>
            <br><br>
            <div class="table-responsive">
                <table class="table table-striped table-sm table-bordered datatables-employeeskill"
                       data-url="{{admin_url('employeeskill-ajax')}}" style="width:100%">
                    <thead>
                    <tr>
                        <th>{{ trans('admin.image') }}</th>
                        <th>{{ trans('admin.skill') }}</th>
                        <th>{{ trans('admin.Status') }}</th>
                        <th>{{ trans('admin.Employer') }}</th>
                        <th>{{ trans('admin.Featured') }}</th>
                        <th>{{ trans('admin.created_at') }}</th>
                        <th>{{ trans('admin.actions') }}</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>


    @includeFirst([
    config('larapen.core.customizedViewPath') . 'admin.models.skill_model',
    'admin.models.skill_model',
    ])

    @includeFirst([
    config('larapen.core.customizedViewPath') . 'admin.models.add_skill_model',
    'admin.models.add_skill_model',
    ])

@endsection

@section('after_scripts')
    <script>
        function add_feature(id, featured) {
            if (confirm("Are you sure you want to change Feature status?")) {

                $.ajax({
                    url: "<?= url('admin/employeeSkill/add_feature') ?>",
                    data: "id=" + id + "&featured=" + featured,
                    method: "post",
                    success: function () {
                        table_employeeskill.draw();
                    }
                })
            } else {

                return false;
            }
        }

        function SkillEdit(id, skill, status, image) {
            $('#image_preview').attr('src', image);
            $('#skillVall').val(skill);
            $('#old_image').val(image);
            $('#skillId').val(id);
            $("#status").val(status).trigger('change');
            $('#skillmodal').modal('show');
        }

        function open_Add_skill_model() {
            $('#addSkill').modal('show');
        }

        document.getElementById('imageInput').addEventListener('change', function () {
            const image = this.files[0];

            if (image) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('displayImage').src = e.target.result;
                    document.getElementById('image_preview').src = e.target.result;
                }
                reader.readAsDataURL(image);
            } else {
                document.getElementById('displayImage').src = '';
                document.getElementById('image_preview').src = '';
            }
        });


        function delete_skill(id) {
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
                    var url = "{{url('/')}}/admin/delete_skill/" + id;
                    showConfirmation(id, url, 'GET')
                }
            })
        }

    </script>
@endsection