<?php

namespace App\Helpers\Search;

use App\Helpers\Search\Traits\Filters;
use App\Helpers\Search\Traits\GroupBy;
use App\Helpers\Search\Traits\Having;
use App\Helpers\Search\Traits\OrderBy;
use App\Helpers\Search\Traits\Relations;
use App\Helpers\Search\Traits\Select;
use App\Models\City;
use App\Models\Country;
use App\Models\EmployeeSkill;
use App\Models\Post;
use Session;

class PostQueries
{

    use Select, Relations, Filters, GroupBy, Having, OrderBy;

    protected static $cacheExpiration = 300; // 5mn (60s * 5)

    public $country;
    public $lang;
    public $perPage = 12;

    // Pre-Search Objects
    public $cat = null;
    public $city = null;
    public $admin = null;

    // Default Columns Selected
    public $orderByParametersFields = [];
    protected $select = [];
    protected $groupBy = [];
    protected $having = [];
    protected $orderBy = [];
    protected $posts;
    protected $postsTable;

    /**
     * PostQueries constructor.
     *
     * @param array $preSearch
     */
    public function __construct($preSearch = [])
    {
        // Pre-Search
        if (isset($preSearch['cat']) && !empty($preSearch['cat'])) {
            $this->cat = $preSearch['cat'];
        }
        if (isset($preSearch['city']) && !empty($preSearch['city'])) {
            $this->city = $preSearch['city'];
        }
        if (isset($preSearch['admin']) && !empty($preSearch['admin'])) {
            $this->admin = $preSearch['admin'];
        }
        // Entries per page
        $this->perPage = (is_numeric(config('settings.listing.items_per_page'))) ? config('settings.listing.items_per_page') : $this->perPage;
        if ($this->perPage < 4) $this->perPage = 4;
        if ($this->perPage > 40) $this->perPage = 40;
        // Init. Builder

        $this->posts = Post::query()->where('is_deleted', 0)->where('is_active', 1)->where('posts.is_post_expire', 0)->where('posts.archived', 0);
        if (!empty(request()->get('q'))) {

            $this->posts = $this->posts->where('category_id', request()->get('q'));
        }
        $this->postsTable = (new Post())->getTable();

        // Add Default Select Columns
        $this->setSelect();
        // Relations
        $this->setRelations();

    }

    /**
     * Get the results
     *
     * @return array
     */
    public function fetch()
    {
        // Apply Requested Filters
        $this->applyFilters();
        // Apply Aggregation & Reorder Statements
        $this->applyGroupBy();
        $this->applyHaving();
        $code = Session::get('country_code');
        if (!empty(request()->get('post'))) {
            $this->posts->where('posts.id', request()->get('post'));
        } else {
            $this->posts->orderByRaw("FIELD(country_code , '$code') DESC");
        }
        $this->posts->orderBy('created_at', 'DESC');
        $this->posts->orderBy('id', 'desc');

        $posts = $this->posts->paginate((int)$this->perPage);

        $query = str_replace(array('?'), array('\'%s\''), $this->posts->toSql());
        $query = vsprintf($query, $this->posts->getBindings());


        // get queries

        $count = collect(['all' => $posts->total()]);
        $emp_skills = EmployeeSkill::getAllskill();
        $cities = City::get_all_city_with_post_count();
        $countries = Country::get_all_country_with_postCount();
        $code = config('country.code');
        $city_data = City::where('country_code', $code)->get();
        $country = !empty(request('country_code')) ? request('country_code') : '';
        $countriesSearch = Country::where('code', $country)->first();
        $skill_id = !empty(request()->get('q')) ? request()->get('q') : 0;
        $skill = EmployeeSkill::where('id', $skill_id)->first();
        $city_id = !empty(request()->get('l')) ? request()->get('l') : 0;
        $city = City::where('id', $city_id)->first();


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
        ];

        return $data;
    }
}
