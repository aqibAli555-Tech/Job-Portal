<?php

namespace App\Http\Controllers\account;

use App\Events\AppliedEmails;
use App\Helpers\Helper;
use App\Helpers\UrlGen;
use App\Http\Controllers\Controller;
use App\Http\Requests\SendMessageRequest;
use App\Models\Applicant;
use App\Models\Country;
use App\Models\OptionalSelectedEmails;
use App\Models\Post;
use Exception;

class PostApplyController extends Controller
{
    public function store($postId, SendMessageRequest $request)
    {
        //who_can_apply
        $post_data = Post::with(['postDetail', 'postMeta', 'company', 'user'])->where('id', $postId)->first();

        if (!empty($post_data->postDetail->who_can_apply)) {
            $country = Country::where('code', $post_data->country_code)->first();
            if (!empty($country->name)) {
                $country_name = $country->name;
            } else {
                $country_name = '';
            }
            if ($post_data->postDetail->who_can_apply == 1 && auth()->user()->country_code != $post_data->country_code) {
                $msg = "This company only allows employees (job seekers) living in $country_name to apply for this job";
                flash($msg)->error();
                return redirect()->back();
            }
        }

        if (!empty(auth()->user()->skill_set)) {
            $skills_set = explode(',', auth()->user()->skill_set);
        } else {
            $skills_set = [];
        }
        // Get the Post
        $post = Post::unarchived()->findOrFail($postId);

        $post_skills_set = explode(',', $post_data->postDetail->skills_set);

        $intersect = array_intersect($skills_set, $post_skills_set);

        if ($post_data->postDetail->post_type == 2 && empty($intersect)) {
            $profile_url = url('/account/profile');
            $message = 'This company has limited which Skills Sets can apply to this job post.<br> The only Skills Sets they accept are: (' . $post_data->postDetail->skills_set . ') <br>You can apply to this job by adding the correct Skills Sets by going to your Profile, clicking Edit Profile and adding the correct skills needed to apply for this job. <br> You may also <a  href=' . $profile_url . '> click HERE </a> to Edit Your Profile and add the correct Skills Sets. <br> Please keep in mind, if your CV doesnt match the Skills Sets you have chosen for yourself - then Companies will NOT contact you & hire you! Make sure the Skills Sets you choose are accurate!';
            flash($message)->error();
            return redirect()->back();
        } else {
            // Create Message Array
            $messageArray = $request->all();
            // Logged User
            if (auth()->check() && !empty($post->user)) {
                $employee_cv = auth()->user()->employee_cv;
                if (!empty($employee_cv)) {
                    $applicants_data = Applicant::where('user_id', auth()->user()->id)->where('post_id', $post->id)->first();
                    if (empty($applicants_data)) {
                        $applicants = new Applicant;
                        if (!empty($post)) {

                            $applicants->name = auth()->user()->name;
                            $applicants->email = auth()->user()->email;
                            $applicants->user_id = auth()->id();
                            $applicants->to_user_id = $post['user_id'];
                            $applicants->post_id = $post['id'];
                            $applicants->status = 'pending';
                            $applicants->unlocked = '0';
                            $applicants->save();
                            Helper::add_notification('applicants', $post['user_id']);
                            $msg = t('You have successfully applied to this job,') . $post_data->company->name . t('will get in contact with you if they are interested');
                            $name = auth()->user()->name;
                            $post_url = UrlGen::post($post);
                            $job_seeker_url = admin_url() . '/job-seekers?search=' . auth()->user()->email;
                            $company_url = admin_url() . '/employer?search=' . $post_data->company->name;
                            $company_name = $post_data->company->name;
                            $description = "A Job seeker Name:<b> <a href='$job_seeker_url'>$name</a></b> Apply for a job  <br> Compnay Name: <b> <a href='$company_url'> $company_name </a> </b> <br> Job title: $post->title<br> Click this link to checkout this job: <a href='$post_url'>$post_url</a>";
                            Helper::activity_log($description);
                            
                            flash($msg)->success();
                            $post->email = $post_data->company->email;

                            event(new AppliedEmails($postId, $post['user_id'], auth()->id()));

                            $data['page'] = 'applied_users';
                            $data['from'] = url()->previous();
                            $data['server'] = json_encode(request()->server());
                            $data['request'] = $request;

                            $back_url = url()->previous();
                            if (strpos($back_url, 'latest-jobs') !== false) {
                                $query_param = 'Search Jobs Page';
                            } else if (strpos($back_url, 'companies') !== false) {
                                $query_param = 'Company Profile Page';
                            } else if ($back_url == url()) {
                                $query_param = 'Home Page';
                            } else {
                                $query_param = 'Post Details Page';
                            }
                            $data['quary_parameter'] = array($post->title, $query_param);
                            Helper::page_count_post($data);


                            if ($request->from_main == 1) {
                                return redirect()->back();
                            } else {
                                return redirect(UrlGen::postUri($post));
                            }
                        }

                    } else {
                        $msg = 'You have already applied for this job';
                        flash($msg)->error();
                        return redirect(UrlGen::postUri($post));
                    }
                } else {
                    $msg = 'Please publish a resume first to apply for this job';
                    flash($msg)->error();
                    return redirect(UrlGen::postUri($post));
                }
            } else {
                $msg = 'Post user not found. Please contact admin.';
                flash($msg)->error();
            }
            $errorFound = false;
            // Send a message to publisher
            if (isset($messageArray['post_id'], $messageArray['from_email'], $messageArray['from_name'], $messageArray['body'])) {
                try {
                    if (!isDemo()) {
                        // $this->sendEmployerContactedemail($post);
                        //                      $post->notify(new EmployerContacted($post, $messageArray));
                    }
                } catch (Exception $e) {
                    $errorFound = true;
                    flash($e->getMessage())->error();
                }
            }
            if ($request->from_main == 1) {
                return redirect('latest-jobs?q=&l=');
            } else {
                return redirect(UrlGen::postUri($post));
            }
        }
    }

}