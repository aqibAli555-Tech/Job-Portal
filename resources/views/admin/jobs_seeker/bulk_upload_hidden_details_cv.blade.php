@extends('admin.layouts.master')
@section('content')
    @if(!empty($data))
        @include('admin.jobs_seeker.hidden_details_cv')      
    @else
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Please upload no contact cv. Max files 50</h4>
            </div>
            <div class="card-body">
                <input type="file" id="fileInput" name="cv[]" multiple class="form-control mb-3" />
                <button class="btn btn-primary" id="uploadBtn">Upload</button>
                <div class="mt-3">
                    <div id="progressContainer" class="d-none">
                        <strong id="fileName"></strong>
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;"></div>
                        </div>
                    </div>
                </div>
                <div class="mt-3" id="files_list"></div>
            </div>
        </div>
    @endif
@endsection

@section('after_scripts')

<script>
    $(document).ready(function () {
        $("#uploadBtn").click(function () {
            let files = $("#fileInput")[0].files;
            if (files.length === 0) {
                Swal.fire({
                    title: 'Error!',
                    text: "Please select files to upload.",
                    icon: 'error',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                });
                return;
            }
    
            let maxFiles = 50;
            if (files.length > maxFiles) {
                Swal.fire({
                    title: 'Error!',
                    text: `You can upload a maximum of ${maxFiles} files at a time.`,
                    icon: 'error',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                });
                return;
            }
    
            $("#progressContainer").removeClass("d-none");
            $('#uploadBtn, #fileInput').hide();
    
            uploadFiles(files, 0);
        });
    
        function uploadFiles(files, index) {
            if (index >= files.length) {
                $(".progress-bar")
                    .css("width", "100%")
                    .text("Upload Complete")
                    .addClass("bg-success");
    
                Swal.fire({
                    title: 'Success!',
                    text: "All files uploaded successfully!",
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok'
                });
    
                return;
            }
    
            let file = files[index];
            let formData = new FormData();
            formData.append("cv", file);
    
            $("#fileName").text(file.name);
    
            $.ajax({
                url: "{{ URL('/') }}/admin/upload_hidden_detail_cv",
                type: "POST",
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function (response) {
                    let percent = Math.round(((index + 1) / files.length) * 100);
                    $(".progress-bar").css("width", percent + "%").text(percent + "%");
    
                    if (response.status === true) {
                        $("#files_list").append("<p class='text-success'>File uploaded successfully: " + file.name + "</p>");
                    } else {
                        $("#files_list").append("<p class='text-danger'>Failed to upload: " + response.message + "</p>");
                    }
    
                    uploadFiles(files, index + 1);
                },
                error: function () {
                    let percent = Math.round(((index + 1) / files.length) * 100);
                    $(".progress-bar").css("width", percent + "%").text(percent + "%");
    
                    $("#files_list").append("<p class='text-danger'>Failed to upload file: " + file.name + " due to an unknown error</p>");
    
                    uploadFiles(files, index + 1);
                }
            });
        }
    });

</script>


@endsection