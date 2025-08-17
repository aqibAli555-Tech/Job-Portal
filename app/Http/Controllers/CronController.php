<?php

namespace App\Http\Controllers;

use App\Helpers\Date;
use App\Helpers\EmailHelper;
use App\Helpers\Helper;
use App\Helpers\MailGunHelper;
use App\Helpers\Subscription;
use App\Helpers\UrlGen;
use App\Helpers\Tap;
use App\Models\AffiliatesCommissionSlots;
use App\Models\Applicant;
use App\Models\AppliedEmails;
use App\Models\Company;
use App\Models\ContactCardsRemaining;
use App\Models\CvUploadLogs;
use App\Models\EmailQueue;
use App\Models\EmployeeSkill;
use App\Models\InterviewApplicantTrack;
use App\Models\OldPost;
use App\Models\OptionalSelectedEmails;
use App\Models\Package;
use App\Models\Pagelog;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Post;
use App\Models\PostDetails;
use App\Models\PostMeta;
use App\Models\PostRemaining;
use App\Models\CompanyPackages;
use App\Models\ReferralCommission;
use App\Models\Unlock;
use App\Models\User;
use App\Models\WithdrawRequest;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Imagick;
use PDO;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;
use App\Helpers\AWS;
use App\Helpers\Twilio;
use App\Models\AffiliateSetting;
use App\Models\TwilioLog;
use App\Models\UserSetting;
use App\Models\Payment as PaymentModel;

class CronController extends Controller
{
    public function send_email_queue()
    {

        $insert_email_log = new EmailQueue();
        $mytime = Carbon::now();
        $insert_email_log = $insert_email_log->where('status', 1)->limit(8)->get();
        if (!empty($insert_email_log)) {
            foreach ($insert_email_log as $object) {
                $email_qu['status'] = 3;
                EmailQueue::where('id', $object->id)->update($email_qu);
                MailGunHelper::send_mail_with_mailgun($object);
            }
        }
    }

    private function add_cv_logs()
    {
        $unlock_contact_counts = User::get_unlock_contact_counts();
        CvUploadLogs::create($unlock_contact_counts);
    }

    public function update_company_subscription()
    {
        $this->add_cv_logs();
        Subscription::update_subscription();
    }



    public function reminder()
    {
        $users = User::where('user_type_id', 1)->get();

        $array = [];
        foreach ($users as $user) {
            $package_details = CompanyPackages::where('is_package_expire', 0)->where('employer_id')->orderBy('id', 'desc')->first();
            $data['remaining_days'] = 0;

            if (!empty($package_details)) {

                $datetime1 = strtotime(date('Y-m-d'));
                $datetime2 = strtotime($package_details->package_expire_date);
                $secs = $datetime2 - $datetime1;
                $daysLeft = $secs / 86400;

                $data['remaning_days'] = $daysLeft;
                if ($daysLeft == 7) {
                    $cc = '';
                    if (OptionalSelectedEmails::check_selected_email(9, $user->id)) {
                        $cc = $user->optional_emails;
                    }

                    $company_email = $user->email;
                    $data['subject'] = 'Your Subscription Expires In One Week';
                    $data['url'] = url('account/upgrade');
                    $data['view'] = 'emails.reminder_email';
                    $data['email'] = $company_email;
                    $data['header'] = 'Subscription Reminder';
                    $data['cc'] = $cc;
                    $helper = new Helper();
                    $helper->send_email($data);
                }
            }

        }

    }

    public function delete_page_logs()
    {
        pagelog::delete_log_older_by_limit();
    }

    // public function backup()
    // {

    //     $databaseName = Config::get('database.connections.mysql.database');
    //     $databaseUsername = Config::get('database.connections.mysql.username');
    //     $databasePassword = Config::get('database.connections.mysql.password');

    //     // Connect to the database
    //     $host = 'localhost';
    //     $dbName = $databaseName;
    //     $user = $databaseUsername;
    //     $password = $databasePassword;

    //     $dsn = "mysql:host=$host;dbname=$dbName;charset=utf8mb4";

    //     $options = [
    //         PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    //         PDO::ATTR_EMULATE_PREPARES => false,
    //     ];

    //     try {
    //         $pdo = new PDO($dsn, $user, $password, $options);
    //     } catch (PDOException $e) {
    //         die('Database connection failed: ' . $e->getMessage());
    //     }

    //     // Tables to backup
    //     $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

    //     // Backup file name and path
    //     $backupFile = 'database_backup_' . '.sql';
    //     if (file_exists($backupFile)) {
    //         // Delete the existing backup file
    //         unlink($backupFile);
    //     }
    //     $backupPath = storage_path('backup/' . $backupFile);

    //     // Fetch and write data to backup file
    //     try {
    //         $handle = fopen($backupPath, 'w');
    //         foreach ($tables as $table) {
    //             $stmt = $pdo->query("SELECT * FROM $table");
    //             while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    //                 $insertValues = implode("','", array_values($row));
    //                 fwrite($handle, "INSERT INTO $table VALUES ('$insertValues');\n");
    //             }
    //         }
    //         fclose($handle);
    //         echo 'Database backup created successfully.';
    //     } catch (PDOException $e) {
    //         die('Database backup failed: ' . $e->getMessage());
    //     }
    // }

    // public function backuploads()
    // {
    //     $uploadFolder = storage_path('app/public/');
    //     $backupFile = 'upload_backup_' . '.zip';
    //     if (file_exists($backupFile)) {
    //         // Delete the existing backup file
    //         unlink($backupFile);
    //     }
    //     $backupPath = storage_path('app/public/' . $backupFile);

    //     $zip = new ZipArchive();
    //     if ($zip->open($backupPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
    //         $files = new RecursiveIteratorIterator(
    //             new RecursiveDirectoryIterator($uploadFolder),
    //             RecursiveIteratorIterator::LEAVES_ONLY
    //         );

    //         foreach ($files as $name => $file) {
    //             if (!$file->isDir()) {
    //                 $filePath = $file->getRealPath();
    //                 $relativePath = substr($filePath, strlen($uploadFolder));
    //                 $zip->addFile($filePath, $relativePath);
    //             }
    //         }

    //         $zip->close();
    //         echo 'Upload folder backup created successfully.';
    //     } else {
    //         die('Upload folder backup failed.');
    //     }
    // }

    public function delete_archived_jobs_applicants()
    {
        $currentDate = Carbon::now();
        $futureDate = $currentDate->subMonths(3);
        $formattedDate = $futureDate->format('Y-m-d');
        $all_archived_post = POST::where('archived', 1)->where('is_deleted', 0)->where('archived_at', '<=', $formattedDate)->get();

        if (!empty($all_archived_post)) {
            foreach ($all_archived_post as $key => $postObj) {
                $post = POST::find($postObj->id);
                $post->is_deleted = 1;
                if ($post->update()) {
                    $post_details = POST::find($postObj->id);
                    $description = "Archived Post <a href='" . url("admin/get_posts?employyeskill=" . $post_details->category_id . "&search=" . $post_details->title) . "'>" . $post_details->title . "</a> is Deleted ";
                    Helper::activity_log($description);
                }

                $applicants = Applicant::where('post_id', $postObj->id)->where('is_deleted', 0)->get();
                if (!empty($applicants)) {
                    foreach ($applicants as $applicantObj) {
                        $applicant_data = Applicant::find($applicantObj->id);
                        $applicant_data->timestamps = false;
                        $applicant_data->is_deleted = 1;
                        $applicant_data->save();
                    }
                }
            }
        }
    }

    public function update_user_company_applicant_post_emails()
    {
        $this->updateEmails(User::where('is_admin', '!=', 1)->get());
        $this->updateEmails(Post::all());
        $this->updateEmails(Company::all());
        $this->updateEmails(Applicant::all());
    }

    private function updateEmails($items)
    {
        foreach ($items as $item) {
            $item->email = str_ends_with($item->email, '.com') ? str_replace('.com', '.com1', $item->email) : $item->email;
            $item->save();
        }
    }


    public function create_user_thumbnail()
    {
        $user_type_id = [1];
        $employer = User::whereIn('user_type_id', $user_type_id)->get();

        if (!empty($employer)) {
            foreach ($employer as $key => $value) {
                if (empty($value->thumbnail)) {

                    $destinationPath1 = public_path('/') . 'storage/pictures/kw/' . $value->id;
                    $destinationPath2 = public_path('/') . 'storage/' . $value->file;
                    if (file_exists($destinationPath1)) {

                        if (file_exists($destinationPath2)) {

                            $url = url('public/storage/' . $value->file);
                            $unique = 'thumbnail_' . uniqid() . '.jpg';

                            $im = new Imagick($url);
                            $imageprops = $im->getImageGeometry();
                            $width = $imageprops['width'];
                            $height = $imageprops['height'];
                            if ($width > $height) {
                                $newHeight = 80;
                                $newWidth = (80 / $height) * $width;
                            } else {
                                $newWidth = 80;
                                $newHeight = (80 / $width) * $height;
                            }

                            $im->resizeImage($newWidth, $newHeight, Imagick::FILTER_LANCZOS, 0.9, true);
                            $im->cropImage(80, 80, 0, 0);
                            $im->writeImage(public_path('/') . 'storage/pictures/kw/' . $value->id . '/' . $unique);

                            $values = array(
                                'thumbnail' => 'pictures/kw/' . $value->id . '/' . $unique,
                                'is_image_uploaded_on_aws' => 0,
                            );
                            User::where('id', $value->id)->update($values);
                            $values2 = array(
                                'thumbnail' => 'pictures/kw/' . $value->id . '/' . $unique,
                                'logo' => $value->id,
                                'is_image_uploaded_on_aws' => 0,
                            );
                            if(in_array(1, $user_type_id)){
                                Company::where('c_id', $value->id)->update($values2);
                            }
                            echo "thumbnail Created";
                        }
                    }
                }
            }
        }
    }

    public function create_company_thumbnail()
    {
        $companies = Company::whereNull('thumbnail')->get();
        if (!empty($companies)) {
            foreach ($companies as $key => $company) {
                if (!empty($company->user_id)) {
                    $user = User::where('id', $company->user_id)->whereNotNull('thumbnail')->first();
                    if ($user) {
                        $company->thumbnail = $user->thumbnail;
                        $company->save();
                    }
                }
            }
        }
    }

    public function copy_employer_images()
    {
        $employer = User::where('user_type_id', 1)->get();
        if (!empty($employer)) {
            foreach ($employer as $key => $value) {
                if (empty($value->thumbnail)) {
                    $destinationPath1 = public_path('/') . 'storage/pictures/kw/' . $value->id;
                    $destinationPath2 = public_path('/') . 'storage/' . $value->file;
                    if (file_exists($destinationPath1)) {
                        if (file_exists($destinationPath2)) {
                            $uniqeid = 'pictures/kw/' . $value->id . '/profile_' . uniqid() . '.jpg';
                            $sourceFilePath = public_path('/') . 'storage/' . $value->file;
                            $destinationFilePath = public_path('/') . 'storage/' . $uniqeid;

                            if (copy($sourceFilePath, $destinationFilePath)) {
                                echo 'File copied successfully.';
                                $values = array(
                                    'file' => $uniqeid,
                                );
                                User::where('id', $value->id)->update($values);
                            } else {
                                echo 'Failed to copy the file.';
                            }
                        }
                    }
                }
            }
        }
    }

    public function delete_extra_file_from_storage()
    {
        $employer = User::all();
        if (!empty($employer)) {
            foreach ($employer as $key => $value) {
                $folder = 'public/storage/pictures/kw/' . $value->id;
                dd(storage_path($folder));
                $imagesInFolder = Storage::files(storage_path($folder));
                dd($imagesInFolder);
                $imagesInDatabase = User::pluck('file')->toArray();
                $imagesToDelete = array_diff($imagesInFolder, $imagesInDatabase);
                foreach ($imagesToDelete as $imagePath) {
                    dd($imagePath);
                    $imageFullPath = storage_path('app/' . $imagePath);
                    if (file_exists($imageFullPath)) {
                        unlink($imageFullPath);
                    }
                }
            }
        }
    }

    public function delete_archive_applicant()
    {
        $applicants = Applicant::where('post_id', 30)->get();
        if (!empty($applicants)) {
            foreach ($applicants as $applicantObj) {
                $applicant_data = Applicant::find($applicantObj->id);
                $applicant_data->is_deleted = 1;
                $applicant_data->save();
            }
        }
    }

    public function update_company_subscriptions()
    {
        $all_packages = PostRemaining::where('is_post_expire', 0)
            ->groupBy('employer_id', 'package_id')
            ->get();
        foreach ($all_packages as $value) {

            $package = Package::find($value->package_id);

            $remaining_post = PostRemaining::where('employer_id', $value->employer_id)->where('package_id', $value->package_id)->where('is_post_expire', 0)->whereNull('post_id')->count();

            $remaining_credits = ContactCardsRemaining::where('employer_id', $value->employer_id)->where('package_id', $value->package_id)->where('is_package_expire', 0)->whereNull('employee_id')->count();

            $package_company = new CompanyPackages();
            $package_company->employer_id = $value->employer_id;
            $package_company->package_id = $value->package_id;
            $package_company->remaining_post = $remaining_post;
            $package_company->remaining_credits = $remaining_credits;
            $package_company->remaining_credits = $remaining_credits;
            $package_company->is_package_expire = $value->is_post_expire;
            $package_company->package_expire_date = $value->post_expire_date_time;
            $package_company->total_credits = $package->number_of_cards;
            $package_company->total_post = $package->number_of_posts;
            $package_company->start_date = $value->created_at;
            $package_company->save();

        }
    }

    public function update_subscription_id()
    {

        $posts = PostRemaining::where('is_post_expire', 0)
            ->get();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $currentDate = Carbon::parse($post->created_at);

                // Format the current date and time
                $today = $currentDate->format('Y-m-d H:i:s');

                // Add 30 days to the current date and time
                $newDate = $currentDate->addDays(30);

                // Format the new date and time, which is 30 days later
                $expire_date = $newDate->format('Y-m-d H:i:s');
                $post->post_expire_date_time = $expire_date;

                $post->save();
            }
        }
        die('done');
        // $all_packages = CompanyPackages::where('is_package_expire', 0)
        //     ->groupBy('employer_id')
        //     ->get();
        // foreach ($all_packages as $value) {
        //     $posts = PostRemaining::where('employer_id', $value->employer_id)
        //         ->where('package_id', $value->package_id)
        //         ->get();

        //     foreach ($posts as $post) {
        //         $post->company_package_id = $value->id;
        //         $post->save();
        //     }

        //     $contacts = ContactCardsRemaining::where('employer_id', $value->employer_id)
        //         ->where('package_id', $value->package_id)
        //         ->get();

        //     foreach ($contacts as $contact) {
        //         $contact->company_package_id = $value->id;
        //         $contact->save();
        //     }
        // }
    }

    public function delete_null_record()
    {
        dd('not allow');
        $all_packages = CompanyPackages::where('is_package_expire', 0)
            ->groupBy('employer_id')
            ->get();
        foreach ($all_packages as $value) {
            $posts = PostRemaining::where('employer_id', $value->employer_id)
                ->where('package_id', $value->package_id)->whereNull('post_id')
                ->get();

            foreach ($posts as $post) {
                $post->delete();
            }

            $contacts = ContactCardsRemaining::where('employer_id', $value->employer_id)
                ->where('package_id', $value->package_id)->whereNull('employee_id')
                ->get();
            foreach ($contacts as $contact) {
                $contact->delete();
            }
        }
    }

    public function send_email_to_company_for_interviewer_applicants()
    {

        $applicants = Applicant::get_applicants_that_have_in_interview_state_from_2_weeks();

        if (!empty($applicants->isNotEmpty())) {
            $applicantsByCompany = [];
            foreach ($applicants as $applicant) {
                $add_in_track['applicant_id'] = $applicant->id;
                InterviewApplicantTrack::create($add_in_track);
                $companyEmail = $applicant->companyData->email;
                $companyName = $applicant->companyData->name;
                $companyuserid = $applicant->companyData->c_id;

                $companyKey = $companyEmail;

                if (!isset($applicantsByCompany[$companyKey])) {
                    // Store company email and name as separate objects
                    $applicantsByCompany[$companyKey] = [
                        'email' => $companyEmail,
                        'name' => $companyName,
                        'user_id' => $companyuserid,
                        'applicants' => [],
                    ];
                }
                $applicantsByCompany[$companyKey]['applicants'][] = $applicant;
            }

            // Process applicants for each company
            foreach ($applicantsByCompany as $companyKey => $companyData) {
                $companyEmail = $companyData['email'];
                $companyName = $companyData['name'];
                $user_id = $companyData['user_id'];
                $usercomnpanydata = User::withoutGlobalScopes()->where('id', $user_id)->first();

                $cc = '';
                if (OptionalSelectedEmails::check_selected_email(4, $usercomnpanydata->id)) {
                    $cc = $usercomnpanydata->optional_emails;
                }

                $applicantsForCompany = $companyData['applicants'];

                $userNames = [];
                foreach ($applicantsForCompany as $applicant) {
                    $userNames[] = $applicant->User->name;
                }
                $userNamesString = implode(', ', $userNames);

                // Send email to the company
                $data['email'] = $companyEmail;
                $data['company_name'] = $companyName;
                $data['subject'] = '💼 Applicants In Interview Status';
                $data['from'] = getenv('MAIL_USERNAME');
                $data['user_names'] = $userNamesString;
                $data['cc'] = $cc;

                $data['header'] = '💼 Applicants In Interview Status';
                $data['view'] = 'emails/interview_notify_email';
                $helper = new Helper();
                $response = $helper->send_email($data);
            }
        }

        $this->send_email_to_company_from_track_interview();
    }

    public function send_email_to_company_from_track_interview()
    {
        $applicants = InterviewApplicantTrack::get_applicants_on_the_date_bases();

        if (!empty($applicants->isNotEmpty())) {
            $applicantsByCompany = [];
            foreach ($applicants as $applicant) {
                if (!empty($applicant->Applicant->companyData)) {
                    $companyEmail = $applicant->Applicant->companyData->email;
                    $companyName = $applicant->Applicant->companyData->name;
                    $companyuserid = $applicant->Applicant->companyData->c_id;
                    $companyKey = $companyEmail;
                    if (!isset($applicantsByCompany[$companyKey])) {
                        $applicantsByCompany[$companyKey] = [
                            'email' => $companyEmail,
                            'name' => $companyName,
                            'userid' => $companyuserid,
                            'applicants' => [],
                        ];
                    }
                    $applicantsByCompany[$companyKey]['applicants'][] = $applicant->Applicant;
                }
            }

            foreach ($applicantsByCompany as $companyKey => $companyData) {
                $companyEmail = $companyData['email'];
                $companyName = $companyData['name'];
                $company_user_id = $companyData['userid'];
                $applicantsForCompany = $companyData['applicants'];

                $userNames = [];
                foreach ($applicantsForCompany as $applicant) {
                    if ($applicant->status == 'interview' && $applicant->is_deleted == 0) {
                        $userNames[] = $applicant->User->name;
                        $update_track['updated_at'] = date('Y-m-d H:i:s');
                        InterviewApplicantTrack::where('applicant_id', $applicant->id)->update($update_track);
                    } else {
                        InterviewApplicantTrack::where('applicant_id', $applicant->id)->delete();
                    }
                }
                $userNamesString = implode(', ', $userNames);

                $usercomnpanydata = User::withoutGlobalScopes()->where('id', $company_user_id)->first();
                $cc = '';
                if (OptionalSelectedEmails::check_selected_email(4, $usercomnpanydata->id)) {
                    $cc = $usercomnpanydata->optional_emails;
                }

                // Send email to the company
                $data['email'] = $companyEmail;
                $data['company_name'] = $companyName;
                $data['subject'] = '💼 Applicants In Interview Status';
                $data['from'] = getenv('MAIL_USERNAME');
                $data['user_names'] = $userNamesString;
                $data['cc'] = $cc;

                $data['header'] = '💼 Applicants In Interview Status';
                $data['view'] = 'emails/interview_notify_email';
                $helper = new Helper();
                $response = $helper->send_email($data);
            }
        }
    }

    public function generateSitemap()
    {
        $changefreq = 'daily';
        $priority = 0.8;
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        $urls = [
            ['loc' => '/'],
            // ['loc' => 'page/faq'],
            // ['loc' => 'page/terms'],
            // ['loc' => 'page/privacy'],
            // ['loc' => 'contact'],
            ['loc' => 'companies'],
            // ['loc' => 'sitemap'],
            // ['loc' => 'countries'],
            // ['loc' => 'register?user_type_id=1'],
            // ['loc' => 'register?user_type_id=2'],
            ['loc' => htmlspecialchars('search-resumes?cat=&country=&city=&keyword=&limit=&offset=0&send=&nationality=&sort=', ENT_XML1)],
            ['loc' => htmlspecialchars('latest-jobs?post=&country_code=&q=&l=&min_salary=&max_salary=&type=', ENT_XML1)],
        ];

        foreach ($urls as $url) {
            $sitemap .= "<url>
            <loc>" . url($url['loc']) . "</loc>
            <changefreq>{$changefreq}</changefreq>
            <priority>{$priority}</priority>
            </url>";
        }

        // $all_posts = Post::get_active_post();
        // foreach ($all_posts as $post) {
        //     $sitemap .= "<url>
        //     <loc>" . UrlGen::post($post) . "</loc>
        //     <changefreq>{$changefreq}</changefreq>
        //     <priority>{$priority}</priority>
        //     </url>";
        // }


        // $all_campnies = Company::get();
        // foreach ($all_campnies as $campny) {
        //     $sitemap .= "<url>
        //     <loc>" . UrlGen::company(null, $campny->id) . "</loc>
        //     <changefreq>{$changefreq}</changefreq>
        //     <priority>{$priority}</priority>
        //     </url>";
        // }

        // $post_skills = EmployeeSkill::getAllskill();
        // foreach ($post_skills as $post_skill) {
        //     $params = [
        //         'post' => '',
        //         'country_code' => '',
        //         'q' => $post_skill->id,
        //         'l' => '',
        //         'min_salary' => '',
        //         'max_salary' => '',
        //         'type' => ''
        //     ];
        //     $query_string = http_build_query($params);
        //     $url = url('latest-jobs?' . $query_string);
        //     $xml_url = htmlspecialchars($url, ENT_XML1);

        //     $sitemap .= "<url>
        //         <loc>$xml_url</loc>
        //         <changefreq>{$changefreq}</changefreq>
        //         <priority>{$priority}</priority>
        //     </url>";
        // }

        $sitemap .= '</urlset>';
        File::put('sitemap.xml', $sitemap);
    }

    public function send_applied_emails()
    {
        $emails = AppliedEmails::get_applied_emails();

        if (!empty($emails['groupedResults'])) {

            foreach ($emails['groupedResults'] as $company_id => $posts) {

                $text = '';
                foreach ($posts as $post) {
                    $post_data = Post::findOrFail($post['post_id']);
                    $user_ids = $post['user_ids'];

                    $applicants = Applicant::where('to_user_id', $company_id)
                    ->where('post_id', $post['post_id'])
                    ->whereIn('user_id', $user_ids)
                    ->where('status', 'applied')
                    ->get();

                    $accurateCount = $applicants->where('skill_accuracy', 'Accurate')->count();
                    $veryAccurateCount = $applicants->where('skill_accuracy', 'Very Accurate')->count();
                    $notAccurateCount = $applicants->where('skill_accuracy', 'Not Accurate')->count();

                    $text .= '<p>' . $applicants->count() . ' employees (job seekers) applied to your ' . $post_data->title . ' job post today </p>';

                    if ($accurateCount > 0) {
                        $text .= '<span style="display: inline-block; background-color: #f0ad4e; color: #fff; padding: 4px 10px; margin: 0 5px 5px 0; font-size: 12px; border-radius: 4px;">Accurate (' . $accurateCount . ')</span>';
                    }
                    if ($veryAccurateCount > 0) {
                        $text .= '<span style="display: inline-block; background-color: #5cb85c; color: #fff; padding: 4px 10px; margin: 0 5px 5px 0; font-size: 12px; border-radius: 4px;">Very Accurate (' . $veryAccurateCount . ')</span>';
                    }
                    if ($notAccurateCount > 0) {
                        $text .= '<span style="display: inline-block; background-color: #d9534f; color: #fff; padding: 4px 10px; margin: 0 5px 5px 0; font-size: 12px; border-radius: 4px;">Not Accurate (' . $notAccurateCount . ')</span>';
                    }

                    $text .= '</br>';
                }
                $user_data = User::get_user_by_id($company_id);
                $cc = '';

                if (OptionalSelectedEmails::check_selected_email(1, $company_id)) {
                    $cc = $user_data->optional_emails;
                }

                EmailHelper::sendEmployerContactedemail($user_data, $text, $cc);
            }
        }
        AppliedEmails::where('is_email_sent', 0)->update(['is_email_sent' => 1]);
    }

    public function regenrate_thumbnails()
    {
        dd('not allow');
        $user_data = User::where('id', '!=', 1)->where('new_thumbnail', 0)->limit(20)->get();
        if (!empty($user_data)) {
            foreach ($user_data as $user) {
                if (!empty($user->thumbnail)) {
                    $remoteFileUrl_thumbnail = 'public/storage/' . $user->thumbnail;
                    if (is_file($remoteFileUrl_thumbnail) && file_exists($remoteFileUrl_thumbnail)) {
                        unlink($remoteFileUrl_thumbnail);
                    }
                }
                $url = url('public/storage/' . $user->file);
                $unique = 'thumbnail_' . uniqid() . '.jpg';
                Helper::generate_thumbnail($url, $user->id, $unique);
                $values = array(
                    'thumbnail' => 'pictures/kw/' . $user->id . '/' . $unique,
                    'new_thumbnail' => 1,
                    'is_image_uploaded_on_aws' => 0,
                );
                User::where('id', $user->id)->update($values);
            }
        }
    }

    public function delete_old_open_from_search_cv_applicants()
    {
        dd('123');
        $date = '2024-11-01';
        $old_applicants = Applicant::where('updated_at', '<', $date)->where('contact_unlock', 1)->where('is_deleted', 0)->get();

        foreach ($old_applicants as $old_applicant) {
            if (!empty($old_applicant)) {
                $old_applicant->is_deleted = 1;
                $old_applicant->save();
            }
        }

        $startDate = '2023-01-01';
        $endDate = '2024-06-01';
        $applicants = Applicant::whereBetween('updated_at', [$startDate, $endDate])
            ->where('status', 'interview')
            ->update(['status' => 'rejected', 'rejected_reason_id' => '3']);
    }
    
    public function add_water_mark(){
        dd('not allow');
        // $last_id = session()->get('last_processed_id', 0);
    
        // dd($last_id);
        // $userData = User::getCronApprovalCv(5, 0);
        $userData = User::where('id',117)->get();
        // dd($userData);
        // dd($userData);
        if (!empty($userData)) {
            foreach ($userData as $user) {
                $file = $user->cv_no_contact;
                dd($file);
                $filePath = 'storage/' . $file;
                $destinationPath = public_path($filePath);
                $outFolder = public_path('storage/');
                // dd($outFolder);
                $new_file_name = 'employee_cv/' . $user->id.'/no_contact_cv.pdf';
                // dd($new_file_name);
                $outputFilePath = $outFolder.''.$new_file_name;
                // dd($outputFilePath);
                Helper::fillPDFFile($file, $outputFilePath);
                // dd('1');
                $values = array(
                    'cv_no_contact' => $new_file_name,
                    'is_approved_no_contact_cv' => 5,
                    'cv_no_contact_rejected_reason' => null,
                );

                User::where('id', $user->id)->update($values);
                $last_id = $user->id;
                // session()->put('last_processed_id', $last_id);
                echo "Fill Proccessed: ".$last_id."<br>";
            }
            // die('Done');
            echo "<script>window.location.reload();</script>";
        }else{
            echo "App Fill Proccessed";
        }
        
    }
    
    
    public function add_water_mark_to_original(){
        dd('not allow');
        $last_id = session()->get('last_original_processed_id', 0);
        // dd($last_id);
        $userData = User::getCronOriginalCv(5, $last_id);
        // dd($userData);
        if (!empty($userData)) {
            foreach ($userData as $user) {
                $file = $user->employee_cv;
                // dd($file);
                $filePath = 'storage/' . $file;
                $destinationPath = public_path($filePath);
                $outFolder = public_path('storage/');
                // dd($outFolder);
                $new_file_name = 'employee_cv/' . $user->id.'/original_cv.pdf';
                // dd($new_file_name);
                $outputFilePath = $outFolder.''.$new_file_name;
                dd($outputFilePath);
                Helper::fillPDFFile($file, $outputFilePath);
                dd('1');
                $values = array(
                    'employee_cv' => $new_file_name,
                );

                User::where('id', $user->id)->update($values);
                $last_id = $user->id;
                session()->put('last_original_processed_id', $last_id);
                echo "Fill Proccessed: ".$last_id."<br>";
            }
            die('Done');
            echo "<script>window.location.reload();</script>";
        }else{
            echo "App Fill Proccessed";
        }
        
    }

    public function commission_cal()
    {
        $startDate = now()->subMonth()->startOfMonth()->toDateString();
        $endDate = now()->subMonth()->endOfMonth()->toDateString();
        $monthName = now()->subMonth()->format('F');
        $year = now()->subMonth()->format('Y');
        $feePercentage = 2;

        $affiliates = User::where('user_type_id',5)->where('is_active', 1)->select('id')->get();

        foreach($affiliates as $affiliate){
            $amount = 0;
            $commission = 0;
            $referral_affiliate_commission = 0;
            $referral_affiliate_total_revenue = 0;
            $commission_amount = 0;
            $commissionAmountAfterApplyFee = 0;
            $total_revenue = 0;
            $applyFeeAmount = 0;
            $commission_slot = json_encode([]);
            $referral_affiliate_commission_value = json_encode([]);
            
            $referral_users = User::where('user_type_id', 1)->where('affiliate_id', $affiliate->id)->where('is_active', 1)->select('id')->get();

            if ($referral_users->isNotEmpty()) {
                foreach($referral_users as $referral_user){
                    $totalPayments = Payment::where('user_id', $referral_user->id)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->sum('amount');
                    $amount += $totalPayments;
                }

                if ($amount > 0) {
                    $total_revenue = $amount; 
                    $amount = (int) $amount;

                    $slot = AffiliatesCommissionSlots::where('affiliate_id', $affiliate->id)
                            ->where('min_amount', '<=', $amount)
                            ->where('max_amount', '>=', $amount)
                            ->first();

                    $commission = $slot ? $slot->commission : 0;
                    $commission_amount = $amount * ($commission / 100);
                    $commission_slot = $slot ? json_encode($slot) : json_encode([]);
                    $applyFeeAmount = $commission_amount * $feePercentage / 100;
                    $commissionAmountAfterApplyFee = $commission_amount - $applyFeeAmount;
                }
            }
            
            $referral_affiliates = User::where('user_type_id', 5)->where('affiliate_id', $affiliate->id)->where('is_active', 1)->select('id')->get();
            
            foreach($referral_affiliates as $referral_affiliate){
                $referral_affiliate_companies = User::where('user_type_id', 1)->where('affiliate_id', $referral_affiliate->id)->where('is_active', 1)->select('id')->get();
                
                if ($referral_affiliate_companies->isEmpty()) {
                    continue;
                }

                foreach($referral_affiliate_companies as $referral_affiliate_company){
                    $totalAffiliateReferralPayments = Payment::where('user_id', $referral_affiliate_company->id)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->sum('amount');
                    $referral_affiliate_total_revenue += $totalAffiliateReferralPayments;
                }
                if ($referral_affiliate_total_revenue > 0) {
                    $affiliate_setting = AffiliateSetting::first();
                    if ($affiliate_setting) {
                        $referral_affiliate_commission_value = json_encode([
                            'type' => $affiliate_setting->affiliate_to_affiliate_commission_type,
                            'value' => $affiliate_setting->affiliate_to_affiliate_commission_value,
                        ]);

                        if ($affiliate_setting->affiliate_to_affiliate_commission_type == 'percentage') {
                            $commission_value = $affiliate_setting->affiliate_to_affiliate_commission_value;
                            $referral_affiliate_commission += $referral_affiliate_total_revenue * ($commission_value / 100);
                        } else {
                            $referral_affiliate_commission += $affiliate_setting->affiliate_to_affiliate_commission_value;
                        }
                    }
                }
                
            }

            if ($commission_amount <= 0 && $referral_affiliate_commission <= 0) {
                continue;
            }

            $existingRecord = ReferralCommission::where('affiliate_id', $affiliate->id)
            ->where('month', $monthName)
            ->where('year', $year)
            ->first();            

            if ($existingRecord) {
                $existingRecord->update([
                    'my_revenue' => $total_revenue,
                    'my_commission' => $commission_amount,
                    'total_revenue' => $total_revenue + $referral_affiliate_total_revenue,
                    'commission_slot' => $commission_slot,
                    'commission_after_apply_fee'  => $commissionAmountAfterApplyFee,
                    'apply_fee_type' => $feePercentage,
                    'apply_fee_amount' => $applyFeeAmount,
                    'total_commission' => $commission_amount + $referral_affiliate_commission,
                    'commission_through_affiliated_user' => $referral_affiliate_commission,
                    'revenue_through_affiliated_user' => $referral_affiliate_total_revenue,
                    'referral_affiliate_commission_value' => $referral_affiliate_commission_value,
                    'status' => 'pending',
                    'updated_at' => now(),
                ]);
            } else {
                ReferralCommission::create([
                    'affiliate_id' => $affiliate->id,
                    'month' => $monthName,
                    'year' => $year,
                    'my_revenue' => $total_revenue,
                    'my_commission' => $commission_amount,
                    'total_revenue' => $total_revenue + $referral_affiliate_total_revenue,
                    'commission_slot' => $commission_slot,
                    'commission_after_apply_fee'  => $commissionAmountAfterApplyFee,
                    'apply_fee_type' => $feePercentage,
                    'apply_fee_amount' => $applyFeeAmount,
                    'total_commission' => $commission_amount + $referral_affiliate_commission,
                    'commission_through_affiliated_user' => $referral_affiliate_commission,
                    'revenue_through_affiliated_user' => $referral_affiliate_total_revenue,
                    'referral_affiliate_commission_value' => $referral_affiliate_commission_value,
                    'status' => 'pending',
                ]);
                $affiliate = User::find($affiliate->id);
                $withdraw_date = Carbon::parse("1 $monthName $year")->addMonths(2)->startOfMonth()->format('F j, Y');
                $email_data = [
                    'name' => $affiliate->name,
                    'email' => $affiliate->email,
                    'month' => $monthName,
                    'year' => $year,
                    'withdraw_date' => $withdraw_date,
                    'commission' => $commission_amount + $referral_affiliate_commission,
                ];
                EmailHelper::sendEmailToAffiliateForCommissionCalculate($email_data);
            }
        }

    }
    public function expire_withdraw_requests()
    {
        $withdrawRequests = WithdrawRequest::where('expiry_time', '<', Carbon::now())
        ->where('status', 'verification_inprocess')
        ->get();
        foreach($withdrawRequests as $withdrawRequest){
            $withdrawRequest->update([
                'status' => 'rejected',
                'rejected_reason' => 'Unable to verify email link',
            ]);
            if($withdrawRequest->referral_commission_id){
                $referralCommission = ReferralCommission::find($withdrawRequest->referral_commission_id);
                if ($referralCommission) {
                    $referralCommission->update([
                        'status' => 'pending',
                    ]);
                }
            } 
        }
    }
    
    function getFlagEmoji($countryCode) {
        $offset = 127397;
        $emoji = '';
        $codePoints = mb_str_split(strtoupper($countryCode));
        foreach ($codePoints as $char) {
            $emoji .= mb_convert_encoding('&#' . (ord($char) + $offset) . ';', 'UTF-8', 'HTML-ENTITIES');
        }
        return $emoji;
    }
    
    public function send_whatsapp_message()
    {
        if(IS_WHATSAPP_ALLOWED){
            $userSettings = UserSetting::with('user')
            ->whereNotNull('whatsapp_number')
            ->where('is_verified', 1)
            ->where('updated_at', '<', Carbon::now()->subHours(24))
            ->get();
            foreach($userSettings as $userSetting){
                $user_setting_skills = $userSetting->skills_set ? explode(',', $userSetting->skills_set) : [];
                $user_skills = $userSetting->user->skill_set ? explode(',', $userSetting->user->skill_set) : [];
                $mergedSkills = collect(array_merge($user_skills, $user_setting_skills))
                ->unique()
                ->values()
                ->all();
                
                $employeeSkills = EmployeeSkill::whereIn('skill', $mergedSkills)->pluck('id');
                $posts = Post::where('is_deleted', 0)
                ->where('is_active', 1)
                ->where('archived', 0)
                ->where('is_post_expire', 0)
                ->where('created_at', '<', Carbon::now()->subHours(24))
                ->where(function ($query) use ($mergedSkills, $employeeSkills) {
                    $query->where(function ($q) use ($mergedSkills) {
                        // Skill match inside post details
                        $q->whereHas('postDetail', function ($subQuery) use ($mergedSkills) {
                            $subQuery->where(function ($innerQuery) use ($mergedSkills) {
                                foreach ($mergedSkills as $skill) {
                                    $innerQuery->orWhereRaw("FIND_IN_SET(?, skills_set)", [$skill]);
                                }
                            });
                        });
                    })
                    ->orWhereIn('category_id', $employeeSkills); // category_id from posts table
                })
                ->get();

                $alreadySent = TwilioLog::where('user_id', $userSetting->user_id)
                    ->where('type', 'New Job Post Alert')
                    ->where('is_sent', 1)
                    ->whereYear('created_at', now()->year)
                    ->whereMonth('created_at', now()->month)
                    ->exists();

                if ($alreadySent) {
                    continue;
                }
                
                $message = '';
                $post_ids = [];
                if($posts->isNotEmpty()){
                    foreach($posts as $post){
                        $link = UrlGen::post($post);
                        $country = $post->country->name;
                        $post_ids[] = $post->id;
                        // dd($post->country->code);
                        $flagEmoji = $this->getFlagEmoji($post->country->code);
                        // dd($flagEmoji);
                        if ($post->postDetail->who_can_apply == 1) {
                            $hiringType = "Local Hire Only $country $flagEmoji";
                        } elseif ($post->postDetail->who_can_apply == 2) {
                            $hiringType = "International Hire Only ðŸŒŽ";
                        } else {
                            $hiringType = "Both Local $country $flagEmoji and International ðŸŒŽ";
                        }
        
                        $message .= "Hungry For Jobs has a new job opportunity that matches your skills sets! \n"
                        . "Country: $country  $flagEmoji\n"
                        . "Type of Hiring: $hiringType \n"
                        . "Click here to apply: $link \n\n";
                    }
                    
                    $post_ids = implode(',', $post_ids);
        
                    $twilio_log = new TwilioLog();
                    $twilio_log->user_id = $userSetting->user_id;
                    $twilio_log->post_ids = $post_ids;
                    $twilio_log->number = $userSetting->whatsapp_number;
                    $twilio_log->type = 'New Job Post Alert';
                    $twilio_log->message = $message;
                    $twilio_log->is_sent = 0;
                    $twilio_log->save();
        
                    $twilio = new Twilio();
                    $to = 'whatsapp:' . $userSetting->whatsapp_number;
                    $messagecheck = $twilio->sendSMS($to,$message);
                    if($messagecheck['status']){
                        $twilio_log->is_sent = 1;
                        $twilio_log->response = $messagecheck['message'];
                        $userSetting->update(['updated_at' => now()]);
                    }else{
                        $twilio_log->is_sent = 0;
                        $twilio_log->response = $messagecheck['message'];
                    }
                
                    $twilio_log->save();
                }
            }
        }
        // echo "Send whatsapp message about new job posts to users skill set match.";
    }

    public function email_affiliate_on_package_previous_purchase(){
        die('Ended');
        $email = 'elkmatia@gmail.com';
        $referral_user = User::where('email',$email)->first();
        $affiliated_company = User::where('affiliate_id', $referral_user->id)->first();

        $latestPayment = PaymentModel::where('user_id', $affiliated_company->id)->latest()->first();

        $affiliate_setting = AffiliateSetting::first();
        if ($affiliate_setting) {
            if ($affiliate_setting->package_discount_type == 'percentage') {
                $discount = $affiliate_setting->package_discount_value . '%';
            } else {
                $discount = '$' . number_format($affiliate_setting->package_discount_value, 2);
            }
        }
        
        $email_data = [
            'referral_by' => $referral_user,
            'company_name' => $affiliated_company->name,
            'package_discount' => $discount,
            'package' => [
                'name' => $latestPayment->package->name,
                'after_discount' => $latestPayment->amount,
            ],
        ];
        EmailHelper::sendEmailToReffererForPackageBuy($email_data);
    }

    public function renew_company_package()
    {
        die('Not allowed');
        $id = 12142;
        $user = User::find($id);
        if(empty($user)){
            echo "Company not found.";
        }
        
        $data['user'] = $user;
        $url = url('update_activity_log_for_expire_package');
        $currentDate = Carbon::now();
        $date = $currentDate->format('Y-m-d H:i:s');
        $latest_package = CompanyPackages::where('is_subscription_cancelled', 0)->where('employer_id', $user->id)->where('package_id', '!=', 5)->orderBy('id', 'desc')->first();
        
        if(empty($latest_package)){
            echo "Company Package not found.";
        }

        $latest_package->is_package_expire = 1;
        $latest_package->save();
        
        $type = $latest_package->package_type;

        $data['token_details'] = Tap::create_token($data);

        if(empty($data['token_details']->id)){
            echo "Tap token issue.";
        }
        
        
        
        $data['package'] = Package::where('id', $latest_package->package_id)->first();

        if(empty($data['package'])){
            echo "Package not found.";
        }

        $data['payment'] = PaymentMethod::where('id', 3)->first();
        
        if(empty($data['payment'])){
            echo "Payment Method not found.";
        }

        $data['error'] = '';
        $data['redirect'] = '';
        $data['success'] = '';
        $data['threeDSecure'] = false;
        $data['save_card'] = false;
        $data['customer_initiated'] = false;
        $data['user_id'] = $user->id;
        $data['type'] = $type;

        $data['token_id'] = $data['token_details']->id ?? '';
        if ($type == 'monthly') {
            $package_type_email = 'Monthly';
            $expire_date = $latest_package->package_expire_date;
        } else {
            $expire_date = $latest_package->yearly_package_expire_date;
            $package_type_email = 'Annually';
        }
        // if ($expire_date <= $date) {

            $response = Tap::create_charge_subscription($data);
            $postResponse = json_encode($response);

            if (!empty($response->status)) {
                if ($response->status == 'CAPTURED') {
                    $data_array['is_auto_renew'] = true;
                    $data_array['package_id'] = $latest_package->package_id;
                    $data_array['package_type'] = $type;
                    $data_array['with_payment'] = true;
                    $data_array['transaction_id'] = $response->id;
                    $data_array['employer_id'] = $id;
                    $data_array_update['company_package_id'] = $latest_package->id;

                    Helper::update_post_and_contact_card_counter($data_array);
                    $user_package_data = CompanyPackages::where('id', $latest_package->id)->first();
                    if (!empty($user_package_data)) {
                        $user_package_data->sub_renew_hit = 0;
                        $user_package_data->sub_hit_count = 0;
                        $user_package_data->save();
                    }
                    $packge_data = Package::where('id', $latest_package->package_id)->first();

                    $cc = '';
                    if (OptionalSelectedEmails::check_selected_email(6, $user->id)) {
                        $cc = $user->optional_emails;
                    }
                    Subscription::sendsubscriptionemail($packge_data, $user, 'Subscription Renew Successfully', $package_type_email, $cc);
                    Tap::create_log($user->id, $url, $data_array['package_type'] . ' Subscription update success', 'POST', 'Success Monthly subscription successfully renew for this user' . $user->name, $postResponse, 'subscriptionmonthly', '200');
                }
            }
        // }
        echo "Renew Company Package.";
    }

    public function commission_withdrawn_email()
    {
        $currentMonth = now()->format('F');;
        $currentYear = now()->format('Y');
        $lastMonth = now()->subMonth()->format('F');
        $lastYear = now()->subMonth()->format('Y');
        $monthNumbers = [
            "January" => 1,
            "February" => 2,
            "March" => 3,
            "April" => 4,
            "May" => 5,
            "June" => 6,
            "July" => 7,
            "August" => 8,
            "September" => 9,
            "October" => 10,
            "November" => 11,
            "December" => 12
        ];

        $commissions = ReferralCommission::where('status','pending')->get();

        foreach ($commissions as $key => $commission){
            if (($commission->month != $lastMonth || $commission->year != $lastYear) &&
                ($commission->year < $currentYear || ($commission->year == $currentYear && $monthNumbers[$commission->month] < $monthNumbers[$currentMonth]))
                ) 
            {
                $affiliate = User::find($commission->affiliate_id);
                $email_data = [
                    'name' => $affiliate->name,
                    'email' => $affiliate->email,
                    'month' => $commission->month,
                    'year' => $commission->year,
                    'commission' => $commission->total_commission,
                ];
                EmailHelper::sendEmailToAffiliateForInformWithdrawCommission($email_data);
            }else{
                continue;
            }
        }
    }
}
