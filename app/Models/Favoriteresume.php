<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favoriteresume extends Model
{
    use HasFactory;

    protected $table = 'favorite_resume';

    protected $fillable = ['user_id', 'resume_id'];

    public static function get_Favoriteresume_by_user_id_and_company_id($id)
    {

        return Favoriteresume::where('company_id', auth()->user()->id)->where('user_id', $id)->first();
    }

    public static function get_fav_cv_count()
    {
        return Favoriteresume::where('company_id', auth()->user()->id)->count();
    }

    public static function get_favorter_cv()
    {
        return Favoriteresume::with(['user', 'user.cityData', 'user.country'])->where('company_id', auth()->user()->id)->get();
    }

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


}
