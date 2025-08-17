<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Account\Traits\MessagesTrait;
use App\Http\Controllers\Auth\Traits\VerificationTrait;
use App\Http\Requests\Admin\Request;

use App\Models\Thread;
use App\Models\ThreadMessage;
use App\Models\ThreadParticipant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Model;

class AffiliateMessageController extends AdminBaseController 
{
    use VerificationTrait, MessagesTrait;

    private $perPage = 20;
    private $threads;

    public function __construct()
    {
        parent::__construct();
    }


    public function index(Request $request)
    {
        $title = 'Affiliate';
        $breadcrumbs = [
            [
                'title' => 'Dashboard',
                'link' => admin_url('dashboard')
            ],
            [
                'title' => 'Affiliate',
                'link' => 'javascript:void(0)'
            ]
        ];

        // Get the search term
        $search = $request->input('search');

        // Fetch threads with search filtering
        $threads = Thread::whereHas('userDataExcludingAuthUser', function ($query) {
            $query->where('user_type_id', 5);
        })
        ->with(['userDataExcludingAuthUser'])
        ->forUser(auth()->id())
        ->latest('updated_at');        
        if ($search) {
            $threads->whereHas('userDataExcludingAuthUser', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                ->where('user_type_id', 5);
            });
        }

        // Additional filters
        if ($request->get('filter') == 'unread') {
            $threads->where('last_read', null);
        }

        if ($request->get('filter') == 'started') {
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

        if ($request->get('filter') == 'important') {
            $threads->where('is_important', 1);
        }
        $threads = $threads->groupBy('id');
        $threads = $threads->whereHas('messages_not_deleted_by_admin')->paginate($this->perPage);

        if ($request->ajax()) {
            $html = view('admin.message.thread_list', compact('threads'))->render();
            $pagination = view('admin.message.pagination', compact('threads'))->render();

            return response()->json([
                'html' => $html,
                'pagination' => $pagination,
            ]);
        }
        $affiliates = User::get_all_affiliate();
        return view('admin.affiliates.message.index', compact('title', 'breadcrumbs', 'threads', 'affiliates'));
    }

    public function show($id)
    {
        $threadTable = (new Thread())->getTable();
        $thread = Thread::forUser(auth()->id())->where($threadTable . '.id', $id)->firstOrFail($id);

        // Get the User ID from Thread Messages
        $messages = ThreadMessage::whereNull('deleted_by_admin')->notDeletedByUser(auth()->id())->where('thread_id', $thread->id);
        $messages = $messages->paginate($this->perPage);
        $linksRender = $messages->links('admin.affiliates.message.message-chat.pagination')->render();
        $messages = $messages->items();
        //Get user data from applicants to check weather contact card is unlocked or not

        try {
            $threadTable = (new Thread())->getTable();
            $thread = Thread::forUser(auth()->id())->where($threadTable . '.id', $id)->firstOrFail($id);
            // Get the Thread's Messages
            $messages = ThreadMessage::query()
                ->notDeletedByUser(auth()->id())
                ->where('thread_id', $thread->id)
                ->whereNull('deleted_by_admin')
                ->orderByDesc('id');

            $messages = $messages->paginate($this->perPage);
            $linksRender = $messages->links('admin.affiliates.message.message-chat.pagination')->render();
            $totalPages = $messages->lastPage();

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

        $result = [];
        $result['messages'] = view('admin.affiliates.message.message-chat.chat', ['messages' => $messages])->render();
        $result['pages'] = $totalPages;
        return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);

    }

    public function messagesend(\Illuminate\Http\Request $request)
    {
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
        if ($messageCount == 1) {
            $this->sendmessageemail($thread_data_unread, $thread_data_read);
        }
        Helper::add_notification('message', $thread_data_unread->user_id);
        $user = User::find($request['send_user_id']);
        $affiliate['name'] = $user->name;
        $affiliate['email'] = $user->email;
        $affiliate['action'] = 'message';
        $affiliateDescription = Helper::adminAffiliateActivityLogDescription($affiliate, 'send_message_to_affiliate');
        if(!empty($affiliateDescription)){
            Helper::activity_log($affiliateDescription);
        }
        $msg = "Message has successfully sent";
        flash($msg)->success();
        return redirect()->back();
    }

    public function sendmessageemail($thread_data_unread, $thread_data_read)
    {
        $unread_user = User::withoutGlobalScopes()->where('id', $thread_data_unread->user_id)->first();
        $read_user = User::withoutGlobalScopes()->where('id', $thread_data_read->user_id)->first();

        $data['email'] = $unread_user->email;
        $data['myName'] = $unread_user->name;
        $data['subject'] = 'New Direct Message';
        $data['from_user_name'] = $read_user->name;
        $data['to_user'] = "Affiliate";
        $data['view'] = 'emails.new_affiliate_message';
        $data['cc'] = '';
        $data['header'] = 'New Message Received';
        $helper = new Helper();
        $response = $helper->send_email($data);
    }

    public function update($id, \Illuminate\Http\Request $request)
    {
        if (!request()->ajax()) {
            return;
        }
        $result = ['success' => false];

        try {
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

        // Save and Send user's resume
        if ($request->hasFile('filename')) {
            $file = $request->file('filename');

            if ($file->isValid()) {
                $file_type = $file->getClientOriginalExtension();
                $destinationPath = public_path('/') . '/storage/files/kw/' . $message->id . '/affiliates/';
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                $fileName = 'files/kw/' . $message->id . '/affiliates/' . time() . '.' . $file_type;
                $file->move($destinationPath, $fileName);

                $message->filename = $fileName;
                $message->save();

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


        $date = date('y-m-d');
        $thread_data_not_login = ThreadParticipant::where('thread_id', $thread->id)->where('user_id', '!=', auth()->id())->first();
        $thread_data_login = ThreadParticipant::where('thread_id', $thread->id)->where('user_id', auth()->id())->first();
        $messageCount = ThreadMessage::where('thread_id', $thread->id)->where('user_id', auth()->id())->where('created_at', 'like', '%' . $date . '%')->count();


        if ($messageCount == 1) {
            $this->sendmessageemail($thread_data_not_login, $thread_data_login);
        }


        $data_thread = ThreadParticipant::where('thread_id', $id)->where('user_id', '!=', auth()->user()->id)->first();

        Helper::add_notification('message', $data_thread->user_id);

        $user = User::find($data_thread->user_id);
        $affiliate['name'] = $user->name;
        $affiliate['email'] = $user->email;
        $affiliate['action'] = 'reply';
        $affiliateDescription = Helper::adminAffiliateActivityLogDescription($affiliate, 'send_message_to_affiliate');
        if(!empty($affiliateDescription)){
            Helper::activity_log($affiliateDescription);
        }

        if (!$errorFound) {
            $result['success'] = true;
            $result['msg'] = t('Your reply has been sent');
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
        $data_thread = ThreadParticipant::where('thread_id', $message->thread_id)->where('user_id', '!=', auth()->user()->id)->first();        
        $user = User::where('id', $data_thread->user_id)->select('email','name')->first();
        $affiliateDescription = Helper::adminAffiliateActivityLogDescription($user, 'delete_message_affiliate');
        if(!empty($affiliateDescription)){
            Helper::activity_log($affiliateDescription);
        }
        return response()->json(['success' => true, 'msg' => 'Message deleted successfully.']);
    }

    public function getAffiliateUnreadMessage()
    {
        $result = Thread::getUnreadAffiliateThreads();
        return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);

    }
}