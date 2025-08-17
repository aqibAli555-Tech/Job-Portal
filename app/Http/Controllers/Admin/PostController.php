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
use App\Models\PostRemaining;
use App\Helpers\Helper;
use App\Helpers\UrlGen;

class PostController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    use VerificationTrait;

    public function get_posts(Request $request)
    {
        $skill_sets = EmployeeSkill::get_All_skill_With_Post_Count();
        $title = 'Job Posts';
        $breadcumbs = [
            [
                'title' => 'Dashboard',
                'link' => admin_url('dashboard')
            ],
            [
                'title' => 'Job Posts',
                'link' => 'javascript:void(0)'
            ]
        ];

        return view('admin.post.index', compact('skill_sets', 'title', 'breadcumbs'));
    }

    public function ajax(Request $request)
    {
        $posts = Post::get_posts($request);
        $post_count = Post:: get_posts_count($request);
        $data = [];
        foreach ($posts as $key => $post) {
            $is_expired = true;
            $re_days = '';
            $remain_post = PostRemaining::get_post_by_employer_id_and_post_id($post->user_id, $post->id);
            if (!empty($remain_post->post_expire_date_time)) {
                if ($post->is_post_expire == 0) {
                    $remainingDays = Helper::calculate_remaining_days($remain_post->post_expire_date_time);
                    $datetime1 = strtotime($remain_post->post_expire_date_time);
                    $datetime2 = strtotime(date('Y-m-d H:i:s'));
                    if ($datetime1 >= $datetime2) {
                        $re_days = $remainingDays . ' ' . 'Days Left';
                        $is_expired = false;
                    }
                }
            }
            $employer_url = admin_url() . '/employer?search=' . $post->company->name ?? $post->company_name;
            $firstrow = '';
            $firstrow .= '<strong><a target="_blank" class="font-weight-bolder" href="' . UrlGen::post($post) . '">' . $post->title . '</a></strong>';
            if ($is_expired) {
                $firstrow .= '<div class="badge text-bg-warning float-end m-1">Expired</div>';
            } else {
                $firstrow .= '<div class="badge text-bg-success float-end m-1">' . $re_days . '</div>';
            }
            if ($post->is_approved == 0) {
                $firstrow .= '<div class="badge text-bg-danger float-end m-1">Not Approved</div>';
            } else {
                $firstrow .= '<div class="badge text-bg-success float-end m-1">Approved</div>';
            }

            if ($post->is_deleted == 1) {
                $firstrow .= '<div class="badge text-bg-danger float-end m-1">Deleted</div>';
            }

            $firstrow .= '<br>';
            $firstrow .= '<img width="55" height="55" src="' .  Helper::getImageOrThumbnailLink($post->user) . '" alt=""><a target="_blank" href="' . $employer_url . '">By : ' . ($post->company->name ?? $post->company_name) . '</a>';


            $fourthrow = '<a target="_blank" href="' . admin_url() . '/applicants?post=' . $post->id . '">';
            if (!empty($post->applicant)) {
                $fourthrow .= $post->applicant->count();
            } else {
                $fourthrow .= "0";
            }
            $fourthrow .= '</a>';
            $skills = '<br>Skills : ';
            if (($post->postDetail->post_type ?? '') == 2) {
                foreach (explode(',', $post->postDetail->skills_set) as $skill_value) {
                    $skills .= '<span class="badge text-bg-info">' . $skill_value . '</span>&nbsp';
                }
            } else {
                $skills .= '<div class="badge text-bg-info">All Skills</div>';
            }

            $skills .= "</div>";

            $data[$key][] = $firstrow . $skills;
            $data[$key][] = '# of Applicants : ' . $fourthrow .
                '<br>Location : 
                <img  alt="' . $post->country_code . '" src="' . url()->asset('images/flags/16/' . strtolower($post->country_code) . '.png') . '"> ' .
                '<a target="_blank" href="' . url('latest-jobs?post=&country_code=' . ($post->country->code ?? "") . '&q=&l=&min_salary=&max_salary=&type[]=') . '">' . ($post->country->name ?? '') . '</a> , ' .
                '<a target="_blank" href="' . url('latest-jobs?post=&country_code=&q=&l=' . ($post->postDetail->city->id ?? "") . '=&min_salary=&max_salary=&type[]=') . '">' . ($post->postDetail->city->name ?? '') . '</a>' .
                '<br><small class="text-info">' . date('d-M-Y h:i a', strtotime($post->created_at)) . '</small>';

            $featuredVar = $post->postMeta->featured ?? '';
            $sixrow = '<a href="javascript:void(0)" onclick="add_feature(' . $post->id . ',' . $featuredVar . ')" data-table="post" data-field="add_feature" data-line-id="add_feature' . $post->id . '" data-id="' . $post->id . '" data-value="1"><i id="add_feature' . $post->id . '" class="admin-single-icon fa';
            if (($post->postMeta->featured ?? '') == 1) {
                $sixrow .= ' font-24 fa-toggle-on  text-success';
            } else {
                $sixrow .= ' font-24 fa-toggle-off text-danger';
            }
            $sixrow .= ' aria-hidden="true"></i></a>';
            $data[$key][] = $sixrow;
            $sevenrow = '';
            $sevenrow .= '<a href="javascript:void(0)" onclick="update_status(' . $post->id . ',' . $post->is_active . ')" data-table="is_active" data-field="is_active" data-line-id="is_active' . $post->id . '" data-id="' . $post->id . '" data-value="1"><i id="is_active' . $post->id . '" class="admin-single-icon fa ';
            if ($post->is_active == 1) {
                $sevenrow .= ' font-24 fa-toggle-on text-success';
            } else {
                $sevenrow .= ' font-24 fa-toggle-off text-danger';
            }
            $sevenrow .= ' aria-hidden="true"></i></a>';
            $data[$key][] = '' . $sevenrow . '';

            $ninerow = '';
            $url = admin_url("edit_post/" . $post->id);
            $ninerow .= '
            <div class="btn-group" role="group">
                <button id="btnGroupDrop' . $key . '" type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop' . $key . '">
                        <a class="dropdown-item" href="' . $url . ')"><i class="far fa-edit"></i> Edit</a>';
            if ($post->is_approved == 0) {
                $ninerow .= '<a class="dropdown-item" href="' . admin_url('approved_post/' . $post->id) . '"><i class="far fa-edit"></i> Approved</a>';
            }
            if ($post->is_deleted == 0) {
                $ninerow .= '<button class="dropdown-item" data-button-type="delete" onclick="delete_item(' . $post->id . ')"><i class="far fa-trash-alt"></i> Delete</button>';
            }
            $ninerow .= '</div>';
            $ninerow .= '</div>';
            $data[$key][] = '' . $ninerow . '';

        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(
            [
                'draw' => $request->get('draw'),
                'recordsTotal' => $post_count,
                'recordsFiltered' => $post_count,
                'data' => $data
            ]);
        die;

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
        return view('admin.post.edit', compact('post', 'employee_skill', 'salary_type', 'post_type'));
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
            echo 1;
            die;
        } else {
            echo 1;
            die;
        }
    }



    public function applicants(Request $request)
    {
        $data['counts'] = Applicant::get_applicant_counts($request);
        $data['applied'] = Applicant::get_applicant_counts_by_status('applied');
        $data['interview'] = Applicant::get_applicant_counts_by_status('interview');
        $data['rejected'] = Applicant::get_applicant_counts_by_status('rejected');
        $data['hired'] = Applicant::get_applicant_counts_by_status('hired');
        $data['pending'] = Applicant::get_applicant_counts_by_status('pending');
        $data['companies'] = User::where('user_type_id', 1)->where('parent_id', '!=', 0)->get();
        $data['posts'] = Post::where('is_deleted', 0)->get();
        $data['rejected_reasons'] = \App\Models\RejectedReason::get_all_rejected_reasons();

        $title = 'Applicants';
        $breadcumbs = [
            [
                'title' => 'Dashboard',
                'link' => admin_url('dashboard')
            ],
            [
                'title' => 'Applicants',
                'link' => 'javascript:void(0)'
            ]
        ];

        return view('admin.post.applicants', compact('data', 'title', 'breadcumbs'));
    }

    public function ajax_applicant(Request $request)
    {
        $applicants = Applicant::get_applicant($request);
        $applicants_count = Applicant::get_applicant_count($request);
        $applicants_count_all = Applicant::get_applicant_count($request, true);
        $data=[];
        foreach ($applicants as $key => $item) {

            if (!empty($item->user)) {
                $user_pic = !empty($item->user->thumbnail) ? $item->user->thumbnail : $item->user->file;
                if ($item->skill_accuracy == 'Accurate') {
                    $color = 'btn-warning';
                } elseif ($item->skill_accuracy == 'Very Accurate') {
                    $color = 'btn-success';
                } elseif ($item->skill_accuracy == 'Not Accurate') {
                    $color = 'btn-danger';
                } else {
                    $color = '';
                }

                if ($item->status == 'interview') {
                    $data[$key][] = '<td class="d-flex flex-column border-0"><div class="pt-1 text-center"><input type="checkbox" name="employee_ids" class="checkbox" onclick="SingletoggleCheckbox(this)" value="' . $item->id . '"></div></td>';
                } else {
                    $data[$key][] = '<td></td>';
                }

                $company_logo = !empty($item->company->thumbnail) ? $item->company->thumbnail : $item->company->logo;
                $is_deleted = '';
                if (!empty($item->is_deleted)) {
                    $is_deleted = 'Deleted';
                }
                $data[$key][] = '<td class="d-flex flex-column border-0"><img width="50" height="50" src="' . \App\Helpers\Helper::getImageOrThumbnailLink($item->user) . '"><span><strong class="font-weight-bolder">Name: </strong><small><a href="' . admin_url('/job-seekers?search=' . $item->user->name) . '">' . $item->user->name . '</a></small></span><br><span style="background: red;" class="badge badge-danger">' . $is_deleted . '</span><br><small class="text-info">' . $item->created_at->format("d-M-Y") . '</small>';

                $row = '';
                foreach (explode(',', $item->user->skill_set) as $value) {
                    $row .= '<span class="badge badge-success">' . $value . '</span>&nbsp';
                }

                $data[$key][] = '' . $row . '';
                $data[$key][] = '<td class="d-flex flex-column border-0"><img style="width: 100px; height: auto;" src="' . \App\Helpers\Helper::get_company_logo_AWS($item->company,true) . '"><span><strong class="font-weight-bolder">Name: </strong><small><a href="' . admin_url('/employer?search=' . $item->company->name) . '">' . $item->company->name . '</a></small></span>';
                $row = '';
                if ($item->contact_unlock == 1) {
                    $row .= t('Unlocked This Contact Through CV Search Page');
                } else {
                    $postTitle = $item->post->title ?? '';
                    $row .= '<a href="' . UrlGen::post($item->post) . '">' . $postTitle . '</a>';
                }
                $data[$key][] = '' . $row . '';
                $row = '';

                if ($item->status == 'pending') {
                    $row .= '<button type="button" class="btn btn-sm btn-block btn-secondary">Pending</button>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-block btn-primary">Approved</button>
                                <button type="button" class="btn btn-sm btn-block btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="ms-1" style="font-size: 10px;">&#x25BC;</span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item bg-success text-white" href="javascript:void(0)" onclick="updateSkillStatus(' . $item->id . ', \'Very Accurate\')">Very Accurate</a></li>
                                    <li><a class="dropdown-item bg-warning text-dark" href="javascript:void(0)" onclick="updateSkillStatus(' . $item->id . ', \'Accurate\')">Accurate</a></li>
                                    <li><a class="dropdown-item bg-danger text-white" href="javascript:void(0)" onclick="updateSkillStatus(' . $item->id . ', \'Not Accurate\')">Not Accurate</a></li>
                                </ul>
                            </div>';
                } elseif ($item->status == 'applied') {
                    $row .= '<button type="button" style="background-color:#D9D946" class="btn btn-sm btn-block">Applied</button>';
                } elseif ($item->status == 'interview') {
                    $row .= '<button type="button" class="btn btn-dark btn-sm active BtnClass btn-block">Interview</button>';
                } elseif ($item->status == 'hired') {
                    $row .= '<button type="button" class="btn btn-success btn-sm activeBtnClass same btn-block">Hired</button>';
                } elseif ($item->status == 'rejected') {
                    $row .= '<button type="button" class="btn btn-danger btn-sm activeBtnClass btn-block">Rejected</button>';
                }
                if ($item->status != 'pending' && $item->status != 'applied') {
                    $row .= '<br><small class="text-info">' . $item->updated_at->format("d-M-Y") . '</small>';
                }
                $data[$key][] = '' . $row . '';
                $titleapp = !empty($item->rejectedReason->title) ? $item->rejectedReason->title : '';
                $data[$key][] = '<td class="my-text">' . $titleapp . '';
                $row = '';
                $row .= '<td class="my-text">';
                if (isset($item->skill_accuracy)) {
                    $row .= '<div class="btn-group" role="group" id="status-wrapper-' . $item->id . '">
                                <button type="button" class="btn btn-sm ' . $color . '" id="status-label-' . $item->id . '">' . $item->skill_accuracy . '</button>
                                <button type="button" class="btn btn-sm ' . $color . ' dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="ms-1" style="font-size: 10px;">&#x25BC;</span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item bg-success text-white" href="javascript:void(0)" onclick="updateSkillStatus(' . $item->id . ', \'Very Accurate\')">Very Accurate</a></li>
                                    <li><a class="dropdown-item bg-warning text-dark" href="javascript:void(0)" onclick="updateSkillStatus(' . $item->id . ', \'Accurate\')">Accurate</a></li>
                                    <li><a class="dropdown-item bg-danger text-white" href="javascript:void(0)" onclick="updateSkillStatus(' . $item->id . ', \'Not Accurate\')">Not Accurate</a></li>
                                </ul>
                            </div>';
                }
                $row .= '';
                $data[$key][] = '' . $row . '';
                $row .= '';
                $row = '';
                if ($item->status != 'pending') {
                    $row .= '<div class="btn-group" role="group"><button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $row .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">';
                    $row .= '<a class="dropdown-item" href="javascript:void(null)" onclick="update_status_model(' . $item->id . ')"><i class="far fa-edit"></i> Update Status</a>';
                    $row .= '</div>';
                    $row .= '</div>';
                }
                $row .= '';
                $data[$key][] = '' . $row . '';
            }
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(
            [
                'draw' => $request->get('draw'),
                'recordsTotal' => $applicants_count_all,
                'recordsFiltered' => $applicants_count,
                'data' => $data
            ]
        );
        die;
    }

    public function approved_applicants(Request $request)
    {
        if (!empty($request->post('skill_accuracy')) && !empty($request->post('applicants_id'))) {
            $applicants = Applicant::find($request->post('applicants_id'));
            $applicants->status = 'applied';
            $applicants->skill_accuracy = $request->post('skill_accuracy');
            if ($applicants->save()) {

                $colorClass = match ($applicants->skill_accuracy) {
                    'Accurate' => 'btn-warning',
                    'Very Accurate' => 'btn-success',
                    'Not Accurate' => 'btn-danger',
                    default => 'btn-secondary',
                };
                
                $data['url'] = url('/account/applicants');
                $data['name'] = $applicants->User->name;
                $data['job_title'] = $applicants->post->title;
                $post_url = UrlGen::post($applicants->post);
                $data['job_url'] = $post_url;
                $companyDescription = Helper::companyDescriptionData($data, 'post_apply');
                if(!empty($companyDescription)){
                    Helper::activity_log($companyDescription,$applicants->post->user_id);
                }
                $pending = Applicant::get_applicant_counts_by_status('pending');
                
                return response()->json([
                    'success' => true,
                    'message' => 'Approved Successfully',
                    'color_class' => $colorClass,
                    'pending' => $pending
                ]);
            } else {
                return response()->json(['success' => false, 'message' => 'Please Try Again'], 400);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Please Try Again'], 400);
        }

    }

    public function reject_bulk_applicant(Request $request)
    {
        $applicants_ids = explode(',', $request->post('applicant_ids'));
        if (!empty($applicants_ids)) {
            foreach ($applicants_ids as $applicants_id) {
                if (!empty($applicants_id)) {
                    $applicant = Applicant::find($applicants_id);
                    if (!empty($applicant)) {
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