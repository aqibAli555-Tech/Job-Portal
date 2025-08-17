<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'availability';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

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
    protected $dates = ['created_at', 'updated_at'];

    public static function availability_name_by_id($id)
    {
        return Availability::select('name')->where('id', $id)->get()->first();
    }

}

