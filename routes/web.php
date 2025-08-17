<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

    Route::group(['middleware' => 'throttle:6000000,1'], function () {
    Route::post('tap/update-subscription', '\App\Http\Controllers\ApphomeController@subscriptionmonthly');
    Route::get('/clear_cache_new_server', function () {
        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
        \Artisan::call('config:clear');
        dd('clear_cache,view,config');
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
                Route::get('/get-revenue/{range?}','DashboardController@getRevenue');

                // CRUD

                CRUD::resource('countries', 'CountryController');
                CRUD::resource('languages', 'LanguageController');
                CRUD::resource('packages', 'PackageController');
                CRUD::resource('report_types', 'ReportTypeController');
                CRUD::resource('roles', 'RoleController');
                CRUD::resource('settings', 'SettingController');
                CRUD::resource('users', 'UserController');
                CRUD::resource('employer', 'EmployerController');
                Route::get('employer-ajax', 'EmployerController@ajax');
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
                Route::get('employeeskill-ajax', 'EmployeeSkillController@ajax');
                Route::post('employeeSkill/skillAdd', 'EmployeeSkillController@skillAdd');
                Route::post('employeeSkill/skillEdit', 'EmployeeSkillController@skillEdit');
                Route::post('employeeSkill/add_feature', 'EmployeeSkillController@add_feature');
                Route::get('employeeSkill/delete/{id}', 'EmployeeSkillController@skilldelete');
                Route::get('send_email', 'EmployeeSkillController@send_email');
                Route::get('employeeSkill/updateStatus/{id}', 'EmployeeSkillController@updateStatus');
                Route::post('send_email_post', 'EmployeeSkillController@send_email_post');
                Route::get('delete_skill/{id}', 'EmployeeSkillController@delete_skill');

                // skill&experience

                Route::get('skillExperience', 'SkillExperienceController@view');
                Route::get('skill-experience-ajax', 'SkillExperienceController@ajax_experience');
                Route::get('employeeSkill', 'EmployeeSkillController@view');
                Route::get('skillExperience/skillAdd', 'SkillExperienceController@skillAdd');
                Route::get('skillExperience/skillEdit', 'SkillExperienceController@skillEdit');
                Route::get('skillExperience/experienceEdit', 'SkillExperienceController@experienceEdit');
                Route::get('skillExperience/experienceAdd', 'SkillExperienceController@experienceAdd');
                Route::get('skillExperience/experienceDelete/{id}', 'SkillExperienceController@experienceDelete');
                // Route::get('skillExperience/skillDelete/{id}', 'SkillExperienceController@skillDelete');
                Route::get('contact_us', 'SkillExperienceController@contact_us');
                Route::get('contact-us-ajax', 'SkillExperienceController@ajax');
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

                //Affiliate dashboard stats
                Route::get('affiliate_dashboard_stats', 'DashboardController@affiliateDashboardStats');

            });
        });
    
    //Admin2 routes   
        Route::group([
            'namespace' => 'App\Http\Controllers\Admin',
            'middleware' => ['web'],
            'prefix' => config('larapen.admin.route_prefix', 'admin'),
        ], function ($router) {
            Route::get('account', 'ProfileController@index');
            Route::post('update_account', 'ProfileController@update_account');
            Route::get('login_as_employee', 'UserController@loginemployee');
            Route::get('login_as_employer', 'UserController@loginemployer');

            Route::get('payments', 'PaymentController@payments');
            Route::get('payments-ajax', 'PaymentController@ajax');

            // Message Request
            Route::get('message_request', 'MessageRequestController@message_request');
            Route::post('approved_request', 'MessageRequestController@approved_request');
            Route::get('track_message_request/{id}', 'MessageRequestController@track_message_request');

            // Post Request
            Route::get('edit_post/{id}', 'PostController@edit_post');
            Route::get('approved_post/{id}', 'PostController@approved_post');
            Route::get('get_posts', 'PostController@get_posts');
            Route::get('job-post-ajax', 'PostController@ajax');
            Route::get('add_post_feature', 'PostController@add_post_feature');
            Route::get('update_status', 'PostController@update_status');
            Route::post('update_posts', 'PostController@update_posts');
            Route::get('delete_item', 'PostController@delete_item');
            Route::post('delete_post', 'PostController@delete_post');
            Route::get('applicants', 'PostController@applicants');
            Route::get('applicant-ajax', 'PostController@ajax_applicant');
            Route::post('approved_applicants', 'PostController@approved_applicants');
            Route::post('reject_bulk_applicant', 'PostController@reject_bulk_applicant');


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
            Route::post('update_applicant_status', 'EmployerController@update_applicant_status');

            // Employee Request

            Route::get('job-seekers', 'EmployeeController@index');
            Route::get('job-seekers-ajax', 'EmployeeController@ajax');
            Route::get('delete/{id}', 'EmployeeController@delete');
            Route::get('get_top_country_employee', 'EmployeeController@get_top_country_employee');
            Route::get('top-nationality-job-seekers', 'EmployeeController@get_top_nationality_employee');
            Route::get('bulk_download_cv', 'EmployeeController@bulk_download_cv');
            Route::post('update_cv_status', 'EmployeeController@update_cv_status');
            Route::get('compare-cv', 'EmployeeController@compare_cv');
            Route::get('load_cv_data', 'EmployeeController@loadCvData');
            Route::get('get_static_data', 'EmployeeController@get_static_data');
            Route::get('get_filters_data', 'EmployeeController@get_filters_data');
            Route::get('bulk_upload_hidden_detail_cv', 'EmployeeController@bulk_upload_hidden_detail_cv');
            Route::post('upload_hidden_detail_cv', 'EmployeeController@upload_hidden_detail_cv');
            Route::post('update_uploaded_hidden_detail_cv', 'EmployeeController@update_uploaded_hidden_detail_cv');
            //message controller

            Route::get('messages', 'MessageController@index');
            Route::get('messages/{id}', 'MessageController@show');
            Route::post('messages/update/{id}', 'MessageController@update');
            Route::post('messages/send', 'MessageController@messagesend');
            Route::get('messages/actions/{id}', 'MessageController@actions');
            Route::get('getUnreadMessage', 'MessageController@getUnreadMessage');

            Route::post('messages/delete/{id}', 'MessageController@deleteMessage');
            Route::post('messages/edit/{id}', 'MessageController@updateMessage');

            Route::get('affiliate_messages', 'AffiliateMessageController@index');
            Route::get('affiliate_messages/{id}', 'AffiliateMessageController@show');
            Route::post('affiliate_messages/send', 'AffiliateMessageController@messagesend');
            Route::post('affiliate_messages/update/{id}', 'AffiliateMessageController@update');
            Route::post('affiliate_messages/delete/{id}', 'AffiliateMessageController@deleteMessage');
            Route::get('getAffiliateUnreadMessage', 'AffiliateMessageController@getAffiliateUnreadMessage');


            Route::post('send_bulk_email', 'EmployeeController@send_bulk_email');
            Route::post('send_bulk_email_employer', 'EmployerController@send_bulk_email_employer');
            Route::post('approve_new_cv', 'EmployeeController@approve_new_cv');
            Route::post('approve_cv_no_contact', 'EmployeeController@approve_cv_no_contact');
            Route::post('reject_cv_no_contact', 'EmployeeController@reject_cv_no_contact');
            Route::post('delete_no_contact_cv', 'EmployeeController@delete_no_contact_cv');
            Route::get('set_skill_status_as_Read', 'EmployeeController@set_skill_status_as_Read');
            Route::post('upload_no_contact_cv', 'EmployeeController@upload_no_contact_cv');
            Route::get('delete_employee', 'EmployeeController@employee_delete');
            Route::post('delete_employee_all_records', 'EmployeeController@delete_employee_all_records');
            Route::get('edit_employee/{id}', 'EmployeeController@edit_employee');
            Route::post('city_change', 'EmployeeController@city_dependency')->name('city_change');
            Route::post('update_employee', 'EmployeeController@update_employee');
            Route::post('send_email', 'EmployeeController@send_email');
            Route::get('verify_employee_phone', 'EmployeeController@verify_employee_phone');
            Route::get('verify_employee_email', 'EmployeeController@verify_employee_email');
            Route::get('verify_employee_cv', 'EmployeeController@verify_employee_cv');
            Route::get('top-skill-jobs', 'EmployerController@get_top_skill_posts');
            // Company Request
            Route::get('get_company', 'CompanyController@get_company');
            Route::get('company-ajax', 'CompanyController@ajax');
            Route::get('delete_company/{id}', 'CompanyController@delete_company');
            Route::get('edit_company/{id}', 'CompanyController@edit_company');
            Route::post('update_company', 'CompanyController@update_company');

            // Gender Request
            Route::get('genders', 'GenderController@get_gender');
            Route::get('genders-ajax', 'GenderController@ajax');
            Route::post('post_title', 'GenderController@post_title');
            Route::get('delete_title/{id}', 'GenderController@delete_title');

            // Activity Request
            Route::get('get_logs', 'ActivitylogController@get_logs');
            Route::get('get_logs_ajax', 'ActivitylogController@ajax');
            Route::get('Tab_setting', 'ActivitylogController@Tab_setting_edit');
            Route::post('Tab_setting_update', 'ActivitylogController@Tab_setting_update');
            Route::get('Contact_Card_Problems', 'ActivitylogController@Contact_Card_Problems');
            Route::get('Contact_Card_Problems_ajax', 'ActivitylogController@Contact_Card_Problems_ajax');
            Route::get('contact_cards', 'ActivitylogController@ContactCards');
            Route::post('track_applicant', 'ActivitylogController@track_applicant');
            Route::get('contactdeleteproblem/{id}', 'ActivitylogController@Contact_problem_delete');
            Route::post('Contact_multiple_delete', 'ActivitylogController@Contact_multiple_delete');
            Route::get('payment_setting', 'ActivitylogController@payment_setting_edit');
            Route::post('payment_setting_update', 'ActivitylogController@payment_setting_update');
            Route::get('email_setting', 'EmailSettingsController@email_setting_edit');
            Route::get('email_stats', 'EmailSettingsController@email_stats');
            Route::get('email_stats-ajax', 'EmailSettingsController@ajax');
            Route::get('whatsapp', 'WhatsAppController@index');
            Route::get('whatsapp_ajax', 'WhatsAppController@ajax');
            Route::get('whatsapp_export', 'WhatsAppController@exportWhatsApp');

            Route::get('mailgun-failed-emails', 'EmailSettingsController@mailgun_failed_emails');
            Route::get('mailgun-ajax', 'EmailSettingsController@mailgun_ajax');
            Route::post('check_email_setting_update', 'EmailSettingsController@check_email_setting_update');
            Route::post('email_setting_update', 'EmailSettingsController@email_setting_update');
            Route::post('update_mailgun_settings', 'EmailSettingsController@update_mailgun_settings');
            Route::get('get_last_five_days_page_logs', 'ActivitylogController@get_last_five_days_page_logs');
            Route::get('get_company_filter_data', 'ActivitylogController@get_company_filter_data');

            Route::get('cache', 'SettingController@cache');
            Route::get('cache-clear', 'SettingController@cache_clear');
            

            // Page Count
            Route::get('get-page-analytics', 'PageCountController@get_all_page_count');
            Route::get('get-page-analytics-details', 'PageCountController@get_analytics_details');
            Route::get('analytics-detail-ajax', 'PageCountController@get_analytics_details_ajax');
            Route::get('statistics', 'PageCountController@statistics');
            Route::post('update_statistics', 'PageCountController@update_statistics');
            Route::get('analytics-data', 'PageCountController@getData');

            //Availability
            Route::get('/availability', 'AvailabilityController@index');
            Route::get('/availability-ajax', 'AvailabilityController@ajax');
            Route::post('/availability/availabilityEdit', 'AvailabilityController@availabilityedit');
            Route::post('/availability/availabilityAdd', 'AvailabilityController@add');
            Route::get('/availability/availabilityDelete/{id}', 'AvailabilityController@delete');
            Route::post('/availability/update_status', 'AvailabilityController@update_status');
            Route::post('/availability/edit', 'AvailabilityController@avai_edit');

            // staffs
            Route::get('/staffs', 'StaffController@index');
            Route::get('/staff-ajax', 'StaffController@ajax');
            Route::get('/staff/delete/{id}', 'StaffController@staff_delete');
            Route::get('/staff/edit/{id}', 'StaffController@staff_edit');
            Route::post('/staff/edit_post', 'StaffController@staff_edit_post');
            Route::post('/staff/updatePermissions', 'StaffController@updatePermissions');
            Route::get('/staff/permissions/{id}', 'StaffController@permissions');

            // rejected reasons
            Route::get('/rejected_reasons', 'RejectedReasonController@index');
            Route::get('/rejected-reasons-ajax', 'RejectedReasonController@ajax');
            Route::post('/rejected_reasons/post', 'RejectedReasonController@rejected_reason_post');
            Route::get('/rejected_reasons/delete/{id}', 'RejectedReasonController@rejected_reason_delete');

            Route::get('/package-cancel-reasons', 'PackageCancelReasonController@index');
            Route::get('/package-cancel-reasons-ajax', 'PackageCancelReasonController@ajax');
            Route::post('/package_cancel_reasons/post', 'PackageCancelReasonController@package_cancel_reasons_post');
            Route::get('/package_cancel_reason_delete/delete/{id}', 'PackageCancelReasonController@package_cancel_reason_delete');

            Route::get('/post-archived-or-delete-reasons', 'PostArchivedReasonController@index');
            Route::get('/post-archived-or-delete-reasons-ajax', 'PostArchivedReasonController@ajax');

            Route::post('/post_archived_reasons_post/post', 'PostArchivedReasonController@post_archived_reasons_post');
            Route::get('/post_archived_reason_delete/delete/{id}', 'PostArchivedReasonController@post_archived_reason_delete');

            // Post type
            Route::get('/p_types', 'PostTypeController@index');
            Route::get('/post-type-ajax', 'PostTypeController@ajax');
            Route::post('/post_type_post', 'PostTypeController@post_type_post');
            Route::get('/post_type_delete/{id}', 'PostTypeController@post_type_delete');
            // Salary type
            Route::get('/salary_types', 'SalaryTypeController@index');
            Route::get('/salary-type-ajax', 'SalaryTypeController@ajax');
            Route::post('/salary_type_post', 'SalaryTypeController@salary_type_post');
            Route::get('/salary_type_delete/{id}', 'SalaryTypeController@salary_type_delete');
            // pages
            Route::get('/pages', 'PageController@index');
            Route::get('/pages-ajax', 'PageController@ajax');
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

            Route::get('affiliates', 'AffiliateController@index');
            Route::get('create_affiliate', 'AffiliateController@create');
            Route::post('store_affiliate', 'AffiliateController@store_affiliate');
            Route::get('edit_affiliate/{id}', 'AffiliateController@edit');
            Route::post('update_affiliate/{id}', 'AffiliateController@update_request');
            Route::post('change_affiliate_status/{id}', 'AffiliateController@change_affiliate_status');
            Route::get('affiliates-ajax', 'AffiliateController@ajax');
            Route::get('bank_detail/{id}', 'AffiliateController@bank_detail');
            Route::post('edit_bank_detail', 'AffiliateController@edit_bank_detail');
            Route::post('delete_affiliate', 'AffiliateController@delete_affiliate');

            Route::get('withdraw_requests', 'WithdrawRequestController@index');
            Route::get('withdraw-requests-ajax', 'WithdrawRequestController@ajax');
            Route::post('withdraw_request_status_change', 'WithdrawRequestController@withdraw_request_status_change');
            Route::get('getUnseenWithdrawRequest', 'WithdrawRequestController@getUnseenWithdrawRequest');
            Route::get('check_payout_status/{payoutBatchId}', 'WithdrawRequestController@checkPayoutStatus');

            Route::get('referral_commission', 'ReferralCommissionController@index');
            Route::get('referral-commission-ajax', 'ReferralCommissionController@ajax');

            Route::get('affiliated-commission-slot/{id}', 'AffiliatesCommissionSlotController@get_affiliated_commission_slot');
            Route::post('add_affiliated_commission_slot', 'AffiliatesCommissionSlotController@affiliated_commission_slot_store');
            Route::get('delete_affiliated_commission_slot/{id}', 'AffiliatesCommissionSlotController@delete_affiliated_commission_slot');
            Route::get('edit_affiliated_commission_slot/{id}', 'AffiliatesCommissionSlotController@edit_affiliated_commission_slot');
            Route::post('update_affiliated_commission_slot', 'AffiliatesCommissionSlotController@update_affiliated_commission_slot');

            Route::get('affiliate_settings', 'AffiliateSettingController@index');
            Route::post('affiliate_settings', 'AffiliateSettingController@store');

            Route::get('email_logs', 'EmailLogController@index');
            Route::get('email_logs_ajax', 'EmailLogController@ajax');
            Route::get('view_email/{id}', 'EmailLogController@viewEmail');
            Route::post('resend_email', 'EmailLogController@resendEmail');
            Route::get('change_email_status/{id}', 'EmailLogController@changeEmailStatus');

            Route::get('twilio_logs', 'TwilioLogController@index');
            Route::get('twilio_logs_ajax', 'TwilioLogController@ajax');
            Route::get('view_message/{id}', 'TwilioLogController@viewMessage');
            Route::post('resend_message', 'TwilioLogController@resendMessage');
            Route::get('change_message_status/{id}', 'TwilioLogController@changeMessageStatus');
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

            // Marketing Static Pages
            Route::get('/industries/hospitality-leisure/hotels-resorts', 'HomeController@static');
            Route::get('/industries/hospitality-leisure/restaurants-cafes-bistros', 'HomeController@static');
            Route::get('/industries/hospitality-leisure/offee-tea-cafeterias', 'HomeController@static');
            Route::get('/industries/hospitality-leisure/gyms-fitness-centers', 'HomeController@static');
            Route::get('/industries/hospitality-leisure/salon-and-spa', 'HomeController@static');
            Route::get('/industries/hospitality-leisure/lounges-beverage-service', 'HomeController@static');
            Route::get('/industries/food-and-beverage/bakeries', 'HomeController@static');
            Route::get('/industries/food-and-beverage/delis', 'HomeController@static');
            Route::get('/industries/food-and-beverage/catering-companies', 'HomeController@static');
            Route::get('/industries/food-and-beverage/dessert-shops', 'HomeController@static');
            Route::get('/industries/food-and-beverage/food-and-beverage-management', 'HomeController@static');
            Route::get('/industries/food-and-beverage/food-distribution-wholesale', 'HomeController@static');
            Route::get('/industries/food-and-beverage/food-trucks-fast-food', 'HomeController@static');
            Route::get('/industries/events-entertainment/entertainment-companies', 'HomeController@static');
            Route::get('/industries/industries/retail-consumer/fashion-retail', 'HomeController@static');
            Route::get('/industries/industries/retail-consumer/flower-shops', 'HomeController@static');
            Route::get('/industries/industries/retail-consumer/shopping-malls', 'HomeController@static');
            Route::get('/industries/industries/retail-consumer/super-markets', 'HomeController@static');

            // Migrate To AWS
            Route::get('/migration', 'MigrateController@index');
            Route::get('/migration/users', 'MigrateController@getUsers');
            Route::get('/migration/move_file_to_aws', 'MigrateController@move_file_to_aws');
            Route::get('/migration/error-cvs-migration', 'MigrateController@getErrorCVs');
            Route::get('/migration/error-cv-users', 'MigrateController@getErrorCVUsers');
            Route::get('/migration/view-cv/{id}', 'Account\ResumeController@viewCV');
            Route::get('/migration/cron-new-user-cv-upload', 'MigrateController@CronToUploadResumeForNewUser');

            // Migrate profile picture To AWS
            Route::get('/migration-profile-image', 'MigrateController@migrateProfileImage');
            Route::get('/migration/profile-image-users', 'MigrateController@getProfileImages');
            Route::get('/migration/move_image_to_aws', 'MigrateController@move_image_to_aws');
            Route::get('/migration/error-profile-images', 'MigrateController@getErrorProfileImage');
            Route::get('/migration/profile-error-image-users', 'MigrateController@getProfileErrorImages');
            Route::get('/migration/cron-new-user-image-upload', 'MigrateController@CronToUploadImageForNewUser');

            // Migrate company picture To AWS
            Route::get('/migration-company-image', 'MigrateController@migrateCompanyImage');
            Route::get('/migration/profile-image-companies', 'MigrateController@getCompanyImages');
            Route::get('/migration/move_company_image_to_aws', 'MigrateController@move_company_image_to_aws');
            Route::get('/migration/error-company-images', 'MigrateController@getErrorCompanyImage');
            Route::get('/migration/profile-error-image-companies', 'MigrateController@getCompanyErrorImages');
            Route::get('/migration/cron-new-company-image-upload', 'MigrateController@CronToUploadImageForNewCompany');

            // HOMEPAGE
            Route::get('/', 'HomeController@index');
            Route::post('page_count', 'HomeController@page_count');
            Route::get('setcountry', 'HomeController@setcountry');
            Route::get('register_session', 'HomeController@register_session');
            Route::get('check_subscription_availiblity', 'HomeController@check_subscription_availiblity');
            Route::get('/postexpirecron', 'HomeController@postexpirecron');
            Route::get('/payment_subscription', 'HomeController@payment_subscription');
            Route::get('/send_unread_message_email', 'HomeController@send_unread_message_email');
            Route::get('cron/expire_post', 'CronController@expire_post');
            Route::get('delete_old_open_from_search_cv_applicants', 'CronController@delete_old_open_from_search_cv_applicants');
            Route::get('add_water_mark', 'CronController@add_water_mark');
            Route::get('add_water_mark_to_original', 'CronController@add_water_mark_to_original');
            // affiliate
            Route::post('store_affiliate', 'HomeController@store_affiliate');
            // affiliate
            Route::post('city_change_global', 'HomeController@city_dependency_global')->name('city_change_global');
            Route::get('commission_cal', 'CronController@commission_cal');
            Route::get('expire_withdraw_requests', 'CronController@expire_withdraw_requests');
            Route::get('commission_withdrawn_email', 'CronController@commission_withdrawn_email');


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
            Route::get('/track_applicant_in_employer/{id}', 'Account\UnlockController@track_applicant_in_employer');
            Route::post('/contact_card_problem', 'Account\UnlockController@contactproblem');


            // Cron

            Route::get('update_company_subscriptions', 'CronController@update_company_subscriptions');
            Route::get('send_email_to_company_for_interviewer_applicants', 'CronController@send_email_to_company_for_interviewer_applicants');
            Route::get('generateSitemap', 'CronController@generateSitemap');
            Route::get('send_applied_emails', 'CronController@send_applied_emails');
            Route::get('regenrate_thumbnails', 'CronController@regenrate_thumbnails');


            Route::get('unlink_old_data', 'CronController@unlink_old_data');
            Route::get('update_subscription_id', 'CronController@update_subscription_id');
            Route::get('delete_null_record', 'CronController@delete_null_record');

            Route::get('delete_archive_applicant', 'CronController@delete_archive_applicant');
            Route::get('create_user_thumbnail', 'CronController@create_user_thumbnail');
            Route::get('create_company_thumbnail', 'CronController@create_company_thumbnail');
            Route::get('copy_employer_images', 'CronController@copy_employer_images');

            Route::get('update_post_date_record', 'CronController@UpdatePostDatteRecord');
            Route::get('delete_extra_file_from_storage', 'CronController@delete_extra_file_from_storage');

            Route::get('send_email_queue_new_server', 'CronController@send_email_queue');
            Route::get('update-company-subscription-cron-new-server', 'CronController@update_company_subscription');
            Route::get('renew_company_package', 'CronController@renew_company_package');
            Route::get('reminder_new_server', 'CronController@reminder');
            Route::get('delete_page_logs_new_server', 'CronController@delete_page_logs');
            Route::get('backup', 'CronController@backup');
            Route::get('backuploads', 'CronController@backuploads');
            Route::get('delete_archived_jobs_applicants_new_server', 'CronController@delete_archived_jobs_applicants');
            Route::get('update-user-company-applicant-post-emails', 'CronController@update_user_company_applicant_post_emails');

            Route::get('send-whatsapp-message', 'CronController@send_whatsapp_message');
            Route::get('email_affiliate_on_package_previous_purchase', 'CronController@email_affiliate_on_package_previous_purchase');
            // Search Resume Request
            Route::get('search-resumes', 'ResumeSearchController@searchresumes');

            // AUTH
            Route::group(['middleware' => ['guest', 'no.http.cache']], function ($router) {
                // Registration Routes...
                Route::get(dynamicRoute('routes.register'), 'Auth\RegisterController@showRegistrationForm');
                Route::post(dynamicRoute('routes.register'), 'Auth\RegisterController@register');
                Route::get(dynamicRoute('routes.registration'), 'Auth\RegisterController@registration');
                Route::get(dynamicRoute('routes.affiliate-register'), 'Auth\RegisterController@affiliateRegistrationForm');


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
                    Route::post('posts/add_new_skill', 'CreateController@add_new_skill');
                    Route::post('posts/preview_post', 'CreateController@preview_post');
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
                    Route::post('/update_email_settings', 'ProfileController@update_email_settings');

                    // Route::group(['middleware' => 'impersonate.protect'], function () {
                    //     Route::put('/', 'ProfileController@update_profile');
                    //     Route::post('settings', 'ProfileController@updateSettings');
                    // });

                    Route::get('Applied-Jobs', 'ApplicationController@Applied_Jobs');
                    Route::post('update_status_bulk', 'ApplicationController@update_status_bulk');

                    Route::get('Applied-Jobs/remove/{id}', 'ApplicationController@remove');
                    Route::post('get_city_by_country', 'ResumeController@get_city_by_country');
                    Route::post('get_interview_state_applicants', 'ApplicationController@get_interview_state_applicants');

                    // Favorite Employee Controller
                    Route::get('favorite-resumes', 'FavoriteEmployeeController@favoriteresumes');
                    Route::get('add_to_favorite/{id}', 'FavoriteEmployeeController@addtofavorite');

                    // Save Cv Controller
                    Route::get('Saved-Resume', 'SaveCvController@Saved_Resume');
                    Route::get('profile/{id}/remove', 'SaveCvController@remove_save_resume');

                    Route::get('update_subscription_ajax', 'TransactionsController@update_subscription_ajax');

                    Route::get('company_cancel_subscription', 'TransactionsController@company_cancel_subscription');
                    Route::get('paymentFree', 'TransactionsController@paymentFree');
                    Route::get('make_subscriptions/{id}', 'TransactionsController@make_subscriptions');
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
                    Route::get('applicants_ajax', 'ApplicationController@applicants_ajax');
                    Route::get('applicants', 'ApplicationController@applicants');
                    Route::get('archived_applicants_ajax', 'ApplicationController@archived_applicants_ajax');
                    Route::post('Applicants/update_applicant_status_ajax', 'ApplicationController@update_applicant_status_ajax');


                    Route::get('Applicants/interview/{id}', 'ApplicationController@interview');
                    Route::get('Applicants/haired/{id}', 'ApplicationController@haired');
                    Route::get('Applicants/applied/{id}', 'ApplicationController@applied');
                    Route::post('rejected', 'ApplicationController@rejected');

                   // Transactions
                    Route::get('track_company_package_details', 'TransactionsController@track_company_package_details');
                    Route::get('upgrade', 'TransactionsController@upgrade');
                    Route::get('credentials/{id}', 'TransactionsController@credentials');
                    Route::get('post/show/{id}', 'TransactionsController@postShow');
                    Route::post('credentials/action', 'TransactionsController@payment');
                    Route::get('credentials/tappayment-redirect', 'TransactionsController@tappayment');
                    Route::get('credentials/tappayment-success', 'TransactionsController@tappaymentsuccess');
                    Route::get('credentials/tap-payment-error', 'TransactionsController@tappaymenterror');
                    Route::get('transactions', 'TransactionsController@index');
                    Route::post('cancel_subscription', 'TransactionsController@cancel_subscription');
                    Route::get('transaction-ajax', 'TransactionsController@ajax');
                    Route::get('transactions/invoice/{id}', 'TransactionsController@invoice');
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
                        Route::get('no-contact-cv/{id}', 'ResumeController@no_contact_cv');
                        Route::get('proxy-cv/{id}', 'ResumeController@proxyAwsPdf');
                    });

                    Route::get('cv-viewed', 'ResumeController@cv_viewed');
                    // Posts
                    Route::get('saved-search', 'PostsController@getSavedSearch');
                    $router->pattern('pagePath', '(my-posts|favourite|pending-approval|saved-search)+');
                    Route::get('{pagePath}', 'PostsController@getPage');
                    Route::get('{id}/delete', 'PostsController@destroy');
                    Route::post('{pagePath}/delete', 'PostsController@destroy');
                    Route::post('add_reason_for_post_archived_or_delete', 'PostsController@add_reason_for_post_archived_or_delete');

                    Route::get('get-my-posts', 'PostsController@getMyPosts');
                    Route::get('get-favourite-posts', 'PostsController@getFavouritePosts');

                    // Archive Job Controller
                    Route::get('archived', 'PostsController@getArchivedPosts');
                    Route::get('{id}/repost', 'PostsController@repost');

//                    Route::get('archived', 'ArchiveJobController@index');
                    Route::get('archived/{id}/delete', 'ArchiveJobController@destroy');
                    Route::get('archived/{id}/repost', 'ArchiveJobController@index');
                    Route::get('add_archived/{id}/offline', 'PostsController@add_archived');

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
                        Route::post('delete/{id}', 'MessagesController@deleteMessage');
                        Route::post('edit/{id}', 'MessagesController@updateMessage');
                    });
                    // Activity Logs
                    Route::get('activity_logs', 'ActivitylogController@index');
                    Route::get('activity-logs-ajax', 'ActivitylogController@ajax');

                    //user setting
                    Route::get('user_setting', 'UserSettingController@index');
                    Route::get('user_setting_ajax', 'UserSettingController@user_setting_ajax');
                    Route::post('user_setting_create', 'UserSettingController@user_setting_create');
                    Route::post('user_setting_update', 'UserSettingController@user_setting_update');
                });
            });

            // AJAX
            Route::group(['prefix' => 'ajax'], function ($router) {
                Route::get('countries/{countryCode}/admins/{adminType}', 'Ajax\LocationController@getAdmins');
                Route::get('countries/{countryCode}/admins/{adminType}/{adminCode}/cities', 'Ajax\LocationController@getCities');
                Route::get('countries/{countryCode}/cities/{id}', 'Ajax\LocationController@getSelectedCity');
                Route::post('countries/{countryCode}/cities/autocomplete', 'Ajax\LocationController@searchedCities');
                Route::post('countries/{countryCode}/admin/cities', 'Ajax\LocationController@getAdmin1WithCities');
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
            Route::get('affiliate-program', 'PageController@makemoney');

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
    

  /*
  |--------------------------------------------------------------------------
  | affiliate routes
  |--------------------------------------------------------------------------
  |
 */

    // affiliate routes
    Route::group([
        'namespace' => 'App\Http\Controllers\Affiliate',
        'middleware' => ['web'],
        'prefix' => config('larapen.affiliate.route_prefix', 'affiliate'),
    ], function ($router) {
        Route::get('dashboard', 'DashboardController@dashboard');
        Route::get('/affiliate_profile/{id}', 'ProfileController@affiliate_profile');
        Route::get('/profile', 'ProfileController@profile');
        Route::post('/update_profile', 'ProfileController@update_profile');
        Route::post('settings', 'ProfileController@updateSettings');
        Route::get('referral_users', 'ReferralUsersController@index');
        Route::get('referral_users_ajax', 'ReferralUsersController@ajax');
        Route::get('/user_purchase/{id}', 'ReferralUsersController@user_purchase');
        Route::get('user_purchase_ajax', 'ReferralUsersController@user_purchase_ajax');
        Route::get('bank_details', 'AffiliateBankDetailsController@index');
        Route::post('bank_details', 'AffiliateBankDetailsController@store');
        Route::get('withdraw_requests', 'WithdrawRequestController@index');
        Route::get('withdraw_requests_ajax', 'WithdrawRequestController@ajax');
        Route::post('delete_withdraw_request/{id}', 'WithdrawRequestController@deleteRequest');
        Route::get('commissions', 'ReferralCommissionController@index');
        Route::get('commissions_ajax', 'ReferralCommissionController@ajax');
        Route::get('commission-withdraw-request/{id}', 'ReferralCommissionController@commission_withdraw_request');
        Route::get('referral_affiliates', 'ReferralUsersController@referral_affiliates');
        Route::get('referral_affiliates_ajax', 'ReferralUsersController@referral_affiliates_ajax');

        // Messenger
        Route::group(['prefix' => 'messages'], function ($router) {
            $router->pattern('id', '[0-9]+');
            Route::get('/', 'AffiliateMessageController@index');
            Route::get('{id}', 'AffiliateMessageController@show');
            Route::post('messagesend/{id}', 'AffiliateMessageController@messagesend');
            Route::put('{id}', 'AffiliateMessageController@update');
            Route::get('{id}/actions', 'AffiliateMessageController@actions');
            Route::post('actions', 'AffiliateMessageController@actions');
            Route::post('delete/{id}', 'AffiliateMessageController@deleteMessage');
            Route::post('edit/{id}', 'AffiliateMessageController@updateMessage');
        });
    });
});
