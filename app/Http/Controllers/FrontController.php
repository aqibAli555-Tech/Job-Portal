<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Search\Traits\TitleTrait;
use App\Http\Controllers\Traits\CommonTrait;
use App\Http\Controllers\Traits\RobotsTxtTrait;
use App\Http\Controllers\Traits\SettingsTrait;
use App\Models\Applicant;
use App\Models\Company;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Post;
use App\Models\Resume;
use App\Models\SavedPost;
use App\Models\SavedSearch;
use App\Models\Scopes\ReviewedScope;
use App\Models\Scopes\VerifiedScope;
use App\Models\Thread;

class FrontController extends Controller
{

    use SettingsTrait, RobotsTxtTrait, CommonTrait, TitleTrait;

    public $request;
    public $data = [];
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
        // Set the storage disk
        $this->setStorageDisk();


        // From Laravel 5.3.4+
        $this->middleware(function ($request, $next) {
            if (auth()->check()) {
                $this->leftMenuInfo();
            }
            // Load Localization Data first
            // Check out the SetCountryLocale Middleware
//            $this->checkDotEnvEntries();
            $this->applyFrontSettings();

            return $next($request);
        });
        view()->share('pagePath', '');

    }

    public function leftMenuInfo()
    {

        // Share User Info
        view()->share('user', auth()->user());

        $today = date('Y-m-d');
        $expire = 0;

        if (strtotime(auth()->user()->post_expire ?? '') < strtotime($today ?? '')) {
            $expire = 1;
        }

        $freepackage = Package::where('price', '0.00')->first()->toArray();

        if (!empty($expire)) {

            if (!empty($freepackage['number_of_posts'])) {
                $this->myPosts = Post::currentCountry()
                    ->where('user_id', auth()->user()->id)
                    ->where('is_deleted', 0)
                    ->where('is_active', 1)
                    ->verified()
                    ->unarchived()
                    ->where('is_post_expire', 0)
                    ->reviewed()
                    ->take((int)$freepackage['number_of_posts'])
                    ->with(['postDetail', 'postDetail.city', 'postMeta', 'employeeskill', 'latestPayment' => function ($builder) {
                        $builder->with(['package']);
                    }])
                    ->orderByDesc('id');
            } else {
                $this->myPosts = Post::where('user_id', auth()->user()->id)
                    ->where('is_deleted', 0)
                    ->where('is_active', 1)
                    ->verified()
                    ->where('is_post_expire', 0)
                    ->unarchived()
                    ->reviewed()
                    ->with(['postDetail', 'postDetail.city', 'postMeta', 'employeeskill', 'latestPayment' => function ($builder) {
                        $builder->with(['package']);
                    }])
                    ->orderByDesc('id');
            }
        } else {

            $this->myPosts = Post::where('user_id', auth()->user()->id)
                ->where('is_deleted', 0)
                ->where('is_active', 1)
                ->where('is_post_expire', 0)
                ->verified()
                ->unarchived()
                ->reviewed()
                ->with(['postDetail', 'postDetail.city', 'postMeta', 'employeeskill', 'latestPayment' => function ($builder) {
                    $builder->with(['package']);
                }])
                ->orderByDesc('id');
        }

        $clonePostQuery = clone $this->myPosts;
        // My Posts
        view()->share('countMyPosts', count($clonePostQuery->get()->toArray()));

        // Archived Posts
        $this->archivedPosts = Post::currentCountry()
            ->where('user_id', auth()->user()->id)
            ->where('is_deleted', 0)
            ->where('is_post_expire', 1)
            ->with(['postDetail', 'postDetail.city', 'postMeta', 'employeeskill', 'latestPayment' => function ($builder) {
                $builder->with(['package']);
            }])
            ->orderByDesc('id');

        view()->share('countArchivedPosts', $this->archivedPosts->count());

        // Favourite Posts
        $this->favouritePosts = SavedPost::whereHas('post', function ($query) {
            $query->currentCountry();
        })
            ->where('saved_posts.user_id', auth()->user()->id)
            ->with(['postDetail', 'postDetail.city', 'postMeta'])
            ->join('posts', 'posts.id', '=', 'saved_posts.post_id')
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->where('is_post_expire', 0)
            ->where('posts.archived', 0)
            ->orderByDesc('saved_posts.id');

        view()->share('countFavouritePosts', $this->favouritePosts->count());

        // Pending Approval Posts
        $this->pendingPosts = Post::withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])
            ->currentCountry()
            ->where('user_id', auth()->user()->id)
            ->unverified()
            ->with(['postDetail', 'postDetail.city', 'postMeta', 'employeeskill', 'latestPayment' => function ($builder) {
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
        $this->companies = Company::where('c_id', auth()->user()->id)->whereNull('deleted_at')->get();
        view()->share('countCompanies', $this->companies->count());


        $this->appliedjobDataCount = Applicant::with('User')->with(['Post'])->where('user_id', auth()->user()->id)->where('status', '!=', 'not')->count();

        view()->share('appliedjobDataCount', $this->appliedjobDataCount);

        // Resumes
        $this->resumes = Resume::where('user_id', auth()->user()->id)->orderByDesc('id');
        view()->share('countResumes', $this->resumes->count());
    }
}
