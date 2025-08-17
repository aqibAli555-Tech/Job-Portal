<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PageCount extends Model
{
    use HasFactory;
    protected $table = 'page_count';
    protected $fillable = ['from', 'page', 'datetime', 'user_id', 'request', 'server'];

    public static function get_all_page_counts()
    {
        $pages = [
            'facebook_link_click', 'twitter_link_click', 'instagram_link_click',
            'tiktok_link_click', 'linkedin_link_click', 'pinterest_link_click',
            'contact_us', 'faq', 'privacy', 'terms', 'home_page',
            'logo_click', 'register_page', 'login_users', 'user_registered',
            'search_cv', 'search_jobs', 'post_details', 'applied_users'
        ];

        return self::select('page')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereIn('page', $pages)
            ->groupBy('page', 'date')
            ->orderBy('date', 'desc')
            ->get();
    }


    public static function get_search_page_count($page)
    {
          // Assuming $tenDaysAgo is already defined

        $dailyCounts = self::where('page', $page)  // Filter by the 'page' parameter
            ->groupBy(DB::raw('DATE(created_at)'), 'query_parameter', 'page')
             ->orderBy('created_at','desc')
            ->select('query_parameter', 'page', DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->take(5)
            ->get();
        return $dailyCounts;
    }
    public static function get_all_search_page_count($type){
        $limit = request()->get('limit')??50;
        $dailyCounts = self::where('page',$type)  // Filter by the 'page' parameter
        ->groupBy(DB::raw('DATE(created_at)'), 'query_parameter', 'page')
         ->orderBy('created_at','desc')
        ->select('query_parameter', 'page', DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
        ->paginate($limit)->appends(request()->query());
        return $dailyCounts;
    }

    public static function get_all_search_page_count_data($type){
        $limit = request()->get('limit')??50;
        $dailyCounts = self::where('page',$type)  // Filter by the 'page' parameter
        ->groupBy(DB::raw('DATE(created_at)'), 'query_parameter', 'page')
         ->orderBy('created_at','desc')
        ->select('query_parameter', 'page', DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
        ->count();
        return $dailyCounts;
    }
}
