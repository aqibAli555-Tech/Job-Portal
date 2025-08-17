<!DOCTYPE html>
<html lang="en">
<head>
    <title>Migration Profile Picture Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

</head>
<body class="bg-body-tertiary">

<!-- Header -->
<section class="text-white text-center py-5" style="background: linear-gradient(to right, #0d6efd, #6610f2);">
    <div class="container">
        <h1 class="display-4 fw-bold">Users Picture Migration To AWS</h1>
        <p class="lead">Track your <b>Users Profile Picture</b> upload process in real-time</p>
    </div>
</section>

<!-- Stats Cards -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">

            <!-- Total profile pictures -->
            <div class="col-md-3">
                <div class="card border-0 shadow text-center h-100">
                    <div class="card-body">
                        <div class="mb-3">
                            <span class="text-primary">
                                <i class="fas fa-users fa-3x"></i>
                            </span>
                        </div>
                        <h5 class="card-title">Total Profile Pictures</h5>
                        <h2 class="fw-bold text-primary">{{ $total_images }}</h2>
                    </div>
                </div>
            </div>

            <!-- Pending profile pictures -->
            <div class="col-md-3">
                <div class="card border-0 shadow text-center h-100">
                    <div class="card-body">
                        <div class="mb-3">
                            <span class="text-warning">
                                <i class="fas fa-clock fa-3x"></i>
                            </span>
                        </div>
                        <h5 class="card-title">Pending Profile Pictures to AWS</h5>
                        <h2 class="fw-bold text-warning">{{ $pending_images }}</h2>
                    </div>
                </div>
            </div>

            <!-- Uploaded profile pictures -->
            <div class="col-md-3">
                <div class="card border-0 shadow text-center h-100">
                    <div class="card-body">
                        <div class="mb-3">
                            <span class="text-success">
                                <i class="fas fa-cloud-upload-alt fa-3x"></i>
                            </span>
                        </div>
                        <h5 class="card-title">Uploaded Profile Pictures to AWS</h5>
                        <h2 class="fw-bold text-success completed">{{ $total_uploaded_aws_images }}</h2>
                    </div>
                </div>
            </div>

            <!-- Error profile pictures -->
            <div class="col-md-3">
                <a href="{{ url('migration/error-profile-images') }}" target="_blank" class="text-decoration-none">
                    <div class="card border-0 shadow text-center h-100">
                        <div class="card-body">
                            <div class="mb-3">
                                <span class="text-danger">
                                    <i class="fas fa-exclamation-triangle fa-3x text-danger"></i>
                                </span>
                            </div>
                            <h5 class="card-title">Total Error Profile Pictures to Upload</h5>
                            <h2 class="fw-bold text-danger">{{ $get_error_profile_images }}</h2>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Error profile pictures -->
            <div class="col-md-3">
                <a href="{{ url('migration/error-profile-images') }}?error_status=2" target="_blank" class="text-decoration-none">
                    <div class="card border-0 shadow text-center h-100">
                        <div class="card-body">
                            <div class="mb-3">
                                <span class="text-danger">
                                    <i class="fas fa-image fa-3x text-danger"></i>
                                </span>
                            </div>
                            <h5 class="card-title">Error Pictures Not Found</h5>
                            <h2 class="fw-bold text-danger">{{ $pictures_not_found }}</h2>
                        </div>
                    </div>
                </a>
            </div>

             <!-- Error profile pictures -->
            <div class="col-md-3">
                <a href="{{ url('migration/error-profile-images') }}?error_status=3" target="_blank" class="text-decoration-none">
                    <div class="card border-0 shadow text-center h-100">
                        <div class="card-body">
                            <div class="mb-3">
                                <span class="text-warning">
                                    <i class="fas fa-compress fa-3x text-warning"></i>
                                </span>
                            </div>
                            <h5 class="card-title">Error Thumbnails Not Found</h5>
                            <h2 class="fw-bold text-warning">{{ $thumbnails_not_found }}</h2>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Error profile pictures -->
            <div class="col-md-3">
                <a href="{{ url('migration/error-profile-images') }}?error_status=4" target="_blank" class="text-decoration-none">
                    <div class="card border-0 shadow text-center h-100">
                        <div class="card-body">
                            <div class="mb-3">
                                <span class="text-danger">
                                    <i class="fas fa-upload fa-3x text-danger"></i>
                                </span>
                            </div>
                            <h5 class="card-title">Error Pictures Upload on AWS</h5>
                            <h2 class="fw-bold text-danger">{{ $pictures_aws_error }}</h2>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Error profile pictures -->
            <div class="col-md-3">
                <a href="{{ url('migration/error-profile-images') }}?error_status=5" target="_blank" class="text-decoration-none">
                    <div class="card border-0 shadow text-center h-100">
                        <div class="card-body">
                            <div class="mb-3">
                                <span class="text-warning">
                                    <i class="fas fa-compress-arrows-alt fa-3x text-warning"></i>
                                </span>
                            </div>
                            <h5 class="card-title">Error Thumbnails Upload on AWS</h5>
                            <h2 class="fw-bold text-warning">{{ $thumbnails_aws_error }}</h2>
                        </div>
                    </div>
                </a>
            </div>

        </div>
        <br>
        <div class="card border-0 shadow text-center h-100" id="progress_box">
            <div class="card-body">
                <div class="progress">
                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0"
                         aria-valuemin="0" aria-valuemax="100" style="width:0%"></div>
                </div>
                <div id="html_progress"></div>
                <br>
                <div id="image_progress" style="max-height: 300px;    overflow-y: scroll;">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <button onclick="get_all_picture_count(this)" class="btn btn-default">Start</button>
                <button onclick="get_error_uploading_picture(this)" class="btn btn-default">Start with Errors</button>
                <button onclick="stop()" class="btn btn-default">Stop</button>
            </div>
        </div>
    </div>
</section>
<script>
    var is_allow = 1;
    function get_all_picture_count(btn) {
        $(btn).attr('disabled', true);
        $.ajax({
            method: 'GET',
            url: "<?= url('migration/profile-image-users') ?>",
            dataType: 'json',
            success: function (response) {
                if (response.pending > 0) {
                    $('.completed').text(response.completed);
                    $('.pending').text(response.pending);
                    import_picture(response, 0);
                } else {
                    alert("No Product Found.");
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
            }
        });
    }

    function import_picture(response, index) {
        if(is_allow == 0){
            return true;
        }
        if (index < 10) {
            $.ajax({
                method: 'GET',
                dataType: 'json',
                url: "<?= url('migration/move_image_to_aws') ?>",
                data: "id=" + response.users[index].id,
                success: function (response_user) {
                    $('.progress-bar').css('width', ((((response.completed) / response.pending)) * 100).toFixed(2) + "%")
                    var messageClass = response_user.status === false ? 'text-danger' : '';
                    $('#image_progress table tbody').append('<tr><td>' + response.users[index].id + '</td><td class="' + messageClass + '">' + response_user.message + '</td></tr>');
                    import_picture(response, index + 1);
                    $("#image_progress").animate({scrollTop: 1000000000000}, 800, 'swing');
                }, error: function (jqXHR, textStatus, errorThrown) {
                    $('.progress-bar').css('width', ((((response.completed) / response.total_product)) * 100).toFixed(2) + "%")
                    $('#image_progress table tbody').append('<tr><td></td><td>' + response.product_ids[index].id + '</td><td>Request time out.</td></tr>');
                    import_picture(response, index + 1);
                    $("#image_progress").animate({scrollTop: 1000000000000}, 800, 'swing');
                }
            });
        } else {
               get_all_picture_count();
        }
    }

    function get_error_uploading_picture(btn) {
        $(btn).attr('disabled', true);
        $.ajax({
            method: 'GET',
            url: "<?= url('migration/profile-error-image-users') ?>",
            dataType: 'json',
            success: function (response) {
                if (response.error > 0) {
                    $('.completed').text(response.completed);
                    $('.pending').text(response.pending);
                    import_picture(response, 0);
                } else {
                    alert("No Product Found.");
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
            }
        });
    }
    function stop() {
        is_allow = 0;
    }
</script>
</body>
</html>
