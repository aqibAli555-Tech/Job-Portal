<?php

namespace App\Models;

use App\Models\Scopes\ActiveScope;
use Larapen\Admin\app\Models\Traits\Crud;

class UserType extends BaseModel
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
    protected $table = 'user_types';
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
    protected $fillable = ['name'];

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

        static::addGlobalScope(new ActiveScope());
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function users()
    {
        return $this->hasMany(User::class, 'user_type_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeActive($builder)
    {
        return $builder->where('active', 1);
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
