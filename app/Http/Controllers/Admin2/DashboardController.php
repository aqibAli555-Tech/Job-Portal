<?php
namespace App\Http\Controllers\Admin;

use App\Helpers\EmailCheck;
use App\Http\Controllers\Admin\Traits\Charts\ChartjsTrait;
use App\Http\Controllers\Admin\Traits\Charts\MorrisTrait;
use App\Models\Activities;
use App\Models\Applicant;
use App\Models\Contact;
use App\Models\ContactCardProblems;
use App\Models\Country;
use App\Models\EmailQueue;
use App\Models\PackageCancelReason;
use App\Models\Payment;
use App\Models\CompanyPackages;
use App\Models\Post;
use App\Models\PageCount;
use App\Models\PostArchivedOrCancleReason;
use App\Models\RejectedReason;
use App\Models\Unlock;
use App\Models\User;
use App\Models\PostRemaining;
use App\Models\UserResume;
use App\Models\UserSkills;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;
use Larapen\Admin\app\Http\Controllers\PanelController;
use phpDocumentor\Reflection\DocBlock\Tags\Uses;


class DashboardController extends PanelController
{
    use MorrisTrait, ChartjsTrait;

    public $data = [];

    protected $countCountries = 0;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {

        // dd('123');

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

    /**
     * Show the admin dashboard.
     *
     * @return Factory|View
     */
    public function dashboard()
    {
        $this->data['title'] = trans('admin.dashboard');
        // User data
        $this->data['rejected_reasons'] = RejectedReason::get_rejected_reasons_with_count();
        $this->data['package_cancel_reasons'] = PackageCancelReason::get_cancel_reasons_with_count();
        $this->data['post_archived_reasons'] = PostArchivedOrCancleReason::get_reasons_with_count();

        $this->data['interview'] = Applicant::get_applicant_counts_by_status('interview');
        $this->data['rejected'] = Applicant::get_applicant_counts_by_status('rejected');
        $this->data['hired'] = Applicant::get_applicant_counts_by_status('hired');
        $this->data['number_of_employees'] = User::number_of_employees_count();
        $this->data['number_of_employeers'] = User::number_of_employeers_count();
        $this->data['verfied_phone_employees'] = User::verfied_phone_employees_count();
        $this->data['verfied_phone_employeers'] = User::verfied_phone_employeers_count();
        $this->data['top_country_employees'] = User::get_all_country_employee(2);
        $this->data['top_nationality_employees'] = User::get_nationality_users(2);
        $this->data['top_country_employers'] = User::get_all_country_employee(1);
        $this->data['latestUsers'] = User::latestUsers();
        $this->data['latestEmployeers'] = User::latestEmployeers();
        $this->data['current_subscription_users'] = CompanyPackages::current_subscription_users_count();
        
        // Payment data
        $this->data['top_life_time_users'] = Payment::top_life_time_users_count();
        $this->data['revenue'] = Payment::revenue_count();
        $this->data['count_no_contact_Cv'] = User::user_count_with_no_contact_Cv();
        // Post data
        $this->data['latestPosts'] = Post::latestPosts();
        $this->data['top_skill_posts'] = Post::top_skill_post_with_count();
        // Other data
        $this->data['assign_cridit'] = EmailCheck::check_assign_cridit();
        $this->data['pending_applicants'] = Applicant::get_pending_applicants_count();
        $this->data['latestemailqueue'] = EmailQueue::get_latest_email_queue_count();
        $this->data['latestContacts'] = Contact::latestContacts();
        $this->data['latestcontactcardproblems'] = ContactCardProblems::latestcontactcardproblems();
        $this->data['latestactivitilog'] = Activities::latestactivitilog();
        $this->data['total_applicants'] = Applicant::total_applicants();
        $this->data['total_search_cv_applicants'] = Applicant::total_search_cv_applicants();
        $this->data['total_unlock'] = Unlock::count();
        $cuurentjobs = Post::where('is_active', 1)->where('is_deleted', 0)->where('is_post_expire', 0);
        $this->data['unapproved_new_cv'] = UserResume::get_all_unapproved_cv();
        $this->data['unapproved_skill'] = UserSkills::get_all_unapproved_skill();

        $this->data['cuurentjobs'] = $cuurentjobs->count();
        $this->data['pending_jobs'] = POST::where('is_approved',0)->count();
       
       
        $lifetimejobs = Post::all();
        $this->data['lifetimejobs'] = $lifetimejobs->count();
        // Charts data
        [$provider, $type] = array_pad(explode('_', config('settings.app.vector_charts_type')), 2, '');
        $this->data['chartsType'] = ['provider' => $provider ?: 'morris', 'type' => $type ?: 'bar'];
        $statDayNumber = 30;
        $this->data['latestPostsChart'] = method_exists($this, $getLatestPostsChartMethod = 'getLatestPostsFor' . ucfirst($provider)) ? $this->$getLatestPostsChartMethod($statDayNumber) : null;
        $this->data['latestUsersChart'] = method_exists($this, $getLatestUsersChartMethod = 'getLatestUsersFor' . ucfirst($provider)) ? $this->$getLatestUsersChartMethod($statDayNumber) : null;
        // Country charts data (optional)
        if (config('settings.app.show_countries_charts')) {
            $countriesLimit = 10;
            $this->data['postsPerCountry'] = $this->getPostsPerCountryForChartjs($countriesLimit);
            $this->data['usersPerCountry'] = $this->getUsersPerCountryForChartjs($countriesLimit);
        }
        return view('admin::dashboard.index', $this->data);
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
}
