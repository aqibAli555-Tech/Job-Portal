<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if (isset($errors) and $errors->any())
<?php $errorMessage = ''; ?>
<div class="col-xl-12" style="display:none;">
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h5><strong>{{ t('oops_an_error_has_occurred') }}</strong></h5>
        <ul class="list list-check">
            @foreach ($errors->all() as $error)
            <?php $errorMessage .= "<li>" . $error . "</li>" ?>
            @endforeach
        </ul>
    </div>
</div>
@if(!empty($errorMessage))
<script>
    Swal.fire({
        html: '<?=$errorMessage?>',
        icon: "error",
        confirmButtonText: "<u>Ok</u>",
    });
</script>
@endif
@endif

@if (Session::has('flash_notification'))
<div class="col-xl-12">
    <div class="row">
        <div class="col-xl-12">
            @include('flash::message')
        </div>
    </div>
</div>
@endif