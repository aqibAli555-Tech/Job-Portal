<?php

namespace App\Http\Controllers\Search;

use App\Helpers\Search\PostQueries;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use Torann\LaravelMetaTags\Facades\MetaTag;

class TagController extends BaseController
{
    public $isTagSearch = true;
    public $tag;

    /**
     * @param $countryCode
     * @param null $tag
     * @return Factory|View
     */
    public function index($countryCode, $tag = null)
    {
        // Check multi-countries site parameters
        if (!config('settings.seo.multi_countries_urls')) {
            $tag = $countryCode;
        }

        view()->share('isTagSearch', $this->isTagSearch);

        // Get Tag
        $this->tag = rawurldecode($tag);

        // Search
        $data = (new PostQueries())->fetch();

        // Get Titles
        $bcTab = $this->getBreadcrumb();
        $htmlTitle = $this->getHtmlTitle();
        view()->share('bcTab', $bcTab);
        view()->share('htmlTitle', $htmlTitle);

        // Meta Tags
        $title = $this->getTitle();
        MetaTag::set('title', $title);
        MetaTag::set('description', $title);

        // Translation vars
        view()->share('uriPathTag', $tag);

        return appView('search.results', $data);
    }
}