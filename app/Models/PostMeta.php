<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostMeta extends Model
{
    use HasFactory;

    use HasFactory;

    protected $table = 'posts_meta';
    protected $primaryKey = 'id';

    protected $fillable = [
        'post_id',
        'visits',
        'reviewed',
        'hide_salary',
        'featured',
    ];


    public static function save_post_meta_request($request, $post_id)
    {
        $company = Company::where('id', $request->company_id)->first();
        if (empty($request->input('post_id'))) {
            $post_meta = new PostMeta();
        } else {
            $post_meta = PostMeta::where('post_id', $request->input('post_id'))->first();
        }
        $post_meta->post_id = $post_id;
        $post_meta->hide_salary = $request->get('hide_salary');
        $user_data = User::where('id', $company->c_id)->first();
        if (!empty($user_data)) {
            $post_meta->featured = 1;
            if ($user_data->package_id != 5) {
                $post_meta->reviewed = 1;
            } else {
                $post_meta->reviewed = 0;
            }
        }

        if ($post_meta->save()) {
            return true;
        } else {
            return false;
        }
    }

    public static function get_post_meta_by_post_id($post_id)
    {
        return PostMeta::where('post_id', $post_id)->first();
    }
}