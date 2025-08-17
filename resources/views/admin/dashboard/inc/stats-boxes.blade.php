<div class="row">
    <?php
    $net_profit = 0;
    $active = 'active';
    $inactive = 'inactive';
    $stats = [];
    if (isset($number_of_employees)) {
        $stats[] = ['title' => '# of Employees', 'link' => admin_url('job-seekers'), 'counter' => $number_of_employees, 'icon' => 'user', 'background_color' => '#FFFFFF', 'text_color' => 'black'];
    }
    if (isset($number_of_employeers)) {
        $stats[] = ['title' => '# of Employers', 'link' => admin_url('employer'), 'counter' => $number_of_employeers, 'icon' => 'users', 'background_color' => '#5F567F', 'text_color' => 'white'];
    }
    if (isset($revenue)) {
        $stats[] = ['title' => 'Revenue', 'link' => admin_url('payments?search=paid'), 'counter' => $revenue, 'icon' => 'dollar-sign', 'background_color' => '#FFFFFF', 'text_color' => 'black'];
    }
    if (!empty($net_profit)) {
        $stats[] = ['title' => 'Revenue', 'link' => admin_url('payments?search=paid'), 'counter' => $net_profit, 'icon' => 'list', 'background_color' => '#5F567F', 'text_color' => 'white'];
    }
//    if (isset($verfied_phone_users)) {
//        $stats[] = ['title' => 'Verified Contact', 'link' => 'javascript:void(null)', 'counter' => $verfied_phone_users, 'icon' => 'list','background_color'=>'#FFFFFF','text_color'=>'black'];
//    }
    if (isset($top_life_time_users)) {
        $stats[] = ['title' => "Life Time Subscription Paid", 'link' => admin_url('payments'), 'counter' => $top_life_time_users, 'icon' => 'list', 'background_color' => '#5F567F', 'text_color' => 'white'];
    }
    if (isset($current_subscription_users)) {
        $stats[] = ['title' => "Active Subscription Users", 'link' => admin_url('employer') . '?current_subscription_users=' . $current_subscription_users, 'counter' => $current_subscription_users, 'icon' => 'check', 'background_color' => '#5F567F', 'text_color' => 'white'];
    }
    if (isset($cuurentjobs)) {
        $stats[] = ['title' => 'Active Total Jobs', 'link' => admin_url('get_posts/?type=current_posts'), 'counter' => $cuurentjobs, 'icon' => 'briefcase', 'background_color' => '#FFFFFF', 'text_color' => 'black'];
    }
    if (isset($lifetimejobs)) {
        $stats[] = ['title' => 'Life Time Total Jobs', 'link' => admin_url('get_posts/?type=' . 'life_time_posts'), 'counter' => $lifetimejobs, 'icon' => 'shopping-bag', 'background_color' => '#5F567F', 'text_color' => 'white'];
    }

    if (isset($pending_applicants)) {
        $stats[] = ['title' => 'Pending Applicants', 'link' => admin_url('applicants/?status=' . 'pending'), 'counter' => $pending_applicants, 'icon' => 'pen-tool', 'background_color' => '#FFFFFF', 'text_color' => 'black'];
    }

    $stats[] = ['title' => ' Email Check Credits', 'link' => '#', 'counter' => !empty($assign_cridit->credits) ? $assign_cridit->credits : 0, 'icon' => 'file-text', 'background_color' => '#FFFFFF', 'text_color' => 'black'];


    if (!empty($total_applicants)) {
        $stats[] = ['title' => 'Total Applicants', 'link' => admin_url('applicants?normal_applicant=1'), 'counter' => $total_applicants, 'icon' => 'user-check', 'background_color' => '#5F567F', 'text_color' => 'white'];
    }

    if (!empty($total_search_cv_applicants)) {
        $stats[] = ['title' => 'Contacts Unlocked From Search Database', 'link' => admin_url('applicants?unlock_applicant=1'), 'counter' => $total_search_cv_applicants, 'icon' => 'unlock', 'background_color' => '#FFFFFF', 'text_color' => 'black'];
    }

    if (!empty($total_unlock)) {
        $stats[] = ['title' => 'Unlocked Contact Cards (Includes Search Database)', 'link' => admin_url('applicants'), 'counter' => $total_unlock, 'icon' => 'unlock', 'background_color' => '#5F567F', 'text_color' => 'white'];
    }

    $stats[] = ['title' => "Pending CV’s without Contact Details", 'link' => admin_url('job-seekers?no_contact_cv=no'), 'counter' => $count_no_contact_Cv, 'icon' => 'unlock', 'background_color' => '#5F567F', 'text_color' => 'white'];

    $stats[] = ['title' => 'Pending Jobs From Customized Skills Sets', 'link' => admin_url('get_posts/?pending_post=1'), 'counter' => $pending_jobs, 'icon' => 'file-text', 'background_color' => '#FFFFFF', 'text_color' => 'black'];
    $stats[] = ['title' => 'Interview', 'link' => admin_url('applicants/?status=interview'), 'counter' => $interview, 'icon' => 'search', 'background_color' => 'black', 'text_color' => 'white'];
    $stats[] = ['title' => 'Hired', 'link' => admin_url('applicants/?status=hired'), 'counter' => $hired, 'icon' => 'link', 'background_color' => '#91bf33', 'text_color' => 'black'];
    $stats[] = ['title' => 'Rejected', 'link' => admin_url('applicants/?status=rejected'), 'counter' => $rejected, 'icon' => 'user-x', 'background_color' => 'red', 'text_color' => 'black'];
    $stats[] = ['title' => "New Updated CV's", 'link' => admin_url('job-seekers?updated_cv=true'), 'counter' => count($unapproved_new_cv), 'icon' => 'file-text', 'background_color' => '#5F567F', 'text_color' => 'white'];
    $stats[] = ['title' => "New Updated Skills", 'link' => admin_url('job-seekers?updated_skill=true'), 'counter' => count($unapproved_skill), 'icon' => 'file-text', 'background_color' => '#FFFFFF', 'text_color' => 'black'];
    $stats[] = ['title' => 'Page Analytics', 'link' => admin_url('get-page-analytics'), 'counter' => '', 'icon' => 'file-text', 'background_color' => '#5F567F', 'text_color' => 'white'];
    $stats[] = ['title' => 'Email Stats', 'link' => admin_url('email_stats'), 'counter' => '', 'icon' => 'file-text', 'background_color' => '#5F567F', 'text_color' => 'white'];
    $stats[] = ['title' => 'Chats With Companies', 'link' => admin_url('messages'), 'counter' => $unread_threads, 'icon' => 'message-circle', 'background_color' => '#FFFFFF', 'text_color' => 'black'];
    $stats[] = ['title' => 'Affiliate Stats', 'link' => admin_url('affiliate_dashboard_stats'), 'counter' => '', 'icon' => 'link', 'background_color' => '#FFFFFF', 'text_color' => 'black'];
    $stats[] = ['title' => "Free No Contact CV Views", 'link' => admin_url('get_logs?type=cv_no_contact'), 'counter' => $free_cv_no_contact, 'icon' => 'file-text', 'background_color' => '#5F567F', 'text_color' => 'white'];
    if(IS_WHATSAPP_ALLOWED){
        $stats[] = ['title' => 'WhatsApp Users', 'link' => admin_url('whatsapp'), 'counter' => $whatsapp_users_count, 'icon' => 'file-text', 'background_color' => '#FFFFFF', 'text_color' => 'black'];
    }

    $counter = 0;
    foreach ($stats as $stat) {
        $class = '';
        $hideRevenue = '';
        if ($stat['title'] == 'Revenue') {
            $class = 'revenue';
            $hideRevenue = 'hidden revenue';
        }
        $class_counter = '';
        if ($stat['title'] == 'Chats With Companies') {
            $class_counter = 'message_counter';
        }
        $class_affiliate_counter = '';
        if ($stat['title'] == 'Affiliate Stats') {
            $class_affiliate_counter = 'affiliate_message_counter';
        }
        $with_draw_request_class = '';
        if ($stat['title'] == 'Affiliate Stats') {
            $with_draw_request_class = 'withdraw_request_counter';
        }
        ?>
    @if($stat['title'] == 'Revenue')
        <div class="col-lg-3 col-xs-6" style="cursor: pointer;">
            @else
                <div class="col-lg-3 col-xs-6" style="cursor: pointer;"
                     onclick="window.location.href = '{{ $stat['link'] }}'">
                    @endif
                    <div class="card border-end">
                        <div class="card-body" style="min-height: 104px;background: <?= $stat['background_color'] ?>;">
                            <div class="d-flex align-items-center">
                                <div>
                                    <a href="{{ $stat['link'] }}" class="d-inline-flex align-items-center">
                                        <h2 style="color: <?= $stat['text_color'] ?> !important;"
                                            class="text-dark mb-1 font-weight-medium <?= $hideRevenue ?> <?= $class_counter ?> <?= $class_affiliate_counter ?> <?= $with_draw_request_class ?>">
                                            {{ $stat['counter'] }}
                                        </h2>
                                    </a>
                                    <h6 style="color: <?= $stat['text_color'] ?> !important;"
                                        class="text-muted font-weight-normal mb-0 font-12 {{$class}}"
                                    >{{ $stat['title'] }}</h6>
                                </div>
                                <div class="ms-auto mt-md-3 mt-lg-0"
                                     onclick="window.location.href = '{{ $stat['link'] }}'">


                        <span style="color: <?= $stat['text_color'] ?> !important;" class="opacity-7 text-muted">
                            @if($stat['title'] == 'Revenue')
                                <i onclick="showRevenue(event)" id="toggle-revenue-icon" data-feather="eye-off"></i>
                            @endif
                            <i data-feather="<?= $stat['icon'] ?>"></i>

                        </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
        </div>
        <script>
            function showRevenue(event) {
                event.preventDefault();
                event.stopPropagation();
                const iconElement = document.getElementById('toggle-revenue-icon');
                const revenueElement = document.querySelector('.revenue');
                if (revenueElement.classList.contains('hidden')) {
                    revenueElement.classList.remove('hidden');
                    iconElement.setAttribute('data-feather', 'eye');
                } else {
                    revenueElement.classList.add('hidden');
                    iconElement.setAttribute('data-feather', 'eye-off');
                }
                feather.replace();
            }

            setInterval(function () {
                var message_counter = $('.message_counter').text();
                $.ajax({
                    url: `getUnreadMessage`,
                    method: 'GET',
                    data: {},
                    success: function (response) {
                        if (response > message_counter) {
                            toastr.options = {
                                "timeOut": "10000",
                                "extendedTimeOut": "10000",
                                "escapeHtml": false
                            };
                            $('.message_counter').text(response);
                            toastr.success('You have received a new message from the Employer! Please check in <a href="{{admin_url('messages')}}" style="color: #FFF; text-decoration: underline;">Chats with Companies Section</a>', 'New Message');
                        }
                    },
                    error: function (xhr) {
                        console.error('An error occurred:', xhr);
                    }
                });
            }, 30000);

            setInterval(function () {
                $.ajax({
                    url: `getAffiliateUnreadMessage`,
                    method: 'GET',
                    data: {},
                    success: function (response) {
                        if (response > 0) {
                            toastr.options = {
                                "timeOut": "10000",
                                "extendedTimeOut": "10000",
                                "escapeHtml": false
                            };
                            $('.affiliate_message_counter').text(response);
                            toastr.success('You have received a new message from the Affiliate! Please check in <a href="{{admin_url('affiliate_messages')}}" style="color: #FFF; text-decoration: underline;">Chats with Affiliates Section</a>', 'New Message');
                        }
                    },
                    error: function (xhr) {
                        console.error('An error occurred:', xhr);
                    }
                });
            }, 30000);

            setInterval(function () {
                $.ajax({
                    url: `getUnseenWithdrawRequest`,
                    method: 'GET',
                    data: {},
                    success: function (response) {
                        if (response > 0) {
                            toastr.options = {
                                "timeOut": "10000",
                                "extendedTimeOut": "10000",
                                "escapeHtml": false
                            };
                            $('.withdraw_request_counter').text(response);
                            toastr.success('You have received a new withdraw request from the Affiliate! Please check in <a href="{{admin_url('withdraw_requests')}}" style="color: #FFF; text-decoration: underline;">Withdraw Requests Section</a>', 'New Request');
                        }
                    },
                    error: function (xhr) {
                        console.error('An error occurred:', xhr);
                    }
                });
            }, 30000);

            $(window).on('load', function() {
                $.ajax({
                    url: '{{url('account/update_subscription_ajax')}}',
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        console.log('Success:', data);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            });
        </script>

        <style>
            .hidden {
                display: none;
            }
        </style>
