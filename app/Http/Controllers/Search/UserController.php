<?php

namespace App\Http\Controllers\Search;

use App\Helpers\Search\PostQueries;
use App\Helpers\UrlGen;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Torann\LaravelMetaTags\Facades\MetaTag;

class UserController extends BaseController
{
    public $isUserSearch = true;
    public $sUser;

    /**
     * @param $countryCode
     * @param null $userId
     * @return Factory|RedirectResponse|View
     */
    public function index($countryCode, $userId = null)
    {

        // Check multi-countries site parameters
        if (!config('settings.seo.multi_countries_urls')) {
            $userId = $countryCode;
        }

        view()->share('isUserSearch', $this->isUserSearch);

        // Get User
        $this->sUser = User::findOrFail($userId);

        // Redirect to User's profile If username exists
        if (!empty($this->sUser->username)) {
            $url = UrlGen::user($this->sUser, $countryCode);

            return redirect()->to($url, 301)->withHeaders(config('larapen.core.noCacheHeaders'));
        }

        return $this->searchByUserId($this->sUser->id);
    }

    /**
     * @param $userId
     * @param null $username
     * @return Factory|View
     */
    private function searchByUserId($userId, $username = null)
    {
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
        view()->share('uriPathUserId', $userId);
        view()->share('uriPathUsername', $username);

        return appView('search.results', $data);
    }

    /**
     * @param $countryCode
     * @param null $username
     * @return Factory|View
     */
    public function profile($countryCode, $username = null)
    {
        // Check multi-countries site parameters
        if (!config('settings.seo.multi_countries_urls')) {
            $username = $countryCode;
        }

        view()->share('isUserSearch', $this->isUserSearch);

        // Get User
        $this->sUser = User::where('username', $username)->firstOrFail();

        return $this->searchByUserId($this->sUser->id, $this->sUser->username);
    }
}
