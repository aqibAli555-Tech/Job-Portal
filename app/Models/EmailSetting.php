<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailSetting extends Model
{
    use HasFactory;

    protected $table = 'email_settings';

    protected $fillable = ['id', 'email', 'key', 'status', 'check_email_api_key', 'check_email_status'];
}
