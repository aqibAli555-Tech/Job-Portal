<?php

namespace App\Http\Controllers\Account;

use App\Events\AppliedEmails;
use App\Helpers\Helper;
use App\Models\Allsaved_resume;
use App\Models\Applicant;
use App\Models\CompanyPackages;
use App\Models\EmployeeSkill;
use App\Models\InterviewApplicantTrack;
use App\Models\Post;
use App\Models\RejectedReason;
use App\Models\User;
use Illuminate\Http\Request;

class ApplicationController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function Applied_Jobs()
    {
        if (!Helper::check_permission(6)) {
            flash(t("You do not have permission to access this module"))->error();
            return redirect('/');
        }
        if (auth()->user()->user_type_id == 2) {
            Helper::update_notification('rejected', auth()->user()->id);
        }

        if (auth()->user()->user_type_id == 1) {
            flash(t("Permission error..!"))->error();
            return redirect('/');
        }
        $applied = Applicant::get_all_applied(auth()->user()->id);
        $data = [];
        foreach ($applied as $item) {
            $data[] = $item;
        }
        view()->share('pagePath', 'Applied-Jobs');
        // Meta Tags
        view()->share([
            'title' => t('Applied Jobs'),
            'description' => t('Applied Jobs'),
            'keywords' => t('Applied Jobs'),
            // Add more variables as needed
        ]);

        return view('account.applied_jobs')->with('data', $data);
    }


    public function interview($id)
    {
        if (!Helper::check_permission(6)) {
            flash(t("You do not have permission to access this module"))->error();
            return redirect('/');
        }

        $applicant = Applicant::with('User')->with(['Post'])->where('id', $id)->first();
        $applicant_status = $applicant->status ?? '';
        $applicant->status = 'interview';
        $applicant->save();

        $company_name = auth()->user()->name;
        $profile_url = admin_url() . '/employer?search=' . auth()->user()->email;
        $employee_url = admin_url() . '/job-seekers?search=' . $applicant->user->email;
        $employee_name = $applicant->user->name;

        $description = "A company Name: <a href='$profile_url'>$company_name</a> changed an applicant's status from $applicant_status to interview a Employee Name:  <a href='$employee_url'>$employee_name</a>";
        Helper::activity_log($description);
        
        $data['employee_url'] = url('/profile/').'/'.$applicant->user->id;
        $data['employee_name'] = $applicant->user->name;
        $data['applicant_status'] = $applicant_status;
        $data['status'] = 'interview';
        $companyDescription = Helper::companyDescriptionData($data, 'applicant_status_update');
        if(!empty($companyDescription)){
            Helper::activity_log($companyDescription,auth()->user()->id);
        }
        
        $this->send_employee_interview_email($id);
        flash(t("Changes saved successfully"))->success();
        return redirect()->back();
    }

    public function rejected(Request $request)
    {
        $id = $request->input('id');
        if (!Helper::check_permission(6)) {
            flash(t("You do not have permission to access this module"))->error();
            return redirect('/');
        }
        $value = array(
            'status' => 'rejected',
            'rejected_reason_id' => $request->input('rejected_reason'),
        );
        $applicant = Applicant::with('User')->where('id', $id)->first();
        $applicant_status = $applicant->status ?? '';
        Applicant::where('id', $id)->update($value);

        $reason_rejecetd = RejectedReason::get_reason_with_id($request->input('rejected_reason'));
        $reason_rejected_name = !empty($reason_rejecetd->title) ? $reason_rejecetd->title : '';


        $company_name = auth()->user()->name;
        $profile_url = admin_url() . '/employer?search=' . auth()->user()->email;
        $employee_url = admin_url() . '/job-seekers?search=' . $applicant->user->email;
        $employee_name = $applicant->user->name;

        $description = "A company Name: <a href='$profile_url'>$company_name</a> changed an applicant's status from $applicant_status to rejected a Employee. Name:  <a href='$employee_url'>$employee_name</a>. Rejecetd Reason: $reason_rejected_name";
        Helper::activity_log($description);
        
        $data['employee_url'] = url('/profile/').'/'.$applicant->user->id;
        $data['employee_name'] = $applicant->user->name;
        $data['applicant_status'] = $applicant_status;
        $data['status'] = 'rejected';
        $companyDescription = Helper::companyDescriptionData($data, 'applicant_status_update');
        if(!empty($companyDescription)){
            Helper::activity_log($companyDescription,auth()->user()->id);
        }
        
        Helper::add_notification('rejected', $applicant->user_id);
        flash(t("Changes saved successfully"))->success();
        return redirect()->back();
    }

    public function haired($id)
    {
        if (!Helper::check_permission(6)) {
            flash(t("You do not have permission to access this module"))->error();
            return redirect('/');
        }
        $applicant = Applicant::with('User')->where('id', $id)->first();
        $applicant_status = $applicant->status ?? '';

        $value = array(
            'status' => 'hired',
            'rejected_reason_id' => '',
        );

        Applicant::where('id', $id)->update($value);

        $company_name = auth()->user()->name;
        $profile_url = admin_url() . '/employer?search=' . auth()->user()->email;
        $employee_url = admin_url() . '/job-seekers?search=' . $applicant->user->email;
        $employee_name = $applicant->user->name;

        $description = "A company Name: <a href='$profile_url'>$company_name</a> changed an applicant's status from $applicant_status to hiring a Employee Name:  <a href='$employee_url'>$employee_name</a>";
        Helper::activity_log($description);
        $this->send_employee_hired_email($applicant);
        flash(t("Changes saved successfully"))->success();
        return redirect()->back();
    }

    public function remove($id)
    {
        if (!Helper::check_permission(6)) {
            flash(t("You do not have permission to access this module"))->error();
            return redirect()->back();
        }

        Allsaved_resume::where('applicant_id', $id)->delete();
        Applicant::where('id', $id)->delete();
        return redirect('account/Applied-Jobs');
    }

    public function applied_applicants(Request $request)
    {
        if (!Helper::check_permission(6)) {
            flash(t("You do not have permission to access this module"))->error();
            return redirect('/');
        }
        if (auth()->user()->user_type_id == 2) {
            flash(t("Permission error..!"))->error();
            return redirect('/');
        }
        $data['posts'] = Post::get_posts_by_employer_id();
        Helper::update_notification('applicants', auth()->user()->id);
        $data['check_user_package'] = CompanyPackages::get_subscribed_package_details();
        $data['applied_data'] = Applicant::get_applicants_data_by_status($request, 'applied', $data['check_user_package']);
        $data['applied'] = Applicant::get_applicants_data_by_status_count('applied');
        $data['interview'] = Applicant::get_applicants_data_by_status_count('interview');
        $data['haired'] = Applicant::get_applicants_data_by_status_count('hired');
        $data['rejected'] = Applicant::get_applicants_data_by_status_count('rejected');
        $data['rejected_reasons'] = RejectedReason::get_all_rejected_reasons();
        if (!empty($request->get('show_not_accurate_employee')) && $request->get('show_not_accurate_employee') == 'Yes') {
            Applicant::update_is_read_status_of_not_accurate_employees_on_the_base_of_status('applied');
        }
        $data['not_accurate_not_read_employee'] = Applicant::not_accurate_not_read_employee('applied');

        $data['user_ids_list'] = Applicant::get_applicants_data_by_status($request, 'applied')->map(function ($applicant) {
            return $applicant->user_id;
        });

        $data['skill_sets_array_list'] = User::get_applicant_skill_set($data['user_ids_list']);

        view()->share('pagePath', 'Applicants');


        // Meta Tags
        view()->share([
            'title' => t('Applied Applicants'),
            'description' => t('Applied Applicants'),
            'keywords' => t('Applied Applicants'),
            // Add more variables as needed
        ]);

        return view('account.applicants/applicants')->with($data);
    }


    public function unlock($id)
    {
        if (!Helper::check_permission(6)) {
            flash(t("You do not have permission to access this module"))->error();
            return redirect('/');
        }

        if (auth()->user()->remaining_credits >= 1) {
            $value = array(
                'unlocked' => 1,
            );
            Applicant::where('id', $id)->update($value);
            $app = Applicant::with('User')->where('id', $id)->first();
            $remainningCredits = auth()->user()->remaining_credits - 1;
            $valuee = array(
                'remaining_credits' => $remainningCredits,
            );
            User::where('id', auth()->user()->id)->update($valuee);

            flash(t("Now you are unlocked this applicant details"))->message();
            return redirect()->back();
        } else {
            flash(t("you have not enough credits"))->error();
            return redirect()->back();
        }
    }


    public function send_employee_interview_email($id)
    {
        $user_data = User::where(['id' => auth()->user()->id])->first();
        $applicant = Applicant::with('User')->with(['Post'])->where('id', $id)->first();
        if (!empty($user_data->name)) {
            $company_name = $user_data->name;
        } else {
            $company_name = '';
        }
        $data['Company_name'] = $company_name;
        if (!empty($applicant->name)) {
            $employee_name = $applicant->name;
        } else {
            $applicant->name = '';
        }
        if (empty($applicant->post['title'])) {
            $data['title'] = 'Unlocked This Contact Through CV Search Page';
        } else {
            $data['title'] = $applicant->post['title'];
        }
        $data['employee_name'] = $employee_name;
        $data['email'] = $applicant->email;
        $data['subject'] = $user_data->name . " has shortlisted you for an interview";
        if ($applicant->post_id == 0) {
            $data['view'] = 'emails.search_cv_employee_interview_email';
        } else {
            $data['view'] = 'emails.employee_interview_email';
        }

        $data['header'] = 'You potentially have a job interview with Hungry For Jobs ' . "\u{1F4BC}";
        $helper = new Helper();
        $response = $helper->send_email($data);
    }

    public function send_employee_hired_email($post)
    {
        $title = post::where('id', $post['post_id'])->select('title')->first();
        if (empty($title->title)) {
            $post_title = 'Unlocked This Contact Through CV Search Page';
        } else {
            $post_title = $title->title;
        }
        $company = User::where('id', $post['to_user_id'])->first();
        $company_name = $company->name;
        $data['name'] = $post['name'];
        $data['email'] = $post['email'];
        $data['subject'] = 'Congrats! Company ' . $company_name . '  has has hired you! 🎉🎈 ';
        $data['post'] = $post['post_name'];
        $data['view'] = 'emails.employee_hired_email';
        $data['header'] = 'Congratulations 🎉🎈 Hungry For Jobs found you a job!';
        $data['company_name'] = $company_name;
        $data['post_title'] = $post_title;
        $helper = new Helper();
        $helper->send_email($data);
    }


    public function save_resume_add(Request $request)
    {
        if (!Helper::check_permission(6)) {
            flash(t("You do not have permission to access this module"))->error();
            return redirect('/');
        }


        if ($request->input('page') == 'Applicant Page') {
            $checker = Allsaved_resume::where('applicant_id', $request->input('id'))->where('user_id', auth()->user()->id)->first();
            $applicant = Applicant::where('id', $request->input('id'))->first();
            if ($checker == null) {
                $values = array(
                    'applicant_id' => $request->input('id'),
                    'user_id' => auth()->user()->id
                );
                Allsaved_resume::create($values);
                echo 'This CV has been saved!';
            } else {
                echo 'CV already saved';
            }
        } else {

            $applicant = Applicant::where('user_id', $request->input('user_id'))->first();
            $checker = Allsaved_resume::where('resume_id', $request->input('id'))->where('user_id', auth()->user()->id)->first();
            if ($checker == null) {
                echo $request->input('id');
                $values = array(
                    'resume_id' => $request->input('id'),
                    'applicant_id' => $request->input('user_id'),
                    'user_id' => auth()->user()->id
                );
                Allsaved_resume::create($values);
                $data['employee_url'] = url('/profile/'). '/' . $applicant->user->id;
                $data['employee_name'] = $applicant->user->name;
                $companyDescription = Helper::companyDescriptionData($data, 'applicant_cv_save');
                if(!empty($companyDescription)){
                    Helper::activity_log($companyDescription,auth()->user()->id);
                }
                echo 'This CV has been saved!';
            } else {
                echo 'CV already saved';
            }
        }
    }

    public function update_applicant_status(Request $request)
    {
        if (!Helper::check_permission(6)) {
            return response()->json(['status' => false, 'message' => t("You do not have permission to access this module")]);
        }

        $id = $request->applicant_id;
        $status = $request->status;

        $applicant = Applicant::with('User')->with(['Post'])->where('id', $id)->first();

        $applicant_status = $applicant->status ?? '';

        $applicant->status = $status;
        $applicant->save();

        $company_name = auth()->user()->name;
        $profile_url = admin_url() . '/employer?search=' . auth()->user()->email;
        $employee_url = admin_url() . '/job-seekers?search=' . $applicant->user->email;
        $employee_name = $applicant->user->name;

        $description = "A company Name: <a href='$profile_url'>$company_name</a> changed an applicant's status from $applicant_status to interview request to Employee Name:  <a href='$employee_url'>$employee_name</a>";
        Helper::activity_log($description);
        $this->send_employee_interview_email($id);

        $response = Helper::list_of_unlocked_applicants();
        $view = $response['view'];

        return response()->json(['status' => true, 'message' => t("Changes saved successfully"), 'view' => $view]);
    }

    public function update_status_bulk(Request $request)
    {
        $applicants_ids = explode(',', $request->input('applicants_ids'));
        if (!empty($applicants_ids)) {
            foreach ($applicants_ids as $applicants_id) {
                if (!empty($applicants_id)) {

                    $id = $applicants_id;
                    $status = 'interview';
                    $applicant = Applicant::with('User')->with(['Post'])->where('id', $id)->first();
                    $applicant_status = $applicant->status ?? '';
                    $applicant->status = $status;
                    $applicant->save();

                    $company_name = auth()->user()->name;
                    $profile_url = admin_url() . '/employer?search=' . auth()->user()->email;
                    $employee_url = admin_url() . '/job-seekers?search=' . $applicant->user->email;
                    $employee_name = $applicant->user->name;
                    $description = "A company Name: <a href='$profile_url'>$company_name</a> changed an applicant's status from $applicant_status to interview request to Employee Name:  <a href='$employee_url'>$employee_name</a>";
                    Helper::activity_log($description);
                    $this->send_employee_interview_email($id);
                }
            }
            flash(t("Changes saved successfully"))->success();
        } else {
            flash(t("Unable to update. Please try again later"))->error();
        }
        echo 1;
        die;
    }


    public function get_interview_state_applicants()
    {
        $applicants = InterviewApplicantTrack::get_applicants_on_the_profile_date_bases();
        if (!empty($applicants->isNotEmpty())) {
            $status = true;
            $view = view('modals.interview_table', compact('applicants'))->render();
            InterviewApplicantTrack::Update_on_profile_seen_date($applicants);
        } else {
            $status = false;
            $view = '';
        }
        return response()->json(['status' => $status, 'view' => $view]);
    }


    public function applicants(Request $request)
    {
        $data['posts'] = Post::get_posts_by_employer_id(true);
        $data['check_user_package'] = CompanyPackages::get_subscribed_package_details();


        $data['rejected_reasons'] = RejectedReason::get_all_rejected_reasons();
        $data['total_applicants'] = Applicant::get_applicant_count_by_status_all($request, $data['check_user_package'],true);


        $archive_posts = $this->archivedPosts->get();
        $archive_posts_ids = [];
        if (!empty($archive_posts)) {
            foreach ($archive_posts as $key => $value) {
                $archive_posts_ids[$key] = $value->id;
            }
        }
        $data['total_archived_applicants'] =Applicant::get_archived_applicants_count_all($archive_posts_ids, $request,true);
       view()->share([
            'title' => t('Applicants'),
            'description' => t('Applicants'),
            'keywords' => t('Applicants'),
        ]);
        return view('account.applicants.applicants')->with($data);
    }

    public function applicants_ajax(Request $request)
    {
        if (!Helper::check_permission(6)) {
            return response()->json(['status' => false, 'message' => t("You do not have permission to access this module")]);
        }
        Helper::update_notification('applicants', auth()->user()->id);
        $check_user_package = CompanyPackages::get_subscribed_package_details();
        $data['applicant_data'] = Applicant::get_applicants_data($request, $check_user_package);
        $data['not_accurate_employee_count'] = Applicant::not_accurate_not_read_employee();

        $data['user_ids_list'] = $data['applicant_data']->map(function ($applicant) {
            return $applicant->user_id;
        });
        $data['skill_sets_array_list'] = User::get_applicant_skill_set($data['user_ids_list']);

        $data['applicant_count'] = Applicant::get_applicant_count_by_status_all($request, $check_user_package);
        return response()->json($data);
    }

    public function update_applicant_status_ajax(Request $request)
    {
        if (!Helper::check_permission(6)) {
            return response()->json(['status' => false, 'message' => t("You do not have permission to access this module")]);
        }

        $id = $request->applicant_id;
        $status = $request->status;
        $rejected_reason = $request->rejected_reason;
        $rejected_request = false;
        if (isset($rejected_reason) && !empty($rejected_reason)) {
            $rejected_request = true;
        }
        $applicant = Applicant::with('User')->with(['Post'])->where('id', $id)->first();
        $applicant_status = $applicant->status;
        $applicant->status = $status;
        if ($rejected_request) {
            $applicant->rejected_reason_id = $rejected_reason;
        }

        if ($applicant->save()) {

            if ($request->type == '#tab-applicants') {
                $applicant_count = Applicant::get_applicant_count_by_status_all($request);
            } else {
                $archive_posts = $this->archivedPosts->get();
                $archive_posts_ids = [];
                if (!empty($archive_posts)) {
                    foreach ($archive_posts as $key => $value) {
                        $archive_posts_ids[$key] = $value->id;
                    }
                }
                $applicant_count = Applicant::get_archived_applicants_count_all($archive_posts_ids, $request);
            }
            if ($status == 'interview' || $status == 'hired') {
                $this->send_email_to_employee_on_status_change($id, $status, $applicant);
            }
            
            $data['employee_url'] = url('/profile/').'/'.$applicant->user->id;
            $data['employee_name'] = $applicant->user->name;
            $data['applicant_status'] = $applicant_status;
            $data['status'] = $status;
            $companyDescription = Helper::companyDescriptionData($data, 'applicant_status_update');
            if(!empty($companyDescription)){
                Helper::activity_log($companyDescription,auth()->user()->id);
            }

            return response()->json(['status' => true, 'message' => "Applicant status has been changes to $status successfully", 'data' => $applicant_count]);

        } else {
            return response()->json(['status' => false, 'message' => "Unable to update status Please try again later"]);
        }
    }

    public function send_email_to_employee_on_status_change($id, $status, $applicant)
    {
        if ($status == 'interview') {
            $this->send_employee_interview_email($id);
        } elseif ($status == 'hired') {
            $this->send_employee_hired_email($applicant);
        }
    }


    public function archived_applicants_ajax(Request $request)
    {
        if (!Helper::check_permission(6)) {
            return response()->json(['status' => false, 'message' => t("You do not have permission to access this module")]);
        }

        $archive_posts = $this->archivedPosts->get();
        $archive_posts_ids = [];
        if (!empty($archive_posts)) {
            foreach ($archive_posts as $key => $value) {
                $archive_posts_ids[$key] = $value->id;
            }
        }
        $data['archive_posts_ids'] = $archive_posts_ids;
        $data['applicant_data'] = Applicant::get_archived_applicants($request, $archive_posts_ids);

        $data['user_ids_list'] = $data['applicant_data']->map(function ($applicant) {
            return $applicant->user_id;
        });

        $data['not_accurate_employee_count'] = Applicant::not_accurate_not_read_employee($archive_posts_ids);

        $data['skill_sets_array_list'] = User::get_applicant_skill_set($data['user_ids_list']);


        $data['applicant_count'] = Applicant::get_archived_applicants_count_all($archive_posts_ids, $request);
        return response()->json($data);
    }

}
