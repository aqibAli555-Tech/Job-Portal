<?php

namespace App\Models;


use App\Observers\ContinentObserver;
use Larapen\Admin\app\Models\Traits\Crud;

class Continent extends BaseModel
{
    use Crud;

    public $incrementing = false;
    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = false;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'continents';
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'code';
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
    protected $fillable = ['code', 'name', 'active'];

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

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    protected static function boot()
    {
        parent::boot();

        Continent::observe(ContinentObserver::class);
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
