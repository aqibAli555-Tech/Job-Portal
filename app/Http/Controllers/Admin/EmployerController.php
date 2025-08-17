<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Date;
use App\Helpers\Helper;
use App\Http\Controllers\Auth\Traits\VerificationTrait;
use App\Http\Requests\Admin\Request;
use App\Models\Applicant;
use App\Models\City;
use App\Models\Company;
use App\Models\CompanyPackages;
use App\Models\ContactCardsRemaining;
use App\Models\Country;
use App\Models\Payment;
use App\Models\Post;
use App\Models\PostRemaining;
use App\Models\Unlock;
use App\Models\User;
use Carbon\Carbon;

class EmployerController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    use VerificationTrait;

    // public function index(Request $request)
    // {
    //     $users = User::get_employees($request, 1);
    //     return view('admin.employer.index', compact('users'));
    // }
 

   public function index(Request $request)
   {
       $title = 'Employer';
       $breadcumbs = [
           [
               'title'=> 'Dashboard',
               'link'=> admin_url('dashboard')
           ],
           [
               'title'=> 'Employer',
               'link'=> 'javascript:void(0)'
           ]
       ];

       $affiliates = User::where('user_type_id',5)->get();
       $affiliate_id = (!empty($request->affiliate_id))?$request->affiliate_id:'';
       $filter = (!empty($request->filter))?$request->filter:'';
       return view('admin.employer.index', compact('title','breadcumbs','affiliates','affiliate_id','filter'));
   }

    public function ajax(Request $request)
    {
        $data = [];
        $users = User::get_employees($request, 1);
        $filtered = User::get_employees_filter_count($request, 1);
        $employers_count = User::get_employees_count(1);

        if (!empty($request->get('current_subscription_users'))) {
            $users = $users->map(function ($user) {
                $expireDate = CompanyPackages::get_latest_package_subscribed($user->id);
                $user->remaining_days = !empty($expireDate)
                    ? helper::calculate_remaining_days_with_time($expireDate)
                    : 999999;
                return $user;
            })->sortBy('remaining_days')->values();
        }

        if ($users->count() > 0){
            foreach ($users as $key => $user){
                $data[$key][] = '<div class="pt-1 text-center"><input type="checkbox" name="employee_ids" class="checkbox" onclick="SingletoggleCheckbox(this)" value="'.$user->id.'"></div>';
                
                $remaining_subscription_days = '';
                $is_cancelled = '';
                if (!empty($request->get('current_subscription_users'))) {
                    $package_expire_date = CompanyPackages::get_latest_package_subscribed($user->id);
                    if (!empty($package_expire_date)) {
                        $remaining_days = helper::calculate_remaining_days_with_time($package_expire_date);
                        $remaining_subscription_days = '<br><span class="badge text-bg-primary"><strong>Remaining Subscription Days:</strong> ' .  $remaining_days . '</span>';
                    }
                    $check_subscription_cancel = User::find($user->id);
                    if(empty($check_subscription_cancel->save_card_id) && empty($check_subscription_cancel->tap_customer_id) && empty($check_subscription_cancel->tap_agreement_id)){
                        $is_cancelled = '&nbsp;<span class="badge text-bg-danger"><strong>Subscription Cancelled :</strong> Yes</span>';
                    }else{
                        $is_cancelled = '&nbsp;<span class="badge text-bg-success"><strong>Subscription Cancelled :</strong> No</span>';
                    }

                }

                $data[$key][] = '<div class="card bg-transparent shadow-none flex-row flex-wrap m-0"><div class="card-header border-0 p-0"><img width="55" height="55" src="' . Helper::getImageOrThumbnailLink($user, true) . '" alt=""></div><div class="card-block px-2"><p class="card-text"><strong><span class="badge badge-success"># ' . $user->id . '</span> &nbsp;' . $user->name . '</strong><br>' . $user->email . '<br>'. $user->phone .'<br><img height="20" alt="'.$user->country_code.'" src="' . url()->asset('images/flags/16/' . strtolower($user->country_code) . '.png') . '"/>&nbsp;<br>'. date('d M-Y h:i A', strtotime($user->created_at)) . $remaining_subscription_days . $is_cancelled . '</p></div>     </div>';

                if ($user->add_feature == 1) {
                    $feature = 'fa-toggle-on';
                } else {
                    $feature = 'fa-toggle-off';
                }  
                $data[$key][]= '<a href="javascript:void(0)" onclick="add_feature('.$user->id.','.$user->add_feature.')" data-table="users" data-field="add_feature" data-line-id="add_feature'.$user->id.'" data-id="'.$user->id.'" data-value="1"><i id="add_feature'.$user->id.'" class="font-20 admin-single-icon fa '.$feature.'" aria-hidden="true"></i></a>';

                if (!empty($user->parent_id) && $user->parent_id == $user->id) {
                    $type = 'Parent';
                } else {
                    $type = 'Child';
                }

                if (empty($user->getAttributes()['last_login_at'])) {
                    $login ='N/A';
                } else {
                    $login =date('d M-Y H:i:s', strtotime($user->last_login_at));
                }
                $data[$key][] = $type.'<br>'.$login;
                $impersonate = url('impersonate/take/' . $user->id);
                if ($user->verified_phone == 0 || $user->verified_email == 0) {
                    $verified = '<a class="dropdown-item" data-toggle="tooltip" data-original-title="Cannot impersonate unactivated users"><i class="fa fa-lock"></i></a>';
                }else{
                    $verified = '';
                }
                $counter = $key + 1;
                $data[$key][]=
                '<div class="btn-group" role="group" aria-label="Action">
                        <a class="btn btn-xs btn-primary btn-sm" href="'.$impersonate.'" data-toggle="tooltip" data-original-title="Impersonate this user"><i class="fas fa-sign-in-alt"></i></a>&nbsp;
                        <div class="dropdown">
                            <div class="btn-group" role="group">
                                <button id="btnGroupDrop'.$counter.'" type="button" class="btn btn-sm btn-warning dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Action</button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" >'.$verified.'
                                    <a class="dropdown-item" href="'.admin_url('edit_employer/' . $user->id).'"><i class="fas fa-edit"></i> Edit</a>
                                    <a class="dropdown-item" href="javascript:void(null)" onclick="send_email('.$user->id.')"><i class="fas fa-envelope"></i> '.trans('admin.Send Email').'</a>
                                    <a class="dropdown-item" href="javascript:void(null)" onclick="delete_employee('.$user->id.')"><i class="fas fa-trash-alt"></i> Delete</a>
                                    <a class="dropdown-item" href="javascript:void(null)" onclick="reset_pass('.$user->id.')"><i class="fas fa-key"></i> Reset Password</a>
                                    <a class="dropdown-item" href="javascript:void(null)" onclick="cancel_sub('.$user->id.')"><i class="fas fa-allergies"></i> Cancel Subscription</a>
                                </div>
                            </div>
                        </div>
                    </div>';
            }
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(
            ['draw' => $request->get('draw'),
                'recordsTotal' => $employers_count,
                'recordsFiltered' =>  $filtered,
                'data' => $data]);
        die;
    }

    public function verify_employer_phone(Request $request)
    {
        $users = User::verified_employee_phone($request);
        if ($users) {
            flash('Updated Successfully')->info();
            echo 1;
            die;
        } else {
            flash('Please Try Again')->info();
            echo 1;
            die;
        }
    }

    public function verify_employer_email(Request $request)
    {
        $users = User::verified_employee_email($request);
        if ($users) {
            flash('Updated Successfully')->info();
            echo 1;
            die;
        } else {
            flash('Please Trey Again')->info();
            echo 1;
            die;
        }
    }

    public function edit_employer($id)
    {
        $country = Country::orderBy('name', 'ASC')->get();
        $user = User::find($id);
        $city = City::where('country_code', $user->country_code)->orderBy('name', 'ASC')->get();
        $company_city = Company::where('c_id', $id)->select('city_id')->first();
        if (!empty($company_city->city_id)) {
            $user->city_id = $company_city->city_id;
        } else {
            $user->city_id = 0;
        }
        // return view('vendor.admin.employer.edit', compact('user', 'city', 'country'));
        return view('admin.employer.edit', compact('user', 'city', 'country'));
    }

    public function city_dependency(Request $request)
    {
        $country_name = $request->input('country_name');
        $city = City::where('country_code', $country_name)->orderBy('name', 'ASC')->get();
        return response()->json($city);
    }

    public function update_employer(Request $request)
    {
        $user = User::find($request->id);
        $user->id = $request->id;
        $user->user_type_id = $request->user_type_id;
        $user->email = $request->email;
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->country_code = $request->country_code;
        $user->add_feature = $request->add_feature;
        $user->city = $request->city_id;

        $this->create_activity_log($request);
        if ($user->save()) {
            $employer = Company::where('c_id', $request->id)->first();
            $employer->country_code = $request->country_code;
            $employer->city_id = $request->city_id;
            $employer->update();

            // updating post emails 
            Post::update_posts_email($employer->id, $user->email);

            flash('Updated Successfully')->info();
            return redirect(admin_url('employer'));
        } else {

            flash('Please Trey Agian')->info();
            return redirect(admin_url('edit_employer  /' . $request->id));
        }
    }

    private function create_activity_log($request)
    {
        $user = User::find($request->id);
        $name = $user->name;
        $user_url = admin_url() . '/employer?search=' . $user->email;
        $description = "Admin Updated the Profile details of Company  Name: <b> <a href='$user_url'>$name</a></b>  <br>";

        $changes = [];
        if ($user->name != $request->name) {
            $changes[] = "Name : " . $request->name . " <br>";
        }


        if ($user->country_code != $request->country_code) {
            $country = Country::where('code', $request->country_code)->first();
            $changes[] = "Country : " . $country->name . " <br>";
        }

        if ($user->city != $request->city_id) {

            $city = City::where('id', $request->city_id)->first();
            $changes[] = "City : " . $city->name . " <br>";
        }

        if ($user->phone != $request->phone) {
            $changes[] = "Old Phone : " . $user->phone . " <br>";
            $changes[] = "New Phone : " . $request->phone . " <br>";
        }


        if (!empty($changes)) {
            $description .= implode(" ", $changes) . "</a>";
            Helper::activity_log($description);
        }
    }


    public function add_feature(Request $request)
    {
        $id = $request->get('id');
        $status = $request->get('status');
        $user = User::find($id);
        if ($status == 0) {
            $user->add_feature = 1;
        } else {
            $user->add_feature = 0;
        }

        if ($user->save()) {
            flash('Updated Successfully')->info();
            echo 1;
            die;
        } else {
            flash('Please Trey Agian')->info();
            echo 1;
            die;
        }
    }

    public function get_top_country_employer()
    {
        $top_country_employers = Country::get_all_country_employee_count(1);

        $title = 'Top Country Employer';
        $breadcumbs = [
            [
                'title'=> 'Dashboard',
                'link'=> admin_url('dashboard')
            ],
            [
                'title'=> 'Top Country Employer',
                'link'=> 'javascript:void(0)'
            ]
        ];

        return view('admin.employer.top_country_employer', compact('top_country_employers','title','breadcumbs'));
    }

    public function get_top_skill_posts()
    {
        $top_skill_posts = Post::get_all_skill_post_with_count();

        $title = 'Top Skill Posts';
        $breadcumbs = [
            [
                'title'=> 'Dashboard',
                'link'=> admin_url('dashboard')
            ],
            [
                'title'=> 'Top Skill Posts',
                'link'=> 'javascript:void(0)'
            ]
        ];


        return view('admin.employer.top_skill_posts', compact('top_skill_posts','title','breadcumbs'));
    }

    public function get_user_current_subscribed_packages(Request $request)
    {
        $get_subscribed_package_details = CompanyPackages::get_subscribed_package_details($request->id);
        if (!empty($get_subscribed_package_details[0])) {
            foreach ($get_subscribed_package_details as $get_subscribed_package_detail) {
                $item = new \stdClass();
                $item->id = $get_subscribed_package_detail->id;
                $item->package_id = $get_subscribed_package_detail->package_id;
                $item->package_expire_date = $get_subscribed_package_detail->package_expire_date;
                $name = json_decode($get_subscribed_package_detail->name);
                $item->package_name = $name->en;
                $data['package_details'][] = $item;
            }
            $data['status'] = 1;
            return response()->json($data);
        } else {
            $data['status'] = 0;
            return response()->json($data);
        }
    }

    public function cancel_subscription(Request $request)
    {
        $currentDate = Carbon::now();
        $date = $currentDate->format('Y-m-d H:i:s');
        $user_package_data = CompanyPackages::where('employer_id', $request['user_id_package'])->where('id', $request['package_id'])->where('is_package_expire', 0)->first();
        if (!empty($user_package_data)) {
            $user_package_data->is_package_expire = 1;
            $user_package_data->total_post = 0;
            $user_package_data->remaining_post = 0;
            $user_package_data->total_credits = 0;
            $user_package_data->remaining_credits = 0;
            $user_package_data->updated_at = $date;
            $user_package_data->save();
            Payment::where('id', $user_package_data->transaction_id)->update(['is_refunded' => 1]);

        }

        $unlock_users = ContactCardsRemaining::where('employer_id', $request['user_id_package'])->where('company_package_id', $request['package_id'])->get();
        if (!empty($unlock_users)) {
            foreach ($unlock_users as $unlock) {
                $unlock_user = Unlock::where('to_user_id', $unlock->employer_id)->where('user_id', $unlock->employee_id)->first();
                $unlock_user->is_unlock = 0;
                $unlock_user->save();

                $contact_cards__remaining = ContactCardsRemaining::find($unlock->id);
                $contact_cards__remaining->is_package_expire = 1;
                $contact_cards__remaining->updated_at = $date;
                $contact_cards__remaining->save();
            }
        }
        $check_post_available = PostRemaining::where('employer_id', $request['user_id_package'])->where('company_package_id', $request['package_id'])->get();
        if (!empty($check_post_available)) {
            foreach ($check_post_available as $item) {
                $post = Post::find($item->post_id);
                if (!empty($post)) {
                    $post->is_post_expire = 1;
                    $post->is_deleted = 1;
                    $post->deleted_at = Carbon::now(Date::getAppTimeZone());
                    $post->save();

                }
                $last_post = PostRemaining::find($item->id);
                $last_post->is_post_expire = 1;
                $last_post->updated_at = date('Y-m-d');
                $last_post->save();
                Applicant::where('post_id', $item->post_id)->update(['is_deleted' => 1]);
            }
        }

        flash('Package Subscription Cancel successfully')->info();
        return redirect(admin_url('employer'));
    }

    public function send_bulk_email_employer(Request $request)
    {
        $user_ids = explode(",", $request['user_ids']);
        if (!empty($user_ids)) {
            foreach ($user_ids as $value) {
                if (!empty($value)) {
                    $user = User::find($value);
                }
                if (!empty($user)) {
                    $data['email'] = $user->email;
                    $data['subject'] = $request->subject;
                    $data['myName'] = $user->name;
                    $data['content'] = nl2br(htmlentities($request->message));
                    $data['view'] = 'emails.general_email';
                    $data['header'] = $request->subject;
                    $helper = new Helper();
                    $response = $helper->bulk_email_queue($data);
                }
            }
            if ($response) {
                flash('Email sent Successfully')->info();
                return redirect()->back();
            } else {
                flash('Please try again.')->error();
                return redirect()->back();
            }
        }

    }

    public function update_applicant_status(Request $request)
    {
        if (empty($request->rejected_reason)) {
            if (!empty($request->applicant_id && $request->status)) {
                $update = Applicant::where('id', $request->applicant_id)->update(['status' => $request->status,'rejected_reason_id'=>'']);
                if ($update) {
                    flash('Status Update successfully')->success();
                    return redirect()->back();
                } else {
                    flash('Unable to update status')->error();
                    return redirect()->back();
                }
            } else {
                flash('Unable to update status')->error();
                return redirect()->back();
            }
        } else {
            $update = Applicant::where('id', $request->applicant_id)->update(['status' => 'rejected','rejected_reason_id'=>$request->rejected_reason]);
            if ($update) {
                flash('Status Update successfully')->success();
                return redirect()->back();
            } else {
                flash('Unable to update status')->error();
                return redirect()->back();
            }
        }
    }


}
