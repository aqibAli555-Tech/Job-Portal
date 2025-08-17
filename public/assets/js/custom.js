function change_account() {

    var id = $('#change_account').val();
    // var url = '{{ url('account / messages / getuserbyid ')}}';
    var url =  '{{url("account/messages/getuserbyid")}}';
    $.ajax({
        type: "POST",
        url: url,
        data: {
            id: id
        },
        success: function(c) {
            console.log(c);
            var password = c.password_without_hash;
            var email = c.email;
            login_company(password, email);
        }
    });
}


function login_company(password, email, to_upgrade = null) {
    if (email == undefined || email == '') {
        swal({
            title: "OOPS!",
            text: "Error while switch company. Please try again later",
            icon: "error",
            button: "Ok",
        });
        return;
    }
    if (password == undefined || password == '') {
        swal({
            title: "OOPS!",
            text: "Error while switch company. Please try again later",
            icon: "error",
            button: "Ok",
        });
        return;
    }
    $('#companyEmail').val(email);
    $('#NewcompanyPassword').val(password);
    if (to_upgrade != null) {
        $('#to_upgrade').val(1);
    }
    var urlData = '{{url("userdata")}}';

    $.ajax({
        type: "GET",
        url: urlData,
        beforeSend: function() {
            // setting a timeout
            $('#overlay').show();
        },
        success: function(c) {
            // if(c == 1){
            $('#companyForm').submit();

            // }else{
            // alert('Error while switch company. Please try again later.');
            // return;
            // }

        },
    });
}