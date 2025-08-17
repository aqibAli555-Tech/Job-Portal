<?php

namespace App\Models;

use App\Helpers\Date;
use App\Helpers\Files\Storage\StorageDisk;
use App\Helpers\RemoveFromString;
use App\Helpers\UrlGen;
use App\Models\Scopes\LocalizedScope;
use App\Observers\CompanyObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Request;
use Larapen\Admin\app\Models\Traits\Crud;

class Company extends BaseModel
{
    use Crud, HasFactory;

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
    // protected $primaryKey = 'id';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'companies';
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'name', 'logo', 'description', 'country_code', 'city_id', 'address', 'tiktok', 'instagram', 'whatsapp', 'phone', 'fax', 'email', 'website', 'facebook', 'twitter', 'linkedin', 'googleplus', 'pinterest', 'causes', 'entities', 'thumbnail','is_image_uploaded_on_aws'];


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
    protected $dates = ['created_at', 'updated_at'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

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

    public static function get_company($request)
    {
        $limit = (!empty($request->get('limit')) ? $request->get('limit') : 30);
        $limit = (!empty($request->get('length')) ? $request->get('length') : $limit);

        $company = Company::with('user');
        if (!empty($request->get('country'))) {
            $company = $company->where('country_code', $request->get('country'));
        }

        if (!empty($request->get('search'))) {
            $company = $company->where(function ($query) use ($request) {
                $search = $request->get('search');
                $query->Where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }
        $company = $company->where('deleted_at', NULL);

        if (!empty($request->get('start'))) {
            return $company->skip($request->get('start'))->take($limit)->get();
        }

        $company = $company->orderBy('id', 'DESC')->paginate($limit)->appends(request()->query());


        return $company;
    }

    public static function get_company_count($request)
    {
        $company = Company::with('user');
        if (!empty($request->get('country'))) {
            $company = $company->where('country_code', $request->get('country'));
        }

        if (!empty($request->get('search'))) {
            $company = $company->where(function ($query) use ($request) {
                $search = $request->get('search');
                $query->Where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }
        $company = $company->where('deleted_at', NULL);
        $company = $company->count();
        return $company;
    }

    public static function get_post_company($id)
    {
        return self::where('c_id', $id)->first();
    }

    public static function get_company_by_id($user_id = null)
    {
        if (auth()->check()) {
            $user_id = auth()->user()->id;
        }
        return Company::where(['user_id' => $user_id, 'deleted_at' => NULL])->orderByDesc('id')->get();
    }
    public static function get_by_id($user_id = null)
    {
        return Company::where(['user_id' => $user_id, 'deleted_at' => NULL])->first();
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public static function get_all_company_count()
    {
        return Company::where(['user_id' => auth()->user()->id, 'deleted_at' => NULL])->count();
    }

    public static function get_company_data($id)
    {
        return Company::with(['city', 'country', 'companyData'])->where('c_id', $id)->first();
    }

    public static function get_company_data_by_id($id)
    {
        return Company::select('id')->where('c_id', $id)->first();
    }

    protected static function boot()
    {
        parent::boot();

        Company::observe(CompanyObserver::class);
        static::addGlobalScope(new LocalizedScope());
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getNameHtml()
    {
        $company = self::find($this->id);

        $out = '';
        if (!empty($company)) {
            $out .= '<a href="' . UrlGen::company(null, $company->id) . '" target="_blank">';
            $out .= $company->name;
            $out .= '</a>';
            $out .= ' <span class="label label-default">' . $company->posts()->count() . ' ' . trans('admin.jobs') . '</span>';
        } else {
            $out .= '--';
        }

        return $out;
    }

    public function getLogoHtml()
    {
        $style = ' style="width:116px; max-height:90px;"';
        // Get logo
        $out = '<img src="' . url('/public/storage/') . '/' . $this->attributes['logo'] . '" data-toggle="tooltip" title="' . $this->name . '"' . $style . '>';
        // Add link to the Ad

        $url = UrlGen::company(null, $this->id);
        $out = '<a href="' . $url . '" target="_blank">' . $out . '</a>';

        return $out;
    }

    public function getCountryHtml()
    {
        $iconPath = 'images/flags/16/' . strtolower($this->country_code) . '.png';
        if (file_exists(public_path($iconPath))) {
            $out = '';
            $out .= '<img src="' . url('public/' . $iconPath) . getPictureVersion() . '" data-toggle="tooltip" title="' . $this->country_code . '">';

            return $out;
        } else {
            return $this->country_code;
        }
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'company_id');
    }

    public function companyData()
    {
        return $this->belongsTo(User::class, 'c_id');
    }

    public function EmployerLogo()
    {
        return $this->hasMany(EmployeeLogo::class, 'user_id', 'c_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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

    public function getEmailAttribute($value)
    {
        if (
            isDemo() &&
            Request::segment(2) != 'password'
        ) {
            if (auth()->check()) {
                if (auth()->user()->id != 1) {
                    $value = hidePartOfEmail($value);
                }
            }

            return $value;
        } else {
            return $value;
        }
    }

    public function getPhoneAttribute($value)
    {
        $countryCode = config('country.code');
        // if (isset($this->country_code) && !empty($this->country_code)) {
        //     $countryCode = $this->country_code;
        // }

        // $value = phoneFormatInt($value, $countryCode);

        return $value;
    }

    public function getNameAttribute($value)
    {
        return mb_ucwords($value);
    }

    public function getDescriptionAttribute($value)
    {
        if (!isFromAdminPanel()) {
            if (!empty($this->user)) {
                if (!$this->user->hasAllPermissions(Permission::getStaffPermissions())) {
                    $value = RemoveFromString::contactInfo($value, false, true);
                }
            } else {
                $value = RemoveFromString::contactInfo($value, false, true);
            }
        }

        return $value;
    }

    public function getWebsiteAttribute($value)
    {
        return addHttp($value);
    }

    public function getLogoFromOldPath()
    {
        if (!isset($this->attributes) || !isset($this->attributes['logo'])) {
            return null;
        }

        $value = $this->attributes['logo'];

        // Fix path
        $value = str_replace('uploads/pictures/', '', $value);
        $value = str_replace('pictures/', '', $value);
        $value = 'pictures/' . $value;

        $disk = StorageDisk::getDisk();

        if (!$disk->exists($value)) {
            $value = null;
        }

        return $value;
    }

    public function getLogoAttribute()
    {
        // // OLD PATH
        // $value = $this->getLogoFromOldPath();
        // if (!empty($value)) {
        // 	return $value;
        // }

        // // NEW PATH
        // if (!isset($this->attributes) || !isset($this->attributes['logo'])) {
        // 	$value = config('larapen.core.picture.default');

        // 	return $value;
        // }

        $value = $this->attributes['logo'];

        // $disk = StorageDisk::getDisk();

        // if (!$disk->exists($value)) {
        // 	$value = config('larapen.core.picture.default');
        // }

        return $value;
    }

    public function City()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function Causes()
    {
        return $this->belongsTo(Causes::class);
    }

    public function Entities()
    {
        return $this->belongsTo(Entities::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_code');
    }

    public static function get_companies()
    {
        return Company::where('deleted_at', NULL)->get();
    }
}