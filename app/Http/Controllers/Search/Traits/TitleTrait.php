<?php

namespace App\Http\Controllers\Search\Traits;

use App\Helpers\Search\PostQueries;
use App\Helpers\UrlGen;
use App\Http\Controllers\Post\Traits\CatBreadcrumbTrait;
use App\Models\City;
use App\Models\Country;
use App\Models\EmployeeSkill;
use App\Models\Post;
use App\Models\PostType;
use Illuminate\Support\Arr;

trait TitleTrait
{
    use CatBreadcrumbTrait;

    /**
     * Get Search Title
     *
     * @return string
     */
    public function getTitle()
    {
        $title = '';

        // Init.
        $title .= t('Job Listing');

        // Keyword
        if (request()->filled('q')) {
            $skill_data = EmployeeSkill::find(request()->get('q'));
            $title .= ' ' . t('for') . ' ';
            $title .= '"' . $skill_data->skill . '"';
        }
        if (request()->filled('post')) {
            $post = Post::find(request()->get('post'));
            $title .= ' ' . t('for') . ' ';
            $title .= $post->title . ' ';
        }


        // Company
        if (isset($this->company) && !empty($this->company)) {
            $title .= ' ' . t('among') . ' ';
            $title .= $this->company->name;
        }


        // Location
        if (request()->filled('r') && !request()->filled('l')) {
            // Administrative Division
            if (isset($this->admin) && !empty($this->admin)) {
                $title .= ' ' . t('in') . ' ';
                $title .= $this->admin->name;
            }
        } else {
            // City
            if (isset($this->city) && !empty($this->city)) {
                $title .= ' ' . t('in') . ' ';
                $title .= $this->city->name . ' ';
            }
        }


        // Country
        if (!empty(request()->filled('country_code'))) {
            $country_data = Country::where('code', request()->get('country_code'))->first();
            $title .= '"' . $country_data->name . '"';
        } else {
            $title .= ', ' . config('country.name');
        }


        return $title;
    }

    /**
     * Get Search HTML Title
     *
     * @return string
     */
    public function getHtmlTitle()
    {
        // Title
        $htmlTitle = '';

        // Init.
        $htmlTitle .= t('All jobs');

        // Location
        $searchUrl = UrlGen::search([], ['l', 'r', 'location', 'distance']);

        if (request()->filled('r') && !request()->filled('l')) {
            // Administrative Division
            if (isset($this->admin) && !empty($this->admin)) {
                $htmlTitle .= ' ' . t('in') . ' ';
                $htmlTitle .= '<a rel="nofollow" class="jobs-s-tag" href="' . $searchUrl . '">';
                $htmlTitle .= $this->admin->name;
                $htmlTitle .= '</a>';
            }
        } else {
            // City
            if (isset($this->city) && !empty($this->city)) {
                if (config('settings.listing.cities_extended_searches')) {
                    $htmlTitle .= ' ' . t('within') . ' ';
                    $htmlTitle .= '<a rel="nofollow" class="jobs-s-tag" href="' . $searchUrl . '">';
                    $htmlTitle .= t('x_distance_around_city', [
                        'distance' => (PostQueries::$distance == 1) ? 0 : PostQueries::$distance,
                        'unit' => getDistanceUnit(config('country.code')),
                        'city' => $this->city->name,
                    ]);
                    $htmlTitle .= '</a>';
                } else {
                    $htmlTitle .= ' ' . t('in') . ' ';
                    $htmlTitle .= '<a rel="nofollow" class="jobs-s-tag" href="' . $searchUrl . '">';
                    $htmlTitle .= $this->city->name;
                    $htmlTitle .= '</a>';
                }
            }
        }

        // Category
        if (isset($this->cat) && !empty($this->cat)) {
            // Get the parent of parent category URL
            $searchUrl = UrlGen::getCatParentUrl($this->cat->parent->parent ?? null, ['c', 'sc', 'cf']);

            if (isset($this->subCat) && !empty($this->subCat)) {
                $htmlTitle .= ' ' . t('in') . ' ';
                $htmlTitle .= '<a rel="nofollow" class="jobs-s-tag" href="' . $searchUrl . '">';
                $htmlTitle .= $this->subCat->name;
                $htmlTitle .= '</a>';

                // Get the parent category URL
                $searchUrl = UrlGen::getCatParentUrl($this->cat->parent ?? null, ['sc', 'cf']);
            }

            $htmlTitle .= ' ' . t('in') . ' ';
            $htmlTitle .= '<a rel="nofollow" class="jobs-s-tag" href="' . $searchUrl . '">';
            $htmlTitle .= $this->cat->name;
            $htmlTitle .= '</a>';
        }

        // Company
        if (isset($this->company) && !empty($this->company)) {
            $htmlTitle .= ' ' . t('from') . ' ';
            $htmlTitle .= '<a rel="nofollow" class="jobs-s-tag" href="' . UrlGen::search() . '">';
            $htmlTitle .= $this->company->name;
            $htmlTitle .= '</a>';
        }

        // Tag
        if (isset($this->tag) && !empty($this->tag)) {
            $htmlTitle .= ' ' . t('for') . ' ';
            $htmlTitle .= '<a rel="nofollow" class="jobs-s-tag" href="' . UrlGen::search() . '">';
            $htmlTitle .= $this->tag;
            $htmlTitle .= '</a>';
        }

        // Date
        if (request()->filled('postedDate') && isset($this->dates) && isset($this->dates->{request()->get('postedDate')})) {
            $htmlTitle .= '<a rel="nofollow" class="jobs-s-tag" href="' . qsUrl(request()->url(), request()->except(['postedDate']), null, false) . '">';
            $htmlTitle .= $this->dates->{request()->get('postedDate')};
            $htmlTitle .= '</a>';
        }

        // Job Type
        if (request()->filled('type')) {
            if (is_array(request()->get('type'))) {
                foreach (request()->get('type') as $key => $value) {
                    $jobType = PostType::find($value);
                    if (!empty($jobType)) {
                        $htmlTitle .= '<a rel="nofollow" class="jobs-s-tag" href="' . qsUrl(request()->url(), request()->except(['type.' . $key]), null, false) . '">';
                        $htmlTitle .= $jobType->name;
                        $htmlTitle .= '</a>';
                    }
                }
            } else {
                $jobType = PostType::find(request()->get('type'));
                if (!empty($jobType)) {
                    $htmlTitle .= '<a rel="nofollow" class="jobs-s-tag" href="' . qsUrl(request()->url(), request()->except(['type']), null, false) . '">';
                    $htmlTitle .= $jobType->name;
                    $htmlTitle .= '</a>';
                }
            }
        }

        view()->share('htmlTitle', $htmlTitle);

        return $htmlTitle;
    }

    /**
     * Get Breadcrumbs Tabs
     *
     * @return array
     */
    public function getBreadcrumb()
    {
        $bcTab = [];

        // City
        if (isset($this->city) && !empty($this->city)) {
            $title = t('in_x_distance_around_city', [
                'distance' => (PostQueries::$distance == 1) ? 0 : PostQueries::$distance,
                'unit' => getDistanceUnit(config('country.code')),
                'city' => $this->city->name,
            ]);

            $bcTab[] = collect([
                'name' => (isset($this->cat) ? t('All jobs') . ' ' . $title : $this->city->name),
                'url' => UrlGen::city($this->city),
                'position' => (isset($this->cat) ? 5 : 3),
                'location' => true,
            ]);
        }

        // Admin
        if (isset($this->admin) && !empty($this->admin)) {
            $title = $this->admin->name;

            $bcTab[] = collect([
                'name' => (isset($this->cat) ? t('All jobs') . ' ' . $title : $this->admin->name),
                'url' => UrlGen::search() . '?d=' . config('country.icode') . '&r=' . $this->admin->name,
                'position' => (isset($this->cat) ? 5 : 3),
                'location' => true,
            ]);
        }

        // Category
        $catBreadcrumb = $this->getCatBreadcrumb(!empty($this->cat) ? $this->cat : '0', 3);
        $bcTab = array_merge($bcTab, $catBreadcrumb);

        // Company
        if (isset($this->company) && !empty($this->company)) {
            $bcTab[] = collect([
                'name' => $this->company->name,
                'url' => UrlGen::company(null, $this->company->id),
                'position' => (isset($this->cat) ? 5 : 3),
                'location' => true,
            ]);
        }

        // Sort by Position
        $bcTab = array_values(Arr::sort($bcTab, function ($value) {
            return $value->get('position');
        }));

        view()->share('bcTab', $bcTab);

        return $bcTab;
    }

    public function gettitleForSearchCV()
    {
        $title = '';

        // Init.
        $title .= t('Search CV') . ' ';

        // Keyword
        if (request()->filled('cat')) {
            $title .= '  ' . t('for') . ' ';
            $title .= '"' . request()->get('cat') . '"';
        }
        if (!empty(request()->filled('keyword'))) {
            $title .= '  ' . t('for') . ' ';
            $title .= request()->get('keyword') . ' ';
        }


        // Location
        if (request()->filled('city')) {
            $city_data = City::where('id', request()->get('city'))->first();
            $title .= ' ' . t('in') . ' ';
            $title .= $city_data->name . ' ';
        }


        // Country
        if (!empty(request()->filled('country'))) {
            $country_data = Country::where('code', request()->get('country'))->first();
            $title .= $country_data->name . ' ';
        }

        return $title;
    }
}
