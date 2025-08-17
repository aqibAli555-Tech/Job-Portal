<?php

namespace App\Http\Controllers\Search;

use App\Helpers\Search\PostQueries;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use Torann\LaravelMetaTags\Facades\MetaTag;

class CityController extends BaseController
{
    public $isCitySearch = true;

    /**
     * City URL
     * Pattern: (countryCode/)free-ads/city-slug/ID
     *
     * @param $countryCode
     * @param $citySlug
     * @param null $cityId
     * @return Factory|View
     */
    public function index($countryCode, $citySlug, $cityId = null)
    {
        // Check if City is found
        if (empty($this->city)) {
            abort(404, t('city_not_found'));
        }

        // Search
        $data = (new PostQueries($this->preSearch))->fetch();

        // Get Titles
        $bcTab = $this->getBreadcrumb();
        $htmlTitle = $this->getHtmlTitle();
        view()->share('bcTab', $bcTab);
        view()->share('htmlTitle', $htmlTitle);

        // Meta Tags
        $title = $this->getTitle();
        $description = t('Jobs in location', ['location' => $this->city->name])
            . ', ' . config('country.name')
            . '. ' . t('Looking for a job')
            . ' - ' . $this->city->name
            . ', ' . config('country.name');

        MetaTag::set('title', $title);
        MetaTag::set('description', $description);

        return appView('search.results', $data);
    }
}
