<?php

namespace App\Helpers;

use App\Models\Activities;
use App\Models\AffiliatesCommissionSlots;
use App\Models\AffiliateSetting;
use App\Models\Applicant;
use App\Models\CompanyPackages;
use App\Models\ContactCardsRemaining;
use App\Models\EmailQueue;
use App\Models\EmailSetting;
use App\Models\Notification;
use App\Models\Package;
use App\Models\PageCount;
use App\Models\Payment;
use App\Models\Post;
use App\Models\PostRemaining;
use App\Models\ReferralCommission;
use App\Models\ThreadParticipant;
use App\Models\Unlock;
use App\Models\User;
use Carbon\Carbon;
use Session;
use setasign\Fpdi\PdfParser\StreamReader;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Imagick;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


class Helper
{

    public static function update_post_and_contact_card_counter($data)
    {
        $package_data = Package::where('id', $data['package_id'])->first();
        $package_name = (!empty($package_data->name)) ? $package_data->name : '';
        $package_price = $package_data->price;

        if ($data['is_auto_renew']) {
            $user = User::find($data['employer_id']);
            $name = $user->name;
            $company_email = $user->email;
            $profile_url = admin_url() . '/employer?search=' . $company_email;
            if (empty($data['with_payment']) && $data['package_type'] == 'yearly') {
                CompanyPackages::update_yearly_package_data_on_the_base_of_month($package_data, $data['company_package_id']);
                $description = "A Company Name: <a href='$profile_url'>$name</a> Contact Cards and Post has been beeen added for this month against the yearly Package.<br> Package Name:$package_name" .'<br> Package Price: $'. $package_price;
                Helper::activity_log($description);
                $package_data['name'] = $package_name;
                $package_data['price'] = $package_price;
                $package_data['cron'] = 1;
                $companyDescription = Helper::companyDescriptionData($package_data, 'yearly_renew_package_with_payment');
                if(!empty($companyDescription)){
                    Helper::activity_log($companyDescription,$user->id);
                }
            } else {
                if ($data['package_type'] == 'yearly') {
                    $price = $package_data->yearly_price;
                } else {
                    $price = $package_data->price;
                }


                $PaymentCreate = array(
                    'user_id' => $data['employer_id'],
                    'payment_method_id' => 3,
                    'active' => 1,
                    'transaction_id' => !empty($data['transaction_id'])?$data['transaction_id']:'',
                    'package_id' => $data['package_id'],
                    'amount' => $price,
                    'important' => 1,
                    'package_type' => $data['package_type'],
                );
                $insertedId = Payment::insertGetId($PaymentCreate);
                $PaymentCreate = json_encode($PaymentCreate);
                Tap::create_log($data['employer_id'], '', 'Update Payment data from helper', 'GET', 'Payment create for auto renew', $PaymentCreate, 'update_post_and_contactcard_counter', '');

                self::update_counter($data['employer_id'], $package_data, $insertedId, $data['package_type']);
                $package_type = ucfirst($data['package_type']);
                $description = "A Company Name: <a href='$profile_url'>$name</a> $package_type Package has been renew successfully.<br> Package Name: $package_name <br> Package Price: $ $package_price";
                Helper::activity_log($description);
                $package_data['type'] = $package_type;
                $package_data['name'] = $package_name;
                $package_data['price'] = $package_price;
                $package_data['cron'] = 1;
                $companyDescription = Helper::companyDescriptionData($package_data, 'renew_package');
                if(!empty($companyDescription)){
                    Helper::activity_log($companyDescription,$user->id);
                }
            }
        } else {

            $currentDate = Carbon::now();
            $date = $currentDate->format('Y-m-d H:i:s');
            $user_package_data = CompanyPackages::find($data['company_package_id']);
            $user = User::find($data['employer_id']);
            if (!empty($user)) {
                $name = $user->name;
                $company_email = $user->email;
                $profile_url = admin_url() . '/employer?search=' . $company_email;

                if ($data['package_type'] == 'yearly') {

                    if (!empty($data['payment_failed']) && $user_package_data->sub_hit_count >= 1) {
                        $payment_failed = $data['payment_failed'];
                        $description = "A Company Name: <a href='$profile_url'>$name</a> Error! Yearly subscription is Not renew due to payment failed:Payment Error: $payment_failed";
                        Helper::activity_log($description);
                        $package_data['payment_failed'] = $payment_failed;
                        $package_data['cron'] = 1;
                        $companyDescription = Helper::companyDescriptionData($package_data, 'payment_failed');
                        if(!empty($companyDescription)){
                            Helper::activity_log($companyDescription,$user->id);
                        }
                    } else {
                        $description = "A Company Name: <a href='$profile_url'>$name</a> " . $package_name . " Yearly Subscription month has been expired on:$date <br> Package Price: $ $package_price";
                        Helper::activity_log($description);
                        $package_data['date'] = $date;
                        $package_data['name'] = $package_name;
                        $package_data['price'] = $package_price;
                        $package_data['cron'] = 1;
                        $companyDescription = Helper::companyDescriptionData($package_data, 'yearly_expire_package');
                        if(!empty($companyDescription)){
                            Helper::activity_log($companyDescription,$user->id);
                        }
                    }

                } else {
                    if (!empty($data['payment_failed']) && $user_package_data->sub_hit_count == 1) {
                        $description = "A Company Name: <a href='$profile_url'>$name</a> " . $package_name . " Subscription has been expired on:$date <br> Package Price: $ $package_price";
                        Helper::activity_log($description);
                        $package_data['date'] = $date;
                        $package_data['name'] = $package_name;
                        $package_data['price'] = $package_price;
                        $package_data['cron'] = 1;
                        $companyDescription = Helper::companyDescriptionData($package_data, 'expire_package');
                        if(!empty($companyDescription)){
                            Helper::activity_log($companyDescription,$user->id);
                        }
                    }

                    if (!empty($data['payment_failed']) && $user_package_data->sub_hit_count >= 1) {
                        $payment_failed = $data['payment_failed'];
                        $description = "A Company Name: <a href='$profile_url'>$name</a> Error! Monthly subscription is Not renew due to payment failed:Payment Error: $payment_failed <br> Package name:$package_name <br> Package Price: $ $package_price";
                        Helper::activity_log($description);
                        $package_data['payment_failed'] = $payment_failed;
                        $package_data['name'] = $package_name;
                        $package_data['price'] = $package_price;
                        $package_data['cron'] = 1;
                        $companyDescription = Helper::companyDescriptionData($package_data, 'month_payment_failed');
                        if(!empty($companyDescription)){
                            Helper::activity_log($companyDescription,$user->id);
                        }
                    }

                    if ($data['package_id'] == 5) {
                        $description = "A Company Name: <a href='$profile_url'>$name</a> " . $package_name . " Subscription has been expired on:$date <br> Package Price: $ $package_price";
                        Helper::activity_log($description);
                        $package_data['date'] = $date;
                        $package_data['name'] = $package_name;
                        $package_data['price'] = $package_price;
                        $package_data['cron'] = 1;
                        $companyDescription = Helper::companyDescriptionData($package_data, 'expire_package');
                        if(!empty($companyDescription)){
                            Helper::activity_log($companyDescription,$user->id);
                        }
                    }
                }


                $unlock_users = ContactCardsRemaining::where('employer_id', $data['employer_id'])->whereNotNull('employee_id')->where('package_id', $data['package_id'])->where('is_package_expire', 0)->where('package_expire_date', '<=', $date)->get();

                foreach ($unlock_users as $unlock) {
                    $unlock_user = Unlock::where('to_user_id', $unlock->employer_id)->where('user_id', $unlock->employee_id)->first();
                    if ($unlock_user) {
                        $unlock_user->is_unlock = 0;
                        $unlock_user->save();
                    }

                    $applicant_unloc = Applicant::where('to_user_id', $unlock->employer_id)->where('user_id', $unlock->employee_id)->where('post_id', 0)->first();

                    if (!empty($applicant_unloc)) {
                        $applicant_unloc->is_deleted = 1;
                        $applicant_unloc->save();
                    }

                    $contact_cards__remaining = ContactCardsRemaining::find($unlock->id);
                    $contact_cards__remaining->is_package_expire = 1;
                    $contact_cards__remaining->updated_at = $date;
                    $contact_cards__remaining->save();
                }


                if (!empty($user_package_data)) {
                    $user_package_data->is_package_expire = 1;
                    $user_package_data->updated_at = $date;
                    $user_package_data->save();
                }

            }
        }
    }

    public static function update_counter($company_id, $package, $trnsaction_id = null, $package_type = 'monthly')
    {
        $company_package_details = self::count_previous_package_data($company_id);
        $currentDate = Carbon::now();
        $today = $currentDate->format('Y-m-d H:i:s');

        $newDate = $currentDate->addDays(30);
        $lastDate = $newDate->format('Y-m-d H:i:s');
        $yearly_date = null;
        if ($package_type == 'yearly') {
            $currentDateyearly = Carbon::now();
            $newDate_yearly = $currentDateyearly->addYear();
            $yearly_date = $newDate_yearly->format('Y-m-d H:i:s');
        }

        $company_package = new CompanyPackages();
        $company_package->employer_id = $company_id;
        $company_package->package_id = $package->id;

        if ($package->unlimited == 1) {
            $company_package->unlimited = 1;
        }else{
            $company_package->total_post = $package->number_of_posts;
            $company_package->remaining_post = $package->number_of_posts;
        }

        if ($package->unlimited_credit == 1) {
            $company_package->unlimited_credit = 1;
        } else {
            $company_package->total_credits = $package->number_of_cards;
            $company_package->remaining_credits = $package->number_of_cards;
        }

        $company_package->start_date = $today;
        $company_package->package_expire_date = $lastDate;
        $company_package->transaction_id = $trnsaction_id;
        $company_package->package_type = $package_type;
        $company_package->yearly_package_expire_date = $yearly_date;
        $company_package->save();

        $isUnlimitedCredit = $company_package_details['remaining_credits'] === 'unlimited';
 
        $isUnlimitedPost = $company_package_details['remaining_posts'] === 'unlimited';
 
        $UserCreate = [
            'previous_remaining_credits' => $isUnlimitedCredit ? 'unlimited' : $company_package_details['remaining_credits'],
            'new_remaining_credits'      => $isUnlimitedCredit ? 'unlimited' : $company_package_details['remaining_credits'] + $package->number_of_cards,
 
            'previous_total_credits'     => $isUnlimitedCredit ? 'unlimited' : $company_package_details['total_credits'],
            'new_total_credits'          => $isUnlimitedCredit ? 'unlimited' : $company_package_details['total_credits'] + $package->number_of_cards,
 
            'previous_remaining_posts'   => $isUnlimitedPost ? 'unlimited' : $company_package_details['remaining_posts'],
            'new_remaining_posts'        => $isUnlimitedPost ? 'unlimited' : $company_package_details['remaining_posts'] + $package->number_of_posts,
 
            'previous_total_posts'       => $isUnlimitedPost ? 'unlimited' : $company_package_details['total_posts'],
            'new_total_posts'            => $isUnlimitedPost ? 'unlimited' : $company_package_details['total_posts'] + $package->number_of_posts,
        ];

        $userarray = json_encode($UserCreate);
        if ($package->id != 5) {
            Tap::create_log($company_id, '', 'Update post and contact card data', 'GET', '', $userarray, 'update_post_and_contactcard_counter', '');
        }
    }

    public static function count_previous_package_data($company_id)
    {
        $data['remaining_credits'] = CompanyPackages::check_credit_available($company_id);
        $data['total_credits'] = CompanyPackages::check_total_credit($company_id);
        $data['remaining_posts'] = CompanyPackages::check_post_available($company_id);
        $data['total_posts'] = CompanyPackages::check_total_post($company_id);
        return $data;
    }

    public static function activity_log($description,$userId = 0,$packageData = '',$type=NULL)
    {
        $activity_log = new Activities();
        $activity_log->user_id = $userId;
        $activity_log->method = $_SERVER['REQUEST_METHOD'];
        $activity_log->route = url()->full();
        $activity_log->referrer = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "";
        $activity_log->ip_address = !empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : "";
        $activity_log->request = request();
        $activity_log->description = $description;
        $activity_log->created_at = date('Y-m-d H:i:s');
        $activity_log->type = $type;
        $activity_log->save();
        return true;
    }
    
    public static function companyDescriptionData($data,$type)
    {
        $description = '';
        if(empty($data['cron'])){
            if((auth()->user()->user_type_id == 2 && $type != 'post_apply') || auth()->user()->user_type_id == 5){
                return $description;
            }
        }
        if($type == 'profile'){
            $description = '{{company_name}} have updated your profile data:<br>'. implode(" ", $data).' at '.date('Y-m-d H:i:s');
        }
        if($type == 'email_settings'){
            $description = '{{company_name}} have updated your email settings:<br>'. implode(" ", $data) .' at '.date('Y-m-d H:i:s');
        }
        if($type == 'upload_logo'){
            $description = '{{company_name}} have '. $data .' at '.date('Y-m-d H:i:s');
        }
        if($type == 'child_company_create'){
            $description = "{{company_name}} have created a new company name: <br><a href='" . $data['child_company_url'] . "'> " . $data['child_company_name'] . "</a><br>at " .date('Y-m-d H:i:s');
        }
        if($type == 'child_company_update'){
            $description = '{{company_name}} have updated your company data: <br>'. implode(" ", $data).'<br>at '.date('Y-m-d H:i:s');
        }
        if($type == 'child_company_delete'){
            $description = "{{company_name}} have deleted your child company name: <br>" . $data['child_company_name'] . '<br>at ' .date('Y-m-d H:i:s');
        }
        if($type == 'staff_create'){
            $description = "{{company_name}} have created a new staff name: <br> <a href='" . $data['profile_url'] . "'> " . $data['name'] . "</a><br> at " .date('Y-m-d H:i:s');
        }
        if($type == 'staff_update'){
            $description = "{{company_name}} have updated your staff name: <br> <a href='" . $data['profile_url'] . "'> " . $data['name'] . "</a><br> at ".date('Y-m-d H:i:s');
        }
        if($type == 'staff_delete'){
            $description = "{{company_name}} have deleted your staff name: <br><a href='" . $data['url'] . "'> ". $data['name'] . "</a><br>at " .date('Y-m-d H:i:s');
        }
        if($type == 'staff_permission'){
            $description = "{{company_name}} have update permission to your staff name: <br><a href='" . $data['url'] . "'> ". $data['name'] . "</a><br>at " .date('Y-m-d H:i:s');
        }
        if($type == 'staff_password'){
            $description = "{{company_name}} have update password to your staff name: <br><a href='" . $data['url'] . "'> ". $data['name'] . "</a><br>at " .date('Y-m-d H:i:s');
        }
        if($type == 'remove_favourite'){
            $description = "{{company_name}} have removed favorite employee CV: <br> <a href='" . $data['employee_url'] . "'> " . $data['employee_name'] . "</a><br>at ".date('Y-m-d H:i:s');
        }
        if($type == 'add_favourite'){
            $description = "{{company_name}} have added into favorite employee CV: <br> <a href='" . $data['employee_url'] . "'> " . $data['employee_name'] . "</a><br>at ".date('Y-m-d H:i:s');
        }
        if($type == 'applicant_status_update'){
            $description = "{{company_name}} have changed an applicant's status from <b>" . $data['applicant_status'] . "</b> to <b>" . $data['status'] . "</b> to <br> employee name: <a href='" . $data['employee_url'] . "'> " . $data['employee_name'] . "</a><br> at ".date('Y-m-d H:i:s');
        }
        if($type == 'job_post'){
            $description = "{{company_name}} have added a new job post title: ". $data['post_title'] ."<br> Click this link to checkout <a href='" . $data['post_url'] . "'>Preview</a><br> at ".date('Y-m-d H:i:s');
        }
        if($type == 'job_post_edit'){
            $description = "{{company_name}} have updated your job details: <b> <a href='" . $data['post_url'] . "'>" . $data['name'] . "</a></b><br>". implode(" ", $data['changes']).' at '.date('Y-m-d H:i:s');
        }
        if($type == 'job_post_delete'){
            $description = "{{company_name}} have deleted your job post title: <b>" . $data['name'] . "</b><br> at " .date('Y-m-d H:i:s');
        }
        if($type == 'job_post_repost'){
            $description = "{{company_name}} have reposted your job post title: <b> <a href='". $data['post_url'] . "'>" . $data['name'] . "</a></b><br> at " .date('Y-m-d H:i:s');
        }
        if($type == 'send_message_to_admin'){
            $description = "{{company_name}} have sent message to admin: Hungry For Jobs<br> at " .date('Y-m-d H:i:s');
        }
        if($type == 'send_message_to_employee'){
            $description = "{{company_name}} have sent message to employee (job seeker):<b>  <a href='"  . $data['employee_url'] . "'>" . $data['employee_name'] . "</a></b><br> at " .date('Y-m-d H:i:s');
        }
        if($type == 'unlock_profile'){
            $description = "{{company_name}} have just used your contact card to unlock the employee profile <br> Employee name: <b><a href='" . $data['employee_url'] . "'>" . $data['employee_name'] .  "</a></b> at " .date('Y-m-d H:i:s');
        }
        if($type == 'applicant_cv_save'){
            $description = "{{company_name}} have saved an applicant's CV applicant name: <b><a href='" . $data['employee_url'] . "'> " . $data['employee_name'] . "</a></b> at ".date('Y-m-d H:i:s');
        }
        if($type == 'remove_save_resume'){
            $description = "{{company_name}} have removed an applicant's CV applicant name: <b><a href='" . $data['employee_url'] . "'> " . $data['employee_name'] . "</a></b> at ".date('Y-m-d H:i:s');
        }
        if($type == 'change_thread_status'){
            $description = "{{company_name}} have " . $data['type']. " chat with employee name: <b><a href='" . $data['employee_url'] . "'> " . $data['employee_name'] . "</a></b> at ".date('Y-m-d H:i:s');
        }
        if($type == 'chat_message_status'){
            $description = "{{company_name}} have " . $data['type']. " a message with Employee name: <b><a href='" . $data['employee_url'] . "'> " . $data['employee_name'] . "</a></b> at ".date('Y-m-d H:i:s');
        }
        if($type == 'post_archived'){
            $description = "{{company_name}} have archived the job title <a href='". $data['url'] . "'>" . $data['name'].'</a><br> with reason '.$data['reason']."<br> at ".date('Y-m-d H:i:s');
        }
        if($type == 'upgrade_account'){
           $description = "{{company_name}} have purchased a premium package: ".$data['name']." and price: $".$data['price']." at ".date('Y-m-d H:i:s');
        }
        if($type == 'cancel_subscription'){
           $description = "{{company_name}} have cancel a premium package: ".$data['name']." and price: $".$data['price']." with reason ".$data['cancel_reason']." at ".date('Y-m-d H:i:s');
        }
        if($type == 'post_apply'){
            $description = "{{company_name}} have a new applicant  <br> <a href='".$data['url']."'>".$data['name']."</a> has applied to your job post  <br>"."<a href='".$data['job_url']."'>".$data['job_title']."</a><br>at ".date('Y-m-d H:i:s');
        }
        if($type == 'subscribe_with_discount'){
            $description = "{{company_name}} subscribe a Premium Package : ".$data['name']." <br>Price: $".$data['price']."<br>Discount: ".$data['discount']."<br>Package price after discount: $ ".$data['after_discount']."<br>at ".date('Y-m-d H:i:s');
        }
        if($type == 'subscribe_without_discount'){
            $description = "{{company_name}} subscribe a Premium Package : ".$data['name']." and Price: $".$data['price']."at ".date('Y-m-d H:i:s');
        }
        if($type == 'yearly_renew_package_with_payment'){
            $description = "{{company_name}} Contact Cards and Post has been been added for this month against the yearly Package.<br> Package Name : ".$data['name']." and Price: $".$data['price']."<br>at ".date('Y-m-d H:i:s');
        }
        if($type == 'renew_package'){
            $description = "{{company_name}} ".$data['type']." Package has been renew successfully.<br> Package Name : ".$data['name']." and Price: $".$data['price']."<br>at ".date('Y-m-d H:i:s');
        }
        if($type == 'payment_failed'){
            $description = "{{company_name}} have not renewed Yearly subscription due to payment failed:<br>Payment Error: ".$data['payment_failed'] ."<br>at ".date('Y-m-d H:i:s');
        }
        if($type == 'yearly_expire_package'){
            $description = "{{company_name}} have expired month of Yearly Subscription on : ".$data['date']."<br> Package Name : ".$data['name']." and Price: $".$data['price'];
        }
        if($type == 'expire_package'){
            $description = "{{company_name}} have expired subscription on : ".$data['date']."<br>Package Name : ".$data['name']." and Price: $".$data['price'];
        }
        if($type == 'month_payment_failed'){
            $description = "{{company_name}} have not subscribed to Monthly subscription due to payment failed:<br>Payment Error: ".$data['payment_failed'] ."<br> Package Name : ".$data['name']." and Price: $".$data['price']."<br>at ".date('Y-m-d H:i:s');
        }
        if($type == 'free_cv_no_contact'){
            $description = "{{company_name}} have opened a free no-contact CV of employee (job seeker):<b>  <a href='"  . $data['employee_url'] . "'>" . $data['employee_name'] . "</a></b><br> at " .date('Y-m-d H:i:s');
        }
        return $description;
    }

    public static function add_notification($type, $user_id)
    {
        $notification = new Notification();
        $notification->type = $type;
        $notification->user_id = $user_id;
        $notification->is_read = 0;
        $notification->created_at = date('Y-m-d H:i:s');
        $notification->save();
        return true;
    }

    public static function update_notification($type, $user_id)
    {
        $value['is_read'] = 1;
        $notification = Notification::where('user_id', $user_id)->where('type', $type)->update($value);
        return true;
    }

    public static function get_notification($type, $user_id)
    {
        return Notification::where('user_id', $user_id)->where('type', $type)->where('is_read', 0)->get();
    }

    public static function check_expiry($todayDate, $expire_date)
    {
        if ($expire_date > $todayDate) {
            return true;
        } else {
            return false;
        }
    }

    public static function check_permission($permission_id)
    {
        if (!empty(Session::get('staff_id'))) {
            $staff_id = Session::get('staff_id');
        } else {
            $staff_id = 0;
        }
        if (!empty($staff_id) && auth()->user()->user_type_id == 1) {
            $staff_data = User::where('id', $staff_id)->first();
            if ($staff_data) {
                $permissions = explode(',', $staff_data->permissions);
                if (in_array($permission_id, $permissions)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    public static function fillPDFFile($file, $outputFilePath, $is_temp = 0)
    {
        error_reporting(E_ALL);
        ini_set('display_errors', '1');
        $pdf = new Pdf();
        if($is_temp == 0){
            $file = storage_path('app/public/') . '/' . $file;
        }        
        $fileContent = file_get_contents($file, 'rb');
        $count = $pdf->setSourceFile(StreamReader::createByString($fileContent));
        for ($i = 1; $i <= $count; $i++) {
            $template = $pdf->importPage($i);
            $size = $pdf->getTemplateSize($template);
            $pdf->AddPage($size['orientation'], array($size['width'], $size['height']));
            $pdf->useTemplate($template);
            $pdf->SetFont("helvetica", "", 15);
            $pdf->SetTextColor(148, 216, 190);
            $left = 5;
            $top = 5;
            $text = "";
            $pdf->Text($left, $top, $text);

            $logo = url()->asset('images/logocv.png');
            $pdf->Image($logo, -10, -5, 220);
        }
        return $pdf->Output($outputFilePath, 'F');
    }

    public static function validatepdffile($cv)
    {
        $temp_path = $cv->getPathName();
        $file = file($temp_path);
        if (!empty(count($file))) {
            $endfile = trim($file[count($file) - 1]);
        } else {
            if (!empty($file)) {
                $endfile = trim($file[count($file) - 1]);
            } else {
                return false;
            }
        }
        $n = "%%EOF";

        if (strpos($endfile, $n) !== false) {
            return true;
        } else {
            return false;
        }

    }

    public static function getPostAllApplicants($posts)
    {

        if (!empty($posts)) {
            foreach ($posts as $key => $value) {
                $value->post_count = Post::getpostapplicants($value->id);
                $value[$key] = $value->post_count;

            }

        }
        return $posts;
    }


    public static function validateUserProfileImage($file)
    {
        if ($file->getError() === UPLOAD_ERR_OK) {
            // Check if the file is an image
            if ($file->isValid()) {
                // Check if the file is not empty
                if ($file->getSize() > 0) {
                    // The image is valid and not empty
                    $extention = $file->getMimeType();
                    $allowed = array('image/jpg', 'image/png', 'image/jpeg');
                    if (!in_array($extention, $allowed)) {
                        return false;
                    } else {
                        return true;
                    }
                } else {
                    // The file is empty
                    return false;
                }
            } else {
                // The file is not a valid image
                return false;
            }
        } else {
            // Handle file upload errors
            return false;
        }
    }

    public static function get_company_logo($company,$real_image=true)
    {
        if (empty($company->thumbnail) || $real_image) {
            if (empty($company->logo)) {
                $logo_show = 'pictures/default.jpg';
            } else {
                if (file_exists(public_path('/') . 'storage/' . $company->logo)) {
                    $logo_show = $company->logo;
                } else {
                    $logo_show = 'pictures/default.jpg';
                }
            }
        } else {
            if (file_exists(public_path('/') . 'storage/' . $company->thumbnail)) {
                $logo_show = $company->thumbnail;
            } else {
                $logo_show = 'pictures/default.jpg';
            }
        }
        return $logo_show;
    }

    public static function get_company_logo_AWS($company,$real_image = true)
    {
        $baseUrl = app()->environment('local') ? TESTING_USER_PICTURE_PATH : LIVE_USER_PICTURE_PATH;
 
        if ($company->is_image_uploaded_on_aws == 1) {
            $imageFile = $real_image ? $company->logo : ($company->thumbnail ?: $company->logo);
            if($imageFile != 'pictures/default.jpg' && $imageFile != 'pictures/avatar.png'){
                return $baseUrl . $imageFile;
            } 
        }
    
        if ($real_image) {
            $imageFile = $company->logo;
        } else {
            $thumbnailPath = public_path('storage/' . $company->thumbnail);
            $imageFile = (!empty($company->thumbnail) && file_exists($thumbnailPath)) ? $company->thumbnail : $company->logo;
        }

        $finalPath = public_path('storage/' . $imageFile);
        if (!empty($imageFile) && file_exists($finalPath)) {
            return url('public/storage/' . $imageFile);
        }

        return url('public/storage/pictures/default.jpg');
    }

    public static function get_post_logo($post)
    {
        if (str_contains($post->logo, 'default') || str_contains($post->logo, 'avatar')) {
            $logo_show=self::getImageOrThumbnailLink(User::find($post->user_id), true);
        } else {
            $logo_show = self::get_company_logo_AWS($post);
        }
        return $logo_show;
    }

    public static function get_users_images($user, $image = 'thumbnail')
    {
        if ($image == 'thumbnail') {
            if (!empty($user->thumbnail) && file_exists(public_path('/') . 'storage/' . $user->thumbnail)) {
                $logo_show = $user->thumbnail;
            } else if (!empty($user->file) && file_exists(public_path('/') . 'storage/' . $user->file)) {
                $logo_show = $user->file;
            } else {
                $logo_show = 'pictures/default.jpg';
            }
        } else {
            if (!empty($user->file) && file_exists(public_path('/') . 'storage/' . $user->file)) {
                $logo_show = $user->file;
            } else {
                $logo_show = 'pictures/default.jpg';
            }
        }

        return $logo_show;
    }

    public static function get_employer_logo($user)
    {

        if (!empty($user->file) && file_exists(public_path('/') . 'storage/' . $user->file)) {
            $logo_show = $user->file;
        } else {
            $logo_show = 'pictures/default.jpg';
        }
        return $logo_show;
    }


    public static function replace_url_param($param, $value)
    {
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
// Get the current URL
        $replacement = $value;
        $string = $actual_link;
        $link = '';
        if (strpos($actual_link, '?' . $param . '=') !== false) {
            $queryString = parse_url($actual_link, PHP_URL_QUERY); // Get the query string
            $queryArray = explode('&', $queryString); // Split the query string into an array of parameters
            foreach ($queryArray as $index => $queryParam) {
                if (strpos($queryParam, $param . '=') === 0) {
                    $queryArray[$index] = $param . '=' . urlencode($replacement); // Replace the 'cat' value with the encoded replacement
                    break; // Stop the loop after replacing the first occurrence of 'cat'
                }
            }
            $newQueryString = implode('&', $queryArray); // Reconstruct the query string
            $link = str_replace($queryString, $newQueryString, $string); // Replace the original query string with the modified one
        }
        return $link;
    }

    public static function replace_param_in_url($param, $value)
    {
        $actual_link = \Request::fullUrl();
        $pagePattern = '/(&page=[^&]*)|(&page=[^&]*&)/';
        if (preg_match($pagePattern, $actual_link)) {
            $actual_link = preg_replace($pagePattern, '&page=', $actual_link);
        } else {
            if (strpos($actual_link, '?') === false) {
                $actual_link .= '?page=';
            } else {
                $actual_link .= '&page=';
            }
        }
        if ($param == 'country') {
            $link = str_replace('&' . $param . '=' . request()->get($param), '&' . $param . '=' . $value, $actual_link);
            $link = str_replace('&city=' . request()->get('city'), '&city=' . '', $link);
        } else {
            $link = str_replace('&' . $param . '=' . request()->get($param), '&' . $param . '=' . $value, $actual_link);
        }

        return $link;
    }
    public function send_email($email_data)
    {
        $email_setting = $this->get_email_setting();
        $email_data['from'] = 'hungryforjobskuwait9@gmail.com';
        $email_data['fb'] = config('settings.social_link.facebook_page_url');
        $email_data['insta'] = config('settings.social_link.instagram_url');
        $email_data['linkedin'] = config('settings.social_link.linkedin_url');
        $email_data['tiktok'] = config('settings.social_link.google_plus_url');

        // $body = view($email_data['view'])->with($email_data);
        $body = view($email_data['view'])->with($email_data)->render();


        $insert_email_log = new EmailQueue();
        $insert_email_log->from = $email_setting->email;
        $insert_email_log->to = $email_data['email'];
        $insert_email_log->status = 1;
        $insert_email_log->body = $body;
        $insert_email_log->subject = $email_data['subject'];
        $insert_email_log->cc = !empty($email_data['cc'])?$email_data['cc']:'';

        $insert_email_log->created_at = date('Y-m-d H:i:s');
        $insert_email_log->updated_at = date('Y-m-d H:i:s');
        $insert_email_log->save();
        // $email_queue_id = $insert_email_log->id;

        // $transport = (new \Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))
        //     ->setUsername($email_setting->email)
        //     ->setPassword($email_setting->key);
        // // Create the Mailer using your created Transport

        // $mailer = new \Swift_Mailer($transport);
        //  $message = (new \Swift_Message())
        //     ->setFrom(array($email_setting->email => 'Hungry For Jobs'))
        //     ->setTo([$email_data['email'] => ''])
        //     ->setSubject($email_data['subject'])
        //     ->setBody($body, 'text/html');
        //     if(!empty($email_setting->status) && $email_setting->status==1){
        //         $result = $mailer->send($message);
        //     }
        // if (!empty($result)) {
        //     $email_qu['status'] = 2;
        //     EmailQueue::where('id', $email_queue_id)->update($email_qu);
        //     return true;
        // } else {
        //     return false;
        // }
        return true;
    }

    public static function get_email_setting()
    {
        $email_setting = EmailSetting::first();
        return $email_setting;
    }

    public function send_email_queue($email_data)
    {
       
        $email_setting = $this->get_email_setting();
        $transport = (new Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))
            ->setUsername($email_setting->email)
            ->setPassword($email_setting->key);
       
        // Create the Mailer using your created Transport

        $mailer = new Swift_Mailer($transport);
        $message = (new Swift_Message())
            ->setFrom(array($email_setting->email => 'Hungry For Jobs'))
            ->setTo([$email_data->to => ''])
            ->setSubject($email_data->subject)
            ->setBody($email_data->body, 'text/html');
        if (!empty($email_setting->status) && $email_setting->status == 1) {
             
            $result = $mailer->send($message);
         
          
        }
        if (!empty($result)) {
            $email_qu['status'] = 2;
            EmailQueue::where('id', $email_data->id)->update($email_qu);
            return true;
        } else {
            return false;
        }
        
    //     $from = '';
    //     $server = str_replace('www.', '', $_SERVER["HTTP_HOST"]);
    //     if (strpos($from, '@') == false) {
    //         $from = "contact@" . $server;
    //     }
    //     $from = "contact@" . $server;

    //     $headers = [
    //         'From' => $from,
    //         'X-Mailer' => 'PHP/' . phpversion(),
    //         'X-Priority' => '1',
    //         'Return-Path' => $from,
    //         'MIME-Version' => '1.0',
    //         'mailed-by' => $from,
    //         'Content-Type' => 'text/html; charset=iso-8859-1'
    //     ];
    //   if (mail($email_data->to, $email_data->subject, $email_data->body, $headers)) {
          
    //          $email_qu['status'] = 2;
    //         //  dd($email_data);
    //         EmailQueue::where('id', $email_data->id)->update($email_qu);
    //         return true;
    //     } else {
          
    //         return false;
    //     }
        
        
    }

    public function bulk_email_queue($email_data)
    {
        $email_setting = $this->get_email_setting();
        $email_data['from'] = 'contact@hungryforjobs.com';
        $email_data['fb'] = config('settings.social_link.facebook_page_url');
        $email_data['insta'] = config('settings.social_link.instagram_url');
        $email_data['linkedin'] = config('settings.social_link.linkedin_url');
        $email_data['tiktok'] = config('settings.social_link.google_plus_url');

        // $body = view($email_data['view'])->with($email_data);
        $body = view($email_data['view'])->with($email_data)->render();


        $insert_email_log = new EmailQueue();
        $insert_email_log->from = $email_setting->email;
        $insert_email_log->to = $email_data['email'];
        $insert_email_log->status = 1;
        $insert_email_log->body = $body;
        $insert_email_log->subject = $email_data['subject'];
        $insert_email_log->created_at = date('Y-m-d H:i:s');
        $insert_email_log->updated_at = date('Y-m-d H:i:s');
        if ($insert_email_log->save()) {
            return true;
        } else {
            return false;
        }
    }

    public static function update_remaining_credit($employer, $employee_id)
    {

        if (!empty($employee_id)) {

            $comoany_package = CompanyPackages::where('employer_id', $employer->id)->where('is_package_expire', 0)->first();
            $contact_card_remain = new ContactCardsRemaining();

            if (!empty($comoany_package) && $comoany_package->unlimited_credit == 1) {

                $contact_card_remain->employer_id = $employer->id;
                $contact_card_remain->package_id = $comoany_package->package_id;
                $contact_card_remain->company_package_id = $comoany_package->id;
                $contact_card_remain->employee_id = $employee_id;
                $contact_card_remain->package_expire_date = $comoany_package->package_expire_date;
                $contact_card_remain->created_at = date("Y-m-d");
                return $contact_card_remain->save();
            } elseif (!empty($comoany_package) && $comoany_package->remaining_credits >= 1) {

                $comoany_package->remaining_credits = $comoany_package->remaining_credits - 1;
                $comoany_package->save();

                $contact_card_remain->employer_id = $employer->id;
                $contact_card_remain->package_id = $comoany_package->package_id;
                $contact_card_remain->company_package_id = $comoany_package->id;
                $contact_card_remain->employee_id = $employee_id;
                $contact_card_remain->package_expire_date = $comoany_package->package_expire_date;
                $contact_card_remain->created_at = date("Y-m-d");
                return $contact_card_remain->save();
            } else {
                return false;
            }

        }

    }

    public static function update_remaining_post($employer, $post_id)
    {
        $check_post_available = PostRemaining::where('employer_id', $employer->id)->where('post_id', $post_id)->where('is_post_expire', 0)->first();

        if (empty($check_post_available)) {
            if (!empty($employer->id)) {

                $comoany_package = CompanyPackages::where('employer_id', $employer->id)->where('is_package_expire', 0)->first();
                $post_remain = new PostRemaining();
                $currentDate = Carbon::now();
                $today = $currentDate->format('Y-m-d H:i:s'); // Include time components
                $newDate = $currentDate->addDays(30);
                $expire_date = $newDate->format('Y-m-d H:i:s');

                if (!empty($comoany_package) && $comoany_package->unlimited == 1) {
                    $post_remain->employer_id = $employer->id;
                    $post_remain->package_id = $comoany_package->package_id;
                    $post_remain->company_package_id = $comoany_package->id;
                    $post_remain->post_id = $post_id;
                    $post_remain->post_expire_date_time = $expire_date;
                    $post_remain->created_at = $today;
                    return $post_remain->save();
                } else if (!empty($comoany_package) && $comoany_package->remaining_post > 0) {

                    $comoany_package->remaining_post = $comoany_package->remaining_post - 1;
                    $comoany_package->save();
                    $post_remain->employer_id = $employer->id;
                    $post_remain->package_id = $comoany_package->package_id;
                    $post_remain->company_package_id = $comoany_package->id;
                    $post_remain->post_id = $post_id;
                    $post_remain->post_expire_date_time = $expire_date;
                    $post_remain->created_at = $today;
                    return $post_remain->save();
                } else {
                    return false;
                }

            }
        }

    }

    public static function calculate_remaining_days($expire_date)
    {
        $expire = date('Y-m-d', strtotime($expire_date));
        $postExpireDate = Carbon::parse($expire);
        $currentDate = Carbon::now();
        $current_date = $currentDate->format('Y-m-d');
        $current_date = Carbon::parse($current_date);
        $remainingDays = $current_date->diffInDays($postExpireDate, false);
        return $remainingDays;
    }

    public static function calculate_remaining_days_of_post($post)
    {
        $remain_post = PostRemaining::get_post_by_employer_id_and_post_id($post->user_id, $post->id);
        if (!empty($remain_post->post_expire_date_time)) {
            if ($post->is_post_expire == 0) {
                $remainingDays = self::calculate_remaining_days($remain_post->post_expire_date_time);
                $datetime1 = strtotime($remain_post->post_expire_date_time);
                $datetime2 = strtotime(date('Y-m-d H:i:s'));
                if ($datetime1 > $datetime2) {
                    return 'Expires In: ' . $remainingDays.' Days';
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function calculate_remaining_days_with_time($expire_date)
    {

        $expire = date('Y-m-d', strtotime($expire_date));
        if ($expire == date('Y-m-d')) {
            $expire = $expire_date;
        }

        $postExpireDate = Carbon::parse($expire);
        $currentDate = Carbon::now();

        if (date('Y-m-d', strtotime($expire_date)) == date('Y-m-d')) {
            $current_date = $currentDate->format('Y-m-d H:i:s');
            $current_date = Carbon::parse($current_date);
            // Calculate remaining days, hours, minutes, and seconds
            $remainingSeconds = $current_date->diffInSeconds($postExpireDate, false);
            $remainingDays = floor($remainingSeconds / (24 * 3600));
            $remainingHours = floor(($remainingSeconds % (24 * 3600)) / 3600);

            return $remainingHours;
        } else {
            $current_date = $currentDate->format('Y-m-d');
            $current_date = Carbon::parse($current_date);
            // Calculate remaining days
            $remainingDays = $current_date->diffInDays($postExpireDate, false);
            return $remainingDays;
        }
    }

    public static function page_count_ajax($request)
    {
        $page_count = new PageCount();
        $page_count->user_id = !empty(auth()->user()->id) ? auth()->user()->id : 0;
        $page_count->from = $request->post('from');
        $page_count->page = $request->post('page');
        $query_parameter = !empty($request->post('quary_parameter')) ? implode(",", $request->post('quary_parameter')) : '';
        $page_count->query_parameter = $query_parameter;
        $page_count->request = json_encode($request);
        $page_count->server = $request->post('server');
        return $page_count->save();
    }

    public static function page_count_post($data)
    {

        $page_count = new PageCount();
        $page_count->user_id = !empty(auth()->user()->id) ? auth()->user()->id : 0;
        $page_count->from = $data['from'];
        $page_count->page = $data['page'];
        $query_parameter = !empty($data['quary_parameter']) ? implode(",", $data['quary_parameter']) : '';
        $page_count->query_parameter = $query_parameter;
        $page_count->request = json_encode($data['request']);
        $page_count->server = $data['server'];
        return $page_count->save();
    }

    public static function list_of_unlocked_applicants()
    {
        $status = false;
        $view = '';
        $unlocked_applicants_list = Applicant::get_unlock_applicants();
        if ($unlocked_applicants_list->isNotEmpty()) {
            $status = true;
            $view = view('account.applicants.applicants_table', compact('unlocked_applicants_list'))->render();
        }
        return ['status' => $status, 'view' => $view];
    }

    public
    static function print_query($query)
    {
        // Get the SQL query string with placeholders
        $sql = $query->toSql();
        // Get the actual values for the placeholders
        $bindings = $query->getBindings();
        // Replace the placeholders with actual values in the SQL query
        $fullSql = vsprintf(str_replace('?', "'%s'", $sql), $bindings);
        // Print the SQL query with values
        dd($fullSql);
    }

    public
    static function get_day_time()
    {
        $currentHour = date('H');
        if ($currentHour >= 5 && $currentHour < 12) {
            $greeting = "Good Morning";
        } elseif ($currentHour >= 12 && $currentHour < 18) {
            $greeting = "Good Afternoon";
        } else {
            $greeting = "Good Evening";
        }

        return $greeting;
    }

    public static function rand_color($index = 0)
    {
        $colors = [
            "#ea577f",
            "#5f76e8",
            "#ff4f70",
            "#79ef0f",
            "#01caf1",
            '#eb4034',
            '#34eb98',
            '#eb348c',
            '#eb5334',
            '#ebd334'
        ];
        if (!empty($colors[$index])) {
            return $colors[$index];
        } else {
            return "#ea577f";
        }
    }

    public function print_Raw_sql($query)
    {
        $sql = $query->toSql();
        $bindings = $query->getBindings();
        $fullSql = vsprintf(str_replace(['?', '%'], ['\'%s\'', '%%'], $sql), $bindings);
        return $fullSql;
    }

    public static function generate_referral_code()
    {
        $ref_code = Str::random('10');
        if (!User::where('referral_code', '=', $ref_code)->exists()) {
            $ref_code = Str::random(10);
        }
        return $ref_code;
    }

    public static function check_is_already_chat_with_admin($threads)
    {
        if(!empty($threads)) {
            foreach ($threads as $thread) {
                $ThreadParticipant = ThreadParticipant::where('thread_id', $thread->id)->where('user_id', '!=', auth()->user()->id)->first();
                if ($ThreadParticipant->user_id == 1) {
                    return true;
                } else {
                    return false;
                }
            }
        }else{
            return false;
        }
    }
    public static function generate_thumbnail($url, $user_id, $image_name)
    {
        $im = new Imagick($url);
        $imageprops = $im->getImageGeometry();
        $width = $imageprops['width'];
        $height = $imageprops['height'];

        if ($width > $height) {
            $newHeight = 350;
            $newWidth = (350 / $height) * $width;
        } else {
            $newWidth = 350;
            $newHeight = (350 / $width) * $height;
        }

        $im->resizeImage($newWidth, $newHeight, Imagick::FILTER_LANCZOS, 0.9, true);
        $im->setImageCompression(Imagick::COMPRESSION_JPEG);
        $im->setImageCompressionQuality(65);

        $directory = public_path('storage/pictures/kw/' . $user_id);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        $im->writeImage(public_path('/') . 'storage/pictures/kw/' . $user_id . '/' . $image_name);
        return true;
    }

    public static function check_user_is_online($thread)
    {
        $otherUser = \App\Models\ThreadParticipant::where('thread_id', $thread->id)->where('user_id', '!=', auth()->user()->id)->first();
        $userdata = \App\Models\User::get_user_by_id($otherUser->user_id);
        $userIsOnline = isUserOnline($userdata) ? 'online' : 'offline';
        return $userIsOnline;
    }

    public static function getCountry($countryCode = 'KW')
    {
        $countries = [
            'AF' => 'Afghanistan',
            'AL' => 'Albania',
            'DZ' => 'Algeria',
            'AS' => 'American Samoa',
            'AD' => 'Andorra',
            'AO' => 'Angola',
            'AI' => 'Anguilla',
            'AQ' => 'Antarctica',
            'AG' => 'Antigua and Barbuda',
            'AR' => 'Argentina',
            'AM' => 'Armenia',
            'AW' => 'Aruba',
            'AU' => 'Australia',
            'AT' => 'Austria',
            'AZ' => 'Azerbaijan',
            'BS' => 'Bahamas',
            'BH' => 'Bahrain',
            'BD' => 'Bangladesh',
            'BB' => 'Barbados',
            'BY' => 'Belarus',
            'BE' => 'Belgium',
            'BZ' => 'Belize',
            'BJ' => 'Benin',
            'BM' => 'Bermuda',
            'BT' => 'Bhutan',
            'BO' => 'Bolivia',
            'BQ' => 'Bonaire, Sint Eustatius and Saba',
            'BA' => 'Bosnia and Herzegovina',
            'BW' => 'Botswana',
            'BR' => 'Brazil',
            'IO' => 'British Indian Ocean Territory',
            'BN' => 'Brunei Darussalam',
            'BG' => 'Bulgaria',
            'BF' => 'Burkina Faso',
            'BI' => 'Burundi',
            'CV' => 'Cabo Verde',
            'KH' => 'Cambodia',
            'CM' => 'Cameroon',
            'CA' => 'Canada',
            'KY' => 'Cayman Islands',
            'CF' => 'Central African Republic',
            'TD' => 'Chad',
            'CL' => 'Chile',
            'CN' => 'China',
            'CO' => 'Colombia',
            'KM' => 'Comoros',
            'CG' => 'Congo',
            'CD' => 'Congo (DRC)',
            'CR' => 'Costa Rica',
            'CI' => 'Côte d’Ivoire',
            'HR' => 'Croatia',
            'CU' => 'Cuba',
            'CW' => 'Curaçao',
            'CY' => 'Cyprus',
            'CZ' => 'Czechia',
            'DK' => 'Denmark',
            'DJ' => 'Djibouti',
            'DM' => 'Dominica',
            'DO' => 'Dominican Republic',
            'EC' => 'Ecuador',
            'EG' => 'Egypt',
            'SV' => 'El Salvador',
            'GQ' => 'Equatorial Guinea',
            'ER' => 'Eritrea',
            'EE' => 'Estonia',
            'SZ' => 'Eswatini',
            'ET' => 'Ethiopia',
            'FJ' => 'Fiji',
            'FI' => 'Finland',
            'FR' => 'France',
            'GF' => 'French Guiana',
            'PF' => 'French Polynesia',
            'GA' => 'Gabon',
            'GM' => 'Gambia',
            'GE' => 'Georgia',
            'DE' => 'Germany',
            'GH' => 'Ghana',
            'GI' => 'Gibraltar',
            'GR' => 'Greece',
            'GL' => 'Greenland',
            'GD' => 'Grenada',
            'GP' => 'Guadeloupe',
            'GU' => 'Guam',
            'GT' => 'Guatemala',
            'GN' => 'Guinea',
            'GW' => 'Guinea-Bissau',
            'GY' => 'Guyana',
            'HT' => 'Haiti',
            'HN' => 'Honduras',
            'HK' => 'Hong Kong',
            'HU' => 'Hungary',
            'IS' => 'Iceland',
            'IN' => 'India',
            'ID' => 'Indonesia',
            'IR' => 'Iran',
            'IQ' => 'Iraq',
            'IE' => 'Ireland',
            'IL' => 'Israel',
            'IT' => 'Italy',
            'JM' => 'Jamaica',
            'JP' => 'Japan',
            'JO' => 'Jordan',
            'KZ' => 'Kazakhstan',
            'KE' => 'Kenya',
            'KI' => 'Kiribati',
            'KW' => 'Kuwait',
            'KG' => 'Kyrgyzstan',
            'LA' => 'Laos',
            'LV' => 'Latvia',
            'LB' => 'Lebanon',
            'LS' => 'Lesotho',
            'LR' => 'Liberia',
            'LY' => 'Libya',
            'LI' => 'Liechtenstein',
            'LT' => 'Lithuania',
            'LU' => 'Luxembourg',
            'MO' => 'Macao',
            'MG' => 'Madagascar',
            'MW' => 'Malawi',
            'MY' => 'Malaysia',
            'MV' => 'Maldives',
            'ML' => 'Mali',
            'MT' => 'Malta',
            'MH' => 'Marshall Islands',
            'MQ' => 'Martinique',
            'MR' => 'Mauritania',
            'MU' => 'Mauritius',
            'MX' => 'Mexico',
            'FM' => 'Micronesia',
            'MD' => 'Moldova',
            'MC' => 'Monaco',
            'MN' => 'Mongolia',
            'ME' => 'Montenegro',
            'MS' => 'Montserrat',
            'MA' => 'Morocco',
            'MZ' => 'Mozambique',
            'MM' => 'Myanmar',
            'NA' => 'Namibia',
            'NR' => 'Nauru',
            'NP' => 'Nepal',
            'NL' => 'Netherlands',
            'NC' => 'New Caledonia',
            'NZ' => 'New Zealand',
            'NI' => 'Nicaragua',
            'NE' => 'Niger',
            'NG' => 'Nigeria',
            'NU' => 'Niue',
            'NF' => 'Norfolk Island',
            'KP' => 'North Korea',
            'MK' => 'North Macedonia',
            'NO' => 'Norway',
            'OM' => 'Oman',
            'PK' => 'Pakistan',
            'PW' => 'Palau',
            'PS' => 'Palestine',
            'PA' => 'Panama',
            'PG' => 'Papua New Guinea',
            'PY' => 'Paraguay',
            'PE' => 'Peru',
            'PH' => 'Philippines',
            'PL' => 'Poland',
            'PT' => 'Portugal',
            'PR' => 'Puerto Rico',
            'QA' => 'Qatar',
            'RE' => 'Réunion',
            'RO' => 'Romania',
            'RU' => 'Russia',
            'RW' => 'Rwanda',
            'WS' => 'Samoa',
            'SM' => 'San Marino',
            'ST' => 'Sao Tome and Principe',
            'SA' => 'Saudi Arabia',
            'SN' => 'Senegal',
            'RS' => 'Serbia',
            'SC' => 'Seychelles',
            'SL' => 'Sierra Leone',
            'SG' => 'Singapore',
            'SX' => 'Sint Maarten',
            'SK' => 'Slovakia',
            'SI' => 'Slovenia',
            'SB' => 'Solomon Islands',
            'SO' => 'Somalia',
            'ZA' => 'South Africa',
            'KR' => 'South Korea',
            'SS' => 'South Sudan',
            'ES' => 'Spain',
            'LK' => 'Sri Lanka',
            'SD' => 'Sudan',
            'SR' => 'Suriname',
            'SE' => 'Sweden',
            'CH' => 'Switzerland',
            'SY' => 'Syria',
            'TW' => 'Taiwan',
            'TJ' => 'Tajikistan',
            'TZ' => 'Tanzania',
            'TH' => 'Thailand',
            'TL' => 'Timor-Leste',
            'TG' => 'Togo',
            'TK' => 'Tokelau',
            'TO' => 'Tonga',
            'TT' => 'Trinidad and Tobago',
            'TN' => 'Tunisia',
            'TR' => 'Turkey',
            'TM' => 'Turkmenistan',
            'TC' => 'Turks and Caicos Islands',
            'TV' => 'Tuvalu',
            'UG' => 'Uganda',
            'UA' => 'Ukraine',
            'AE' => 'United Arab Emirates',
            'GB' => 'United Kingdom',
            'US' => 'United States',
            'UY' => 'Uruguay',
            'UZ' => 'Uzbekistan',
            'VU' => 'Vanuatu',
            'VA' => 'Vatican City',
            'VE' => 'Venezuela',
            'VN' => 'Vietnam',
            'YE' => 'Yemen',
            'ZM' => 'Zambia',
            'ZW' => 'Zimbabwe'
        ];
        return $countries[$countryCode] ?? null;
    }


    public static function getSeo($page = 'home', $additonal_info = '')
    {
        if ($page == 'home') {
            $data['title'] = 'Jobs in Hospitality & F&B | Hire or Get Hired | Hungry for Jobs';
            $data['description'] = 'Find top hospitality & F&B jobs globally. Employers can post unlimited jobs and find talent, while job seekers can apply for free. Join Hungry for Jobs now!';
        } elseif ($page == 'register_employee') {
            $data['title'] = 'Find jobs in ' . self::getCountry($additonal_info) . '- Hungry For Jobs';
            $data['description'] = 'Looking for a job in ' . self::getCountry($additonal_info) . ' in the hospitality, food and beverage industries? Start your job search now with Hungry For Jobs, apply now with your CV and get hired.';

        } elseif ($page == 'register_employer') {

            $data['title'] = 'Find and hire talent from ' . self::getCountry($additonal_info) . '- Hungry For Jobs';
            $data['description'] = 'Register your company now to advertise for your job vacancies, search CVs and acquire talent in the hospitality, food and beverage industries in ' . self::getCountry($additonal_info);
        } elseif ($page == 'faq') {

            $data['title'] = 'Frequently Asked Questions - Hungry For Jobs';
            $data['description'] = 'Do you need help finding a job near you in the hospitality, food and beverage industries? Check the the most frequently asked questions.';
        } elseif ($page == 'terms') {

            $data['title'] = 'Terms and Conditions - Hungry For Jobs';
            $data['description'] = "What do you need to know in order to navigate the Hungry For Jobs's website. All your rights and obligations. ";
        } elseif ($page == 'privacy') {

            $data['title'] = 'Privacy Policy - Hungry For Jobs';
            $data['description'] = "What data is collected and shared while navigating the Hungry For Jobs's website. All your rights and obligations.";
        } elseif ($page == 'contact') {
            $data['title'] = 'Contact Us - Hungry For Jobs';
            $data['description'] = "Reach us quickly by submitting your request. We attend to all inquiries and we will be in touch with you within 1 business day.";
        } elseif ($page == 'companies') {
            $data['title'] = 'Top Hiring Employers - Hungry For Jobs';
            $data['description'] = "Navigate quickly to check out who are the top employers looking for candidates in the hospitality, food and beverage industries.";
        } elseif ($page == 'search-resume') {
            $data['title'] = 'Search for Employees - Hungry For Jobs';
            $data['description'] = "Search for top talent and employees in the hospitality, food and beverage industries.";
        } elseif ($page == 'latest-jobs') {
            $data['title'] = 'Search for Jobs - Hungry For Jobs';
            $data['description'] = "Search for the latest jobs in the hospitality, food and beverage industries.";
        } elseif ($page == 'company_profile') {
            $data['title'] = 'Find jobs in ' . $additonal_info . ' - Hungry For Jobs';
            $data['description'] = "Welcome to the careers page of $additonal_info. Find a job near you in the hospitality, food & beverage industries. Register now and check vacancies now.";
        } elseif ($page == 'affiliate-program') {
            $data['title'] = 'Make Money - Hungry For Jobs';
            $data['description'] = "Become An Affiliate And Make Money.";
        } elseif ($page == 'affiliate-register') {
            $data['title'] = 'Make Money - Hungry For Jobs';
            $data['description'] = 'Become An Affiliate And Make Money.';
        }
        return $data;
    }

    public static function withdraw_request_status($status = null){
         
        $statuses = [
            'processing' => t('processing'),
            'requested' => t('requested'),
            'paypal_requested' => t('paypal_requested'),
            'approved' => t('approved'),
            'rejected' => t('rejected'),
        ];     
        
        if(!empty($status)){
            return $statuses[$status]; 
        } 
        return $statuses;
    }

    public static function referral_commission_status($status = null){
         
        $statuses = [
            'pending' => t('pending'),
            'verification_inprocess' => t('verification_inprocess'),
            'paid' => t('paid'),
            'withdraw_request' => t('withdraw_request'),
        ];     
        
        if(!empty($status)){
            return $statuses[$status]; 
        } 
        return $statuses;
    }

    public static function getwithdrawYears() {
        return [
            "2025" => "2025",
        ];
    }

    public static function getCommissionStatuses() {
        return [
            'pending' => t('pending'),
            'verification_inprocess' => t('verification_inprocess'),
            'paid' => t('paid'),
            'withdraw_request' => t('withdraw_request'),
        ];
    }

    public static function getWithdrawStatuses() {
        return [
            'processing' => t('processing'),
            'requested' => t('requested'),
            'paypal_requested' => t('paypal_requested'),
            'approved' => t('approved'),
            'rejected' => t('rejected'),
        ];
    }

    public static function get_current_month_referral_commission($affiliate_id = null)
    {
        $startDate = now()->startOfMonth();
        $endDate = now()->endOfDay();

        $affiliates = [];

        if (!empty($affiliate_id)) {
            $affiliate = User::where('user_type_id', 5)->where('is_active', 1)->where('id', $affiliate_id)->select('id')->first();

            if ($affiliate) {
                $affiliates[] = $affiliate;
            }

        } else {
            $affiliates = User::where('user_type_id', 5)->where('is_active', 1)->select('id')->get();
        }

        $results = [];

        foreach ($affiliates as $affiliate) {
            $direct_total_revenue = 0;
            $referral_affiliate_total_revenue = 0;
            $referral_affiliate_commission = 0;

            $referral_users = User::where('affiliate_id', $affiliate->id)
                ->where('user_type_id', 1)
                ->where('is_active', 1)
                ->select('id')->get();

                if ($referral_users->isNotEmpty()) {
                    foreach ($referral_users as $referral_user) {
                        $totalPayments = Payment::where('user_id', $referral_user->id)
                            ->whereBetween('created_at', [$startDate, $endDate])
                            ->sum('amount');
                        $direct_total_revenue += $totalPayments;
                    }
                }

            $referral_affiliates = User::where('user_type_id', 5)
                ->where('affiliate_id', $affiliate->id)
                ->where('is_active', 1)
                ->select('id')->get();

            foreach ($referral_affiliates as $referral_affiliate) {
                $referral_affiliate_companies = User::where('user_type_id', 1)
                    ->where('affiliate_id', $referral_affiliate->id)
                    ->where('is_active', 1)
                    ->select('id')->get();

                foreach ($referral_affiliate_companies as $referral_affiliate_company) {
                    $subAffiliatePayment = Payment::where('user_id', $referral_affiliate_company->id)
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->sum('amount');

                    $referral_affiliate_total_revenue += $subAffiliatePayment;
                }
            }
            
            $affiliate_setting = AffiliateSetting::first();

            if ($referral_affiliate_total_revenue > 0 && $affiliate_setting) {
                if ($affiliate_setting->affiliate_to_affiliate_commission_type === 'percentage') {
                    $referral_affiliate_commission += $referral_affiliate_total_revenue * ($affiliate_setting->affiliate_to_affiliate_commission_value / 100);
                } else {
                    $referral_affiliate_commission += $affiliate_setting->affiliate_to_affiliate_commission_value;
                }
            }

            $commission_amount = 0;
            if ($direct_total_revenue > 0) {
                $slot = AffiliatesCommissionSlots::where('affiliate_id', $affiliate->id)
                    ->where('min_amount', '<=', $direct_total_revenue)
                    ->where('max_amount', '>=', $direct_total_revenue)
                    ->first();

                $commission = $slot ? $slot->commission : 0;
                $commission_amount = $direct_total_revenue * ($commission / 100);
            }

            if ($direct_total_revenue > 0 || $referral_affiliate_total_revenue > 0) {
                $results[] = [
                    'affiliate_id' => $affiliate->id,
                    'my_revenue' => round($direct_total_revenue, 2),
                    'my_commission' => round($commission_amount, 2),
                    'referral_affiliate_commission' => round($referral_affiliate_commission, 2),
                    'referral_affiliate_total_revenue' => round($referral_affiliate_total_revenue, 2),
                ];
            }
        }

        return $affiliate_id ? ($results[0] ?? null) : $results;
    }

    public static function getPaymentServices() {
        return [
            "paypal" => "Paypal",
        ];
    }

    public static function adminAffiliateActivityLogDescription($data,$type)
    {
        $description = '';
        $admin_url = admin_url() . '/employer?search=' . auth()->user()->email;
        $url = "<a href='$admin_url'><strong>HungryForJobs</strong></a>";
        if($type == 'change_affiliate_status'){
            $referrer_url = admin_url() . '/affiliates?search=' . $data->email;
            $description = "The admin ". $url ." has changed status of affiliate User Name: <a href='$referrer_url'>$data->name</a> at " . date('Y-m-d H:i:s');
        }
        if($type == 'update_bank_detail'){       
            $referrer_url = admin_url() . '/affiliates?search=' . $data->email;     
            $description = "The admin ". $url ." has updated bank details of affiliate User Name: <a href='$referrer_url'>$data->name</a> at " . date('Y-m-d H:i:s');
        }
        if($type == 'send_message_to_affiliate'){
            $referrer_url = admin_url() . '/affiliates?search=' . $data['email'];
            $description = "The admin ". $url ." has sent " . $data['action'] . " to affiliate User Name: <a href='$referrer_url'>" . $data['name'] . "</a> at " . date('Y-m-d H:i:s');
        }
        if($type == 'delete_message_affiliate'){
            $referrer_url = admin_url() . '/affiliates?search=' . $data->email;
            $description = "The admin ". $url ." has deleted message to affiliate User Name: <a href='$referrer_url'>$data->name</a> at " . date('Y-m-d H:i:s');
        }
        if($type == 'add_slot'){
            $referrer_url = admin_url() . '/affiliates?search=' . $data->email;
            $description = "The admin ". $url ." has added new commission slot of affiliate User Name: <a href='$referrer_url'>$data->name</a> at " . date('Y-m-d H:i:s');
        }
        if($type == 'edit_slot'){
            $referrer_url = admin_url() . '/affiliates?search=' . $data->email;
            $description = "The admin ". $url ." has updated commission slot of affiliate User Name: <a href='$referrer_url'>$data->name</a> at " . date('Y-m-d H:i:s');
        }
        if($type == 'delete_slot'){
            $referrer_url = admin_url() . '/affiliates?search=' . $data->email;
            $description = "The admin ". $url ." has deleted commission slot of affiliate User Name: <a href='$referrer_url'>$data->name</a> at " . date('Y-m-d H:i:s');
        }
        if($type == 'affiliate_settings'){
            $description = "The admin ". $url ." has " . $data . " affiliate settings at " . date('Y-m-d H:i:s');
        }
        if($type == 'change_withdraw_request_status'){
            $description = "The admin ". $url ." has <strong>" . $data['status'] . "</strong> the withdrawal request of $" . $data['amount'] ." for affiliate User Name: <a href='" . $data['referrer_url'] . "'>" . $data['name'] . "</a> at " . date('Y-m-d H:i:s');
        }
        return $description;
    }

    public static function affiliateDescriptionData($data,$type)
    {
        $description = '';
        return $description;
    
        if($type == 'profile'){
            $description = '{{company_name}} have updated profile data:<br>'. implode(" ", $data).' at '.date('Y-m-d H:i:s');
        }
        if($type == 'bank_details'){
            $description = '{{company_name}} have updated bank details at '.date('Y-m-d H:i:s');
        }
        if($type == 'withdraw_request'){
            $description = '{{company_name}} have withdrawal request of $'. $data["amount"] .' to Admin: Hungry For Jobs at '.date('Y-m-d H:i:s');
        }
        if($type == 'message_send'){
            $description = '{{company_name}} have sent '. $data["action"] .' to Admin: Hungry For Jobs at '.date('Y-m-d H:i:s');
        }
        if($type == 'message_update'){
            $description = '{{company_name}} have '. $data["action"] .' message at '.date('Y-m-d H:i:s');
        }
        if($type == 'chat_delete'){
            $description = '{{company_name}} have deleted chat with Admin: Hungry For Jobs at '.date('Y-m-d H:i:s');
        }
        return $description;
    }

    public static function getResumeLink($id,$is_no_contact_cv = null)
    {
        $basePath = app()->environment('local') ? TESTING_USER_RESUME_PATH : LIVE_USER_RESUME_PATH;
 
        if(!empty($id)){
            
            $user = User::find($id);  
 
            if(!empty($user)){
 
                $file = $is_no_contact_cv ? $user->cv_no_contact : $user->employee_cv;
 
                if($user->is_resume_uploaded_on_aws == 1){
                    return $basePath . $file;
                }else{
                    return public_path('storage/' . $file);
                }
            }
        }

        return $basePath;
    }

    public static function getImageOrThumbnailLink($user, $is_file = null)
    {   
        $baseUrl = app()->environment('local') ? TESTING_USER_PICTURE_PATH : LIVE_USER_PICTURE_PATH;
 
        // If image is uploaded on AWS
        if ($user->is_image_uploaded_on_aws == 1) {
            $imageFile = $is_file ? $user->file : ($user->thumbnail ?: $user->file);
            return $baseUrl . $imageFile;
        }
    
        // Local storage case
        if ($is_file) {
            $imageFile = $user->file;
        } else {
            $thumbnailPath = public_path('storage/' . $user->thumbnail);
            $imageFile = (!empty($user->thumbnail) && file_exists($thumbnailPath)) ? $user->thumbnail : $user->file;
        }
    
        $finalPath = public_path('storage/' . $imageFile);
        if (!empty($imageFile) && file_exists($finalPath)) {
            return url('public/storage/' . $imageFile);
        }
    
        return url('public/storage/pictures/default.jpg');
    }

    public static function aws_cv_status($id = null)
    {
        $types = [
            '0' => t('Contact CV + No Contact CV is not uploaded on AWS, Pending State'),
            '1' => t('Contact CV + No Contact CV is uploaded on AWS, Completed State'),
            '2' => t('Contact CV is not found on local server, Fail State'),
            '3' => t('No Contact CV is not found on local server, Fail State'),
            '4' => t('Contact CV is failed to upload on AWS, Fail State'),
            '5' => t('No Contact CV is failed to upload on AWS, Fail State'),
        ];
        if ($id !== null && isset($types[$id])) {
            return $types[$id];
        }
        return $types; 
    }

    public static function aws_profile_status($id = null)
    {
        $types = [
            '0' => t('Picture + Thumbnail is not uploaded on AWS, Pending State'),
            '1' => t('Picture + Thumbnail is uploaded on AWS, Completed State'),
            '2' => t('Picture is not found on local server, Fail State'),
            '3' => t('Thumbnail is not found on local server, Fail State'),
            '4' => t('Picture is failed to upload on AWS, Fail State'),
            '5' => t('Thumbnail is failed to upload on AWS, Fail State'),
        ];
        if ($id !== null && isset($types[$id])) {
            return $types[$id];
        }
        return $types; 
    }

    public static function get_affiliate_dashboard_metrics($affiliate_id = 0)
    {
        $userQuery = User::query()
            ->where('user_type_id', 1)
            ->whereNull('deleted_at');

        $affiliateQuery = User::query()
            ->where('user_type_id', 5)
            ->whereNull('deleted_at');

        $referralQuery = ReferralCommission::query();

        if ($affiliate_id != 0) {
            $userQuery->where('affiliate_id', $affiliate_id);
            $referralQuery->where('affiliate_id', $affiliate_id);
            $affiliateQuery->where('affiliate_id', $affiliate_id);
        } else {
            $userQuery->where('affiliate_id', '!=', 0);
            $affiliateQuery->where('affiliate_id', '!=', 0);
        }

        // Users
        $number_of_referral_users = (clone $userQuery)->count();

        $current_month_referral_users = (clone $userQuery)
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();

        $last_month_referral_users = (clone $userQuery)
            ->whereYear('created_at', now()->subMonth()->year)
            ->whereMonth('created_at', now()->subMonth()->month)
            ->count();

        // Affiliates
        $number_of_referral_affiliates = (clone $affiliateQuery)->count();

        $current_month_referral_affiliates = (clone $affiliateQuery)
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();

        $last_month_referral_affiliates = (clone $affiliateQuery)
            ->whereYear('created_at', now()->subMonth()->year)
            ->whereMonth('created_at', now()->subMonth()->month)
            ->count();


        // Commissions & Revenue
        $my_commission = (clone $referralQuery)->sum('my_commission');
        $my_revenue = (clone $referralQuery)->sum('my_revenue');

        $commission_through_affiliated_user = (clone $referralQuery)->sum('commission_through_affiliated_user');
        $revenue_through_affiliated_user = (clone $referralQuery)->sum('revenue_through_affiliated_user');

        if ($affiliate_id) {
            $result = Helper::get_current_month_referral_commission($affiliate_id);
            $currentMonthReferral = $result ? [$result] : []; // wrap in array
        } else {
            $currentMonthReferral = Helper::get_current_month_referral_commission(); // already returns array of arrays
        }        
        

        
        $current_month_commission = array_sum(array_column($currentMonthReferral, 'my_commission'));
        $current_month_revenue = array_sum(array_column($currentMonthReferral, 'my_revenue'));
        $current_month_referral_affiliate_commission = array_sum(array_column($currentMonthReferral, 'referral_affiliate_commission'));
        $current_month_referral_affiliate_total_revenue = array_sum(array_column($currentMonthReferral, 'referral_affiliate_total_revenue'));
        $my_last_month_revenue = ReferralCommission::where('year', now()->subMonth()->format('Y'))
            ->where('month', now()->subMonth()->format('F'))
            ->when($affiliate_id, function ($q) use ($affiliate_id) {
                return $q->where('affiliate_id', $affiliate_id);
            })
            ->sum('my_revenue');

        $my_last_month_commission = ReferralCommission::where('year', now()->subMonth()->format('Y'))
            ->where('month', now()->subMonth()->format('F'))
            ->when($affiliate_id, function ($q) use ($affiliate_id) {
                return $q->where('affiliate_id', $affiliate_id);
            })
            ->sum('my_commission');

        $last_month_referral_affiliate_commission = ReferralCommission::where('year', now()->subMonth()->format('Y'))
            ->where('month', now()->subMonth()->format('F'))
            ->when($affiliate_id, function ($q) use ($affiliate_id) {
                return $q->where('affiliate_id', $affiliate_id);
            })
            ->sum('commission_through_affiliated_user');

        $last_month_referral_affiliate_total_revenue = ReferralCommission::where('year', now()->subMonth()->format('Y'))
            ->where('month', now()->subMonth()->format('F'))
            ->when($affiliate_id, function ($q) use ($affiliate_id) {
                return $q->where('affiliate_id', $affiliate_id);
            })
            ->sum('revenue_through_affiliated_user');

            $my_lifetime_commission = number_format($my_commission + $current_month_commission, 2);
            $my_lifetime_revenue = number_format($my_revenue + $current_month_revenue, 2);

            $affiliated_lifetime_commission = number_format($commission_through_affiliated_user + $current_month_referral_affiliate_commission, 2);
            $affiliated_lifetime_revenue = number_format($revenue_through_affiliated_user + $current_month_referral_affiliate_total_revenue, 2);
        
        return [
            'total_referral_users' => $number_of_referral_users,
            'current_month_referral_users' => $current_month_referral_users,
            'last_month_referral_users' => $last_month_referral_users,

            'total_referral_affiliates' => $number_of_referral_affiliates,
            'current_month_referral_affiliates' => $current_month_referral_affiliates,
            'last_month_referral_affiliates' => $last_month_referral_affiliates,

            'my_lifetime_revenue' => $my_lifetime_revenue,
            'my_lifetime_commission' => $my_lifetime_commission,

            'affiliated_lifetime_revenue' => $affiliated_lifetime_revenue,
            'affiliated_lifetime_commission' => $affiliated_lifetime_commission,

            'total_commission' => number_format($my_lifetime_commission + $affiliated_lifetime_commission, 2),
            'total_revenue' => number_format($my_lifetime_revenue + $affiliated_lifetime_revenue, 2),

            'my_current_month_commission' => number_format($current_month_commission, 2),
            'my_current_month_revenue' => number_format($current_month_revenue, 2),

            'affiliated_current_month_commission' => number_format($current_month_referral_affiliate_commission, 2),
            'affiliated_current_month_revenue' => number_format($current_month_referral_affiliate_total_revenue, 2),

            'total_current_month_commission' => number_format($current_month_commission + $current_month_referral_affiliate_commission, 2),
            'total_current_month_revenue' => number_format($current_month_revenue + $current_month_referral_affiliate_total_revenue, 2),

            'my_last_month_commission' => number_format($my_last_month_commission, 2),
            'my_last_month_revenue' => number_format($my_last_month_revenue, 2),

            'affiliated_last_month_commission' => number_format($last_month_referral_affiliate_commission, 2),
            'affiliated_last_month_revenue' => number_format($last_month_referral_affiliate_total_revenue, 2),

            'total_last_month_commission' => number_format($my_last_month_commission + $last_month_referral_affiliate_commission, 2),
            'total_last_month_revenue' => number_format($my_last_month_revenue + $last_month_referral_affiliate_total_revenue, 2),

            'current_month_referral_affiliate_commission' => number_format($current_month_referral_affiliate_commission, 2),
            'current_month_referral_affiliate_revenue' => number_format($current_month_referral_affiliate_total_revenue, 2),

            'last_month_referral_affiliate_commission' => number_format($last_month_referral_affiliate_commission, 2),
            'last_month_referral_affiliate_revenue' => number_format($last_month_referral_affiliate_total_revenue, 2),
        ];
    }

    function send_developer_email($view_name,$data){
 
        $body = view($view_name)->with('data', $data);
 
        $transport = (new Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))
                ->setUsername('maqibali.335@gmail.com')
                ->setPassword('hhodnelobuanyidt');
 
            // Create the Mailer using your created Transport
        $mailer = new Swift_Mailer($transport);
            
        $message2 = (new Swift_Message())
                ->setFrom(array('hungryforjobskuwait9@gmail.com' => 'Hungry For Jobs'))
                ->setTo(DEVELOPER_EMAIL)
                ->setSubject('Hungry for jobs developer issue')
                ->setBody($body, 'text/html');
        $mailer->send($message2);

        return true;
    }

}