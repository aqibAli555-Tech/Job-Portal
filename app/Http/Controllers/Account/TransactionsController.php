<?php

namespace App\Http\Controllers\Account;

use App\Helpers\EmailHelper;
use App\Helpers\Helper;
use App\Helpers\Subscription;
use App\Helpers\Tap;
use App\Models\AffiliateSetting;
use App\Models\Company;
use App\Models\ContactCardsRemaining;
use App\Models\OptionalSelectedEmails;
use App\Models\Package;
use App\Models\PackageCancelReason;
use App\Models\Payment;
use App\Models\Payment as PaymentModel;
use App\Models\PaymentMethod;
use App\Models\PostRemaining;
use App\Models\User;
use App\Models\UserCancelledPackages;
use Carbon\Carbon;
use App\Models\CompanyPackages;
use DB;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Torann\LaravelMetaTags\Facades\MetaTag;

class TransactionsController extends AccountBaseController
{
    private $perPage = 10;

    public function __construct()
    {
        parent::__construct();

        $this->perPage = (is_numeric(config('settings.listing.items_per_page'))) ? config('settings.listing.items_per_page') : $this->perPage;
    }

    public function index()
    {
        if (!Helper::check_permission(11)) {
            flash(t("You do not have permission to access this module"))->error();
            return redirect('/');
        }

        if (auth()->user()->user_type_id == 1 && empty(auth()->user()->parent_id)) {
            flash(t("Please change account to child company to view this page."))->error();
            return redirect('/');
        }
        $data = [];
        $data['cancel_reason'] = PackageCancelReason::get_all_cancel_reasons();
        $data['packages'] = Package::whereHas('payments', function($query) {
            $query->where('user_id', auth()->user()->id);
        })->get();
        view()->share('pagePath', 'transactions');

        $data['trans'] = Payment::with('Package')->where('user_id', auth()->user()->id)->paginate($this->perPage);
        // Meta Tags
        view()->share([
            'title' => t('My Transactions'),
            'description' => t('My Transactions'),
            'keywords' => t('My Transactions'),
            // Add more variables as needed
        ]);
        return appView('account.transactions', $data);
    }

    public function ajax(Request $request)
    {
        $data = [];
        $transactions = Payment::with('Package')
            ->where('user_id', auth()->user()->id);

        if (!empty($request->get('search_new'))) {
            $search = $request->get('search_new');
            $transactions = $transactions->where(function ($query) use ($search) {
                $query->where('amount', 'LIKE', "%{$search}%");
            });
        }
        if (!empty($request->get('package'))) {
            $packageId = $request->get('package');
            $transactions = $transactions->where(function ($query) use ($packageId) {
                $query->where('package_id', $packageId);
            });
        }

        if (!empty($request->get('search_date'))) {
            $searchDate = $request->get('search_date');
            $transactions = $transactions->whereDate('created_at', '=', $searchDate);
        }

        $recordsFiltered = $transactions->count();
        $recordsTotal = $transactions->count();
        $transactions = $transactions->orderByDesc('id')->get();

        if ($transactions->count() > 0) {
            $start = $request->get('start', 0);
            foreach ($transactions as $key => $transaction) {
                $data[$key][] = $start + $key + 1;;
                $data[$key][] = $transaction->package->name ?? '';
                if(auth()->user()->affiliate_id != 0 && !empty($transaction->discount_value)){
                        $discount = $transaction->discount_type === 'percentage' ? $transaction->discount_value . '%' : '$' . $transaction->discount_value;
                        $data[$key][] = '<strong>Package Price : </strong>USD ' . $transaction->package_amount .'<br><strong>Discount : </strong>' . $discount . '<br><strong>Package price after discount : </strong>USD ' . $transaction->amount;
                }else{
                    $data[$key][] = 'USD ' . $transaction->amount;
                }
                $data[$key][] = $transaction->created_at->format('d-M-Y');
                $invoice = url('account/transactions/invoice/' . $transaction->id);
                $data[$key][] =
                    '<div class="btn-group" role="group" aria-label="Action">
                            <a class="btn btn-primary btn-sm" target="#" href="' . $invoice . '"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;View Invoice</a>
                    </div>';
            }
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(
            [   'draw' => $request->get('draw'),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data]);
        die;
    }


    public function upgrade()
    {
        if (!Helper::check_permission(12)) {
            flash(t("You do not have permission to access this module"))->error();
            return redirect('/');
        }

        if (auth()->user()->user_type_id == 1 && empty(auth()->user()->parent_id)) {
            flash(t("Please change account to child company to view this page."))->error();
            return redirect('/');
        }
        $payment = Payment::where('user_id', auth()->user()->id)->where('package_id', 5)->first();

        $excludedId = 5;
        $data = Package::orderBy('lft', 'asc')->whereNotIn('id', [$excludedId])->get();

        $remaning_days = 0;
        $package_expire_date = CompanyPackages::get_latest_package_subscribed();
        if (!empty($package_expire_date)) {
            // Pass true for absolute value
            $remaning_days = helper::calculate_remaining_days($package_expire_date);
        }
        // Meta Tags
        view()->share([
            'title' => t('Upgrade Account'),
            'description' => t('Upgrade Account'),
            'keywords' => t('Upgrade Account'),
            'remaning_days' => $remaning_days,
            // Add more variables as needed
        ]);
        return view('account.upgrade')->with('data', $data);
    }

    public function credentials($id)
    {
        // Meta Tags
        MetaTag::set('title', 'Credit Card Credentials');
        MetaTag::set('description', 'Credit Card Credentials ' . config('settings.app.app_name'));
        $data['type'] = !empty(request()->get('type')) ? request()->get('type') : 'monthly';
        $data['user'] = User::where('id', auth()->user()->id)->first();
        $data['package'] = Package::where('id', $id)->first();
        $data['payment'] = Paymentmethod::where('id', 3)->first();
        $data['error'] = lurl('account/credentials/tappayment-error');
        $data['redirect'] = lurl('account/credentials/tappayment-redirect/?package_id=' . $id . '&type=' . $data['type']);
        $data['success'] = lurl('account/credentials/tappayment-success');
        $data['threeDSecure'] = true;
        $data['save_card'] = true;
        $data['customer_initiated'] = true;
        $data['payment_agreement_id'] = '';
        $response = Tap::create_charge($data);

        $file = auth()->user()->id . '-' . date('Y-m-d') . '-' . time() . rand() . '_file.json';
        $destinationPath = public_path() . "/chargeResponse/";
        if (!is_dir($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
        File::put($destinationPath . $file, json_encode($response));
        if (!empty($response->transaction->url)) {
            return redirect($response->transaction->url);
        } else {
            flash(t("Payment Failed. Please Contact Admin."))->error();
            return redirect('/account/upgrade');
        }
    }

    public function tappayment()
    {

        Tap::create_log(auth()->user()->id, lurl('tappayment'), 'Tap payment redirection', 'POST', json_encode(request()), 'Tap payment redirection', 'tappayment', 200);

        if (auth()->user()->user_type_id == 2) {
            flash(t("Permission error.."))->error();
            return redirect('/');
        }

        $data = Tap::payemntDetails(request()->get('tap_id'));
        $package_type = request()->get('type');

        $file = request()->get('tap_id') . '-' . time() . rand() . '_file.json';
        $destinationPath = public_path() . "/tapresponse/";
        if (!is_dir($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
        File::put($destinationPath . $file, json_encode($data));

        if (!empty($data) && $data->status == 'CAPTURED') {
            if(empty($data->card->id) || empty($data->customer->id) || empty($data->payment_agreement->id)){
                $email_data['email'] = 'raja.aqibali@gmail.com';
                $email_data['subject'] = 'Tap Payment Issue';
                $email_data['view'] = 'emails.tap_payment_issue';
                $email_data['header'] = 'Tap Payment Issue';
                $email_data['data'] = $data;
                $email_data['user_data'] = auth()->user();
                $helper = new Helper();
                $response = $helper->send_email($email_data);
                $email_data['email'] = 'mfaizan.javaid786@gmail.com';
                $helper = new Helper();
                $response = $helper->send_email($email_data);
            }
            $package_id = request()->get('package_id');
            $user_id = auth()->user()->id;
            $packeg_data = Package::where('id', $package_id)->first();
            if ($package_type == 'yearly') {
                $price_package = $packeg_data->yearly_price;
            } else {
                $price_package = $packeg_data->price;
            }
            $package_amount = $price_package;
            $affiliate_setting = AffiliateSetting::first();
            $discount_type = null;
            $discount_value = null;
            if(auth()->user()->affiliate_id != 0 && $affiliate_setting){
                $discount_value = $affiliate_setting->package_discount_value;
                $discount_type = $affiliate_setting->package_discount_type;

                if($discount_type === 'fixed'){
                    $price_package = $price_package - $discount_value;
                }else{
                    $price_package = $price_package - ($price_package * $discount_value / 100);
                }
            }

            $price_package = max($price_package, 0);

            $PaymentCreate = array(
                'user_id' => $user_id,
                'payment_method_id' => 3,
                'active' => 1,
                'transaction_id' => $data->id,
                'package_id' => $package_id,
                'amount' => $price_package,
                'package_amount' => $package_amount,
                'discount_type' => $discount_type,
                'discount_value' => $discount_value,
                'important' => 1,
                'package_type' => $package_type,
            );

            // $insertedId = PaymentModel::insertGetId($PaymentCreate);
            $inserted = PaymentModel::create($PaymentCreate);
            $insertedId = $inserted->id;            
            $UserCreate = array(
                'save_card_id' => $data->card->id,
                'tap_customer_id' => $data->customer->id,
                'tap_agreement_id' => !empty($data->payment_agreement->id)?$data->payment_agreement->id:'',
            );
            User::where('id', $user_id)->update($UserCreate);
            Helper::update_counter($user_id, $packeg_data, $insertedId, $package_type);
            $company_name = auth()->user()->name;
            $profile_url = admin_url() . '/employer?search=' . auth()->user()->email;
            if(!empty($inserted->discount_value)){
                $referral_by = User::find(auth()->user()->affiliate_id);
                $referrer_url = admin_url() . '/affiliates?search=' . $referral_by->email;
                $referral_data = '<br><strong>Referral By: </strong> <a href="'. $referrer_url. '">'. $referral_by->name. '</a>';
                $discount = $inserted->discount_type === 'percentage' ? $inserted->discount_value . '%' : '$' . $inserted->discount_value;
                $description = "A company Name: <a href='$profile_url'>$company_name</a>  subscribe a Premium Package :$packeg_data->name  <br>Price: $ $packeg_data->price <br>Discount: $discount <br>Package price after discount: $ $inserted->amount  $referral_data";
                $package_data['name'] = $packeg_data->name;
                $package_data['price'] = $packeg_data->price;
                $package_data['discount'] = $discount;
                $package_data['after_discount'] = $inserted->amount;
                $companyDescription = Helper::companyDescriptionData($package_data, 'subscribe_with_discount');
            }else{
                $description = "A company Name: <a href='$profile_url'>$company_name</a>  subscribe a Premium Package :$packeg_data->name and Price: $ $packeg_data->price";
                $package_data['name'] = $packeg_data->name;
                $package_data['price'] = $packeg_data->price;
                $companyDescription = Helper::companyDescriptionData($package_data, 'subscribe_without_discount');
            }
            Helper::activity_log($description);
            if(!empty($companyDescription)){
                Helper::activity_log($companyDescription,auth()->user()->id);
            }
            if ($package_type == 'yearly') {
                $package_type_email = 'Annually';
            } else {
                $package_type_email = 'Monthly';
            }
            $cc = '';
            if (OptionalSelectedEmails::check_selected_email(5, auth()->user()->id)) {
                $cc = auth()->user()->optional_emails;
            }

            $this->sendsubscriptionemail($packeg_data, $package_type_email, $cc, $package_data);
            if(!empty($inserted->discount_value)){
                $email_data['referral_by'] = User::find(auth()->user()->affiliate_id);
                $email_data['company_name'] = auth()->user()->name;
                $email_data['package'] = $package_data;
                $affiliate_setting = AffiliateSetting::first();
                if ($affiliate_setting) {
                    if ($affiliate_setting->package_discount_type == 'percentage') {
                        $email_data['package_discount'] = $affiliate_setting->package_discount_value . '%';
                    } else {
                        $email_data['package_discount'] = '$' . number_format($affiliate_setting->package_discount_value, 2);
                    }
                }
                EmailHelper::sendEmailToReffererForPackageBuy($email_data);
                $firstAffiliate = User::where('user_type_id',5)->where('id',$email_data['referral_by']->affiliate_id)->first();
                if($firstAffiliate){
                    $email_data['referral_affiliate_name'] = $email_data['referral_by']->name;
                    $email_data['referral_by'] = $firstAffiliate;
                    EmailHelper::sendEmailToReffererAffiliateForPackageBuy($email_data);
                }
            }

            Tap::create_log(auth()->user()->id, lurl('account/credentials/tappayment-success'), 'Tap payment Success', 'POST', json_encode(request()), 'Payment success with subscription', 'tappayment-success', 200);
            return redirect('account/credentials/tappayment-success');

        } else {
            Tap::create_log(auth()->user()->id, lurl('account/credentials/tap-payment-error'), 'Tap payment error', 'POST', json_encode(request()), 'Payment Not captured.', 'tappayment-error', 200);
            return redirect('account/credentials/tap-payment-error');
        }
    }

    public function tappaymentsuccess()
    {
        flash(t("Account upgraded successfully"))->success();
        if (!empty(Session::get('to_post_job'))) {
            session()->forget('to_post_job');
            return redirect('/posts/create');
        } else {
            return redirect('/profile/' . auth()->user()->id);
        }
    }

    public function tappaymenterror()
    {
        flash(t("Account not updated Please contact admin"))->error();
        return redirect('/profile/' . auth()->user()->id);
    }

    public function paymentFree(Request $request)
    {
        $id = $request->get('id');
        $freepackage = Package::where('price', '0.00')->first()->toArray();
        $packeg_data = Package::where('price', '0.00')->first();

        $old_payment = PaymentModel::where('user_id', auth()->user()->id)->first();
        if (!empty($old_payment) && $old_payment->package_id != 5) {
            flash(t("A premium package is already activated"))->error();
            return redirect('/account/upgrade');
        }


        $payment_data = array();

        if (!empty($freepackage['id'])) {
            $payment_data = PaymentModel::where('user_id', auth()->user()->id)->where('package_id', $freepackage['id'])->first();
        }
        $valid_for_subscription = false;
        if (empty($payment_data)) {
            $valid_for_subscription = true;
        } else {
            $check_valid_date = false;
            $todatDate = date('Y-m-d');
            $latest_user_package = CompanyPackages::get_latest_package_details();
            if (!empty($latest_user_package)) {
                if ($latest_user_package->package_expire_date <= $todatDate && $latest_user_package->is_package_expire == 1) {
                    $check_valid_date = true;
                }
            }

            if ($check_valid_date) {
                $valid_for_subscription = true;
            } else {
                $valid_for_subscription = false;
            }
        }

        if (!empty($valid_for_subscription)) {
            $today = date('Y-m-d');
            $data = Package::where('id', $request->get('id'))->first();
            $currentDate = Carbon::now(); // Get the current date and time
            $newDate = $currentDate->addDays(30); // Add 1 year to the current date
            // You can format the new date as needed
            $lastDate = $newDate->format('Y-m-d');
            $PaymentCreate = array(
                'user_id' => auth()->user()->id,
                'payment_method_id' => 2,
                'active' => 1,
                'package_id' => $data->id,
                'important' => '',
            );
            $insertedId = PaymentModel::insertGetId($PaymentCreate);
            Helper::update_counter(auth()->user()->id, $packeg_data, $insertedId);

            $cc = '';
            if (OptionalSelectedEmails::check_selected_email(5, auth()->user()->id)) {
                $cc = auth()->user()->optional_emails;
            }

            $this->sendsubscriptionemail($packeg_data, 'Monthly', $cc);
            flash(t("Account upgraded successfully"))->success();
            $company_name = auth()->user()->name;
            $profile_url = admin_url() . '/employer?search=' . auth()->user()->email;
            $description = "A company Name: <a href='$profile_url'>$company_name</a> subscribe a Free package";
            Helper::activity_log($description);
            if (!empty(Session::get('to_post_job'))) {
                session()->forget('to_post_job');
                return redirect('/posts/create');
            } else {
                return redirect('/profile/' . auth()->user()->id);
            }
        } else {
            flash(t("The FREE package can only be bought once"))->error();
            return redirect('/account/upgrade');

        }
    }

    public function sendsubscriptionemail($packeg_data, $package_type = 'Monthly', $cc = null, $package_data = null)
    {
        if ($package_type == 'Annually') {
            $price = $packeg_data->yealry_price;
        } else {
            $price = $packeg_data->price;
        }
        $data['email'] = auth()->user()->email;
        $data['subject'] = 'Subscribed Successfully';
        $data['myName'] = auth()->user()->name;
        $data['package_name'] = $packeg_data->short_name;
        $data['price'] = $price . ' ' . $packeg_data->currency_code;
        $data['package_type'] = $package_type;
        $data['view'] = 'emails.subscription_email';
        $data['cc'] = $cc;
        $data['header'] = 'Package Subscription';

        if (!empty($package_data) && !empty($package_data['discount'])) {
            $data['discount'] = $package_data['discount'];
        }

        if (!empty($package_data) && !empty($package_data['after_discount'])) {
            $data['after_discount'] = number_format($package_data['after_discount']) . ' ' . $packeg_data->currency_code;
        }

        $helper = new Helper();
        $response = $helper->send_email($data);
    }

    public function cancel_subscription(Request $request)
    {
        if (!Helper::check_permission(6)) {
            flash(t("You do not have permission to access this module"))->error();
            return redirect('/');
        }


        $response = $this->cancel_user_auto_subscription();
        if ($response) {
            $latest_package = CompanyPackages::get_latest_package_details();
            if (!empty($latest_package)) {
                $package_details = Package::find($latest_package->package_id);
                if (!empty($package_details)) {
                    $package_name = $package_details->name;
                    $package_price = $package_details->price;
                }

            }


            $data = [
                'package_id' => $latest_package->package_id,
                'user_id' => auth()->user()->id,
                'cancel_reason_id' => $request->post('cancel_reason'),
            ];
            UserCancelledPackages::create($data);
            $company_name = auth()->user()->name;
            $profile_url = admin_url() . '/employer?search=' . auth()->user()->email;
            $PackageCancelReason = PackageCancelReason::get_cancel_reasons_with_id($request->post('cancel_reason'));
            $PackageCancelReasontitle = !empty($PackageCancelReason->title) ? $PackageCancelReason->title : '';
            $description = "A company Name: <a href='$profile_url'>$company_name</a> subscription has been cancel.Package Name =" . $package_name . ' and Package Price =$' . $package_price . ' Cancel Reason: ' . $PackageCancelReasontitle;

            Helper::activity_log($description);

            $cc = '';
            if (OptionalSelectedEmails::check_selected_email(10, auth()->user()->id)) {
                $cc = auth()->user()->optional_emails;
            }


            $this->sendcancelsubscriptionemail($cc);
            flash("Subscription Cancel successfully")->success();
            return redirect()->back();
        } else {
            flash("Cancel Subscription Fail")->error();
            return redirect()->back();
        }
    }

    private function cancel_user_auto_subscription()
    {
        $Update = array(
            'save_card_id' => '',
            'tap_customer_id' => '',
            'tap_agreement_id' => '',
        );
        User::where('id', auth()->user()->id)->update($Update);
        return true;
    }

    public function company_cancel_subscription()
    {
        if (!Helper::check_permission(6)) {
            flash(t("You do not have permission to access this module"))->error();
            return redirect('/');
        }
        $this->sendcancelsubscriptionemail();
        if (!empty(auth()->user()->tap_subscription_id)) {
            $response = Tap::cancel_subscription(auth()->user()->tap_subscription_id);
            if ($response) {
                $company_name = auth()->user()->name;
                $profile_url = admin_url() . '/employer?search=' . auth()->user()->email;
                $description = "A company Name: <a href='$profile_url'>$company_name</a>  cancel subscription";
                Helper::activity_log($description);
                $this->sendcancelsubscriptionemail();
                flash("Subscription Cancel successfully")->success();
                return redirect()->back();
            } else {
                flash("Cancel Subscription Fail")->error();
                return redirect()->back();
            }
        } else {
            flash("Subscription id not found")->error();
            return redirect()->back();
        }
    }

    public function sendcancelsubscriptionemail($cc = null)
    {
        $data['email'] = auth()->user()->email;
        $data['subject'] = 'Cancel Package subscription';
        $data['myName'] = auth()->user()->name;
        $data['content'] = "We're sorry to see you have cancelled your subscription. Since you have already made payment for a 30 day period prior to your cancellation, you will still have access to our advanced search features until expiry of your session.";
        $data['view'] = 'emails.cancel_subscription_email';
        $data['header'] = 'Cancel Package subscription';
        $data['cc'] = $cc;
        $helper = new Helper();
        $response = $helper->send_email($data);
    }

    public function track_company_package_details()
    {
        $all_packages = CompanyPackages::get_subscribed_package_details();
        if (!empty($all_packages)) {
            foreach ($all_packages as $key => $value) {
                $name = json_decode($value->name);
                $value->name = $name->en;
                $value->remaining_days = helper::calculate_remaining_days($value->package_expire_date);
                $all_packages[$key] = $value;
            }
        }
        echo json_encode($all_packages);
        die();

    }

    public function update_subscription_ajax()
    {
        if (auth()->user()->is_admin == 1) {
            $company_id = null;
        } else {
            $company_id = auth()->user()->id;
        }
        Subscription::update_subscription($company_id);
        echo json_encode(['message' => 'Subscription has been updated successfully']);
        die();
    }

    public function invoice($id)
    {
        if (!Helper::check_permission(11)) {
            flash(t("You do not have permission to access this module"))->error();
            return redirect('/');
        }

        if (auth()->user()->user_type_id == 1 && empty(auth()->user()->parent_id)) {
            flash(t("Please change account to child company to view this page."))->error();
            return redirect('/');
        }
        $data = [];

        $data['invoice'] = Payment::with('Package')->where('id', $id)->first();
        $data['company_data'] = Company::where('c_id', auth()->user()->id)->first();
        // Meta Tags
        view()->share([
            'title' => t('Invoice'),
            'description' => t('Invoice'),
            'keywords' => t('Invoice'),
            // Add more variables as needed
        ]);
        return appView('account.invoice', $data);
    }
}
