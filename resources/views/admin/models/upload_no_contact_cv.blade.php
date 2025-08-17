<style>
    #pdfPreview {
        border: none;
        width: 100%;
        height: 600px;
        display: block;
        margin: 0 auto;
        margin-top: 30px;
        background: #f1f1f1;
    }
</style>

<div class="modal fade parent" id="upload_Cv_model" tabindex="-1" aria-labelledby="upload_Cv_model" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="upload_Cv_model">{{trans('Upload CV With Hidden Details')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>

            </div>
            <div class="modal-body">
                <form id="Uploadcv" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="subject-name"
                               class="col-form-label">{{ trans('Upload CV With Hidden Details') }}</label>
                        <input type="file" id="fileInput" class="form-control" name="cv" required>
                    </div>

                    <div class="mb-3">
                        <h4 id="name_user"></h4>
                        <h4 id="id_user"></h4>
                    </div>

                    <iframe id="pdfPreview"></iframe>

                    <input type="hidden" id="cv_user_id" name="user_id">
                    <div class="modal-footer">
                        <button type="button" id="upload_cv" class="btn btn-primary">{{ trans('Upload CV') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>

    document.getElementById('upload_cv').addEventListener('click', function (event) {
        event.preventDefault();

        var formData = new FormData(document.getElementById('Uploadcv'));
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        $('.preloader').show();
        fetch('{{ URL('/') }}/admin/upload_no_contact_cv', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
            body: formData,
        })
            .then(response => response.json())
            .then(data => {
                $('.preloader').hide();

                if (data.status) {
                    Swal.fire({
                        title: 'Success',
                        text: data.message,
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok'
                    })
                    $('#fileInput').val('');
                    $('#pdfPreview').hide();
                    $('#id_user').text('');
                    $('#name_user').text('');
                    $('#upload_Cv_model').modal('hide')
                    $('.datatables-job-seekers').DataTable().ajax.reload(null, false);
                } else {
                    $('#fileInput').val('');
                    $('#pdfPreview').hide();

                    Swal.fire({
                        title: 'Error!',
                        text: data.message,
                        icon: 'error',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok'
                    })
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });


    $('#fileInput').change(function () {
        var file = this.files[0];
        var user_id = $('#cv_user_id').val();
        var filename = file.name;
        filename_check = filename.split('.');
        $('#id_user').text('')
        $('#name_user').text('')
        if (file && file.type === 'application/pdf') {
            if (filename_check[0] === user_id + '_edited') {
                $.ajax({
                    url: "<?= url('admin/verify_employee_cv') ?>",
                    data: "file_name=" + filename,
                    method: "get",
                    success: function (e) {
                        if (e.status == true) {
                            $('#id_user').text('ID: ' + e.data.id)
                            $('#name_user').text('Name: ' + e.data.name)
                            var fileURL = URL.createObjectURL(file);
                            $('#pdfPreview').attr('src', fileURL).show();
                        } else {
                            $('#fileInput').val('');
                            $('#pdfPreview').hide();

                            Swal.fire({
                                title: 'Error!',
                                text: e.message,
                                icon: 'error',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok'
                            })

                        }
                    }
                })
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: "File is not matching with user id",
                    icon: 'error',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                })
                $('#fileInput').val('');
                $('#pdfPreview').hide();
            }
        } else {
            Swal.fire({
                title: 'Error!',
                text: "Please select A Pdf file.",
                icon: 'error',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Ok'
            })
            $('#fileInput').val('');
            $('#pdfPreview').hide();
        }
    });

    function disableButtonnocontact(event) {
        var button = event.target;
        var form = button.form;

        if (form.checkValidity()) {
            button.disabled = true;
            form.submit();
        } else {
            event.preventDefault();
        }
    }
</script>

