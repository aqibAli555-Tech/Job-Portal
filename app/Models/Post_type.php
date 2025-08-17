<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post_type extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'post_types';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['translation_lang', 'translation_of', 'name', 'lft', 'rgt', 'depth', 'active'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'deleted_at'];

}