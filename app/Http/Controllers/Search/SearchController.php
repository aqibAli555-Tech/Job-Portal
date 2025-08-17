<?php

namespace App\Http\Controllers\Search;

use App\Helpers\Helper;
use App\Models\City;
use App\Models\Country;
use App\Models\EmployeeSkill;
use App\Models\Post;
use App\Models\PostType;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class SearchController extends BaseController
{
    public $isIndexSearch = true;

    /**
     * @return Factory|View
     */
    public function index(Request $request)
    {

        if (!isset($request->q) || !isset($request->l) || !isset($request->country_code) || !isset($request->q) || !isset($request->post) || !isset($request->type) || !isset($request->min_salary) || !isset($request->max_salary)) {
            return redirect(url('latest-jobs?post=&country_code=&q=&l=&min_salary=&max_salary=&type[]='));

        }

        if (!empty($request->country_code) && empty(Session::get('country_code_for_search') && !empty(Session::get('country_code')))) {
            session()->put('country_code_for_search', Session::get('country_code'));
        }
        if (!empty(Session::get('country_code_for_search'))) {
            session()->put('country_code', Session::get('country_code_for_search'));
        }

        $posts = Post::apply_filter_on_post();
        $posts_count = Post::count_all_post_with_filter();
        $count = collect(['all' => $posts_count->count()]);
     
        $emp_skills = Cache::remember('emp_skills_post_count'. md5(serialize($request->all())), config('cache.stores.file.expire'), function () {

            return EmployeeSkill::get_All_skill_With_Post_Count();
        });

        $cities = Cache::remember('cities_post_count'. md5(serialize($request->all())), config('cache.stores.file.expire'), function () {

            return City::get_all_city_with_post_count();
        });

        $countries = Cache::remember('countries_post_count'. md5(serialize($request->all())), config('cache.stores.file.expire'), function () {

            return Country::get_all_country_with_postCount();
        });

        $city_data = Cache::remember('city_data'. md5(serialize($request->all())), config('cache.stores.file.expire'), function () {

            $code = config('country.code');
            return City::where('country_code', $code)->get();
        });


        $countriesSearch = Cache::remember('countriesSearch'. md5(serialize($request->all())), config('cache.stores.file.expire'), function () {

            $country = !empty(request('country_code')) ? request('country_code') : '';
            return Country::where('code', $country)->first();
        });


        $skill = Cache::remember('skill'. md5(serialize($request->all())), config('cache.stores.file.expire'), function () {

            $skill_id = !empty(request()->get('q')) ? request()->get('q') : 0;
            return EmployeeSkill::where('id', $skill_id)->first();
        });

        $city = Cache::remember('city'. md5(serialize($request->all())), config('cache.stores.file.expire'), function () {

            $city_id = !empty(request()->get('l')) ? request()->get('l') : 0;
            return City::where('id', $city_id)->first();
        });
        $postTypes = Cache::remember('postTypes'. md5(serialize($request->all())), config('cache.stores.file.expire'), function () {

            return PostType::all();
        });

        // Results Data
        $data = [
            'posts' => $posts,
            'count' => $count,
            'emp_skills' => $emp_skills,
            'cities' => $cities,
            'countries' => $countries,
            'city_data' => $city_data,
            'countriesSearch' => $countriesSearch,
            'skill' => $skill,
            'city' => $city,
            'postTypes' => $postTypes,
        ];

        // Get Titles
        $title = $this->getTitle();
        $this->getBreadcrumb();
        $this->getHtmlTitle();
        $seo = Helper::getSeo('latest-jobs');

        // Meta Tags
        view()->share([
            'title' => $seo['title'],
            'description' => $seo['description'],
            'keywords' => $seo['description'],
        ]);

        return view('search.results', $data);
    }
}