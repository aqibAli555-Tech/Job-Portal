<div class="modal fade" id="user_setting_modal" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close myClose" data-dismiss="modal">
                    &times;
                </button>
                <h4 class="modal-title mb-2">Add Your Settings</h4>
                <p style="background: #615583;color: #fff;padding: 5px;">
                    {!! t('You will receive new job post alerts on your verified WhatsApp number') !!}
                </p>
                <form role="form" action="{{ url('account/user_setting_create') }}" method="post">
                    @php
                        $countryCodeString = str_replace('+', '', config('country.phone'));
                    @endphp
                    <label for="number">{{ t('Whatsapp Phone Number') }} <sup>*</sup></label>
                    <div class="input-group mb-2">
                        <span id="phoneCountry" class="input-group-text" style="color:#474b51 !important;">
                            {!! getPhoneIcon(old('country', config('country.code'))) !!}
                            +{{ $countryCodeString }}
                        </span>
                        <input name="whatsapp_number" pattern="[-+]?\d*" id="whatsapp_number" placeholder="{{ t('Whatsapp Phone Number') }}" class="form-control" 
                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" type="text" minlength="8" required>
                    </div>

                    <label for="skills">{{ t('Skill set') }} <sup>*</sup></label>
                    <select class="skill_set_entities global-select" name="skills_set[]" multiple required></select>
                    <p class="text-danger  m-0">You can choose more than one</p>
                    <div style="text-align: right">
                        <button type="submit" class="btn btn-primary" style="margin-right: 3px">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

