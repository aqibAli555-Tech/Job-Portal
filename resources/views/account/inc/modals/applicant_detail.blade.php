
<style>
    .modal .wide-intro {
        height: 200px !important;
    }

    .modal-header {
        border: none !important;
    }


    button.close span {
        background: white;
        width: 25px;
        height: 25px;
        display: inline-block;
        text-align: center;
        border-radius: 50%;
        color: black;
        font-size: 23px;
        margin-right: 12px;
        margin-top: 12px;
    }

    .modal-body .user-image {
        position: absolute; top: -43px;
    }

    .modal-body .user-image img {
        width: 75px;
        height: 75px;
    }
    .modal-body h6{
        position: absolute  ;
        top: -55px;
        left: 100px  ;
        font-size: 30px  ;
        color: white  ;
    }

    .modal-header::after {

    }

</style>

<div class="modal fade" id="applicant_detail" role="dialog" aria-labelledby="unlock_applicants_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header wide-intro p-0">
                <div style="background: rgba(0,0,0,0.7); width: 100%; height: 100%;">
                    <button type="button" class="close" onclick="closeModal()" aria-label="Close">
                        <span>×</span>
                    </button>
                </div>
            </div>
            <div class="modal-body" id="applicant-detail-body"></div>
        </div>
    </div>
</div>

<script>
    function closeModal() {
        $('#applicant_detail').modal('hide');
    }
</script>
