<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TapLog extends Model
{
    use HasFactory;

    protected $table = 'tap_logs';

    protected $fillable = ['user_id', 'url', 'method', 'title', 'request', 'response', 'function_name', 'header_response_code'];
}

