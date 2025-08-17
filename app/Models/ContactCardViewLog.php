<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactCardViewLog extends Model
{
    use HasFactory;

    protected $table = 'contact_card_view_log';

    protected $fillable = ['user_id', 'company_id', 'description', 'created_at', 'updated_at'];

    public static function get_all_user_cv_viewd_count()
    {
        return ContactCardViewLog::where('user_id', auth()->user()->id)->count();
    }

}
