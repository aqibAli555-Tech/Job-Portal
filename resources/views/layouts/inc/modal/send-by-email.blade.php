<div class="modal fade" id="sendByEmail" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <div class="modal-title">
                    <i class="fa icon-info-circled-alt"></i> {{ t('Share job') }}
                </div>

                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">{{ t('Close') }}</span>
                </button>
            </div>

            <form role="form" method="POST" action="{{ url('send-by-email') }}">
                <div class="modal-body">

                    @if (isset($errors) and $errors->any() and old('sendByEmailForm')=='1')
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <ul class="list list-check">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    {!! csrf_field() !!}

                    <!-- sender_email -->
                    @if (auth()->check() and isset(auth()->user()->email))
                    <input type="hidden" name="sender_email" value="{{ auth()->user()->email }}">
                    @else
                    <?php $senderEmailError = (isset($errors) and $errors->has('sender_email')) ? ' is-invalid' : ''; ?>
                    <div class="form-group required">
                        <label for="sender_email" class="control-label">{{ t('By Email') }} <sup>*</sup></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <i class="fa fa-envelope border p-2"
                                   style="padding-top: 12px !important; background-color: white; color: #22d3fd;"
                                   aria-hidden="true"></i>
                            </div>
                            <input id="sender_email" name="sender_email" type="text" maxlength="60"
                                   class="form-control{{ $senderEmailError }}" value="{{ old('sender_email') }}" required>
                        </div>
                    </div>
                    @endif

                    <!-- recipient_email -->
                    <?php $recipientEmailError = (isset($errors) and $errors->has('recipient_email')) ? ' is-invalid' : ''; ?>
                    <div class="form-group required">
                        <label for="recipient_email" class="control-label">{{ t('To Email') }} <sup>*</sup></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <i class="fa fa-envelope border p-2"
                                   style="padding-top: 12px !important; background-color: white; color:#615583;"
                                   aria-hidden="true"></i>
                            </div>
                            <input id="recipient_email" name="recipient_email" type="text" maxlength="60"
                                   class="form-control{{ $recipientEmailError }}" value="{{ old('recipient_email') }}" required>
                            <br>
                            <button type="submit" style="float: right" class="btn btn-primary-dark">{{ t('Share') }}</button>
                        </div>
                    </div>
                    <input type="hidden" id="post" id="post_id" name="post" value="{{ old('post') }}">
                    <input type="hidden" name="sendByEmailForm" value="1">
                    <div class="form-group">
                        <div class="row" style="padding-left: 14px;">
                            <label class="control-label">{{t('Also share on:')}}</label>
                            <br>
                            <div class="col-md-8">
                                <a class="btn btn-primary-dark btn-sm" id="whatsapp_share" title="" data-placement="top" target="_blank" data-toggle="tooltip"
                                   href=""
                                   data-original-title="Facebook">
                                    <i class="fab fa-whatsapp"></i>
                                </a>

                                <a class="btn btn-primary-dark btn-sm" id="fb_share" title="" data-placement="top" target="_blank" data-toggle="tooltip"
                                   href=""
                                   data-original-title="Facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>

                                <a class="btn btn-primary-dark btn-sm" id="telegram_share" title="" data-placement="top" target="_blank" data-toggle="tooltip"
                                   href="">
                                    <i class="fab fa-telegram"></i>
                                </a>

                                <a class="btn btn-primary-dark btn-sm" id="twitter_share" title="" data-placement="top" target="_blank" data-toggle="tooltip"
                                   href="">
                                    <i class="fab fa-twitter"></i>
                                </a>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ t('Cancel') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>

</script>