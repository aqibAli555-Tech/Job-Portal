<?php

namespace App\Models;

use App\Helpers\Number;
use App\Models\Scopes\ActiveScope;
use App\Models\Scopes\LocalizedScope;
use App\Models\Traits\CountryTrait;
use App\Observers\CityObserver;
use Illuminate\Support\Facades\DB;
use Larapen\Admin\app\Models\Traits\Crud;
use Larapen\Admin\app\Models\Traits\SpatieTranslatable\HasTranslations;

class City extends BaseModel
{
    use Crud, CountryTrait, HasTranslations;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = true;
    public $translatable = ['name'];

    /**
     * The primary key for the model.
     *
     * @var string
     */

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cities';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    // protected $guarded = ['id'];

    protected $appends = ['slug'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['country_code', 'name', 'asciiname', 'longitude', 'latitude', 'feature_class', 'feature_code', 'subadmin1_code', 'subadmin2_code', 'population', 'time_zone', 'active'];
    /**
     * The attributes that should be hidden for arrays
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    protected $casts = [];


    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public static function name_by_login_user_city_code()
    {
        $cityData = City::select('name')->where('id', auth()->user()->city)->first();
        $cityName = '';
        if (!empty($cityData->name)) {
            $cityName = $cityData->name;
        }
        return $cityName;
    }

    public static function get_city_by_country_with_employee_count($country_code)
    {
        return self::select('cities.*', \DB::raw('IFNULL(count(users.employee_cv),Null) as count'))
            ->join('users', 'cities.id', '=', 'users.city', 'left')
            ->where('cities.country_code', $country_code)
            ->orderBy('count', 'desc')
            ->groupBy('cities.name')
            ->get();
    }

    public static function get_all_city_with_post_count()
    {

       $cities = DB::table('cities');
        $cities = $cities->select('cities.*', DB::raw('COALESCE(post_counts.post_count, 0) as post_count'));
        $country_code = (request()->get('country_code')) ? request()->get('country_code') : 'KW';
        $cities = $cities->where('cities.country_code', $country_code);
        $cities = $cities->leftJoin(
            DB::raw('(SELECT city_id, COUNT(post_id) as post_count
                    FROM posts_details
                    LEFT JOIN posts ON posts.id = posts_details.post_id
                    WHERE posts.is_post_expire = 0 AND posts.is_deleted = 0
                    GROUP BY city_id) as post_counts'),
            'cities.id',
            '=',
            'post_counts.city_id'
        );
        $cities = $cities->groupBy('cities.id');
        $cities = $cities->get();
        return $cities;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public static function get_post_country_city($post_id)
    {
        return City::where('country_code', $post_id)->get();
    }

    protected static function boot()
    {
        parent::boot();

        City::observe(CityObserver::class);

        static::addGlobalScope(new ActiveScope());
        static::addGlobalScope(new LocalizedScope());
    }

    public function getAdmin2Html()
    {
        $out = $this->subadmin2_code;

        if (isset($this->subAdmin2) && !empty($this->subAdmin2)) {
            $out = $this->subAdmin2->name;
        }

        return $out;
    }

    public function getAdmin1Html()
    {
        $out = $this->subadmin1_code;

        if (isset($this->subAdmin1) && !empty($this->subAdmin1)) {
            $out = $this->subAdmin1->name;
        }

        return $out;
    }

    public function posts()
    {
        return $this->hasMany(PostDetails::class, 'city_id');
    }

    public function subAdmin2()
    {
        return $this->belongsTo(SubAdmin2::class, 'subadmin2_code', 'code');
    }

    public function subAdmin1()
    {
        return $this->belongsTo(SubAdmin1::class, 'subadmin1_code', 'code');
    }

    public function getNameAttribute($value)
    {
        if (isset($this->attributes['name']) && !isValidJson($this->attributes['name'])) {
            return $this->attributes['name'];
        }
        return $value;
    }

    public function getSlugAttribute($value)
    {
        $value = (is_null($value) && isset($this->name)) ? $this->name : $value;

        $value = slugify($value);
        return $value;
    }

    /**
     * @param $value
     * @return float
     */
    public function getLatitudeAttribute($value)
    {
        return Number::toFloat($value);
    }

    /**
     * @param $value
     * @return float
     */
    public function getLongitudeAttribute($value)
    {
        return Number::toFloat($value);
    }

    public function Country()
    {
        return $this->belongsTo(Country::class, 'country_code', 'code');

    }
    public static function get_city_name_by_id($id){
        return self::where('id',$id)->first();
    }
}