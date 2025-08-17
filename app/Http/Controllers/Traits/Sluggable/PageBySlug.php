<?php

namespace App\Http\Controllers\Traits\Sluggable;

use App\Models\Page;
use Illuminate\Support\Facades\Cache;

trait PageBySlug
{
    /**
     * Get Page by ID
     *
     * @param $pageId
     * @param null $locale
     * @return mixed
     */
    public function getPageById($pageId, $locale = null)
    {
        if (empty($locale)) {
            $locale = config('app.locale');
        }

        $cacheId = 'page.' . $pageId . '.' . $locale;
        $page = Cache::remember($cacheId, $this->cacheExpiration, function () use ($pageId, $locale) {
            $page = Page::find($pageId);

            if (!empty($page)) {
                $page->setLocale($locale);
            }

            return $page;
        });

        return $page;
    }

    /**
     * Get Page by Slug
     * NOTE: Slug must be unique
     *
     * @param $slug
     * @param null $locale
     * @return mixed
     */
    private function getPageBySlug($slug, $locale = null)
    {
        if (empty($locale)) {
            $locale = config('app.locale');
        }

        $cacheId = 'page.slug.' . $slug . '.' . $locale;

        $page = Cache::remember($cacheId, config('cache.stores.file.expire'), function () use ($slug, $locale) {
            // Your actual query here
            $page = Page::where('slug', $slug)->first();

            if (!empty($page)) {
                $page->setLocale($locale);
            }
            return $page;
        });

        return $page;
    }
}
