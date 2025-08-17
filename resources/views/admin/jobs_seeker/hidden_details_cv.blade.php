
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

    .custom-select {
        border: 1px solid black;
        border-radius: 4px;
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
    
    .custom-btn, .rejected_button {
        width: 100%; /* Makes buttons responsive */
        text-align: center;
        margin-top: 10px; /* Adds spacing on smaller screens */
    }
    
    .rejected_button {
        color: white;
        background-color: #bb2323;
        padding: 12px 24px;
        border: none;
    }
    
    .custom-btn {
        background-color: #28a745;
        color: white;
        padding: 12px 24px;
        border: none;
    }
    
    /* Adjust button width and layout for larger screens */
    @media (min-width: 768px) {
        .custom-btn, .rejected_button {
            margin-top: 0;
            margin-left: 5px;
        }
    }


</style>
<div class="card">
    <div class="card-body">
        <div class="container" id="cvContainer">
            <div class="all_cv" id="">
                <div class="p-2 bg-light">
                    <div class="row mt-3 justify-content-end">
                        <div class="col-md-4">
                            <a href="javascript:void(0)" class="btn btn-success custom-btn" onclick="approve_cv('{{ $data }}', 'approve')">Approve</a>
                        </div>
                        <div class="col-md-4">
                            <select id="rejected_reason" name="rejected_reason" class="form-control custom-select rejected_button" onchange="reject_cv('{{ $data }}', 'reject')">
                                <option value="">Select Rejected Reason</option>
                                <option value="Watermark issue">Watermark issue</option>
                                <option value="Contact Information not removed">Contact Information not removed</option>
                                <option value="CV Not Opening">CV Not Opening</option>
                                <option value="Incorrect CV">Incorrect CV</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div id="nocontactcv" class="col-md-6">
                        <label class="p-3 mt-2 bg-warning d-block">Original CV - {{$user->id}}</label>
                        <iframe class="pdfPreview" src="{{ url('storage/app/public/'.$user->employee_cv) }}"></iframe>
                    </div>
                    <div id="nocontactcv" class="col-md-6 cv-iframe">
                        <label class="p-3 mt-2 bg-warning d-block">CV - {{$user->id}}</label>
                        <iframe class="pdfPreview" src="{{ url('storage/app/public/employee_cv_temp/'.$data) }}"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@section('after_scripts')
<script>
    function approve_cv(temp_file_name, status) {
        $.ajax({
            type: "POST",
            url: "{{URL('/')}}/admin/update_uploaded_hidden_detail_cv",
            data: { temp_file_name: temp_file_name, status: status },
            beforeSend: function () {
                $('.preloader').show();
            },
            success: function (response) {
                $('.preloader').hide();
                if (response.status) {
                    window.location.reload();                
                } else {
                    window.location.reload();                
                }
            },
            error: function (response) {
                $('.preloader').hide();
            }
        });
    }

    var reject_id = 0;
    var reject_type = '';
    function reject_cv(temp_file_name, status) {
        var rejected_reason = $('#rejected_reason').val();
        var reject_id = temp_file_name;
        var reject_type = status;
        if(rejected_reason == ''){
            showSwalAlert('Error!', 'Please provide the valid reason', 'error', 'Ok');
            return false;
        }
        if(reject_id == 0 && reject_type == ''){
            showSwalAlert('Error!', 'Something went wrong. Please try again', 'error', 'Ok');
            return false;
        }
        $.ajax({
            type: "POST",
            url: "{{URL('/')}}/admin/update_uploaded_hidden_detail_cv",
            data: { temp_file_name: reject_id, status: reject_type,rejected_reason:rejected_reason },
            beforeSend: function () {
                $('.preloader').show();                
            },
            success: function (response) {
                $('.preloader').hide();                
                $('#rejected_reason').val('');
                reject_id = 0;
                reject_type = '';
                if (response.status) {
                    window.location.reload();
                } else {
                    window.location.reload();   
                }
            },
            error: function (response) {
                $('.preloader').hide();
                $('#reject_cv_popup').modal('hide');
                window.location.reload();            
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
@endsection