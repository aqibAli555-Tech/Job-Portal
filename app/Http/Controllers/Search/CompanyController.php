<?php

namespace App\Http\Controllers\Search;

use App\Helpers\Helper;
use App\Helpers\Search\PostQueries;
use App\Models\City;
use App\Models\Company;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class CompanyController extends BaseController
{
    public $isCompanySearch = true;
    public $company;
    private $perPage = 10;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->perPage = (is_numeric(config('settings.listing.items_per_page')))
            ? config('settings.listing.items_per_page')
            : $this->perPage;
    }

    /**
     * Listing of Companies
     *
     * @return Factory|View
     */
    public function index()
    {

        // Get Companies List
        $companies = Cache::remember('get_company_list', config('cache.stores.file.expire'), function () {
            // Your actual query here
            $companies = Company::with(['EmployerLogo'])->whereHas('posts', function ($query) {
                //$query->currentCountry();
            })->withCount([
                'posts' => function ($query) {
                    //$query->currentCountry();
                    $query->where('is_active', 1);
                    $query->where('is_deleted', 0);
                    $query->where('archived', 0);
                    $query->where('is_post_expire', 0);
                    //$query->where('posts.created_at', '>', date("Y-m-d H:i:s", strtotime("-29 days")));
                },
            ]);

            // Apply search filter
            if (request()->filled('q')) {
                $keywords = rawurldecode(request()->get('q'));
                $companies = $companies->where('name', 'LIKE', '%' . $keywords . '%')
                    ->whereOr('description', 'LIKE', '%' . $keywords . '%');
            }

            // Get Companies List with pagination
            $companies = $companies->orderByDesc('id')->get();
            return $companies;
        });
        $seo= Helper::getSeo('companies');

        view()->share([
            'title' => $seo['title'],
            'description' => $seo['description'],
            'keywords' => $seo['description'],
        ]);
        return appView('search.company.index')->with('companies', $companies);
    }

    /**
     * Show a Company profiles (with its Jobs ads)
     *
     * @param $countryCode
     * @param null $companyId
     * @return Factory|View
     */
    public function profile($countryCode, $companyId = null)
    {
        // Check multi-countries site parameters
        if (!config('settings.seo.multi_countries_urls')) {
            $companyId = $countryCode;
        }

        // Get Company
        $this->company = Company::with(['country', 'EmployerLogo'])->where('id', $companyId)->first();
        if (empty($this->company)) {
            abort(404, t('company_not_found'));
        }

        // Get the Company's Jobs
        $data = $this->jobs($this->company->id);

        // Share the Company's info with the view
        $data['company'] = $this->company;
        $data['all_companies'] = User::where('parent_id', $this->company->c_id)->get();
        if (empty($data['company']->city_id)) {
            $user = User::where('id', $data['company']->c_id)->first();
            $data['cityData'] = City::where('id', $user->city)->first();
        } else {
            $data['cityData'] = City::where('id', $data['company']->city_id)->first();
        }
        $seo=Helper::getSeo('company_profile',$this->company->name);

        // Meta Tag
        view()->share([
            'title' => $seo['title'],
            'description' =>  $seo['description'],
            'keywords' => $seo['description'],
        ]);
        return appView('search.company.profile', $data);
    }

    /**
     * Get the Company Jobs ads
     *
     * @param $companyId
     * @return array
     */
    private function jobs($companyId)
    {
        view()->share('isCompanySearch', $this->isCompanySearch);
        // Search
        $data = (new PostQueries())->fetch();

        // Get Titles
        $bcTab = $this->getBreadcrumb();
        $htmlTitle = $this->getHtmlTitle();
        view()->share('bcTab', $bcTab);
        view()->share('htmlTitle', $htmlTitle);
        // Meta Tags

        // Translation vars
        view()->share('uriPathCompanyId', $companyId);

        return $data;
    }
}