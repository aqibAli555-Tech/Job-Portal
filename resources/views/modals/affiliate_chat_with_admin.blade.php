<div class="modal fade modal-delete" id="modal_chat_with_admin" style="z-index: 111111111;" tabindex="1"
     role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close myClose" data-dismiss="modal">
                    &times;
                </button>
                <h4><i data-feather="calendar"></i>Start A Conversation </h4>
                <p>Chat With Hungry For Jobs Team</p>
                <form role="form" id="form2" action="{{ url('affiliate/messages/messagesend/0') }}" method="post">
                    <textarea style="border: 1px solid; !important;height: 144px;" class="form-control" name="message"></textarea>
                    <br>
                    <input type="hidden" name="send_user_id" id="send_user_id" value="1">
                    <div style="text-align: right">
                        <button href="" type="submit" class="btn btn-primary" style="margin-right: 3px">Send Message</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>