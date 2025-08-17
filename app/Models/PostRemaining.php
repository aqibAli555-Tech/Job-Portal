<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostRemaining extends Model
{
    use HasFactory;
    protected $table = 'post_remaining';
    protected $fillable = ['post_id', 'employer_id', 'package_id', 'is_post_expire', 'post_expire_date_time'];
   
    public static function check_total_post_used($employer_id){
        return PostRemaining::where('post_remaining.employer_id', $employer_id)
            ->where('post_remaining.is_post_expire', 0)
            ->join('company_packages', 'post_remaining.company_package_id', '=', 'company_packages.id')
            ->join('posts', 'posts.id', '=', 'post_remaining.post_id')
            ->where('company_packages.is_package_expire', 0)
            ->where('posts.is_deleted', 0)
            ->count();
    }
    public static function get_post_by_employer_id_and_post_id($employer_id,$post_id){
        return PostRemaining::where('employer_id', $employer_id)->where('is_post_expire',0)
                                                ->where('post_id', $post_id)
                                                ->orderBy('id', 'desc')
                                                ->first();
    }
    public static function expire_package_posts($employer_id){
        return PostRemaining::where('post_remaining.employer_id', $employer_id)
            ->where('post_remaining.is_post_expire', 0)
            ->join('company_packages', 'post_remaining.company_package_id', '=', 'company_packages.id')
            ->where('company_packages.is_package_expire', 1)
            ->count();
    }
    
  
   
}
