<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    use HasFactory;

    protected $table = 'statistics';
    protected $fillable = ['employees', 'companies', 'jobs'];

    public static function get_home_page_statistic()
    {
        return self::where('id', 1)->first();
    }
}


