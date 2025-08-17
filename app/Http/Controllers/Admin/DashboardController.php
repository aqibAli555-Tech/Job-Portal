<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\EmailCheck;
use App\Helpers\Helper;
use App\Http\Controllers\Admin\Traits\Charts\ChartjsTrait;
use App\Http\Controllers\Admin\Traits\Charts\MorrisTrait;
use App\Http\Controllers\Controller;
use App\Models\Activities;
use App\Models\Applicant;
use App\Models\CompanyPackages;
use App\Models\Contact;
use App\Models\ContactCardProblems;
use App\Models\Country;
use App\Models\EmailQueue;
use App\Models\PackageCancelReason;
use App\Models\Payment;
use App\Models\Post;
use App\Models\PostArchivedOrCancleReason;
use App\Models\PostRemaining;
use App\Models\ReferralCommission;
use App\Models\RejectedReason;
use App\Models\Thread;
use App\Models\Unlock;
use App\Models\User;
use App\Models\UserResume;
use App\Models\UserSetting;
use App\Models\UserSkills;
use App\Models\WithdrawRequest;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;


class DashboardController extends Controller
{
    use MorrisTrait, ChartjsTrait;

    public $data = [];
    protected $countCountries = 0;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('admin');
        parent::__construct();

        // Get the Mini Stats data
        $countActivatedPosts = 0;
        $countUnactivatedPosts = 0;
        $countActivatedUsers = 0;
        $countUnactivatedUsers = 0;
        $countUsers = 0;

        try {
            $countActivatedPosts = Post::verified()->count();
            $countUnactivatedPosts = Post::unverified()->count();
            $countActivatedUsers = User::doesntHave('permissions')->verified()->count();
            $countUnactivatedUsers = User::doesntHave('permissions')->unverified()->count();
            $countUsers = User::doesntHave('permissions')->count();
            $this->countCountries = Country::where('active', 1)->count();
        } catch (Exception $e) {
        }

        view()->share('countActivatedPosts', $countActivatedPosts);
        view()->share('countUnactivatedPosts', $countUnactivatedPosts);
        view()->share('countActivatedUsers', $countActivatedUsers);
        view()->share('countUnactivatedUsers', $countUnactivatedUsers);
        view()->share('countUsers', $countUsers);
        view()->share('countCountries', $this->countCountries);
    }

    public function getRevenue($range)
    {
        $revenue = Payment::get_revenue($range);
        return response()->json($revenue);
    }


    /**
     * Show the admin dashboard.
     *
     * @return Factory|View
     */
    public function dashboard()
    {
        $this->data = Cache::remember(time(), 3000000, function () {
            $data = [];
            $data['title'] = trans('admin.dashboard');
            $data['rejected_reasons'] = RejectedReason::get_rejected_reasons_with_count();
            $data['unread_threads'] = Thread::getUnreadThreads();
            $data['unread_affiliate_threads'] = Thread::getUnreadAffiliateThreads();
//            dd($data['unread_threads']);
            $data['post_archived_reasons'] = PostArchivedOrCancleReason::get_reasons_with_count();
            $data['package_cancel_reasons'] = PackageCancelReason::get_cancel_reasons_with_count();
            $data['interview'] = Applicant::get_applicant_counts_by_status('interview');
            $data['rejected'] = Applicant::get_applicant_counts_by_status('rejected');
            $data['hired'] = Applicant::get_applicant_counts_by_status('hired');
            $data['number_of_employees'] = User::get_employees_count(2);
            $data['number_of_employeers'] = User::number_of_employeers_count();
            $data['verfied_phone_employees'] = User::verfied_phone_employees_count();
            $data['verfied_phone_employeers'] = User::verfied_phone_employeers_count();
            $data['top_country_employees'] = User::get_all_country_employee(2);
            $data['top_nationality_employees'] = User::get_nationality_users(2);
            $data['top_country_employers'] = User::get_all_country_employee(1);
            $data['latestUsers'] = User::latestUsers();
            $data['latestEmployeers'] = User::latestEmployeers();
            $data['current_subscription_users'] = CompanyPackages::current_subscription_users_count();
            // Payment data
            $data['top_life_time_users'] = Payment::top_life_time_users_count();
            $data['revenue'] = Payment::revenue_count();

            $data['count_no_contact_Cv'] = User::user_count_with_no_contact_Cv();
            // Post data
            $data['latestPosts'] = Post::latestPosts();
            $data['top_skill_posts'] = Post::top_skill_post_with_count();
            // Other data
            $data['assign_cridit'] = EmailCheck::check_assign_cridit();
            $data['pending_applicants'] = Applicant::get_pending_applicants_count();
            $data['latestemailqueue'] = EmailQueue::get_latest_email_queue_count();
            $data['latestContacts'] = Contact::latestContacts();
            $data['latestcontactcardproblems'] = ContactCardProblems::latestcontactcardproblems();
            $data['latestactivitilog'] = Activities::latestactivitilog();
            $data['total_applicants'] = Applicant::total_applicants();
            $data['total_search_cv_applicants'] = Applicant::total_search_cv_applicants();
            $data['total_unlock'] = Unlock::count();

            $currentDate = Carbon::now();

            $date_post = $currentDate->format('Y-m-d H:i:s');
            $cuurentjobs = PostRemaining::leftJoin('posts', 'posts.id', '=', 'post_remaining.post_id')
                ->whereNotNull('post_id')
                ->where('post_remaining.is_post_expire', 0)
                ->where('post_expire_date_time', '>=', $date_post)
                ->where('posts.is_deleted', 0)
                ->get();

            $data['cuurentjobs'] = $cuurentjobs->count();

            $data['unapproved_new_cv'] = UserResume::get_all_unapproved_cv();
            $data['unapproved_skill'] = UserSkills::get_all_unapproved_skill();
            $data['pending_jobs'] = POST::where('is_approved', 0)->count();
            $data['free_cv_no_contact'] = Activities::where('type','cv_no_contact')->count();
            $data['whatsapp_users_count'] = UserSetting::whereNotNull('whatsapp_number')->where('whatsapp_number', '!=', '')->count();
            $lifetimejobs = Post::all();
            $data['lifetimejobs'] = $lifetimejobs->count();
            // Charts data
            [$provider, $type] = array_pad(explode('_', config('settings.app.vector_charts_type')), 2, '');
            $data['chartsType'] = ['provider' => $provider ?: 'morris', 'type' => $type ?: 'bar'];
            $statDayNumber = 30;
            $data['latestPostsChart'] = method_exists($this, $getLatestPostsChartMethod = 'getLatestPostsFor' . ucfirst($provider)) ? $this->$getLatestPostsChartMethod($statDayNumber) : null;
            $data['latestUsersChart'] = method_exists($this, $getLatestUsersChartMethod = 'getLatestUsersFor' . ucfirst($provider)) ? $this->$getLatestUsersChartMethod($statDayNumber) : null;
            // Country charts data (optional)
            if (config('settings.app.show_countries_charts')) {
                $countriesLimit = 10;
                $data['postsPerCountry'] = $this->getPostsPerCountryForChartjs($countriesLimit);
                $data['usersPerCountry'] = $this->getUsersPerCountryForChartjs($countriesLimit);
            }        
            return $data;
        });

        $title = $this->data['title'];
        $breadcumbs = [
            [
                'title' => 'Dashboard',
                'link' => 'javascript:void(0)'
            ]
        ];
        return view('admin.dashboard.index', $this->data, compact('title', 'breadcumbs'));
    }


    /**
     * Redirect to the dashboard.
     *
     * @return Redirector|RedirectResponse
     */
    public function crud()
    {
        return 'happy Coding';
    }

    public function redirect()
    {

        // The '/admin' route is not to be used as a page, because it breaks the menu's active state.
        return redirect(admin_uri('dashboard'));
    }

    /**
     * Show the admin affiliate dashboard stats.
     *
     * @return Factory|View
     */
    public function affiliateDashboardStats()
    {
        $this->data = Cache::remember(time(), 3000000, function () {
            $data = [];
            $data['unread_affiliate_threads'] = Thread::getUnreadAffiliateThreads();
            $data['number_of_affiliates'] = User::number_of_affiliates_count();
            $data['total_withdraw_requests'] = WithdrawRequest::getUnseenRequests();

            $data += Helper::get_affiliate_dashboard_metrics();

            return $data;
        });

        $breadcumbs = [
            [
                'title'=> 'Dashboard',
                'link'=> admin_url('dashboard')
            ],
            [
                'title' => 'Affiliate Stats',
                'link' => 'javascript:void(0)'
            ]
        ];
        return view('admin.dashboard.affiliate-index', $this->data, compact('breadcumbs'));
    }
}
