<?php

use App\Http\Controllers\PageController;

if (auth()->check()) {
    $companies = PageController::userCompanies();
}
?>

<div class="apply-popup">
    <div class="modal fade" id="apply-popup-id" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i data-feather="edit"></i>{{ t('Contact Employer') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form role="form" id="form" action="#">
                        <div class="form-group">
                            <select class="form-control" name="compamy" id="company" required="required">
                                @foreach($companies as $key => $item)
                                <option value="{{$item}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                            <textarea id="message" name="message" class="form-control"
                                      placeholder="{{ t('Your message here...') }}"
                                      rows="10">{{ old('message') }}</textarea>
                            <small>{{ t('Message') }} (2000 max) <sup style="color: red">*</sup></small>
                        </div>
                        <button type="submit" class="button primary-bg btn-block" id="sendBtn">Apply Now
                            <img id="isLoad" style="width: 22px;display: none"
                                 src="{{url('/')}}/new_assets/images/spinning-wheel.gif" alt=""></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://www.gstatic.com/firebasejs/4.9.0/firebase.js"></script>
<script src="https://www.gstatic.com/firebasejs/4.9.0/firebase-firestore.js"></script>
<script>
    // Initialize Firebase
    var config = {
        apiKey: "",
        authDomain: "hungryforjob-web.firebaseapp.com",
        databaseURL: "https://hungryforjob-web.firebaseio.com",
        projectId: "hungryforjob-web",
        storageBucket: "hungryforjob-web.appspot.com",
        messagingSenderId: "334502409431",
    };
    firebase.initializeApp(config);
    var db = firebase.firestore();
    const docRef = db.collection("messages");
    var datetime = new Date();

    $('#form').submit(function (e) {
        e.preventDefault();
        var company = '';
        company = $('#company').val();
        if (company == '') {
        } else {
            company = JSON.parse(company);
            var ifchecker = false;
            var result = {
                filename: '',
                from_email: company.email,
                from_name: company.name,
                from_phone: company.phone,
                from_user_id: company.user_id,
                message: $('#message').val(),
                to_image: '{{$data->user->file}}',
                from_image: company.logo,
                post_id: '',
                id: '',
                subject: 'New Message',
                to_email: '{{$data->user->email}}',
                to_name: '{{$data->user->name}}',
                to_phone: '{{$data->user->phone}}',
                to_user_id: '{{$data->user_id}}',
            };
            console.log(result, String(result.from_user_id));

            docRef.where("from_user_id", "==", String(result.to_user_id)).where("to_user_id", "==", String(result.from_user_id))
                .onSnapshot(function (querySnapshot) {
                    var length = querySnapshot.docs.length;
                    console.log(length, ' 89');
                    if (length == 0) {


                        if (ifchecker == false) {
                            docRef.doc('<?=time();?>').set({
                                id: result.id,
                                is_read_from: '1',
                                is_read_to: '0',
                                created_at: datetime,
                                filename: result.filename,
                                from_email: result.to_email,
                                from_name: result.to_name,
                                from_phone: result.to_phone,
                                from_user_id: String(result.to_user_id),
                                message: result.message,
                                to_image: result.from_image,
                                from_image: result.to_image,
                                post_id: result.post_id,
                                subject: result.subject,
                                to_email: result.from_email,
                                to_name: result.from_name,
                                to_phone: result.from_phone,
                                to_user_id: String(result.from_user_id),
                                updated_at: datetime,
                            }).then(function (e) {
                                console.log('saved');
                                docRef.doc('<?=time();?>').collection('sub_messages').doc().set({
                                    id: result.id,
                                    is_read_from: '1',
                                    is_read_to: '0',
                                    created_at: datetime,
                                    filename: result.filename,
                                    from_email: result.from_email,
                                    from_name: result.from_name,
                                    from_phone: result.from_phone,
                                    from_user_id: String(result.from_user_id),
                                    message: result.message,
                                    to_image: result.to_image,
                                    from_image: result.from_image,
                                    post_id: result.post_id,
                                    subject: result.subject,
                                    to_email: result.to_email,
                                    to_name: result.to_name,
                                    to_phone: result.to_phone,
                                    to_user_id: String(result.to_user_id),
                                    updated_at: datetime,
                                }).then(function (e) {
                                    console.log('saved ==== ');
                                    $('#form')[0].reset();
                                }).catch(function (error) {
                                    console.log(error);
                                });
                            }).catch(function (error) {
                                console.log(error);
                            });
                        }
                        ifchecker = true;
                    } else {
                        if (ifchecker == false) {
                            var docId = querySnapshot.docs[0].id;
                            console.log(docId, ' 148');
                            docRef.doc(docId).collection('sub_messages').doc().set({
                                id: result.id,
                                from_read: '1',
                                to_read: '0',
                                created_at: datetime,
                                filename: result.filename,
                                from_email: result.from_email,
                                from_name: result.from_name,
                                to_image: result.to_image,
                                from_image: result.from_image,
                                from_phone: result.from_phone,
                                from_user_id: String(result.from_user_id),
                                message: result.message,
                                post_id: result.post_id,
                                subject: result.subject,
                                to_email: result.to_email,
                                to_name: result.to_name,
                                to_phone: result.to_phone,
                                to_user_id: String(result.to_user_id),
                                updated_at: datetime,
                                docId: docId,
                            }).then(function (e) {
                                console.log('saved');
                                $('#form')[0].reset();

                            }).catch(function (error) {
                                console.log(error);
                            });
                            ifchecker = true;
                        }
                    }
                });
        }

    });
</script>
