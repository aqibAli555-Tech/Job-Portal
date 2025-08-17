<?php

namespace App\Models;


use App\Observers\BlacklistObserver;
use Larapen\Admin\app\Models\Traits\Crud;

class Blacklist extends BaseModel
{
    use Crud;

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
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'blacklist';
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
    protected $fillable = ['type', 'entry'];

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

        Blacklist::observe(BlacklistObserver::class);
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
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

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
