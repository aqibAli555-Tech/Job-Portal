<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nationality extends Model
{


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    protected $table = 'nationality';

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

    public static function get_Nationality_by_id($id)
    {
        return Nationality::where('id', $id)->first();
    }
    public static function get_all_nationality_employee_count($type)
    {
        return self::select('nationality.*', \DB::raw('IFNULL(count(users.id),Null) as count'))->join('users', 'nationality.id', '=', 'users.nationality', 'left')->where('users.user_type_id', $type)->orderBy('count', 'desc')->orderBy('nationality.name', 'asc')->groupBy('nationality.name')->groupBy('nationality.id')->get();
    }
    public static function get_nationalities(){
        return self::all();
    }


    public static function get_nationalities_with_employee_count()
    {
        return Nationality::
        select('nationality.id', 'nationality.name', \DB::raw('IFNULL(count(users.id), 0) as count'),)
            ->leftJoin('users', function ($join) {
                $join->on('nationality.id', '=', 'users.nationality')
                    ->where('users.user_type_id', 2)
                    ->where('users.employee_cv','!=', '');
            })
            ->groupBy('nationality.id')
            ->orderByDesc('count')
            ->orderBy('nationality.name')
            ->get();

    }

    public static function get_nationality_name_array($ids){
        return self::whereIn('id', $ids)->pluck('name'); 
    }

}




