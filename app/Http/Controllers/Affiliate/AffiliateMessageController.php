<?php

namespace App\Http\Controllers\Affiliate;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Account\Traits\MessagesTrait;
use App\Models\Applicant;
use App\Models\Thread;
use App\Models\ThreadMessage;
use App\Models\ThreadParticipant;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Torann\LaravelMetaTags\Facades\MetaTag;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ReplySent;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Exception;



class AffiliateMessageController extends AffiliateBaseController
{
    use MessagesTrait;

    private $perPage = 10;

    public function __construct()
    {
        parent::__construct();
        // Set the Page Path
        view()->share('pagePath', 'affiliate_messenger');
    }

    public function index()
    {
        if(!auth()->check()){
            return redirect()->back();
        }
        Helper::update_notification('message', auth()->user()->id);

        // All threads that user is participating in
        $threads = $this->threads;

        $threads= $threads->groupBy('id');

        // Get rows & paginate
        $threads = $threads->whereHas('messages_not_deleted_by_user')->paginate($this->perPage);

        // Meta Tags
        MetaTag::set('title', t('messenger_inbox'));
        MetaTag::set('description', t('messenger_inbox'));

        if (request()->ajax()) {
            $result = [];
            $result['threads'] = view('affiliate.messenger.threads.threads', ['threads' => $threads])->render();
            $result['links'] = view('affiliate.messenger.threads.links', ['threads' => $threads])->render();

            return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
        }
        // Meta Tags
        view()->share([
            'title' => t('affiliate_messenger'),
            'description' => t('affiliate_messenger'),
            'keywords' => t('affiliate_messenger'),
        ]);
        

        return view('affiliate.messenger.index', compact('threads'));
    }

    public function messagesend(Request $request)
    {
        if(!auth()->check()){
            return redirect()->back();
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

        $user_url = admin_url() . '/affiliates?search=' . auth()->user()->email;
        $name = auth()->user()->name;
        $admin_url = admin_url() . '/employer?search=contact@hungryforjobs.com';
        $url = "<a href='$admin_url'><strong>HungryForJobs</strong></a>";
        $description = "An affiliate User Name: <b> <a href='$user_url'>$name</a></b> has sent message to Admin: ". $url ." at " .date('Y-m-d H:i:s');
        Helper::activity_log($description);
        $affiliatedata['action'] =  'message';
        $affiliateDescription = Helper::affiliateDescriptionData($affiliatedata, 'message_send');
        if(!empty($affiliateDescription)){
            Helper::activity_log($affiliateDescription,auth()->user()->id);
        }
        
       if ($messageCount == 1) {
        $this->sendmessageemail($thread_data_unread, $thread_data_read);
       }
        Helper::add_notification('message', $thread_data_unread->user_id);
        $msg = "Message has successfully sent";
        flash($msg)->success();
        return redirect()->back();
    }

    /**
     * Shows a message thread.
     *
     * @param $id
     * @return mixed
     */
    public function show($id)
    {

        if(!auth()->check()){
            return redirect()->back();
        }
        $threadTable = (new Thread())->getTable();
        $thread = Thread::forUser(auth()->id())->where($threadTable . '.id', $id)->firstOrFail($id);

        // Get the User ID from Thread Messages
        $messages = ThreadMessage::whereNull('deleted_at')->whereNull('deleted_by_user')->notDeletedByUser(auth()->id())->where('thread_id', $thread->id);
        $messages = $messages->paginate($this->perPage);
        $linksRender = $messages->links('affiliate.messenger.messages.pagination')->render();
        $messages = $messages->items();
        

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
            $linksRender = $messages->links('affiliate.messenger.messages.pagination')->render();
            $messages = $messages->items();
        } catch (ModelNotFoundException $e) {
            $msg = t('thread_not_found', ['id' => $id]);
            flash($msg)->error();

            return redirect('affiliate/messages');
        }

        // Mark the Thread as read
        $thread->markAsRead(auth()->id());

        // Reverse the collection order like Messenger
        $messages = collect($messages)->reverse();

        if (request()->ajax()) {
            $result = [];
            $result['messages'] = view('affiliate.messenger.messages.messages', ['messages' => $messages])->render();
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

        return view('affiliate.messenger.show', compact('thread', 'messages', 'linksRender'));
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
        if(!auth()->check()){
            return redirect()->back();
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
        
        // Save and Send user's resume
        if ($request->hasFile('filename')) {
            $file = $request->file('filename');

            if ($file->isValid()) {
                $file_type = $file->getClientOriginalExtension();
                $destinationPath = public_path('/') . '/storage/files/kw/' . $message->id . '/affiliates/';
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                $fileName = 'files/kw/' . $message->id . '/affiliates/' . time() .'.'. $file_type;
                $file->move($destinationPath, $fileName);

                $message->filename = $fileName;

                $message->save();
            }
        }

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

        $user_url = admin_url() . '/affiliates?search=' . auth()->user()->email;
        $name = auth()->user()->name;
        $admin_url = admin_url() . '/employer?search=contact@hungryforjobs.com';
        $url = "<a href='$admin_url'><strong>HungryForJobs</strong></a>";
        $description = "An affiliate User Name: <b> <a href='$user_url'>$name</a></b> has sent reply to Admin: ". $url ." at " .date('Y-m-d H:i:s');
        Helper::activity_log($description);
        $affiliatedata['action'] =  'reply';
        $affiliateDescription = Helper::affiliateDescriptionData($affiliatedata, 'message_send');
        if(!empty($affiliateDescription)){
            Helper::activity_log($affiliateDescription,auth()->user()->id);
        }

         if ($messageCount == 1) {
        $this->sendmessageemail($thread_data_not_login, $thread_data_login);
        }

        $data_thread = ThreadParticipant::where('thread_id', $id)->where('user_id', '!=', auth()->user()->id)->first();

        Helper::add_notification('message', $data_thread->user_id);

        return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
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
         $data['email'] = $unread_user->email;
         $data['myName'] = $unread_user->name;
         $data['subject'] = 'New Direct Message';
         $data['from_user_name'] = $read_user->name;

         $data['to_user'] = "Affiliate";
         
         $data['view'] = 'emails.new_affiliate_message';
         $data['cc'] = $cc;
         $data['header'] = 'New Message Received';
         $helper = new Helper();
         $response = $helper->send_email($data);
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
        if(!auth()->check()){
            return redirect()->back();
        }
        if (!request()->ajax()) {
            return;
        }

        $result = ['success' => false];

        if (request()->get('type') == 'delete') {
            $deleted_thread = ThreadParticipant::where('thread_id', $threadId)->where('user_id', '!=', auth()->user()->id)->first();        
            $res = $this->deleteThreadByUser($threadId);

            $user_url = admin_url() . '/affiliates?search=' . auth()->user()->email;
            $name = auth()->user()->name;
            $admin_url = admin_url() . '/employer?search=contact@hungryforjobs.com';
            $url = "<a href='$admin_url'><strong>HungryForJobs</strong></a>";
            $description = "An affiliate User Name: <b> <a href='$user_url'>$name</a></b> has deleted his chat with Admin: ". $url ." at " .date('Y-m-d H:i:s');
            Helper::activity_log($description);
            $affiliatedata =  '';
            $affiliateDescription = Helper::affiliateDescriptionData($affiliatedata, 'chat_delete');
            if(!empty($affiliateDescription)){
                Helper::activity_log($affiliateDescription,auth()->user()->id);
            }

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
        return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
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

        $user_url = admin_url() . '/affiliates?search=' . auth()->user()->email;
        $name = auth()->user()->name;
        $description = "An affiliate User Name: <b> <a href='$user_url'>$name</a></b> has deleted his message at " .date('Y-m-d H:i:s');
        Helper::activity_log($description);
        $affiliatedata['action'] =  'deleted';
        $affiliateDescription = Helper::affiliateDescriptionData($affiliatedata, 'message_update');
        if(!empty($affiliateDescription)){
            Helper::activity_log($affiliateDescription,auth()->user()->id);
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

        $user_url = admin_url() . '/affiliates?search=' . auth()->user()->email;
        $name = auth()->user()->name;
        $description = "An affiliate User Name: <b> <a href='$user_url'>$name</a></b> has updated his message at " .date('Y-m-d H:i:s');
        Helper::activity_log($description);
        $affiliatedata['action'] =  'updated';
        $affiliateDescription = Helper::affiliateDescriptionData($affiliatedata, 'message_update');
        if(!empty($affiliateDescription)){
            Helper::activity_log($affiliateDescription,auth()->user()->id);
        }

        return response()->json(['success' => true, 'msg' => 'Message updated successfully.']);
    }
    
}
