<?php

namespace App\Http\Controllers\Account;

use App\Helpers\Helper;
use App\Helpers\UrlGen;
use App\Http\Controllers\Account\Traits\MessagesTrait;
use App\Http\Requests\ReplyMessageRequest;
use App\Http\Requests\SendMessageRequest;
use App\Models\Applicant;
use App\Models\CompanyPackages;
use App\Models\EmployeeSkill;
use App\Models\MessageRequest;
use App\Models\OptionalSelectedEmails;
use App\Models\Post;
use App\Models\Thread;
use App\Models\ThreadMessage;
use App\Models\ThreadParticipant;
use App\Models\TrackMessageRequest;
use App\Models\Unlock;
use App\Models\User;
use App\Notifications\ReplySent;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Notification;
use Torann\LaravelMetaTags\Facades\MetaTag;

class MessagesController extends AccountBaseController
{
    use MessagesTrait;

    private $perPage = 10;

    public function __construct()
    {
        parent::__construct();
        $this->perPage = (is_numeric(config('settings.listing.items_per_page'))) ? config('settings.listing.items_per_page') : $this->perPage;
        // Set the Page Path
        view()->share('pagePath', 'messenger');
    }

    public function index()
    {
        if (!Helper::check_permission(10)) {
            flash(t("You do not have permission to access this module"))->error();
            return redirect('/');
        }

        if (auth()->user()->user_type_id == 1 && empty(auth()->user()->parent_id)) {
            flash(t("Please change account to child company to view this page."))->error();
            return redirect('/');
        }
        Helper::update_notification('message', auth()->user()->id);

        // All threads that user is participating in
        $threads = $this->threads;

        // Get threads that have new messages or that are marked as unread
        if (request()->get('filter') == 'unread') {
            //            $threads = $this->threadsWithNewMessage;
            $threads->where('last_read', NULL);
        }

        // Get threads started by this user
        if (request()->get('filter') == 'started') {
            $threadTable = (new Thread())->getTable();
            $messageTable = (new ThreadMessage())->getTable();

            $threads->where(function ($query) use ($threadTable, $messageTable) {
                $query->select('user_id')
                    ->from($messageTable)
                    ->whereColumn($messageTable . '.thread_id', $threadTable . '.id')
                    ->orderBy($messageTable . '.created_at', 'ASC')
                    ->limit(1);
            }, auth()->id());
        }

        // Get this user's important thread
        if (request()->get('filter') == 'important') {
            $threads->where('is_important', 1);
        }

        $threads= $threads->groupBy('id');

        // Get rows & paginate
        $threads = $threads->whereHas('messages_not_deleted_by_user')->paginate($this->perPage);

        // Meta Tags
        MetaTag::set('title', t('messenger_inbox'));
        MetaTag::set('description', t('messenger_inbox'));

        if (request()->ajax()) {
            $result = [];
            $result['threads'] = view('account.messenger.threads.threads', ['threads' => $threads])->render();
            $result['links'] = view('account.messenger.threads.links', ['threads' => $threads])->render();

            return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
        }
        if (auth()->user()->user_type_id == 1) {
            // Meta Tags
            view()->share([
                'title' => t('Chat With Employees'),
                'description' => t('Chat With Employees'),
                'keywords' => t('Chat With Employees'),
                // Add more variables as needed
            ]);
        } else {
            // Meta Tags
            view()->share([
                'title' => t('Chat With Companies'),
                'description' => t('Chat With Companies'),
                'keywords' => t('Chat With Companies'),
                // Add more variables as needed
            ]);
        }

        return view('account.messenger.index', compact('threads'));
    }

    /**
     * Shows a message thread.
     *
     * @param $id
     * @return mixed
     */
    public function show($id)
    {

        if (!Helper::check_permission(10)) {
            flash(t("You do not have permission to access this module"))->error();
            return redirect('/');
        }
        $threadTable = (new Thread())->getTable();
        $thread = Thread::forUser(auth()->id())->where($threadTable . '.id', $id)->firstOrFail($id);

        // Get the User ID from Thread Messages
        $messages = ThreadMessage::whereNull('deleted_at')->whereNull('deleted_by_user')->notDeletedByUser(auth()->id())->where('thread_id', $thread->id);
        $messages = $messages->paginate($this->perPage);
        $linksRender = $messages->links('account.messenger.messages.pagination')->render();
        $messages = $messages->items();
        //Get user data from applicants to check weather contact card is unlocked or not

        if (count($messages) > 0) {
            $applicant = Applicant::where('user_id', $messages[0]['user_id'])->first();
        } else {
            $applicant = null;
        }

        try {
            $threadTable = (new Thread())->getTable();
            $thread = Thread::forUser(auth()->id())->where($threadTable . '.id', $id)->firstOrFail($id);
            // Get the Thread's Messages
            $messages = ThreadMessage::query()
                ->whereNull('deleted_at')
                ->whereNull('deleted_by_user')
                ->notDeletedByUser(auth()->id())
                ->where('thread_id', $thread->id)
                ->orderByDesc('id');

            $messages = $messages->paginate($this->perPage);
            $linksRender = $messages->links('account.messenger.messages.pagination')->render();
            $messages = $messages->items();
        } catch (ModelNotFoundException $e) {
            $msg = t('thread_not_found', ['id' => $id]);
            flash($msg)->error();

            return redirect('account/messages');
        }

        // Mark the Thread as read
        $thread->markAsRead(auth()->id());

        // Reverse the collection order like Messenger
        $messages = collect($messages)->reverse();

        if (request()->ajax()) {
            $result = [];
            $result['messages'] = view('account.messenger.messages.messages', ['messages' => $messages])->render();
            $result['links'] = $linksRender;

            return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
        }
        // Meta Tags
        view()->share([
            'title' => t('Messages Received'),
            'description' => t('Messages Received'),
            'keywords' => t('Messages Received'),
            // Add more variables as needed
        ]);

        return view('account.messenger.show', compact('thread', 'messages', 'linksRender'));
    }

    public function sendEmployerContactedemail($post)
    {

        $data['email'] = $post->email;
        $data['subject'] = 'New Applicant On Your Job Post';

        $data['myName'] = $post->company_name;
        $data['postname'] = $post->title;
        $data['view'] = 'emails.send_employer_email_for_applyjob';
        $data['header'] = 'New Applicant';
        $helper = new Helper();
        $response = $helper->send_email($data);
    }

    public
    function messagesend(Request $request)
    {

        if (empty(auth()->user()->id)) {
            flash(t("No Data Found"))->error();
            return redirect('/');
        }
        if (!Helper::check_permission(10)) {
            flash(t("You do not have permission to access this module"))->error();
            return redirect('/');
        }
        $thread_login_participant = ThreadParticipant::get_all_threads_login_user();
        $thread_login_participantArray = array_filter($thread_login_participant);
        $thread_without_participant = ThreadParticipant::get_all_threads_without_login_user($request['send_user_id']);
        $thread_without_participantArray = array_filter($thread_without_participant);
        $intersect = array_intersect($thread_login_participantArray, $thread_without_participantArray);

        $thread = new Thread();
        if (empty($intersect)) {
            $thread->post_id = 0;
            $thread->subject = 'Contact';
            $thread->save();
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
        $message->user_id = auth()->id();
        $message->body = $request->message;
        $message->filename = null;
        $message->save();

        // Update Message Array
        $messageArray['from_name'] = auth()->user()->name;
        $messageArray['from_email'] = auth()->user()->email;
        $messageArray['from_phone'] = auth()->user()->phone;
        $messageArray['country_code'] = config('country.code');
        if (!empty($message->filename)) {
            $messageArray['filename'] = $message->filename;
        }

        // Sender
        $sender = new ThreadParticipant();
        $sender->thread_id = $message->thread_id;
        $sender->user_id = auth()->id();
        $sender->last_read = new Carbon;
        $sender->save();
        $thread->addParticipant($request->send_user_id);

        $thread_data_unread = ThreadParticipant::where('thread_id', $message->thread_id)->where('user_id', '!=', auth()->id())->first();
        $thread_data_read = ThreadParticipant::where('thread_id', $message->thread_id)->where('user_id', auth()->id())->first();
        $date = date('y-m-d');
        $messageCount = ThreadParticipant::where('thread_id', $message->thread_id)->where('user_id', auth()->id())->where('created_at', 'like', '%' . $date . '%')->count();
//        if ($messageCount == 1) {
        $this->sendmessageemail($thread_data_unread, $thread_data_read);
//        }
        Helper::add_notification('message', $thread_data_unread->user_id);
        $msg = "Message has successfully sent";
        flash($msg)->success();
        return redirect()->back();
    }

    /**
     * Stores a new message thread.
     * Contact the Post's Author
     * NOT use AJAX
     *
     * @param $postId
     * @param SendMessageRequest $request
     * @return Application|RedirectResponse|Redirector
     */

    public function sendmessageemail($thread_data_unread, $thread_data_read)
    {
        $unread_user = User::withoutGlobalScopes()->where('id', $thread_data_unread->user_id)->first();
        $read_user = User::withoutGlobalScopes()->where('id', $thread_data_read->user_id)->first();
        $cc = '';
        if($unread_user->user_type_id==1) {
            if (OptionalSelectedEmails::check_selected_email(4, $unread_user->id)) {
                $cc = $unread_user->optional_emails;
            }
        }
        $data['email'] = $unread_user->email;
        $data['myName'] = $unread_user->name;
        $data['subject'] = 'New Direct Message';
        $data['from_user_name'] = $read_user->name;
        if ($unread_user->user_type_id == 2) {
            $data['to_user'] = "Company";
        } else {
            $data['to_user'] = "employee (job seeker)";
        }
        $data['view'] = 'emails.new_message';
        $data['cc'] = $cc;
        $data['header'] = 'New Message Received';
        $helper = new Helper();
        // create activity log for
        if ($unread_user->user_type_id == 2) {
            $company_name = $read_user->name;
            $profile_url = admin_url() . '/employer?search=' . $read_user->email;
            $employee_url = admin_url() . '/job-seekers?search=' . $unread_user->email;
            $employee_name = $unread_user->name;
            $descriptionCompany['employee_name'] = $employee_name;
            $descriptionCompany['employee_url'] = url('/profile/').'/'.$unread_user->id;;
            $companyDescription = Helper::companyDescriptionData($descriptionCompany, 'send_message_to_employee');
            if(!empty($companyDescription)){
                Helper::activity_log($companyDescription,auth()->user()->id);
            }
            $description = "Company: <a href='$profile_url'>$company_name</a> sent message to employee (job seeker):  <a href='$employee_url'>$employee_name</a>";
        } else {
            $employee_name = $read_user->name;
            $employee_url = admin_url() . '/employer?search=' . $read_user->email;
            $company_name = admin_url() . '/job-seekers?search=' . $unread_user->email;
            $profile_url = $unread_user->name;
            $companyDescription = Helper::companyDescriptionData($data, 'send_message_to_admin');
            if(!empty($companyDescription)){
                Helper::activity_log($companyDescription,auth()->user()->id);
            }
            $description = "A Employee (job seeker): <a href='$employee_url'>$employee_name</a> sent message to Company:  <a href='$profile_url'>$company_name</a>";
        }
        Helper::activity_log($description);
        $response = $helper->send_email($data);
    }

    /**
     * Adds a new message to a current thread.
     *
     * @param $id
     * @param ReplyMessageRequest $request
     * @return JsonResponse|void
     */
    public
    function update($id, Request $request)
    {
        if (!Helper::check_permission(10)) {
            flash(t("You do not have permission to access this module"))->error();
            return redirect('/');
        }
        if (!request()->ajax()) {
            return;
        }
        $result = ['success' => false];

        try {
            // We use with([users => fn()]) to prevent email sending
            // to the message sender (which is the current user)
            $thread = Thread::with([
                'post',
                'users' => function ($query) {
                    $query->where((new User())->getTable() . '.id', '!=', auth()->id());
                },
            ])->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            $result['msg'] = t('thread_not_found', ['id' => $id]);
            return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
        }
        // Re-activate the Thread for all participants
        $thread->deleted_by = null;
        $thread->save();

        $thread->activateAllParticipants();

        // Create Message Array
        $messageArray = $request->all();

        // Message
        $message = new ThreadMessage();
        $message->thread_id = $thread->id;
        $message->user_id = auth()->id();
        $message->body = $request->get('body');
        $message->save();
        $thread_message = ThreadMessage::where('user_id', auth()->id())->get();
        $message_count = !empty($thread_message) ? count($thread_message) : 0;
        if ($message_count == 1) {
            $user = User::where('id', auth()->id())->first();
            $company_name = $thread->users[0]->name;
            $profile_url = admin_url() . '/employer?search=' . $thread->users[0]->email;
            $employee_url = admin_url() . '/job-seekers?search=' . $user->email;
            $employee_name = $user->name;
            $description = "A Employee (job seeker): <a href='$employee_url'>$employee_name</a> reply message to company: <a href='$profile_url'>$company_name</a> ";
            Helper::activity_log($description);
        }

        // Save and Send user's resume
        if ($request->hasFile('filename')) {
            $file = $request->file('filename');

            if ($file->isValid()) {
                $file_type = $file->getClientOriginalExtension();
                $destinationPath = public_path('/') . '/storage/files/kw/' . $message->id . '/applications/';
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                $fileName = 'files/kw/' . $message->id . '/applications/' . time() .'.'. $file_type;
                $file->move($destinationPath, $fileName);

                $message->filename = $fileName;

                $message->save();
                //                Activity ============ End
            }
        }

        // Update Message Array
        $messageArray['country_code'] = config('country.code');
        $messageArray['post_id'] = (!empty($thread->post)) ? $thread->post->id : null;
        $messageArray['from_name'] = auth()->user()->name;
        $messageArray['from_email'] = auth()->user()->email;
        $messageArray['from_phone'] = auth()->user()->phone;
        $title = !empty($thread->post->title) ? $thread->post->title : '';
        $messageArray['subject'] = t('New message about') . ': ' . $title;
        if (!empty($message->filename)) {
            $messageArray['filename'] = $message->filename;
        }

        // Add replier as a participant
        $participant = ThreadParticipant::firstOrCreate([
            'thread_id' => $thread->id,
            'user_id' => auth()->id(),
        ]);
        $participant->last_read = new Carbon;
        $participant->save();

        // Recipients
        if ($request->has('recipients')) {
            $thread->addParticipant($request->get('recipients'));
        } else {
            if (!empty($thread->post->user->id)) {
                $thread->addParticipant($thread->post->user->id);
            }
        }

        // Remove input file to prevent Laravel Queue serialization issue
        if (isset($messageArray['filename']) && !is_string($messageArray['filename'])) {
            unset($messageArray['filename']);
        }

        $errorFound = false;

        // Send Reply Email
        if (isset($messageArray['post_id'], $messageArray['from_email'], $messageArray['from_name'], $messageArray['body'])) {
            try {
                if (isset($thread->users) && $thread->users->count() > 0) {
                    foreach ($thread->users as $user) {
                        $messageArray['to_email'] = $user->email ?? '';
                        $messageArray['to_phone'] = $user->phone ?? '';
                        Notification::send($user, new ReplySent($messageArray));
                    }
                }
            } catch (Exception $e) {
                $errorFound = true;
                $result['msg'] = $e->getMessage();
            }
        }
        $date = date('y-m-d');
        $thread_data_not_login = ThreadParticipant::where('thread_id', $thread->id)->where('user_id', '!=', auth()->id())->first();
        $thread_data_login = ThreadParticipant::where('thread_id', $thread->id)->where('user_id', auth()->id())->first();
        $messageCount = ThreadMessage::where('thread_id', $thread->id)->where('user_id', auth()->id())->where('created_at', 'like', '%' . $date . '%')->count();


         if ($messageCount == 1) {
        $this->sendmessageemail($thread_data_not_login, $thread_data_login);
        }

        $data_thread = ThreadParticipant::where('thread_id', $id)->where('user_id', '!=', auth()->user()->id)->first();

        Helper::add_notification('message', $data_thread->user_id);

        if (!$errorFound) {
            $user = User::where('id', $data_thread->user_id)->first();
            $data['employee_name'] = $user->name;
            $data['employee_url'] = url('/profile/').'/'.$user->id;
            $companyDescription = Helper::companyDescriptionData($data, 'send_message_to_employee');
            if(!empty($companyDescription)){
                Helper::activity_log($companyDescription,auth()->user()->id);
            }
            $result['success'] = true;
            $result['msg'] = t('Your reply has been sent');
        }

        return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Actions on the Threads
     *
     * @param null $threadId
     * @return JsonResponse|void
     */
    public
    function actions($threadId = null)
    {
        if (!Helper::check_permission(10)) {
            flash(t("You do not have permission to access this module"))->error();
            return redirect('/');
        }
        if (!request()->ajax()) {
            return;
        }

        $result = ['success' => false];

        if (request()->get('type') == 'markAsRead') {
            $res = $this->markAsRead($threadId);
            $data['type'] = 'mark As Read';
        }
        if (request()->get('type') == 'markAsUnread') {
            $res = $this->markAsUnread($threadId);
            $data['type'] = 'mark As Unread ';
        }
        if (request()->get('type') == 'markAsImportant') {
            $res = $this->markAsImportant($threadId);
            $data['type'] = 'mark As Important';
        }
        if (request()->get('type') == 'markAsNotImportant') {
            $res = $this->markAsNotImportant($threadId);
            $data['type'] = 'mark As Not Important';
        }
        if (request()->get('type') == 'delete') {
            $deleted_thread = ThreadParticipant::where('thread_id', $threadId)->where('user_id', '!=', auth()->user()->id)->first();        
            $res = $this->deleteThreadByUser($threadId);
        }

        if (
            isset($res)
            && array_key_exists('success', $res)
            && array_key_exists('msg', $res)
        ) {
            if (!empty($threadId)) {
                $result['baseUrl'] = request()->url();
            }
            $result['type'] = request()->get('type');
            $result['success'] = $res['success'];
            $result['msg'] = $res['msg'];
        }
        if (request()->get('type') == 'delete') {
            $user = User::where('id', $deleted_thread->user_id)->first();
            $data['employee_name'] = $user->name;
            $data['employee_url'] = url('/profile/').'/'.$user->id;
            $data['type'] = 'deleted';
            $companyDescription = Helper::companyDescriptionData($data, 'change_thread_status');
            if(!empty($companyDescription)){
                Helper::activity_log($companyDescription,auth()->user()->id);
            }
        }else{
            $data_thread = ThreadParticipant::where('thread_id', $threadId)->where('user_id', '!=', auth()->user()->id)->first();        
            $user = User::where('id', $data_thread->user_id)->first();
            $data['employee_name'] = $user->name;
            $data['employee_url'] = url('/profile/').'/'.$user->id;
            $companyDescription = Helper::companyDescriptionData($data, 'change_thread_status');
            if(!empty($companyDescription)){
                Helper::activity_log($companyDescription,auth()->user()->id);
            }
        }
        return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Check Threads with New Messages
     *
     * @return JsonResponse|void
     */
    public
    function checkNew()
    {
        if (!request()->ajax()) {
            return;
        }

        $countLimit = 20;
        $countThreadsWithNewMessages = 0;
        $oldValue = request()->input('oldValue');
        $languageCode = request()->input('languageCode');

        if (auth()->check()) {
            $countThreadsWithNewMessages = Thread::whereHas('post', function ($query) {
                $query->currentCountry()->unarchived();
            })->forUserWithNewMessages(auth()->id())->count();
        }

        $result = [
            'logged' => (auth()->check()) ? auth()->user()->id : 0,
            'countLimit' => (int)$countLimit,
            'countThreadsWithNewMessages' => (int)$countThreadsWithNewMessages,
            'oldValue' => (int)$oldValue,
            'loginUrl' => UrlGen::login(),
        ];

        return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
    }

    public
    function checkcontact(Request $request)
    {
        $user_data_thread = ThreadParticipant::where('thread_id', $request->input('a'))->groupBy('user_id')->get();
        $temp = 0;
        $data['user_data'] = array();
        if (!empty($user_data_thread)) {
            foreach ($user_data_thread as $user_data) {
                $dataUnlock = Unlock::where('user_id', $user_data->user_id)->where('to_user_id', auth()->user()->id)->where('is_unlock', 1)->first();
                if ($user_data->user_id != auth()->user()->id) {
                    $data['user_data'] = $user_data;
                }
                if (!empty($dataUnlock)) {
                    $temp = 1;
                }
            }
        }
        $remaining_credits = CompanyPackages::check_credit_available(auth()->user()->id);
        if (empty($remaining_credits)) {
            $data['package'] = 0;
        }

        if ($temp == 0) {
            $data['isUnlock'] = 0;
        } else {
            $data['isUnlock'] = 1;
        }
        return response()->json($data, 200);
        die;
    }

    public
    function getuserbyid(Request $request)
    {
        $data = User::withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])->where('id', $request->input('id'))->first();
        return response()->json($data, 200);
    }

    public function message_request()
    {
        if (!Helper::check_permission(14)) {
            flash(t("You do not have permission to access this module"))->error();
            return redirect('/');
        }

        if (auth()->user()->user_type_id == 2) {
            flash(t("Permission error..!"))->error();
            return redirect('/');
        }
        $valid_package = CompanyPackages::check_company_has_premium_package();
        if (!$valid_package) {
            flash(t('If you would like to send Bulk Chat Requests, you will need to subscribe to the Package.'))->error();
            return redirect(url('account/upgrade'));
        }

        $message_request = MessageRequest::get_all_message_request_by_employer_id();
        $posts = Post::get_post_by_user_id(auth()->user()->id);

        $employeeSkill = EmployeeSkill::getAllskill();

        view()->share('pagePath', 'message_request');
        // Meta Tags
        view()->share([
            'title' => t('Bulk Chat Request'),
            'description' => t('Bulk Chat Request'),
            'keywords' => t('Bulk Chat Request'),
            // Add more variables as needed
        ]);

        return view('account.message_request', compact('message_request', 'posts', 'employeeSkill'));
    }

    public function message_request_post(Request $request)
    {
        if (!Helper::check_permission(14)) {
            flash(t("You do not have permission to access this module"))->error();
            return redirect('/');
        }
        $latest_package = CompanyPackages::get_latest_package_details();
        if (empty($latest_package) && $latest_package->package_id != 6) {
            flash("Please Upgrade Your account.")->error();
            return redirect()->back();
        }

        if (!empty($latest_package) && $latest_package->unlimited_credit == 0) {
            $remaining_credits = CompanyPackages::check_credit_available(auth()->user()->id);

            if ($request->number_of_employee > $remaining_credits) {
                $msg = t('The number of employees you entered exceeds your subscribed package limit.');
                flash($msg)->error();
                return redirect()->back();
            }
        }
        
        $msg_request = new MessageRequest();
        $msg_request->user_id = $request->user_id;
        $msg_request->message = $request->message;
        $msg_request->number_of_employee = $request->number_of_employee;
        $msg_request->post_id = $request->post_id;
        $msg_request->skill_set = $request->skill_set;
        $msg_request->save();
        $msg = t("Bulk Chat request successfully sent to the Hungry For Jobs team");
        flash($msg)->success();
        return redirect()->back();
    }

    public function delete_message_request($id)
    {
        if (!Helper::check_permission(14)) {
            flash(t("You do not have permission to access this module"))->error();
            return redirect('/');
        }

        $msg_request = MessageRequest::find($id);
        if ($msg_request->delete()) {
            $msg = "Message Request Deleted successfully";
            flash($msg)->success();
            return redirect()->back();
        }
    }

    public function track_bulk_request($id)
    {

        if (!Helper::check_permission(14)) {
            flash(t("You do not have permission to access this module"))->error();
            return redirect('/');
        }

        if (auth()->user()->user_type_id == 2) {
            flash(t("Permission error..!"))->error();
            return redirect('/');
        }

        $latest_package = CompanyPackages::get_latest_package_details();
        if (empty($latest_package) && $latest_package->package_id != 6) {
            flash("Please Upgrade Your account.")->error();
            return redirect()->back();
        }
        $data = TrackMessageRequest::get_track_request_by_request_id($id);
        view()->share('pagePath', 'track_message_request');
        // Meta Tags
        view()->share([
            'title' => t('Track Message Request'),
            'description' => t('Track Message Request'),
            'keywords' => t('Track Message Request'),
            // Add more variables as needed
        ]);

        return view('account.track_message_request', compact('data'));
    }

    public function deleteMessage($id)
    {
        $message = ThreadMessage::find($id);
        if (!$message) {
            return response()->json(['success' => false, 'msg' => 'Message not found.']);
        }

        Model::withoutTouching(function () use ($message) {
            $message->timestamps = false;
            $message->deleted_at = Carbon::now();
            $message->deleted_by = auth()->id();
            $message->save();
        });
        $data_thread = ThreadParticipant::where('thread_id', $message->thread_id)->where('user_id', '!=', auth()->user()->id)->first();        
        $user = User::where('id', $data_thread->user_id)->first();
        $data['employee_name'] = $user->name;
        $data['employee_url'] = url('/profile/').'/'.$user->id;
        $data['type'] = 'delete';
        $companyDescription = Helper::companyDescriptionData($data, 'chat_message_status');
        if(!empty($companyDescription)){
            Helper::activity_log($companyDescription,auth()->user()->id);
        }
        return response()->json(['success' => true, 'msg' => 'Message deleted successfully.']);
    }

    public function updateMessage(Request $request, $id)
    {
        $message = ThreadMessage::find($id);
        if (!$message) {
            return response()->json(['success' => false, 'msg' => 'Message not found.']);
        }
        Model::withoutTouching(function () use ($message,$request) {
            $message->timestamps = false;
            $message->body = $request->input('message');
            $message->save();
        });
        $data_thread = ThreadParticipant::where('thread_id', $message->thread_id)->where('user_id', '!=', auth()->user()->id)->first();        
        $user = User::where('id', $data_thread->user_id)->first();
        $data['employee_name'] = $user->name;
        $data['employee_url'] = url('/profile/').'/'.$user->id;
        $data['type'] = 'update';
        $companyDescription = Helper::companyDescriptionData($data, 'chat_message_status');
        if(!empty($companyDescription)){
            Helper::activity_log($companyDescription,auth()->user()->id);
        }

        return response()->json(['success' => true, 'msg' => 'Message updated successfully.']);
    }
}
