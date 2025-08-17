@if (auth()->check())
        <?php
// Get plugins admin menu
        $pluginsMenu = '';
        ?>
    <aside class="left-sidebar" id="adminSidebar" style="background: #615583;">
        {{-- Sidebar scroll --}}
        <div class="scroll-sidebar">
            {{-- Sidebar navigation --}}
            <nav class="sidebar-nav">
                <ul id="sidebarnav">
                    <li class="sidebar-item user-profile">
                        <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)"
                           aria-expanded="false">
                            <img src="{{  \App\Helpers\Helper::getImageOrThumbnailLink($user,true); }}" alt="user">
                            <span class="hide-menu">{{ auth()->user()->name }}</span>
                        </a>
                        <ul aria-expanded="false" class="collapse first-level">
                            <li class="sidebar-item">
                                <a href="{{ admin_url('account') }}" class="sidebar-link p-0">
                                    <i class="mdi mdi-adjust"></i>
                                    <span class="hide-menu">{{ trans('admin.my_account') }}</span>
                                </a>
                            </li>
                            <li class="sidebar-item d-none">
                                <a href="{{ admin_url('login_as_employee') }}" class="sidebar-link p-0">
                                    <i class="mdi mdi-adjust"></i>
                                    <span class="hide-menu">{{ trans('admin.login_as_employee') }}</span>
                                </a>
                            </li>
                            <li class="sidebar-item d-none">
                                <a href="{{ admin_url('login_as_employer') }}" class="sidebar-link p-0">
                                    <i class="mdi mdi-adjust"></i>
                                    <span class="hide-menu">{{ trans('admin.login_as_employer') }}</span>
                                </a>
                            </li>

                            <li class="sidebar-item" hidden>
                                <a href="{{ admin_url('logout') }}" class="sidebar-link p-0">
                                    <i class="mdi mdi-adjust"></i>
                                    <span class="hide-menu">{{ trans('admin.logout') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="sidebar-item">
                        <a href="{{ admin_url('dashboard') }}" class="sidebar-link waves-effect waves-dark">
                            <i data-feather="home" class="feather-icon"></i> <span
                                    class="hide-menu">{{ trans('admin.dashboard') }}</span>
                        </a>
                    </li>


                    @if (
                      auth()->user()->can('user-list')
                    || auth()->user()->can('role-list')
                    || auth()->user()->can('permission-list')
                    || auth()->user()->can('gender-list')
                    || userHasSuperAdminPermissions()
                    )
                        <li class="sidebar-item">
                            <a href="javascript:void(0)" class="sidebar-link has-arrow waves-effect waves-dark">
                                <i data-feather="users"></i> <span class="hide-menu">{{ trans('job seeker') }}</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">

                                @if (auth()->user()->can('list-user') || userHasSuperAdminPermissions())
                                    <li class="sidebar-item">
                                        <a href="{{ admin_url('job-seekers') }}" class="sidebar-link">
                                            <i class="mdi mdi-adjust"></i>
                                            <span class="hide-menu">{{ trans('admin.list') }}</span>
                                        </a>
                                    </li>
                                    @if(auth()->user()->user_type_id!=4)
                                        <li class="sidebar-item">
                                            <a href="{{ admin_url('get_top_country_employee') }}" class="sidebar-link">
                                                <i class="mdi mdi-adjust"></i>
                                                <span class="hide-menu">{{ trans('admin.Top Countries') }}</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ admin_url('top-nationality-job-seekers') }}"
                                               class="sidebar-link">
                                                <i class="mdi mdi-adjust"></i>
                                                <span class="hide-menu">{{ trans('admin.Top Nationalities') }}</span>
                                            </a>
                                        </li>
                                    @endif
                                @endif
                            </ul>
                        </li>

                    @endif
                    @if (
                    auth()->user()->can('post-list')
                    || auth()->user()->can('category-list')
                    || auth()->user()->can('company-list')
                    || auth()->user()->can('post-type-list')
                    || auth()->user()->can('salary-type-list')
                    || userHasSuperAdminPermissions()

                  )
                        @if(auth()->user()->user_type_id!=4)
                            <li class="sidebar-item">
                                <a href="javascript:void(0)" class="sidebar-link has-arrow waves-effect waves-dark">
                                    <i data-feather="user-plus"></i> <span
                                            class="hide-menu">{{ trans('employer') }}</span>
                                </a>
                                <ul aria-expanded="false" class="collapse first-level">
                                    @if (auth()->user()->can('list-user') || userHasSuperAdminPermissions())
                                        <li class="sidebar-item">
                                            <a href="{{ admin_url('employer') }}" class="sidebar-link">
                                                <i class="mdi mdi-adjust"></i>
                                                <span class="hide-menu">{{ trans('list') }}</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ admin_url('staffs') }}" class="sidebar-link">
                                                <i class="mdi mdi-adjust"></i> <span
                                                        class="hide-menu">{{ trans('admin.Staff') }}</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ admin_url('get_top_country_employer') }}" class="sidebar-link">
                                                <i class="mdi mdi-adjust"></i>
                                                <span class="hide-menu">{{ trans('admin.Top Countries') }}</span>
                                            </a>
                                        </li>
                                    @endif
                                    @if (auth()->user()->can('post-list') || userHasSuperAdminPermissions())
                                        <li class="sidebar-item">
                                            <a href="{{ admin_url('get_posts/?type='.'life_time_posts') }}"
                                               class="sidebar-link">
                                                <i class="mdi mdi-adjust"></i>
                                                <span class="hide-menu">{{ trans('admin.jobs') }}</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a href="{{ admin_url('top-skill-jobs') }}" class="sidebar-link">
                                                <i class="mdi mdi-adjust"></i>
                                                <span class="hide-menu">{{ trans('admin.Top Skill Posts') }}</span>
                                            </a>
                                        </li>
                                    @endif
                                    <li class="sidebar-item">
                                        <a href="{{ admin_url('applicants') }}" class="sidebar-link">
                                            <i class="mdi mdi-adjust"></i>
                                            <span class="hide-menu">{{ trans('admin.Applicants') }}</span>
                                        </a>
                                    </li>
                                    @if (auth()->user()->can('category-list') || userHasSuperAdminPermissions())
                                        <li class="sidebar-item" hidden>
                                            <a href="{{ admin_url('categories') }}" class="sidebar-link">
                                                <i class="mdi mdi-adjust"></i>
                                                <span class="hide-menu">{{ trans('admin.categories') }}</span>
                                            </a>
                                        </li>
                                    @endif
                                    @if (auth()->user()->can('company-list') || userHasSuperAdminPermissions())
                                        <li class="sidebar-item">
                                            <a href="{{ admin_url('get_company') }}" class="sidebar-link">
                                                <i class="mdi mdi-adjust"></i>
                                                <span class="hide-menu">{{ trans('admin.companies') }}</span>
                                            </a>
                                        </li>
                                    @endif
                                    <li class="sidebar-item">
                                        <a href="{{ admin_url('assignCredits') }}" class="sidebar-link">
                                            <i class="mdi mdi-adjust"></i>
                                            <span>Assign Credits</span>
                                        </a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="{{ admin_url('message_request') }}" class="sidebar-link">
                                            <i class="mdi mdi-adjust"></i>
                                            <span>{{trans('bulk chat request')}}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                        @endif
                    @endif
                    @if (userHasSuperAdminPermissions() && auth()->user()->user_type_id!=4)
                        <li class="sidebar-item">
                            <a href="javascript:void(0)" aria-expanded="false"
                               class="sidebar-link has-arrow waves-effect waves-dark">
                                <i data-feather="user"></i> <span class="hide-menu">{{ trans('Admin Settings') }}</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a href="{{ admin_url('employeeSkill') }}" class="sidebar-link">
                                        <i data-feather="bar-chart-2" class="feather-icon"></i> <span
                                                class="hide-menu">{{ trans('admin.employee_skill') }}</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ admin_url('rejected_reasons') }}" class="sidebar-link">
                                        <i data-feather="bar-chart-2" class="feather-icon"></i> <span
                                                class="hide-menu">{{ trans('admin.rejected_reasons') }}</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ admin_url('package-cancel-reasons') }}" class="sidebar-link">
                                        <i data-feather="bar-chart-2" class="feather-icon"></i> <span
                                                class="hide-menu">{{ trans('admin.package_cancel_reasons') }}</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ admin_url('post-archived-or-delete-reasons') }}" class="sidebar-link">
                                        <i data-feather="bar-chart-2" class="feather-icon"></i> <span
                                                class="hide-menu">{{ trans('admin.post_archived_or_delete_reasons') }}</span>
                                    </a>
                                </li>
                                @if (auth()->user()->can('list-gender') || userHasSuperAdminPermissions())
                                    <li class="sidebar-item">
                                        <a href="{{ admin_url('genders') }}" class="sidebar-link">
                                            <i data-feather="bar-chart-2" class="feather-icon"></i>
                                            <span class="hide-menu">{{ trans('admin.titles') }}</span>
                                        </a>
                                    </li>
                                @endif
                                @if (auth()->user()->can('post-type-list') || userHasSuperAdminPermissions())
                                    <li class="sidebar-item">
                                        <a href="{{ admin_url('p_types') }}" class="sidebar-link">
                                            <i data-feather="bar-chart-2" class="feather-icon"></i>
                                            <span class="hide-menu">{{ trans('admin.Jobs types') }}</span>
                                        </a>
                                    </li>
                                @endif
                                @if (auth()->user()->can('salary-type-list') || userHasSuperAdminPermissions())
                                    <li class="sidebar-item">
                                        <a href="{{ admin_url('salary_types') }}" class="sidebar-link">
                                            <i data-feather="bar-chart-2" class="feather-icon"></i>
                                            <span class="hide-menu">{{ trans('admin.salary type') }}</span>
                                        </a>
                                    </li>
                                @endif
                                <li class="sidebar-item">
                                    <a href="{{ admin_url('availability') }}" class="sidebar-link">
                                        <i data-feather="bar-chart-2" class="feather-icon"></i> <span
                                                class="hide-menu">{{ trans('Avaliabllity') }}</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ admin_url('contact_us') }}" class="sidebar-link">
                                        <i data-feather="bar-chart-2" class="feather-icon"></i> <span
                                                class="hide-menu">{{ trans('admin.Contact') }}</span>
                                    </a>
                                </li>
                                @if (userHasSuperAdminPermissions())
                                    <li class="sidebar-item">
                                        <a href="{{ admin_url('skillExperience') }}" class="sidebar-link">
                                            <i data-feather="bar-chart-2" class="feather-icon"></i> <span
                                                    class="hide-menu">{{ trans('admin.skill_experience') }}</span>
                                        </a>
                                    </li>
                                @endif
                                <li class="sidebar-item">
                                    <a href="{{ admin_url('entityCauses') }}" class="sidebar-link">
                                        <i data-feather="clipboard" class="feather-icon"></i> <span
                                                class="hide-menu">{{ trans('admin.entities_cuisines') }}</span>
                                    </a>
                                </li>
                                @if (auth()->user()->can('list-role') || userHasSuperAdminPermissions())
                                <li class="sidebar-item" hidden>
                                        <a href="{{ admin_url('roles') }}" class="sidebar-link">
                                            <i data-feather="bar-chart-2" class="feather-icon"></i>
                                            <span class="hide-menu">{{ trans('admin.roles') }}</span>
                                        </a>
                                    </li>
                                @endif
                                @if (auth()->user()->can('list-permission') || userHasSuperAdminPermissions())
                                <li class="sidebar-item" hidden>
                                        <a href="{{ admin_url('permissions') }}" class="sidebar-link">
                                            <i data-feather="bar-chart-2" class="feather-icon"></i>
                                            <span class="hide-menu">{{ trans('admin.permissions') }}</span>
                                        </a>
                                    </li>
                                @endif

                                @if (auth()->user()->can('payment-list') || userHasSuperAdminPermissions())
                                <li class="sidebar-item">
                                        <a href="{{ admin_url('payments') }}" class="sidebar-link">
                                            <i data-feather="dollar-sign" class="feather-icon"></i> <span
                                                    class="hide-menu">{{ trans('admin.payments') }}</span>
                                        </a>
                                    </li>
                                @endif
                                <li class="sidebar-item">
                                    <a href="{{ admin_url('send_email?user_type=1') }}" class="sidebar-link">
                                        <i data-feather="clipboard" class="feather-icon"></i> <span
                                                class="hide-menu">{{ t('Send email') }}</span>
                                    </a>
                                </li>

                                @if (auth()->user()->can('page-list') || userHasSuperAdminPermissions())
                                <li class="sidebar-item">
                                        <a href="{{ admin_url('pages') }}" class="sidebar-link">
                                            <i data-feather="book-open" class="feather-icon"></i> <span
                                                    class="hide-menu">{{ trans('admin.pages') }}</span>
                                        </a>
                                    </li>
                                @endif
                                @if (auth()->user()->can('page-list') || userHasSuperAdminPermissions())
                                <li class="sidebar-item">
                                        <a href="{{ admin_url('get_logs') }}" class="sidebar-link">
                                            <i data-feather="book-open" class="feather-icon"></i> <span
                                                    class="hide-menu">{{ trans('admin.Activity log') }}</span>
                                        </a>
                                    </li>
                                @endif
                                @if (auth()->user()->can('page-list') || userHasSuperAdminPermissions())
                                <li class="sidebar-item">
                                        <a href="{{ admin_url('payment_setting') }}" class="sidebar-link">
                                            <i data-feather="book-open" class="feather-icon"></i> <span
                                                    class="hide-menu">{{ trans('admin.payment setting') }}</span>
                                        </a>
                                    </li>
                                @endif
                                @if (auth()->user()->can('page-list') || userHasSuperAdminPermissions())
                                <li class="sidebar-item">
                                        <a href="{{ admin_url('email_setting') }}" class="sidebar-link">
                                            <i data-feather="book-open" class="feather-icon"></i> <span
                                                    class="hide-menu">{{ trans('admin.email setting') }}</span>
                                        </a>
                                    </li>
                                <li class="sidebar-item">
                                        <a href="{{ admin_url('email_stats') }}" class="sidebar-link">
                                            <i data-feather="book-open" class="feather-icon"></i> <span
                                                    class="hide-menu">{{ trans('admin.email stats') }}</span>
                                        </a>
                                    </li>
                                @endif
                                @if (auth()->user()->can('page-list') || userHasSuperAdminPermissions())
                                <li class="sidebar-item">
                                        <a href="{{ admin_url('get-page-analytics') }}" class="sidebar-link">
                                            <i data-feather="book-open" class="feather-icon"></i> <span
                                                    class="hide-menu">{{ trans('admin.page-analytics') }}</span>
                                        </a>
                                    </li>
                                @endif
                                @if (auth()->user()->can('page-list') || userHasSuperAdminPermissions())
                                <li class="sidebar-item">
                                        <a href="{{ admin_url('statistics') }}" class="sidebar-link">
                                            <i data-feather="check" class="feather-icon"></i> <span
                                                    class="hide-menu">{{ trans('admin.statistics') }}</span>
                                        </a>
                                    </li>
                                @endif

                                @if (auth()->user()->can('page-list') || userHasSuperAdminPermissions())
                                <li class="sidebar-item">
                                        <a href="{{ admin_url('Contact_Card_Problems') }}" class="sidebar-link">
                                            <i data-feather="book-open" class="feather-icon"></i> <span
                                                    class="hide-menu">{{ trans('admin.Contact Card Problems') }}</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    {!! $pluginsMenu !!}

                    {{-- ======================================= --}}
                    @if (
                    auth()->user()->can('setting-list')
                    || auth()->user()->can('language-list')
                    || auth()->user()->can('home-section-list')
                    || auth()->user()->can('meta-tag-list')
                    || auth()->user()->can('package-list')
                    || auth()->user()->can('payment-method-list')
                    || auth()->user()->can('advertising-list')
                    || auth()->user()->can('country-list')
                    || auth()->user()->can('currency-list')
                    || auth()->user()->can('blacklist-list')
                    || auth()->user()->can('report-type-list')
                    || userHasSuperAdminPermissions()
                    )
                        <li class="nav-small-cap" hidden>
                            <i class="mdi mdi-dots-horizontal"></i>
                            <span class="hide-menu">{{ trans('admin.configuration') }}</span>
                        </li>

                        @if (
                        auth()->user()->can('setting-list')
                        || auth()->user()->can('language-list')
                        || auth()->user()->can('home-section-list')
                        || auth()->user()->can('meta-tag-list')
                        || auth()->user()->can('package-list')
                        || auth()->user()->can('payment-method-list')
                        || auth()->user()->can('advertising-list')
                        || auth()->user()->can('country-list')
                        || auth()->user()->can('currency-list')
                        || auth()->user()->can('blacklist-list')
                        || auth()->user()->can('report-type-list')
                        || userHasSuperAdminPermissions()
                        )
                            @if(auth()->user()->user_type_id!=4)
                                <li class="sidebar-item">
                                    <a href="javascript:void(0)" class="has-arrow sidebar-link">
                                        <i data-feather="settings" class="feather-icon"></i>
                                        <span class="hide-menu">{{ trans('admin.settings') }}</span>
                                    </a>
                                    <ul aria-expanded="false" class="collapse first-level">
                                        @include('admin::layouts.inc.sidebar.general-settings')
                                        @include('admin::layouts.inc.sidebar.tableData-settings')
                                    </ul>
                                </li>
                            @endif
                        @endif
                    @endif

                    @if (auth()->user()->can('plugin-list') || userHasSuperAdminPermissions())
                        <li class="sidebar-item" hidden>
                            <a href="{{ admin_url('plugins') }}" class="sidebar-link">
                                <i data-feather="package" class="feather-icon"></i> <span
                                        class="hide-menu">{{ trans('admin.plugins') }}</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->can('clear-cache') || userHasSuperAdminPermissions())
                        <li class="sidebar-item" hidden>
                            <a href="{{ admin_url('actions/clear_cache') }}" class="sidebar-link">
                                <i data-feather="refresh-cw" class="feather-icon"></i> <span
                                        class="hide-menu">{{ trans('admin.clear cache') }}</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->can('backup-list') || userHasSuperAdminPermissions())
                        <li class="sidebar-item" hidden>
                            <a href="{{ admin_url('backups') }}" class="sidebar-link">
                                <i data-feather="hard-drive" class="feather-icon"></i> <span
                                        class="hide-menu">{{ trans('admin.backups') }}</span>
                            </a>
                        </li>
                    @endif
                    @if (
                    auth()->user()->can('system-info')
                    || auth()->user()->can('maintenance')
                    || userHasSuperAdminPermissions()
                    )
                        <li class="sidebar-item" hidden>
                            <a href="javascript:void(0)" class="sidebar-link has-arrow waves-effect waves-dark">
                                <i data-feather="alert-circle"></i> <span
                                        class="hide-menu">{{ trans('admin.system') }}</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                @if (
                                auth()->user()->can('maintenance') ||
                                userHasSuperAdminPermissions()
                                )
                                    @if (app()->isDownForMaintenance())
                                        @if (auth()->user()->can('maintenance') || userHasSuperAdminPermissions())
                                            <li class="sidebar-item">
                                                <a href="{{ admin_url('actions/maintenance/up') }}"
                                                   data-toggle="tooltip"
                                                   title="{{ trans('admin.Leave Maintenance Mode') }}"
                                                   class="sidebar-link maintenance-mode"
                                                >
                                                    <span class="hide-menu">{{ trans('admin.Live Mode') }}</span>
                                                </a>
                                            </li>
                                        @endif
                                    @else
                                        @if (auth()->user()->can('maintenance') || userHasSuperAdminPermissions())
                                            <li class="sidebar-item">
                                                <a href="{{ admin_url('actions/maintenance/down') }}"
                                                   data-toggle="tooltip"
                                                   title="{{ trans('admin.Put in Maintenance Mode') }}"
                                                   class="sidebar-link maintenance-mode"
                                                >
                                                    <span class="hide-menu">{{ trans('admin.Maintenance') }}</span>
                                                </a>
                                            </li>
                                        @endif
                                    @endif
                                @endif
                                @if (auth()->user()->can('system-info') || userHasSuperAdminPermissions())
                                    <li class="sidebar-item">
                                        <a href="{{ admin_url('system') }}" class="sidebar-link">
                                            <span class="hide-menu">{{ trans('admin.system_info') }}</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    <li class="sidebar-item" hidden>
                        <a href="{{ admin_url('logout') }}" class="sidebar-link p-0">
                            <i class="mdi mdi-adjust"></i>
                            <span class="hide-menu">{{ trans('admin.logout') }}</span>
                        </a>
                    </li>
                </ul>

            </nav>
        </div>
    </aside>
@endif
