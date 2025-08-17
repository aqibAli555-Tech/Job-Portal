<?php

namespace App\Helpers;
use App\Models\CompanyPackages;
use App\Models\OptionalSelectedEmails;
use App\Models\Package;
use App\Models\PaymentMethod;
use App\Models\Post;
use App\Models\PostRemaining;
use App\Models\User;
use Carbon\Carbon;
use Session;

class Subscription
{
    public static function update_subscription($company_id = null)
    {
   
         self::expire_post();
        $currentDate = Carbon::now();
        $date = $currentDate->format('Y-m-d H:i:s');
        $updated_at = $currentDate->format('Y-m-d');
        $all_packages = CompanyPackages::get_expire_user_packages($company_id);

        if (!empty($all_packages)) {
            foreach ($all_packages as $key => $value) {
                $user = User::find($value->employer_id);
                if (!empty($user)) {
                    if ($value)
                        if ($value->package_type == 'yearly') {
                            $yearly_package_start_date = Carbon::parse($value->yearly_package_expire_date);
                            $yearly_package_expire_date = $yearly_package_start_date->format('Y-m-d H:i:s');
                            if ($date >= $yearly_package_expire_date) {
                                self::send_subscription_request_to_tap($value, $user, $date, 'yearly');
                            } else {
                                // first cancel the old contact cards and posts
                                $data_array['company_package_id'] = $value->id;
                                $data_array['employer_id'] = $value->employer_id;
                                $data_array['package_id'] = $value->package_id;
                                $data_array['is_auto_renew'] = false;
                                $data_array['package_type'] = 'yearly';
                                Helper::update_post_and_contact_card_counter($data_array);
                                // auto add the new contact and posts
                                $data_array_update['company_package_id'] = $value->id;
                                $data_array_update['employer_id'] = $value->employer_id;
                                $data_array_update['package_id'] = $value->package_id;
                                $data_array_update['is_auto_renew'] = true;
                                $data_array_update['package_type'] = 'yearly';
                                $data_array_update['with_payment'] = false;
                                Helper::update_post_and_contact_card_counter($data_array_update);
                            }
                        } else {
                            // expire all unlock and posts
                            self::send_subscription_request_to_tap($value, $user, $date, 'monthly');
                        }
                }
            }
        }
    }

    private static function send_subscription_request_to_tap($value, $user, $date, $type = 'monthly')
    {
        //if hit is greater than 29 then must be hit after one week
        if ($value->sub_renew_hit == 1 && $value->sub_hit_count > 29) {
            $currentDate = Carbon::now();
            if ($value->updated_at->lt($currentDate->subWeek())) {
                $valid_request = true;
            } else {
                $valid_request = false;
            }
        } else {
            $valid_request = true;
        }
        if ($valid_request) {
            $data_array['company_package_id'] = $value->id;
            $data_array['employer_id'] = $value->employer_id;
            $data_array['package_id'] = $value->package_id;
            $data_array['is_auto_renew'] = false;
            $data_array['package_type'] = $type;
            Helper::update_post_and_contact_card_counter($data_array);
            self::SendEmailSubcriptionExpired($user, $value->package_id);

            $check_user_has_another_package_subscribed_or_not = CompanyPackages::where('employer_id', $user->id)->where('package_id', '!=', 5)->where('is_package_expire', 0)->where('package_expire_date', '>=', $date)->first();
            if (!empty($check_user_has_another_package_subscribed_or_not) && $check_user_has_another_package_subscribed_or_not->id != $value->id) {
                $companyPackage = CompanyPackages::find($value->id);
                $companyPackage->sub_renew_hit = 0;
                $companyPackage->save();
                return true;
            }


            if ($value->package_id != 5) {

                // check latest package subscription for renew package
                if ($type == 'monthly') {
                    $latest_package = CompanyPackages::where('employer_id', $user->id)->where('package_id', '!=', 5)->where('package_expire_date', '<=', $date)->orderBy('id', 'desc')->first();
                } else {
                    $latest_package = CompanyPackages::where('employer_id', $user->id)->where('package_id', '!=', 5)->where('yearly_package_expire_date', '<=', $date)->orderBy('id', 'desc')->first();
                }


                if (!empty($latest_package) && $latest_package->package_id == $value->package_id) {
                    $data['user'] = $user;
                    $captured = false;
                    $postResponse = '';
                    $url = url('update_activity_log_for_expire_package');

                    if (empty($user->save_card_id) || empty($user->tap_customer_id || $value->sub_hit_count > 29)) {
                        CompanyPackages::where('employer_id', $user->id)->update(['sub_renew_hit' => 0, 'is_package_expire' => 1, 'is_subscription_cancelled' => 1]);
                        if(!empty($user) && $latest_package->is_package_expire == 0) {
                            $name = $user->name;
                            $company_email = $user->email;
                            $profile_url = admin_url() . '/employer?search=' . $company_email;
                            $package_data = Package::where('id', $latest_package->package_id)->first();
                            $package_name = (!empty($package_data->name)) ? $package_data->name : '';
                            $package_price = $package_data->price;
                            $description = "A Company Name: <a href='$profile_url'>$name</a> " . $package_name . " Subscription month has been expired on:$date <br> Pakcage Price: $ $package_price";
                            Helper::activity_log($description,0, $latest_package->package_expire_date);
                            $package_data['date'] = $date;
                            $package_data['name'] = $package_name;
                            $package_data['price'] = $package_price;
                            $package_data['cron'] = 1;
                            $companyDescription = Helper::companyDescriptionData($package_data, 'expire_package');
                            if(!empty($companyDescription)){
                                Helper::activity_log($companyDescription,$user->id);
                            }
                        }
                        return false;
                    }

                    $data['token_details'] = Tap::create_token($data);
                    if(empty($data['token_details']->id)){
                        $view = 'emails.tap_token_issue';
                        $email_data['user_data'] = $user;
                        $url = url('/');
                        $email_data['url'] = $url;
                        $helper = new Helper();
                        $helper->send_developer_email($view,$email_data);
                    }
                    
                    $data['package'] = Package::where('id', $latest_package->package_id)->first();
                    $data['payment'] = PaymentMethod::where('id', 3)->first();
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
                    if ($expire_date <= $date) {

                        $response = Tap::create_charge_subscription($data);
                        $postResponse = json_encode($response);

                        if (!empty($response->status)) {
                            if ($response->status == 'CAPTURED') {
                                $captured = true;
                                $data_array['is_auto_renew'] = true;
                                $data_array['package_id'] = $latest_package->package_id;
                                $data_array['package_type'] = $type;
                                $data_array['with_payment'] = true;
                                $data_array['transaction_id'] = $response->id;

                                Helper::update_post_and_contact_card_counter($data_array);
                                $user_package_data = CompanyPackages::where('id', $value->id)->first();
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
                                self::sendsubscriptionemail($packge_data, $user, 'Subscription Renew Successfully', $package_type_email, $cc);
                                Tap::create_log($user->id, $url, $data_array['package_type'] . ' Subscription update success', 'POST', 'Success Monthly subscription successfully renew for this user' . $user->name, $postResponse, 'subscriptionmonthly', '200');
                            }
                        }
                    }
                    if (!$captured) {
                        $user_package_data = CompanyPackages::where('id', $value->id)->first();
                        if (!empty($user_package_data)) {
                            if ($user_package_data->sub_hit_count == 55) {
                                $user_package_data->sub_renew_hit = 2;
                            } else {
                                $user_package_data->sub_renew_hit = 1;
                            }
                            $user_package_data->sub_hit_count = $user_package_data->sub_hit_count + 1;
                            $user_package_data->data_json = $postResponse;
                            $user_package_data->save();
                        }
                        $message = '';
                        if (!empty($response->response->message)) {
                            $message = $response->response->message;
                        } else {
                            $message = $response->errors[0]->description;
                        }
                        $data_array['is_auto_renew'] = false;
                        $data_array['package_id'] = $latest_package->package_id;
                        $data_array['payment_failed'] = $message;
                        Helper::update_post_and_contact_card_counter($data_array);
                        $packge_data = Package::where('id', $latest_package->package_id)->first();
                        Tap::create_log($user->id, $url, 'Monthly Subscription update error', 'POST', 'Error! Monthly subscription is Not renew for this user' . $user->name, $postResponse, 'subscriptionmonthly', '400');
                    }

                }
            }
        }
    }

    private static function SendEmailSubcriptionExpired($company_data, $package_id)
    {
        if (!empty($company_data)) {
            $packeg_data = Package::where('id', $package_id)->first();
            $data['email'] = $company_data->email;
            $data['subject'] = 'Package Subscription Has Expired';
            $data['myName'] = $company_data->name;
            $data['myurl'] = url('account/upgrade');
            $data['package_name'] = $packeg_data->short_name;
            $data['view'] = 'emails/send_email_subcription_expired';
            $data['header'] = 'Package Subscription Has Expired';
            $helper = new Helper();
            $helper->send_email($data);
        }
    }

    public static function sendsubscriptionemail($packeg_data, $company_data, $subject, $package_type_email, $cc = null)
    {
        if ($package_type_email == 'Annually') {
            $price = $packeg_data->yealry_price;
        } else {
            $price = $packeg_data->price;
        }
        $data['email'] = $company_data->email;
        $data['subject'] = $subject;
        $data['from'] = getenv('MAIL_USERNAME');
        $data['myName'] = $company_data->name;
        $data['package_name'] = $packeg_data->short_name;
        $data['price'] = $price . ' ' . $packeg_data->currency_code;
        $data['package_type'] = $package_type_email;
        $data['header'] = 'Renew Package Subscription';
        $data['view'] = 'emails/subscription_email';
        $data['cc'] = $cc;
        $helper = new Helper();
        $response = $helper->send_email($data);
    }

    public static function expire_post()
    {
        $currentDate = Carbon::now();
        $date = $currentDate->format('Y-m-d H:i:s');

        $posts = PostRemaining::whereNotNull('post_id')->where('is_post_expire', 0)->where('post_expire_date_time', '<=', $date)->get();

        foreach ($posts as $item) {
            $post = Post::find($item->post_id);
            if (!empty($post)) {
                $post->is_post_expire = 1;
                $post->archived = 1;
                $post->archived_at = $item->post_expire_date_time;
                $post->save();

                $last_post = PostRemaining::find($item->id);
                $last_post->is_post_expire = 1;
                $last_post->updated_at = date('Y-m-d');
                $last_post->save();

                $company_name = $post->company_name;
                $title = $post->title;
                $url = UrlGen::post($post);
                $user = User::find($item->employer_id);
                $name = $user->name;
                $company_email = $user->email;
                $profile_url = admin_url() . '/employer?search=' . $company_email;
                $description = "A Post Name: $title Has been expired on $item->post_expire_date_time.<br>Company Name:<a href='$profile_url'>$company_name</a>.<br>Post Url:<a href='$url'>$url</a>";
                Helper::activity_log($description,0, $item->post_expire_date_time);
                self::sendPostExpireEmail($post, $user);
            }
        }
    }


    public static function sendPostExpireEmail($post, $company_data)
    {
        $data['email'] = $company_data->email;
        $data['subject'] = "Your ($post->title) Job Expired";
        $data['from'] = getenv('MAIL_USERNAME');
        $data['myName'] = $company_data->name;
        $data['header'] = 'Your Job Post has expired';
        $data['post_title'] = $post->title;
        $data['view'] = 'emails/post_expire_email';
        $data['cc'] = '';
        $helper = new Helper();
        $response = $helper->send_email($data);
    }

}
