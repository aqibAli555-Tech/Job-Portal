<?php

namespace App\Models;

use App\Models\Scopes\ActiveScope;
use App\Observers\MetaTagObserver;
use Larapen\Admin\app\Models\Traits\Crud;
use Larapen\Admin\app\Models\Traits\SpatieTranslatable\HasTranslations;

class MetaTag extends BaseModel
{
    use Crud, HasTranslations;


    private static $defaultPages = [
        'home' => 'Homepage',
        'register' => 'Register',
        'login' => 'Login',
        'create' => 'Ads Creation',
        'countries' => 'Countries',
        'contact' => 'Contact',
        'sitemap' => 'Sitemap',
        'password' => 'Password',
        'pricing' => 'Pricing',
    ];

    /**
     * The primary key for the model.
     *
     * @var string
     */
    // protected $primaryKey = 'id';
    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = false;
    public $translatable = ['title', 'description', 'keywords'];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'meta_tags';
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

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
    // protected $dates = [];

    // Default Pages    /**
    //  * The attributes that are mass assignable.
    //  *
    //  * @var array
    //  */
    protected $fillable = ['page', 'title', 'description', 'keywords', 'active'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    protected static function boot()
    {
        parent::boot();

        MetaTag::observe(MetaTagObserver::class);

        static::addGlobalScope(new ActiveScope());
    }

    public function getPageHtml()
    {
        $entries = self::getDefaultPages();

        // Get Page Name
        $out = $this->page;
        if (isset($entries[$this->page])) {
            $url = admin_url('meta_tags/' . $this->id . '/edit');
            $out = '<a href="' . $url . '">' . $entries[$this->page] . '</a>';
        }

        return $out;
    }

    public static function getDefaultPages()
    {
        return self::$defaultPages;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
