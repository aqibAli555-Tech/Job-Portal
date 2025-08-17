<?php

namespace App\Models;

use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagelog extends Model
{
    use HasFactory;

    protected $table = 'page_logs';

    protected $fillable = ['route', 'method', 'session_id', 'referrer', 'created_time', 'userAgent', 'userIP', 'isAjax', 'data', 'session_data'];

    public static function get_pagelog()
    {
        $date = Carbon::today()->subDays(5);
        $pagelog = new self();
        $pagelog = $pagelog->select(DB::raw('COUNT(userAgent) as totalRequest'), 'userAgent', 'created_time')->where('created_time', '>=', $date)->groupBy('userAgent')->havingRaw('count(userAgent) > 20')->orderBy('created_time', 'DESC')->get();
        return $pagelog;
    }

    public static function delete_log_older_by_limit()
    {
        $date = Carbon::today()->subDays(30);
        $pagelog = new self();
        $pagelog = $pagelog->whereDate('created_time', '<', $date)->limit(50);
        return $pagelog->delete();
    }
}
