<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionalSelectedEmails extends Model
{
    use HasFactory;

    protected $table = 'optional_selected_emails';
    protected $fillable = ['user_id', 'email_id'];


    public static function get_selected_emails($user_id)
    {
        return OptionalSelectedEmails::where('user_id', $user_id)->get();
    }

    public static function update_selection($optional_emails, $user_id)
    {
        self::delete_old_selected_emails($user_id);
        $data = collect($optional_emails)->map(function ($email) use ($user_id) {
            return [
                'user_id' => $user_id,
                'email_id' => $email,
            ];
        })->toArray();
        return OptionalSelectedEmails::insert($data);
    }

    public static function delete_old_selected_emails($user_id)
    {
        return OptionalSelectedEmails::where('user_id', $user_id)->delete();
    }

    public static function check_selected_email($email_id, $user_id)
    {
        return self::where(['user_id' => $user_id, 'email_id' => $email_id])->first();
    }
}
