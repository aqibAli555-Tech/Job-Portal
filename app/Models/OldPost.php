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
use Illuminate\Support\Str;
use Larapen\Admin\app\Models\Traits\Crud;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;

class OldPost extends BaseModel implements Feedable
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
    protected $table = 'old_posts';
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
        'tags',
        'salary_min',
        'salary_max',
        'salary_type_id',
        'negotiable',
        'start_date',
        'application_url',
        'contact_name',
        'email',
        'phone',
        'phone_hidden',
        'city_id',
        'lat',
        'lon',
        'address',
        'ip_addr',
        'visits',
        'tmp_token',
        'email_token',
        'phone_token',
        'verified_email',
        'verified_phone',
        'accept_terms',
        'accept_marketing_offers',
        'reviewed',
        'featured',
        'archived',
        'archived_at',
        'deletion_mail_sent_at',
        'partner',
        'created_at',
        'hide_salary',
        'transportation_available',
        'overtime_pay',
        'housing_available',
        'gender',
        'experiences',
        'nationality',
        'is_post_expire',
        'Hide_company_logo',
        'Hide_company_name',
        'who_can_apply',
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
            $applicants_count = Applicant::where(['post_id' => $id])->count();
        } else {
            $applicants_count = 0;
        }
        return $applicants_count;
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

    public function getLogoHtml()
    {
        $style = 'style="max-width:116px; max-height:90px;"';
        // Get logo
        $logo = url('/public/storage/') . '/' . $this->attributes['logo'];
//        $logo=imgUrl($this->attributes['logo'], 'small');
        $out = '<img src="' . $logo . '" data-toggle="tooltip" title="' . $this->title . '"' . $style . '>';
        // Add link to the Ad
        $url = dmUrl($this->country_code, UrlGen::postPath($this));
        $out = '<a href="' . $url . '" target="_blank">' . $out . '</a>';

        return $out;
    }

    public function getPictureHtml()
    {
        // Get ad URL
        $url = url(UrlGen::postUri($this));

        $style = ' style="width:auto; max-height:90px;"';
        // Get first picture
        if ($this->pictures->count() > 0) {
            foreach ($this->pictures as $picture) {
                $url = dmUrl($picture->post->country_code, UrlGen::postPath($this));
                $out = '<img src="' . imgUrl($picture->filename, 'small') . '" data-toggle="tooltip" title="' . $this->title . '"' . $style . ' class="img-rounded">';
                break;
            }
        } else {
            // Default picture
            $out = '<img src="' . imgUrl(config('larapen.core.picture.default'), 'small') . '" data-toggle="tooltip" title="' . $this->title . '"' . $style . ' class="img-rounded">';
        }

        // Add link to the Ad
        $out = '<a href="' . $url . '" target="_blank">' . $out . '</a>';

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
    public function postType()
    {
        return $this->belongsTo(PostType::class, 'post_type_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
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

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeVerified($builder)
    {
        $builder->where(function ($query) {
            $query->where('verified_email', 1)->where('verified_phone', 1);
        });

        if (config('settings.single.posts_review_activation')) {
            $builder->where('reviewed', 1);
        }

        return $builder;
    }

    public function scopeUnverified($builder)
    {
        $builder->where(function ($query) {
            $query->where('verified_email', 0)->orWhere('verified_phone', 0);
        });

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

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
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

}
