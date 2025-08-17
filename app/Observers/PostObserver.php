<?php

namespace App\Observers;

use App\Helpers\Files\Storage\StorageDisk;
use App\Helpers\Helper;
use App\Helpers\UrlGen;
use App\Models\CompanyPostArchivedReason;
use App\Models\Language;
use App\Models\Payment;
use App\Models\Picture;
use App\Models\Post;
use App\Models\PostArchivedOrCancleReason;
use App\Models\SavedPost;
use App\Models\Scopes\ActiveScope;
use App\Models\Scopes\StrictActiveScope;
use App\Models\Thread;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Models\User;

class PostObserver
{
    /**
     * Listen to the Entry updating event.
     *
     * @param Post $post
     * @return void
     */
    public function updating(Post $post)
    {
        if(auth()->check()) {
            if ($post->isDirty('archived') || $post->isDirty('is_post_expire')) {

                $originalArchivedValue = $post->getOriginal('archived');
                $newArchivedValue = $post->archived;
                if ($originalArchivedValue == 0 && $newArchivedValue == 1) {
                    $this->is_archived($post);
                }

                $originalPostExpireValue = $post->getOriginal('is_post_expire');
                $newPostExpireValue = $post->is_post_expire;

                if (($originalArchivedValue == 1 && $newArchivedValue == 0) || ($originalPostExpireValue == 1 && $newPostExpireValue == 0)) {
                    $this->is_repost($post);
                }
            }
            if ($post->isDirty('is_deleted')) {
                $originalityValue = $post->getOriginal('is_deleted');
                $deletedValue = $post->is_deleted;
                if ($originalityValue == 0 && $deletedValue == 1) {
                    $this->is_deleted($post);
                }
            }
        }
    }

    public function is_repost($post)
    {
        $postUrl = UrlGen::post($post);
        $postTitle = $post->title;

        $company_name = auth()->user()->name;
        $profile_url = admin_url() . '/employer?search=' . auth()->user()->email;
        $date = date('Y-m-d');
        $description = "A company Name: <a href='$profile_url'>$company_name</a> Respost his Post:  <a href='$postUrl'>$postTitle</a> on Date $date";
        Helper::activity_log($description);
    }

    // public function is_archived($post)
    // {
    //     $profileUrl = admin_url() . '/employer?search=' . auth()->user()->email;
    //     $companyName = auth()->user()->name;
    //     $postTitle = $post->title;
    //     $postUrl = admin_url() . '/get_posts?status=&search=' . $postTitle;
    //     $archivedReason = PostArchivedOrCancleReason::get_reasons_with_id(request()->get('reason_id'));
    //     $reasonTitle = $archivedReason ? $archivedReason->title : 'No reason provided';
    //     if($reasonTitle == 'No reason provided'){
    //         $companyName = 'Hungry For Jobs';
    //         $profileUrl = admin_url() . '/employer?search=contact@hungryforjobs.com';
    //     }
    //     $archivedPostLog = "An Employer: <a href='$profileUrl'>$companyName</a> added a post to archives: <a href='$postUrl'>$postTitle</a>. Reason: $reasonTitle";
    //     Helper::activity_log($archivedPostLog);
    //     $data['name'] = $postTitle;
    //     $data['reason'] = $reasonTitle;
    //     $data['url'] = url('/account/my-posts/');
    //     $companyDescription = Helper::companyDescriptionData($data, 'post_archived');
    //     if(!empty($companyDescription)){
    //         Helper::activity_log($companyDescription,auth()->user()->id);
    //     }
    //     $this->addReason($post->id);
    // }
    
    public function is_archived($post)
    {   
        $userData = User::where('id',$post->user_id)->first();
        $profileUrl = admin_url() . '/employer?search=' . $userData->email;
        $companyName = $userData->name;
        $postTitle = $post->title;
        $postUrl = admin_url() . '/get_posts?status=&search=' . $postTitle;
        $archivedReason = PostArchivedOrCancleReason::get_reasons_with_id(request()->get('reason_id'));
        $reasonTitle = $archivedReason ? $archivedReason->title : 'No reason provided';
        if($reasonTitle == 'No reason provided'){
            $companyName = 'Hungry For Jobs';
            $profileUrl = admin_url() . '/employer?search=contact@hungryforjobs.com';
        }
        $archivedPostLog = "An Employer: <a href='$profileUrl'>$companyName</a> added a post to archives: <a href='$postUrl'>$postTitle</a>. Reason: $reasonTitle";
        Helper::activity_log($archivedPostLog);
        $data['name'] = $postTitle;
        $data['reason'] = $reasonTitle;
        $data['url'] = url('/account/my-posts/');
        $companyDescription = Helper::companyDescriptionData($data, 'post_archived');
        if(!empty($companyDescription)){
            Helper::activity_log($companyDescription,$userData->id);
        }
        $this->addReason($post->id);
    }


    public function is_deleted($post)
    {
        $profileUrl = admin_url() . '/employer?search=' . auth()->user()->email;
        $companyName = auth()->user()->name;
        $postTitle = $post->title;
        $archivedReason = PostArchivedOrCancleReason::get_reasons_with_id(request()->get('reason_id'));
        $reasonTitle = $archivedReason ? $archivedReason->title : 'No reason provided';
        $delete_post_log = "A Company : <a href='$profileUrl'>$companyName</a> deleted a Post: <strong>$postTitle</strong>. Reason: " . $reasonTitle;
        Helper::activity_log($delete_post_log);
        $this->addReason($post->id);

    }

    public function addReason($postId)
    {
        $data['post_id'] = $postId;
        $data['reason_id'] = request()->get('reason_id');
        CompanyPostArchivedReason::create($data);
    }

    /**
     * Listen to the Entry deleting event.
     *
     * @param Post $post
     * @return void
     */
    public function deleting(Post $post)
    {
        // Storage Disk Init.
        $disk = StorageDisk::getDisk();

        // Delete all Threads
        $messages = Thread::where('post_id', $post->id);
        if ($messages->count() > 0) {
            foreach ($messages->cursor() as $message) {
                $message->forceDelete();
            }
        }

        // Delete all Saved Posts
        $savedPosts = SavedPost::where('post_id', $post->id);
        if ($savedPosts->count() > 0) {
            foreach ($savedPosts->cursor() as $savedPost) {
                $savedPost->delete();
            }
        }

        // Remove logo files (if exists)
        if (empty($post->company_id)) {
            if (!empty($post->logo)) {
                $filename = str_replace('uploads/', '', $post->logo);
                if (
                    !Str::contains($filename, config('larapen.core.picture.default'))
                    && $disk->exists($filename)
                ) {
                    $disk->delete($filename);
                }
            }
        }

        // Delete all Pictures
        $pictures = Picture::where('post_id', $post->id);
        if ($pictures->count() > 0) {
            foreach ($pictures->cursor() as $picture) {
                $picture->delete();
            }
        }

        // Delete the Payment(s) of this Post
        $payments = Payment::withoutGlobalScope(StrictActiveScope::class)->where('post_id', $post->id)->get();
        if ($payments->count() > 0) {
            foreach ($payments as $payment) {
                $payment->delete();
            }
        }

        // Remove the ad media folder
        if (!empty($post->country_code) && !empty($post->id)) {
            $directoryPath = 'files/' . strtolower($post->country_code) . '/' . $post->id;

            if ($disk->exists($directoryPath)) {
                $disk->deleteDirectory($directoryPath);
            }
        }

        // Removing Entries from the Cache
        $this->clearCache($post);
    }

    /**
     * Removing the Entity's Entries from the Cache
     *
     * @param $post
     */
    private function clearCache($post)
    {
        Cache::forget($post->country_code . '.sitemaps.posts.xml');

        Cache::forget($post->country_code . '.home.getPosts.sponsored');
        Cache::forget($post->country_code . '.home.getPosts.latest');
        Cache::forget($post->country_code . '.home.getFeaturedPostsCompanies');

        Cache::forget('post.withoutGlobalScopes.with.city.pictures.' . $post->id);
        Cache::forget('post.with.city.pictures.' . $post->id);

        try {
            $languages = Language::withoutGlobalScopes([ActiveScope::class])->get(['abbr']);
        } catch (Exception $e) {
            $languages = collect([]);
        }

        if ($languages->count() > 0) {
            foreach ($languages as $language) {
                Cache::forget('post.withoutGlobalScopes.with.city.pictures.' . $post->id . '.' . $language->abbr);
                Cache::forget('post.with.city.pictures.' . $post->id . '.' . $language->abbr);
                Cache::forget($post->country_code . '.count.posts.by.cat.' . $language->abbr);
            }
        }

        Cache::forget('posts.similar.category.' . $post->category_id . '.post.' . $post->id);
        Cache::forget('posts.similar.city.' . $post->city_id . '.post.' . $post->id);
    }

    /**
     * Listen to the Entry saved event.
     *
     * @param Post $post
     * @return void
     */
    public function saved(Post $post)
    {
        // Create a new email token if the post's email is marked as unverified
        // if ($post->verified_email != 1) {
        // 	if (empty($post->email_token)) {
        // 		$post->email_token = md5(microtime() . mt_rand());
        // 		$post->save();
        // 	}
        // }

        // // Create a new phone token if the post's phone number is marked as unverified
        // if ($post->verified_phone != 1) {
        // 	if (empty($post->phone_token)) {
        // 		$post->phone_token = mt_rand(100000, 999999);
        // 		$post->save();
        // 	}
        // }

        // Removing Entries from the Cache
        $this->clearCache($post);
    }

    /**
     * Listen to the Entry deleted event.
     *
     * @param Post $post
     * @return void
     */
    public function deleted(Post $post)
    {
        //...
    }
}
