<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\City;
use App\Models\EmployeeSkill;
use Illuminate\Support\Facades\Cache;
use Torann\LaravelMetaTags\Facades\MetaTag;

class SitemapController extends FrontController
{
    public function index()
    {

        $data = [];

        // Get Categories
        $cats = Cache::remember('categories_all', config('cache.stores.file.expire'), function () {
            // Your actual query here with filters
            return Category::orderBy('lft')->get();
        });


        $cats = collect($cats)->keyBy('id');
        $cats = $subCats = $cats->groupBy('parent_id');

        if ($cats->has(null)) {
            $col = round($cats->get(null)->count() / 3, 0, PHP_ROUND_HALF_EVEN);
            $col = ($col > 0) ? $col : 1;
            $data['cats'] = $cats->get(null)->chunk($col);
            $data['subCats'] = $subCats->forget(null);
        } else {
            $data['cats'] = collect([]);
            $data['subCats'] = collect([]);
        }
        // Get Cities

        $cities = Cache::remember('cities_list_sitemap', config('cache.stores.file.expire'), function () {
            $limit = 1000;
            // Your actual query here with filters
            return City::currentCountry()->take($limit)->orderBy('population', 'DESC')->orderBy('name')->get();
        });


        $col = round($cities->count() / 4, 0, PHP_ROUND_HALF_EVEN);
        $col = ($col > 0) ? $col : 1;
        $data['cities'] = $cities->chunk($col);
        $data['employee_skills'] = Cache::remember('employee_skills_sets', config('cache.stores.file.expire'), function () {
            // Your actual query here with filters
            return EmployeeSkill::getAllskill();
        });
        $data['title'] = t('sitemap');
        // Meta Tags
        MetaTag::set('title', getMetaTag('title', 'sitemap'));
        MetaTag::set('description', strip_tags(getMetaTag('description', 'sitemap')));
        MetaTag::set('keywords', getMetaTag('keywords', 'sitemap'));

        return appView('sitemap.index', $data);
    }
}
