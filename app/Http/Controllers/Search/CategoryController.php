<?php

namespace App\Http\Controllers\Search;

use App\Helpers\Search\PostQueries;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Torann\LaravelMetaTags\Facades\MetaTag;

class CategoryController extends BaseController
{
    public $isCatSearch = true;

    /**
     * Category URL
     * Pattern: (countryCode/)category/category-slug
     * Pattern: (countryCode/)category/parent-category-slug/category-slug
     *
     * @param $countryCode
     * @param $catSlug
     * @param null $subCatSlug
     * @return Factory|RedirectResponse|Redirector|View
     */
    public function index($countryCode, $catSlug, $subCatSlug = null)
    {
        // Check if Category is found
        if (empty($this->cat)) {
            abort(404, t('category_not_found'));
        }

        // Search
        $data = (new PostQueries($this->preSearch))->fetch();

        // Get Titles
        $bcTab = $this->getBreadcrumb();
        $htmlTitle = $this->getHtmlTitle();
        view()->share('bcTab', $bcTab);
        view()->share('htmlTitle', $htmlTitle);

        // SEO
        $title = $this->getTitle();
        if (isset($this->cat->description) && !empty($this->cat->description)) {
            $description = Str::limit($this->cat->description, 200);
        } else {
            $description = Str::limit(t('Jobs category in location', [
                    'category' => $this->cat->name,
                    'location' => config('country.name'),
                ]) . '. ' . t('Looking for a job') . ' - ' . config('country.name'), 200);
        }

        // Meta Tags
        MetaTag::set('title', $title);
        MetaTag::set('description', $description);

        // Open Graph
        $this->og->title($title)->description($description)->type('website');
        if ($data['count']->get('all') > 0) {
            if ($this->og->has('image')) {
                $this->og->forget('image')->forget('image:width')->forget('image:height');
            }
        }
        view()->share('og', $this->og);

        return appView('search.results', $data);
    }
}
