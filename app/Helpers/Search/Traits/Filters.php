<?php

namespace App\Helpers\Search\Traits;

use App\Helpers\Search\Traits\Filters\AuthorFilter;
use App\Helpers\Search\Traits\Filters\CategoryFilter;
use App\Helpers\Search\Traits\Filters\CompanyFilter;
use App\Helpers\Search\Traits\Filters\DateFilter;
use App\Helpers\Search\Traits\Filters\DynamicFieldsFilter;
use App\Helpers\Search\Traits\Filters\KeywordFilter;
use App\Helpers\Search\Traits\Filters\LocationFilter;
use App\Helpers\Search\Traits\Filters\PostTypeFilter;
use App\Helpers\Search\Traits\Filters\SalaryFilter;
use App\Helpers\Search\Traits\Filters\TagFilter;

trait Filters
{
    use AuthorFilter, CategoryFilter, KeywordFilter, LocationFilter, TagFilter,
        DateFilter, PostTypeFilter, SalaryFilter, DynamicFieldsFilter, CompanyFilter;

    protected function applyFilters()
    {

        if (!(isset($this->posts))) {
            return;
        }
        // Default Filters
        if (!empty(request()->get('country_code'))) {
            $this->applycountrycode(request()->get('country_code'));
        }

        if (config('settings.single.posts_review_activation')) {
            $this->posts->reviewed();
        }

        // Author
        $this->applyAuthorFilter();

        // Category
        $this->applyCategoryFilter();

        // Keyword
        $this->applyKeywordFilter();

//        $this->applyCountryFilter();


        // Location

        if (!empty(request()->get('l'))) {
            $this->posts->where('city_id', request()->get('l'));
        }

        // Tag
        $this->applyTagFilter();

        // Date
        $this->applyDateFilter();

        // Post Type
        $this->applyPostTypeFilter();

        // Salary
        $this->applySalaryFilter();

        // Dynamic Fields
        $this->applyDynamicFieldsFilters();

        // Company
        $this->applyCompanyFilter();
    }
}
