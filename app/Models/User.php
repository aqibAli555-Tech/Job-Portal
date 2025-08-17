<?php

namespace App\Models;

use App\Helpers\Date;
use App\Helpers\EmailCheck;
use App\Helpers\EmailHelper;
use App\Helpers\Files\Storage\StorageDisk;
use App\Helpers\Helper;
use App\Models\Scopes\LocalizedScope;
use App\Models\Scopes\VerifiedScope;
use App\Models\Traits\CountryTrait;
use App\Notifications\ResetPasswordNotification;
use App\Observers\UserObserver;
use Creativeorange\Gravatar\Facades\Gravatar;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Larapen\Admin\app\Models\Traits\Crud;
use Laravel\Passport\HasApiTokens;
use phpDocumentor\Reflection\Types\This;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Hash;
use stdClass;

class User extends BaseUser
{
    use Crud, HasRoles, CountryTrait, HasApiTokens, Notifiable, HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = true;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    // protected $primaryKey = 'id';    /**
//     * The table associated with the model.
//     *
//     * @var string
//     */
    protected $table = 'users';

    protected $appends = ['created_at_formatted', 'photo_url'];
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    protected $with = ['nationalityData'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'country_code',
        'city',
        'language_code',
        'user_type_id',
        'gender_id',
        'name',
        'photo',
        'about',
        'phone',
        'phone_hidden',
        'email',
        'username',
        'password',
        'remember_token',
        'is_admin',
        'can_be_impersonate',
        'disable_comments',
        'ip_addr',
        'provider',
        'provider_id',
        'email_token',
        'phone_token',
        'verified_email',
        'verified_phone',
        'accept_terms',
        'accept_marketing_offers',
        'time_zone',
        'blocked',
        'closed',
        'is_login_at',
        'last_activity',
        'parent_id',
        'password_without_hash',
        'is_package_expire',
        'skill_set',
        'employee_cv',
        'nationality',
        'experiences',
        'visa',
        'visa_number',
        'country_work_visa',
        'availability',
        'optional_emails',
        'register_via',
        'is_active',
        'affiliate_id',
    ];

    /**
     * The attributes that should be hidden for arrays
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'last_login_at', 'deleted_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function employer_logos()
    {
        return $this->hasMany(EmployeeLogo::class, 'user_id');
    }

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
     */
    public static function get_home_page_employees()
    {
        $user = User::select('users.*')
            ->with('cityData', 'country', 'nationalityData', 'countryVisa')
            ->withoutGlobalScopes([VerifiedScope::class])
            ->where('users.user_type_id', 2)
//            ->whereNotNull('users.country_code')
//            ->whereNotNull('users.city')
            ->whereNotNull('users.nationality')
            ->whereNotNull('users.country_work_visa')
            ->orderByRaw('RAND()')
            ->get()
            ->take(20);
        return $user;
    }

    public static function get_employees_count($type)
    {
        $user = User::select('users.*')->with('UserResume', 'UserSkill', 'applicant', 'country', 'nationalityData', 'countryVisa')->withoutGlobalScopes([VerifiedScope::class])->where('users.user_type_id', $type);
        if ($type == 2) {
            $user = $user->where('employee_cv', '!=', '')->whereNotNull('employee_cv')->whereNull('deleted_at');
        }
        $user_count = $user->get()->count();
        return $user_count;
    }

    public static function get_employees_filter_count($request, $type)
    {
        $user = self::build_query($request, $type);
        $user_count = $user->get()->count();
        return $user_count;
    }

    protected static function build_query($request, $type)
    {
        $user = User::select('users.*')->with('UserResume', 'UserSkill', 'applicant', 'country', 'nationalityData', 'countryVisa','UserSetting')->withoutGlobalScopes([VerifiedScope::class])->where('users.user_type_id', $type);
        if ($type == 2) {
            $user = $user->where('employee_cv', '!=', '');
        }
        if (!empty($request->get('verified_phone'))) {
            $verified_phone = '';
            if ($request->get('verified_phone') == 'active') {
                $verified_phone = '1';
            } elseif ($request->get('verified_phone') == 'inactive') {
                $verified_phone = '0';
            }
            $user = $user->where('verified_phone', $verified_phone);
        }
        if (!empty($request->get('verified_email'))) {
            $verified_email = '';
            if ($request->get('verified_email') == 'active') {
                $verified_email = '1';
            } elseif ($request->get('verified_email') == 'inactive') {
                $verified_email = '0';
            }
            $user = $user->where('verified_email', $verified_email);
        }
        if (!empty($request->get('affiliate_id'))) {
            $user = $user->where('affiliate_id', $request->get('affiliate_id'));
        }
        if (!empty($request->get('filter'))) {
            if($request->get('filter') == 'current_month'){
                $user = $user->whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->where('affiliate_id', '!=', 0)
                ->whereNull('deleted_at');
            }else if($request->get('filter') == 'last_month'){
                $user = $user->whereYear('created_at', now()->subMonth()->year)
                ->whereMonth('created_at', now()->subMonth()->month)
                ->where('affiliate_id', '!=', 0)
                ->whereNull('deleted_at');
            }else{
                $user = $user->where('affiliate_id', '!=', 0)->whereNull('deleted_at');
            }
            if (!empty($request->get('daterange'))) {
                $date_range = preg_replace('/\s+/', '', $request->get('daterange'));
                $date_range = explode('-', trim($date_range));
                $start_date = $date_range[0];
                $end_date = $date_range[1];
                $user->whereRaw('DATE(users.created_at) BETWEEN "' . date('Y-m-d', strtotime($start_date)) . '" and "' . date('Y-m-d', strtotime($end_date)) . '"');
            }
        }

        if (!empty($request->get('search'))) {

            $user = $user->where(function ($query1) use ($request) {
                $search = $request->get('search');
                if (is_array($search)) {
                    if (!empty($search['value'])) {
                        $search = $search['value'];
                    } else {
                        $search = '';
                    }
                }
                if (!empty($search)) {
                    $query1->Where('name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%")
                        ->orWhere('phone', 'LIKE', "%{$search}%")
                        ->orWhere('id', 'LIKE', "$search");
                }
            });

        }
        if (!empty($request->get('country_code'))) {
            $user = $user->where('country_code', $request->get('country_code'));
        }
        if (!empty($request->get('nationality'))) {
            $user = $user->where('nationality', $request->get('nationality'));
        }
        if (!empty($request->get('employyeskill'))) {
            $user = $user->where(function ($query) use ($request) {
                $search1 = rtrim($request->get('employyeskill'));
                $query->Where('skill_set', 'LIKE', "%{$search1}%");
            });
        }

        if ($request->get('no_contact_cv') == '0') {
            $user->whereNotNull('cv_no_contact')->where('is_approved_no_contact_cv', 0);
        } elseif ($request->get('no_contact_cv') == '1') {
            $user->whereNotNull('cv_no_contact')->where('is_approved_no_contact_cv', 1);
        } elseif ($request->get('no_contact_cv') == '2') {
            $user->whereNotNull('cv_no_contact_rejected_reason');
        }  elseif ($request->get('no_contact_cv') == '5') {
            $user->whereNotNull('cv_no_contact')->where('is_approved_no_contact_cv', 5);
        } elseif ($request->get('no_contact_cv') == '3' || auth()->user()->user_type_id == 4) {
            $user->whereNull('cv_no_contact');
        }


        if (!empty($request->get('current_subscription_users'))) {
            $user = $user->join('company_packages', 'users.id', '=', 'company_packages.employer_id')
                ->where('company_packages.is_package_expire', 0)->groupBy('company_packages.employer_id');
        }
        if ($request->get('updated_cv') == true) {
            $user = $user->whereHas('UserResume', function ($query) {
                $query->where('is_approved', 0);
            });
        }
        if ($request->get('updated_skill') == true) {
            $user = $user->whereHas('UserSkill', function ($query) {
                $query->where('is_read', 0);
            });
        }

        if (!empty($request->get('last_login'))) {
            if ($request->get('last_login') == 1) {
                $user->orderBy('last_login_at', 'DESC');
            } else {
                $user->orderBy('last_login_at', 'asc');
            }

        } else {
            $user->orderBy('users.id', 'DESC');
        }

        if (
            auth()->user()->user_type_id == 4 &&
            !$request->has('search') &&
            (!$request->has('cv_no_contact') || $request->get('cv_no_contact') !== '') &&
            (!$request->has('last_login') || $request->get('last_login') !== '') &&
            (!$request->has('updated_cv') || $request->get('updated_cv') !== '')
        ) {
            $user->where(function ($query) {
                $query->whereDoesntHave('UserResume')
                    ->orWhereHas('UserResume', function ($query) {
                        $query->whereIn('is_approved', [1, 2]);
                    });
            })->orderBy('last_login_at', 'DESC');
        }

        return $user;
    }

    public static function get_employees($request, $type)
    {
        $limit = (!empty($request->get('limit')) ? $request->get('limit') : 30);
        $limit = (!empty($request->get('length')) ? $request->get('length') : $limit);
        $user = self::build_query($request, $type);
        $user = $user->with(['UserResume', 'UserSkill', 'applicant', 'country', 'nationalityData', 'countryVisa', 'UserSetting']);
        if (!empty($request->get('start'))) {
            return $user->skip($request->get('start'))->take($limit)->get();
        }
        $user = $user->paginate($limit)->appends(request()->query());
        return $user;
    }
    public static function get_employee_count_by_skill_set($param)
    {
        $skill_set = rtrim($param);
        $user = User::where('skill_set', 'like', '%' . $skill_set . '%')->get();
        return count($user);
    }

    public static function get_employee_count_by_country($param)
    {
        $user = User::where('country_code', $param)->where('user_type_id', 2)->get();
        return count($user);
    }

    public static function get_employee_count_by_city($param)
    {
        $user = User::where('city', $param)->where('user_type_id', 2)->get();
        return count($user);
    }

    public static function get_allusers($request)
    {

        $user = User::withoutGlobalScopes([VerifiedScope::class]);
        if (!empty($request->get('search'))) {

            $user = $user->where(function ($query) use ($request) {
                $search = $request->get('search');
                $query->Where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%")
                    ->orWhere('country_code', 'LIKE', "%{$search}%");
            });

        }

        $limit = (!empty($request->get('limit')) ? $request->get('limit') : 15);
        $user = $user->orderBy('id', 'DESC')->paginate($limit);

        return $user;

    }

    public static function verified_employee_phone($request)
    {
        $id = $request->get('id');
        $verified_phone = $request->get('status');
        $employee = User::find($id);
        if ($verified_phone == 0) {
            $employee->verified_phone = 1;
        } else {
            $employee->verified_phone = 0;
        }
        return $employee->save();
    }

    public static function verified_employee_email($request)
    {
        $id = $request->get('id');
        $verified_email = $request->get('status');
        $employee = self::find($id);
        if ($verified_email == 0) {
            $employee->verified_email = 1;
        } else {
            $employee->verified_email = 0;
        }
        return $employee->save();
    }

    public static function user_by_id($id)
    {

        return User::with(['availabilityData', 'cityData', 'country', 'nationalityData', 'countryVisa'])->where('id', $id)->first();
    }

    public static function get_user_by_email($email)
    {
        $user = self::where('email', $email)->where('user_type_id', 1)->first();
        return $user;
    }

    public static function update_user($email, $data)
    {
        return self::where('email', $email)->update($data);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
     */

    public static function get_all_country_employee_count()
    {
        return self::select('countries.*', DB::raw('IFNULL(count(users.employee_cv),Null) as count'))->join('users', 'countries.code', '=', 'users.country_code', 'left')->orderBy('count', 'desc')->orderBy('countries.name', 'asc')->groupBy('countries.name')->groupBy('countries.code')->get();

    }

    public static function get_all_country_employee($type)
    {
        return self::select(array('country_code', DB::raw('COUNT(*) as users')))->where(['user_type_id' => $type])->groupBy('country_code')->orderBy('users', 'DESC')->take(5)->get();
    }

    public static function get_employee_list($request)
    {
        $cat = !empty($request->get('cat')) ? rtrim($request->get('cat')) : 'all';
        $city = !empty($request->get('city')) ? $request->get('city') : '';
        $country = (!empty($request->get('country')) ? $request->get('country') : '');
        $keyword = !empty($request->get('keyword')) ? $request->get('keyword') : '';
        $nationality = (!empty($request->get('nationality')) ? $request->get('nationality') : '');
        $sort = (!empty($request->get('sort')) ? $request->get('sort') : '');

        $user_list = User::with(['cityData', 'country'])->where('employee_cv', '!=', '');
        if ($cat == 'all') {
            $user_list = $user_list->where('name', 'like', '%' . $keyword . '%');
        } else {
            $user_list = $user_list->where('skill_set', 'like', '%' . $cat . '%')->where('name', 'like', '%' . $keyword . '%');
        }
        if (!empty($country)) {
            $user_list = $user_list->where('country_code', $country);
        }
        if (!empty($city)) {
            $user_list = $user_list->where('city', $city);
        }

        if (!empty($nationality)) {
            $user_list = $user_list->where('nationality', $nationality);
        }

        if (!empty($sort)) {
            if ($sort == 'old_to_new') {
                $user_list = $user_list->orderBy('updated_at', 'ASC');

            } else if ($sort == 'new_to_old') {
                $user_list = $user_list->orderBy('updated_at', 'DESC');
            }
        } else {
            $user_list = $user_list->orderBy('id', 'DESC');
        }

        $limit = (!empty($request->get('limit')) ? $request->get('limit') : 50);
        $user_list = $user_list->paginate($limit)->appends(request()->query());
        return $user_list;
    }

    public static function get_users_list_with_post_skills($skills)
    {
        $query = User::select("users.*");

        foreach ($skills as $index => $skill) {
            if ($index === 0) {
                $query = $query->whereRaw("FIND_IN_SET('$skill', skill_set)");
            } else {
                $query = $query->orWhereRaw("FIND_IN_SET('$skill', skill_set)");
            }
        }
        $query = $query->where("user_type_id", 2);
        $data = $query->get();
        return $data;
    }

    public static function get_child_company($id)
    {
        return User::where('parent_id', $id)->get()->toArray();
    }

    public static function get_parant_company($id)
    {
        return User::where('id', $id)->get()->toArray();
    }

    public static function get_user_by_id($id)
    {
        return User::where('id', $id)->first();
    }

    public static function get_all_featured_companies()
    {
        $featured_companies = User::with('company')->where('user_type_id', 1)->where('add_feature', 1)->get();
        $featured_companies_list = [];

        foreach ($featured_companies as $user) {
            $company = $user->company;
            $std = new stdClass();
            $std->id = $company->id;
            $std->user_id = (int)$company->c_id;
            $std->name = $company->name;
            $post_count = Post::where('company_id', $company->id)->where('is_deleted', 0)->where('archived', 0)->where('is_post_expire', 0)->where('is_approved', 1)->count();
            $std->post_count = $post_count;
            // $employer_logos = EmployeeLogo::where('user_id', $company->c_id)->get();
            // $logo_urls = [];
            // $logo_urls[] = $user->file ;
            // foreach ($employer_logos as $logo) {
            //     $logo_urls[] = $logo->logo;
            // }

            $std->logo = Helper::get_company_logo($company);

            $featured_companies_list[] = $std;
        }

        return $featured_companies_list;
    }

    public static function number_of_employees_count()
    {
        return User::where(['user_type_id' => 2])->count();
    }

    public static function number_of_employeers_count()
    {
        return User::where(['user_type_id' => 1])->count();
    }

    public static function current_subscription_users_count()
    {
        return User::where(['user_type_id' => 1])->where('is_package_expire', 0)->count();
    }

    public static function verfied_phone_employees_count()
    {
        return User::where(['user_type_id' => 2])->where('employee_cv', '!=', '')->count();
    }

    public static function number_of_affiliates_count()
    {
        return User::where(['user_type_id' => 5])->count();
    }
    
    public static function number_of_referral_users_count()
    {
        return User::where('affiliate_id', '!=', 0)
        ->where('user_type_id', 1)
        ->whereNull('deleted_at')->count();
    }

    public static function last_month_referral_users_count()
    {
        return User::whereYear('created_at', now()->subMonth()->year)
        ->whereMonth('created_at', now()->subMonth()->month)
        ->where('affiliate_id', '!=', 0)
        ->where('user_type_id', 1)
        ->whereNull('deleted_at')->count();
    }

    public static function current_month_referral_users_count()
    {
        return User::whereYear('created_at', now()->year)
        ->whereMonth('created_at', now()->month)
        ->where('affiliate_id', '!=', 0)
        ->where('user_type_id', 1)
        ->whereNull('deleted_at')->count();
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
     */

    public static function verfied_phone_employeers_count()
    {
        return User::where(['user_type_id' => 1])->where('verified_phone', 1)->where('verified_email', 1)->count();
    }

    public static function latestUsers()
    {
        return User::take(config('settings.app.latest_entries_limit', 5))->where('user_type_id', 2)->orderBy('created_at', 'DESC')->get();
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
     */

    public static function latestEmployeers()
    {
        return User::where('user_type_id', 1)->take(config('settings.app.latest_entries_limit', 5))->orderBy('created_at', 'DESC')->get();
    }

    public static function get_all_featured_companies_users()
    {
        return User::where('user_type_id', 1)->where('add_feature', 1)->get();
    }

    public static function get_all_featured_companies_users_with_employer_logo()
    {
        return User::select('users.id as companies_id','users.*')->where('user_type_id', 1)->where('add_feature', 1)->with('employer_logos')->limit(10)->get();
    }

    public static function get_staff_list($request)
    {
        // Base query to get staff users
        $staff = User::where('users.user_type_id', 3) // Staff user_type_id
        ->leftJoin('users as companies', 'users.company_id', '=', 'companies.id')
        ->select(
            'users.*',
            'companies.email as company_email',
            'companies.name as company_name',
        );

        // Apply search filter if provided
        if (!empty($request->get('search'))) {
            $staff = $staff->where(function ($query) use ($request) {
                $search = $request->get('search');
                $query->where('users.name', 'LIKE', "%{$search}%")
                    ->orWhere('users.email', 'LIKE', "%{$search}%")
                    ->orWhere('users.phone', 'LIKE', "%{$search}%");
            });
        }

        $limit = (!empty($request->get('limit')) ? $request->get('limit') : 30);

        $staff = $staff->orderBy('users.id', 'DESC')->paginate($limit)
            ->appends(request()->query());


        return $staff;
    }


    public static function get_staff_list_count($request)
    {
        $staff = User::where('user_type_id', 3);
        if (!empty($request->get('search'))) {
            $staff = $staff->where(function ($query) use ($request) {
                $search = $request->get('search');
                $query->Where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%");
            });

        }
        $limit = (!empty($request->get('limit')) ? $request->get('limit') : 30);
        $staff->orderBy('id', 'DESC');
        $staff_count = $staff->get()->count();
        return $staff_count;
    }

    protected static function boot()
    {
        parent::boot();

        User::observe(UserObserver::class);

        // Don't apply the ActiveScope when:
        // - User forgot its Password
        // - User changes its Email or Phone
        if (
            !Str::contains(Route::currentRouteAction(), 'Auth\ForgotPasswordController') &&
            !Str::contains(Route::currentRouteAction(), 'Auth\ResetPasswordController') &&
            !session()->has('emailOrPhoneChanged') &&
            !Str::contains(Route::currentRouteAction(), 'Impersonate\Controllers\ImpersonateController')
        ) {
            static::addGlobalScope(new VerifiedScope());
        }
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

    /**
     * Send the password reset notification.
     *
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {

        $users = User::where('email', $this->email)->first();
        $users->reset_token = $token;
        $users->save();

        if (request()->filled('email') || request()->filled('phone')) {
            if (request()->filled('email')) {
                $field = 'email';
            } else {
                $field = 'phone';
            }
        } else {
            if (!empty($this->email)) {
                $field = 'email';
            } else {
                $field = 'phone';
            }
        }

        try {
            $this->notify(new ResetPasswordNotification($this, $token, $field));
        } catch (Exception $e) {
            flash($e->getMessage())->error();
        }
    }

    /**
     * @return bool
     */
    public function canImpersonate()
    {
        // Cannot impersonate from Demo website,
        // Non admin users cannot impersonate
        if (isDemo() || !$this->can(Permission::getStaffPermissions())) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function canBeImpersonated()
    {
        // Cannot be impersonated from Demo website,
        // Admin users cannot be impersonated,
        // Users with the 'can_be_impersonated' attribute != 1 cannot be impersonated
        if (isDemo()
            || $this->can_be_impersonated != 1) {
            return false;
        }

        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
     */

    public function impersonateBtn($xPanel = false)
    {
        // Get all the User's attributes
        $user = self::findOrFail($this->getKey());

        // Get impersonate URL
        $impersonateUrl = dmUrl($this->country_code, 'impersonate/take/' . $this->getKey(), false, false);

        // If the Domain Mapping plugin is installed,
        // Then, the impersonate feature need to be disabled
        if (config('plugins.domainmapping.installed')) {
            return null;
        }

        // Generate the impersonate link
        $out = '';
        if ($user->getKey() == auth()->user()->getAuthIdentifier()) {
            $tooltip = '" data-toggle="tooltip" title="' . t('Cannot impersonate yourself') . '"';
            $out .= '<a class="btn btn-xs btn-warning" ' . $tooltip . '><i class="fa fa-lock"></i></a>';
        } else if ($user->can(Permission::getStaffPermissions())) {
            $tooltip = '" data-toggle="tooltip" title="' . t('Cannot impersonate admin users') . '"';
            $out .= '<a class="btn btn-xs btn-warning" ' . $tooltip . '><i class="fa fa-lock"></i></a>';
        } else if (!isVerifiedUser($user)) {
            $tooltip = '" data-toggle="tooltip" title="' . t('Cannot impersonate unactivated users') . '"';
            $out .= '<a class="btn btn-xs btn-warning" ' . $tooltip . '><i class="fa fa-lock"></i></a>';
        } else {
            $tooltip = '" data-toggle="tooltip" title="' . t('Impersonate this user') . '"';
            $out .= '<a class="btn btn-xs btn-light" href="' . $impersonateUrl . '" ' . $tooltip . '><i class="fas fa-sign-in-alt"></i></a>';
        }

        return $out;
    }

    public function deleteBtn($xPanel = false)
    {
        if (auth()->check()) {
            if ($this->id == auth()->user()->id) {
                return null;
            }
            if (isDemoDomain() && $this->id == 1) {
                return null;
            }
        }

        $url = admin_url('users/' . $this->id);

        $out = '';
        $out .= '<a href="' . $url . '" class="btn btn-xs btn-danger" data-button-type="delete">';
        $out .= '<i class="fa fa-trash"></i> ';
        $out .= trans('admin.delete');
        $out .= '</a>';

        return $out;
    }

    public function isOnline()
    {
        $isOnline = ($this->last_activity > Carbon::now(Date::getAppTimeZone())->subMinutes(5));

        // Allow only logged users to get the other users status
        $isOnline = auth()->check() ? $isOnline : false;

        return $isOnline;
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id')->orderByDesc('created_at');
    }

    public function Company()
    {
        return $this->hasOne(Company::class, 'c_id');
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class, 'gender_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_code');
    }

    public function UserResume()
    {
        return $this->hasMany(UserResume::class, 'user_id');
    }

    public function UserSkill()
    {
        return $this->hasMany(UserSkills::class, 'user_id');
    }

    public function cityData()
    {
        return $this->belongsTo(City::class, 'city');
    }

    public function availabilityData()
    {
        return $this->belongsTo(Availability::class, 'availability');
    }

    public function nationalityData()
    {
        return $this->belongsTo(Nationality::class, 'nationality');
    }

    public function countryVisa()
    {
        return $this->belongsTo(Country::class, 'country_work_visa');
    }

    public function unlock()
    {
        return $this->hasMany(Unlock::class, 'user_id');
    }

    public function UserSetting()
    {
        return $this->hasOne(UserSetting::class, 'user_id');
    }

    public function receivedThreads()
    {
        return $this->hasManyThrough(
            Thread::class,
            Post::class,
            'user_id', // Foreign key on the Post table...
            'post_id', // Foreign key on the Thread table...
            'id', // Local key on the User table...
            'id' // Local key on the Post table...
        );
    }

    public function threads()
    {
        return $this->hasManyThrough(
            Thread::class,
            ThreadMessage::class,
            'user_id', // Foreign key on the ThreadMessage table...
            'post_id', // Foreign key on the Thread table...
            'id', // Local key on the User table...
            'id' // Local key on the ThreadMessage table...
        );
    }

    public function savedPosts()
    {
        return $this->belongsToMany(Post::class, 'saved_posts', 'user_id', 'post_id');
    }

    public function savedSearch()
    {
        return $this->hasMany(SavedSearch::class, 'user_id');
    }

    public function userType()
    {
        return $this->belongsTo(UserType::class, 'user_type_id');
    }

    public function scopeVerified($builder)
    {
        $builder->where(function ($query) {
            $query->where('verified_email', 1)->where('verified_phone', 1);
        });

        return $builder;
    }

    public function scopeUnverified($builder)
    {
        $builder->where(function ($query) {
            $query->where('verified_email', 0)->orWhere('verified_phone', 0);
        });

        return $builder;
    }

    public function getCreatedAtAttribute($value)
    {
        $value = new Carbon($value);
        $value->timezone(Date::getAppTimeZone());

        return $value;
    }

    public function getUpdatedAtAttribute($value)
    {
        $value = new Carbon($value);
        $value->timezone(Date::getAppTimeZone());

        return $value;
    }

    public function getLastActivityAttribute($value)
    {
        $value = new Carbon($value);
        $value->timezone(Date::getAppTimeZone());

        return $value;
    }

    public function getLastLoginAtAttribute($value)
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
        if (empty($this->attributes['created_at'])) {
            return null;
        }

        if (!empty($this->attributes['created_at']) and is_null($this->attributes['created_at'])) {
            return null;
        }

        $value = new Carbon($this->attributes['created_at']);
        $value->timezone(Date::getAppTimeZone());

        $value = Date::formatFormNow($value);

        return $value;
    }

    public function getPhotoUrlAttribute($value)
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
        return $value;
    }

    public function getNameAttribute($value)
    {
        $value = mb_ucwords($value);

        return $value;
    }

    public function setPhotoAttribute($value)
    {
        $disk = StorageDisk::getDisk();
        $attribute_name = 'photo';

        // Path
        $destination_path = 'avatars/' . strtolower($this->country_code) . '/' . $this->id;

        // If the image was erased
        if (empty($value)) {
            // delete the image from disk
            $disk->delete($this->{$attribute_name});

            // set null in the database column
            $this->attributes[$attribute_name] = null;

            return false;
        }

        // Check the image file
        if ($value == url('/')) {
            $this->attributes[$attribute_name] = null;

            return false;
        }

        // If laravel request->file('filename') resource OR base64 was sent, store it in the db
        try {
            if (fileIsUploaded($value)) {
                // Remove all the current user's photos, by removing his photo directory.
                $disk->deleteDirectory($destination_path);

                // Get file extension
                $extension = getUploadedFileExtension($value);
                if (empty($extension)) {
                    $extension = 'jpg';
                }

                // Image quality
                $imageQuality = config('settings.upload.image_quality', 90);

                // Image default dimensions
                $width = (int)config('larapen.core.picture.otherTypes.user.width', 800);
                $height = (int)config('larapen.core.picture.otherTypes.user.height', 800);

                // Other parameters
                $ratio = config('larapen.core.picture.otherTypes.user.ratio', '1');
                $upSize = config('larapen.core.picture.otherTypes.user.upsize', '0');

                // Init. Intervention
                $image = Image::make($value);

                // Get the image original dimensions
                $imgWidth = (int)$image->width();
                $imgHeight = (int)$image->height();

                // Fix the Image Orientation
                if (exifExtIsEnabled()) {
                    $image = $image->orientate();
                }

                // If the original dimensions are higher than the resize dimensions
                // OR the 'upsize' option is enable, then resize the image
                if ($imgWidth > $width || $imgHeight > $height || $upSize == '1') {
                    // Resize
                    $image = $image->resize($width, $height, function ($constraint) use ($ratio, $upSize) {
                        if ($ratio == '1') {
                            $constraint->aspectRatio();
                        }
                        if ($upSize == '1') {
                            $constraint->upsize();
                        }
                    });
                }

                // Encode the Image!
                $image = $image->encode($extension, $imageQuality);

                // Generate a filename.
                $filename = md5($value . time()) . '.' . $extension;

                // Store the image on disk.
                $disk->put($destination_path . '/' . $filename, $image->stream()->__toString());

                // Save the path to the database
                $this->attributes[$attribute_name] = $destination_path . '/' . $filename;
            } else {
                // Retrieve current value without upload a new file.
                if (Str::startsWith($value, config('larapen.core.picture.default'))) {
                    $value = null;
                } else {
                    if (!Str::startsWith($value, 'avatars/')) {
                        $value = $destination_path . last(explode($destination_path, $value));
                    }
                }
                $this->attributes[$attribute_name] = $value;
            }
        } catch (Exception $e) {
            flash($e->getMessage())->error();
            $this->attributes[$attribute_name] = null;

            return false;
        }
    }

    public function applicant()
    {
        return $this->hasMany(Applicant::class, 'user_id');
    }

    public function User_skill_experience()
    {
        return $this->hasMany(User_skill_experience::class);
    }

    public static function get_applicant_skill_set($user_ids)
    {

        $appliedUsers = User::whereIn('id', $user_ids)->get();

        // Extract skill sets for dropdown
        $skillSets = [];
        foreach ($appliedUsers as $user) {
            $userSkillSets = explode(',', $user->skill_set);
            $skillSets = array_merge($skillSets, $userSkillSets);
        }
        // Remove duplicates and create an array suitable for dropdown
        $uniqueSkillSets = array_unique($skillSets);
        // Convert the unique skill sets array to indexed array
        $skillSetsDropdown = array_values($uniqueSkillSets);
        return $skillSetsDropdown;
    }

    public static function get_nationality_users($type)
    {
        return self::select(array('nationality', DB::raw('COUNT(*) as users')))->with(['nationalityData'])->where(['user_type_id' => $type])->groupBy('nationality')->orderBy('users', 'DESC')->take(8)->get();
    }

    public static function get_user_by_api_token($api_token)
    {
        return self::where('api_token', request()->post('token'))->first();
    }

    public static function user_count_with_no_contact_Cv()
    {
        return self::whereNull('cv_no_contact')
            ->where('user_type_id', 2)
            ->where('employee_cv', '!=', '')
            ->whereNotNull('employee_cv') // Just to be extra safe
            ->whereNull('deleted_at')
            ->whereIn('is_approved_no_contact_cv', [2, 0, 3, 5])
            ->withoutGlobalScopes([VerifiedScope::class])
            ->count();
    }

    public static function get_unlock_contact_counts()
    {
        $baseQuery = User::where('user_type_id', 2)
                        ->where('employee_cv', '!=', '')
                        ->whereNotNull('employee_cv')
                        ->whereNull('deleted_at')
                        ->withoutGlobalScopes([VerifiedScope::class]);
    
        $data = [
            'total_cv' => (clone $baseQuery)->count(),
    
            'approved_no_contact_cv' => (clone $baseQuery)
                ->whereNotNull('cv_no_contact')
                ->where('is_approved_no_contact_cv', 1)
                ->count(),
    
            'rejected_no_contact_Cv' => (clone $baseQuery)
                ->whereNull('cv_no_contact')
                ->where('is_approved_no_contact_cv', 2)
                ->count(),
    
            'pending_no_contact_cv' => (clone $baseQuery)
                ->whereNull('cv_no_contact')
                ->where('is_approved_no_contact_cv', 0)
                ->count(),
    
            'pending_approved' => (clone $baseQuery)
                ->whereNotNull('cv_no_contact')
                ->where('is_approved_no_contact_cv', 0)
                ->count(),
    
            'inprocess' => (clone $baseQuery)
                ->whereNull('cv_no_contact')
                ->where('is_approved_no_contact_cv', 3)
                ->count(),
    
            'qa_pending' => (clone $baseQuery)
                ->whereNotNull('cv_no_contact')
                ->where('is_approved_no_contact_cv', 5)
                ->count(),
        ];
    
        return $data;
    }
    
    public static function get_employee_no_contact_cv()
    {
        
        $user = User::where('users.user_type_id', 2)
            ->where('employee_cv', '!=', '')
            ->where(function ($query) {
                $query->where('is_approved_no_contact_cv', '!=', 3)
                    ->whereNull('cv_no_contact')
                    ->orWhere('cv_no_contact', '');
            })
            ->where(function ($query) {
                $query->whereDoesntHave('UserResume')
                    ->orWhereHas('UserResume', function ($query) {
                        $query->whereIn('is_approved', [1, 2]);
                    });
            })
            ->orderByRaw("CASE WHEN cv_no_contact_rejected_reason IS NOT NULL THEN 0 ELSE 1 END")
            ->orderBy('id', 'DESC')
            ->paginate(25)
            ->appends(request()->query());
        
        return $user;
    }


    public static function update_login_time()
    {
        self::where('id', auth()->user()->id)->update(['last_login_at' => date('Y-m-d H:i:s')]);
    }


    public static function get_all_employer()
    {
        return self::where('user_type_id', 1)->orderBy('id', 'desc')->get();
    }

    public static function update_cv_status($user_id, $status)
    {
        return self::where('id', $user_id)->update(['is_approved_no_contact_cv' => 4]);

    }

    public static function get_next_or_previous_user($type, $id=null)
    {
        $user = User::whereNotNull('cv_no_contact')->where('is_approved_no_contact_cv', 5);
        if ($type == 'next') {
            $user->where('id', '>', $id)->orderBy('id', 'asc');
        } elseif ($type == 'previous') {
            $user->where('id', '<', $id)->orderBy('id', 'desc');
        }else{
            $user->orderBy('id', 'desc');
        }
        $user = $user->first();
        return $user;

    }

    public static function getFiveCvs($id, $type = null, $limit = 5, $offset = 0)
    {
        $query = User::whereNotNull('cv_no_contact')
            ->where('is_approved_no_contact_cv', 5)
            ->where('id', '>=', $id)
            ->orderBy('id', 'asc');

        if ($type === 'next') {
            $query->skip($offset)->take(1);
        } elseif ($type === 'previous') {
            $query->skip($offset - 1)->take(1);
        } else {
            $query->take($limit);
        }
        return $query->get();
    }

    public static function getApprovalCv($type, $number_of_cv,$last_id)
    {
        $query = User::whereNotNull('cv_no_contact');
        if($type == 'admin'){
            $query = $query->where('is_approved_no_contact_cv', 0);
        }else{
            $query = $query->where('is_approved_no_contact_cv', 5);
        }
        if(!empty($last_id)){
            $query = $query->where('id', '>', $last_id);
        }
        $query->take($number_of_cv)->orderBy('id', 'asc');

        return $query->get();
    }

    public static function getCronApprovalCv($number_of_cv,$last_id)
    {
        $query = User::whereNotNull('cv_no_contact')->where('is_approved_no_contact_cv', 0);
        return $query->take($number_of_cv)->orderBy('id', 'asc')->get();
    }
    
    public static function getCronOriginalCv($number_of_cv,$last_id)
    {
        $query = User::where('user_type_id', 2)->where('users.employee_cv', '!=', '');
        if(!empty($last_id)){
            $query = $query->where('id', '>', $last_id);
        }
        return $query->take($number_of_cv)->orderBy('id', 'asc')->get();
    }


    // Affiliates Functions

    public static function get_affiliate($affiliate_id)
    {
        return self::where('id', $affiliate_id)->first();
    }

    public static function get_affiliates($request, $type)
    {
        $limit = (!empty($request->get('limit')) ? $request->get('limit') : 30);
        $limit = (!empty($request->get('length')) ? $request->get('length') : $limit);
        $search = $request->search ?? '';

        $affiliate_users = User::where('user_type_id', 5)
            ->with('affiliatedCommissionSlots') // Eager loading
            ->when(!empty($search), function ($query) use ($search) {
                $query->where(function ($q) use ($search) { // Prevent OR breaking
                    $q->where('phone', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('id', 'like', "%{$search}%");
                });
            })->orderBy('id', 'DESC');
        if (!empty($request->get('affiliate_id'))) {
            $affiliate_users = $affiliate_users->where('affiliate_id', $request->get('affiliate_id'));
        }

        if (!empty($request->get('start'))) {
            return $affiliate_users->skip($request->get('start'))->take($limit)->get();
        }
        $affiliate_users = $affiliate_users->paginate($limit)->appends(request()->query()); // Prevent memory overload
        return $affiliate_users;
    }

    public static function get_affiliates_filter_count($request)
    {
        
        $search = $request->search ?? '';

        $affiliate_users = User::where('user_type_id', 5)
            ->with('affiliatedCommissionSlots') // Eager loading
            ->when(!empty($search), function ($query) use ($search) {
                $query->where(function ($q) use ($search) { // Prevent OR breaking
                    $q->where('phone', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('id', 'like', "%{$search}%");
                });
            });

        if (!empty($request->get('affiliate_id'))) {
            $affiliate_users = $affiliate_users->where('affiliate_id', $request->get('affiliate_id'));
        }

        $affiliates_filter_count = $affiliate_users->get()->count();
        return $affiliates_filter_count;
    }

    public static function check_existence($email, $phone, $id = null)
    {
        $check = User::where(function ($query) use ($email, $phone) {
                $query->where('email', $email)
                      ->orWhere('phone', $phone);
            })
            ->when($id, function ($query) use ($id) {
                $query->where('id', '!=', $id);
            })
            ->first(['id', 'is_active']); // Fetch only needed columns

        if ($check) {
            return [
                'status' => false,
                'message' => trans(
                    $check->is_active == 0 
                        ? 'Your account has been suspended by the admin. Please contact the admin for further assistance.' 
                        : 'We already have a registered affiliate with this email.'
                ),
            ];
        }

        return ['status' => true];
    }


    public static function store_affiliate_request($request)
    {
        $checkExistence = self::check_existence($request->email, $request->phone);
        if (!$checkExistence['status']) {
            return ['status' => false, 'message' => $checkExistence['message']];
        }

        if (!empty($request->get('email'))) {
            $result = EmailCheck::check_user_email($request->get('email'));
            if (!$result) {
                return ['status' => false, 'message' => t('please_provide_valid_email_to_register_account')];
            }
        }

        $isActive = auth()->check() && auth()->user()->is_admin ? 1 : 1;
        $phone = $request->get('phone');
        if (!str_contains($request->get('phone'), '+' . config('country.phone'))) {
            $phone = '+' . config('country.phone') . $request->get('phone');
        }

        // get referral details 
        $affiliate_id = '';
        if (!empty($request->referral_code) && $request->user_type_id == 5) {
            $referral_user = User::where('referral_code', $request->referral_code)->first();
            if (empty($referral_user)) {
                return ['status' => false, 'message' => trans('referral_not_exist')];
            }

            $affiliate_id = $referral_user->id;
        }

        $user = User::create([
            'name' => $request->name,
            'phone' => $phone,
            'email' => $request->email,
            'country_code' => $request->country_code,
            'city' => $request->city,
            'language_code' => 'en',
            'gender_id' => 1,
            'is_active' => $isActive,
            'user_type_id' => 5,
            'password' => !empty($request->password) ? Hash::make($request->password) : null,
            'register_via' => auth()->check() ? 'admin' : 'manual',
            'affiliate_id' => !empty($affiliate_id) && $request->user_type_id == 5 ? $affiliate_id : 0
        ]);

        if ($user) {
            self::notifyAdmins($user);
            $name = $user->name;
            $profile_url = admin_url() . '/affiliates?search=' . $user->email;
            if(auth()->check()){
                $admin_url = admin_url() . '/employer?search=' . auth()->user()->email;
                $url = "<a href='$admin_url'><strong>HungryForJobs</strong></a>";
                $description = "The admin ". $url ." has registered a new User Name: <a href='$profile_url'>$name</a> for the affiliate program.";
            }else{
                if (!empty($request->referral_code)) {
                    $referrer_name = $referral_user->name;
                    $referrer_url = admin_url() . '/affiliates?search=' . $referral_user->email;
                    $description = "A new User Name: <a href='$profile_url'>$name</a> has registered for the affiliate program using a referral link. Referred by: <a href='$referrer_url'>$referrer_name</a>";
                }else{
                    $description = "A new User Name: <a href='$profile_url'>$name</a> has registered for the affiliate program.";
                }
            }
            Helper::activity_log($description);
            if(!auth()->check()){
                return ['status' => true, 'message' => 'Congratulations! You have successfully registered for our affiliate program. Welcome aboard!'];
            }else{
                return ['status' => true, 'message' => 'The user has successfully registered for the affiliate program.'];
            }
        }

        return ['status' => false, 'message' => trans('Your form could not be submitted successfully. Please try again.')];
    }

    private static function notifyAdmins($user)
    {
        if (config('settings.mail.admin_notification')) {
            try {
                $admins = User::permission(Permission::getStaffPermissions())->get();
                foreach ($admins as $admin) {
                    EmailHelper::sendadminemailToAffiliate($admin, $user);
                }
                EmailHelper::sendaffiliateregisteremail($user);
                if($user->affiliate_id != 0){
                    $data['referrer_affiliate'] = User::find($user->affiliate_id);
                    $data['affiliate'] = $user;
                    EmailHelper::notifyReferrerOfNewAffiliateRegistration($data);
                }
            } catch (Exception $e) {
                flash($e->getMessage())->error();
            }
        }
    }

    
    public static function update_affiliate_request($request, $id)
    {
        $checkExistence = self::check_existence($request->email, $request->phone, $id);
        if (!$checkExistence['status']) {
            return ['status' => false, 'message' => $checkExistence['message']];
        }

        $user = User::find($id);
        if (!$user) {
            return ['status' => false, 'message' => trans('User not found')];
        }

        $user->fill([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'country_code' => $request->country_code,
            'city' => $request->city,
        ]);
        
        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }

        if ($user->save()) {
            if($request->hasFile('file')){
                 self::updateUserProfileImage($user, $request->file('file'));
            }
            $message = trans('The affiliate has been successfully updated');
        }else{
            $message = trans('The affiliate has not been updated. Please try again later.');
        }

        return [
            'status' => true,
            'message' => $message,
        ];
    }

    private static function updateUserProfileImage($user, $file)
    {
        $userId = $user->id;
        $fileType = $file->getClientOriginalExtension();
        $destinationPath = public_path("storage/pictures/kw/{$userId}");
        
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        if (!Helper::validateUserProfileImage($file)) {
            $fileName = 'pictures/default.jpg';
        } else {
            $fileName = "pictures/kw/{$userId}/profile_" . time() . ".{$fileType}";
        }

        $oldFilePath = public_path("storage/{$user->file}");
        if (!str_contains($oldFilePath, 'default') && file_exists($oldFilePath)) {
            unlink($oldFilePath);
        }
        
        $file->move($destinationPath, $fileName);
        $user->update(['file' => $fileName]);
    }

    public function affiliatedCommissionSlots()
    {
        return $this->hasMany(AffiliatesCommissionSlots::class, 'affiliate_id', 'id');    
    }

    public static function get_current_slot($affiliate_id)
    {
        $referral_users = User::where('affiliate_id', $affiliate_id)->where('is_active', 1)->pluck('id');
        $amount = Payment::whereIn('user_id', $referral_users)
                ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
                ->sum('amount');

        $currentSlot = AffiliatesCommissionSlots::get_commission_slot($affiliate_id, $amount);
        
        return $currentSlot;
    }

    public static function get_all_affiliate()
    {
        return self::where('user_type_id', 5)->orderBy('id', 'desc')->get();
    }
    // Affiliates Functions

    public static function user_cvs_data()
    {
        $baseQuery = User::where('user_type_id', 2)
            ->where('employee_cv', '!=', '')
            ->whereNotNull('employee_cv')
            ->whereNull('deleted_at')
            ->withoutGlobalScopes([VerifiedScope::class]);

        $data = [
            'rejected' => (clone $baseQuery)
                ->whereNull('cv_no_contact')
                ->where('is_approved_no_contact_cv', 2)
                ->get(),

            'pending' => (clone $baseQuery)
                ->whereNull('cv_no_contact')
                ->where('is_approved_no_contact_cv', 0)
                ->get(),

            'inprocess' => (clone $baseQuery)
                ->whereNull('cv_no_contact')
                ->where('is_approved_no_contact_cv', 3)
                ->get(),
        ];

        return $data;
    }
}
