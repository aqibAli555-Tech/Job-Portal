<div class="modal fade" id="experience_modal" data-backdrop="static" role="dialog" data-dismiss="modal"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                {{ t('We need you to add how many years of work experience you have and your nationality - this information will now be available when Companies check your profile') }}
            </div>
            <div class="modal-body">
                <form action="{{ url('update_employee_profile') }}" method="post">
                    @if (empty(auth()->user()->experiences))
                        <div class="form-group required experiences">
                            <label class="col-md-4 col-form-label" for="experience">{{ t('Work Experience') }} <sup
                                    style="color:red">*</sup></label>
                            <div class="col-md-12">
                                <select id="experiences" name="experiences" class="form-control select1" required>
                                    <option value="">{{ t('select experiences') }}</option>
                                    <option value="0-1 years"
                                        {{ !empty(auth()->user()->experiences) && auth()->user()->experiences == '0-1 years' ? 'selected' : '' }}>
                                        {{ t('0-1 years') }}</option>
                                    <option value="1-3 years"
                                        {{ !empty(auth()->user()->experiences) && auth()->user()->experiences == '1-3 years' ? 'selected' : '' }}>
                                        {{ t('1-3 years') }}</option>
                                    <option value="3-5 years"
                                        {{ !empty(auth()->user()->experiences) && auth()->user()->experiences == '3-5 years' ? 'selected' : '' }}>
                                        {{ t('3-5 years') }}</option>
                                    <option value="5-10 years"
                                        {{ !empty(auth()->user()->experiences) && auth()->user()->experiences == '5-10 years' ? 'selected' : '' }}>
                                        {{ t('5-10 years') }}</option>
                                    <option value="10-20 years"
                                        {{ !empty(auth()->user()->experiences) && auth()->user()->experiences == '10-20 years' ? 'selected' : '' }}>
                                        {{ t('10-20 years') }}</option>
                                    <option value="20+ years"
                                        {{ !empty(auth()->user()->experiences) && auth()->user()->experiences == '20+ years' ? 'selected' : '' }}>
                                        {{ t('20+ years') }}</option>

                                </select>
                            </div>
                        </div>
                    @endif
                    @if (empty(auth()->user()->nationality))
                        <div class="form-group required nationality">
                            <label class="col-md-4 col-form-label" for="nationality">{{ t('Nationality') }}<sup
                                    style="color:red">*</sup></label>
                            <div class="col-md-12">
                                <select class="form-control select1" name="nationality" required>
                                    @if (!empty($data['nationality_list']))
                                        @foreach ($data['nationality_list'] as $key => $value)
                                            @if ($key !== 'Any')
                                                <option value="{{ $value }}"
                                                    {{ !empty(auth()->user()->nationality) && auth()->user()->nationality == $value ? 'selected' : '' }}>
                                                    {{ $key }}
                                                </option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    @endif
                    
                @if(auth()->check() &&  auth()->user()->country_code == 'KW' )
                        <div class="form-group row required visa">
                            <label class="col-md-4 col-form-label" for="visa">{{ t('Work Visa') }} <sup
                                    style="color:red">*</sup></label>
                            <div class="col-md-12">
                                <select id="work_visa" name="visa" class="form-control select1" required
                                    onchange="show_visa_box_model(this)">
                                    <option value="">{{ t('Choose an Option') }}</option>
                                    <option value="No, I don’t NEED a visa"
                                        {{ !empty(auth()->user()->visa) && auth()->user()->visa == 'No, I don’t NEED a visa' ? 'selected' : '' }}>
                                        {{ t('No, I don’t NEED a visa') }}</option>
                                    <option value="No, I don’t HAVE a visa"
                                        {{ !empty(auth()->user()->visa) && auth()->user()->visa == 'No, I don’t HAVE a visa' ? 'selected' : '' }}>
                                        {{ t('No, I don’t HAVE a visa') }}</option>
                                    <option value="Yes, I HAVE a visa"
                                        {{ !empty(auth()->user()->visa) && auth()->user()->visa == 'Yes, I HAVE a visa' ? 'selected' : '' }}>
                                        {{ t('Yes, I HAVE a visa') }}</option>

                                </select>


                            </div>


                        </div>
                        <div class="form-group row required country_of_work_visa_div" id="country_of_work_visa_div"
                            style="display:none">

                            <label class="col-md-4 col-form-label"
                                for="country_of_work_visa_div">{{ t('Country of Work Visa') }} <sup
                                    style="color:red">*</sup></label>
                            <div class="col-md-12">
                                <select class="form-control select1" name="country_work_visa" id="country_work_visa"
                                    onchange="work_visa_country_select(this)">

                                    @if (!empty($countries_list))
                                        <option value="">Select Country </option>
                                        @foreach ($countries_list as $item)
                                            <option value="{{ $item->code }}"
                                                {{ !empty(auth()->user()->country_work_visa) && auth()->user()->country_work_visa == $item->code ? 'selected' : '' }}>
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>

                            </div>
                        </div>
                        <div class="form-group row required visa_number_div" id="visa_number_div" style="display:none">
                            <label class="col-md-4 col-form-label" for="visa">{{ t('Work Visa Type') }} <sup
                                    style="color:red">*</sup></label>
                            <div class="col-md-12">
                                <select id="visa_number" name="visa_number" class="form-control select1">
                                    <option value="">{{ t('Choose an Option') }}</option>
                                    <?php 
                                    if(!empty($visa_types)):
                                    foreach ($visa_types as $visa) : ?>
                                    <?php foreach ($visa as $value => $label) : ?>
                                    <option value="<?php echo $value; ?>"
                                        {{ !empty(auth()->user()->visa_number) && auth()->user()->visa_number == $value ? 'selected' : '' }}>
                                        <?php echo $label; ?></option>
                                    <?php endforeach; ?>
                                    <?php endforeach;
                                         endif;
                                     ?>




                                </select>

                            </div>
                        </div>
                @endif
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{ t('update_profile') }}</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="employee_cv_modal" data-backdrop="static" role="dialog" data-dismiss="modal"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                {{ t('Upload CV') }}
                @if (str_contains(request()->url(), 'user_resume'))
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                @endif

            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="{{ url('account/resumes/reupload_resume') }}"
                      id="myform-reupload" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">{{ t('Upload CV') }}:</label>
                        <input type="file" id="reupload_resume" name="reupload_resume" class="file-input"
                            accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint,
                  text/plain, application/pdf"
                            required>
                        <input type="hidden" name="user_id"
                            value="{{ !empty(auth()->user()->id) ? auth()->user()->id : '' }}">
                    </div>
                    <div class="modal-footer">

                        <button type="submit" onclick="disabledbutton()" id="reupload"
                            class="btn btn-primary">Update</button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
<script>
    $('.select1').select2({
        width: "100%",
    });
    function disabledbutton() {
        document.getElementById('myform-reupload').submit();
        var btn = document.getElementById('reupload');
        btn.disabled = true;
    }
    $(document).ready(function() {


        $(".skill_set").select2({
            width: '100%',
            maximumSelectionLength: 5,
            placeholder: '{{ t('Employee Skills(you can choose more than one)') }}',
        })

    });

    function show_visa_box_model(obj) {
        var visa = $(obj).val();
        if (visa == 'Yes, I HAVE a visa') {
            $('.country_of_work_visa_div').show();
            addRequired();
        } else {
            $('.visa_number_div').hide();
            $('.country_of_work_visa_div').hide();
            removeRequired();
        }
    }

    function work_visa_country_select(obj) {
        var country = $(obj).val();
        if (country == 'KW') {
            $('.visa_number_div').show();
            $('#work_visa_number').prop('required', true);
        } else {
            $('.visa_number_div').hide();
            $('#work_visa_number').prop('required', false);
        }
    }
    // Function to add the "required" attribute to input elements
    function addRequired() {

        $('#country_work_visa').prop('required', true);
    }

    // Function to remove the "required" attribute from input elements
    function removeRequired() {

        $('#country_work_visa').prop('required', false);
    }
</script>
