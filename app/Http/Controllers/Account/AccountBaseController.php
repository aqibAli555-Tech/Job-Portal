<?php

namespace App\Http\Controllers\Account;

use App\Helpers\Helper;
use App\Http\Controllers\FrontController;
use App\Models\Activities;
use App\Models\Allsaved_resume;
use App\Models\Applicant;
use App\Models\Company;
use App\Models\ContactCardViewLog;
use App\Models\Favoriteresume;
use App\Models\MessageRequest;
use App\Models\Notification;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Post;
use App\Models\Resume;
use App\Models\SavedPost;
use App\Models\SavedSearch;
use App\Models\Scopes\ReviewedScope;
use App\Models\Scopes\VerifiedScope;
use App\Models\Thread;
use App\Models\Unlock;

abstract class AccountBaseController extends FrontController
{
    public $countries;
    public $myPosts;
    public $archivedPosts;
    public $favouritePosts;
    public $pendingPosts;
    public $threads;
    public $threads2;
    public $threadsWithNewMessage;
    public $threadsWithNewMessage2;
    public $transactions;
    public $companies;
    public $resumes;
    public $appliedjobDataCount;

    public function __construct()
    {
        parent::__construct();
        $this->middleware(function ($request, $next) {
            if (auth()->check()) {
                $this->leftMenuInfo();
            }
            return $next($request);
        });

        view()->share('pagePath', '');
    }

    public function leftMenuInfo()
    {
        // Share User Info
        view()->share('user', auth()->user());
        $todayDate = date('Y-m-d');
        $expire_date = auth()->user()->post_expire;
        $is_valid = Helper::check_expiry($todayDate, $expire_date);

        $freepackage = Package::where('price', '0.00')->first()->toArray();

        if (!empty($is_valid)) {
            if (!empty($freepackage['number_of_posts'])) {
                $this->myPosts = Post::where('user_id', auth()->user()->id)
                    ->where('is_deleted', 0)
                    ->where('is_active', 1)
                    ->verified()
                    ->unarchived()
                    ->reviewed()
                    ->where('is_post_expire', 0)
                    ->take((int)$freepackage['number_of_posts'])
                    ->with(['postDetail', 'postDetail.city', 'postMeta', 'latestPayment' => function ($builder) {
                        $builder->with(['package']);
                    }])
                    ->orderByDesc('id');
            } else {
                $this->myPosts = Post::where('user_id', auth()->user()->id)
                    ->where('is_deleted', 0)
                    ->where('is_active', 1)
                    ->verified()
                    ->unarchived()
                    ->reviewed()
                    ->where('is_post_expire', 0)
                    ->with(['postDetail', 'postDetail.city', 'postMeta', 'latestPayment' => function ($builder) {
                        $builder->with(['package']);
                    }])
                    ->orderByDesc('id');
            }
        } else {
            $this->myPosts = Post::where('user_id', auth()->user()->id)
                ->where('is_deleted', 0)
                ->where('is_active', 1)
                ->verified()
                ->unarchived()
                ->reviewed()
                ->where('is_post_expire', 0)
                ->with(['postDetail', 'postDetail.city', 'postMeta', 'latestPayment' => function ($builder) {
                    $builder->with(['package']);
                }])
                ->orderByDesc('id');
        }

        $clonePostQuery = clone $this->myPosts;

        // My Posts

        // Archived Posts
        $this->archivedPosts = Post::where(function ($query) {
            $query->where('is_post_expire', 1)
                ->orWhere('archived', 1);
        })->where('user_id', auth()->user()->id)
            ->with(['postDetail', 'postDetail.city', 'postMeta', 'latestPayment' => function ($builder) {
                $builder->with(['package']);
            }])->where('is_deleted',0)
            ->orderByDesc('id');
        view()->share('countArchivedPosts', $this->archivedPosts->count());

        // Favourite Posts
        $this->favouritePosts = SavedPost::whereHas('post', function ($query) {
            //$query->currentCountry();
        })->where('saved_posts.user_id', auth()->user()->id)
            ->join('posts', 'posts.id', '=', 'saved_posts.post_id')
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->where('posts.archived', 0)
            ->where('is_post_expire', 0)
            ->orderByDesc('saved_posts.id');
        view()->share('countFavouritePosts', $this->favouritePosts->count());

        // Pending Approval Posts
        $this->pendingPosts = Post::withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])
            ->currentCountry()
            ->where('user_id', auth()->user()->id)
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->unverified()
            ->with(['postDetail', 'postDetail.city', 'postMeta', 'latestPayment' => function ($builder) {
                $builder->with(['package']);
            }])
            ->orderByDesc('id');
        view()->share('countPendingPosts', $this->pendingPosts->count());

        // Save Search
        $savedSearch = SavedSearch::currentCountry()
            ->where('user_id', auth()->user()->id)
            ->orderByDesc('id');
        view()->share('countSavedSearch', $savedSearch->count());

        // Threads
        $this->threads = Thread::forUser(auth()->id())->latest('updated_at');
        view()->share('countThreads', $this->threads->count());

        // Threads (With New Messages)
        $this->threadsWithNewMessage = Thread::forUserWithNewMessages(auth()->id());
        view()->share('countThreadsWithNewMessage', $this->threadsWithNewMessage->count());
        $this->threadsWithNewMessage = Thread::whereHas('post', function ($query) {
            $query->currentCountry()->unarchived();
        })->forUserWithNewMessages(auth()->id());
        view()->share('countThreadsWithNewMessage', $this->threadsWithNewMessage->count());

        // Payments
        $this->transaction = Payment::with('Package')->where('user_id', auth()->user()->id)->get();
        if (!empty($this->transaction)) {
            view()->share('countTransactions', count($this->transaction));
        } else {
            view()->share('countTransactions', 0);
        }

        // Companies
        $this->companies = Company::with('companyData')->where(['user_id' => auth()->user()->id, 'deleted_at' => NULL])->orderby('id', 'asc');
        view()->share('countCompanies', $this->companies->count());
        $this->appliedjobDataCount = Applicant::with('User')->with(['Post'])->where('user_id', auth()->user()->id)->where('status', '!=', 'not')->get()->count();
        view()->share('appliedjobDataCount', $this->appliedjobDataCount);

        // Resumes
        $this->resumes = Resume::where('user_id', auth()->user()->id)->orderByDesc('id');
        view()->share('resumes', $this->resumes->count());
        $postid = Post::get_active_post_id();

        if (!empty($postid)) {
            $not_accurate=request()->get('show_not_accurate_employee');
            $total_applied_applicants = Applicant::get_applicant_count_by_status('applied', $postid,$not_accurate);
            $total_interview_applicants = Applicant::get_applicant_count_by_status('interview', $postid,$not_accurate);
            $total_applicants = $total_applied_applicants + $total_interview_applicants;
        } else {
            $total_applicants = 0;
        }

        $total_applied_applicants_unlock = Applicant::get_employer_applicants('applied');
        $total_interview_applicants_unlock = Applicant::get_employer_applicants('interview');
        $totalapplicatns = $total_applied_applicants_unlock + $total_interview_applicants_unlock + $total_applicants;
        view()->share('totalapplicatns', $totalapplicatns);

        $message_request_count = MessageRequest::get_message_request_count();
        view()->share('message_request_count', $message_request_count);
        $fav_count = Favoriteresume::get_fav_cv_count();
        view()->share('fav_count', $fav_count);
        $totalsaved = Allsaved_resume::get_all_save_cv_count();
        view()->share('totalsaved', $totalsaved);
        $totalunlock = Unlock::get_all_unlock_contact_count();
        view()->share('totalunlock', $totalunlock);

        $posts = Post::get_all_archive_post_ids();
        $Archivetotalapplicants = Applicant::where('to_user_id', auth()->user()->id)->where('is_deleted',0)->whereIn('post_id', $posts)->count();
        
        view()->share('Archivetotalapplicants', $Archivetotalapplicants);

        $who_view_your_cv_count = ContactCardViewLog::get_all_user_cv_viewd_count();
        view()->share('who_view_your_cv_count', $who_view_your_cv_count);


        $archive_post = Post::get_all_archive_post_count();
        view()->share('archive_post', $archive_post);

        $countMyPosts = Post::get_all_post_post_count_by_employer_id();
        view()->share('countMyPosts', $countMyPosts);


        $companyCount = Company::get_all_company_count();
        view()->share('companyCount', $companyCount);

        $message_notification=Notification::get_notification_by_type('message')->count();
        view()->share('messagenotificationcount', $message_notification);

        $applicants_notification=Notification::get_notification_by_type('applicants')->count();
        view()->share('applicants_notification', $applicants_notification);

        $notification_profile = Notification::get_notification_by_type('profile')->count();
        view()->share('notification_profile', $notification_profile);

        $notification_resume = Notification::get_notification_by_type('resume')->count();
        view()->share('notification_resume', $notification_resume);

    }
}