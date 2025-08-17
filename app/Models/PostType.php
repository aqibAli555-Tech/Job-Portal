<?php

namespace App\Models;

use App\Models\Scopes\ActiveScope;
use App\Observers\PostTypeObserver;
use Larapen\Admin\app\Models\Traits\Crud;
use Larapen\Admin\app\Models\Traits\SpatieTranslatable\HasTranslations;

class PostType extends BaseModel
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
    protected $table = 'post_types';
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
    protected $fillable = ['name', 'active'];

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

        PostType::observe(PostTypeObserver::class);

        static::addGlobalScope(new ActiveScope());
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function posts()
    {
        return $this->hasMany(Post::class, 'post_type_id');
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

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public static function get_post_type_name_by_id($id){
        return self::where('id',$id)->pluck('name')->first();
    }


}
