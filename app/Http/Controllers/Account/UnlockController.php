<?php

namespace App\Http\Controllers\Account;

use App\Helpers\Helper;
use App\Models\Applicant;
use App\Models\ContactCardProblems;
use App\Models\ContactCardViewLog;
use App\Models\EmployeeSkill;
use App\Models\Package;
use App\Models\Post;
use App\Models\RejectedReason;
use App\Models\Unlock;
use App\Models\User;
use App\Models\ContactCardsRemaining;
use App\Models\CompanyPackages;
use Illuminate\Http\Request;
use Session;
use App\Models\EmployerUnlockApplicantsWatchHistory;

class UnlockController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function UnlockedContactCards(Request $request)
    {
        if (!Helper::check_permission(8)) {
            flash(t("You do not have permission to access this module"))->error();
            return redirect('/');
        }

        if (auth()->user()->user_type_id == 2) {
            flash(t("Permission error..!"))->error();
            return redirect('/');
        }
        if (auth()->user()->user_type_id == 1 && empty(auth()->user()->parent_id)) {
            flash(t("Please change account to child company to view this page."))->error();
            return redirect('/');
        }

        $data = Unlock::get_unlock_contact_card($request);

        $temp_array = array();
        $i = 0;
        $key_array = array();

        foreach ($data as $key => $val) {
            if (!in_array($val->user_id, $key_array)) {
                $key_array[$i] = $val->user_id;
                $temp_array[$i] = $val;
            }
            $i++;
        }
        $posts = Post::get_posts_by_employer_id();

        view()->share('pagePath', 'Unlocked-Contact-Cards');
        // Meta Tags
        view()->share([
            'title' => t('Unlocked Contact Cards'),
            'description' => t('Unlocked Contact Cards'),
            'keywords' => t('Unlocked Contact Cards'),
            // Add more variables as needed
        ]);
        $rejected_reasons = RejectedReason::get_all_rejected_reasons();
        view()->share('rejected_reasons', $rejected_reasons);
        return view('account.unlocked_contact_cards')->with(['data'=> $temp_array,'posts'=>$posts]);
    }

    public function UnlockProfile(Request $request, $id)
    {
//        $response = Applicant::check_application_status_by_employer_id();
//        if ($response['status'] == false) {
//            flash('You can only unlock this Contact Card once you�ve filtered the previous Contact Card you opened as Interview, Hired, Or Rejected. Click <a href="javascript:void(0);" id="load_unlock_contacts"> HERE </a> to filter your previously opened Contact Card.')->error();
//            return redirect()->back();
//        }
//
//        $applicants_older_than_two_months = Applicant::get_applicants_older_than_two_months(auth()->user()->id);
//
//        if ($applicants_older_than_two_months->isNotEmpty()) {
//            flash('You are unable to Unlock this Contact Card as you have Applicants in the Interview state for more than 3 months. Please update their status to Hired or Rejected by clicking on the <a href="' . url('account/interview_applicants') . '">Applicants Page</a> and/or <a href="' . url('account/Archive_applicants') . '">Archived Applicants Page</a> to continue opening new Contact Cards.')->error();
//            return redirect()->back();
//        }

        if (url()->previous()) {
            Session::put('previous_ul', url()->previous());
        }
        $check_post_exist = Post::where('user_id', auth()->user()->id)->where('archived', 0)->first();
        if (empty($check_post_exist)) {
            flash('You have to post atleast one job first before viewing Contact Cards of employees')->error();
            return redirect()->back();
        }
        if (auth()->check()) {
            $today = date('Y-m-d');
            $remainig_count  = CompanyPackages::check_credit_available(auth()->user()->id);
            if ($remainig_count > 0 || $remainig_count == 'unlimited' ) {
                $unlock_user_check = Unlock::where('user_id', $id)->where('to_user_id', auth()->user()->id)->where('is_unlock', 1)->first();
                if (empty($unlock_user_check)) {
                    $unlock_user_data = Unlock::where('user_id', $id)->where('to_user_id', auth()->user()->id)->first();
                    $values = array(
                        'user_id' => $id,
                        'to_user_id' => auth()->user()->id,
                        'is_unlock' => 1,
                        'post_id' => $request->get('selected_post'),
                    );
                    if (empty($unlock_user_data)) {
                        $data = Unlock::create($values);
                    } else {
                        $data = Unlock::where('user_id', $id)->where('to_user_id', auth()->user()->id)->update($values);
                    }

                    // Create Applicants
                    $check_applicant = Applicant::where('user_id', $id)->where('to_user_id', auth()->user()->id)->where('is_deleted',0)->first();
                    $check_user_for_applicant_create = User::find($id);
                    if (empty($check_applicant)) {
                        $new_applicant = new Applicant();
                        $new_applicant->name = $check_user_for_applicant_create->name;
                        $new_applicant->email = $check_user_for_applicant_create->email;
                        $new_applicant->user_id = $check_user_for_applicant_create->id;
                        $new_applicant->to_user_id = auth()->user()->id;
                        $new_applicant->post_id = '0';
                        $new_applicant->status = 'applied';
                        $new_applicant->contact_unlock = '1';
                        $new_applicant->save();
                    }
                    // Create Activity Logs


                    $myurl = url('/companyprofile/' . auth()->user()->id);
                    $company_name = auth()->user()->name;
                    $description = "Company <a href='$myurl'><b>$company_name</b></a> viewed your CV from our database of CV�s. There is a possibility they could contact you for an interview.";
                    $contact_card_log = array(
                        'user_id' => $id,
                        'company_id' => auth()->user()->id,
                        'description' => $description,
                    );
                    ContactCardViewLog::create($contact_card_log);
                    Helper::update_remaining_credit(auth()->user(),$id);
                  
                    $job_seeker_data = User::where('id', $id)->first();
                    $company_name = auth()->user()->name;
                    $profile_url = admin_url() . '/employer?search=' . auth()->user()->email;
                    $job_seeker_url = admin_url() . '/job-seekers?search=' . $job_seeker_data->email;
                    $description = "A company Name: <a href='$profile_url'>$company_name</a> just use his contact card to unlock the Employee profile <br> Employee name: <a href='$job_seeker_url'>$job_seeker_data->name</a> ";
                    Helper::activity_log($description);
                    $data = [];
                    $data['employee_name'] = $job_seeker_data->name;
                    $data['employee_url'] = url('/profile/').'/'.$job_seeker_data->id;
                    $companyDescription = Helper::companyDescriptionData($data, 'unlock_profile');
                    if(!empty($companyDescription)){
                        Helper::activity_log($companyDescription,auth()->user()->id);
                    }
                    $this->send_employee_contact_card_email($job_seeker_data);
                    flash(t('You can contact this person by viewing their CV or chatting with them directly through their profile!'))->success();
                    return redirect('profile/' . $id);
                } else {
                    flash('You have already unlock the profile of this user')->error();
                    return redirect('profile/' . $id);
                }

            }


        }
        return redirect()->back();
    }


    public function send_employee_contact_card_email($post)
    {
        $company = User::where('id', auth()->user()->id)->first();
        $company_name = $company->name;
        $data['name'] = $post['name'];
        $data['email'] = $post['email'];
        $data['subject'] = $company_name . '  has viewed your CV and could be interested👀';
        $data['post'] = $post['post_name'];
        $data['view'] = 'emails.unlock_contact_card_for_employee_email';
        $data['header'] = 'Your CV has been viewed';
        $data['company_name'] = $company_name;
        $helper = new Helper();
        $helper->send_email($data);
    }

    public function contactproblem(Request $request)
    {
        if (empty($request->name)) {
            flash("Please Enter Name.")->error();
            return redirect()->back();
        }
        $contact = new ContactCardProblems();
        $contact->name = $request->name;
        $contact->company = $request->company;
        if ($contact->save()) {
            flash("Thank you! Check your Contact Card balance on your profile within 48 hours - you should be reimbursed if your review was accepted by our team.")->success();
        } else {
            flash("Please try again.")->error();
        }
        return redirect()->back();
    }

    public function UnlockContactCardBulk(Request $request)
    {
        if (url()->previous()) {
            Session::put('previous_ul', url()->previous());
        }
        $user_ids = explode(",", $request['user_ids']);
        $arrayresult = [];
        if (!empty($user_ids)) {
            foreach ($user_ids as $id) {
                if (!empty($id)) {
                    $user = User::find($id);
                    $result = $this->match_skill_sets($request['post_id'], $user);
                    if ($result) {
                        if (auth()->check()) {
                            $today = date('Y-m-d');
                            $employer = User::find(auth()->user()->id);
                            if ((int)$employer->remaining_credits > 0 && (strtotime($employer->post_expire) > strtotime($today))) {
                                $unlock_user_check = Unlock::where('user_id', $id)->where('to_user_id', auth()->user()->id)->where('is_unlock', 1)->first();
                                if (empty($unlock_user_check)) {
                                    $values = array(
                                        'user_id' => $id,
                                        'to_user_id' => auth()->user()->id,
                                        'is_unlock' => 1,
                                        'post_id' => $request['post_id'],
                                    );

                                    $data = Unlock::create($values);
                                    $myurl = url('/companyprofile/' . auth()->user()->id);
                                    $company_name = auth()->user()->name;
                                    $description = "Company <a href='$myurl'><b>$company_name</b></a> viewed your CV from our database of CV’s. There is a possibility they could contact you for an interview.";
                                    $contact_card_log = array(
                                        'user_id' => $id,
                                        'company_id' => auth()->user()->id,
                                        'description' => $description,
                                    );
                                    ContactCardViewLog::create($contact_card_log);
                                    Helper::update_remaining_credit($employer);

                                    $job_seeker_data = User::where('id', $id)->first();
                                    $company_name = auth()->user()->name;
                                    $profile_url = admin_url() . '/employer?search=' . auth()->user()->email;
                                    $job_seeker_url = admin_url() . '/job-seekers?search=' . $job_seeker_data->email;
                                    $description = "A company Name: <a href='$profile_url'>$company_name</a> just use his contact card to unlock the Employee profile <br> Employee name: <a href='$job_seeker_url'>$job_seeker_data->name</a> ";
                                    Helper::activity_log($description);
                                    //$this->send_employee_contact_card_email($job_seeker_data);
                                }

                            }


                        }
                    }
                }
            }
        }
        $message = "You can only view Contact Cards if anyone of your current job posts are relevant to any skills sets that you want to view CV’s for As an example, you can view an Accountant’s CV if you post a job for an Accountant or you can view a Chef’s CV if you post a job for a Chef You can also view CV’s by editing your job post and adding their skills sets to the section: Only Specific Skills Sets Can Apply As an example, you post a job for a Chef and you choose the skills set Chef. You can then add Sous Chef, Commi Chef, Chef De Partie, etc .. under the section: Only Specific Skills Sets Can Apply For any questions or inquiries - please get in touch with the Hungry For Jobs team through our Contact Us page on the footer of our website";
        flash($message)->info();
        return redirect()->back();
    }

    function match_skill_sets($post_id, $user)
    {
        $post = Post::find($post_id);
        $skill_sets = EmployeeSkill::find($post->category_id);
        $userArray = explode(",", $user->skill_set);

        if (stristr($user->skill_set, $skill_sets->skill) !== FALSE) {

            $check_applicant = Applicant::where('user_id', $user->id)->first();
            if (empty($check_applicant)) {
                $new_applicant = new Applicant();
                $new_applicant->name = $user->name;
                $new_applicant->email = $user->email;
                $new_applicant->user_id = $user->id;
                $new_applicant->to_user_id = auth()->user()->id;
                $new_applicant->post_id = '0';
                $new_applicant->status = 'applied';
                $new_applicant->contact_unlock = '1';
                $new_applicant->save();
            }
            return true;
        } else {
            return false;
        }

    }
    
     public function track_applicant_in_employer($id){
        $applied = Applicant::track_applicant_user_by_employer($id,auth()->user()->id);
        return response()->json($applied);
    }

    public function check_company_has_unlock_applicants(Request $request)  {
        $status = false;
        $view = '';
        $load_anyway = $request->load_anyway;
        $applicant = EmployerUnlockApplicantsWatchHistory::check_is_employer_check_applied_unlock_applicants();
        if(!$applicant){
            EmployerUnlockApplicantsWatchHistory::add_employer_check_applied_applicants_history();
            $response = Helper::list_of_unlocked_applicants();
            $status = $response['status'];
            $view = $response['view'];
        }

        if($load_anyway){
            $response = Helper::list_of_unlocked_applicants();
            $status = $response['status'];
            $view = $response['view'];
        }

        return response()->json(['status' => $status, 'view' => $view]);
    }

}