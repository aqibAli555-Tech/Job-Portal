<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    use HasFactory;

    protected $table = 'user_settings';
    protected $fillable = ['user_id', 'whatsapp_number', 'skills_set', 'is_verified', 'created_at', 'updated_at'];

    public static function getUserSetting()
    {
        return self::where('user_id', auth()->user()->id)->first();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function get_user_setting($request)
    {
        $limit = (!empty($request->get('limit')) ? $request->get('limit') : 30);
        $limit = (!empty($request->get('length')) ? $request->get('length') : $limit);
        $search = $request->search ?? '';

        $whatsApp_users = UserSetting::with('user')
        ->whereNotNull('whatsapp_number')
        ->where('whatsapp_number', '!=', '')
        ->when(!empty($search), function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('whatsapp_number', 'like', "%{$search}%")
                ->orWhere('id', 'like', "%{$search}%");
            })
            ->orWhereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        })
        ->orderBy('id', 'DESC');


        if (!empty($request->get('start'))) {
            return $whatsApp_users->skip($request->get('start'))->take($limit)->get();
        }
        $whatsApp_users = $whatsApp_users->paginate($limit)->appends(request()->query()); // Prevent memory overload
        return $whatsApp_users;
    }

    public static function user_setting_filter_count($request)
    {
        
        $search = $request->search ?? '';

        $whatsApp_users = UserSetting::with('user')
        ->whereNotNull('whatsapp_number')
        ->where('whatsapp_number', '!=', '')
        ->when(!empty($search), function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('whatsapp_number', 'like', "%{$search}%")
                ->orWhere('id', 'like', "%{$search}%");
            })
            ->orWhereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        });


        $whatsApp_users_filter_count = $whatsApp_users->get()->count();
        return $whatsApp_users_filter_count;
    }
}
