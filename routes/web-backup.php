<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

    Route::group(['middleware' => 'throttle:60,1'], function () {
    Route::post('tap/update-subscription', '\App\Http\Controllers\ApphomeController@subscriptionmonthly');
    Route::get('/clear_cache', function () {
        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
        dd('clear_cache');
        });
    Route::group([
        'namespace' => 'App\Http\Controllers\Admin',
        'middleware' => ['web'],
        'prefix' => config('larapen.admin.route_prefix', 'admin'),
    ], function ($router) {
        // Auth
        // Authentication Routes...
        Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
        Route::post('login', 'Auth\LoginController@login');
        Route::post('impersonatelogin', 'Auth\LoginController@impersonatelogin');
        Route::get('logout', 'Auth\LoginController@logout')->name('logout');

        // Registration Routes...
        Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
        Route::post('register', 'Auth\RegisterController@register');


        // Password Reset Routes...
        Route::get('password/reset', 'Auth\ForgotcountryPasswordController@showLinkRequestForm')->name('password.request');
        Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
        Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
        Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

        // Admin Panel Area
        Route::group([
            'middleware' => ['admin', 'clearance', 'banned.user', 'no.http.cache'],
        ], function ($router) {
            // Dashboard
            Route::get('dashboard', 'DashboardController@dashboard');

            Route::get('/', 'DashboardController@redirect');

            // CRUD
            
            CRUD::resource('countries', 'CountryController');
            CRUD::resource('languages', 'LanguageController');
            CRUD::resource('packages', 'PackageController');
            CRUD::resource('report_types', 'ReportTypeController');
            CRUD::resource('roles', 'RoleController');
            CRUD::resource('settings', 'SettingController');
            CRUD::resource('users', 'UserController');
            CRUD::resource('employer', 'EmployerController');
            CRUD::resource('blacklists', 'BlacklistController');
            Route::post('ajax/{table}/{field}', 'InlineRequestController@make');

            // Actions
            Route::get('actions/clear_cache', 'ActionController@clearCache');
            Route::get('actions/clear_images_thumbnails', 'ActionController@clearImagesThumbnails');
            Route::get('actions/maintenance/{mode}', 'ActionController@maintenance')->where('mode', '(down|up)');

            // Re-send Email or Phone verification message
            Route::get('verify/user/{id}/resend/email', 'UserController@reSendVerificationEmail');
            Route::get('verify/user/{id}/resend/sms', 'UserController@reSendVerificationSms');
            Route::get('verify/post/{id}/resend/email', 'PostController@reSendVerificationEmail');
            Route::get('verify/post/{id}/resend/sms', 'PostController@reSendVerificationSms');

            // EmployeeSkill
            Route::get('employeeSkill', 'EmployeeSkillController@view');
            Route::post('employeeSkill/skillAdd', 'EmployeeSkillController@skillAdd');
            Route::post('employeeSkill/skillEdit', 'EmployeeSkillController@skillEdit');
            Route::get('employeeSkill/delete/{id}', 'EmployeeSkillController@skilldelete');
            Route::post('employeeSkill/add_feature', 'EmployeeSkillController@add_feature');
            Route::get('send_email', 'EmployeeSkillController@send_email');
            Route::get('employeeSkill/updateStatus/{id}', 'EmployeeSkillController@updateStatus');
            Route::post('send_email_post', 'EmployeeSkillController@send_email_post');

            // skill&experience

            Route::get('skillExperience', 'SkillExperienceController@view');
            Route::get('employeeSkill', 'EmployeeSkillController@view');
            Route::get('skillExperience/skillAdd', 'SkillExperienceController@skillAdd');
            Route::get('skillExperience/skillEdit', 'SkillExperienceController@skillEdit');
            Route::get('skillExperience/experienceEdit', 'SkillExperienceController@experienceEdit');
            Route::get('skillExperience/experienceAdd', 'SkillExperienceController@experienceAdd');
            Route::get('skillExperience/experienceDelete/{id}', 'SkillExperienceController@experienceDelete');
            // Route::get('skillExperience/skillDelete/{id}', 'SkillExperienceController@skillDelete');
            Route::get('contact_us', 'SkillExperienceController@contact_us');
            Route::get('contactDelete/{id}', 'SkillExperienceController@contactDelete');
            Route::get('contactsDelete', 'SkillExperienceController@contactsDelete');

            // Assign Credits
            Route::get('assignCredits', 'AssignCreditsController@view');
            Route::get('assignCredits/search', 'AssignCreditsController@search_user_by_email');
            Route::post('assignCredits/add', 'AssignCreditsController@add');

            // Entity & Causes
            Route::get('entityCauses', 'EntityCausesController@view');
            Route::get('entityCauses/entityAdd', 'EntityCausesController@entityAdd');
            Route::get('entityCauses/entityEdit', 'EntityCausesController@entityEdit');
            Route::get('entityCauses/causesEdit', 'EntityCausesController@causesEdit');
            Route::get('entityCauses/causesAdd', 'EntityCausesController@causesAdd');
            Route::get('entityCauses/causesDelete/{id}', 'EntityCausesController@causesDelete');
            Route::get('entityCauses/entityDelete/{id}', 'EntityCausesController@entityDelete');
            
        });
    });
    
    //Admin2 routes   
    Route::group([
        'namespace' => 'App\Http\Controllers\Admin2',
        'middleware' => ['web'],
        'prefix' => config('larapen.admin.route_prefix', 'admin'),
    ], function ($router) {
        Route::get('account', 'UserController@account');
        Route::post('update_account', 'UserController@update_account');
        Route::get('login_as_employee', 'UserController@loginemployee');
        Route::get('login_as_employer', 'UserController@loginemployer');

        Route::get('payments', 'PaymentController@payments');
        // Message Request
        Route::get('message_request', 'MessageRequestController@message_request');
        Route::post('approved_request', 'MessageRequestController@approved_request');
        Route::get('track_message_request/{id}', 'MessageRequestController@track_message_request');

        // Post Request
        Route::get('approved_post/{id}', 'PostController@approved_post'); 
        Route::get('edit_post/{id}', 'PostController@edit_post');
        Route::get('get_posts', 'PostController@get_posts');
        Route::get('add_post_feature', 'PostController@add_post_feature');
        Route::get('update_status', 'PostController@update_status');
        Route::post('update_posts', 'PostController@update_posts');
        Route::get('delete_item', 'PostController@delete_item');
        Route::post('delete_post', 'PostController@delete_post');
        Route::get('get_applicants', 'PostController@get_applicants');
        Route::post('approved_applicants', 'PostController@approved_applicants');
        // Employer Request
        Route::get('employer', 'EmployerController@index');
        Route::get('get_top_country_employer', 'EmployerController@get_top_country_employer');
        Route::post('update_employer', 'EmployerController@update_employer');
        Route::get('edit_employer/{id}', 'EmployerController@edit_employer');
        Route::post('country_change', 'EmployerController@city_dependency')->name('country_change');
        Route::get('add_feature', 'EmployerController@add_feature');
        Route::get('verify_employer_email', 'EmployerController@verify_employer_email');
        Route::get('verify_employer_phone', 'EmployerController@verify_employer_phone');
        Route::get('get_user_current_subscribed_packages', 'EmployerController@get_user_current_subscribed_packages');
        Route::post('cancel_subscription', 'EmployerController@cancel_subscription');
        Route::get('top-skill-jobs', 'EmployerController@get_top_skill_posts');

        // Employee Request

        Route::get('job-seekers', 'EmployeeController@index');
        Route::get('delete/{id}', 'EmployeeController@delete');
        Route::get('get_top_country_employee', 'EmployeeController@get_top_country_employee');
        Route::get('top-nationality-job-seekers', 'EmployeeController@get_top_nationality_employee');
        Route::post('send_bulk_email', 'EmployeeController@send_bulk_email');
        Route::get('delete_employee', 'EmployeeController@employee_delete');
        Route::post('approve_new_cv', 'EmployeeController@approve_new_cv');
        Route::get('set_skill_status_as_Read', 'EmployeeController@set_skill_status_as_Read');


        Route::post('delete_employee_all_records', 'EmployeeController@delete_employee_all_records');
        Route::get('edit_employee/{id}', 'EmployeeController@edit_employee');
        Route::post('city_change', 'EmployeeController@city_dependency')->name('city_change');
        Route::post('update_employee', 'EmployeeController@update_employee');
        Route::post('send_email', 'EmployeeController@send_email');
        Route::get('verify_employee_phone', 'EmployeeController@verify_employee_phone');
        Route::get('verify_employee_email', 'EmployeeController@verify_employee_email');

        // Company Request
        Route::get('get_company', 'CompanyController@get_company');
        Route::get('delete_company/{id}', 'CompanyController@delete_company');
        Route::get('edit_company/{id}', 'CompanyController@edit_company');
        Route::post('update_company', 'CompanyController@update_company');

        // Gender Request
        Route::get('genders', 'GenderController@get_gender');
        Route::post('post_title', 'GenderController@post_title');
        Route::get('delete_title/{id}', 'GenderController@delete_title');

        // Activity Request
        Route::post('track_applicant', 'ActivitylogController@track_applicant');
        Route::get('get_logs', 'ActivitylogController@get_logs');
        Route::get('Tab_setting', 'ActivitylogController@Tab_setting_edit');
        Route::post('Tab_setting_update', 'ActivitylogController@Tab_setting_update');
        Route::get('Contact_Card_Problems', 'ActivitylogController@Contact_Card_Problems');
        Route::get('contact_cards', 'ActivitylogController@ContactCards');
        Route::get('contactdeleteproblem/{id}', 'ActivitylogController@Contact_problem_delete');
        Route::post('Contact_multiple_delete', 'ActivitylogController@Contact_multiple_delete');
        Route::get('payment_setting', 'ActivitylogController@payment_setting_edit');
        Route::post('payment_setting_update', 'ActivitylogController@payment_setting_update');
        Route::get('email_setting', 'ActivitylogController@email_setting_edit');
        Route::post('check_email_setting_update', 'ActivitylogController@check_email_setting_update');
        Route::post('email_setting_update', 'ActivitylogController@email_setting_update');
        Route::get('get_last_five_days_page_logs', 'ActivitylogController@get_last_five_days_page_logs');

         // Page Count
        Route::get('get-page-analytics', 'PageCountController@get_all_page_count');
        Route::get('get-page-analytics-details', 'PageCountController@get_analytics_details');
        Route::get('statistics', 'PageCountController@statistics');
        Route::post('update_statistics', 'PageCountController@update_statistics');
        //Availability 
        Route::get('/availability', 'AvailabilityController@index');
        Route::post('/availability/availabilityEdit', 'AvailabilityController@availabilityedit');
        Route::post('/availability/availabilityAdd', 'AvailabilityController@add');
        Route::get('/availability/availabilityDelete/{id}', 'AvailabilityController@delete');
        Route::post('/availability/update_status', 'AvailabilityController@update_status');
        Route::post('/availability/edit', 'AvailabilityController@avai_edit');

        // staffs
        Route::get('/staffs', 'StaffController@index');
        Route::get('/staff/delete/{id}', 'StaffController@staff_delete');
        Route::get('/staff/edit/{id}', 'StaffController@staff_edit');
        Route::post('/staff/edit_post', 'StaffController@staff_edit_post');
        Route::post('/staff/updatePermissions', 'StaffController@updatePermissions');
        Route::get('/staff/permissions/{id}', 'StaffController@permissions');
        
        // rejected reasons
        Route::get('/rejected_reasons', 'RejectedReasonController@index');
        Route::post('/rejected_reasons/post', 'RejectedReasonController@rejected_reason_post');
        Route::get('/rejected_reasons/delete/{id}', 'RejectedReasonController@rejected_reason_delete');

        // Post type 
        Route::get('/p_types', 'PostTypeController@index');
        Route::post('/post_type_post', 'PostTypeController@post_type_post');
        Route::get('/post_type_delete/{id}', 'PostTypeController@post_type_delete');
        // Salary type
        Route::get('/salary_types', 'SalaryTypeController@index');
        Route::post('/salary_type_post', 'SalaryTypeController@salary_type_post');
        Route::get('/salary_type_delete/{id}', 'SalaryTypeController@salary_type_delete');
        // pages 
        Route::get('/pages', 'PageController@index');
        Route::post('/update_pages', 'PageController@update_pages');
        Route::post('/update_status/pages', 'PageController@update_status');
        Route::get('/pages_edit/{id}', 'PageController@pages_edit');
        // Currency
        Route::get('/add/currency', 'CurrencyController@add');
        Route::get('/currencies', 'CurrencyController@index');
        Route::post('/update_currency', 'CurrencyController@update_currency');
        Route::post('/add_currency', 'CurrencyController@add_currency');
        Route::post('/update_status/currency', 'CurrencyController@update_status');
        Route::get('/currency_edit/{id}', 'CurrencyController@currency_edit');
        Route::get('/delete_currency/{id}', 'CurrencyController@delete_currency');


     

    });

    Route::group([
        'namespace' => 'App\Http\Controllers',
        'middleware' => ['web'],
    ], function ($router) {
        // SEO
        Route::get('sitemaps.xml', 'SitemapsController@index');
        // Impersonate (As admin user, login as an another user)
        Route::group(['middleware' => 'auth'], function ($router) {
            Route::impersonate();
        });
    });

    /*
  |--------------------------------------------------------------------------
  | Front-end
  |--------------------------------------------------------------------------
  |
  | The translated front-end routes
  |
 */
    Route::group([
        'namespace' => 'App\Http\Controllers',
    ], function ($router) {
        Route::group(['middleware' => ['web']], function ($router) {
            // Country Code Pattern
            $countryCodePattern = implode('|', array_map('strtolower', array_keys(getCountries())));
            $countryCodePattern = !empty($countryCodePattern) ? $countryCodePattern : 'us';
            /*
         * NOTE:
         * '(?i:foo)' : Make 'foo' case-insensitive
         */
            $countryCodePattern = '(?i:' . $countryCodePattern . ')';
            $router->pattern('countryCode', $countryCodePattern);
            // HOMEPAGE
            Route::get('/', 'HomeController@index');
            Route::post('page_count', 'HomeController@page_count');
            Route::get('setcountry', 'HomeController@setcountry');
            Route::get('register_session', 'HomeController@register_session');
            Route::get('check_subscription_availiblity', 'HomeController@check_subscription_availiblity');
            Route::get('/postexpirecron', 'HomeController@postexpirecron');
            Route::get('/payment_subscription', 'HomeController@payment_subscription');
            Route::get('/send_unread_message_email', 'HomeController@send_unread_message_email');

            Route::get(dynamicRoute('routes.countries'), 'CountriesController@index');
            // Account Profile Request
            Route::get('/profile/{id}', 'Account\ProfileController@get_profile');
            Route::get('/employee_profile/{id}', 'Account\ProfileController@employee_profile');
            Route::post('/upload_logo', 'Account\ProfileController@upload_logo');
            Route::get('/delete_employee_logo/{id}', 'Account\ProfileController@DeleteEmployeelogo');
            Route::post('/update_employee_profile', 'Account\ProfileController@update_employee_profile');

            Route::get('/companyprofile/{id}', 'Account\CompanyController@companyprofile');

            Route::get('userdata', 'PageController@userdata');
            // Unlock Contact Card Request
            Route::get('/UnlockProfile/{id}', 'Account\UnlockController@UnlockProfile');
            Route::post('/contact_card_problem', 'Account\UnlockController@contactproblem');
            Route::get('/track_applicant_in_employer/{id}', 'Account\UnlockController@track_applicant_in_employer');


            // Cron 
            
            Route::get('tap/get_subscription/{id}', 'CronController@get_subscription');
            Route::get('delete_archive_applicant', 'CronController@delete_archive_applicant');
            Route::get('create_employer_thumbnail', 'CronController@create_employer_thumbnail');
            Route::get('copy_employer_images', 'CronController@copy_employer_images');


            Route::get('update_company_subscriptions', 'CronController@update_company_subscriptions');
            Route::get('update_subscription_id', 'CronController@update_subscription_id');
            Route::get('delete_null_record', 'CronController@delete_null_record');
            Route::get('update_company_data', 'CronController@update_company_data');
            Route::get('update_company_subscriptions', 'CronController@update_company_subscriptions');
            Route::get('send_email_queue', 'CronController@send_email_queue');
            Route::get('update-company-subscription-cron', 'CronController@update_company_subscription');
            Route::get('reminder', 'CronController@reminder');
            Route::get('delete_page_logs', 'CronController@delete_page_logs');
            Route::get('backup', 'CronController@backup');
            Route::get('backuploads', 'CronController@backuploads');
            Route::get('delete_archived_jobs_applicants', 'CronController@delete_archived_jobs_applicants');
            Route::get('update-user-company-applicant-post-emails', 'CronController@update_user_company_applicant_post_emails');
            // Search Resume Request
            Route::get('search-resumes', 'ResumeSearchController@searchresumes');

            // AUTH
            Route::group(['middleware' => ['guest', 'no.http.cache']], function ($router) {
                // Registration Routes...
                Route::get(dynamicRoute('routes.register'), 'Auth\RegisterController@showRegistrationForm');
                Route::post(dynamicRoute('routes.register'), 'Auth\RegisterController@register');


                // Authentication Routes...
                Route::get(dynamicRoute('routes.login'), 'Auth\LoginController@showLoginForm');
                Route::post(dynamicRoute('routes.login'), 'Auth\LoginController@login');

                // Forgot Password Routes...
                Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
                Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');

                // Reset Password using Token
                Route::get('password/token', 'Auth\ForgotPasswordController@showTokenRequestForm');
                Route::post('password/token', 'Auth\ForgotPasswordController@sendResetToken');

                // Reset Password using Link (Core Routes...)
                Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
                Route::post('password/reset', 'Auth\ResetPasswordController@reset');
            });
            //Forget password of employee and employer by admin
            Route::post('/password/reset', 'Auth\RegisterController@resetpass');
            Route::post('set_user_type', 'Auth\RegisterController@set_user_type');
            Route::post('/send-reset-password-email', 'Auth\RegisterController@sendResetPasswordEmail');
            Route::post('/update-password', 'Auth\RegisterController@UpdatePassword');

            // Email Address or Phone Number verification
            $router->pattern('field', 'email|phone');
            Route::get('verify/user/{id}/resend/email', 'Auth\RegisterController@reSendVerificationEmail');
            Route::get('verify/user/{id}/resend/sms', 'Auth\RegisterController@reSendVerificationSms');
            Route::get('verify/user/{field}/{token?}', 'Auth\RegisterController@verification');
            Route::post('verify/user/{field}/{token?}', 'Auth\RegisterController@verification');


            // User Logout
            Route::get(dynamicRoute('routes.logout'), 'Auth\LoginController@logout');

            // POSTS
            Route::group(['namespace' => 'Post'], function ($router) {
                $router->pattern('id', '[0-9]+');
                $bannedSlugs = collect(config('routes'))->filter(function ($value, $key) {
                    return (!Str::contains($key, '.') && !empty($value));
                })->flatten()->toArray();
                if (!empty($bannedSlugs)) {
                    $router->pattern('slug', '^(?!' . implode('|', $bannedSlugs) . ')(?=.*)((?!\/).)*$');
                } else {
                    $router->pattern('slug', '^(?=.*)((?!\/).)*$');
                }

                // MultiSteps Post creation
                Route::group(['namespace' => 'CreateOrEdit\MultiSteps'], function ($router) {
                    Route::get('posts/create/{tmpToken?}', 'CreateController@getForm');
                    Route::get('posts/create/{tmpToken}/finish', 'CreateController@finish');
                    Route::get('posts/get_company_by_id/{id}', 'CreateController@get_company_by_id');
                    Route::get('posts/get_post_data/{id}', 'CreateController@get_post_data');
                    Route::get('posts/resendemail/{id}', 'CreateController@resendemail');
                    Route::post('posts/create', 'CreateController@postForm');
                    Route::post('posts/preview_post', 'CreateController@preview_post');
                    Route::post('posts/add_new_skill', 'CreateController@add_new_skill');
                });

                Route::group(['middleware' => 'auth'], function ($router) {
                    $router->pattern('id', '[0-9]+');

                    // MultiSteps Post edition
                    Route::group(['namespace' => 'CreateOrEdit\MultiSteps'], function ($router) {
                        Route::get('posts/{id}/edit', 'EditController@getForm');
                        Route::post('posts/{id}/edit', 'EditController@postForm');
                    });
                });
                // Post's Details
                Route::get(dynamicRoute('routes.post'), 'DetailsController@index');
            });

            Route::post('send-by-email', 'Search\SearchController@sendByEmail');
            // ACCOUNT
            Route::group(['prefix' => 'account'], function ($router) {
                // Contact Post's Author
                Route::group([
                    'namespace' => 'Account',
                    'prefix' => 'messages',
                ], function ($router) {
                    Route::post('messagesend/{id}', 'MessagesController@messagesend');
                });
                
                Route::group([
                    'middleware' => ['auth', 'banned.user', 'no.http.cache'],
                    'namespace' => 'Account'
                ], function ($router) {
                    $router->pattern('id', '[0-9]+');
                    Route::post('apply_post/{id}', 'PostApplyController@store');
                    // Users
                    Route::get('/', 'ProfileController@index');
                    Route::get('/profile', 'ProfileController@profile');
                    Route::post('/update_profile', 'ProfileController@update_profile');
                    Route::post('settings', 'ProfileController@updateSettings');
                    // Route::group(['middleware' => 'impersonate.protect'], function () {
                    //     Route::put('/', 'ProfileController@update_profile');
                    //     Route::post('settings', 'ProfileController@updateSettings');
                    // });

                    Route::get('Applied-Jobs', 'ApplicationController@Applied_Jobs');
                    Route::get('Applied-Jobs/remove/{id}', 'ApplicationController@remove');
                    Route::post('get_city_by_country', 'ResumeController@get_city_by_country');

                    // Favorite Employee Controller
                    Route::get('favorite-resumes', 'FavoriteEmployeeController@favoriteresumes');
                    Route::get('add_to_favorite/{id}', 'FavoriteEmployeeController@addtofavorite');

                    // Save Cv Controller
                    Route::get('Saved-Resume', 'SaveCvController@Saved_Resume');
                    Route::get('profile/{id}/remove', 'SaveCvController@remove_save_resume');

                    Route::get('company_cancel_subscription', 'TransactionsController@company_cancel_subscription');
                    Route::get('paymentFree', 'TransactionsController@paymentFree');
                    Route::get('applied_applicants', 'ApplicationController@applied_applicants');
                    Route::get('interview_applicants', 'ApplicationController@interview_applicants');
                    Route::get('hired_applicants', 'ApplicationController@hired_applicants');
                    Route::get('rejected_applicants', 'ApplicationController@rejected_applicants');
                    Route::get('Archive_applicants', 'ApplicationController@Archive_applicants');
                    Route::get('Applicants/unlock/{id}', 'ApplicationController@unlock');
                    Route::post('resumes/sendResumeByEmail', 'ResumeController@sendResumeByEmail');
                    Route::post('save_resume_add', 'ApplicationController@save_resume_add');
                    // UnlockController
                    Route::get('Unlocked-Contact-Cards', 'UnlockController@UnlockedContactCards');
                    Route::post('unlock_contact_card_in_bulk', 'UnlockController@UnlockContactCardBulk');

                    
                    Route::post('check_company_has_unlock_applicants', 'UnlockController@check_company_has_unlock_applicants');
                    Route::post('Applicants/update_applicant_status', 'ApplicationController@update_applicant_status');
                    

                    Route::get('Applicants/interview/{id}', 'ApplicationController@interview');
                    Route::get('Applicants/haired/{id}', 'ApplicationController@haired');
                    Route::get('Applicants/applied/{id}', 'ApplicationController@applied');
                    Route::post('rejected', 'ApplicationController@rejected');

                   // Transactions
                    Route::get('upgrade', 'TransactionsController@upgrade');
                    Route::get('credentials/{id}', 'TransactionsController@credentials');
                    Route::get('post/show/{id}', 'TransactionsController@postShow');
                    Route::post('credentials/action', 'TransactionsController@payment');
                    Route::get('credentials/tappayment-redirect', 'TransactionsController@tappayment');
                    Route::get('credentials/tappayment-success', 'TransactionsController@tappaymentsuccess');
                    Route::get('credentials/tap-payment-error', 'TransactionsController@tappaymenterror');
                    Route::get('transactions', 'TransactionsController@index');
                    Route::get('cancel_subscription', 'TransactionsController@cancel_subscription');
                    Route::get('track_company_package_details', 'TransactionsController@track_company_package_details');
                    // Companies
                    Route::group(['prefix' => 'companies'], function ($router) {
                        Route::get('/', 'CompanyController@index');
                        Route::get('create', 'CompanyController@create');
                        Route::post('/', 'CompanyController@store');
                        Route::get('{id}', 'CompanyController@show');
                        Route::get('{id}/edit', 'CompanyController@edit');
                        Route::put('{id}', 'CompanyController@update');
                        Route::get('{id}/delete', 'CompanyController@destroy');
                        Route::post('delete', 'CompanyController@destroy');
                    });

                    // Companies
                    Route::group(['prefix' => 'skill_set'], function ($router) {
                        Route::get('/', 'EmployeeSkillSetController@index');
                        Route::post('/', 'EmployeeSkillSetController@save_skill_set');
                    });

                    // Staff
                    Route::group(['prefix' => 'staff'], function ($router) {
                        Route::get('/', 'StaffController@index');
                        Route::get('create', 'StaffController@create');
                        Route::post('/', 'StaffController@store');
                        Route::get('{id}', 'StaffController@show');
                        Route::get('{id}/edit', 'StaffController@edit');
                        Route::put('{id}', 'StaffController@update');
                        Route::get('{id}/delete', 'StaffController@destroy');
                        Route::post('delete', 'StaffController@destroy');
                        Route::get('/{id}/change_password', 'StaffController@change_password');
                        Route::put('update_passwords', 'StaffController@update_passwords');

                        //permission
                        Route::get('/{id}/permissions', 'StaffController@permissions');
                        Route::post('/permissions', 'StaffController@updatePermissions');
                    });

                    // Resumes
                    Route::group(['prefix' => 'resumes'], function ($router) {
                        Route::get('user_resume', 'ResumeController@user_resume');
                        Route::post('reupload_resume', 'ResumeController@reupload_resume');
                        Route::get('show_cv/{id}', 'ResumeController@show_cv');
                    });

                    Route::get('cv-viewed', 'ResumeController@cv_viewed');
                    // Posts
                    Route::get('saved-search', 'PostsController@getSavedSearch');
                    $router->pattern('pagePath', '(my-posts|favourite|pending-approval|saved-search)+');
                    Route::get('{pagePath}', 'PostsController@getPage');
                    Route::get('{pagePath}/{id}/delete', 'PostsController@destroy');
                    Route::post('{pagePath}/delete', 'PostsController@destroy');

                    // Archive Job Controller
                    Route::get('archived', 'ArchiveJobController@index');
                    Route::get('archived/{id}/delete', 'ArchiveJobController@destroy');
                    Route::get('archived/{id}/repost', 'ArchiveJobController@index');
                    Route::get('add_archived/{id}/offline', 'ArchiveJobController@add_archived');

                    // Messenger
                    Route::group(['prefix' => 'messages'], function ($router) {
                        $router->pattern('id', '[0-9]+');
                        Route::post('check-new', 'MessagesController@checkNew');
                        Route::get('/', 'MessagesController@index');
                        Route::get('{id}', 'MessagesController@show');
                        Route::post('checkcontact', 'MessagesController@checkcontact');
                        Route::put('{id}', 'MessagesController@update');
                        Route::get('{id}/actions', 'MessagesController@actions');
                        Route::post('actions', 'MessagesController@actions');
                        Route::post('mark-as-read', 'MessagesController@markAllAsRead');
                        Route::post('getuserbyid', 'MessagesController@getuserbyid');
                        Route::get('message_request', 'MessagesController@message_request');
                        Route::post('message_request_post', 'MessagesController@message_request_post');
                        Route::get('track_bulk_request/{id}', 'MessagesController@track_bulk_request');
                        Route::get('delete_message_request/{id}', 'MessagesController@delete_message_request');
                    });
                });
            });

            // AJAX
            Route::group(['prefix' => 'ajax'], function ($router) {
                Route::get('countries/{countryCode}/admins/{adminType}', 'Ajax\LocationController@getAdmins');
                Route::get('countries/{countryCode}/admins/{adminType}/{adminCode}/cities', 'Ajax\LocationController@getCities');
                Route::get('countries/{countryCode}/cities/{id}', 'Ajax\LocationController@getSelectedCity');
                Route::post('countries/{countryCode}/cities/autocomplete', 'Ajax\LocationController@searchedCities');
                Route::post('countries/{countryCode}/admin1/cities', 'Ajax\LocationController@getAdmin1WithCities');
                Route::post('category/select-category', 'Ajax\CategoryController@getCategoriesHtml');
                Route::post('save/post', 'Ajax\PostController@savePost');
                Route::post('save/search', 'Ajax\PostController@saveSearch');
                Route::post('post/phone', 'Ajax\PostController@getPhone');
                Route::get('get_city_by_country/{countryCode}', 'Ajax\LocationController@get_city_by_country');
            });

            // FEEDS
            Route::feeds();

            // SITEMAPS (XML)
            Route::get('{countryCode}/sitemaps.xml', 'SitemapsController@site');
            Route::get('{countryCode}/sitemaps/pages.xml', 'SitemapsController@pages');
            Route::get('{countryCode}/sitemaps/categories.xml', 'SitemapsController@categories');
            Route::get('{countryCode}/sitemaps/cities.xml', 'SitemapsController@cities');
            Route::get('{countryCode}/sitemaps/posts.xml', 'SitemapsController@posts');

            // PAGES
            Route::get(dynamicRoute('routes.pricing'), 'PageController@pricing');
            Route::get(dynamicRoute('routes.pageBySlug'), 'PageController@cms');
            Route::get(dynamicRoute('routes.contact'), 'PageController@contact');
            Route::post(dynamicRoute('routes.contact'), 'PageController@contactPost');
            // SITEMAP (HTML)
            Route::get(dynamicRoute('routes.sitemap'), 'SitemapController@index');
            // SEARCH
            Route::group(['namespace' => 'Search'], function ($router) {
               //        
                $router->pattern('id', '[0-9]+');
                $router->pattern('username', '[a-zA-Z0-9]+');
                Route::get(dynamicRoute('routes.companies'), 'CompanyController@index');
                Route::get(dynamicRoute('routes.search'), 'SearchController@index');
                Route::get(dynamicRoute('routes.searchPostsByUserId'), 'UserController@index');
                Route::get(dynamicRoute('routes.searchPostsByUsername'), 'UserController@profile');
                Route::get(dynamicRoute('routes.searchPostsByCompanyId'), 'CompanyController@profile');
                Route::get(dynamicRoute('routes.searchPostsByCity'), 'CityController@index');
                Route::get(dynamicRoute('routes.searchPostsBySubCat'), 'CategoryController@index');
                Route::get(dynamicRoute('routes.searchPostsByCat'), 'CategoryController@index');
            });
        });
    });
});
