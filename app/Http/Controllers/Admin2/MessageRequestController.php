<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Models\Applicant;
use App\Models\ContactCardViewLog;
use App\Models\EmployeeSkill;
use App\Models\MessageRequest;
use App\Models\Post;
use App\Models\CompanyPackages;
use App\Models\Thread;
use App\Models\ThreadMessage;
use App\Models\ThreadParticipant;
use App\Models\TrackMessageRequest;
use App\Models\Unlock;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Session;

class MessageRequestController extends AdminBaseController
{
    public function message_request(Request $request)
    {
        $data['MessageRequest'] = MessageRequest::get_all_message_request($request);
        return view('vendor.admin.post.message_request', compact('data'));
    }

    public function approved_request(Request $request)
    {
        $status = $request->post('status');
        $requestId = $request->post('request_id');

        if (empty($status) || empty($requestId)) {
            flash('Request Not Found.')->info();
            return redirect()->back();
        }

        $requestData = MessageRequest::get_message_request_by_id($requestId);

        if (empty($requestData)) {
            flash('Request Not Found.')->info();
            return redirect()->back();
        }

        $requestData->status = $status;

        if ($status === 'approved') {
            $post = Post::find($requestData->post_id);

            if (empty($post->category_id)) {
                flash("You do not have any posts at the moment")->error();
                return back();
            }

            $skillSets = EmployeeSkill::find($post->category_id);

            if (empty($skillSets)) {
                flash("Unfortunately, you don't possess the required skills to be approved.")->error();
                return back();
            }

            $this->send_bulk_message($requestData);
        }

        $successMessage = [
                'approved' => 'Approved Successfully',
                'rejected' => 'Rejected Successfully',
                'pending' => 'Pending Successfully',
            ][$status] ?? 'Please Try Again';

        if ($requestData->save()) {
            flash($successMessage)->info();
        }

        return redirect()->back();
    }

    function send_bulk_message($data)
    {
        // get post details
        $post = Post::find($data->post_id);
        // getpost skill by skill id
        $skill_sets = EmployeeSkill::find($post->category_id);
        $postArray = array();
        if (!empty($data->skill_set)) {
            array_unshift($postArray, $data->skill_set);
        }
        // set post skill in special post skills array
        array_unshift($postArray, $skill_sets->skill);
        // get all user against post skill
        $user_data = User::get_users_list_with_post_skills($postArray);

        // create user list if thay have not any chat with this compnay
        if (!empty($user_data)) {
            $user_list = [];
            foreach ($user_data as $user) {
                $thread_data = $this->checkUserChatExist($user->id, $data->user_id);

                if ($thread_data == null || empty($thread_data)) {
                    $user_list[] = $user;
                }
                if (count($user_list) >= $data->number_of_employee) {
                    break;

                }
            }
         
            // buy contact card and chat with employee
            if (!empty($user_list)) {
                foreach ($user_list as $userObj) {
                    $result = $this->UnlockUserProfile($userObj->id, $data->user_id, $data->post_id);
                   
                    if (!empty($result)) {
                        $this->messagesend($userObj->id, $data);
                        $track_request_array = new TrackMessageRequest;
                        $track_request_array->user_id = $userObj->id;
                        $track_request_array->to_user_id = $data->user_id;
                        $track_request_array->post_id = $data->post_id;
                        $track_request_array->request_id = $data->id;
                        $track_request_array->save();
                    }

                }
            }

        }
    }

    // this function use to buy contact card

    function checkUserChatExist($employee, $employer)
    {
        $threads = ThreadParticipant::where('user_id', $employee)->first();
        $threads_id = !empty($threads->thread_id) ? $threads->thread_id : 0;
        return ThreadParticipant::where('user_id', $employer)->where('thread_id', $threads_id)->first();

    }

    // this function use to send email with contact card user 

    function UnlockUserProfile($user_id, $sender_id, $post_id)
    {
        $today = date('Y-m-d');
        $employer = User::find($sender_id);
        $check_applicant = Applicant::where('user_id', $user_id)->where('to_user_id',$sender_id)->first();
        $check_user_for_applicant_create = User::find($user_id);
        
        if (empty($check_applicant)) {
            $new_applicant = new Applicant();
            $new_applicant->name = $check_user_for_applicant_create->name;
            $new_applicant->email = $check_user_for_applicant_create->email;
            $new_applicant->user_id = $check_user_for_applicant_create->id;
            $new_applicant->to_user_id = $sender_id;
            $new_applicant->post_id = '0';
            $new_applicant->status = 'applied';
            $new_applicant->contact_unlock = '1';
            $new_applicant->save();
        }

        $remainig_count  = CompanyPackages::check_credit_available($sender_id);
       
        if ($remainig_count > 0 || $remainig_count == 'unlimited' ) {
            $unlock_user_check = Unlock::where('user_id', $user_id)->where('to_user_id', $sender_id)->where('is_unlock', 1)->first();
            if (empty($unlock_user_check)) {
                $values = array(
                    'user_id' => $user_id,
                    'to_user_id' => $sender_id,
                    'is_unlock' => 1,
                    'post_id' => $post_id,
                );

                $data = Unlock::create($values);
                $myurl = url('/companyprofile/' . $sender_id);
                $company_name = $employer->name;
                $description = "Company <a href='$myurl'><b>$company_name</b></a> viewed your CV from our database of CV’s. There is a possibility they could contact you for an interview.";
                $contact_card_log = array(
                    'user_id' => $user_id,
                    'company_id' => $employer->id,
                    'description' => $description,
                );
                ContactCardViewLog::create($contact_card_log);
                Helper::update_remaining_credit($employer,$user_id);

                $job_seeker_data = User::where('id', $user_id)->first();
                $company_name = $employer->name;
                $profile_url = admin_url() . '/employer?search=' . $employer->email;
                $job_seeker_url = admin_url() . '/job-seekers?search=' . $job_seeker_data->email;
                $description = "A company Name: <a href='$profile_url'>$company_name</a> just use his contact card to unlock the Employee profile <br> Employee name: <a href='$job_seeker_url'>$job_seeker_data->name</a> ";
                Helper::activity_log($description);
                
                $this->send_employee_contact_card_email($job_seeker_data, $employer->id);
            }
            return true;

        } else {
            return false;
        }


    }

    // this function use to check chat already exist with this employer or not

    function send_employee_contact_card_email($post, $employer_id)
    {
        $company = User::where('id', $employer_id)->first();
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

    // this function use to create chat with employer

    function messagesend($id, $data)
    {
        $employer = User::find($data->user_id);
        $thread_login_participant = ThreadParticipant::where('user_id', $data->user_id)->pluck('thread_id')->toArray();
        $thread_login_participantArray = array_filter($thread_login_participant);
        $thread_without_participant = ThreadParticipant::get_all_threads_without_login_user($id);
        $thread_without_participantArray = array_filter($thread_without_participant);
        $intersect = array_intersect($thread_login_participantArray, $thread_without_participantArray);
        $thread = new Thread();
        if (empty($intersect)) {
            $thread->post_id = 0;
            $thread->subject = 'Contact';
            $thread->save();
        } else {
            return true;
        }
        $message = new ThreadMessage();
        if (empty($intersect)) {
            $message->thread_id = $thread->id;
        } else {
            $thread_id = 0;
            foreach ($intersect as $key => $value) {
                $thread_id = $value;
                break;
            }
            $message->thread_id = $thread_id;
        }
        // Message
        $message->user_id = $data->user_id;
        $message->body = $data->message;
        $message->filename = null;
        $message->save();
        // Update Message Array
        $messageArray['from_name'] = $employer->name;
        $messageArray['from_email'] = $employer->email;
        $messageArray['from_phone'] = $employer->phone;
        $messageArray['country_code'] = $employer->country_code;
        if (!empty($message->filename)) {
            $messageArray['filename'] = $message->filename;
        }
        // Sender
        $sender = new ThreadParticipant();
        $sender->thread_id = $thread->id;
        $sender->user_id = $employer->id;
        $sender->last_read = new Carbon;
        $sender->save();
        $thread->addParticipant($id);
        $thread_data_unread = ThreadParticipant::where('thread_id', $thread->id)->where('last_read', null)->first();
        $thread_data_read = ThreadParticipant::where('thread_id', $thread->id)->whereNotNull('last_read')->first();
        $date = date('y-m-d');
        $messageCount = ThreadParticipant::where('thread_id', $thread->id)->where('user_id', $employer->id)->where('created_at', 'like', '%' . $date . '%')->count();
        if ($messageCount == 1) {
            $this->sendmessageemail($thread_data_unread, $thread_data_read);
        }
        Helper::add_notification('message', $thread_data_unread->user_id);
        return true;
    }

    // this function is use to send email to employee
    public function sendmessageemail($thread_data_unread, $thread_data_read)
    {
        $unread_user = User::withoutGlobalScopes()->where('id', $thread_data_unread->user_id)->first();
        $read_user = User::withoutGlobalScopes()->where('id', $thread_data_read->user_id)->first();

        $data['email'] = $unread_user->email;
        $data['myName'] = $unread_user->name;
        $data['subject'] = 'New Direct Message';
        $data['from_user_name'] = $read_user->name;
        if ($unread_user->user_type_id == 2) {
            $data['to_user'] = "Company";
        } else {
            $data['to_user'] = "Job Seekers";
        }
        $data['view'] = 'emails.new_message';
        $data['header'] = 'New Message Received';
        $helper = new Helper();
        // create activity log for
        $company_name = $read_user->name;
        $profile_url = admin_url() . '/employer?search=' . $read_user->email;
        $employee_url = admin_url() . '/job-seekers?search=' . $unread_user->email;
        $employee_name = $unread_user->name;
        if ($unread_user->user_type_id == 2) {
            $description = "Company: <a href='$profile_url'>$company_name</a> sent message to Employee:  <a href='$employee_url'>$employee_name</a>";
        } else {
            $description = "Job Seekers: <a href='$profile_url'>$company_name</a> sent message to Company:  <a href='$employee_url'>$employee_name</a>";
        }

        Helper::activity_log($description);
        $response = $helper->send_email($data);
    }

    public function track_message_request($id)
    {
        $data['track_request_data'] = TrackMessageRequest::get_track_request_by_request_id($id);
        return view('vendor.admin.post.track_message_request', compact('data'));
    }
}