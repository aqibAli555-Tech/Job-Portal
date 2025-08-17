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
use App\Models\Notification;
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
use File;

class EmployeeController extends AdminBaseController
{
    use VerificationTrait;

    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $skill_sets = EmployeeSkill::getAllskillWithEmplyeeCount($request);
        $countries = Country::get_all_country_with_employee_count();
        $users = User::get_employees($request, 2);
        $posts = Post::get_active_post();
        $unlock_contact_counts = User::get_unlock_contact_counts();

        return view('vendor.admin.employee.index', compact('users', 'skill_sets', 'countries', 'posts','unlock_contact_counts'));
    }

    public function get_employee_last_logged_in()
    {
        $users = User::orderBy('created_at', 'DESC')->get();
        return view('vendor.admin.employee.index', compact('users'));
    }

    public function verify_employee_phone()
    {
        $users = User::verified_employee_phone();
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

    public function edit_employee($id)
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

        return view('vendor.admin.employee.edit', compact('user', 'city', 'country', 'gender', 'employee_skills', 'nationality', 'availability', 'visa_types'));
    }

    public function city_dependency(Request $request)
    {
        $country_name = $request->input('country_name');
        $city = City::where('country_code', $country_name)->orderBy('name', 'ASC')->get();
        return response()->json($city);
    }

    public function update_employee(Request $request)
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

    private function create_activity_log($request)
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

    public function send_email(Request $request)
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

    public function employee_delete(Request $request)
    {
        $users = User::get_allusers($request);
        return view('vendor.admin.employee.user_delete', compact('users'));
    }

    public function delete($id)
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


    public function delete_employee_all_records()
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
                flash('Post Share Successfully')->info();
                return redirect()->back();
            } else {
                flash('Please try again.')->error();
                return redirect()->back();
            }
        }

    }

    public function get_top_country_employee()
    {
        $top_country_employees = Country::get_all_country_employee_count(2);

        return view('vendor.admin.employee.top_country_employee', compact('top_country_employees'));
    }

    public function get_top_nationality_employee()
    {
        $top_nationality_employees = Nationality::get_all_nationality_employee_count(2);

        return view('vendor.admin.employee.top_nationality_employee', compact('top_nationality_employees'));
    }

    public function approve_new_cv(Request $request)
    {
        if (UserResume::update_status($request->cv_id, $request->cv_status)) {

            flash('CV status change successfully')->info();
            return redirect()->back();
        } else {
            flash('Please try again.')->error();
            return redirect()->back();
        }
    }

    public function set_skill_status_as_Read(Request $request)
    {
        if (UserSkills::update_read_status()) {
            flash('Mark All Skills as Read')->info();
            return redirect()->back();
        } else {
            flash('Please try again.')->error();
            return redirect()->back();
        }
    }

    public function upload_no_contact_cv(Request $request)
    {
        if ($request->hasFile('cv')) {
            $cv = $request->file('cv');
            $user_id = $request->user_id;
            if ($cv->getSize() > 6000000) {
                flash("Exceeded filesize limit. You can upload maximum 5 MB files")->error();
                return back()->withInput();
            }

            if (empty($cv->getFilename())) {
                flash("Please select pdf file")->error();
                return back()->withInput();
            }

            $filename = $cv->getRealPath();

            $handle = fopen($filename, "r");
            $contents = fread($handle, filesize($filename));
            fclose($handle);
            if (stristr($contents, "/Encrypt")) {
                flash("Please upload valid pdf file")->error();
                return back()->withInput();
            }

            $result = Helper::validatepdffile($cv);
            if ($result == false) {
                flash("Please upload valid pdf file")->error();
                return back()->withInput();
            }

            $file = $cv;
            if (!empty($file)) {
                $file_name = $file->getClientOriginalName();

                $check_name=explode('.',$file_name);
                if($check_name[0] != $request->user_id.'_edited'){
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
                    'is_approved_no_contact_cv' => 0,
                    'cv_no_contact_rejected_reason'=>null,
                    'is_resume_uploaded_on_aws' => 0,
                );

                User::where('id', $user_id)->update($values);
                $remoteFileUrl_employee_cv = 'public/storage/' . $user_up->cv_no_contact;
                if (!empty($user_up->cv_no_contact)) {
                    if (file_exists($remoteFileUrl_employee_cv)) {
                        unlink($remoteFileUrl_employee_cv);
                    }
                }
                flash(t('CV Uploaded Successfully'))->success();
                return redirect()->back();
            }
        } else {
            flash("Please upload pdf file")->error();
            return redirect()->back();
        }

    }

    public function approve_cv_no_contact(Request $request)
    {
        if (!empty($request->user_id_no_contact_cv)) {
            $user_up = User::where('id', $request->user_id_no_contact_cv)->first();
            if ($request->cv_status == 1) {
                $values = array(
                    'is_approved_no_contact_cv' => 1,
                    'cv_no_contact_rejected_reason'=>null,
                );
            } else {
                $values = array(
                    'cv_no_contact' => null,
                    'is_approved_no_contact_cv' => 2,
                    'cv_no_contact_rejected_reason' => $request->rejected_reason,
                    'is_resume_uploaded_on_aws' => 0,
                );
                $remoteFileUrl_employee_cv = 'public/storage/' . $user_up->cv_no_contact;
                if (!empty($user_up->cv_no_contact)) {
                    if (file_exists($remoteFileUrl_employee_cv)) {
                        unlink($remoteFileUrl_employee_cv);
                    }
                }
            }
            if (User::where('id', $user_up->id)->update($values)) {
                flash('CV status update Successfully')->success();
                return redirect()->back();
            } else {
                flash("Unable to update status please try again later")->error();
                return redirect()->back();
            }
        } else {
            flash("Unable to update status please try again later")->error();
            return redirect()->back();

        }
    }

    public function delete_no_contact_cv(Request $request)
    {
        $pincode = $request->pincode;
        if (!empty($pincode) && $pincode == 'hungry') {
            $id = $request->id;
            if (!empty($id)) {
                $values = array(
                    'cv_no_contact' => null,
                    'is_approved_no_contact_cv' => 0,
                    'cv_no_contact_rejected_reason'=>null,
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
        $employee = User::get_employee_no_contact_cv();
        return view('vendor.admin.employee.bulk_download_cv', compact('employee'));
    }

    public function verify_employee_cv(Request $request)
    {
        if (!empty($request->get('file_name'))) {
            $filename = $request->get('file_name');
            $new_file = explode('.', $filename);
            if (!empty($new_file[0])) {
                $file_with_id_and_edit = $new_file[0];
                if($file_with_id_and_edit){
                    $file_only_with_id = explode('_', $file_with_id_and_edit);
                    $user_data=User::get_user_by_id($file_only_with_id);
                    if(!empty($user_data)){
                        if($user_data->id.'_edited'==$file_with_id_and_edit){
                            $data['name']=$user_data->name;
                            $data['id']=$user_data->id;
                            $response = array(
                                'status' => true,
                                'message' => '',
                                'data' => $data,
                            );

                        }else{
                            $response = array(
                                'status' => false,
                                'message' => 'File not not matching with user id',
                                'data' => '',
                            );
                        }
                    }else{
                        $response = array(
                            'status' => false,
                            'message' => 'User not found against this file',
                            'data' => '',
                        );

                    }
                }else{
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

}

?>