<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compare CV</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .pdfPreview {
            border: none;
            width: 100%;
            height: 85vh;
            background: #f1f1f1;
        }

        .custom-btn {
            padding: 12px 24px;
            font-size: 18px;
            float: right;
            margin-left: 10px;
        }

        .sticky-top {
            z-index: 1020;
            background-color: #f8f9fa;
            padding: 10px 0;
        }

        .spinner-container {
            display: flex;          
            justify-content: center;
            align-items: center;
            margin-top: 5%;
        }

        @media (min-width: 1200px) {
            .container, .container-lg, .container-md, .container-sm, .container-xl {
                max-width: 1500px;
            }
        }

        .toast-success {
            background-color: #28a745 !important;
            color: #fff !important;
            border: 2px solid #218838 !important;
        }

        .toast-success .toast-progress {
            background-color: #218838 !important;
        }
    </style>
</head>

<body class="pb-1">
@if (Session::has('flash_notification'))
    <div class="col-xl-12">
        @include('flash::message')
    </div>
@endif

<div class="container" id="cvContainer">
    
</div>
@includeFirst([config('larapen.core.customizedViewPath') . 'admin.models.reject_cv_model','admin.models.reject_cv_model',])
<div class="spinner-container">
    <div class="spinner-border spinner-border text-muted loading" role="status"></div>
</div>

</body>
</html>
<script src="{{ url()->asset('adminlite/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ url()->asset('adminlite/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    $(document).ready(function() {
        loadCvData('{{$data['type']}}',5,0);
    });
    let currentIndex = 0;
    let cvData = [];
    let completed = 0;
    let firstCV = 0;
    function loadCvData(type, number_of_cv, last_id) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: '{{URL('/')}}/admin/load_cv_data',
            type: 'GET',
            data: { 
                type: type, 
                number_of_cv: number_of_cv, 
                last_id: last_id 
            },
            beforeSend: function () {
                $('.loading').show();
            },
            success: function(response) {
                $('.loading').hide();
                if (response.cvs.length > 0) {
                    cvData.push(...response.cvs); 
                    renderCvData(response.cvs);
                    resolve(true);
                } else if(cvData.length <= 0){
                    window.location.href = '{{admin_url('job-seekers')}}';
                    resolve(false);
                }else{
                    renderCvData(cvData);
                    resolve(true);
                }
            },
            error: function(error) {
                $('.loading').hide();
                alert('Error loading CV data.');
                reject(error);
            }
        });
    });
}

    function renderCvData(cvs) {
        cvs.forEach((cv, index) => {
            let employeeId = 'employee_' + cv.id;
            let cvHtml = `
                <div class="all_cv" id="${employeeId}" style="display:${firstCV === 0 ? 'block' : 'none'};">
                    <div class="p-3 mt-2 bg-light sticky-top">
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <h6>#${cv.id}</h6>
                                <h6>${cv.name}</h6>
                                <h6>Email: ${cv.email}</h6>
                            </div>
                            <div class="col-md-6">
                                <a href="javascript:void(0)" class="btn btn-danger custom-btn" onclick="reject_cv_show_popup(${cv.id}, '{{ $data['type'] }}',${currentIndex})">Reject</a>
                                <a href="javascript:void(0)" class="btn btn-success custom-btn" onclick="approve_cv(${cv.id}, '{{ $data['type'] }}',${currentIndex})">Approve</a>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div id="cv${index+1}" class="col-md-6 cv-iframe">
                            <label class="p-3 mt-2 bg-primary text-light d-block">Normal CV</label>
                            <iframe class="pdfPreview" src="{{ url('public/storage/')}}/${cv.cv}"></iframe>
                        </div>
                        <div id="nocontactcv${index+1}" class="col-md-6 cv-iframe">
                            <label class="p-3 mt-2 bg-warning d-block">No Contact CV</label>
                            <iframe class="pdfPreview" src="{{ url('public/storage/')}}/${cv.cv_no_contact}"></iframe>
                        </div>
                    </div>
                </div>
            `;
            $('#cvContainer').append(cvHtml);
            firstCV = 1;
            currentIndex++;
        });
        return true;
    }

    async function showNextCv(index,remove = 0) {
        if (cvData && cvData.length > 0) {
            
            const lastValue = cvData[cvData.length - 1];
            if (lastValue && lastValue.id) {
                try {
                    await loadCvData('{{$data['type']}}', 1, lastValue.id);
                } catch (error) {
                    console.error("Error loading CV data:", error);
                    return; 
                }
            }

            if(remove == 1){
                $('#employee_' + cvData[0].id).remove();
                cvData.splice(0, 1);
                const reindexedCvData = cvData.map((cv, index) => ({
                    ...cv,
                    index: index + 1, 
                }));
                currentIndex = cvData.length;
            }
            
            if(cvData.length <= 0){
                window.location.href = '{{admin_url('job-seekers')}}';
            }
            $('#employee_' + cvData[0].id).show();
        } else {
            window.location.href = '{{admin_url('job-seekers')}}';
        }
    }

    
    function approve_cv(id, type, index) {

        $.ajax({
            type: "POST",
            url: "{{URL('/')}}/admin/approve_cv_no_contact",
            data: { id: id, type: type },
            beforeSend: function () {
                $('.loading').show();
                
            },
            success: function (response) {
                $('.loading').hide();
                if (response.status) {
                    showNextCv(index,1);
                    showSwalAlert('Great!', response.message, 'success', 'Ok')
                } else {
                    showSwalAlert('Error!', response.message, 'error', 'Ok')
                }
            },
            error: function (response) {
                $('.loading').hide();
                showSwalAlert('Error!', 'Something went wrong. Please try again', 'error', 'Ok');
            }
        });
    }

    var reject_id = 0;
    var reject_type = '';
    var reject_index = 0;
    function reject_cv_show_popup(id, type, index) {
        reject_id = id;
        reject_type = type;
        reject_index = index;
        $('#reject_cv_popup').modal('show');
        return true;
    }
    function reject_cv() {
        var rejected_reason = $('#rejected_reason').val();
        if(rejected_reason == ''){
            showSwalAlert('Error!', 'Please provide the valid reason', 'error', 'Ok');
            return false;
        }
        if(reject_id == 0 && reject_type == '' && reject_index == 0){
            $('#reject_cv_popup').modal('hide');
            showSwalAlert('Error!', 'Something went wrong. Please try again', 'error', 'Ok');
            return false;
        }

        
        $.ajax({
            type: "POST",
            url: "{{URL('/')}}/admin/reject_cv_no_contact",
            data: { id: reject_id, type: reject_type,rejected_reason:rejected_reason },
            beforeSend: function () {
                $('.loading').show();
                
            },
            success: function (response) {
                $('.loading').hide();
                $('#reject_cv_popup').modal('hide');
                $('#rejected_reason').val('Watermark issue');
                reject_id = 0;
                reject_type = '';
                reject_index = 0;
                if (response.status) {
                    showNextCv(reject_index,1);
                    showSwalAlert('Great!', response.message, 'success', 'Ok')
                } else {
                    showSwalAlert('Error!', response.message, 'error', 'Ok')
                }
            },
            error: function (response) {
                $('.loading').hide();
                $('#reject_cv_popup').modal('hide');
                showSwalAlert('Error!', 'Something went wrong. Please try again', 'error', 'Ok');
            }
        });
    }
    function showSwalAlert(title, message, icon = 'success', button) {
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: 'toast-bottom-right',
            timeOut: 5000,
            extendedTimeOut: 1000, 
            preventDuplicates: true,
            iconClasses: {
                success: 'toast-success'
            }
        };

        if (icon === 'success') {
            toastr.success(message, title);
        } else if (icon === 'error') {
            toastr.error(message, title);
        }
    }
    
    $('.close_modal').on('click', function () {
      $('#reject_cv_popup').modal('hide'); 
     });
</script>
