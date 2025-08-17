<!DOCTYPE html>
<html lang="en">
<head>
    <title>Migration Resume Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
</head>
<body class="bg-body-tertiary">

<!-- Header -->
<section class="text-white text-center py-5" style="background: linear-gradient(to right, #0d6efd, #6610f2);">
    <div class="container">
        <h1 class="display-4 fw-bold">Resume Migration To AWS</h1>
        <p class="lead">Track your <b>Resume (Employee CV & No Contact CV)</b> upload process in real-time</p>
    </div>
</section>

<!-- Stats Cards -->
<section class="py-5">
    <div class="container">
        <div class="card border-0 shadow text-center h-100" id="progress_box">
            <div class="card-body">
                <table id="errorProfileImageTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">ID</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">CV</th>
                            <th class="text-center">no-contact CV</th>
                            <th class="text-center">Error</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($errorCVs as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td class="text-center">
                                <a href="{{ url('migration/view-cv/' . $user->id) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    View
                                </a>
                            </td>
                            <td class="text-center">
                                <a href="{{ url('migration/view-cv/' . $user->id . '?type=cv_no_contact') }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                    View
                                </a>
                            </td>
                            <td>{{ \App\Helpers\Helper::aws_cv_status($user->is_resume_uploaded_on_aws) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        var table = $('#errorProfileImageTable').DataTable({
            "pageLength": 10
        });
    });
</script>

</body>
</html>
