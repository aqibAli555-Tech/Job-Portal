<!doctype html>
<html dir=ltr lang="{{ config('app.locale') }}">

<head>
    <meta charset=utf-8>
    <meta http-equiv=X-UA-Compatible content="IE=edge">
    <meta name=viewport content="width=device-width,initial-scale=1">
    <meta name=description content="">
    <meta name=author content="{{ config('app.name') }}">
    <link rel=icon type=image/png sizes=16x16 href="{{ imgUrl(config('settings.app.favicon'), 'favicon') }}">
    <title>{!! isset($title) ? strip_tags($title) . ' : ' . config('app.name') . ' Admin' : config('app.name') . ' Admin' !!}</title>
    <meta name=csrf-token content="{{ csrf_token() }}">
    <link href="{{ url()->asset('adminlite/css/c3.min.css') }}" rel=stylesheet>
    <link href="{{ url()->asset('adminlite/css/style.min.css') }}" rel=stylesheet>
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    @yield('before_styles') @yield('after_styles')
    <script src="{{ url()->asset('adminlite/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ url()->asset('js/sweetalert2@1.js') }}"></script>
    <link href="{{ url()->asset('plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    {{-- <link rel="stylesheet" href="{{ url()->asset('adminlite/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}"> --}}
    <link rel="stylesheet"
          href="{{ url()->asset('adminlite/libs/datatables.net-bs4/css/responsive.dataTables.min.css') }}">
    <script type="text/javascript" src="<?= url()->asset('js/datepicker/moment.min.js') ?>"></script>
    <script type="text/javascript" src="<?= url()->asset('js/datepicker/daterangepicker.min.js') ?>"></script>
    <link rel="stylesheet" type="text/css" href="<?= url()->asset('js/datepicker/daterangepicker.css') ?>"/>
    <link rel="stylesheet" type="text/css" href="<?= url()->asset('css/toaster.min.css') ?>"/>

    <link href="https://cdn.datatables.net/v/bs5/dt-2.1.2/datatables.min.css" rel="stylesheet">
    <style>
        .select2-container--default .select2-selection--multiple,
        .select2-container--default .select2-selection--single {
            border-radius: 4px;
            height: calc(1.5em + 0.75rem + 2px);
            padding: 2px 5px;
            font-size: 14px;
            font-weight: 400;
            line-height: 1.5;
            color: #54667a;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #e9ecef;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #54667a;
        }

        .select2-container--open {
            z-index: 9999 !important;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #615583;
            color: white;
        }

        .select2-dropdown {
            border: 1px solid #e9ecef;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #615583;
            border: 1px solid #615583;
            color: white;

        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            background-color: #615583;
            color: #fff;
        }


        .badge-success {
            color: #fff;
            background-color: green;
        }

        tbody td a {
            color: #000;
        }

        .active > .page-link,
        .page-link.active {
            z-index: 3;
            color: #fff;
            background-color: #615583;
            border-color: #615583;
        }

        .page-wrapper > .page-breadcrumb {
            max-width: 100%;
        }

        /* new work */
        .dt-container {
            min-height: 400px;
        }
        /* new work */
        
        textarea {
            resize: vertical;  /* Allow vertical resizing */
            overflow: auto;    /* Enable scrolling if the content exceeds the size */
        }
    </style>
</head>

<body>
<div class=preloader>
    <div class=lds-ripple>
        <div class=lds-pos></div>
        <div class=lds-pos></div>
    </div>
</div>
<div id=main-wrapper data-theme=light data-layout=vertical data-navbarbg=skin6 data-sidebartype=full
     data-sidebar-position=fixed data-header-position=fixed data-boxed-layout=full>
    <header class=topbar data-navbarbg=skin6>
        <nav class="navbar top-navbar navbar-expand-lg">
            <div class=navbar-header data-logobg=skin6>
                <a class="nav-toggler waves-effect waves-light d-block d-lg-none" href=javascript:void(0)><i
                            class="ti-menu ti-close"></i></a>
                <div class=navbar-brand>
                    <a href="{{ admin_url('dashboard') }}">
                        <img src="{{ url()->asset('/icon/logo.png') }}" alt="" class=img-fluid>
                    </a>
                </div>
                <a class="topbartoggler d-block d-lg-none waves-effect waves-light" href=javascript:void(0)
                   data-bs-toggle=collapse data-bs-target=#navbarSupportedContent
                   aria-controls=navbarSupportedContent aria-expanded=false aria-label="Toggle navigation"><i
                            class=ti-more></i></a>
            </div>
            <div class="navbar-collapse collapse" id=navbarSupportedContent>
                <ul class="navbar-nav float-left me-auto ms-3 ps-1">
                    <li class="nav-item dropdown d-none">
                        <a class="nav-link dropdown-toggle pl-md-3 position-relative" href=javascript:void(0)
                           id=bell role=button data-bs-toggle=dropdown aria-haspopup=true aria-expanded=false>
                            <span><i data-feather=bell class=svg-icon></i></span>
                            <span class="badge text-bg-primary notify-no rounded-circle">5</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-left mailbox animated bounceInDown">
                            <ul class=list-style-none>
                                <li>
                                    <div class="message-center notifications position-relative">
                                        <a href=javascript:void(0)
                                           class="message-item d-flex align-items-center border-bottom px-3 py-2">
                                            <div class="btn btn-danger rounded-circle btn-circle"><i
                                                        data-feather=airplay class=text-white></i></div>
                                            <div class="w-75 d-inline-block v-middle ps-2">
                                                <h6 class="message-title mb-0 mt-1">Luanch Admin</h6>
                                                <span class="font-12 text-nowrap d-block text-muted">Just see the my
                                                        new admin!</span>
                                                <span class="font-12 text-nowrap d-block text-muted">9:30 AM</span>
                                            </div>
                                        </a>
                                        <a href=javascript:void(0)
                                           class="message-item d-flex align-items-center border-bottom px-3 py-2">
                                                <span class="btn btn-success text-white rounded-circle btn-circle"><i
                                                            data-feather=calendar class=text-white></i></span>
                                            <div class="w-75 d-inline-block v-middle ps-2">
                                                <h6 class="message-title mb-0 mt-1">Event today</h6>
                                                <span
                                                        class="font-12 text-nowrap d-block text-muted text-truncate">Just
                                                        a reminder that you have event</span>
                                                <span class="font-12 text-nowrap d-block text-muted">9:10 AM</span>
                                            </div>
                                        </a>
                                        <a href=javascript:void(0)
                                           class="message-item d-flex align-items-center border-bottom px-3 py-2">
                                                <span class="btn btn-info rounded-circle btn-circle"><i
                                                            data-feather=settings class=text-white></i></span>
                                            <div class="w-75 d-inline-block v-middle ps-2">
                                                <h6 class="message-title mb-0 mt-1">Settings</h6>
                                                <span
                                                        class="font-12 text-nowrap d-block text-muted text-truncate">You
                                                        can customize this template as you want</span>
                                                <span class="font-12 text-nowrap d-block text-muted">9:08 AM</span>
                                            </div>
                                        </a>
                                        <a href=javascript:void(0)
                                           class="message-item d-flex align-items-center border-bottom px-3 py-2">
                                                <span class="btn btn-primary rounded-circle btn-circle"><i
                                                            data-feather=box class=text-white></i></span>
                                            <div class="w-75 d-inline-block v-middle ps-2">
                                                <h6 class="message-title mb-0 mt-1">Pavan kumar</h6> <span
                                                        class="font-12 text-nowrap d-block text-muted">Just see the my
                                                        admin!</span>
                                                <span class="font-12 text-nowrap d-block text-muted">9:02 AM</span>
                                            </div>
                                        </a>
                                    </div>
                                </li>
                                <li>
                                    <a class="nav-link pt-3 text-center text-dark" href=javascript:void(0);>
                                        <strong>Check all notifications</strong>
                                        <i class="fa fa-angle-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item dropdown d-none">
                        <a class="nav-link dropdown-toggle" href=# id=navbarDropdown role=button
                           data-bs-toggle=dropdown aria-haspopup=true aria-expanded=false>
                            <i data-feather=settings class=svg-icon></i>
                        </a>
                        <div class=dropdown-menu aria-labelledby=navbarDropdown>
                            <a class=dropdown-item href=#>Action</a>
                            <a class=dropdown-item href=#>Another action</a>
                            <div class=dropdown-divider></div>
                            <a class=dropdown-item href=#>Something else here</a>
                        </div>
                    </li>
                    <li class="nav-item d-none">
                        <a class=nav-link href=javascript:void(0)>
                            <div class=customize-input>
                                <select
                                        class="custom-select form-control bg-white custom-radius custom-shadow border-0">
                                    <option selected>EN</option>
                                    <option value=1>AB</option>
                                    <option value=2>AK</option>
                                    <option value=3>BE</option>
                                </select>
                            </div>
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav float-end">
                    <li class="nav-item">
                        <a target="_blank" class="btn btn-purple mt-3" href="{{ url('/') }}">
                            View Website
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href=javascript:void(0) data-bs-toggle=dropdown
                           aria-haspopup=true aria-expanded=false>
                            <img src="{{ url()->asset('adminlite/images/users/profile-pic.jpg') }}" alt=user
                                 class="rounded-circle d-none" width=40>
                            <span class="ms-2 d-none d-lg-inline-block"><span>Hello,</span> <span
                                        class=text-dark>{{ auth()->user()->name }}</span> <i data-feather=chevron-down
                                                                                             class=svg-icon></i></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-right user-dd animated flipInY">
                            <a class=dropdown-item href="{{ admin_url('account') }}"><i data-feather=user
                                                                                        class="svg-icon me-2 ms-1"></i>
                                My Profile</a>
                            <div class="dropdown-divider d-none"></div>
                            <a class="dropdown-item d-none" href=javascript:void(0)><i data-feather=settings
                                                                                       class="svg-icon me-2 ms-1"></i>
                                Account Setting</a>
                            <div class=dropdown-divider></div>
                            <a class=dropdown-item href="{{ admin_url('logout') }}"><i data-feather=power
                                                                                       class="svg-icon me-2 ms-1"></i>
                                Logout</a>
                            <div class="dropdown-divider d-none"></div>
                            <div class="pl-4 p-3 d-none"><a href=javascript:void(0) class="btn btn-sm btn-info">View
                                    Profile</a></div>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <aside class=left-sidebar data-sidebarbg=skin6>
        <div class=scroll-sidebar data-sidebarbg=skin6>
            <nav class=sidebar-nav>
                <ul id=sidebarnav>
                    <li class="sidebar-item">
                        <a class="sidebar-link sidebar-link" href="{{ admin_url('dashboard') }}"
                           aria-expanded=false><i data-feather=home class=feather-icon></i><span
                                    class=hide-menu>Dashboard</span></a>
                    </li>
                    <li class=list-divider></li>
                    <li class=nav-small-cap><span class=hide-menu>Job Seeker</span></li>
                    <li class=sidebar-item><a class=sidebar-link href="{{ admin_url('job-seekers') }}"
                                              aria-expanded=false><i data-feather=users class=feather-icon></i><span
                                    class=hide-menu>{{ trans('admin.list') }}</span></a></li>
                    @if(auth()->user()->user_type_id!=4)
                        <li class=sidebar-item><a class="sidebar-link sidebar-link"
                                                  href="{{ admin_url('get_top_country_employee') }}"
                                                  aria-expanded=false><i
                                        data-feather=globe class=feather-icon></i><span class=hide-menu>Top
                                    Countries</span></a></li>
                        <li class=sidebar-item><a class="sidebar-link sidebar-link"
                                                  href="{{ admin_url('top-nationality-job-seekers') }}"
                                                  aria-expanded=false><i
                                        data-feather=flag class=feather-icon></i><span class=hide-menu>Top
                                    Nationalities</span></a></li>
                        <li class=list-divider></li>
                        <li class=nav-small-cap><span class=hide-menu>Employers</span></li>
                        <li class=sidebar-item><a class=sidebar-link href="{{ admin_url('employer') }}"
                                                  aria-expanded=false><i data-feather=users class=feather-icon></i><span
                                        class=hide-menu>{{ trans('admin.list') }}</span></a></li>
                        <li class=sidebar-item><a class=sidebar-link href="{{ admin_url('staffs') }}"
                                                  aria-expanded=false><i class="icon-user"></i><span
                                        class=hide-menu>{{ trans('admin.Staff') }}</span></a></li>
                        <li class=sidebar-item><a class=sidebar-link href="{{ admin_url('get_top_country_employer') }}"
                                                  aria-expanded=false><i data-feather=globe class=feather-icon></i><span
                                        class=hide-menu>{{ trans('admin.Top Countries') }}</span></a></li>
                        <li class=sidebar-item><a class=sidebar-link
                                                  href="{{ admin_url('get_posts?type=life_time_posts') }}"
                                                  aria-expanded=false><i
                                        class="icon-briefcase"></i><span
                                        class=hide-menu>{{ trans('admin.Jobs') }}</span></a></li>
                        <li class=sidebar-item><a class=sidebar-link href="{{ admin_url('top-skill-jobs') }}"
                                                  aria-expanded=false><i class="icon-magic-wand"></i><span
                                        class=hide-menu>{{ trans('admin.Top Skill Posts') }}</span></a></li>
                        <li class=sidebar-item><a class=sidebar-link href="{{ admin_url('applicants') }}"
                                                  aria-expanded=false><i class="icon-book-open"></i><span
                                        class=hide-menu>{{ trans('admin.Applicants') }}</span></a></li>
                        <li class=sidebar-item><a class=sidebar-link href="{{ admin_url('get_company') }}"
                                                  aria-expanded=false><i class="icon-user-follow"></i><span
                                        class=hide-menu>{{ trans('admin.companies') }}</span></a></li>
                        <li class=sidebar-item><a class=sidebar-link href="{{ admin_url('assignCredits') }}"
                                                  aria-expanded=false><i class="icon-wallet"></i><span
                                        class=hide-menu>{{ trans('admin.assign_credits') }}</span></a></li>
                        <li class=sidebar-item><a class=sidebar-link href="{{ admin_url('message_request') }}"
                                                  aria-expanded=false><i class="fas fa-comments"></i><span
                                        class=hide-menu>{{ trans('admin.bulk_chat_request') }}</span></a></li>
                        <li class=sidebar-item><a class=sidebar-link href="{{ admin_url('messages') }}"
                                                  aria-expanded=false><i class="icon-bubble"></i><span
                                        class=hide-menu>{{ trans('admin.messages') }}</span></a></li>
                        <li class=list-divider></li>
                        <li class=nav-small-cap><span class=hide-menu>{{ trans('Affiliates') }}</span></li>
                        <li class="sidebar-item">
                            <a href="{{ admin_url('affiliate_dashboard_stats') }}" class="sidebar-link">
                                <i class="fas fa-chart-bar"></i> <span
                                        class="hide-menu">{{ trans('admin.stats') }}</span>
                            </a>
                        </li>
                        <li class=sidebar-item>
                            <a class=sidebar-link href="{{ admin_url('affiliates') }}" aria-expanded=false>
                                <i class="fas fa-user-plus"></i>
                                <span class=hide-menu>{{ trans('Affiliates') }}</span>
                            </a>
                        </li>
                        <li class=sidebar-item>
                            <a class=sidebar-link href="{{ admin_url('referral_commission') }}" aria-expanded=false>
                                <i class="fas fa-handshake"></i>
                                <span class=hide-menu>{{ trans('Referral Commission') }}</span>
                            </a>
                        </li>
                        <li class=sidebar-item>
                            <a class=sidebar-link href="{{ admin_url('withdraw_requests') }}" aria-expanded=false>
                                <i class="icon-envelope"></i>
                                <span class=hide-menu>{{ trans('admin.withdraw_requests') }}</span>
                            </a>
                        </li>
                        <li class=sidebar-item>
                            <a class=sidebar-link href="{{ admin_url('affiliate_messages') }}" aria-expanded=false>
                                <i class="icon-bubble"></i>
                                <spanclass=hide-menu>{{ trans('admin.affiliate_messages') }}</span>
                            </a>
                        </li>
                        <li class=sidebar-item>
                            <a class=sidebar-link href="{{ admin_url('affiliate_settings') }}" aria-expanded=false>
                                <i class="icon-settings"></i>
                                <span class=hide-menu>{{ trans('admin.Settings') }}</span>
                            </a>
                        </li>
                        <li class=list-divider></li>
                        <li class=nav-small-cap><span class=hide-menu>Admin Settings</span></li>
                        <li class=sidebar-item><a class=sidebar-link href="{{ admin_url('employeeSkill') }}"
                                                  aria-expanded=false><i class="fas fa-chart-area"></i><span
                                        class=hide-menu>{{ trans('admin.employee_skill') }}</span></a></li>
                        <li class="sidebar-item">
                            <a href="{{ admin_url('email_logs') }}" class="sidebar-link">
                                <i class="fas fa-envelope"></i> <span
                                        class="hide-menu">{{ trans('admin.email_logs') }}</span>
                            </a>
                        </li>
                        @if (auth()->user()->can('page-list') || userHasSuperAdminPermissions())
                            <li class="sidebar-item">
                                <a href="{{ admin_url('get_logs') }}" class="sidebar-link">
                                    <i class="fab fa-blogger-b"></i> <span
                                            class="hide-menu">{{ trans('admin.Activity log') }}</span>
                                </a>
                            </li>
                        @endif
                        <li class="sidebar-item"><a href="{{ admin_url('rejected_reasons') }}"
                                                    class="sidebar-link"><i class="fas fa-window-close"></i><span
                                        class="hide-menu">{{ trans('admin.rejected_reasons') }}</span></a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ admin_url('package-cancel-reasons') }}" class="sidebar-link">
                                <i class="icon-close"></i> <span
                                        class="hide-menu">{{ trans('admin.package_cancel_reasons') }}</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ admin_url('post-archived-or-delete-reasons') }}" class="sidebar-link">
                                <i class="icon-envelope-open"></i> <span
                                        class="hide-menu">{{ trans('admin.post_archived_or_delete_reasons') }}</span>
                            </a>
                        </li>
                        @if (auth()->user()->can('list-gender') || userHasSuperAdminPermissions())
                            <li class="sidebar-item">
                                <a href="{{ admin_url('genders') }}" class="sidebar-link">
                                    <i class="fas fa-file"></i>
                                    <span class="hide-menu">{{ trans('admin.titles') }}</span>
                                </a>
                            </li>
                        @endif
                        @if (auth()->user()->can('post-type-list') || userHasSuperAdminPermissions())
                            <li class="sidebar-item">
                                <a href="{{ admin_url('p_types') }}" class="sidebar-link">
                                    <i class="fas fa-folder-open"></i>
                                    <span class="hide-menu">{{ trans('admin.Jobs types') }}</span>
                                </a>
                            </li>
                        @endif
                        @if (auth()->user()->can('salary-type-list') || userHasSuperAdminPermissions())
                            <li class="sidebar-item">
                                <a href="{{ admin_url('salary_types') }}" class="sidebar-link">
                                    <i class="fas fa-hands-helping"></i>
                                    <span class="hide-menu">{{ trans('admin.salary type') }}</span>
                                </a>
                            </li>
                        @endif
                        <li class="sidebar-item">
                            <a href="{{ admin_url('availability') }}" class="sidebar-link">
                                <i class="far fa-clock"></i> <span
                                        class="hide-menu">{{ trans('Avaliabllity') }}</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ admin_url('contact_us') }}" class="sidebar-link">
                                <i class="far fa-address-card"></i> <span
                                        class="hide-menu">{{ trans('admin.Contact') }}</span>
                            </a>
                        </li>
                        @if (userHasSuperAdminPermissions())
                            <li class="sidebar-item">
                                <a href="{{ admin_url('skillExperience') }}" class="sidebar-link">
                                    <i class="far fa-arrow-alt-circle-right"></i> <span
                                            class="hide-menu">{{ trans('admin.skill_experience') }}</span>
                                </a>
                            </li>
                        @endif
                        <li class="sidebar-item">
                            <a href="{{ admin_url('entityCauses') }}" class="sidebar-link">
                                <i class="icon-pie-chart"></i> <span
                                        class="hide-menu">{{ trans('admin.entities_cuisines') }}</span>
                            </a>
                        </li>
                        @if (auth()->user()->can('list-role') || userHasSuperAdminPermissions())
                            <li class="sidebar-item" hidden>
                                <a href="{{ admin_url('roles') }}" class="sidebar-link">
                                    <i data-feather="circle" class="feather-icon"></i>
                                    <span class="hide-menu">{{ trans('admin.roles') }}</span>
                                </a>
                            </li>
                        @endif
                        @if (auth()->user()->can('list-permission') || userHasSuperAdminPermissions())
                            <li class="sidebar-item" hidden>
                                <a href="{{ admin_url('permissions') }}" class="sidebar-link">
                                    <i data-feather="circle" class="feather-icon"></i>
                                    <span class="hide-menu">{{ trans('admin.permissions') }}</span>
                                </a>
                            </li>
                        @endif

                        @if (auth()->user()->can('payment-list') || userHasSuperAdminPermissions())
                            <li class="sidebar-item">
                                <a href="{{ admin_url('payments') }}" class="sidebar-link">
                                    <i class="icon-paypal"></i> <span
                                            class="hide-menu">{{ trans('admin.payments') }}</span>
                                </a>
                            </li>
                        @endif
                        <li class="sidebar-item">
                            <a href="{{ admin_url('send_email?user_type=1') }}" class="sidebar-link">
                                <i class="fas fa-book"></i> <span class="hide-menu">{{ t('Send email') }}</span>
                            </a>
                        </li>

                        @if (auth()->user()->can('page-list') || userHasSuperAdminPermissions())
                            <li class="sidebar-item">
                                <a href="{{ admin_url('pages') }}" class="sidebar-link">
                                    <i class="icon-notebook"></i> <span
                                            class="hide-menu">{{ trans('admin.pages') }}</span>
                                </a>
                            </li>
                        @endif
                        
                        @if (auth()->user()->can('page-list') || userHasSuperAdminPermissions())
                            <li class="sidebar-item">
                                <a href="{{ admin_url('payment_setting') }}" class="sidebar-link">
                                    <i class="icon-settings"></i> <span
                                            class="hide-menu">{{ trans('admin.payment setting') }}</span>
                                </a>
                            </li>
                        @endif
                        @if (auth()->user()->can('page-list') || userHasSuperAdminPermissions())
                            <li class="sidebar-item">
                                <a href="{{ admin_url('email_setting') }}" class="sidebar-link">
                                    <i class="fas fa-cogs"></i><span
                                            class="hide-menu">{{ trans('admin.email setting') }}</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ admin_url('email_stats') }}" class="sidebar-link">
                                    <i class="fas fa-chart-bar"></i> <span
                                            class="hide-menu">{{ trans('admin.email stats') }}</span>
                                </a>
                            </li>
                        @endif
                        @if (auth()->user()->can('page-list') || userHasSuperAdminPermissions())
                            <li class="sidebar-item">
                                <a href="{{ admin_url('get-page-analytics') }}" class="sidebar-link">
                                    <i class="fas fa-chart-line"></i> <span
                                            class="hide-menu">{{ trans('admin.page-analytics') }}</span>
                                </a>
                            </li>
                        @endif
                        @if (auth()->user()->can('page-list') || userHasSuperAdminPermissions())
                            <li class="sidebar-item">
                                <a href="{{ admin_url('statistics') }}" class="sidebar-link">
                                    <i class="icon-chart"></i> <span
                                            class="hide-menu">{{ trans('admin.statistics') }}</span>
                                </a>
                            </li>
                        @endif

                        @if (auth()->user()->can('page-list') || userHasSuperAdminPermissions())
                            <li class="sidebar-item">
                                <a href="{{ admin_url('Contact_Card_Problems') }}" class="sidebar-link">
                                    <i class="fas fa-id-card"></i> <span
                                            class="hide-menu">{{ trans('admin.Contact Card Problems') }}</span>
                                </a>
                            </li>
                        @endif
                        @if (auth()->user()->can('page-list') || userHasSuperAdminPermissions())
                            <li class="sidebar-item">
                                <a href="{{ admin_url('cache') }}" class="sidebar-link">
                                    <i class="fas fa-sync-alt"></i> <span
                                            class="hide-menu">{{ trans('admin.Cache Setting') }}</span>
                                </a>
                            </li>
                        @endif
                        @if(IS_WHATSAPP_ALLOWED)
                        <li class="nav-small-cap d-none"><span class=hide-menu>Twilio Logs</span></li>
                        <li class="sidebar-item">
                            <a href="{{ admin_url('twilio_logs') }}" class="sidebar-link">
                                <i class="fas fa-envelope"></i> <span
                                        class="hide-menu">{{ trans('admin.twilio_logs') }}</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ admin_url('whatsapp') }}" class="sidebar-link">
                                <i class="fab fa-whatsapp"></i> <span
                                        class="hide-menu">{{ trans('admin.whatsapp_users') }}</span>
                            </a>
                        </li>
                        @endif
                        <li class=list-divider></li>
                    @endif
                </ul>
            </nav>
        </div>
    </aside>
    <div class=page-wrapper>
        <div class=page-breadcrumb>
            <div class=row>
                <div class="col-7 align-self-center">
                    <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">
                        <?= \App\Helpers\Helper::get_day_time() ?>, {{ auth()->user()->name }}!</h3>
                    <div class="d-flex align-items-center">
                        <nav aria-label=breadcrumb>
                            <ol class="breadcrumb m-0 p-0">
                                @if (!empty($breadcumbs))
                                    @foreach ($breadcumbs as $breadcumb)
                                        @if (!empty($breadcumb['link']))
                                            <li class=breadcrumb-item><a
                                                        href="{{ $breadcumb['link'] }}">{{ $breadcumb['title'] }}</a>
                                            </li>
                                        @endif
                                    @endforeach
                                @endif
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-3">
            @yield('content')
        </div>
        <footer class="footer text-center text-muted">
            <?php $now = new DateTime();
            $year = $now->format('Y');
            ?>
            © {{ $year }} {{ config('settings.app.app_name') }}. {{ t('All Rights Reserved') }}.
            <br>
            @if (config('settings.footer.powered_by_info'))
                {{ trans('admin.powered_by') }} {!! config('settings.footer.powered_by_info') !!}
            @else
                {{ trans('admin.powered_by') }} <a target="_blank" href="https://menu.house">Menu House</a>
            @endif
        </footer>
    </div>
</div>
@yield('before_scripts')
<script>
    var siteUrl = "<?php echo url('/'); ?>"
</script>
<script src="{{ url()->asset('js/toaster.min.js') }}"></script>

<script src="{{ url()->asset('adminlite/libs/popper.js/dist/umd/popper.min.js') }}"></script>
<script src="{{ url()->asset('adminlite/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ url()->asset('adminlite/js/app-style-switcher.js') }}"></script>
<script src="{{ url()->asset('adminlite/js/feather.min.js') }}"></script>
<script src="{{ url()->asset('adminlite/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js') }}"></script>
<script src="{{ url()->asset('adminlite/js/sidebarmenu.js') }}"></script>
<script src="{{ url()->asset('adminlite/js/custom.min.js') }}?v={{VERSION}}"></script>
<script src="{{ url()->asset('adminlite/js/c3/d3.min.js') }}"></script>
<script src="{{ url()->asset('adminlite/js/c3/c3.min.js') }}"></script>
<script src="{{ url()->asset('/plugins/select2/js/select2.min.js') }}"></script>
{{-- <script src="{{ url()->asset('adminlite/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script> --}}
<script src="{{ url()->asset('adminlite/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
{{-- <script src="https://cdn.datatables.net/v/bs5/dt-2.1.2/datatables.min.js"></script> --}}
<script src="{{ url()->asset('adminlite/js/pages/datatable/datatable-basic.init.js') }}?v={{VERSION}}"></script>

<script>
    function showConfirmation(id, url, method = 'POST') {
        Swal.fire({
            title: 'Please enter the PIN code:',
            input: 'text',
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Submit',
            showLoaderOnConfirm: true,
            preConfirm: (pincode) => {
                return $.ajax({
                    url: url,
                    data: {
                        id: id,
                        pincode: pincode
                    },
                    method: method, // Change to POST method to send sensitive data
                    success: function (response) {
                        if (response.status == false) {
                            Swal.fire({
                                title: "OOPS!",
                                text: response.message,
                                icon: "error",
                                button: "Ok",
                            });
                        } else {
                            Swal.fire({
                                title: "Great!",
                                text: response.message,
                                icon: "success",
                                button: "Ok",
                            }).then(() => {
                                window.location.reload();
                            });
                        }
                    },
                    error: function () {
                        alert('Error deleting the record.');
                    }
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.dismiss === Swal.DismissReason.cancel) {
                // Handle cancel action if needed
            }
        });
    }

    function showSwalConfirmation(message, icon = 'success', title, btn_text, callback) {
        Swal.fire({
            title: title,
            text: message,
            icon: icon,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: btn_text
        }).then((result) => {
            if (result.isConfirmed) {
                callback();
            }
        });
    }

    function showSwalAlert(title, message, icon = 'success', button) {
        Swal.fire({
            title: title,
            text: message,
            icon: icon,
            button: button,
        }).then(() => {
            return true;
        });
    }


    $('.select1').select2({
        width: "100%"
    });


    // new work
    $('.parent').on('shown.bs.modal', function () {
        var modal = $(this);
        // Initialize Select2 on modal with a different dropdown parent
        $('.select1.modal-select').select2({
            dropdownParent: modal,
            width: "100%"
        });
    });

    $('.modal').on('hide.bs.modal', function () {
        // Destroy Select2 instance on modal
        $('.select1.modal-select').select2('destroy');
    });

    // new work

</script>
@yield('after_scripts')
</body>

</html>
