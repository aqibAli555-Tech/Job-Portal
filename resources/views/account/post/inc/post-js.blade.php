<script>
    $(document).ready(function () {
        $('#filterInput').on('keyup', function () {
            var value = $(this).val().toLowerCase();
            $('#addManageTable tbody tr').filter(function () {
                $(this).toggle($(this).find('td').first().text().toLowerCase().indexOf(value) > -1);
            });
        });
        loadPosts('{{$pagePath}}')
    });

    $('#myTab a[data-toggle="tab"]').on('shown.bs.tab', function (event) {
        selectedTabIndex = $(event.target).attr('href');
        loadPosts(selectedTabIndex);
    });

    function loadPosts(pagePath = 'my-posts') {
        let url = '';
        if (pagePath == 'my-posts') {
            url = '{{ url('account/get-my-posts') }}';
            $('.archived-text').hide();
        } else if (pagePath == 'posts-archived') {
            $('.archived-text').show();
            url = '{{ url('account/archived') }}';
        }else if(pagePath == 'favourite'){
            url = '{{ url('account/get-favourite-posts') }}';
        }
        document.querySelector('.table-data').innerHTML = '<center>Loading...</center>';
        fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        }).then(response => {
            if (!response.ok) {
                throw new Error(`Network response was not ok (${response.statusText})`);
            }
            return response.json();
        }).then(data => {
            if (data.status && data.html) {
                document.querySelector('.table-data').innerHTML = data.html;
            } else {
                showSwalAlert('Error',data.message, 'error', 'Ok')
            }
        }).catch(error => {
            console.error('Fetch error:', error);
            document.querySelector('.table-data').innerHTML = '<p>Failed to load posts. Please try again later.</p>';
        });
    }


    function open_reason_model(id, type) {
        $('#type').val(type);
        $('#post_id_for_Reason').val(id);
        $('#post-archived-reason').modal('show');
    }

    function save_reasons() {
        var postId = $('#post_id_for_Reason').val();
        var reason_id = $('#reason_id').val();
        var type = $('#type').val();
        if (reason_id == '' || reason_id == null) {
            $('#post-archived-reason').modal('hide');
            showSwalAlert('Error', 'Please select reason first', 'error', 'Ok')
            return false;
        }
        let url = '';
        if (type == 'archived') {
            url = '{{ url("account/add_archived") }}/' + postId + '/offline?reason_id=' + reason_id;
        } else {
            url = '{{ url("account") }}/' + postId + '/delete?reason_id=' + reason_id;
        }

        fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        }).then(response => response.json())
            .then(c => {
                $('#post-archived-reason').modal('hide');
                loadPosts('{{$pagePath}}');
                if (c.status) {
                    showSwalAlert('Success', c.message, 'success', 'Ok');
                } else {
                    showSwalAlert('Error', c.message, 'error', 'Ok');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showSwalAlert('Error', 'Something went wrong, please try again later.', 'error', 'Ok');
            });
    }


    function repost(id) {
        Swal.fire({
            title: "Are you sure?",
            text: "Post will be live after that",
            icon: "warning",
            confirmButtonText: '"Yes, proceed!"',
            showCancelButton: true,

        }).then((result) => {
            if (result.isConfirmed) {
                var myurl = '{{ url('account') }}/' + id + '/repost';
                fetch(myurl, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                }).then(response => response.json())
                    .then(response => {
                        const url = '<?= url('account/upgrade') ?>';

                        if (response.status) {
                            loadPosts('posts-archived');
                            showSwalAlert('Success', response.message, 'success', 'Ok');
                        } else {
                            showSwalAlert('Error', response.message, 'error', 'Ok');

                            if (response.redirect) {
                                window.location.href = url;
                            }
                        }
                    }).catch(error => {
                        console.error('Error:', error);
                        showSwalAlert('Error', 'Something went wrong, please try again later.', 'error', 'Ok');
                    });
            } else {
                showSwalAlert('Info', 'Action canceled.', 'info', 'Ok');
            }
        });
    }

    var lang = {
        labelSavePostSave: "{!! t('Favourite Job') !!}",
        labelSavePostRemove: "{{ t('Favourited Job') }}",
        loginToSavePost: "{!! t('Please log in to save the Ads') !!}",
        loginToSaveSearch: "{!! t('Please log in to save your search') !!}",
        confirmationSavePost: "{!! t('Post saved in favorites successfully') !!}",
        confirmationRemoveSavePost: "{!! t('Post deleted from favorites successfully') !!}",
        confirmationSaveSearch: "{!! t('Search saved successfully') !!}",
        confirmationRemoveSaveSearch: "{!! t('Search deleted successfully') !!}"
    };

    function favouritePost(elmt, id) {
        const postId = id;
        const token = document.querySelector('input[name=_token]').value;
        const siteUrl = window.siteUrl;
        const lang = window.lang;

        fetch(`${siteUrl}/ajax/save/post`,{
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({ postId: postId })
        }).then(response => response.json())
            .then(data => {
                if (typeof data.logged === "undefined") {
                    return false;
                }
                if (data.logged === 0) {
                    document.getElementById('quickLogin').modal();
                    return false;
                }
                if (data.status === 1) {
                    Swal.fire({
                        text: lang.confirmationSavePost,
                        icon: "success",
                        button: "Ok"
                    }).then(() => {
                        loadPosts('favourite');
                    });
                } else {
                    Swal.fire({
                        text: lang.confirmationRemoveSavePost,
                        icon: "success",
                        button: "Ok"
                    }).then(() => {
                        loadPosts('favourite');
                    });
                }
                return false;
            }).catch(error => {
                console.error('Error:', error);
            });
        return false;
    }



</script>