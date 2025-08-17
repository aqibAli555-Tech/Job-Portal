<?php

namespace App\Http\Controllers\Ajax;

use App\Helpers\Helper;
use App\Helpers\UrlGen;
use App\Http\Controllers\FrontController;
use App\Models\Post;
use App\Models\SavedPost;
use App\Models\SavedSearch;
use App\Models\Scopes\ReviewedScope;
use App\Models\Scopes\VerifiedScope;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Larapen\TextToImage\Facades\TextToImage;

class PostController extends FrontController
{
    /**
     * PostController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function savePost(Request $request)
    {
        $postId = $request->get('postId');
        $status = 0;
        if (auth()->check()) {
            $savedPost = SavedPost::where('user_id', auth()->user()->id)->where('post_id', $postId);
            $user_id = auth()->user()->id;
            $post_data = Post::where('id', $postId)->first();
            $post_title = $post_data['title'];
            $user_name = auth()->user()->name;
            $user_profile = admin_url() . '/job-seekers?search=' . auth()->user()->email;
            $post_url = admin_url() . '/get_posts?status=&search=' . $post_title;
            if ($savedPost->count() > 0) {
                // Delete SavedPost
                $savedPost->delete();
            } else {
                // Store SavedPost
                $savedPostInfo = [
                    'user_id' => auth()->user()->id,
                    'post_id' => $postId,
                ];
                $savedPost = new SavedPost($savedPostInfo);
                $post_like = " A User <a href='$user_profile'>$user_name</a> add a Job into favorite: <a href='$post_url'>$post_title</a>";
                Helper::activity_log($post_like);

                $savedPost->save();
                $status = 1;
            }
        }

        $result = [
            'logged' => (auth()->check()) ? auth()->user()->id : 0,
            'postId' => $postId,
            'status' => $status,
            'loginUrl' => UrlGen::login(),
        ];

        return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function saveSearch(Request $request)
    {
        $queryUrl = $request->get('url');
        $tmp = parse_url($queryUrl);
        $query = $tmp['query'];
        parse_str($query, $tab);
        $keyword = $tab['q'];
        $countPosts = $request->get('countPosts');
        if ($keyword == '') {
            return response()->json([], 200, [], JSON_UNESCAPED_UNICODE);
        }

        $status = 0;
        if (auth()->check()) {
            $savedSearch = SavedSearch::where('user_id', auth()->user()->id)->where('keyword', $keyword)->where('query', $query);
            if ($savedSearch->count() > 0) {
                // Delete SavedSearch
                $savedSearch->delete();
            } else {
                // Store SavedSearch
                $savedSearchInfo = [
                    'country_code' => config('country.code'),
                    'user_id' => auth()->user()->id,
                    'keyword' => $keyword,
                    'query' => $query,
                    'count' => $countPosts,
                ];
                $savedSearch = new SavedSearch($savedSearchInfo);
                $savedSearch->save();
                $status = 1;
            }
        }

        $result = [
            'logged' => (auth()->check()) ? auth()->user()->id : 0,
            'query' => $query,
            'status' => $status,
            'loginUrl' => UrlGen::login(),
        ];

        return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getPhone(Request $request)
    {
        $postId = $request->get('postId', 0);

        $post = Post::withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])->where('id', $postId)->first(['phone']);

        if (empty($post)) {
            return response()->json(['error' => ['message' => t("Error. Post doesn't exist.")], 404]);
        }

        $phone = $post->phone;
        $phoneLink = 'tel:' . $post->phone;

        if (config('settings.single.convert_phone_number_to_img')) {
            try {
                $phone = TextToImage::make($post->phone, config('larapen.core.textToImage'));
            } catch (Exception $e) {
                $phone = $post->phone;
            }
        }

        $data = [
            'phone' => $phone,
            'link' => $phoneLink,
        ];

        return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
    }
}
