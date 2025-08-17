<?php

namespace App\Helpers\Search\Traits\Filters;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

trait TagFilter
{
    protected function applyTagFilter()
    {
        if (!isset($this->posts)) {
            return;
        }

        $tag = null;
        if (Str::contains(Route::currentRouteAction(), 'Search\TagController')) {
            $tag = request()->segment(2);
            if (config('settings.seo.multi_countries_urls')) {
                $tag = request()->segment(3);
            }
        } else {
            if (request()->filled('tag')) {
                $tag = request()->get('tag');
            }
        }

        if (empty(trim($tag ?? ''))) {
            return;
        }

        $tag = rawurldecode($tag);
        $tag = mb_strtolower($tag);

        $this->posts->whereRaw('FIND_IN_SET(?, LOWER(tags)) > 0', [$tag]);
    }
}
