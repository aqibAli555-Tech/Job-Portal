<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Models\ContactCardsRemaining;
use App\Models\PostRemaining;
use App\Models\CompanyPackages;
use App\Models\User;
use Illuminate\Http\Request;
use Larapen\Admin\app\Http\Controllers\PanelController;

class AssignCreditsController extends PanelController
{
    public function view()
    {
        return view('vendor.admin.assignCredits');
    }

    public function search_user_by_email(Request $request)
    {

        $data['email'] = $request['email'];
        if (!empty($request['email'])) {
            $user = User::get_user_by_email($request['email']);

            if (empty($user)) {
                flash('Invalid credential for employer')->error();
                return redirect()->back();
            }
            $package_subscribed = CompanyPackages::get_latest_employer_package_details($user->id);
            $data['remaining_credits']  = CompanyPackages::check_credit_available($user->id);
            $data['remaining_post']  = CompanyPackages::check_post_available($user->id);

            if (empty($package_subscribed->package_id)) {
                flash('You can not assign creditts from admin until you buy an package for this employer.')->error();
                return redirect()->back();
            }
            if (strtotime($package_subscribed->package_expire_date ?? '') < strtotime(date('Y-m-d'))) {
                flash('You cannot assign post and credit to employer before subscribe the package')->error();
                return redirect()->back();
            }

            $data['user'] = $user;
            return view('vendor.admin.assignCredits')->with('data', $data);
        } else {
            return view('vendor.admin.assignCredits');
        }
    }

    public function add(Request $request)
    {

        $user = User::get_user_by_email($request->input('email'));

        if (!empty($user)) {
            $package_subscribed = CompanyPackages::get_latest_employer_package_details($user->id);

            $data['company_id'] = $package_subscribed->employer_id;
            $data['credits'] = $request['credits'];
            $data['posts'] = $request->input('post_credits');
           $res= $this->update_package_counter($data);
            $profile_url = admin_url() . '/get_employer?search=' . $request->input('email');
            $employee_name = $user->name;
            $credits = $request['credits'];
            $post_credits = $request->input('post_credits');
            $date = date('Y-m-d h:i:sa');
            $description = " Post credits: $post_credits and Contact credits: $credits  has been added to :<a href='$profile_url'>$employee_name</a> account on  Date: $date  ";
            Helper::activity_log($description);
            if ($res) {
                $data['email'] = $request->input('email');
                $data['user'] = User::get_user_by_email($request->input('email'));
                $data['remaining_credits']  = CompanyPackages::check_credit_available($user->id);
                $data['remaining_post']  = CompanyPackages::check_post_available($user->id);
                flash('Credits successfully added')->info();
                return redirect('admin/assignCredits/search?email=' . $request->input('email'));
            }

        }

    }

    private function update_package_counter($data)
    {

        $package_subscribed = CompanyPackages::get_latest_employer_package_details($data['company_id']);
        if(!empty($package_subscribed)){
            $package_subscribed->remaining_credits = $package_subscribed->remaining_credits + $data['credits'];
            $package_subscribed->total_credits = $package_subscribed->total_credits + $data['credits'];
            $package_subscribed->remaining_post = $package_subscribed->remaining_post + $data['posts'];
            $package_subscribed->total_post = $package_subscribed->total_post + $data['posts'];
            $package_subscribed->save();
        }
        
        return true;
    }
}
