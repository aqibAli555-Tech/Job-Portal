<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\EmployeeSkill;
use App\Models\Post;
use App\Models\User;

class HomeController extends Controller
{
    public function Homepage()
    {
        $data['skills'] = EmployeeSkill::get_All_skill_With_Post_Count();
        $data['featured_companies'] = User::get_all_featured_companies();
        $orderBy = 'id,desc';
        $posts = Post::getLatestOrSponsored(8, 'latest', $orderBy);
        $data['latest_post'] = [];
        if (!empty($posts)) {
            foreach ($posts as $key => $post) {
                $post_data = Post::get_formated_post($post);
                $data['latest_post'][$key] = $post_data;
            }
        }

        return response()->json([
            'message' => 'Data Fetched Successfully',
            'data' => $data
        ], 200);
    }

}
