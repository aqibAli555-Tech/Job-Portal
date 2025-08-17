<?php

namespace App\Http\Controllers;

use App\Helpers\ArrayHelper;
use App\Helpers\Helper;
use App\Helpers\EmailCheck;
use App\Helpers\Tap;
use App\Helpers\UrlGen;
use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\EmployeeLogo;
use App\Models\EmployeeSkill;
use App\Models\HomeSection;
use App\Models\Package;
use App\Models\Payment as PaymentModel;
use App\Models\Post;
use App\Models\Statistic;
use App\Models\SubAdmin1;
use App\Models\Thread;
use App\Models\ThreadParticipant;
use App\Models\Unlock;
use App\Models\User;
use App\Models\CompanyPackages;
use DateTime;
use Exception;
use extras\plugins\domainmapping\app\Models\DomainHomeSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Torann\LaravelMetaTags\Facades\MetaTag;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class HomeController extends FrontController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {

        $data = [];


        $countryCode = config('country.code');

        // Get all homepage sections
        $this->getLatestPosts();
        $this->getCategories();
        $this->getFeaturedPostsCompanies();

        //------------Queries for get values of employee Skills and countries....... 

        session()->put('country_code_for_details', session()->get('country_code'));

        if (!empty($request->get('d'))) {
            $countryCode = $request->get('d');

            session()->put('country_code', $countryCode);
            session()->put('country_code_for_search', $request->get('d'));
        }

        $data['emp_skills'] = Cache::remember('emp_skills', config('cache.stores.file.expire'), function () {
            // Your actual query here
            return EmployeeSkill::getAllFeaturedskill();
        });

        $data['employees'] = Cache::remember('employees-home', config('cache.stores.file.expire'), function () {
            return User::get_home_page_employees();
        });

        $data['statistics'] = Cache::remember('statistics', config('cache.stores.file.expire'), function () {
            return Statistic::get_home_page_statistic();
        });

        $seo = Helper::getSeo('home');

        view()->share([
            'title' => $seo['title'],
            'description' => $seo['description'],
            'keywords' => $seo['description'],
        ]);
        return appView('home.index', $data);
    }

    protected function setSeo($searchFormOptions = [])
    {
        $title = getMetaTag('title', 'home');
        $description = getMetaTag('description', 'home');
        $keywords = getMetaTag('keywords', 'home');

        // Meta Tags
        MetaTag::set('title', $title);
        MetaTag::set('description', strip_tags($description));
        MetaTag::set('keywords', $keywords);

//        // Open Graph
//        $this->og->title($title)->description($description);
//        $backgroundImage = '';
//        if (!empty(config('country.background_image'))) {
//            if (isset($this->disk) && $this->disk->exists(config('country.background_image'))) {
//                $backgroundImage = config('country.background_image');
//            }
//        }
//        if (empty($backgroundImage)) {
//            if (isset($searchFormOptions['background_image']) && !empty($searchFormOptions['background_image'])) {
//                $backgroundImage = $searchFormOptions['background_image'];
//            }
//        }
//        if (!empty($backgroundImage)) {
//            if ($this->og->has('image')) {
//                $this->og->forget('image')->forget('image:width')->forget('image:height');
//            }
//            $this->og->image(imgUrl($backgroundImage, 'bgHeader'), [
//                'width' => 600,
//                'height' => 600,
//            ]);
//        }
//        view()->share('og', $this->og);
    }

    public function register_session(Request $request)
    {
        if (!empty($request->get('d'))) {
            session()->put('country_code', $request->get('d'));
            session()->put('register_session', $request->get('d'));
            // return redirect(URL::to('/register?user_type_id=').$request->get('user_type_id').'&country='.$request->get('d'));

            if($request->get('user_type_id') == 5 && !empty($request->get('referral_code'))){
                return redirect(url('affiliate-register?user_type_id=') . $request->get('user_type_id') . '&country=' . $request->get('d'). '&referral_code=' . $request->get('referral_code'));
            }else if (!empty($request->get('referral_code'))) {
                return redirect(url('register?user_type_id=') . $request->get('user_type_id') . '&country=' . $request->get('d'). '&referral_code=' . $request->get('referral_code'));
            }else if($request->get('user_type_id') == 5){
                return redirect(url('affiliate-register?user_type_id=') . $request->get('user_type_id') . '&country=' . $request->get('d'));
            }else{
                return redirect(url('register?user_type_id=') . $request->get('user_type_id') . '&country=' . $request->get('d'));
            }        
        } else {

            //return redirect(URL::to('/register?user_type_id=').$request->get('user_type_id'));
            return redirect(url('register?user_type_id=') . $request->get('user_type_id'));
        }
    }

    public function setcountry(Request $request)
    {
        $countryCode = $request->get('d');
        if (!empty($countryCode)) {
            session()->put('country_code', $countryCode);
        }
        return redirect(url()->previous());
    }

    public function payment_subscription(Request $request)
    {
        $data = Tap::payemntDetails($request->input('tap_id'));
        if (!empty($data)) {
            $package_id = $request->input('package_id');
            $user_id = auth()->user()->id;
            $today = date('Y-m-d');
            $packeg_data = Package::where('id', $package_id)->first();
            $credits = $packeg_data->number_of_cards + auth()->user()->remaining_credits;
            $credit = $packeg_data->number_of_cards + auth()->user()->credits;
            $posts = $packeg_data->number_of_posts + auth()->user()->remaining_posts;
            $post = $packeg_data->number_of_posts + auth()->user()->posts;
            $date = new DateTime("$today");
            $date->modify('+1 month');
            $postExpireDate = $date->format('Y-m-d');
            $date = new DateTime("$today");
            $short_name = $packeg_data->short_name;

            if ($short_name = 'Premium') {
                $date->modify('+1 month');
            } else {
                $date->modify('+1 month');
            }

            $pcDate = $date->format('Y-m-d');
            $UserCreate = array(
                'remaining_credits' => $credits,
                'credits' => $credit,
                'remaining_posts' => $posts,
                'posts' => $post,
                'package_id' => $packeg_data->id,
                'cDate' => $today,
                'post_expire' => $postExpireDate,
                'pDate' => $pcDate,

            );
            User::where('id', $user_id)->update($UserCreate);
            $PaymentCreate = array(
                'user_id' => $user_id,
                'payment_method_id' => 3,
                'active' => 1,
                'transaction_id' => $data->id,
                'package_id' => $package_id,
                'amount' => $packeg_data->price,
                'important' => 1,
            );
            $payment = new PaymentModel($PaymentCreate);
            $payment_data = $payment->save();
            $values['is_active'] = 1;
            Post::where('user_id', auth()->user()->id)->update($values);
            return redirect('account/credentials/tappayment-success');
        } else {
            return redirect('account/credentials/tap-payment-error');
        }
    }

    public function send_unread_message_email()
    {
        $userData = User::all();
        if (!empty($userData)) {
            foreach ($userData as $user) {
                $all_thread_data = Thread::forUser($user->id)->latest('updated_at')->get();
                if ($all_thread_data->isNotEmpty()) {
                    if (!empty($all_thread_data)) {
                        foreach ($all_thread_data as $threadD) {
                            if ($threadD->isUnread()) {
                                $message_sender = ThreadParticipant::where('thread_id', $threadD->id)->where('user_id', '!=', $user->id)->first();
                                $message_receiver = ThreadParticipant::where('thread_id', $threadD->id)->where('user_id', $user->id)->first();
                                $date_last_read = $message_receiver->last_read;
                                $today_date = date("Y-m-d H:i:s");
                                if ($today_date >= $date_last_read || empty($message_receiver->last_read)) {
                                    $sender_user_data = User::where('id', $message_sender->user_id)->first();
                                    $rev_user_data = User::where('id', $message_receiver->user_id)->first();

                                    $data['email'] = $user->email;
                                    $data['subject'] = 'New Direct Message';
                                    $data['header'] = 'New Direct Message';
                                    $data['myName'] = $rev_user_data->name;
                                    $data['from_user_name'] = $sender_user_data->name;
                                    $data['view'] = 'emails/new_message';
                                    $helper = new Helper();
                                    $response = $helper->send_email($data);
                                    // create activity log for
                                    $company_name = $sender_user_data->name;
                                    $profile_url = admin_url() . '/employer?search=' . $sender_user_data->email;
                                    $employee_url = admin_url() . '/job-seekers?search=' . $rev_user_data->email;
                                    $employee_name = $rev_user_data->name;
                                    $description = "A company Name: <a href='$profile_url'>$company_name</a> sent message to Employee Name:  <a href='$employee_url'>$employee_name</a>";
                                    Helper::activity_log($description);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function update_package_cron()
    {
        $companies = User::where('user_type_id', 1)->whereNotNull('parent_id')->whereNull('deleted_at')->get();
        if (!empty($companies)) {
            foreach ($companies as $company) {
                if (!empty($company->cDate)) {
                    $startDate = new DateTime($company->cDate);
                    $endDate = new DateTime(date('Y-m-d'));
                    $difference = $endDate->diff($startDate);
                    if ($difference->format("%a") >= 30) {
                        Helper::update_post_and_contactcard_counter($company->id, true, false);
                    }
                }
            }
        }
    }

    public function update_post_contact_card()
    {
        $companies = User::where('user_type_id', 1)->whereNotNull('parent_id')->whereNull('deleted_at')->get();
        if (!empty($companies)) {
            foreach ($companies as $company) {
                $companyposts = Post::where('user_id', $company->id)->get();
                foreach ($companyposts as $post) {
                    $startDate = new DateTime($post->created_at);
                    $endDate = new DateTime(date('Y-m-d'));
                    $difference = $endDate->diff($startDate);
                    if ($difference->format("%a") > 30) {
                        $values = array(
                            'is_active' => 0,
                        );
                        Post::where('id', $post->id)->update($values);
                    }
                }

                $unlock_data = Unlock::where('to_user_id', $company->id)->get();
                if (!empty($unlock_data)) {
                    foreach ($unlock_data as $unlock) {
                        $startDate = new DateTime($unlock->created_at);
                        $endDate = new DateTime(date('Y-m-d'));
                        $difference = $endDate->diff($startDate);
                        if ($difference->format("%a") > 30) {
                            $values = array(
                                'is_unlock' => 0,
                            );
                            Unlock::where('id', $unlock->id)->update($values);
                        }
                    }
                }
                Helper::update_post_and_contactcard_counter($company->id);
            }
        }
    }

     public function check_subscription_availiblity()
    {
        if(!auth()->check()){
            echo 0;
            die;
        }
        $post_count= CompanyPackages::check_post_available(auth()->user()->id);
        if($post_count > 0 || $post_count == 'unlimited' ){
            echo 2;
            die;
        } else {
            echo 1;
            die;
        }
    }

    protected function getSearchForm($value = [])
    {
        view()->share('searchFormOptions', $value);
    }

    protected function getLocations($value = [])
    {
        // Get the default Max. Items
        $maxItems = 14;
        if (isset($value['max_items'])) {
            $maxItems = (int)$value['max_items'];
        }

        // Modal - States Collection
        $cacheId = config('country.code') . '.home.getLocations.modalAdmins';
        $modalAdmins = Cache::remember($cacheId, config('cache.stores.file.expire'), function () {
            return SubAdmin1::currentCountry()->orderBy('name')->get(['code', 'name'])->keyBy('code');
        });
        view()->share('modalAdmins', $modalAdmins);

        // Get cities
        if (config('settings.listing.count_cities_posts')) {
            $cacheId = config('country.code') . 'home.getLocations.cities.withCountPosts';
            $cities = Cache::remember($cacheId, config('cache.stores.file.expire'), function () use ($maxItems) {
                return City::currentCountry()->withCount('posts')->take($maxItems)->orderByDesc('population')->orderBy('name')->get();
            });
        } else {
            $cacheId = config('country.code') . 'home.getLocations.cities';
            $cities = Cache::remember($cacheId, config('cache.stores.file.expire'), function () use ($maxItems) {
                return City::currentCountry()->take($maxItems)->orderByDesc('population')->orderBy('name')->get();
            });
        }
        $cities = collect($cities)->push(ArrayHelper::toObject([
            'id' => 0,
            'name' => t('More cities') . ' &raquo;',
            'subadmin1_code' => 0,
        ]));

        // Get cities number of columns
        $numberOfCols = 4;
        if (file_exists(config('larapen.core.maps.path') . strtolower(config('country.code')) . '.svg')) {
            if (isset($value['show_map']) && $value['show_map'] == '1') {
                $numberOfCols = (isset($value['items_cols']) && !empty($value['items_cols'])) ? (int)$value['items_cols'] : 3;
            }
        }

        // Chunk
        $maxRowsPerCol = round($cities->count() / $numberOfCols, 0); // PHP_ROUND_HALF_EVEN
        $maxRowsPerCol = ($maxRowsPerCol > 0) ? $maxRowsPerCol : 1;  // Fix array_chunk with 0
        $cities = $cities->chunk($maxRowsPerCol);

        view()->share('cities', $cities);
        view()->share('citiesOptions', $value);
    }

    protected function getSponsoredPosts($value = [])
    {
        $type = 'sponsored';

        // Get the default Max. Items
        $maxItems = 20;
        if (isset($value['max_items'])) {
            $maxItems = (int)$value['max_items'];
        }

        // Get the default orderBy value
        $orderBy = 'id , DESC';
        if (isset($value['order_by'])) {
            $orderBy = $value['order_by'];
        }

        // Get Posts
        $cacheId = config('country.code') . '.home.getPosts.' . $type;
        $posts = Cache::remember($cacheId, config('cache.stores.file.expire'), function () use ($maxItems, $type, $orderBy) {
            return Post::getLatestOrSponsored($maxItems, $type, $orderBy);
        });

        $sponsored = null;
        if (!empty($posts)) {
            $sponsored = [
                'title' => t('Home - Sponsored Jobs'),
                'link' => UrlGen::search(),
                'posts' => $posts,
            ];
            $sponsored = ArrayHelper::toObject($sponsored);
        }

        view()->share('featured', $sponsored);
        view()->share('featuredOptions', $value);
    }

    protected function getLatestPosts($value = [])
    {

        // get posts
        $type = 'latest';
        // Get the default Max. Items
        $maxItems = 10;
        if (isset($value['max_items'])) {
            $maxItems = (int)$value['max_items'];
        }
        // Get the default orderBy value
        $orderBy = 'id,desc';
        if (isset($value['order_by'])) {
            $orderBy = $value['order_by'];
        }
        $posts = Post::getLatestOrSponsored($maxItems, $type, $orderBy);


        $latest = null;
        if (!empty($posts)) {
            $latest = [
                'title' => t('Home - Latest Jobs'),
                'link' => UrlGen::search(),
                'posts' => $posts,
            ];

            $latest = ArrayHelper::toObject($latest);
        }


        view()->share('latest', $latest);
        view()->share('latestOptions', $value);
    }

    protected function getCategories($value = [])
    {
        // Get the default Max. Items
        $maxItems = null;
        if (isset($value['max_items'])) {
            $maxItems = (int)$value['max_items'];
        }

        // Number of columns
        $numberOfCols = 3;

        $cacheId = 'categories.parents.' . config('app.locale') . '.take.' . $maxItems;

        if (isset($value['type_of_display']) && in_array($value['type_of_display'], ['cc_normal_list', 'cc_normal_list_s'])) {

            $categories = Cache::remember($cacheId, config('cache.stores.file.expire'), function () {
                $categories = Category::orderBy('lft')->get();

                return $categories;
            });

            $categories = collect($categories)->keyBy('id');
            $categories = $subCategories = $categories->groupBy('parent_id');

            if ($categories->has(null)) {
                if (!empty($maxItems)) {
                    $categories = $categories->get(null)->take($maxItems);
                } else {
                    $categories = $categories->get(null);
                }
                $subCategories = $subCategories->forget(null);

                $maxRowsPerCol = round($categories->count() / $numberOfCols, 0, PHP_ROUND_HALF_EVEN);
                $maxRowsPerCol = ($maxRowsPerCol > 0) ? $maxRowsPerCol : 1;
                $categories = $categories->chunk($maxRowsPerCol);
            } else {
                $categories = collect();
                $subCategories = collect();
            }

            view()->share('categories', $categories);
            view()->share('subCategories', $subCategories);
        } else {

            $categories = Cache::remember('get_categories_data', config('cache.stores.file.expire'), function () use ($maxItems) {
                if (!empty($maxItems)) {
                    $categories = Category::where(function ($query) {
                        $query->where('parent_id', 0)->orWhereNull('parent_id');
                    })->take($maxItems)->orderBy('lft')->get();
                } else {
                    $categories = Category::where(function ($query) {
                        $query->where('parent_id', 0)->orWhereNull('parent_id');
                    })->orderBy('lft')->get();
                }

                return $categories;
            });


            if (isset($value['type_of_display']) && $value['type_of_display'] == 'c_picture_icon') {
                $categories = collect($categories)->keyBy('id');
            } else {
                // $maxRowsPerCol = round($categories->count() / $numberOfCols, 0); // PHP_ROUND_HALF_EVEN
                $maxRowsPerCol = ceil($categories->count() / $numberOfCols);
                $maxRowsPerCol = ($maxRowsPerCol > 0) ? $maxRowsPerCol : 1; // Fix array_chunk with 0
                $categories = $categories->chunk($maxRowsPerCol);
            }

            view()->share('categories', $categories);
        }

        // Count Posts by category (if the option is enabled)
        $countPostsByCat = collect();
        if (config('settings.listing.count_categories_posts')) {
            $cacheId = config('country.code') . '.count.posts.by.cat.' . config('app.locale');
            $countPostsByCat = Cache::remember($cacheId, config('cache.stores.file.expire'), function () {
                $countPostsByCat = Category::countPostsByCategory();

                return $countPostsByCat;
            });
        }
        view()->share('countPostsByCat', $countPostsByCat);

        // Export the Options
        view()->share('categoriesOptions', $value);
    }

    /**
     * Get mini stats data
     *
     * @param array $value
     */
    protected function getStats($value = [])
    {
        // Count posts
        $countPosts = Post::unarchived()->where('is_active', 1)->where('is_deleted', 0)->where('posts.created_at', '>', date("Y-m-d H:i:s", strtotime("-29 days")))->count();

        // Count cities
        $all_post = Post::unarchived()->get();
        if (!empty($all_post)) {
            foreach ($all_post as $post) {
                $city_id[] = $post->city_id;
            }
        }
        if (!empty($city_id)) {
            $countCities = City::whereIn('id', $city_id)->count();
        } else {
            $countCities = 0;
        }
        // Count users
        $countUsers = User::count();

        // Share vars
        view()->share('countPosts', $countPosts);
        view()->share('countCities', $countCities);
        view()->share('countUsers', $countUsers);

        // Export the Options
        view()->share('statsOptions', $value);
    }

    private function getFeaturedPostsCompanies($value = [])
    {
        $orderBy = 'random';
        if (isset($value['order_by'])) {
            $orderBy = $value['order_by'];
        }

        $featuredCompanies = null;

        $featured_companies = Cache::remember('featured_companies', config('cache.stores.file.expire'), function () {
            return User::get_all_featured_companies_users_with_employer_logo();
        });
        if ($featured_companies->count() > 0) {
            if ($orderBy == 'random') {
                $featured_companies = $featured_companies->shuffle();
            }
            $featuredCompanies = [
                'title' => t('Home - Featured Companies'),
                'link' => UrlGen::company(),
                'companies' => $featured_companies,
            ];
            $featuredCompanies = ArrayHelper::toObject($featuredCompanies);
        }

        view()->share('featuredCompanies', $featuredCompanies);
        view()->share('featuredCompaniesOptions', $value);
    }
    
      public function page_count(Request $request){
        if ($request->ajax()) {
           $result=Helper::page_count_ajax($request);
           echo 1;
           die();
        }
    }

    public function store_affiliate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'password' => 'required|min:6|confirmed',
            'g-recaptcha-response' => 'required',
            'accept_terms' => ['accepted'],
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed!',
                'errors' => $validator->errors()->all(),
                'redirect' => false,
            ]);
        }

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => G_RECAPTCHA_PRIVATE_KEY,
            'response' => $request->input('g-recaptcha-response'),
            'remoteip' => $request->ip(),
         ]);

        if (!($response->json()['success'] ?? false)) {
            return response()->json(['status' => false, 'message' => trans('reCAPTCHA validation failed. Please try again.'),'redirect' => false]);        
        }

        if(!empty($request->b_value)){
            return response()->json(['status' => false, 'message' => trans('Your form could not be submitted successfully. Please try again.'),'redirect' => true]);        
        }

        $response = User::store_affiliate_request($request);
        return response()->json(['status' => $response['status'], 'message' => $response['message'], 'redirect' => false,]);
    }

    public function city_dependency_global(Request $request)
    {
        $country_name = $request->input('country_name');
        $city = City::where('country_code', $country_name)->orderBy('name', 'ASC')->get();
        return response()->json($city);
    }

    public function static(){
        $currentURL = request()->path();
        if($currentURL === 'industries/hospitality-leisure/hotels-resorts'){
            return appView('static.index');
        }else if($currentURL === 'industries/hospitality-leisure/restaurants-cafes-bistros'){
            return appView('static.restaurants-cafes-bistros');
        }else if($currentURL === 'industries/hospitality-leisure/offee-tea-cafeterias'){
            return appView('static.offee-tea-cafeterias');
        }else if($currentURL === 'industries/hospitality-leisure/gyms-fitness-centers'){
            return appView('static.gyms-fitness-centers');
        }else if($currentURL === 'industries/hospitality-leisure/salon-and-spa'){
            return appView('static.salon-and-spa');
        }else if($currentURL === 'industries/food-and-beverage/bakeries'){
            return appView('static.bakeries');
        }else if($currentURL === 'industries/food-and-beverage/delis'){
            return appView('static.delis');
        }else if($currentURL === 'industries/hospitality-leisure/lounges-beverage-service'){
            return appView('static.lounges-beverage-service');
        }else if($currentURL === 'industries/food-and-beverage/catering-companies'){
            return appView('static.catering-companies');
        }else if($currentURL === 'industries/food-and-beverage/dessert-shops'){
            return appView('static.dessert-shops');
        }else if($currentURL === 'industries/food-and-beverage/food-and-beverage-management'){
            return appView('static.food-and-beverage-management');
        }else if($currentURL === 'industries/food-and-beverage/food-distribution-wholesale'){
            return appView('static.food-distribution-wholesale');
        }else if($currentURL === 'industries/food-and-beverage/food-trucks-fast-food'){
            return appView('static.food-trucks-fast-food');
        }else if($currentURL === 'industries/events-entertainment/entertainment-companies'){
            return appView('static.entertainment-companies');
        }else if($currentURL === 'industries/industries/retail-consumer/fashion-retail'){
            return appView('static.fashion-retail');
        }else if($currentURL === 'industries/industries/retail-consumer/flower-shops'){
            return appView('static.flower-shops');
        }else if($currentURL === 'industries/industries/retail-consumer/shopping-malls'){
            return appView('static.shopping-malls');
        }else if($currentURL === 'industries/industries/retail-consumer/super-markets'){
            return appView('static.super-markets');
        }
    }
}