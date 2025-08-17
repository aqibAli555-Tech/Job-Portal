<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notification';
    protected $fillable = ['type', 'user_id', 'is_read'];

    public static function get_notification_data()
    {
        return Notification::where('user_id', auth()->user()->id)->where('is_read', 0)->get();
    }


    public static function get_notification_by_type($type){
        return Notification::where('user_id', auth()->user()->id)->where('type', $type)->where('is_read', 0)->get();

    }
    public static function get_notification_applicants()
    {
        return Notification::where('user_id', auth()->user()->id)->where('type', 'applicants')->where('is_read', 0)->get();
    }

    public static function get_notification_messages()
    {
        return Notification::where('user_id', auth()->user()->id)->where('type', 'message')->where('is_read', 0)->get();
    }

    public static function add_new_notification($user_id, $type, $Is_Read = 0)
    {
        $notifcaiton = new Notification();
        $notifcaiton->user_id = $user_id;
        $notifcaiton->type = $type;
        $notifcaiton->is_read = $Is_Read;
        return  $notifcaiton->save();
    }
    public static function update_read_status($type){
        return Notification::where('user_id', auth()->user()->id)->where('type', $type)->update(['is_read' => 1]);

    }
}
