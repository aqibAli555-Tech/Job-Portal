<?php

namespace App\Http\Controllers\Account;

use App\Helpers\ArrayHelper;
use App\Helpers\Date;
use App\Helpers\Helper;
use App\Helpers\UrlGen;
use App\Http\Controllers\Search\Traits\LocationTrait;
use App\Models\CompanyPackages;
use App\Models\CompanyPostArchivedReason;
use App\Models\OptionalSelectedEmails;
use App\Models\PostRemaining;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostArchivedOrCancleReason;
use App\Models\SavedPost;
use App\Models\SavedSearch;
use App\Models\Scopes\ReviewedScope;
use App\Models\Scopes\VerifiedScope;
use DB;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Torann\LaravelMetaTags\Facades\MetaTag;

class PostsController extends AccountBaseController
{
    use LocationTrait;

    private $perPage = 12;

    public function __construct()
    {
        parent::__construct();
        $this->perPage = 20;
    }

    public function getPage($pagePath)
    {

        $get_all_reasons = PostArchivedOrCancleReason::get_all_reasons();

        $pageData = $this->getView($pagePath);
        $post_count=Post::get_all_post_post_count_by_employer_id();
        $archived_post_count=Post::get_all_archive_post_count();

        view()->share([
            'title' => $pageData['title'],
            'description' => $pageData['description'],
            'keywords' => $pageData['keywords'],
        ]);
        return appView('account.post.posts', compact('pagePath', 'get_all_reasons','post_count','archived_post_count'));
    }

    public function getView($pagePath)
    {
        switch ($pagePath) {
            case 'my-posts':
                $data['title'] = t('My ads');
                $data['description'] = t('My ads');
                $data['keywords'] = t('My ads');
                return $data;
                break;
            case 'archived':

                $data['title'] = t('Archived Jobs');
                $data['description'] = t('Archived Jobs');
                $data['keywords'] = t('Archived Jobs');
                return $data;
                break;
            case 'favourite':
                $data['title'] = t('My favourite jobs');
                $data['description'] = t('My favourite jobs');
                $data['keywords'] = t('My favourite jobs');
                return $data;
                break;
            default:
                abort(404);
        }
    }

//        public function getPage($pagePath)
//    {
//        
//        view()->share('pagePath', $pagePath);
//
//        switch ($pagePath) {
//
//            case 'my-posts':
//                return $this->getMyPosts();
//                break;
//            case 'archived':
//
//                return $this->getArchivedPosts($pagePath);
//                break;
//            case 'favourite':
//                return $this->getFavouritePosts();
//                break;
//            case 'pending-approval':
//                return $this->getPendingApprovalPosts();
//                break;
//            default:
//                abort(404);
//        }
//    }


    public function getMyPosts($postId = null)
    {
        if (!Helper::check_permission(3)) {
            return response()->json(['status' => true, 'message' => t("You do not have permission to access this module")]);
        }

        if (auth()->user()->user_type_id == 2) {
            return response()->json(['status' => true, 'message' => t("Permission Error..")]);
        }

        if (auth()->user()->user_type_id == 1 && empty(auth()->user()->parent_id)) {
            return response()->json(['status' => true, 'message' => t("Please change account to child company to view this page.")]);
        }
        $data = [];
        $data['posts'] = $this->myPosts->paginate($this->perPage);
        $data['posts'] = Helper::getPostAllApplicants($data['posts']);


        $data['type'] = 'posts';
        $response = appView('account.post.inc.post_table', $data)->render();
        return response()->json(['status' => true, 'html' => $response]);
    }


    public function getArchivedPosts()
    {
        if (!Helper::check_permission(3)) {
            return response()->json(['status' => true, 'message' => t("You do not have permission to access this module")]);
        }

        if (auth()->user()->user_type_id == 2) {
            return response()->json(['status' => true, 'message' => t("Permission Error..")]);
        }

        if (auth()->user()->user_type_id == 1 && empty(auth()->user()->parent_id)) {
            return response()->json(['status' => true, 'message' => t("Please change account to child company to view this page.")]);
        }

        $data = [];
        $data['posts'] = $this->archivedPosts->paginate($this->perPage);
        $data['posts'] = Helper::getPostAllApplicants($data['posts']);
        $data['type'] = 'archived_posts';
        $response = appView('account.post.inc.post_table', $data)->render();
        return response()->json(['status' => true, 'html' => $response]);
    }

    public function getFavouritePosts()
    {
        if (!Helper::check_permission(3)) {
            return response()->json(['status' => true, 'message' => t("You do not have permission to access this module")]);
        }

        if (auth()->user()->user_type_id == 1) {
            return response()->json(['status' => true, 'message' => t("Permission Error..")]);
        }

        $data = [];
        $data['posts'] = $this->favouritePosts->paginate($this->perPage);
        $response = appView('account.post.inc.favourite', $data)->render();
        return response()->json(['status' => true, 'html' => $response]);
    }


    public function repost($postId)
    {
        $post = null;
        if (is_numeric($postId) && $postId > 0) {
            $currentDate = Carbon::now();
            $date = $currentDate->format('Y-m-d H:i:s');
            $post = Post::where('user_id', auth()->user()->id)->where('id', $postId)->first();
            if (empty($post)) {
                return response()->json(['status' => false, 'message' => t('Post not found')]);
            }

            $check_post_expire = PostRemaining::where('employer_id', auth()->user()->id)->where('post_id', $postId)->where('is_post_expire', 0)->first();
            if (!empty($check_post_expire->package_expire_date) && $check_post_expire->package_expire_date < $date) {
                $post_count = CompanyPackages::check_post_available(auth()->user()->id);
                if ($post_count < 0) {
                    return response()->json(['status' => false, 'message' => t('You have reached the maximum amount of Contact'),'redirect'=> true]);
                }
            }

            if ($post->is_post_expire == 1 || $post->archived == 1) {
                $post->archived = 0;
                $post->archived_at = null;
                $post->is_active = 1;
                $post->is_post_expire = 0;
                $post->created_at = date('Y-m-d');
                $post->save();
                $cc = '';
                if (OptionalSelectedEmails::check_selected_email(3, auth()->user()->id)) {
                    $cc = auth()->user()->optional_emails;
                }
                $this->sendrepostemail($post, $cc);
                if (!empty($check_post_expire->package_expire_date) && $check_post_expire->package_expire_date > $date) {
                    $data['name'] = $post->title;
                    $data['post_url'] = UrlGen::post($post);
                    $companyDescription = Helper::companyDescriptionData($data, 'job_post_repost');
                    if(!empty($companyDescription)){
                        Helper::activity_log($companyDescription,auth()->user()->id);
                    }
                    return response()->json(['status' => true, 'message' => t('The repost has done successfully')]);
                } else {
                    $user_data = User::where('id', $post->user_id)->first();
                    Helper::update_remaining_post($user_data, $post->id);
                    $data['name'] = $post->title;
                    $data['post_url'] = UrlGen::post($post);
                    $companyDescription = Helper::companyDescriptionData($data, 'job_post_repost');
                    if(!empty($companyDescription)){
                        Helper::activity_log($companyDescription,auth()->user()->id);
                    }
                    return response()->json(['status' => true, 'message' => t('The repost has done successfully')]);
                }
            } else {
                return response()->json(['status' => false, 'message' => t('The ad is already online')]);
            }
        } else {
            return response()->json(['status' => false, 'message' => "The repost has failed"]);
        }
    }

    public
    function sendrepostemail($post, $cc = null)
    {
        $data['email'] = $post->email;
        $data['subject'] = 'Job Post Successfully Republished';
        $data['myName'] = $post->company_name;
        $data['url'] = UrlGen::post($post);
        $data['view'] = 'emails.job_republished';
        $data['header'] = 'Job Post Republished';
        $data['cc'] = $cc;
        $helper = new Helper();
        $response = $helper->send_email($data);
    }



    public
    function getPendingApprovalPosts()
    {
        if (!Helper::check_permission(3)) {
            flash(t("You do not have permission to access this module"))->error();
            return redirect()->back();
        }
        if (auth()->user()->user_type_id == 2) {
            flash(t("Permission Error.."))->error();
            return redirect('/');
        }
        if (auth()->user()->user_type_id == 1 && empty(auth()->user()->parent_id)) {
            flash(t("Please change account to child company to view this page."))->error();
            return redirect('/');
        }
        $data = [];
        $data['posts'] = $this->pendingPosts->paginate($this->perPage);
        // Meta Tags
        MetaTag::set('title', t('My pending approval ads'));
        MetaTag::set('description', t('My pending approval ads on', ['appName' => config('settings.app.app_name')]));
        return appView('account.posts', $data);
    }

    public
    function destroy($id)
    {
        if (!Helper::check_permission(3)) {
            return response()->json(['status' => false, 'message' => t("You do not have permission to access this module")]);
        }
        $pagePath = 'posts';
        $nb = 0;
        if ($pagePath == 'favourite') {
            $savedPosts = SavedPost::where('user_id', auth()->user()->id)->whereIn('post_id', $id);
            if ($savedPosts->count() > 0) {
                $nb = $savedPosts->delete();
            }
        } else if ($pagePath == 'saved-search') {
            $nb = SavedSearch::destroy($id);
        } else {
            $post = Post::withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])->where('user_id', auth()->user()->id)
                ->where('id', $id)
                ->first();
            if (!empty($post)) {
                $post->is_deleted = '1';

                if ($post->save()) {
                    $data['name'] = $post->title;
                    $companyDescription = Helper::companyDescriptionData($data, 'job_post_delete');
                    if(!empty($companyDescription)){
                        Helper::activity_log($companyDescription,auth()->user()->id);
                    }
                    return response()->json(['status' => true, 'message' => 'Post Deleted Successfully']);
                } else {
                    return response()->json(['status' => false, 'message' => t("No deletion is done")]);

                }
            }
        }
    }

    public
    function add_reason_for_post_archived_or_delete(Request $request)
    {
        if (!empty($request->postId) && !empty($request->reason_id)) {
            $data['post_id'] = $request->postId;
            $data['reason_id'] = $request->reason_id;
            CompanyPostArchivedReason::create($data);
            $Response['status'] = true;
            echo json_encode($Response);
        } else {
            $Response['status'] = false;
            echo json_encode($Response);
        }
    }

    public
    function add_archived($postId = null)
    {
        if (is_numeric($postId) && $postId > 0) {
            $post = Post::where('user_id', auth()->user()->id)->where('id', $postId)->first();

            if (empty($post)) {
                return response()->json(['status' => false, 'message' => t('Post not found')]);
            }

            if ($post->archived != 1) {
                $post->archived = 1;
                $post->archived_at = Carbon::now(Date::getAppTimeZone());
                $post->archived_manually = 1;
                $post->save();

                if ($post->archived == 1) {
                    $archivedPostsExpiration = config('settings.cron.manually_archived_posts_expiration', 180);
                    $message = t('offline_putting_message', [
                        'postTitle' => $post->title,
                        'dateDel' => Date::format($post->archived_at->addDays($archivedPostsExpiration)),
                    ]);
                    $delete_date = Date::format($post->archived_at->addDays($archivedPostsExpiration));
                    $cc = '';
                    if (OptionalSelectedEmails::check_selected_email(2, auth()->user()->id)) {
                        $cc = auth()->user()->optional_emails;
                    }

                    $this->sendpostarcihvedmail($post, $delete_date, $cc);
                    return response()->json(['status' => true, 'message' => $message]);

                } else {
                    return response()->json(['status' => false, 'message' => t("The putting offline has failed")]);
                }
            } else {
                return response()->json(['status' => false, 'message' => t("The ad is already offline")]);
            }
        } else {
            return response()->json(['status' => false, 'message' => t("The putting offline has failed")]);
        }

    }


    public
    function sendpostarcihvedmail($post, $delete_date, $cc = null)
    {
        $data['email'] = $post->email;
        $data['subject'] = 'Your job has been archived for the position' . $post->title;
        $data['myName'] = $post->company_name;
        $data['delete_date'] = $delete_date;
        $data['url'] = UrlGen::post($post);
        $data['reposturl'] = url('account/archived');
        $data['view'] = 'emails.job_archived';
        $data['cc'] = $cc;
        $data['header'] = 'Archived Job';
        $helper = new Helper();
        $helper->send_email($data);
    }
}
