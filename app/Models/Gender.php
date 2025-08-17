<?php

namespace App\Models;

use App\Observers\GenderObserver;
use Larapen\Admin\app\Models\Traits\Crud;
use Larapen\Admin\app\Models\Traits\SpatieTranslatable\HasTranslations;

class Gender extends BaseModel
{
    use Crud, HasTranslations;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    // protected $primaryKey = 'id';
    public $translatable = ['name'];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gender';
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
    protected $fillable = ['translation_lang', 'translation_of', 'name'];
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
    protected $dates = [];
    protected $casts = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    protected static function boot()
    {
        parent::boot();

        Gender::observe(GenderObserver::class);
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
