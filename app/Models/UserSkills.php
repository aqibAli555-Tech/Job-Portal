<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSkills extends Model
{
    use HasFactory;

    protected $table = 'user_skills';
    protected $fillable = ['user_id', 'is_read', 'old_skills', 'new_skills', 'created_at', 'updated_at'];

    public static function create($old_skill, $new_skills)
    {
        $userskill = UserSkills::check_new_user_skills(auth()->user()->id);
        if (empty($userskill)) {
            $userskill = new self();
        }
        $userskill->old_skills = $old_skill;
        $userskill->new_skills = $new_skills;
        $userskill->user_id = auth()->user()->id;
        return $userskill->save();
    }

    public static function update_read_status()
    {
      return  self::where('is_read', 0)->update(['is_read' => 1]);
    }

    public static function get_not_Read_user_skill($user_id)
    {
        return self::where('user_id', $user_id)->where('is_read', 0)->first();
    }

    public static function check_new_user_skills($user_id)
    {
        return self::where('user_id', $user_id)->where('is_read', 0)->orderBy('id', 'DESC')->first();
    }

    public static function get_all_unapproved_skill()
    {
        return self::where('is_read', 0)->get();
    }

}
