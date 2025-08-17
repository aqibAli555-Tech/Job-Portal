<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unlock extends Model
{
    use HasFactory;

    protected $table = 'unlock_user';

    protected $fillable = ['user_id', 'to_user_id', 'is_unlock', 'post_id', 'expire_date'];

    public static function check_user_profile_unlocked($user_id, $to_user_id)
    {
        return Unlock::where("user_id", $user_id)->where("to_user_id", $to_user_id)->where('is_unlock', 1)->first();
    }

    public static function get_unlocked_by_user_id($id)
    {
        return Unlock::where('user_id', $id)->where('to_user_id', auth()->user()->id)->where('is_unlock', 1)->first();
    }

    public static function get_all_unlock_contact_count()
    {
        return Unlock::where('to_user_id', auth()->user()->id)->where('is_unlock', 1)->count();
    }

    public static function total_unlock()
    {
        return Unlock::count();
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public function Post()
    {
        return $this->belongsTo(Post::class);
    }

    public function postDetail()
    {
        return $this->belongsTo(PostDetails::class);
    }
    
    public static function get_unlock_contact_count()
    {
        return Unlock::where('unlock_user.to_user_id', auth()->user()->id)->where('unlock_user.is_unlock', 1)->get();
    }
    public static function get_unlock_contact_card($request){
        $data = Unlock::with(['user'])
            ->where('to_user_id', auth()->user()->id)
            ->where('is_unlock', '1')
            ->orderBy('id', 'DESC')
            ->whereHas('user.applicant', function ($query) use ($request) {
                if(!empty($request->get('post_id'))){
                    $query->where('post_id', '=', $request->get('post_id'));
                }
                if(!empty($request->get('status'))) {
                    $query->where('status', '=', $request->get('status'));
                }
            })
            ->get();


        return $data;
    }

}
