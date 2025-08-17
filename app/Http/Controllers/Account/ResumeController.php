<?php

namespace App\Http\Controllers\Account;

use App\Helpers\Helper;
use App\Models\City;
use App\Models\ContactCardViewLog;
use App\Models\EmployeeSkill;
use App\Models\Notification;
use App\Models\Post;
use App\Models\Unlock;
use App\Models\User;
use App\Models\User_type;
use App\Models\UserResume;
use DB;
use Illuminate\Http\Request;
use Mail;
use Response;

class ResumeController extends AccountBaseController
{
    public $pagePath = 'resumes';
    private $perPage = 10;

    public function __construct()
    {
        parent::__construct();

        $this->perPage = (is_numeric(config('settings.listing.items_per_page'))) ? config('settings.listing.items_per_page') : $this->perPage;
        view()->share('pagePath', $this->pagePath);
    }

    public function getPage($pagePath)
    {
        view()->share('pagePath', $pagePath);
        switch ($pagePath) {
            case 'resumes':
                return $this->user_resume();
                break;
            case 'cv-viewed':
                return $this->cv_viewed();
                break;
            default:
                abort(404);
        }
    }


    public function user_resume()
    {
        $pagePath = 'resumes';
        if (auth()->user()->user_type_id == 1) {
            flash(t("Permission Error.."))->error();
            return redirect('/');
        }
        $resume = User::where('id', auth()->user()->id)->first();
        $new_resume = UserResume::check_new_user_cv(auth()->user()->id);
        if (!empty($new_resume) && ($new_resume->is_approved == 2 || $new_resume->is_approved == 1)) {
            UserResume::update_read_status($new_resume->id);
        }
        Notification::update_read_status('resume');

        view()->share('pagePath', $pagePath);
        view()->share([
            'title' => t('My CV'),
            'description' => t('My CV'),
            'keywords' => t('My CV'),
            // Add more variables as needed
        ]);
        return view('account.resume.index')->with(['resume' => $resume, 'new_resume' => $new_resume]);
    }

    public function cv_viewed()
    {
        $pagePath = 'cv-viewed';
        $data['unlock_users'] = ContactCardViewLog::where(['user_id' => auth()->user()->id])->orderby('created_at', 'desc')->paginate(15);
        view()->share('pagePath', $pagePath);
        view()->share([
            'title' => t('Who Viewed Your CV'),
            'description' => t('Who Viewed Your CV'),
            'keywords' => t('Who Viewed Your CV'),
            // Add more variables as needed
        ]);

        return Appview('account.cv_viewed')->with('data', $data);
    }

    public function searchresumes(Request $request)

    {
        if (!isset($request->cat) || !isset($request->country) || !isset($request->keyword) || !isset($request->city) || !isset($request->limit)) {
            return redirect()->back();
        }
        $pagePath = 'search-resumes';
        if (!empty($request->all())) {
            $data['search_cv'] = User::get_employee_list($request);
        }
        $data['emp_skills'] = EmployeeSkill::getAllskillWithEmplyeeCount();
        $data['countries'] = Country::get_all_country_with_employee_count();
        if (!empty(request()->get('country'))) {
            $data['cities'] = City::get_city_by_country_with_employee_count(request()->get('country'));
        }
        view()->share('pagePath', 'search-resumes');
        return view('account.search-resume')->with('data', $data);
    }

    public function get_city_by_country(Request $request)
    {
        $country_code = $request['country_code'];
        if (!empty($country_code)) {
            $city = City::where('country_code', $country_code)->get();
            echo json_encode($city);
            die;
        }
    }


    public function show($id)
    {
        return redirect(config('app.locale') . '/account/resumes/' . $id . '/edit');
    }

    public function reupload_resume(Request $request)
    {
        $user_id = $request->input('user_id');
        $check_unapproved_Cv = UserResume::check_unapproved_user_cv($user_id);
        if ($check_unapproved_Cv) {
            flash("You have already a unapproved Cv in process please wait until first one is approved.")->error();
            return back()->withInput();
        }
        if ($request->hasFile('reupload_resume')) {
            $cv = $request->file('reupload_resume');

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
        } else {
            flash("Please upload pdf file")->error();
            return back()->withInput();
        }
        if ($request->hasFile('reupload_resume')) {
            $file = $request->file('reupload_resume');
            if (!empty($file)) {
                $file_name = $file->getClientOriginalName();
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
                $file_final_type = $file_ex['1'];
                if (!file_exists(public_path('/') . 'storage/employee_cv/' . $user_id)) {
                    mkdir(public_path('/') . 'storage/employee_cv/' . $user_id, 0777, true);
                }
                $move = move_uploaded_file($file_tmp, public_path('/') . '/storage/' . $fileName = 'employee_cv/' . $user_id . '/' . time() . '.pdf');

                $user_resume = new UserResume;
                $user_resume->user_id = $user_id;
                $user_resume->cv = $fileName;
                $user_resume->save();

//                $user_up = User::where('id', $user_id)->first();
//                $values = array(
//                    'employee_cv' => $fileName,
//                );
//                User::where('id', $user_id)->update($values);
//                $remoteFileUrl_employee_cv = 'public/storage/' . $user_up->employee_cv;
//                if (!empty($user_up->employee_cv)) {
//                    if (file_exists($remoteFileUrl_employee_cv)) {
//                        unlink($remoteFileUrl_employee_cv);
//                    }
//                }

                // re upload cv log
                $name = auth()->user()->name;
                $job_seeker_url = admin_url() . '/job-seekers?search=' . auth()->user()->email;
                $description = "<b>A Employee:<a href='$job_seeker_url'>$name </a> has updated his cv.";
                Helper::activity_log($description);

                flash(t('CV Uploaded Successfully'))->success();
                return redirect('account/resumes/user_resume');
            } else {
                flash(t("Please select pdf file"))->error();
                return back()->withInput();
            }
        }
    }

    public function show_cv($id)
    {
        $set_not_contact_cv_status_to_process = request()->get('set_not_contact_cv_status_to_process');
        if (!empty($set_not_contact_cv_status_to_process)) {
            User::where('id',$id)->update(['is_approved_no_contact_cv'=>3]);
        }

        if (!empty(request()->get('type')) && request()->get('type') != 'preview') {
            if (request()->get('type') == 'new_cv') {
                $user_resume = UserResume::check_unapproved_user_cv($id);
                if (!empty($user_resume) && !empty($user_resume->cv)) {
                    $headers = ["Content-type:application/pdf"];
                    $file = $user_resume->cv;
                    $filePath = 'storage/' . $file;
                    $outputFilePath = public_path('/') . $filePath;
                    if (!file_exists($outputFilePath)) {
                        flash("User CV Not Exist")->error();
                        return redirect()->back();
                    }
                    // $outputFilePath = public_path("sample_output.pdf");
                    // Helper::fillPDFFile($file, $outputFilePath);
                    if (auth()->user()->is_admin == 1) {
                        return response()->file($outputFilePath, ['Content-Type' => 'application/pdf']);
                    }
                }
            } elseif (request()->get('type') == 'cv_no_contact') {

                $user = User::find($id);
                if (!empty($user) && !empty($user->cv_no_contact)) {
                    // $headers = ["Content-type:application/pdf"];
                    // $file = $user->cv_no_contact;
                    // $filePath = 'storage/' . $file;
                    // $outputFilePath = public_path('/') . $filePath;
                    $outputFilePath = Helper::getResumeLink($id,1);
                    $isRemote = str_starts_with($outputFilePath, 'http');
                    // if (!file_exists($outputFilePath)) {
                    //     flash("User CV Not Exist")->error();
                    //     return redirect()->back();
                    // }

                    //   $filePath = storage_path('app/public/') . $file;

                    // $outputFilePath = public_path("sample_output.pdf");
                    // Helper::fillPDFFile($file, $outputFilePath);
                    if (auth()->user()->is_admin == 1) {
                        if ($isRemote) {
                            return redirect()->away($outputFilePath);
                        } else {
                            if (!file_exists($outputFilePath)) {
                                flash("User CV Not Exist")->error();
                                return redirect()->back();
                            }
                            return response()->file($outputFilePath, ['Content-Type' => 'application/pdf']);
                        }                    
                    } elseif (auth()->user()->user_type_id == 1 || auth()->user()->id == $id) {
                        $filename = 'hungry_for_jobs_cv(' . $user->name . ').pdf';

                        if ($isRemote) {
                            return response()->streamDownload(function () use ($outputFilePath) {
                                echo file_get_contents($outputFilePath);
                            }, $filename, ['Content-Type' => 'application/pdf']);
                        } else {
                            if (!file_exists($outputFilePath)) {
                                flash("User CV Not Exist")->error();
                                return redirect()->back();
                            }
                            return response()->download($outputFilePath, $filename, ['Content-Type' => 'application/pdf']);
                        }

                    } else {

                        flash("Permission denied")->error();
                        return redirect()->back();
                    }
                } else {
                    flash("User CV Not Found")->error();
                    return redirect()->back();
                }
            } else {
                flash("User CV Not Found")->error();
                return redirect()->back();
            }
        } else {

            $user = User::find($id);
            if (!empty($user) && !empty($user->employee_cv)) {
                // $headers = ["Content-type:application/pdf"];
                // $file = $user->employee_cv;
                // $filePath = 'storage/' . $file;

                // $outputFilePath = public_path('/') . $filePath;
                $outputFilePath = Helper::getResumeLink($id);
                $isRemote = str_starts_with($outputFilePath, 'http');

                // if (!file_exists($outputFilePath)) {
                //     flash("User CV Not Exist")->error();
                //     return redirect()->back();
                // }
                //   $filePath = storage_path('app/public/') . $file;
                // if (empty(request()->get('set_not_contact_cv_status_to_process')) && request()->get('set_not_contact_cv_status_to_process') != 1) {
                //     $outputFilePath = public_path("sample_output.pdf");
                //     Helper::fillPDFFile($file, $outputFilePath);
                // } else {
                //     $outputFilePath=$destinationPath;
                // }
                
                if (auth()->user()->is_admin == 1 || request()->get('type') == 'preview') {
                    // return response()->file($outputFilePath, ['Content-Type' => 'application/pdf']);
                    if ($isRemote) {
                        return redirect()->away($outputFilePath);
                    } else {
                        if (!file_exists($outputFilePath)) {
                            flash("User CV Not Exist")->error();
                            return redirect()->back();
                        }
                        return response()->file($outputFilePath, ['Content-Type' => 'application/pdf']);
                    }

                } elseif (auth()->user()->user_type_id == 1) {
                    $unlock_users = Unlock::where('user_id', $id)->where('to_user_id', auth()->user()->id)->where('is_unlock', 1)->first();
                    if (empty($unlock_users)) {
                        flash("Permission denied")->error();
                        return redirect()->back();
                    } else {
                        // return Response::download($outputFilePath, 'hungry_for_jobs_cv(' . $user->name . ').pdf', $headers);
                        $filename = 'hungry_for_jobs_cv(' . $user->name . ').pdf';

                        if ($isRemote) {
                            return response()->streamDownload(function () use ($outputFilePath) {
                                echo file_get_contents($outputFilePath);
                            }, $filename, ['Content-Type' => 'application/pdf']);
                        } else {
                            if (!file_exists($outputFilePath)) {
                                flash("User CV Not Exist")->error();
                                return redirect()->back();
                            }
                            return response()->download($outputFilePath, $filename, ['Content-Type' => 'application/pdf']);
                        }

                    }
                } else if (auth()->user()->id == $id) {
                    // return Response::download($outputFilePath, 'hungry_for_jobs_cv(' . $user->name . ').pdf', $headers);
                    $filename = 'hungry_for_jobs_cv(' . $user->name . ').pdf';

                    if ($isRemote) {
                        return response()->streamDownload(function () use ($outputFilePath) {
                            echo file_get_contents($outputFilePath);
                        }, $filename, ['Content-Type' => 'application/pdf']);
                    } else {
                        if (!file_exists($outputFilePath)) {
                            flash("User CV Not Exist")->error();
                            return redirect()->back();
                        }
                        return response()->download($outputFilePath, $filename, ['Content-Type' => 'application/pdf']);
                    }
                } else {

                    flash("Permission denied")->error();
                    return redirect()->back();
                }
            } else {
                flash("User CV Not Found")->error();
                return redirect()->back();
            }
        }
    }

    public function no_contact_cv($id)
    {
        $user = User::find($id);

        $check_post_exist = Post::where('user_id', auth()->user()->id)->where('archived', 0)->first();
        if (empty($check_post_exist)) {
            return response()->json(['error' => 'You have to post atleast one job first before viewing Free no contact CV of employees'], 404);
        }

        if (!empty($user) && !empty($user->cv_no_contact)) {
            // $filePath = public_path('storage/' . $user->cv_no_contact);

            // if (!file_exists($filePath)) {
            //     return response()->json(['error' => 'User CV Not Found'], 404);
            // }

            $outputFilePath = Helper::getResumeLink($id,1);
            $isRemote = str_starts_with($outputFilePath, 'http');

            if ($isRemote) {
                // $urlPath = $outputFilePath;
                $urlPath = url("account/resumes/proxy-cv/$id");
            } else {
                if (!file_exists($outputFilePath)) {
                    return response()->json(['error' => 'User CV Not Found'], 404);
                }
                $urlPath = url('public/storage/' . $user->cv_no_contact);
            }

            $data['employee_name'] = $user->name;
            $data['employee_url'] = url('/profile/').'/'.$user->id;
            $companyDescription = Helper::companyDescriptionData($data, 'free_cv_no_contact');
            if(!empty($companyDescription)){
                Helper::activity_log($companyDescription,auth()->user()->id,'','cv_no_contact');
            }
            return response()->json(['fileUrl' => $urlPath]);
        } else {
            return response()->json(['error' => 'User CV Not Found'], 404);
        }
    }

    public function proxyAwsPdf($id)
    {
        $outputFilePath = Helper::getResumeLink($id, 1);

        if (!str_starts_with($outputFilePath, 'http')) {
            abort(404, 'Invalid remote file path.');
        }

        try {
            $pdfContent = file_get_contents($outputFilePath);

            return response($pdfContent, 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="resume.pdf"');
        } catch (\Exception $e) {
            return response('File not found', 404);
        }
    }


    public function viewCV($id, Request $request)
    {
        $isNoContact = $request->get('type') === 'cv_no_contact';

        if($isNoContact){
            $outputFilePath = Helper::getResumeLink($id,1);
        }else{
            $outputFilePath = Helper::getResumeLink($id);

        }
        $isRemote = str_starts_with($outputFilePath, 'http');

        if ($isRemote) {
            return redirect()->away($outputFilePath);
        } else {
            if (!file_exists($outputFilePath)) {
                flash("User CV Not Exist")->error();
                return redirect()->back();
            }
            return response()->file($outputFilePath, ['Content-Type' => 'application/pdf']);
        }
    }

}
