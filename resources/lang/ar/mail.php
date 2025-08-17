<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Emails Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used by the Mail notifications.
    |
    */

    // built-in template
    'Whoops!' => 'عذرًا!',
    'Hello!' => 'مرحبًا!',
    'Regards' => 'يعتبر',
    "having_trouble_on_link" => "إذا كنت تواجه مشكلة في النقر فوق \":actionText\" الزر ، انسخ والصق عنوان URL أدناه \ n في مستعرض الويب الخاص بك:",
    'All rights reserved.' => 'كل الحقوق محفوظة.',


    // mail salutation
    'footer_salutation' => 'يعتبر,<br>:appName',


    // custom mail_footer (unused)
    'mail_footer_content'             => 'موقع ويب بوابة الوظائف. بسيط وسريع وفعال.',


    // email_verification
    'email_verification_title'        => 'يرجى التحقق من عنوان البريد الإلكتروني الخاص بك.',
    'email_verification_action'       => 'التحقق من عنوان البريد الإلكتروني',
    'email_verification_content_1'    => 'مرحبًا: اسم المستخدم !',
    'email_verification_content_2'    => 'انقر فوق الزر أدناه للتحقق من عنوان بريدك الإلكتروني.',
    'email_verification_content_3'    => 'أنت تتلقى هذا البريد الإلكتروني لأنك قمت مؤخرًا بإنشاء ملف:appName حساب أو إضافة عنوان بريد إلكتروني جديد. إذا لم تكن أنت ، يرجى تجاهل هذا البريد الإلكتروني.',


    // post_activated (new)
    'post_activated_title'              => 'تم تفعيل إعلانك',
    'post_activated_content_1'          => 'مرحبًا,',
    'post_activated_content_2'          => 'إعلانك <a href=":postUrl">:title</a> تم تفعيله.',
    'post_activated_content_3'          => 'سيتم فحصه قريبًا من قبل أحد المسؤولين لدينا لنشره على الإنترنت.',
    'post_activated_content_4'          => 'تتلقى هذا البريد الإلكتروني لأنك قمت مؤخرًا بإنشاء إعلان جديد في:appName. إذا لم تكن أنت ، يرجى تجاهل هذا البريد الإلكتروني.',


    // post_reviewed (new)
    'post_reviewed_title'               => 'إعلانك الآن على الإنترنت',
    'post_reviewed_content_1'           => 'مرحبًا,',
    'post_reviewed_content_2'           => 'Your ad <a href=":postUrl">:title</a> is now online.',
    'post_reviewed_content_3'           => 'تتلقى هذا البريد الإلكتروني لأنك قمت مؤخرًا بإنشاء إعلان جديد في:appName. إذا لم تكن أنت ، يرجى تجاهل هذا البريد الإلكتروني.',


    // post_republished (new)
    'post_republished_title'              => 'تمت إعادة نشر إعلانك',
    'post_republished_content_1'          => 'مرحبًا,',
    'post_republished_content_2'          => 'إعلانك<a href=":postUrl">:title</a> تم إعادة نشره بنجاح.',
    'post_republished_content_3'          => 'تتلقى هذا البريد الإلكتروني لأنك قمت مؤخرًا بإنشاء إعلان جديد في :appName. إذا لم تكن أنت ، يرجى تجاهل هذا البريد الإلكتروني.',


    // post_deleted
    'post_deleted_title'                => 'تم حذف إعلانك',
    'post_deleted_content_1'            => 'مرحبًا,',
    'post_deleted_content_2'            => 'إعلانك ":title" تم حذفه من <a href=":appUrl">:appName</a> at :now.',
    'post_deleted_content_3'            => 'شكرا لك على ثقتك ونراكم قريبا,',
    'post_deleted_content_4'            => 'ملاحظة: هذا بريد إلكتروني تلقائي ، من فضلك لا ترد.',


    // post_employer_contacted
    'post_employer_contacted_title'     => 'إعلانك":title" تشغيل :appName',
    'post_employer_contacted_content_1' => '<strong>معلومات الاتصال:</strong>
<br>Name: :name
<br>Email address: :email
<br>Phone number: :phone',
    'post_employer_contacted_content_2' => 'تم إرسال هذا البريد الإلكتروني لك حول الإعلان ":title" قمت بتقديمه في :appName : <a href=":postUrl">:postUrl</a>',
    'post_employer_contacted_content_3' => 'NOTE: الشخص الذي اتصل بك لا يعرف بريدك الإلكتروني لأنك لن ترد.',
    'post_employer_contacted_content_4' => '',
    'post_employer_contacted_content_5' => '',
    'post_employer_contacted_content_6' => 'شكرا لك على ثقتك ونراكم قريبا.',
    'post_employer_contacted_content_7' => 'ملاحظة: هذا بريد إلكتروني تلقائي ، من فضلك لا ترد.',


    // user_deleted
    'user_deleted_title'              => 'تم حذف حسابك في:appName',
    'user_deleted_content_1'          => 'مرحبًا,',
    'user_deleted_content_2'          => 'تم حذف حسابك من <a href=":appUrl">:appName</a> at :now.',
    'user_deleted_content_3'          => 'شكرا لك على ثقتك ونراكم قريبا.',
    'user_deleted_content_4'          => 'ملاحظة: هذا بريد إلكتروني تلقائي ، من فضلك لا ترد.',


    // user_activated (new)
    'user_activated_title'            => 'مرحبا بك في :appName !',
    'user_activated_content_1'        => 'مرحبا بك في :appName :userName !',
    'user_activated_content_2'        => 'تم تنشيط حسابك.',
    'user_activated_content_3'        => '<strong>Note : :appName فريق يوصي بأنك:</strong>
<br><br>1 - لا ترسل الأموال أبدًا عن طريق Western Union أو أي تفويض دولي آخر.
<br>2 - إذا كان لديك أي شك حول جدية أحد المعلنين ، يرجى الاتصال بنا على الفور. يمكننا بعد ذلك تحييده في أسرع وقت ممكن ومنع أي شخص أقل معرفة من أن يصبح ضحية.',
    'user_activated_content_4'        => 'تتلقى هذا البريد الإلكتروني لأنك أنشأت مؤخرًا حسابًا جديدًا: appName. إذا لم تكن أنت ، يرجى تجاهل هذا البريد الإلكتروني.',


    // reset_password
    'reset_password_title'            => 'اعد ضبط كلمه السر',
    'reset_password_action'           => 'إعادة تعيين كلمة المرور',
    'reset_password_content_1'        => 'نسيت رقمك السري?',
    'reset_password_content_2'        => 'دعنا نحضر لك واحدة جديدة.',
    'reset_password_content_3'        => 'إذا لم تطلب إعادة تعيين كلمة المرور ، فلا داعي لاتخاذ أي إجراء آخر.',


    // contact_form
    'contact_form_title'              => 'رسالة جديدة من :appName',


    // post_report_sent
    'post_report_sent_title'            => 'تقرير إساءة جديد',
    'Post URL'                          => 'عنوان URL المنشور',


    // ad archived
    'post_archived_title'               => 'تم وضع إعلانك في الأرشيف',
    'post_archived_content_1'           => 'مرحبًا,',
    'post_archived_content_2'           => 'إعلانك":title" تم أرشفته من :appName at :الآن.',
    'post_archived_content_3'           => 'يمكنك إعادة نشرها بالضغط هنا : <a href=":repostUrl">:repostUrl</a>',
    'post_archived_content_4'           => 'إذا لم تفعل شيئًا فسيتم حذف إعلانك نهائيًا في:dateDel.',
    'post_archived_content_5'           => 'شكرا لك على ثقتك ونراكم قريبا.',
    'post_archived_content_6'           => 'ملاحظة: هذا بريد إلكتروني تلقائي ، من فضلك لا ترد.',


    // post_will_be_deleted
    'post_will_be_deleted_title'        => 'سيتم حذف إعلانك في:days days',
    'post_will_be_deleted_content_1'    => 'مرحبًا,',
    'post_will_be_deleted_content_2'    => 'إعلانك ":title" سيتم حذفها في :أيام من أيام :appName.',
    'post_will_be_deleted_content_3'    => 'يمكنك إعادة نشرها بالضغط هنا : <a href=":repostUrl">:repostUrl</a>',
    'post_will_be_deleted_content_4'    => 'إذا لم تفعل شيئًا فسيتم حذف إعلانك نهائيًا في:dateDel.',
    'post_will_be_deleted_content_5'    => 'شكرا لك على ثقتك ونراكم قريبا.',
    'post_will_be_deleted_content_6'    => 'ملاحظة: هذا بريد إلكتروني تلقائي ، من فضلك لا ترد.',


    // post_sent_by_email
    'post_sent_by_email_title'          => 'اقتراح جديد- :appName/:countryCode',
    'post_sent_by_email_content_1'      => 'أوصاك أحد المستخدمين برابط الوظيفة بعنوان البريد الإلكتروني: :senderEmail',
    'post_sent_by_email_content_2'      => 'انقر أدناه لمعرفة تفاصيل عرض العمل.',
    'Job URL'                           => 'رابط الوظيفة',


    // post_notification
    'post_notification_title'           => 'تم نشر وظيفة جديدة',
    'post_notification_content_1'       => 'مرحبًا مسؤل,',
    'post_notification_content_2'       => 'قام المستخدم: AdvertiserName بنشر وظيفة جديدة.',
    'post_notification_content_3'       => 'عنوان الإعلان: <a href=":postUrl">:title</a><br>Posted on: :now at :time',


    // user_notification
    'user_notification_title'         => 'تسجيل مستخدم جديد',
    'user_notification_content_1'     => 'مرحبًا مسؤل,',
    'user_notification_content_2'     => ':تم تسجيل الاسم للتو.',
    'user_notification_content_3'     => 'مسجل على: الآن في :time<br>Email: <a href="mailto::email">:email</a>',


    // payment_sent
    'payment_sent_title'              => 'شكرا على الدفع الخاص بك!',
    'payment_sent_content_1'          => 'مرحبًا,',
    'payment_sent_content_2'          => 'لقد تلقينا مدفوعاتك مقابل إعلان الوظيفة "<a href=":postUrl">:title</a>".',
    'payment_sent_content_3'          => 'شكرًا لك!',


    // payment_notification
    'payment_notification_title'      => 'تم إرسال دفعة جديدة',
    'payment_notification_content_1'  => 'مرحبًا Admin,',
    'payment_notification_content_2'  => 'المستخدم: AdvertiserName قد دفع للتو حزمة مقابل إعلان وظيفتها"<a href=":postUrl">:title</a>".',
    'payment_notification_content_3'  => 'تفاصيل الدفع
<br><strong>سبب الدفع:</strong> Ad #:adId - :packageName
<br><strong>Amount:</strong> :amount :currency
<br><strong>Payment Method:</strong> :paymentMethodName',

    // payment_approved (new)
    'payment_approved_title'     => 'تمت الموافقة على الدفع الخاص بك!',
    'payment_approved_content_1' => 'مرحبًا,',
    'payment_approved_content_2' => 'دفعتك للإعلان "<a href=":postUrl">:title</a>" has been approved.',
    'payment_approved_content_3' => 'شكرًا لك!',
    'payment_approved_content_4' => 'تفاصيل الدفع
<br><strong>سبب الدفع:</strong> Ad #:adId - :packageName
<br><strong>Amount:</strong> :amount :currency
<br><strong>Payment Method:</strong> :paymentMethodName',


    // reply_form
    'reply_form_title'                => ':موضوعات',
    'reply_form_content_1'            => 'مرحبًا,',
    'reply_form_content_2'            => '<strong>لقد تلقيت ردًا من: senderName. انظر الرسالة أدناه:</strong>',


    // generated_password
    'generated_password_title'            => 'كلمتك السرية',
    'generated_password_content_1'        => 'مرحبًا :اسم االمستخدم!',
    'generated_password_content_2'        => 'تم إنشاء حسابك.',
    'generated_password_verify_content_3' => 'انقر فوق الزر أدناه للتحقق من عنوان بريدك الإلكتروني.',
    'generated_password_verify_action'    => 'التحقق من عنوان البريد الإلكتروني',
    'generated_password_content_4'        => 'كلمة المرور الخاصة بك هي: <strong>:randomPassword</strong>',
    'generated_password_login_action'     => 'تسجيل الدخول الآن!',
    'generated_password_content_6'        => 'أنت تتلقى هذا البريد الإلكتروني لأنك قمت مؤخرًا بإنشاء ملف:appName حساب أو إضافة عنوان بريد إلكتروني جديد. إذا لم تكن أنت ، يرجى تجاهل هذا البريد الإلكتروني.',


];
