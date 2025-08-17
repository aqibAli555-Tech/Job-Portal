<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\FrontController;
use App\Http\Controllers\Post\Traits\CatBreadcrumbTrait;
use App\Models\Package;
use App\Models\Permission;
use App\Models\Post;
use App\Models\PostMeta;
use App\Models\Scopes\ReviewedScope;
use App\Models\Scopes\VerifiedScope;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Torann\LaravelMetaTags\Facades\MetaTag;

class DetailsController extends FrontController
{
    use CatBreadcrumbTrait;

    /**
     * Post expire time (in months)
     *
     * @var int
     */
    public $expireTime = 24;

    /**
     * DetailsController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware(function ($request, $next) {
            $this->commonQueries();

            return $next($request);
        });
    }

    /**
     * Common Queries
     */
    public function commonQueries()
    {
        // Count Packages
        $countPackages = Package::applyCurrency()->count();
        view()->share('countPackages', $countPackages);

        // Count Payment Methods
        view()->share('countPaymentMethods', $this->countPaymentMethods);
    }

    /**
     * Show Post's Details.
     *
     * @param $postId
     * @return Factory|View
     */
    public function index($postId)
    {
        $data = [];
        $parameters = request()->route()->parameters();
        $postId = $parameters['id'] ?? null;
        $slug = $parameters['slug'] ?? null;

        if (!isset($postId) || empty($postId) || !is_numeric($postId)) {
            abort(404);
        }

        $query = Post::withCountryFix()
            ->where('id', $postId)
            ->with([
                'category' => function ($builder) {
                    $builder->with(['parent']);
                },
                'postType',
                'latestPayment' => function ($builder) {
                    $builder->with(['package']);
                },
                'company',
                'savedByLoggedUser',
                'employeeskill',
                'postDetail',
                'postDetail.city',
                'postMeta',
            ]);

        if (auth()->check() && !auth()->user()->can(Permission::getStaffPermissions())) {
            $query->withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])
                ->where('user_id', auth()->user()->id);
        }
        $post = $query->first();
      


        if (empty($post) || empty($post->category_id) || empty($post->postType) || empty($post->postDetail->city)) {
            abort(404, t('Post not found'));
        }

         view()->share('post', $post);
         
        if(empty(auth()->user()->id) || auth()->user()->id != $post->user_id ){
            if (isset($post->archived) && $post->archived == 1) {
            flash(t('This post is not acitve'))->error();
            return redirect('/');
        }
        }

        if (isset($post->archived) && $post->archived == 1) {
            flash(t('This ad has been archived'))->warning();
        }

        $user = User::find($post->user_id);
        view()->share('user', $user);

        $commentsAreDisabledByUser = $user->disable_comments == 1 ?? false;
        view()->share('commentsAreDisabledByUser', $commentsAreDisabledByUser);

        $catBreadcrumb = $this->getCatBreadcrumb($post->category_id, 1);
        view()->share('catBreadcrumb', $catBreadcrumb);

        $post_meta = PostMeta::where('post_id', $post->id)->first();
        $post_meta->visits = $post->postMeta->visits + 1;
        $post_meta->save();

        $data['post'] = $post;
        $title = $post->title . ', ' . $post->postDetail->city->name;
        $description = Str::limit(str_strip(strip_tags($post->description)), 200);
        MetaTag::set('title', $title);
        MetaTag::set('description', $description);

        if (!empty($post->tags)) {
            MetaTag::set('keywords', str_replace(',', ', ', $post->tags));
        }

        $this->og->title($title)
            ->description($description)
            ->type('article');

        if (isset($post->logo) && !empty($post->logo)) {
            $this->og->image(imgUrl($post->logo, 'company'), [
                'width' => 600,
                'height' => 600,
            ]);
        }

        // Meta Tags
        view()->share([
            'title' => $post->title,
            'description' => $post->title . ',' . $post->company_name . ',' . $post->employeeskill->skill . ',' . $description,
            // 'description' => $post->description,
            'keywords' => $post->title . ',' . $post->company_name . ',' . $post->employeeskill->skill,
            // Add more variables as needed
        ]);

        view()->share('og', $this->og);
        
        return appView('post.details', $data);
    }
}
