<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Allsaved_resume extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'allsaved_resumes';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'user_id', 'resume_id', 'applicant_id'];

    protected $dates = ['deleted_at', 'deleted_at', 'archived_at', 'deletion_mail_sent_at', 'deleted_at'];

    public static function get_Allsaved_resume_by_applicant_id($id)
    {
        return Allsaved_resume::where('user_id', auth()->user()->id)->where('applicant_id', $id)->first();
    }

    public static function get_all_save_cv_count()
    {
        return Allsaved_resume::where('user_id', auth()->user()->id)->count();
    }

    public static function get_all_saved_cv()
    {
        return Allsaved_resume::with(['user', 'user.cityData', 'user.country','UnlockContact'])->where('user_id', auth()->user()->id)->get();
    }

    public function Applicant()
    {
        return $this->belongsTo(Applicant::class);
    }

    public function Resume()
    {
        return $this->belongsTo(Resume::class);
    }

    public function User()
    {
        return $this->belongsTo(User::class, 'applicant_id');
    }
    public function UnlockContact()
    {
        return $this->hasOne(Unlock::class,'to_user_id','user_id');
    }

}