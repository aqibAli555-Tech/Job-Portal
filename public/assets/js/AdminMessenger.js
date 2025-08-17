function loadThreads(options = {}, callback = null) {
    var search = options.search || '';
    var page = options.page || 1;

    $.ajax({
        url: 'messages',
        method: 'GET',
        data: {
            search: search,
            page: page
        },
        success: function (response) {
            $('#threads-list').html(response.html);
            $('#pagination').html(response.pagination);

            if (typeof callback === 'function') {
                callback();
            }
        },
        error: function (xhr) {
            console.error('An error occurred:', xhr);
        }
    });
}

$(document).on('keyup', '#search-input', function () {
    var search = $(this).val();
    loadThreads({search: search});
});

function load_new_threads(page = 1, callback = null) {
    loadThreads({page: page}, callback);
}


var loading = false;
var page = 1;
var maxPages = 1;

function load_messages(thread_id) {
    $('#thread_id').val(thread_id);

    var thread_id_check = $('#thread_id').val();
    if (thread_id_check) {
        $('.type-chat').show();
    } else {
        $('.type-chat').hide();
    }

    $('.chat').empty();
    maxPages = 1;
    page = 1;
    messages();
}

function messages(page = 1, type = 'prepend') {
    if (loading || page > maxPages) return;
    loading = true;

    var thread_id = $('#thread_id').val();
    $.ajax({
        url: `messages/${thread_id}?page=${page}`,
        method: 'GET',
        data: {},
        success: function (response) {
            if (response.messages) {
                if (type == 'prepend') {
                    $('.chat').prepend(response.messages);
                } else {
                    if (response.messages) {
                        let tempContainer = $('<div></div>').html(response.messages); // Temporary container
                        $('.chat').html(tempContainer.html()); // Replace content without blink
                    }
                    // $('.chat').append(response.messages)
                }
            }

            $('.discussion').removeClass('message-active');
            $(`.discussion[data-thread-id="${thread_id}"]`).addClass('message-active');

            if (response.pages) {
                maxPages = response.pages;
            }
            if (page == 1) {
                scrollToBottom();
            }
            loading = false;
        },
        error: function (xhr) {
            console.error('An error occurred:', xhr);
        }
    });
}

function scrollToBottom() {
    var chatContainer = $('.chat');
    chatContainer.scrollTop(chatContainer[0].scrollHeight);
}

$('.chat').on('scroll', function () {
    var chatContainer = $(this);
    if (chatContainer.scrollTop() === 0 && !loading) {
        page++;
        messages(page);
    }
});
setInterval(function () {
    var thread_id = $('#thread_id').val();
    if (thread_id) {
        // $('.chat').empty(); // Clear existing messages

        maxPages = 1;
        page = 1;
        messages(page, 'append');
    }
    load_new_threads(1, function () {
        $(`.discussion[data-thread-id="${thread_id}"]`).addClass('message-active');
    });
}, 10000000);


$('#chatForm').on('submit', function (e) {
    e.preventDefault();

    updateChat(this);
    var chatTextField = $('#body');
    var chatFileFiled = $('#addFile');
    chatTextField.val('');
    clearFileInput(chatFileFiled);

    return false;
});

function updateChat(formElement) {
    var thread_id = $('#thread_id').val();
    var formUrl = 'messages/update/' + thread_id;
    var formData = new FormData(formElement);

    console.log(formData);
    $.ajax({
        url: formUrl,
        method: 'POST',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            maxPages = 1;
            page = 1;
            messages(1, 'append');
            $('#body').val('').focus();
            clearFileInput($('#addFile'));
            $('#search-input').val('');
            load_new_threads(1, function () {
                $(`.discussion[data-thread-id="${thread_id}"]`).addClass('message-active');
            });
        },
        error: function (xhr) {
            console.error('An error occurred:', xhr);
        }
    });
}


function clearFileInput(input) {
    input.replaceWith(input.val('').clone(true));
}

function confirmDeletion(threadId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'messages/actions/' + threadId + '?type=delete',
                type: 'GET',
                success: function (response) {
                    Swal.fire(
                        'Deleted!',
                        'Your thread has been deleted.',
                        'success'
                    );
                    $('.chat').html('');

                    load_new_threads();

                },
                error: function (xhr) {
                    Swal.fire(
                        'Error!',
                        'There was a problem deleting your thread.',
                        'error'
                    );
                }
            });
        }
    });
}

function start_new_conversation() {
    $('#start-new-conevrsation').modal('show');
}

function confirmMessageDeletion(messageId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            var formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            $.ajax({
                url: 'messages/delete/' + messageId,
                method: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    Swal.fire(
                        'Deleted!',
                        'Your Messege has been deleted.',
                        'success'
                    );
                    if (response.success) {
                        $('#message-' + messageId).fadeOut(300, function() {
                            $(this).remove();
                        });
                        load_new_threads();
                    } else {
                        alert(response.msg);
                    }

                },
                error: function (xhr) {
                    alert('issue');
                    Swal.fire(
                        'Error!',
                        'There was a problem deleting your message.',
                        'error'
                    );
                }
            });
        }
    });
}

function editMessage(messageId) {
    var messageText = $('#message-' + messageId + ' .text p').text().trim();
    $('#edit-message-body').val(messageText);
    $('#update-message-btn').data('message-id', messageId);
    $('#edit-message-modal').modal('show');
}

function updateMessage() {
    var messageId = $('#update-message-btn').data('message-id');
    var updatedMessage = $('#edit-message-body').val().trim();
    
    if (updatedMessage === '') {
		$('#edit-message-modal').modal('hide');
        Swal.fire('Error!', 'Message cannot be empty.', 'error');
        return;
    }

    $.ajax({
        url: 'messages/edit/' + messageId,
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            message: updatedMessage
        },
        success: function(response) {
            if (response.success) {
                $('#message-' + messageId + ' .text p').text(updatedMessage);
                
                $('#edit-message-modal').modal('hide');
                
                Swal.fire('Updated!', 'Your message has been updated.', 'success');
            } else {
                Swal.fire('Error!', response.msg, 'error');
            }
        },
        error: function(xhr) {
            Swal.fire('Error!', 'There was a problem updating your message.', 'error');
        }
    });
}

$('#chatAffiliateForm').on('submit', function (e) {
    e.preventDefault();

    updateAffiliateChat(this);
    var chatTextField = $('#body');
    var chatFileFiled = $('#addFile');
    chatTextField.val('');
    clearFileInput(chatFileFiled);

    return false;
});

function updateAffiliateChat(formElement) {
    var thread_id = $('#thread_id').val();
    var formUrl = 'affiliate_messages/update/' + thread_id;
    var formData = new FormData(formElement);

    console.log(formData);
    $.ajax({
        url: formUrl,
        method: 'POST',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            maxPages = 1;
            page = 1;
            affiliateMessages(1, 'append');
            $('#body').val('').focus();
            clearFileInput($('#addFile'));
            $('#search-input').val('');
            load_new_affiliate_threads(1, function () {
                $(`.discussion[data-thread-id="${thread_id}"]`).addClass('message-active');
            });
        },
        error: function (xhr) {
            console.error('An error occurred:', xhr);
        }
    });
}

function loadAffiliateThreads(options = {}, callback = null) {
    var search = options.search || '';
    var page = options.page || 1;

    $.ajax({
        url: 'affiliate_messages',
        method: 'GET',
        data: {
            search: search,
            page: page
        },
        success: function (response) {
            $('#threads-list').html(response.html);
            $('#pagination').html(response.pagination);

            if (typeof callback === 'function') {
                callback();
            }
        },
        error: function (xhr) {
            console.error('An error occurred:', xhr);
        }
    });
}

$(document).on('keyup', '#search-input', function () {
    var search = $(this).val();
    loadAffiliateThreads({search: search});
});

function load_new_affiliate_threads(page = 1, callback = null) {
    loadAffiliateThreads({page: page}, callback);
}

function confirmAffiliateMessageDeletion(messageId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            var formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            $.ajax({
                url: 'affiliate_messages/delete/' + messageId,
                method: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    Swal.fire(
                        'Deleted!',
                        'Your Messege has been deleted.',
                        'success'
                    );
                    if (response.success) {
                        $('#message-' + messageId).fadeOut(300, function() {
                            $(this).remove();
                        });
                        load_new_affiliate_threads();
                    } else {
                        alert(response.msg);
                    }

                },
                error: function (xhr) {
                    alert('issue');
                    Swal.fire(
                        'Error!',
                        'There was a problem deleting your message.',
                        'error'
                    );
                }
            });
        }
    });
}

var affiliateLoading = false;
var affiliatePage = 1;
var affiliatemaxPages = 1;

function load_affiliate_messages(thread_id) {
    $('#thread_id').val(thread_id);

    var thread_id_check = $('#thread_id').val();
    if (thread_id_check) {
        $('.type-chat').show();
    } else {
        $('.type-chat').hide();
    }

    $('.chat').empty();
    affiliatemaxPages = 1;
    affiliatePage = 1;
    affiliateMessages();
}

function affiliateMessages(affiliatePage = 1, type = 'prepend') {
    if (affiliateLoading || affiliatePage > affiliatemaxPages) return;
    affiliateLoading = true;

    var thread_id = $('#thread_id').val();
    $.ajax({
        url: `affiliate_messages/${thread_id}?page=${affiliatePage}`,
        method: 'GET',
        data: {},
        success: function (response) {
            if (response.messages) {
                if (type == 'prepend') {
                    $('.chat').prepend(response.messages);
                } else {
                    if (response.messages) {
                        let tempContainer = $('<div></div>').html(response.messages); // Temporary container
                        $('.chat').html(tempContainer.html()); // Replace content without blink
                    }
                    // $('.chat').append(response.messages)
                }
            }

            $('.discussion').removeClass('message-active');
            $(`.discussion[data-thread-id="${thread_id}"]`).addClass('message-active');

            if (response.pages) {
                affiliatemaxPages = response.pages;
            }
            if (page == 1) {
                scrollToBottom();
            }
            affiliateLoading = false;
        },
        error: function (xhr) {
            console.error('An error occurred:', xhr);
        }
    });
}

function confirmAffiliateDeletion(threadId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'messages/actions/' + threadId + '?type=delete',
                type: 'GET',
                success: function (response) {
                    Swal.fire(
                        'Deleted!',
                        'Your thread has been deleted.',
                        'success'
                    );
                    $('.chat').html('');

                    load_new_affiliate_threads();

                },
                error: function (xhr) {
                    Swal.fire(
                        'Error!',
                        'There was a problem deleting your thread.',
                        'error'
                    );
                }
            });
        }
    });
}

function copyMessageText(id) {
    const messageElement = document.querySelector(`#message-${id} .text p`);
    if (messageElement) {
        const text = messageElement.innerText;
        navigator.clipboard.writeText(text).then(() => {
            Swal.fire(
                'Copied!',
                'Message has been copied.',
                'success'
            );
        }).catch(err => {
            Swal.fire(
                'Error!',
                'Copy failed.',
                'error'
            );
        });
    }
}