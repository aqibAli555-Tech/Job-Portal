<?php

namespace App\Models\Post;

use App\Models\Package;
use App\Models\Payment;
use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Session;

trait LatestOrPremium
{
    /**
     * Get Latest or Sponsored Posts
     *
     * @param int $limit
     * @param string $type
     * @param null $defaultOrder
     * @return Builder[]|Collection
     */
    public static function getLatestOrSponsored($limit = 20, $type = 'latest', $defaultOrder = null)
    {
       
        $posts = Post::query();
        $tablesPrefix = DB::getTablePrefix();
        $postsTable = (new Post())->getTable();
        $paymentsTable = (new Payment())->getTable();
        $packagesTable = (new Package())->getTable();

        // Select fields
        $select = [
            $postsTable . '.id',
            $postsTable . '.country_code',
            'category_id',
            'post_type_id',
            'company_id',
            'company_name',
            'logo',
            'title',
            $postsTable . '.description',
            'salary_min',
            'salary_max',
            'salary_type_id',
            $postsTable . '.created_at',
            $postsTable . '.created_at',
            'start_date',
            'is_deleted',
            'is_approved',
            'user_id',
            'as_soon',

            // 'tPackage.lft',
        ];

        // GroupBy fields
        $groupBy = [
            $postsTable . '.id',
        ];

        $orderBy = [
            $tablesPrefix . $postsTable . '.created_at DESC',
        ];

        // If the MySQL strict mode is activated, ...
        // Append all the non-calculated fields available in the 'SELECT' in 'GROUP BY' to prevent error related to 'only_full_group_by'
        if (env('DB_MODE_STRICT')) {
            $groupBy = $select;
        }

        if (is_array($select) && count($select) > 0) {
            foreach ($select as $column) {
                $posts->addSelect($column);
            }
        }

        // Default Filters
        $posts->verified()->unarchived();
        if (config('settings.single.posts_review_activation')) {
            $posts->reviewed();
        }

        // Relations
        $posts = $posts->with([
            'postType',
            'employeeskill',
            'savedByLoggedUser',
            'postDetail',
            'postDetail.city',
            'postMeta',
            'company',
            'country'
        ])
            ->whereHas('postType')
            ->whereHas('employeeskill')
            ->whereHas('postDetail')
            ->whereHas('postDetail.city')
            ->whereHas('postMeta', function ($query) {
                $query->where('featured', 1);
            })
            ->whereHas('company')
            ->whereHas('country')
            ->where([
                ['is_post_expire', 0],
                ['is_deleted', 0],
                ['is_active', 1],
                ['is_approved', 1]
            ]);

        // Set GROUP BY
        if (is_array($groupBy) && count($groupBy) > 0) {
            // Get valid columns name
            $groupBy = collect($groupBy)->map(function ($value, $key) use ($tablesPrefix) {
                if (Str::contains($value, '.')) {
                    $value = $tablesPrefix . $value;
                }

                return $value;
            })->toArray();

            $posts->groupByRaw(implode(', ', $groupBy));
        }

        // Set ORDER BY
        $code = Session::get('country_code');
        if (!empty($code)) {
            $posts = $posts->orderByRaw("FIELD(country_code , '$code') DESC");
        }
        if (is_array($orderBy) && count($orderBy) > 0) {
            $posts->orderByRaw(implode(', ', $orderBy));
        }


        // Get the Results
        $posts = $posts->take((int)$limit)->get();

        // Order By Home Section Settings
        if ($posts->count() > 0) {
            if ($defaultOrder == 'random') {
                $posts = $posts->shuffle();
            }
        }

        return $posts;
    }
}
