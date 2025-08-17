<?php
// From Company's Form
$classLeftCol = 'col-md-3';
$classRightCol = 'col-md-9';

if (isset($originForm)) {
    // From User's Form
    if ($originForm == 'user') {
        $classLeftCol = 'col-md-3';
        $classRightCol = 'col-md-7';
    }

    // From Post's Form
    if ($originForm == 'post') {
        $classLeftCol = 'col-md-3';
        $classRightCol = 'col-md-8';
    }
}

$countries = App\Models\Country::orderBy('name')->get();
if (!empty(request()->segments()[3])) {
    if (!empty(request()->segments()[3] == 'edit')) {
        $segment = 'edit';
    } else {
        $segment = 'create';
    }
} else {
    $segment = 'create';
}
?>
<style>
.fileinput-remove {
    display: none;
}
</style>

<div id="companyFields">
    <!-- name -->
    <?php $staffNameError = (isset($errors) and $errors->has('staff.name')) ? ' is-invalid' : ''; ?>
    <div class="form-group row required">
        <label class="{{ $classLeftCol }} control-label" for="staff.name">{{ t('Name') }} <sup>*</sup></label>
        <div class="{{ $classRightCol }}">
            <input name="staff[name]" placeholder="{{ t('Staff name') }}"
                class="form-control inputs input-md{{ $staffNameError }}" type="text"
                value="{{ old('staff.name', (isset($staff->name) ? $staff->name : '')) }}" required>
        </div>
    </div>

    <!-- email -->
    <?php $staffEmailError = (isset($errors) and $errors->has('staff.email')) ? ' is-invalid' : ''; ?>
    <div class="form-group row required">
        <label class="{{ $classLeftCol }} control-label" for="staff.email">{{ t('Email') }} <sup>*</sup></label>
        <div class="input-group {{ $classRightCol }}">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="icon-mail"></i></span>
            </div>
            <input name="staff[email]" type="email" class="form-control{{ $staffEmailError }}"
                placeholder="{{ t('Staff email') }}"
                value="{{ old('staff.email', (isset($staff->email) ? $staff->email : '')) }}" required>
        </div>
    </div>

    <!-- phone -->
    <?php $staffPhoneError = (isset($errors) and $errors->has('staff.phone')) ? ' is-invalid' : ''; ?>
    <div class="form-group row required">
        <label class="{{ $classLeftCol }} control-label" for="staff.phone">Phone<sup>*</sup> </label>
        <div class="input-group {{ $classRightCol }}">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="icon-phone-1"></i></span>
            </div>
            <input name="staff[phone]" type="text" class="validatePhoneCheck form-control{{ $staffPhoneError }}"
                placeholder="" min="6"
                value="{{ old('staff.phone', (isset($staff->phone) ? $staff->phone :  '+'.config('country.phone'))) }}"
                required>
        </div>
    </div>
    @if($segment!='edit')
    <div class="form-group row required">
        <label class="{{ $classLeftCol }} control-label" for="staff.password">{{t('Password')}}<sup>*</sup></label>
        <div class="col-md-9 show-pwd-group">
            <input id="password" required pattern=".{6,}" title="6 characters minimum" name="staff[password]"
                type="password" class="form-control" placeholder="{{ t('Password') }}">
            <span class="icon-append show-pwd">
                <button type="button" class="eyeOfPwd" onclick="showPwd()">
                    <i id="eyeIcon" class="far fa-eye-slash" style="color:#474b51"></i>
                </button>
            </span>
        </div>
    </div>

    <div class="form-group row required">
        <label class="{{ $classLeftCol }} control-label" for="staff.phone">{{t('Confirm Password')}}<sup>*</sup>
        </label>

        <div class="col-md-9 show-pwd-group">
            <input id="password_confirmation" required name="staff[confirm_password]" pattern=".{6,}"
                title="6 characters minimum" type="password" class="form-control"
                placeholder="{{ t('Confirm Password') }}">
            <span class="icon-append show-pwd">
                <button type="button" class="eyeOfPwd" onclick="showconfirmdPwd()">
                    <i id="eyeIcon2" class="far fa-eye-slash" style="color:#474b51"></i>
                </button>
            </span>
        </div>
    </div>
    @endif
</div>

@section('after_styles')
@parent
<style>
#companyFields .select2-container {
    width: 100% !important;
}

.file-loading:before {
    content: " {{ t('Loading') }}...";
}

.krajee-default.file-preview-frame .kv-file-content {
    height: auto;
}

.krajee-default.file-preview-frame .file-thumbnail-footer {
    height: 30px;
}

#toggle-password {
    cursor: pointer;
}

.fa-eye-slash:before {
    content: "\f070";
}

.fa-eye:before {
    content: "\f06e";
}
</style>
@endsection

@section('after_scripts')
@parent
@endsection