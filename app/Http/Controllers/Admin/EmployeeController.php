<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Helpers\UrlGen;
use App\Http\Controllers\Auth\Traits\VerificationTrait;
use App\Http\Requests\Admin\Request;
use App\Models\Allsaved_resume;
use App\Models\Applicant;
use App\Models\Availability;
use App\Models\City;
use App\Models\Company;
use App\Models\Country;
use App\Models\EmployeeSkill;
use App\Models\Favoriteresume;
use App\Models\Gender;
use App\Models\Nationality;
use App\Models\Payment;
use App\Models\Post;
use App\Models\Resume;
use App\Models\SavedPost;
use App\Models\SavedSearch;
use App\Models\Thread;
use App\Models\ThreadMessage;
use App\Models\ThreadParticipant;
use App\Models\Unlock;
use App\Models\User;
use App\Models\UserResume;
use App\Models\UserSkills;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class EmployeeController extends AdminBaseController
{
    use VerificationTrait;

    public function __construct()
    {
        parent::__construct();
    }

    public function ajax(Request $request)
    {
        $data = [];
        $users = User::get_employees($request, 2);

        $filtered = User::get_employees_filter_count($request, 2);
        $employees_count = User::get_employees_count(2);
        foreach ($users as $key => $user) {
            $nationalityDataName = "";
            if (!empty($user->nationalityData->name)) {
                $nationalityDataName = $user->nationalityData->name;
            }
            $userWhatsAppNumber = '';
            if (!empty($user->UserSetting) && !empty($user->UserSetting->whatsapp_number)) {
                $userWhatsAppNumber = '<br>WhatsApp : ' . $user->UserSetting->whatsapp_number;
            }


            $visa = '';
            if (!empty($user->visa)) {
                $visa = '<div class="mb-1">Work Visa&nbsp;<span class="badge text-bg-danger">&nbsp;' . $user->visa . "</span></div>";
            }
            $visa_name = '';
            if (!empty($user->countryVisa->name)) {
                $visa_name = '<div class="mb-1">Country of Work Visa &nbsp;<span class="badge text-bg-danger"> ' . $user->countryVisa->name . "</span></div>";
            }

            $visa_number = '';
            if (!empty($user->visa_number) && !empty($user->country_work_visa) && $user->country_work_visa == 'KW') {
                $visa_number = '<div>Work Visa Type&nbsp;<span class="badge text-bg-danger">' . $user->visa_number . "</></div>";
            }

            $visa_html = "<br>";
            if (!empty($visa) || !empty($visa_name) || !empty($visa_number)) {
                $visa_html = "<div class='bg-banner p-2 mt-1'>" . $visa . $visa_name . $visa_number . '</div>';
            }

            $skills = '';
            if (!empty($user->skill_set)) {
                foreach (explode(',', $user->skill_set) as $value) {
                    $skills .= '<span class="badge text-bg-primary">' . $value . '</span>&nbsp;';
                }
            }
            $new_skills = UserSkills::get_not_Read_user_skill($user->id);
            if (!empty($new_skills)) {
                $skills .= '<b>Old Skills:</b>';
                foreach (explode(',', $new_skills->old_skills) as $newvalue) {
                    $skills .= '<span class="badge text-bg-primary">' . $newvalue . '</span>&nbsp;';
                }
            }

            $applicants = $user->applicant->count();
            $total_applied_jobs = '<a href="' . admin_url() . '/applicants' . '?search=' . $user->email . '">' . $applicants . '</a>';
            $impersonate = '';
            if (auth()->user()->user_type_id != 4) {
                $impersonate = url('impersonate/take/' . $user->id);
            }
            $counter = $key + 1;

            $data[$key][] = '<div class="text-center cursor-pointer"><input type="checkbox" name="employee_ids" class="checkbox" onclick="SingletoggleCheckbox(this)" value="' . $user->id . '"></div>';
            $data[$key][] = '<table><tr><td><img width="55" height="55" src="' .  Helper::getImageOrThumbnailLink($user) . '" alt=""></td><td style="padding-left: 5px"><p class="card-text"><strong><span class="badge badge-success"># ' . $user->id . '</span>&nbsp;<img height="20" alt="' . $user->country_code . '" src="' . url()->asset('images/flags/16/' . strtolower($user->country_code) . '.png') . '"/>&nbsp;' . $user->name . '</strong><br>' . $user->email . "<br>Nationality : " . $nationalityDataName . $userWhatsAppNumber . '<br>' . date('d M-Y h:i A', strtotime($user->created_at)) . '</p></td></tr></table>';
            $data[$key][] = '<div class="card bg-transparent shadow-none flex-row flex-wrap m-0"><div class="card-block border-0 p-0">' . $skills . $visa_html . '<small class="text-warning-emphasis"><b>Applied Jobs : ' . $total_applied_jobs . '</b></small><br><small class="text-info">Last Login : ' . date('d M-Y h:i A', strtotime($user->last_login_at)) . '</small></div></div>';

            $cv = '';
            if (!empty($user->employee_cv)) {
                $cv .= '<a class="btn btn-sm btn-light my-1" href="' . url('account/resumes/show_cv/' . $user->id) . '" target="_blank"><i class="fa fa-download"></i> CV</a>';
            }
            $new_resume = UserResume::check_unapproved_user_cv($user->id);
            if (!empty($new_resume)) {
                $cv .= '<a class="btn btn-sm btn-light mb-1" href="' . url('account/resumes/show_cv/' . $user->id . '?type=new_cv') . '" target="_blank"><i class="fa fa-download"></i>New CV</a>&nbsp<a href="javascript:void(null)" onclick="approve_cv(' . $new_resume->id . ')">&nbsp;<i class="fa fa-cog"></i></a>';
            }

            if (!empty($user->cv_no_contact)) {
                $cv .= '<br>';

                if ($user->is_approved_no_contact_cv == 1) {
                    $class = 'btn-success';
                } else {
                    $class = 'btn-warning';
                }
                $cv .= '&nbsp;<a class="btn btn-sm ' . $class . '" href="' . url('account/resumes/show_cv/' . $user->id . '?type=cv_no_contact') . '" target="_blank"><i class="fa fa-download"></i> CV No Contact</a>';
                if (auth()->user()->user_type_id != 4) {
                    if (empty($user->is_approved_no_contact_cv)) {
                        $cv .= '&nbsp;<a class="btn btn-sm ' . $class . '" href="javascript:void(null)" onclick="approve_cv_no_contact(' . $user->id . ')" class=""><i class="fa fa-cog"></i></a>';
                    }
                    if ($user->is_approved_no_contact_cv == 1) {
                        $cv .= '&nbsp;<a  class="btn btn-sm ' . $class . '" href="javascript:void(0)" onclick="delete_no_contact_cv(' . $user->id . ')"><i class="fa fa-trash-alt"></i></a>';
                    }
                }
            }
            // if (!empty($user->cv_no_contact) && $user->is_approved_no_contact_cv == 5) {
            //     $cv .= '&nbsp;<a  class="btn btn-sm btn-outline-warning" target="_blank" href="' . admin_url('compare-cv/' . $user->id) . '" ><i class="fa fa-columns"></i>&nbsp; Compare CV</a>';
            // }



            if (auth()->user()->user_type_id != 4) {
                // if ($user->is_approved_no_contact_cv == 0) {
                //     $cv .= '&nbsp;<a  class="btn btn-sm btn-outline-warning" target="_blank" href="' . admin_url('compare-cv/' . $user->id) . '" ><i class="fa fa-columns"></i>&nbsp; Compare CV</a>';
                // }
                $cv .= '&nbsp;<a class="btn btn-sm btn-primary" href="' . $impersonate . '" data-toggle="tooltip" data-original-title="Impersonate this user"><i class="fas fa-sign-in-alt"></i></a>';
            }
            $cv .= '<div class="dropdown d-inline">
                        <div class="btn-group" role="group">
                            <button id="btnGroupDrop' . $counter . '" type="button" class="btn btn-sm btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Action</button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop' . $counter . '">';

            if (auth()->user()->user_type_id != 4) {
                $cv .= '<a class="dropdown-item" href="' . admin_url('edit_employee/' . $user->id) . '"><i class="fas fa-edit"></i> Edit</a>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="send_email(' . $user->id . ')"><i class="fas fa-envelope"></i> ' . trans('admin.Send Email') . '</a>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="delete_employee(' . $user->id . ')"><i class="fas fa-trash-alt"></i> Delete</a>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="reset_pass(' . $user->id . ')"><i class="fas fa-key"></i> Reset Password</a>';
            }

            $cv .= '<a class="dropdown-item" href="javascript:void(0)" onclick="update_cv_status(' . $user->id . ')"><i class="fas fa-eraser"></i> Mark CV Status As Error</a>
                            </div>
                        </div>
                    </div>';

            $cv .= '</div>';
            if (!empty($user->cv_no_contact_rejected_reason)) {
                $cv .= '<br><span>CV No Contact Rejected Reason: <br><b>' . $user->cv_no_contact_rejected_reason . '</b> </span>';
            }
            if (!empty($user->is_approved_no_contact_cv == 4)) {
                $cv .= '<br><span class="badge text-bg-danger">CV Staus: Error</span>';
            }

            $data[$key][] = $cv;

        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(
            [
                'draw' => $request->get('draw'),
                'recordsTotal' => $employees_count,
                'recordsFiltered' => $filtered,
                'data' => $data
            ]
        );
        die;
    }

    public function index2(Request $request)
    {
        $skill_sets = EmployeeSkill::getAllskillWithEmplyeeCount($request);
        $countries = Country::get_all_country_with_employee_count();
        $nationality = Nationality::get_nationalities_with_employee_count();
        $posts = Post::get_active_post();
        $unlock_contact_counts = User::get_unlock_contact_counts();

        $title = 'Job Seekers';
        $breadcumbs = [
            [
                'title' => 'Dashboard',
                'link' => admin_url('dashboard')
            ],
            [
                'title' => 'Job Seekers',
                'link' => 'javascript:void(0)'
            ]
        ];

        return view('admin.jobs_seeker.list', compact('skill_sets', 'nationality', 'countries', 'posts', 'unlock_contact_counts', 'title', 'breadcumbs'));
    }

    public function index(Request $request)
    {
        $title = 'Job Seekers';
        $breadcumbs = [
            [
                'title' => 'Dashboard',
                'link' => admin_url('dashboard')
            ],
            [
                'title' => 'Job Seekers',
                'link' => 'javascript:void(0)'
            ]
        ];

        return view('admin.jobs_seeker.list', compact('title', 'breadcumbs'));
    }

    public function index1(Request $request)
    {
        $skill_sets = EmployeeSkill::getAllskillWithEmplyeeCount($request);
        $countries = Country::get_all_country_with_employee_count();
        $users = User::get_employees($request, 2);
        $posts = Post::get_active_post();
        $unlock_contact_counts = User::get_unlock_contact_counts();

        return view('vendor.admin.employee.index', compact('users', 'skill_sets', 'countries', 'posts', 'unlock_contact_counts'));
    }


    public function get_employee_last_logged_in()
    {
        $users = User::orderBy('created_at', 'DESC')->get();
        return view('vendor.admin.employee.index', compact('users'));
    }

    public function verify_employee_phone($request)
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

    public function verify_employee_email()
    {
        $users = User::verified_employee_email();
        if ($users) {
            flash('Updated Successfully')->info();
            echo 1;
            die;
        } else {
            flash('Please Trey Agian')->info();
            echo 1;
            die;
        }

    }

    public
    function edit_employee($id)
    {
        $user = User::find($id);
        $nationality = Nationality::pluck('id', 'name');
        $availability = Availability::select('id', 'name')->orderBy('id')->where('status', 1)->get();
        $country = Country::orderBy('name', 'ASC')->get();
        $visa_types = array(
            array("Visa 18 (Normal/Professional)" => t("Visa 18 (Normal/Professional)")),
            array("Visa 18 (Mubarak AlKabeer/Small Business)" => t("Visa 18 (Mubarak AlKabeer/Small Business)")),
            array("Visa 18 (VIP/Golden)" => t('Visa 18 (VIP/Golden)')),
            array("Visa 18 (Other)" => t('Visa 18 (Other)')),
            array("Visa 22 (Family)" => t('Visa 22 (Family)')),
        );

        $city = City::where('country_code', $user->country_code)->orderBy('name', 'ASC')->get();
        $gender = Gender:: all();
        $employee_skills = EmployeeSkill::getAllskill();

        // return view('vendor.admin.employee.edit', compact('user', 'city', 'country', 'gender', 'employee_skills', 'nationality', 'availability', 'visa_types'));
        return view('admin.jobs_seeker.edit', compact('user', 'city', 'country', 'gender', 'employee_skills', 'nationality', 'availability', 'visa_types'));
    }

    public
    function city_dependency(Request $request)
    {
        $country_name = $request->input('country_name');
        $city = City::where('country_code', $country_name)->orderBy('name', 'ASC')->get();
        return response()->json($city);
    }

    public
    function update_employee(Request $request)
    {
        $user = User::find($request->id);
        $user->id = $request->id;
        $user->user_type_id = $request->user_type_id;
        $user->email = $request->email;
        $user->name = $request->name;
        $user->country_code = $request->country_code;
        $user->city = $request->city_code;
        $user->availability = $request->get('availability');
        $user->visa = !empty($request->get('visa')) ? $request->get('visa') : '';
        $user->visa_number = !empty($request->get('visa_number')) ? $request->get('visa_number') : '';
        $user->country_work_visa = !empty($request->get('country_work_visa')) ? $request->get('country_work_visa') : '';
        $user->nationality = !empty($request->get('nationality')) ? $request->get('nationality') : '';
        $user->experiences = !empty($request->get('experiences')) ? $request->get('experiences') : '';

        if (!empty($request->skill_set)) {
            $skills_set = implode(',', $request->skill_set);
        } else {
            $skills_set = [];
        }
        $user->skill_set = $skills_set;
        $this->create_activity_log($request);
        if ($user->save()) {

            flash('Updated Successfully')->info();
            return redirect(admin_url('job-seekers'));
        } else {

            flash('Please Trey Agian')->info();
            return redirect(admin_url('edit_employee  /' . $request->id));
        }
    }

    private
    function create_activity_log($request)
    {
        $user = User::find($request->id);
        $name = $user->name;
        $user_url = admin_url() . '/job-seekers?search=' . $user->email;
        $description = "Admin Updated the Profile details of  Name: <b> <a href='$user_url'>$name</a></b>  <br>";

        $changes = [];
        if (!empty($request->skill_set)) {
            $skills_set = implode(',', $request->skill_set);
        } else {
            $skills_set = [];
        }
        if ($user->name != $request->name) {
            $changes[] = "Name : " . $request->name . " <br>";
        }

        if ($user->availability != $request->availability) {
            $availability = Availability::availability_name_by_id($request->availability);
            $changes[] = "Availability : " . $availability->name . " <br>";
        }


        if ($user->country_code != $request->country_code) {
            $country = Country::where('code', $request->country_code)->first();
            $changes[] = "Country : " . $country->name . " <br>";
        }

        if ($user->city != $request->city_code) {
            $city = City::where('id', $request->city_code)->first();
            $changes[] = "City : " . ($city ? $city->name : '') . " <br>";
        }


        if ($user->skill_set != $skills_set) {
            $changes[] = "Skills Sets : <strong>" . $skills_set . "</strong> <br>";
            $changes[] = "Skills Sets Old: <strong>" . $user->skill_set . "</strong> <br>";
        }
        if ($user->visa != $request->visa) {
            $changes[] = "Visa : <strong>" . $request->visa . "</strong> <br>";
            $changes[] = "Visa Old: <strong>" . $user->visa . "</strong> <br>";

        }

        if (!empty($changes)) {
            $description .= implode(" ", $changes) . "</a>";
            Helper::activity_log($description);
        }
    }

    public
    function send_email(Request $request)
    {
        if (!empty($request['user_id'])) {
            $userData = User::find($request['user_id']);
        }
        if (!empty($userData)) {
            $data['email'] = $userData->email;
            $data['subject'] = $request->subject;
            $data['myName'] = $userData->name;
            $data['content'] = $request->message;
            $data['view'] = 'emails.general_email';
            $data['header'] = $request->subject;
            $helper = new Helper();
            $response = $helper->send_email($data);
            flash("Email send successfully")->success();
            return back();
        } else {
            flash("Email Not send.")->error();
            return back();
        }
    }

    public
    function employee_delete(Request $request)
    {
        $users = User::get_allusers($request);
        return view('vendor.admin.employee.user_delete', compact('users'));
    }

    public
    function delete($id)
    {
        $model = User::find($id);
        $employee_name = $model->name;
        if (!empty($model->user_type_id)) {
            if ($model->user_type_id == 2) {
                $model0 = Unlock::where('user_id', $id)->first();
                $model1 = Applicant::where('user_id', $id)->first();
                $model2 = Favoriteresume::where('user_id', $id)->first();
                $model4 = SavedPost::where('user_id', $id)->first();
                if (!empty($model1->id) || !empty($model2->id) || !empty($model4->id) || !empty($model0->id)) {
                    flash("You can not delete this user")->error();
                    return back();
                } else {
                    User::where('id', $id)->delete();
                    $description = "Admin delete Employee Name : $employee_name";
                    Helper::activity_log($description);
                    flash("Deleted successfully")->success();
                    return back();
                }
            } else if ($model->user_type_id == 1) {
                $model6 = Company::where('user_id', $id)->get();
                $model8 = Applicant::where('to_user_id', $id)->first();
                $model9 = SavedSearch::where('user_id', $id)->first();
                $model10 = Allsaved_resume::where('user_id', $id)->first();
                $model11 = Post::where('user_id', $id)->first();
                $model12 = Unlock::where('to_user_id', $id)->first();
                $model14 = Payment::where('user_id', $id)->first();
                $model5 = Company::where('c_id', $id)->first();

                if (!empty($model5)) {
                    $model13 = Favoriteresume::where('company_id', $model5->id)->first();
                }

                if (!empty($model6) && count($model6) > 1) {
                    flash("You can not delete this user")->error();
                    return back();
                }

                if (!empty($model8->id) || !empty($model9->id) || !empty($model10->id) || !empty($model11->id) || !empty($model12->id) || !empty($model13->id) || !empty($model14->id)) {
                    flash("You can not delete this user")->error();
                    return back();
                } else {
                    Company::where('c_id', $id)->delete();
                    User::where('id', $id)->delete();
                    $description = "Admin delete Employer Name : $employee_name";
                    Helper::activity_log($description);
                    flash("Deleted successfully")->success();
                    return back();
                }

            } else {
                flash("User Not Found.")->error();
                return back();
            }
        } else {
            flash("User Not Found.")->error();
            return back();
        }
    }


    public
    function delete_employee_all_records()
    {
        $pincode = request()->get('pincode');

        if (!empty($pincode) && $pincode == 'hungry') {
            $id = request()->get('id');
            $model = User::find($id);
            $user_all_data = [];
            $user_all_data['user'] = $model;
            if ($model->user_type_id == 2) {
                $applicant = Applicant::where('user_id', $id)->get();
                if (!empty($applicant)) {
                    $user_all_data['applicant'] = $applicant;
                }
                $favoriteresume = Favoriteresume::where('user_id', $id)->get();
                if (!empty($favoriteresume)) {
                    $user_all_data['favoriteresume'] = $favoriteresume;
                }
                $resume = Resume::where('user_id', $id)->get();
                if (!empty($resume)) {
                    $user_all_data['resume'] = $resume;
                }
                $savedPost = SavedPost::where('user_id', $id)->get();
                if (!empty($savedPost)) {
                    $user_all_data['savedPost'] = $savedPost;
                }
                $threadparticipant = ThreadParticipant::where('user_id', $id)->get();
                if (!empty($threadparticipant)) {
                    $user_all_data['threadparticipant'] = $threadparticipant;
                }
            }
            if ($model->user_type_id == 1) {
                $ChildCompany = Company::where('user_id', $id)->get();
                if (!empty($ChildCompany)) {
                    $user_all_data['ChildCompany'] = $ChildCompany;
                }
                $Company = Company::where('c_id', $id)->get();
                if (!empty($Company)) {
                    $user_all_data['Company'] = $Company;
                }
                $CompanyApplicant = Applicant::where('to_user_id', $id)->get();
                if (!empty($CompanyApplicant)) {
                    $user_all_data['CompanyApplicant'] = $CompanyApplicant;
                }
                $CompanySavedSearch = SavedSearch::where('user_id', $id)->get();
                if (!empty($CompanySavedSearch)) {
                    $user_all_data['CompanySavedSearch'] = $CompanySavedSearch;
                }
                $CompanyAllsaved_resume = Allsaved_resume::where('user_id', $id)->get();
                if (!empty($CompanyAllsaved_resume)) {
                    $user_all_data['CompanyAllsaved_resume'] = $CompanyAllsaved_resume;
                }
                $CompanyPost = Post::where('user_id', $id)->get();
                if (!empty($CompanyPost)) {
                    $user_all_data['CompanyPost'] = $CompanyPost;
                }
                $CompanyUnlock = Unlock::where('to_user_id', $id)->get();

                if (!empty($CompanyUnlock)) {
                    $user_all_data['CompanyUnlock'] = $CompanyUnlock;
                }
                $CompanyPayment = Payment::where('user_id', $id)->get();
                if (!empty($CompanyPayment)) {
                    $user_all_data['CompanyPayment'] = $CompanyPayment;
                }
                $threadparticipant = ThreadParticipant::where('user_id', $id)->get();
                if (!empty($threadparticipant)) {
                    $user_all_data['threadparticipant'] = $threadparticipant;
                }
            }
            $file = $id . '-' . time() . rand() . '_file.json';
            $destinationPath = public_path() . "/delete_users/";
            if (!is_dir($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            File::put($destinationPath . $file, json_encode($user_all_data));

            if (!empty($model->user_type_id)) {
                if ($model->user_type_id == 2) {
                    $model1 = Applicant::where('user_id', $id)->delete();
                    $model2 = Favoriteresume::where('user_id', $id)->delete();
                    $model3 = Resume::where('user_id', $id)->delete();
                    $model4 = SavedPost::where('user_id', $id)->delete();
                    ThreadParticipant::where('user_id', $id)->delete();

                    $description = "<b>A Employee: $model->name has been deleted by admin.";

                } elseif ($model->user_type_id == 1) {
                    $model6 = Company::where('user_id', $id)->delete();
                    $model7 = Company::where('c_id', $id)->delete();
                    $model8 = Applicant::where('to_user_id', $id)->delete();
                    $model9 = SavedSearch::where('user_id', $id)->delete();
                    $model10 = Allsaved_resume::where('user_id', $id)->delete();
                    $model11 = Post::where('user_id', $id)->delete();
                    $model12 = Unlock::where('to_user_id', $id)->delete();
                    $model14 = Payment::where('user_id', $id)->delete();
                    $threads = ThreadParticipant::where('user_id', $id)->groupBy('id')->get();
                    if (!empty($threads)) {
                        foreach ($threads as $value) {
                            Thread::where('id', $value->thread_id)->delete();
                            ThreadMessage::where('thread_id', $value->thread_id)->delete();
                        }
                    }
                    ThreadParticipant::where('user_id', $id)->delete();
                    $description = "<b>A Employer: $model->name has been deleted by admin.";

                }
                Helper::activity_log($description);

                $res = User::where('id', $id)->delete();
                if ($res) {
                    $response = array(
                        'status' => true,
                        'message' => t("Deleted successfully"),
                        'url' => '',
                    );
                    return response()->json($response);
                } else {
                    $response = array(
                        'status' => false,
                        'message' => t("please try again"),
                        'url' => '',
                    );
                    return response()->json($response);
                }
            } else {

                $response = array(
                    'status' => false,
                    'message' => t("User Not Found"),
                    'url' => '',
                );
                return response()->json($response);
            }
        } else {

            $response = array(
                'status' => false,
                'message' => t("Your Pin Code Invalid"),
                'url' => '',
            );
            return response()->json($response);
        }

    }

    public function send_bulk_email(Request $request)
    {
        if (!empty($request['posts'])) {
            $post = Post::with(['company'])->where('id', $request['posts'])->first();
        }
        $user_ids = explode(",", $request['user_ids']);
        if (!empty($user_ids)) {
            foreach ($user_ids as $value) {
                if (!empty($value)) {
                    $user = User::find($value);
                }
                if (!empty($post) && !empty($user)) {
                    $data['url'] = UrlGen::post($post);
                    $data['email'] = $user->email;
                    $data['subject'] = 'You might be interested in applying to this job!';
                    $data['employee'] = $user->name;
                    $data['position'] = $post->title;
                    $data['company'] = $post->company->name;
                    $data['view'] = 'emails.post_share_bulk';
                    $data['header'] = 'You might be interested in applying to this job!';
                    $helper = new Helper();
                    $response = $helper->bulk_email_queue($data);
                }
            }
            if ($response) {
                $response_send = array(
                    'status' => true,
                    'message' => 'Emails sent successfully',
                    'url' => '',
                );
                return response()->json($response_send);

            } else {
                $response_send = array(
                    'status' => true,
                    'message' => 'Error while sending emails.',
                    'url' => '',
                );
                return response()->json($response_send);
            }
        }
    }

    public
    function get_top_country_employee()
    {
        $top_country_employees = Country::get_all_country_employee_count(2);

        $title = 'Top Country Employee';
        $breadcumbs = [
            [
                'title' => 'Dashboard',
                'link' => admin_url('dashboard')
            ],
            [
                'title' => 'Top Country Employee',
                'link' => 'javascript:void(0)'
            ]
        ];

        return view('admin.jobs_seeker.top_country_employee', compact('top_country_employees', 'title', 'breadcumbs'));
    }

    public
    function get_top_nationality_employee()
    {
        $top_nationality_employees = Nationality::get_all_nationality_employee_count(2);
        $title = 'Top Nationality Employee';
        $breadcumbs = [
            [
                'title' => 'Dashboard',
                'link' => admin_url('dashboard')
            ],
            [
                'title' => 'Top Nationality Employee',
                'link' => 'javascript:void(0)'
            ]
        ];

        return view('admin.jobs_seeker.top_nationality_employee', compact('top_nationality_employees', 'title', 'breadcumbs'));
    }

    public
    function approve_new_cv(Request $request)
    {
        if (UserResume::update_status($request->cv_id, $request->cv_status)) {

            flash('CV status change successfully')->info();
            return redirect()->back();
        } else {
            flash('Please try again.')->error();
            return redirect()->back();
        }
    }


    public
    function set_skill_status_as_Read(Request $request)
    {
        if (UserSkills::update_read_status()) {
            flash('Mark All Skills as Read')->info();
            return redirect()->back();
        } else {
            flash('Please try again.')->error();
            return redirect()->back();
        }
    }

    public
    function upload_no_contact_cv(Request $request)
    {
        if ($request->hasFile('cv')) {
            $cv = $request->file('cv');
            $user_id = $request->user_id;
            if ($cv->getSize() > 11000000) {
                $response = array(
                    'status' => false,
                    'message' => 'Exceeded filesize limit. You can upload maximum 10 MB files',
                    'url' => '',
                );
                return response()->json($response);
            }

            if (empty($cv->getFilename())) {

                $response = array(
                    'status' => false,
                    'message' => 'Please select pdf file',
                    'url' => '',
                );
                return response()->json($response);
            }

            $filename = $cv->getRealPath();

            $handle = fopen($filename, "r");
            $contents = fread($handle, filesize($filename));
            fclose($handle);
            if (stristr($contents, "/Encrypt")) {
                $response = array(
                    'status' => false,
                    'message' => 'Please upload valid pdf file',
                    'url' => '',
                );
                return response()->json($response);
            }

            $result = Helper::validatepdffile($cv);
            if ($result == false) {

                $response = array(
                    'status' => false,
                    'message' => 'Please upload valid pdf file',
                    'url' => '',
                );
                return response()->json($response);

            }

            $file = $cv;
            if (!empty($file)) {
                $file_name = $file->getClientOriginalName();
                $check_name = explode('.', $file_name);
                if ($check_name[0] != $request->user_id . '_edited') {
                    flash("Your file is not matching with User id")->error();
                    return redirect()->back();
                }
                $file_type = $file->getClientMimeType();
                $file_size = $file->getSize();
                $file_tmp = $file->getRealPath();
                $file_ex = explode("/", $file_type);
                $allowed = array('pdf', 'PDF');
                $filename = $file_name;
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                if (!in_array($ext, $allowed)) {
                    flash(t("Please select pdf file"))->error();
                    return back()->withInput();
                }
                if (!file_exists(public_path('/') . 'storage/employee_cv/' . $user_id)) {
                    mkdir(public_path('/') . 'storage/employee_cv/' . $user_id, 0777, true);
                }
                $fileName = '';
                move_uploaded_file($file_tmp, public_path('/') . '/storage/' . $fileName = 'employee_cv/' . $user_id . '/' . time() . '.pdf');
                $user_up = User::where('id', $user_id)->first();
                $values = array(
                    'cv_no_contact' => $fileName,
                    'is_approved_no_contact_cv' => 5,
                    'cv_no_contact_rejected_reason' => null,
                    'is_resume_uploaded_on_aws' => 0,
                );

                User::where('id', $user_id)->update($values);
                $remoteFileUrl_employee_cv = 'public/storage/' . $user_up->cv_no_contact;
                if (!empty($user_up->cv_no_contact)) {
                    if (file_exists($remoteFileUrl_employee_cv)) {
                        unlink($remoteFileUrl_employee_cv);
                    }
                }

                $response = array(
                    'status' => true,
                    'message' => 'CV Uploaded Successfully',
                    'url' => '',
                );
                return response()->json($response);

            }
        } else {
            $response = array(
                'status' => false,
                'message' => 'Please upload pdf file',
                'url' => '',
            );
            return response()->json($response);
        }

    }

    public
    function approve_cv_no_contact(Request $request)
    {
        if(!empty($request->id) && !empty($request->type)){
            if($request->type == 'admin'){
                $values = array(
                    'is_approved_no_contact_cv' => 1,
                    'cv_no_contact_rejected_reason' => null,
                );
            }
            if($request->type == 'QA'){
                $values = array(
                    'is_approved_no_contact_cv' => 0,
                    'cv_no_contact_rejected_reason' => null,
                );
            }
            if(!empty($values)){
                if (User::where('id', $request->id)->update($values)) {

                    $response = array(
                        'status' => true,
                        'message' => 'CV status update Successfully',
                    );
                    return response()->json($response);

                }
            }               
        }

        $response = array(
            'status' => false,
            'message' => 'Unable to update status please try again later',
        );
        return response()->json($response);  
    }

    public
    function reject_cv_no_contact(Request $request)
    {
        if(!empty($request->id) && !empty($request->type) && !empty($request->rejected_reason)){
            $values = array(
                'cv_no_contact' => null,
                'is_approved_no_contact_cv' => 2,
                'cv_no_contact_rejected_reason' => $request->rejected_reason,
            );
            if (User::where('id', $request->id)->update($values)) {

                $response = array(
                    'status' => true,
                    'message' => 'CV status update Successfully',
                );
                return response()->json($response);

            }              
        }

        $response = array(
            'status' => false,
            'message' => 'Unable to update status please try again later',
        );
        return response()->json($response);  
    }

    public
    function delete_no_contact_cv(Request $request)
    {
        $pincode = $request->pincode;
        if (!empty($pincode) && $pincode == 'hungry') {
            $id = $request->id;
            if (!empty($id)) {
                $values = array(
                    'cv_no_contact' => null,
                    'is_approved_no_contact_cv' => 0,
                    'cv_no_contact_rejected_reason' => null,
                    'is_resume_uploaded_on_aws' => 0,
                );
                $user_up = User::where('id', $id)->first();
                $remoteFileUrl_employee_cv = 'public/storage/' . $user_up->cv_no_contact;
                if (!empty($user_up->cv_no_contact)) {
                    if (file_exists($remoteFileUrl_employee_cv)) {
                        unlink($remoteFileUrl_employee_cv);
                    }
                }
                if (User::where('id', $user_up->id)->update($values)) {
                    $response = array(
                        'status' => true,
                        'message' => "CV Delete Successfully",
                        'url' => '',
                    );
                    return response()->json($response);

                } else {
                    $response = array(
                        'status' => false,
                        'message' => "Unable to delete Cv please try again later",
                        'url' => '',
                    );
                    return response()->json($response);
                }
            } else {
                $response = array(
                    'status' => false,
                    'message' => "Unable to delete Cv please try again later",
                    'url' => '',
                );
                return response()->json($response);
            }
        } else {
            $response = array(
                'status' => false,
                'message' => t("Your Pin Code Invalid"),
                'url' => '',
            );
            return response()->json($response);
        }
    }


    public function bulk_download_cv()
    {
        $user_cvs_data = User::user_cvs_data();
        return view('admin.jobs_seeker.bulk_download_cv', compact('user_cvs_data'));
    }

    public function verify_employee_cv(Request $request)
    {
        if (!empty($request->get('file_name'))) {
            $filename = $request->get('file_name');
            $new_file = explode('.', $filename);
            if (!empty($new_file[0])) {
                $file_with_id_and_edit = $new_file[0];
                if ($file_with_id_and_edit) {
                    $file_only_with_id = explode('_', $file_with_id_and_edit);
                    $user_data = User::get_user_by_id($file_only_with_id);
                    if (!empty($user_data)) {
                        if ($user_data->id . '_edited' == $file_with_id_and_edit) {
                            $data['name'] = $user_data->name;
                            $data['id'] = $user_data->id;
                            $response = array(
                                'status' => true,
                                'message' => '',
                                'data' => $data,
                            );

                        } else {
                            $response = array(
                                'status' => false,
                                'message' => 'File not not matching with user id',
                                'data' => '',
                            );
                        }
                    } else {
                        $response = array(
                            'status' => false,
                            'message' => 'User not found against this file',
                            'data' => '',
                        );

                    }
                } else {
                    $response = array(
                        'status' => false,
                        'message' => 'There is name is issue in file',
                        'data' => '',
                    );
                }
            } else {
                $response = array(
                    'status' => false,
                    'message' => 'Upload a valid file',
                    'data' => '',
                );
            }
        } else {
            $response = array(
                'status' => false,
                'message' => 'File not found',
                'data' => '',
            );
        }
        return response()->json($response);

    }

    public function update_cv_status(Request $request)
    {
        if (User::update_cv_status($request->id, $request->status)) {
            $response = array(
                'status' => true,
                'message' => "Cv Status Updated successfully",
                'url' => '',
            );
            return response()->json($response);
        } else {
            $response = array(
                'status' => false,
                'message' => "Unable to update please try again later",
                'url' => '',
            );
            return response()->json($response);
        }
    }

    // public function compare_cv($id)
    // {

    //     if (empty($id)) {
    //         flash('No user found')->error();
    //         return redirect(admin_url('job-seekers'));
    //     }

    //     if (request()->get('type') == 'next' || request()->get('type') == 'previous') {
    //         $user = User::get_next_or_previous_user(request()->get('type'), $id);
    //         if (empty($user)) {
    //             $user = User::get_next_or_previous_user('first');
    //         }
    //         return redirect(admin_url('compare-cv/' . $user->id));
    //     } elseif (request()->get('type') == 'nextorprevious') {
    //         $user = User::get_next_or_previous_user('next', $id);
    //         if (empty($user)) {
    //             $user = User::get_next_or_previous_user('previous', $id);
    //             if (empty($user)) {
    //                 flash('No user found')->error();
    //                 return redirect(admin_url('job-seekers'));
    //             }
    //         }
    //         return redirect(admin_url('compare-cv/' . $user->id));

    //     } else {
    //         $user = User::user_by_id($id);
    //     }
    //     if (empty($user)) {
    //         flash('No user found')->error();
    //         return redirect(admin_url('job-seekers'));
    //     }

    //     $data['name'] = $user->name;
    //     $data['id'] = $user->id;
    //     $data['email'] = $user->email;
    //     $data['cv'] = $user->employee_cv;
    //     $data['cv_no_contact'] = $user->cv_no_contact;
    //     return view('admin.jobs_seeker.compare_cv', compact('data'));
    // }

    public function compare_cv(Request $request)
    {
        if(!empty($request->type)){
            if($request->type == 'admin' || $request->type == 'QA'){
                $data['type'] = $request->type;
                return view('admin.jobs_seeker.compare_cv', compact('data'));
            }
        }
        flash('No record found')->error();
        return redirect(admin_url('job-seekers'));
    }
            

    public function loadCvData(Request $request)
    {
        
        $type = $request->type;
        $number_of_cv = $request->number_of_cv;
        $last_id = $request->last_id;
        
        $users = User::getApprovalCv($type, $number_of_cv,$last_id);
        if($users->isEmpty()){
            flash('No new user CV found for '.$type)->error();
        }
        
        $cvs = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'cv' => $user->employee_cv,
                'cv_no_contact' => $user->cv_no_contact,
            ];
        });

        return response()->json([
            'cvs' => $cvs,
        ]);
    }

    public function get_static_data()
    {

        $cacheDuration = 60;
        $unlock_contact_counts = Cache::remember('unlock_contact_counts', $cacheDuration, function () {
            return User::get_unlock_contact_counts();
        });
        $response = array(
            'status' => true,
            'data' => $unlock_contact_counts,
        );
        return response()->json($response);
    }

    public function get_filters_data(Request $request)
    {
        $cacheDuration = 60;

        $posts = Cache::remember('active_posts', 60, function () {
            return Post::get_active_post();
        });
        
        $skill_sets = Cache::remember('skills_with_employee_count', $cacheDuration, function () use ($request) {
            return EmployeeSkill::getAllskillWithEmplyeeCount($request);
        });
        
        $countries = Cache::remember('countries_with_employee_count', $cacheDuration, function () {
            return Country::get_all_country_with_employee_count();
        });
        
        $nationality = Cache::remember('nationalities_with_employee_count', $cacheDuration, function () {
            return Nationality::get_nationalities_with_employee_count();
        });


        $data = [
            'skill_sets' => $skill_sets,
            'countries' => $countries,
            'nationality' => $nationality,
            'posts' => $posts,
        ];
        $response = array(
            'status' => true,
            'data' => $data,
        );
        return response()->json($response);
    }

    public function bulk_upload_hidden_detail_cv()
    {
        $title = trans('admin.bulk_upload_cv');
        $directory = storage_path('app/public/employee_cv_temp');
        $data = [];
        $user = [];
        if(is_dir($directory))
        {
            $filesInDirectory = array_diff(scandir($directory), array('..', '.'));
            if (!empty($filesInDirectory)) {
                foreach ($filesInDirectory as $file) {
                    if (pathinfo($file, PATHINFO_EXTENSION) === 'pdf') {
                        $data = $file;
                        // $check_name = explode('.', $file_name);
                        $parts = explode('_', $data);
                        $user = User::find($parts[0]);
                        break;
                    }
                }
            }
        }
        return view('admin.jobs_seeker.bulk_upload_hidden_details_cv', compact('title','user','data'));
    }
    
    
    public function upload_hidden_detail_cv(Request $request)
    {
        if (!$request->hasFile('cv')) {
            return response()->json([
                'status' => false,
                'message' => 'No uploaded file found',
            ]);
        }
    
        $file = $request->file('cv');
        
        if ($file->getSize() > 11000000) {
            return response()->json([
                'status' => false,
                'message' => 'Exceeded file size limit. You can upload a maximum of 10 MB per file.',
            ]);
        }
    
        if (empty($file->getFilename())) {
            return response()->json([
                'status' => false,
                'message' => 'Please select a valid PDF file.',
            ]);
        }
    
        $filename = $file->getRealPath();
        $handle = fopen($filename, "r");
        $contents = fread($handle, filesize($filename));
        fclose($handle);
    
        if (stristr($contents, "/Encrypt")) {
            return response()->json([
                'status' => false,
                'message' => 'Please upload a valid, non-encrypted PDF file.',
            ]);
        }
    
        if (!Helper::validatepdffile($file)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid PDF file.',
            ]);
        }
    
        $file_name = $file->getClientOriginalName();
        $check_name = explode('.', $file_name);
        $filename_parts = explode('_', $check_name[0]);
    
        if (count($check_name) != 2 || strtolower($check_name[1]) != 'pdf' || $filename_parts[1] != 'edited') {
            return response()->json([
                'status' => false,
                'message' => 'File name must follow the format: {id}_edited.pdf.',
            ]);
        }
    
        $user_id = $filename_parts[0];
        $user = User::find($user_id);
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => "User ID $user_id does not exist.",
            ]);
        }
        if (!in_array($user->is_approved_no_contact_cv, [2, 3]) && $user->cv_no_contact != null) {

            if ($user->is_approved_no_contact_cv == 1) {
                $statusShow = 'Admin Approved';
            } else if ($user->is_approved_no_contact_cv == 0) {
                $statusShow = 'QA Approved';
            } else if ($user->is_approved_no_contact_cv == 5) {
                $statusShow = 'QA Approval Pending';
            } else {
                $statusShow = 'Unknown Status';
            }

            return response()->json([
                'status' => false,
                'message' => "User ID $user_id cannot upload the CV as their status is: $statusShow.",
            ]);
        }
    
        $directory = storage_path('app/public/employee_cv_temp');
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
    
        $new_file_name = $file->getClientOriginalName();
        // $outputFilePath = $directory . '/' . $new_file_name;
        // $file_tmp = $file->getRealPath();
    
        $request->file('cv')->move($directory, $new_file_name);
        // Helper::fillPDFFile($file_tmp, $outputFilePath, 1);
    
        return response()->json([
            'status' => true,
            'message' => 'CV uploaded successfully!',
        ]);
    }
    
    
    public function update_uploaded_hidden_detail_cv(Request $request)
    {
        $check_name = explode('.', $request->temp_file_name);
        $user_id = explode('_', $check_name[0])[0];

        $user_up = User::where('id', $user_id)->first();
        if(empty($user_up)){
            $response = array(
                'status' => false,
                'message' => 'User not found.',
            );
        }

        if($request->status === 'approve'){
            $directory_temp = storage_path('app/public/employee_cv_temp');
            $file_temp_path = $directory_temp.'/'.$request->temp_file_name;

            $directory = storage_path('app/public/employee_cv/');
            $user_folder = $directory . $user_id;
            $fileName = 'employee_cv/'.$user_id.'/no_contact_cv.pdf';

            $userFileName = 'no_contact_cv.pdf'; 
            $file_path = $user_folder . '/' . $userFileName;

            if (!is_dir($user_folder)) {
                mkdir($user_folder, 0777, true);
            }

            if (file_exists($file_temp_path)) {
                rename($file_temp_path, $file_path);
            }

            // move_uploaded_file($file_temp_path, $filePath);
            if (file_exists($file_temp_path)) {
                unlink($file_temp_path);
            }

            $values = array(
                'cv_no_contact' => $fileName,
                'is_approved_no_contact_cv' => 5,
                'cv_no_contact_rejected_reason' => null,
            );

        }else{
            $directory_temp = storage_path('app/public/employee_cv_temp');
            $file_temp_path = $directory_temp.'/'.$request->temp_file_name;
            // delete file from employee cv temp
            if (file_exists($file_temp_path)) {
                unlink($file_temp_path);
            }

            $values = array(
                'cv_no_contact' => null,
                'is_approved_no_contact_cv' => 2,
                'cv_no_contact_rejected_reason' => $request->rejected_reason,
            );
        }

        // user save()

        if(!empty($values)){
            if (User::where('id', $user_id)->update($values)) {

                $response = array(
                    'status' => true,
                    'message' => 'CV status update Successfully',
                );
                return response()->json($response);

            }
        }
    }
}


?>