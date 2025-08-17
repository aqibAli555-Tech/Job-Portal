<?php

namespace App\Helpers\Search\Traits\Filters;

trait PostTypeFilter
{
    protected function applyPostTypeFilter()
    {
        if (!isset($this->posts)) {
            return;
        }

        $postTypeIds = [];
        if (request()->filled('type')) {
            $postTypeIds = request()->get('type');
        }

        if (is_array($postTypeIds) && !empty($postTypeIds)) {
            $this->posts->whereIn('post_type_id', $postTypeIds);
        }
    }
}
