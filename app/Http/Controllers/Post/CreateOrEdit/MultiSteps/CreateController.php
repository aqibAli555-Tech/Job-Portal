<?php

namespace App\Http\Controllers\Post\CreateOrEdit\MultiSteps;

use App\Helpers\Helper;
use App\Helpers\UrlGen;
use App\Http\Controllers\Auth\Traits\VerificationTrait;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\Post\CreateOrEdit\MultiSteps\Traits\EditTrait;
use App\Http\Controllers\Post\CreateOrEdit\Traits\AutoRegistrationTrait;
use App\Http\Controllers\Post\CreateOrEdit\Traits\PricingTrait;
use App\Http\Requests\PostRequest;
use App\Models\CompanyPackages;
use App\Models\City;
use App\Models\Company;
use App\Models\Country;
use App\Models\EmployeeSkill;
use App\Models\Nationality;
use App\Models\OptionalSelectedEmails;
use App\Models\Package;
use App\Models\Post;
use App\Models\PostDetails;
use App\Models\PostMeta;
use App\Models\PostRemaining;
use App\Models\PostType;
use App\Models\SalaryType;
use App\Models\Scopes\ReviewedScope;
use App\Models\Scopes\VerifiedScope;
use App\Models\User;
use DB;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Mail;
use Torann\LaravelMetaTags\Facades\MetaTag;


class CreateController extends FrontController
{
    use EditTrait, VerificationTrait, PricingTrait, AutoRegistrationTrait;

    public $data;

    /**
     * CreateController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        // Check if guests can post Ads
        if (config('settings.single.guests_can_post_ads') != '1') {
            $this->middleware('auth')->only(['getForm', 'postForm']);
        }

        // From Laravel 5.3.4 or above
        $this->middleware(function ($request, $next) {
            $this->commonQueries();

            return $next($request);
        });
    }

    /**
     * Common Queries
     */
    public function commonQueries()
    {
        // References
        $data = [];
        // Get Post Types
        $cacheId = 'postTypes.all.' . config('app.locale');
        $data['postTypes'] = PostType::get();
        view()->share('postTypes', $data['postTypes']);

        // Get Salary Types
        $cacheId = 'salaryTypes.all.' . config('app.locale');
        $data['salaryTypes'] = Cache::remember($cacheId, $this->cacheExpiration, function () {
            return SalaryType::orderBy('lft')->get();
        });
        view()->share('salaryTypes', $data['salaryTypes']);

        if (auth()->check()) {
            // Get all the User's Companies
            if (!empty(auth()->user()->parent_id)) {
                $data['companies'] = Company::where('c_id', auth()->user()->id)->whereNull('deleted_at')->take(100)->orderByDesc('id')->get();
            } else {
                $data['companies'] = Company::where('user_id', auth()->user()->id)->whereNull('deleted_at')->take(100)->orderByDesc('id')->get();
            }

            view()->share('companies', $data['companies']);

            // Get the User's latest Company
            if ($data['companies']->has(0)) {
                $data['postCompany'] = $data['companies']->get(0);
                view()->share('postCompany', $data['postCompany']);
            }
        }

        // Count Packages
        $data['countPackages'] = Package::applyCurrency()->count();
        view()->share('countPackages', $data['countPackages']);

        // Count Payment Methods
        $data['countPaymentMethods'] = $this->countPaymentMethods;

        // Save common's data
        $this->data = $data;
    }

    /**
     * New Post's Form.
     *
     * @param null $tmpToken
     * @return Factory|RedirectResponse|Redirector|View
     */
    public function getForm(Request $request, $tmpToken = null)
    {

        if (auth()->check()) {


            if (auth()->user()->user_type_id == 2) {
                flash(t("Permission error.."))->error();
                return redirect('/');
            }

            $data_compn = Company::where('c_id', auth()->user()->id)->first();

            if (!empty($data_compn)) {
                if (empty($data_compn->country_code)) {
                    $data_compn->country_code = auth()->user()->country_code;
                    $data_compn->update();
                }
                if (empty($data_compn->city_id)) {
                    $data_compn->city_id = auth()->user()->city;
                    $data_compn->update();
                }
            }
        } else {
            flash('You are not logged in. Please log in first')->error();
            return redirect('login');
        }
        if (!empty($request->get('company'))) {
            $data_compn = Company::where('id', $request->get('company'))->first();
            $use_data = User::where('id', $data_compn->c_id)->first();
               $post_count= CompanyPackages::check_post_available(auth()->user()->id);
            if (!empty($use_data) && $post_count < 1) {
                flash(t("Please upgrade your account and receive credit to Post a Job and to view employee Contact Cards"))->error();
                return redirect('account/companies');
            }
        }

        $today = date('Y-m-d');

        // Only Admin users and Employers/Companies can post ads
        if (auth()->check()) {
            if (!in_array(auth()->user()->user_type_id, [1])) {
                return redirect()->intended('account');
            }
        }

        // Check possible Update
        if (!empty($tmpToken)) {
            session()->keep(['message']);

            return $this->getUpdateForm($tmpToken);
        }

        // Meta Tags
        MetaTag::set('title', getMetaTag('title', 'create'));
        MetaTag::set('description', strip_tags(getMetaTag('description', 'create')));
        MetaTag::set('keywords', getMetaTag('keywords', 'create'));
        $data['employee_skills'] = EmployeeSkill::getAllSkillSetAndEmployer();
        $data['valid_package']=CompanyPackages::check_company_has_premium_package();

        
        $company = Company::where('c_id', auth()->user()->id)->first();
        if (!empty($company->country_code)) {
            $country_code = $company->country_code;
        } else {
            $country_code = auth()->user()->country_code;
        }
      
        $data['country_name'] = Country::get_country_by_code(auth()->user()->country_code);
                $data['city_name'] = City::where('country_code', auth()->user()->country_code)->get();
        // Create
        $data['nationality'] = Nationality::pluck('id', 'name');
        // Meta Tags
        view()->share([
            'title' => t('Create New Job Post'),
            'description' => t('Create New Job Post'),
            'keywords' => t('Create New Job Post'),
            // Add more variables as needed
        ]);


        return appView('post.createOrEdit.multiSteps.create')->with('data', $data);
    }

    public function resendemail($id)
    {
        $post = Post::withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])->where('id', $id)->first();
        $response = $this->sendVerificationEmail($post);
        return redirect('account/pending-approval');
    }

    /**
     * Store a new Post.
     *
     * @param PostRequest $request
     * @param null $tmpToken
     * @return RedirectResponse|Redirector
     */
    public function postForm(PostRequest $request, $tmpToken = null)
    {
        if (auth()->check() == false) {
            return redirect('/');
        }
        // Check possible  update
        if (!empty($tmpToken)) {
            session()->keep(['message']);
            return $this->postUpdateForm($tmpToken, $request);
        }


        $company = Company::where('id', $request->company_id)->first();
        if (!empty($company)) {
            $cityId = !empty($company->city_id) ? $company->city_id : 0;
            $country_code = $company->country_code;
        } else {
            $country_code = auth()->user()->country_code;
        }

        if (!empty($request->post('city_id'))) {
            $cityId = $request->post('city_id');
        }

        if (empty($cityId)) {
            flash("Please select a city first from company")->error();
            return back()->withInput();
        }


        $user_data = User::where('id', $company->c_id)->first();
        $post_count= CompanyPackages::check_post_available($user_data->id);
        if (!empty($post_count) && $post_count < 1) {
            flash(t("User credit is low.Please upgrade your account."))->error();
            return back()->withInput();
        }

        // Get the Post's City
        $city = City::find($cityId);
        if (empty($city)) {
            flash(t("Posting Ads is disabled"))->error();
            return back()->withInput($request->except('company.logo'));
        }

        if (empty($request->get('category_id'))) {
            flash(("Please select an category"))->error();
            return back()->withInput();
        }

        if (empty($request->get('description'))) {
            flash(("Please add some description for post"))->error();
            return back()->withInput();
        }

        //save post

        $post = Post::Save_post_request($request, $country_code);
        $post_id = $post->id;

        if (!empty($post_id)) {
            $result = PostDetails::save_post_details_request($request, $city, $post_id);
            if (empty($result)) {
                $record = Post::find($post_id);
                $record->delete();
                flash(("Post Not Save Successfully.Please try Again"))->error();
                return back()->withInput();
            }
            $result = PostMeta::save_post_meta_request($request, $post_id);
            if (empty($result)) {
                $record = Post::find($post_id);
                $record->delete();
                flash(("Post Not Save Successfully.Please try Again"))->error();
                return back()->withInput();
            }
        } else {
            flash(("Post Not Save Successfully.Please try Again"))->error();
            return back()->withInput();
        }


        if ($post) {
            $post_title = $request->get('title');
            $company_name = auth()->user()->name;
            $post_url = UrlGen::post($post);
            $profile_url = admin_url() . '/employer?search=' . auth()->user()->email;
            $description = "A Company Name: <b> <a href='$profile_url'>$company_name</a> </b>  Add a new job post title:$post_title<br> Click this link to checkout <a href='$post_url'>$post_url</a>";
            Helper::activity_log($description);
            $data['post_title'] = $post_title;
            $data['post_url'] = $post_url;
            $companyDescription = Helper::companyDescriptionData($data, 'job_post');
            if(!empty($companyDescription)){
                Helper::activity_log($companyDescription,auth()->user()->id);
            }
            helper::update_remaining_post(auth()->user(),$post_id);

            $cc = '';
            if (OptionalSelectedEmails::check_selected_email(7, $user_data->id)) {
                $cc = $user_data->optional_emails;
            }
            $this->senduseremail($post, $cc);

            if (request()->segment(2) == 'create') {
                flash(t('Your ad has been created'))->success();
            }
            return redirect($post_url);
        } else {
            flash(t("Post is unable to save please try again"))->error();
            return back()->withInput();
        }
    }

    public function senduseremail($post, $cc = null)
    {

        $data['email'] = $post->email;
        $data['subject'] = 'You Have Posted A New Job Offer';
        $data['myName'] = auth()->user()->name;
        $data['url'] = UrlGen::post($post);
        $data['view'] = 'emails.post_job';
        $data['cc'] = $cc;
        $data['header'] = 'Your Job Is Now Live!';
        $helper = new Helper();
        $response = $helper->send_email($data);
    }

    public function sendadminemail($admin, $user)
    {
        if ($user->type_id == 1) {
            $data['role'] = 'Employer';
        } else {
            $data['role'] = 'Employee(Job Seeker)';
        }
        $data['email'] = $admin->email;
        $data['subject'] = 'New Employee ' . $data['role'] . ' Registration';
        $data['from'] = getenv('MAIL_USERNAME');
        $data['user'] = $user;
        $data['view'] = 'emails.admin_email_for_new_user';
        $data['header'] = 'Job Post now Online';
        $helper = new Helper();
        $response = $helper->send_email($data);
    }

    /**
     * Confirmation
     *
     * @param $tmpToken
     * @return Factory|RedirectResponse|Redirector|View
     */
    public function finish($tmpToken)
    {
        // Keep Success Message for the page refreshing
        session()->keep(['message']);
        if (!session()->has('message')) {
            return redirect('/');
        }

        // Clear the steps wizard
        if (session()->has('tmpPostId')) {
            // Get the Post
            $post = Post::withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])
                ->where('id', session('tmpPostId'))
                ->where('tmp_token', $tmpToken)
                ->first();

            if (empty($post)) {
                abort(404);
            }

            // Apply finish actions
            $post->tmp_token = null;
            $post->save();
            session()->forget('tmpPostId');
        }

        // Redirect to the Post,
        // - If User is logged
        // - Or if Email and Phone verification option is not activated
        if (auth()->check() || (config('settings.mail.email_verification') != 1 && config('settings.sms.phone_verification') != 1)) {
            if (!empty($post)) {
                flash(session('message'))->success();

                return redirect(UrlGen::postUri($post));
            }
        }
        flash(session('message'))->success();
        // Meta Tags
        MetaTag::set('title', session('message'));
        MetaTag::set('description', session('message'));

        return appView('post.createOrEdit.multiSteps.finish');
    }

    public function get_company_by_id($id)
    {
        $data = Company::where('id', $id)->first();
        $data['logo'] = Helper::get_company_logo_AWS($data);
        $user_data = User::where('id', $data->c_id)->first();
          $post_count= CompanyPackages::check_post_available($user_data->id);
        if (!empty($user_data) && $post_count < 0) {
            $button = "<label  href=\"javascript:void(0)\" style='color:black' > Please upgrade your account to receive credit to Post a Job. Click below button to upgrade your account.</label>";
            $data['lowCredit'] = 1;
            $data['lowCreditMessage'] = $button;
            $data['password'] = $user_data->password_without_hash;
            $data['email'] = $user_data->email;
        } else {
            $data['lowCredit'] = 0;
            $data['lowCreditMessage'] = '';
        }
        return response()->json($data, 200);
    }

    public function get_post_data($id)
    {
        $post_data = Post::where('id', $id)->first();
        $post_url = UrlGen::post($post_data);
        return response()->json($post_url, 200);
    }

    public function preview_post(Request $request)
    {

        $data['company'] = Company::find($request->input('company_id'));
        $data['category'] = EmployeeSkill::find($request->input('category_id'));
        $data['post_type'] = PostType::find($request->input('post_type_id'));
        $data['post_data'] = $request->input();
        if (!empty($request->input('skill_set'))) {
            $data['skill_set'] = implode(",", $request->input('skill_set'));
        }
        $nationality = '';
        if (!empty($request->input('nationality'))) {
            if (!empty($request->input('nationality'))) {
                foreach ($request->input('nationality') as $key => $value) {

                    $nationalityTableData = Nationality::where('id', $value)->first();
                    if (empty($nationality)) {
                        $nationality .= $nationalityTableData->name;
                    } else {
                        $nationality .= ', ' . $nationalityTableData->name;
                    }
                }
            }
        }

        $data['nationality'] = $nationality;
        if (empty($data['post_data']['created_at'])) {
            $data['post_data']['created_at'] = date('Y-m-d H:i:s');
        }
        $data['city'] = City::find($data['company']->city_id);
        $data['country'] = Country::find($data['company']->country_code);
        $imageSource = url()->asset('images/flags/16/' . strtolower($data['company']->country_code) . '.png');
        $image = '<img src="' . $imageSource . '"  alt="Image">';
        $data['image'] = $image;
        $data['post_city_url'] = url('latest-jobs?post=&country_code=&q=&l=' . $data['company']->city_id . '=&min_salary=&max_salary=&type[]=');
        $data['post_type_url'] = url('latest-jobs?post=&country_code=&q=&l=&min_salary=&max_salary=&type[]=' . $request->input('post_type_id'));
        $data['post_skill_set_url'] = url('latest-jobs?post=&country_code=&q=' . $request->input('category_id') . '&l=&min_salary=&max_salary=&type[]=');
        $data['post_country_url'] = url('latest-jobs?post=&country_code=' . $data['company']->country_code . '&q=&l=&min_salary=&max_salary=&type[]=');

        echo json_encode($data);
        die;
    }

    public function add_new_skill(Request $request)
    {
        if (empty($request->input('skill_set'))) {
            flash(("Please add Skill Set"))->error();
            return back()->withInput();
        }
        $skill_obj=new EmployeeSkill();
        $skill_obj->skill = $request->input('skill_set');
        $skill_obj->status = 0;
        $skill_obj->employer_id = auth()->user()->id;
        if($skill_obj->save()){
            $company_name=auth()->user()->name;
            $profile_url = admin_url() . '/employer?search=' . auth()->user()->email;
            $description = "A Company Name: <b> <a href='$profile_url'>$company_name</a> </b>  Add a new skill set ".$request->input('skill_set');
            Helper::activity_log($description);
            flash(("Skill Add Successfully"))->info();
            return back()->withInput();
        }else{
            flash(("Please try again"))->error();
            return back()->withInput();
        }
    }
}
