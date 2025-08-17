<?php

namespace App\Models;

use App\Helpers\Date;
use App\Helpers\Files\Storage\StorageDisk;
use App\Helpers\RemoveFromString;
use App\Helpers\UrlGen;
use App\Models\Post\LatestOrPremium;
use App\Models\Post\SimilarByCategory;
use App\Models\Post\SimilarByLocation;
use App\Models\Scopes\LocalizedScope;
use App\Models\Scopes\ReviewedScope;
use App\Models\Scopes\VerifiedScope;
use App\Models\Traits\CountryTrait;
use App\Observers\PostObserver;
use Creativeorange\Gravatar\Facades\Gravatar;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Larapen\Admin\app\Models\Traits\Crud;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;
use stdClass;

class Post extends BaseModel implements Feedable
{
    use Crud, CountryTrait, Notifiable, HasFactory, LatestOrPremium, SimilarByCategory, SimilarByLocation;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = true;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'posts';
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    protected $appends = ['slug', 'created_at_formatted', 'user_photo_url'];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    // protected $guarded = ['id'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'country_code',
        'user_id',
        'company_id',
        'company_name',
        'logo',
        'company_description',
        'category_id',
        'post_type_id',
        'title',
        'description',
        'salary_min',
        'salary_max',
        'salary_type_id',
        'negotiable',
        'start_date',
        'contact_name',
        'email',
        'phone',
        'deleted_at',
        'is_deleted',
        'is_active',
        'archived',
        'is_approved',
        'archived_manually',
        'archived_at',
        'as_soon',
        'is_post_expire',
        'updated_at',
        'created_at',
    ];
    /**
     * The attributes that should be hidden for arrays
     *
     * @var array
     */
    // protected $hidden = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public static function save_post_request($request, $country_code)
    {

        $company = Company::where('id', $request->company_id)->first();
        $employeeSkill = EmployeeSkill::find($request->input('category_id'));

        if (empty($request->input('post_id'))) {
            $post = new Post();
        } else {
            $post = Post::find($request->input('post_id'));
        }
        if ($employeeSkill->status == 0) {
            $post->is_approved = 0;
        }
        $post->country_code = $country_code;
        $post->user_id = !empty($company->c_id) ? $company->c_id : auth()->user()->id;
        $post->company_id = !empty($company->id) ? $company->id : $request->company_id;
        $post->company_name = !empty($company->name) ? $company->name : $request->input('contact_name');
        $post->logo = !empty($company->logo) ? $company->logo : '';
        $post->company_description = !empty($company->description) ? $company->description : '';

        $post->category_id = $request->input('category_id');
        $post->post_type_id = $request->input('post_type_id');
        $post->title = $request->input('title');
        $post->description = $request->input('description');
        $post->salary_min = $request->input('salary_min');
        $post->salary_max = $request->input('salary_max');
        $post->salary_type_id = $request->input('salary_type_id');
        $post->negotiable = $request->input('negotiable');
        $post->start_date = !empty($request->input('start_date')) ? $request->input('start_date') : '';
        $post->as_soon = !empty($request->input('as_soon')) ? $request->input('as_soon') : 0;
        $post->contact_name = $request->input('contact_name');
        $post->email = $request->input('email');
        $post->phone = $request->input('phone');
//        $post->archived = 0;
//        $post->is_post_expire = 0;
        $post->is_active = 1;

        if ($post->save()) {
            return $post;
        } else {
            return false;
        }

    }

    public static function getFeedItems()
    {
        $postsPerPage = (int)config('settings.listing.items_per_page', 50);

        $posts = Post::reviewed()->unarchived();

        if (request()->has('d') || config('plugins.domainmapping.installed')) {
            $countryCode = config('country.code');
            if (!config('plugins.domainmapping.installed')) {
                if (request()->has('d')) {
                    $countryCode = request()->input('d');
                }
            }
            $posts = $posts->where('country_code', $countryCode);
        }

        $posts = $posts->take($postsPerPage)->orderByDesc('id')->get();

        return $posts;
    }

    public static function getpostapplicants($id)
    {
        if (!empty($id)) {
            // $applicants_count = Applicant::where('to_user_id',auth()->user()->id)->where(['post_id' => $id])->count();
            $applicants_count = Applicant::where('to_user_id', auth()->user()->id)->where(['post_id' => $id])->where('status', '!=', 'pending')->where('is_deleted', 0)->count();
        } else {
            $applicants_count = 0;
        }
        return $applicants_count;
    }

    public static function getLogo($value)
    {
        $disk = StorageDisk::getDisk();

        // OLD PATH
        $value = str_replace('uploads/pictures/', '', $value);
        $value = str_replace('pictures/', '', $value);
        $value = 'pictures/' . $value;
        if ($disk->exists($value) && substr($value, -1) != '/') {
            return $value;
        }

        // NEW PATH
        $value = str_replace('pictures/', '', $value);
        if (!$disk->exists($value) && substr($value, -1) != '/') {
            $value = config('larapen.core.picture.default');
        }

        return $value;
    }

    public static function get_posts($request)
    {

        $limit = (!empty($request->get('limit')) ? $request->get('limit') : 30);
        $limit = (!empty($request->get('length')) ? $request->get('length') : $limit);


        $type = (!empty($request->get('type')) ? $request->get('type') : '');
        $posts = Post::with(['postDetail', 'postDetail.city', 'postMeta', 'user', 'latestPayment', 'applicant', 'company']);
        if ($type == 'current_posts') {
            $posts = $posts->where('is_active', 1)->where('is_deleted', 0)->where('is_post_expire', 0);
        }
        if (!empty($request->get('status'))) {
            $status = '';
            if ($request->get('status') == 'active') {
                $status = '1';
            } elseif ($request->get('status') == 'inactive') {
                $status = '0';
            }
            $posts = $posts->where('posts.is_active', $status);
        }

        if (!empty($request->get('search'))) {
            $posts = $posts->where(function ($query) use ($request) {
                $search = $request->get('search');
                $query->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('company_name', 'LIKE', "%{$search}%");
            });
        }
        if (!empty($request->get('employyeskill'))) {
            $posts = $posts->where('category_id', $request->get('employyeskill'));
        }

        if (!empty($request->get('pending_post'))) {
            $posts = $posts->where('is_approved', 0);
        }
        $posts = $posts->orderBy('created_at', 'DESC');

        if (!empty($request->get('start'))) {
            return $posts->skip($request->get('start'))->take($limit)->get();
        }

        $posts = $posts->paginate($limit)->appends(request()->query());
        return $posts;
    }

    public static function get_posts_count($request)
    {

        $type = (!empty($request->get('type')) ? $request->get('type') : '');
        $posts = Post::with(['postDetail', 'postDetail.city', 'postMeta', 'user', 'latestPayment', 'applicant', 'company']);
        if ($type == 'current_posts') {
            $posts = $posts->where('is_active', 1)->where('is_deleted', 0)->where('is_post_expire', 0);
        }
        if (!empty($request->get('status'))) {
            $status = '';
            if ($request->get('status') == 'active') {
                $status = '1';
            } elseif ($request->get('status') == 'inactive') {
                $status = '0';
            }
            $posts = $posts->where('posts.is_active', $status);
        }

        if (!empty($request->get('search'))) {
            $posts = $posts->where(function ($query) use ($request) {
                $search = $request->get('search');
                $query->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('company_name', 'LIKE', "%{$search}%");
            });
        }
        if (!empty($request->get('employyeskill'))) {
            $posts = $posts->where('category_id', $request->get('employyeskill'));
        }

        if (!empty($request->get('pending_post'))) {
            $posts = $posts->where('is_approved', 0);
        }
        $posts = $posts->count();

        return $posts;
    }

    public static function get_post_count_by_skill_set($param)
    {
        // $post=Post::where('is_deleted', 0)->where('is_active', 1)->where('archived', 0)->where('is_post_expire', 0);
        $post = Post::where('category_id', $param)->get()->count();
        return $post;
    }

    public static function get_active_post_count_by_skill_set($param)
    {
        $post = Post::where('is_deleted', 0)->where('is_active', 1)->where('archived', 0)->where('is_post_expire', 0);
        $post = $post->where('category_id', $param)->get()->count();
        return $post;
    }

    public static function get_post_country_count($code)
    {
        $count = Post::where('country_code', $code)->where('is_deleted', 0)->where('is_active', 1)->where('archived', 0)->where('is_post_expire', 0)->get()->count();
        return $count;
    }


    public static function get_post_by_user_id($id)
    {

        $data = POST::select("posts.*", "employee_skills.skill as post_main_skill")
            ->join("employee_skills", "posts.category_id", "=", "employee_skills.id")
            ->where('posts.user_id', $id)
            ->where('posts.archived', 0)
            ->where('posts.is_deleted', 0)
            ->where('posts.is_post_expire', 0)
            ->get();

        return $data;
    }

    public static function get_all_latest_post()
    {
        $data = Post::select('id', 'title')->where('is_active', 1)->where('is_deleted', 0)->where('is_post_expire', 0)->where('archived', 0)->orderByDesc('id')->get();
        return $data;
    }

    public static function get_active_post_id()
    {
        return Post::where('user_id', auth()->user()->id)
            ->where(function ($query) {
                $query->where('archived', 0)
                    ->where('is_post_expire', 0);
            })
            ->pluck('id')
            ->toArray();
    }

    public static function get_all_archive_post_ids()
    {
        return Post::where(function ($query) {
            $query->where('is_post_expire', 1)
                ->orWhere('archived', 1);
        })
            ->where('user_id', auth()->user()->id)
            ->pluck('id');
    }

    public static function get_all_archive_post_count()
    {
        return Post::where(function ($query) {
            $query->where('is_post_expire', 1)
                ->orWhere('archived', 1);
        })->where('user_id', auth()->user()->id)->where('is_deleted', 0)
            ->count();
    }

    public static function get_all_post_post_count_by_employer_id()
    {
        $count = Post::where('user_id', auth()->user()->id)->where('archived', 0)->where('is_deleted', 0)->where('is_active', 1)->where('is_post_expire', 0)
            ->count();
        return $count;
    }

    /*
    |--------------------------------------------------------------------------
    | QUERIES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public static function get_post_applicant_count()
    {
        Post::where(function ($query) {
            $query->where('is_post_expire', 1)->orWhere('archived', 1);
        })
            ->where('user_id', auth()->user()->id)
            ->get();
        $postid = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $postid[] = $post->id;
            }
        }
        if (!empty($postid)) {
            $totalapplicatns = Applicant::whereIn('post_id', $postid)->count();

        } else {
            $totalapplicatns = 0;
        }
        return $totalapplicatns;
    }

    public static function apply_filter_on_post()
    {
        $post = Post::with(['postDetail', 'postDetail.city', 'postMeta']);
        $post = $post->where('is_deleted', 0);
        $post = $post->where('archived', 0);
        $post = $post->where('is_post_expire', 0);
        $post = $post->where('is_approved', 1);
        $post = $post->where('is_active', 1);

        if (!empty(request()->get('post'))) {
            $post = $post->where('id', request()->get('post'));
        }


        if (!empty(request()->get('l'))) {

            $post = $post->whereHas('postDetail', function ($query) {
                $query->where('city_id', request()->get('l'));
            });
        }
        if (!empty(request()->get('q'))) {
            $post = $post->where('category_id', request()->get('q'));
        }
        $post->when(request()->filled('min_salary'), function ($query) {
            return $query->where('salary_min', '>=', request()->input('min_salary'));
        });

        $post->when(request()->filled('max_salary'), function ($query) {
            return $query->where('salary_max', '<=', request()->input('max_salary'));
        });

        if (!empty(request()->get('type')[0])) {
            $post = $post->whereIn('post_type_id', request()->get('type'));
        }
        if (!empty(request()->get('country_code'))) {
            $countryCode = Session::get('country_code');
            $post = $post->where('country_code', request()->get('country_code'));
            $post = $post->orderByRaw("CASE WHEN country_code = ? THEN 0 ELSE 1 END", [$countryCode])
                ->orderByDesc('created_at');
        } else {
            $countryCode = Session::get('country_code');
            $post = $post->orderByRaw("CASE WHEN country_code = ? THEN 0 ELSE 1 END", [$countryCode])
                ->orderByDesc('created_at');
        }
        return $post->paginate('10')->appends(request()->query());

    }


    public static function count_all_post_with_filter()
    {
        $post = Post::with(['postDetail', 'postDetail.city', 'postMeta']);
        $post = $post->where('is_deleted', 0);
        $post = $post->where('archived', 0);
        $post = $post->where('is_post_expire', 0);
        $post = $post->where('is_approved', 1);

        if (!empty(request()->get('post'))) {
            $post = $post->where('id', request()->get('post'));
        }


        if (!empty(request()->get('l'))) {
            $post = $post->whereHas('postDetail', function ($query) {
                $query->where('city_id', request()->get('l'));
            });
        }
        if (!empty(request()->get('q'))) {
            $post = $post->where('category_id', request()->get('q'));
        }
        $post->when(request()->filled('min_salary'), function ($query) {
            return $query->where('salary_min', '>=', request()->input('min_salary'));
        });

        $post->when(request()->filled('max_salary'), function ($query) {
            return $query->where('salary_max', '<=', request()->input('max_salary'));
        });

        if (!empty(request()->get('type')[0])) {
            $post = $post->whereIn('post_type_id', request()->get('type'));
        }
        if (!empty(request()->get('country_code'))) {
            $countryCode = Session::get('country_code');
            $post = $post->where('country_code', request()->get('country_code'));
            $post = $post->orderByRaw("CASE WHEN country_code = ? THEN 0 ELSE 1 END", [$countryCode])
                ->orderByDesc('created_at');
        } else {
            $countryCode = Session::get('country_code');
            $post = $post->orderByRaw("CASE WHEN country_code = ? THEN 0 ELSE 1 END", [$countryCode])
                ->orderByDesc('created_at');
        }
        return $post->get();

    }


    public static function get_formated_post($post)
    {
        $std = new stdClass();
        $std->id = $post->id;
        $std->country_code = $post->country_code;
        $std->country_name = $post->country->name;
        $std->category_id = $post->category_id;
        $std->skill = $post->employeeskill->skill;
        $std->post_type_id = $post->post_type_id;
        $std->post_type_name = $post->postType->name;
        $std->company_id = $post->company_id;
        $std->company_name = $post->company_name;
        $std->company_logo = $post->logo;
        $std->title = $post->title;
        $std->description = $post->description;
        $std->salary_type_id = $post->salary_type_id;
        $std->salary_min = $post->salary_min;
        $std->salary_max = $post->salary_max;
        $std->user_id = $post->user_id;
        $std->user_name = $post->user->name;
        $std->slug = $post->slug;
        $std->city_id = $post->postDetail->city_id;
        $std->longitude = $post->postDetail->longitude;
        $std->latitude = $post->postDetail->latitude;
        $std->city_name = $post->postDetail->city->name;
        $std->city_name = $post->postDetail->city->name;
        $std->transportation_available = $post->postDetail->transportation_available;
        $std->overtime_pay = $post->postDetail->overtime_pay;
        $std->housing_available = $post->postDetail->housing_available;
        $std->gender = $post->postDetail->gender;
        $std->experiences = $post->postDetail->experiences;
        $std->nationality_id = $post->postDetail->nationality;
        // get nationality list
        $nationalities = explode(',', $post->postDetail->nationality);
        $nationalities_name_list = Nationality::whereIn('id', $nationalities)->pluck('name')->toArray();
        $nationalities_string = implode(',', $nationalities_name_list);
        $std->nationality_name = $nationalities_string;
        $std->post_skill_type = $post->postDetail->post_type;
        $std->skills_set = $post->postDetail->skills_set;
        $std->hide_company_logo = $post->postDetail->hide_company_logo;
        $std->who_can_apply = $post->postDetail->who_can_apply;
        $std->visits = $post->postMeta->visits;
        $std->hide_salary = $post->postMeta->hide_salary;
        $std->reviewed = $post->postMeta->reviewed;
        $std->featured = $post->postMeta->featured;

        $std->created_at = $post->created_at;
        return $std;
    }

    public static function top_skill_post_with_count()
    {
        return Post::select('employee_skills.skill', 'posts.category_id', DB::raw('COUNT(*) as totalskills'))
            ->join('employee_skills', 'employee_skills.id', '=', 'posts.category_id')
            ->groupBy('category_id')
            ->orderByDesc('totalskills')
            ->limit(8)
            ->get();
    }

    public static function get_all_skill_post_with_count()
    {
        return Post::select('employee_skills.skill', 'posts.category_id', DB::raw('COUNT(*) as totalskills'))
            ->join('employee_skills', 'employee_skills.id', '=', 'posts.category_id')
            ->groupBy('category_id')
            ->orderByDesc('totalskills')
            ->paginate(50);
    }


    public static function latestPosts()
    {
        return Post::take(config('settings.app.latest_entries_limit', 5))->orderBy('created_at', 'DESC')->get();
    }

    protected static function boot()
    {
        parent::boot();

        Post::observe(PostObserver::class);

        static::addGlobalScope(new VerifiedScope());
        static::addGlobalScope(new ReviewedScope());
        static::addGlobalScope(new LocalizedScope());
    }

    public function routeNotificationForMail()
    {
        return $this->email;
    }

    public function routeNotificationForNexmo()
    {
        $phone = phoneFormatInt($this->phone, $this->country_code);
        $phone = setPhoneSign($phone, 'nexmo');

        return $phone;
    }

    public function routeNotificationForTwilio()
    {
        $phone = phoneFormatInt($this->phone, $this->country_code);
        $phone = setPhoneSign($phone, 'twilio');

        return $phone;
    }

    public function toFeedItem()
    {
        $title = $this->title;
        $title .= (isset($this->city) && !empty($this->city)) ? ' - ' . $this->city->name : '';
        $title .= (isset($this->country) && !empty($this->country)) ? ', ' . $this->country->name : '';
        // $summary = str_limit(str_strip(strip_tags($this->description)), 5000);
        $summary = transformDescription($this->description);
        $link = UrlGen::postUri($this, true);

        return FeedItem::create()
            ->id($link)
            ->title($title)
            ->summary($summary)
            ->category((!empty($this->category)) ? $this->category->name : '')
            ->updated($this->updated_at)
            ->link($link)
            ->author($this->contact_name);
    }

    public function getTitleHtml()
    {
        $out = '';

        // $post = self::find($this->id);

        $out .= getPostUrl($this);

        if (isset($this->archived) && $this->archived == 1) {
            $out .= '<br>';
            $out .= '<span class="badge badge-secondary">';
            $out .= trans('admin.Archived');
            $out .= '</span>';
        }

        return $out;
    }

    public function getCompanyNameHtml()
    {
        $out = '';

        // Company Name
        $out .= $this->company_name;

        // User Name
        $out .= '<br>';
        $out .= '<small>';
        $out .= trans('admin.By_') . ' ';
        if (isset($this->user) and !empty($this->user)) {
            $url = admin_url('users/' . $this->user->getKey() . '/edit');
            $tooltip = ' data-toggle="tooltip" title="' . $this->user->name . '"';

            $out .= '<a href="' . $url . '"' . $tooltip . '>';
            $out .= $this->contact_name;
            $out .= '</a>';
        } else {
            $out .= $this->contact_name;
        }
        $out .= '</small>';

        return $out;
    }

    public function getCityHtml()
    {

        $out = $this->getCountryHtml();
        $out .= ' - ';
        if (isset($this->city) and !empty($this->city)) {
            $out .= '<a href="' . UrlGen::city($this->city) . '" target="_blank">' . $this->city->name . '</a>';
        } else {
            $out .= UrlGen::city($this->city);
        }
        return $out;
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function getapplicantsHtml()
    {
        if (!empty($this->id)) {
            $applicants_count = Applicant::where(['post_id' => $this->id])->count();
        } else {
            $applicants_count = 0;
        }
        return $applicants_count;
    }

    public function getReviewedHtml()
    {
        return ajaxCheckboxDisplay($this->{$this->primaryKey}, $this->getTable(), 'reviewed', $this->reviewed);
    }

    public function getFeaturedHtml()
    {
        $out = '-';
        if (config('plugins.offlinepayment.installed')) {
            $opTool = '\extras\plugins\offlinepayment\app\Helpers\OpTools';
            if (class_exists($opTool)) {
                $out = $opTool::featuredCheckboxDisplay($this->{$this->primaryKey}, $this->getTable(), 'featured', $this->featured);
            }
        }

        return $out;
    }

    public function postType()
    {
        return $this->belongsTo(PostType::class, 'post_type_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_code', 'code');
    }

    public function postDetail()
    {
        return $this->hasOne(PostDetails::class, 'post_id');
    }

    public function RemainingPost()
    {
        return $this->hasOne(PostRemaining::class, 'post_id');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function postMeta()
    {
        return $this->hasOne(PostMeta::class, 'post_id');
    }

    public function employeeskill()
    {
        return $this->belongsTo(EmployeeSkill::class, 'category_id');
    }

    public function latestPayment()
    {
        return $this->hasOne(Payment::class, 'post_id')->orderByDesc('id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'post_id');
    }

    public function pictures()
    {
        return $this->hasMany(Picture::class, 'post_id')->orderBy('position')->orderByDesc('id');
    }

    public function savedByLoggedUser()
    {
        $userId = (auth()->check()) ? auth()->user()->id : '-1';

        return $this->hasMany(SavedPost::class, 'post_id')->where('user_id', $userId);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function salaryType()
    {
        return $this->belongsTo(SalaryType::class, 'salary_type_id');
    }

    public function scopeVerified($builder)
    {

        if (config('settings.single.posts_review_activation')) {
            $builder->where('reviewed', 1);
        }

        return $builder;
    }

    public function scopeUnverified($builder)
    {

        if (config('settings.single.posts_review_activation')) {
            $builder->orWhere('reviewed', 0);
        }

        return $builder;
    }

    public function scopeArchived($builder)
    {
        return $builder->where('archived', 1);
    }

    public function scopeUnarchived($builder)
    {
        return $builder->where('archived', 0);
    }

    public function scopeReviewed($builder)
    {
        if (config('settings.single.posts_review_activation')) {
            return $builder->where('reviewed', 1);
        } else {
            return $builder;
        }
    }

    public function scopeUnreviewed($builder)
    {
        if (config('settings.single.posts_review_activation')) {
            return $builder->where('reviewed', 0);
        } else {
            return $builder;
        }
    }

    public function scopeWithCountryFix($builder)
    {
        // Check the Domain Mapping plugin
        if (config('plugins.domainmapping.installed')) {
            return $builder->where('country_code', config('country.code'));
        } else {
            return $builder;
        }
    }

    // public function getLogoAttribute()
    // {
    //     // OLD PATH
    //     $value = $this->getLogoFromOldPath();
    //     if (!empty($value)) {
    //         return $value;
    //     }

    //     // NEW PATH
    //     if (!isset($this->attributes) || !isset($this->attributes['logo'])) {
    //         $value = config('larapen.core.picture.default');

    //         return $value;
    //     }

    //     $value = $this->attributes['logo'];

    //     $disk = StorageDisk::getDisk();

    //     if (!$disk->exists($value)) {
    //         $value = config('larapen.core.picture.default');
    //     }

    //     return $value;
    // }

    public function getCreatedAtAttribute($value)
    {
        $value = new Carbon($value);
        $value->timezone(Date::getAppTimeZone());

        return $value;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    public function getUpdatedAtAttribute($value)
    {
        $value = new Carbon($value);
        $value->timezone(Date::getAppTimeZone());

        return $value;
    }

    public function getDeletedAtAttribute($value)
    {
        $value = new Carbon($value);
        $value->timezone(Date::getAppTimeZone());

        return $value;
    }

    public function getCreatedAtFormattedAttribute($value)
    {
        $value = new Carbon($this->attributes['created_at']);
        $value->timezone(Date::getAppTimeZone());

        $value = Date::formatFormNow($value);

        return $value;
    }

    public function getArchivedAtAttribute($value)
    {
        $value = (is_null($value)) ? $this->updated_at : $value;

        $value = new Carbon($value);
        $value->timezone(Date::getAppTimeZone());

        return $value;
    }

    public function getDeletionMailSentAtAttribute($value)
    {
        $value = (is_null($value)) ? $this->updated_at : $value;

        $value = new Carbon($value);
        $value->timezone(Date::getAppTimeZone());

        return $value;
    }

    public function getUserPhotoUrlAttribute($value)
    {
        // Default Photo
        $defaultPhotoUrl = url('images/user.jpg');

        // Photo from Gravatar
        $gravatarUrl = null;
        try {
            $gravatarUrl = (!empty($this->email)) ? Gravatar::fallback($defaultPhotoUrl)->get($this->email) : null;
        } catch (Exception $e) {
        }
        if (
            Str::contains(urldecode($gravatarUrl), $defaultPhotoUrl)
            || Str::contains($gravatarUrl, $defaultPhotoUrl)
        ) {
            $gravatarUrl = $defaultPhotoUrl;
        }
        $gravatarUrl = empty($gravatarUrl) ? $defaultPhotoUrl : $gravatarUrl;

        $value = $gravatarUrl;

        return $value;
    }

    public function getEmailAttribute($value)
    {
        if (isFromAdminPanel() || (!isFromAdminPanel() && in_array(request()->method(), ['GET']))) {
            if (
                isDemo() &&
                request()->segment(2) != 'password'
            ) {
                if (auth()->check()) {
                    if (auth()->user()->id != 1) {
                        if (isset($this->phone_token)) {
                            if ($this->phone_token == 'demoFaker') {
                                return $value;
                            }
                        }
                        $value = hidePartOfEmail($value);
                    }
                }
            }
        }

        return $value;
    }

    public function getPhoneAttribute($value)
    {
        $countryCode = config('country.code');
        if (isset($this->country_code) && !empty($this->country_code)) {
            $countryCode = $this->country_code;
        }

        $value = phoneFormatInt($value, $countryCode);

        return $value;
    }

    public function getTitleAttribute($value)
    {
        $value = mb_ucfirst($value);

        if (!isFromAdminPanel()) {
            if (!empty($this->user)) {
                // dd(Permission::getStaffPermissions());
                // if (!$this->user->hasAllPermissions(Permission::getStaffPermissions())) {
                //     $value = RemoveFromString::contactInfo($value, false, true);
                // }
            } else {
                $value = RemoveFromString::contactInfo($value, false, true);
            }
        }

        return $value;
    }

    public function getSlugAttribute($value)
    {
        $value = (is_null($value) && isset($this->title)) ? $this->title : $value;

        $value = stripNonUtf($value);
        $value = slugify($value);

        return $value;
    }

    public function getContactNameAttribute($value)
    {
        $value = mb_ucwords($value);

        return $value;
    }

    public function getCompanyNameAttribute($value)
    {
        $value = mb_ucwords($value);

        return $value;
    }

    public function getCompanyDescriptionAttribute($value)
    {
        if (!isFromAdminPanel()) {
            if (!empty($this->user)) {
                // if (!$this->user->hasAllPermissions(Permission::getStaffPermissions())) {
                //     $value = RemoveFromString::contactInfo($value, false, true);
                // }
            } else {
                $value = RemoveFromString::contactInfo($value, false, true);
            }
        }

        return $value;
    }

    public function getDescriptionAttribute($value)
    {
        if (!isFromAdminPanel()) {
            if (!empty($this->user)) {
                // if (!$this->user->hasAllPermissions(Permission::getStaffPermissions())) {
                //     $value = RemoveFromString::contactInfo($value, false, true);
                // }
            } else {
                $value = RemoveFromString::contactInfo($value, false, true);
            }
        }

        return $value;
    }


    public function setTagsAttribute($value)
    {
        $this->attributes['tags'] = (!empty($value)) ? mb_strtolower($value) : $value;
    }

    public function setApplicationUrlAttribute($value)
    {
        $this->attributes['application_url'] = (!empty($value)) ? strtolower($value) : $value;
    }

    public function applicant()
    {
        return $this->hasMany(Applicant::class, 'post_id');
    }

    public static  function get_posts_by_employer_id($get_Archived=false){
        $post = Post::with(['postDetail', 'postDetail.city', 'postMeta']);
        $post = $post->where('is_deleted', 0);
        if(empty($get_Archived)) {
            $post = $post->where('archived', 0);
            $post = $post->where('is_post_expire', 0);
        }
        $post = $post->where('is_approved', 1);
        $post = $post->where('user_id', auth()->user()->id);
        return $post->get();
    }
    public static function get_active_post()
    {
        return Post::with(['company'])->where('is_post_expire', 0)->where('is_approved', 1)->where('archived', 0)->where('is_deleted', 0)->get();
    }

    public static function get_user_posts($id)
    {

        $data = POST::with(['postDetail', 'postDetail.city', 'postMeta', 'employeeskill', 'user', 'company'])
            ->where('posts.user_id', $id)
            ->where('posts.archived', 0)
            ->where('posts.is_deleted', 0)
            ->where('posts.is_post_expire', 0)
            ->get();

        return $data;
    }

    public static function get_post_details_by_id($id)
    {

        $data = POST::with(['postDetail', 'postDetail.city', 'postMeta', 'employeeskill', 'user', 'company'])
            ->where('posts.id', $id)
            ->where('posts.archived', 0)
            ->where('posts.is_deleted', 0)
            ->where('posts.is_post_expire', 0)
            ->first();

        return $data;
    }

    public static function formet_post_data($data)
    {

        $std = new stdClass();
        $std->post_id = $data->id;
        $std->title = $data->title;
        return $std;

    }

    public static function apply_filter_on_post_api()
    {
        $post = Post::with(['postDetail', 'postDetail.city', 'postMeta', 'company']);
        $post = $post->where('is_deleted', 0);
        $post = $post->where('archived', 0);
        $post = $post->where('is_post_expire', 0);
        $post = $post->where('is_approved', 1);

        if (!empty(request()->get('post_id'))) {
            $post = $post->where('id', request()->get('post_id'));
        }


        if (!empty(request()->get('city_id'))) {
            // Ensure that the relationship is filtered based on city_id
            $post = $post->whereHas('postDetail', function ($query) {
                $query->where('city_id', request()->get('city_id'));
            });
        }

        if (!empty(request()->get('skill_id'))) {
            $post = $post->where('category_id', request()->get('skill_id'));
        }


        $post->when(request()->filled('min_salary'), function ($query) {
            return $query->where('salary_min', '>=', request()->input('min_salary'));
        });

        $post->when(request()->filled('max_salary'), function ($query) {
            return $query->where('salary_max', '<=', request()->input('max_salary'));
        });


        if (!empty(request()->get('post_type'))) {
            $post = $post->whereIn('post_type_id', explode(',', request()->get('post_type')));
        }
        if (!empty(request()->get('country_code'))) {
            $countryCode = Session::get('country_code');
            $post = $post->where('country_code', request()->get('country_code'));
            $post = $post->orderByRaw("CASE WHEN country_code = ? THEN 0 ELSE 1 END", [$countryCode])
                ->orderByDesc('created_at');
        } else {
            $countryCode = Session::get('country_code');
            $post = $post->orderByRaw("CASE WHEN country_code = ? THEN 0 ELSE 1 END", [$countryCode])
                ->orderByDesc('created_at');
        }
        return $post->paginate('10')->appends(request()->query());

    }


    public static function count_all_post_with_filter_api()
    {
        $post = Post::with(['postDetail', 'postDetail.city', 'postMeta']);
        $post = $post->where('is_deleted', 0);
        $post = $post->where('archived', 0);
        $post = $post->where('is_post_expire', 0);
        $post = $post->where('is_approved', 1);

        if (!empty(request()->get('post_id'))) {
            $post = $post->where('id', request()->get('post_id'));
        }


        if (!empty(request()->get('city_id'))) {
            // Ensure that the relationship is filtered based on city_id
            $post = $post->whereHas('postDetail', function ($query) {
                $query->where('city_id', request()->get('city_id'));
            });
        }
        if (!empty(request()->get('skill_id'))) {
            $post = $post->where('category_id', request()->get('skill_id'));
        }
        $post->when(request()->filled('min_salary'), function ($query) {
            return $query->where('salary_min', '>=', request()->input('min_salary'));
        });

        $post->when(request()->filled('max_salary'), function ($query) {
            return $query->where('salary_max', '<=', request()->input('max_salary'));
        });

        if (!empty(request()->get('post_type'))) {
            $post = $post->whereIn('post_type_id', explode(',', request()->get('post_type')));
        }
        if (!empty(request()->get('country_code'))) {
            $countryCode = Session::get('country_code');
            $post = $post->where('country_code', request()->get('country_code'));
            $post = $post->orderByRaw("CASE WHEN country_code = ? THEN 0 ELSE 1 END", [$countryCode])
                ->orderByDesc('created_at');
        } else {
            $countryCode = Session::get('country_code');
            $post = $post->orderByRaw("CASE WHEN country_code = ? THEN 0 ELSE 1 END", [$countryCode])
                ->orderByDesc('created_at');
        }
        return $post->get();

    }


    public static function update_posts_email($company_id, $email)
    {
        Post::where('company_id', $company_id)->update(['email' => $email]);
    }


}