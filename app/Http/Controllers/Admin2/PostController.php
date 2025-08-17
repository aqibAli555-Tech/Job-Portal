<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Auth\Traits\VerificationTrait;
use App\Models\Applicant;
use App\Models\EmployeeSkill;
use App\Models\Post;
use App\Models\PostDetails;
use App\Models\PostMeta;
use App\Models\PostType;
use App\Models\SalaryType;
use App\Models\User;
use Illuminate\Http\Request;


class PostController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    use VerificationTrait;

    public function get_posts(Request $request)
    {
        $posts = Post::get_posts($request);
        $skill_sets = EmployeeSkill::get_All_skill_With_Post_Count();
        return view('vendor.admin.post.index', compact('posts', 'skill_sets'));
    }

    public function approved_post($id)
    {
        if (!empty($id)) {
            $post = Post::find($id);
            $post->is_approved = 1;
            if ($post->save()) {
                $skill = EmployeeSkill::find($post->category_id);
                $skill->status = 1;
                $skill->save();
            }
            flash('Post Approved Successfully')->info();
            return redirect()->back();

        }
    }

    public function edit_post($id)
    {
        $post = Post::with(['postDetail', 'postDetail.city', 'postMeta'])->where('id', $id)->first();
        $employee_skill = EmployeeSkill::getAllskill();
        $post_type = PostType:: all();
        $salary_type = SalaryType:: all();
        return view('vendor.admin.post.edit', compact('post', 'employee_skill', 'salary_type', 'post_type'));
    }

    public function delete_post(Request $request)
    {
        $selectedIds = explode(",", $request['selectedIds']);
        $selectedIdsarray = array_filter($selectedIds);
        foreach ($selectedIdsarray as $id) {
            $post = Post::find($id);
            $post->is_deleted = 1;
            $post->save();

        }
        flash('Deleted Successfully')->info();
        echo 1;
        die;
    }

    public function delete_item(Request $request)
    {
        $id = $request->get('id');
        $post = Post::find($id);
        $post->is_deleted = 1;
        if ($post->save()) {
            flash('Deleted Successfully')->info();
            echo 1;
            die;
        } else {
            flash('Please Try Again')->info();
            echo 1;
            die;
        }

    }

    public function update_posts(Request $request)
    {

        $post = Post::find($request->id);
        $post->id = $request->id;
        $post->category_id = $request->category_id;
        $post->company_name = $request->company_name;
        $post->company_description = $request->company_description;
        $post->title = $request->title;
        $post->description = $request->description;
        $post->salary_min = $request->salary_min;
        $post->salary_max = $request->salary_max;
        $post->salary_type_id = $request->salary_type_id;
        $post->negotiable = $request->negotiable;
        $post->contact_name = $request->contact_name;
        $post->email = $request->email;
        $post->phone = $request->phone;
        $post->post_type_id = $request->post_type_id;
        $post->archived = $request->archived;

        if ($post->save()) {
            $post_details = PostDetails::where('post_id', $request->id)->first();
            $post_details->overtime_pay = $request->overtime;
            $post_details->housing_available = $request->housing;
            $post_details->transportation_available = $request->transportation;
            $post_details->save();
            flash('Updated Successfully')->info();
            return redirect(admin_url('get_posts'));
        } else {
            flash('Please Try Again')->info();
            return redirect(admin_url('edit_post/' . $request->id));
        }
    }

    public function add_post_feature(Request $request)
    {
        $id = $request->get('id');
        $status = $request->get('status');
        $post = PostMeta::where('post_id', $id)->first();
        if ($status == 0) {
            $post->featured = 1;
        } else {
            $post->featured = 0;
        }
        if ($post->save()) {
            flash('Updated Successfully')->info();
            echo 1;
            die;
        } else {
            flash('Please Try Again')->info();
            echo 1;
            die;
        }
    }

    public function update_status(Request $request)
    {
        $id = $request->get('id');
        $status = $request->get('status');
        $post = Post::find($id);
        if ($status == 0) {
            $post->is_active = 1;
        } else {
            $post->is_active = 0;
        }
        if ($post->save()) {
            flash('Updated Successfully')->info();
            echo 1;
            die;
        } else {
            flash('Please Trey Agian')->info();
            echo 1;
            die;
        }
    }

    public function get_applicants(Request $request)
    {
        $data['applicants'] = Applicant::get_applicant($request);
        $data['counts'] = Applicant::get_applicant_counts($request);
        $data['applied'] = Applicant::get_applicant_counts_by_status('applied');
        $data['interview'] = Applicant::get_applicant_counts_by_status('interview');
        $data['rejected'] = Applicant::get_applicant_counts_by_status('rejected');
        $data['hired'] = Applicant::get_applicant_counts_by_status('hired');
        $data['pending'] = Applicant::get_applicant_counts_by_status('pending');
        $data['companies'] = User::where('user_type_id', 1)->where('parent_id', '!=', 0)->get();
        $data['posts'] = Post::where('is_deleted', 0)->get();
        $data['rejected_reasons'] = \App\Models\RejectedReason::get_all_rejected_reasons();

        return view('vendor.admin.post.applicants', compact('data'));
    }

    public function approved_applicants(Request $request)
    {
        if (!empty($request->post('skill_accuracy')) && !empty($request->post('applicants_id'))) {
            $applicants = Applicant::find($request->post('applicants_id'));
            $applicants->status = 'applied';
            $applicants->skill_accuracy = $request->post('skill_accuracy');
            if ($applicants->save()) {
                flash('Approved Successfully')->info();
                return redirect()->back();
            } else {
                flash('Please Trey Agian')->info();
                return redirect()->back();
            }

        } else {
            return redirect()->back();
        }

    }


    public function reject_bulk_applicant(Request $request)
    {
        $applicants_ids = explode(',', $request->post('applicant_ids'));
        if (!empty($applicants_ids)) {
            foreach ($applicants_ids as $applicants_id) {
                if(!empty($applicants_id)) {
                    $applicant = Applicant::find($applicants_id);
                    if(!empty($applicant)) {
                        $applicant->update([
                            'status' => 'rejected',
                            'rejected_reason_id' => 3,
                        ]);
                    }
                }
            }
            flash('Applicants Rejected Successfully')->success();
            return redirect()->back();
        } else {
            flash('Please Try Again')->error();
            return redirect()->back();
        }
    }


}

?>