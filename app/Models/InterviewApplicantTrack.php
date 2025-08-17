<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterviewApplicantTrack extends Model
{
    use HasFactory;

    protected $table = 'interview_applicant_track';
    protected $fillable = ['applicant_id', 'on_profile_check_date'];


    public function Applicant()
    {
        return $this->hasOne(Applicant::class, 'id', 'applicant_id');
    }

    public static function get_applicants_on_the_date_bases()
    {
        return InterviewApplicantTrack::with(['Applicant', 'Applicant.companyData', 'Applicant.User', 'Applicant.post'])
            ->where('updated_at', '<=', Carbon::now()->subDay(7))->get();
    }

    public static function get_applicants_on_the_profile_date_bases()
    {
        return InterviewApplicantTrack::with(['Applicant', 'Applicant.companyData', 'Applicant.User', 'Applicant.post'])
            ->where(function ($query) {
                $query->where('on_profile_check_date', '<=', Carbon::now()->subDay(7))
                    ->orWhereNull('on_profile_check_date');
            })->whereHas('Applicant', function ($query) {
                $query->where('to_user_id', auth()->user()->id);
            })->get();

    }

    public static function update_on_profile_seen_date($applicants)
    {
        foreach ($applicants as $applicant) {
            self::where('id', $applicant->id)->update(['on_profile_check_date' => date('Y-m-d H:i:s')]);
        }
    }
}
