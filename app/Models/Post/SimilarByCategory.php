<?php

namespace App\Models\Post;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Support\Collection;

trait SimilarByCategory
{
    /**
     * Get similar Posts (Posts in the same Category)
     *
     * @param int $limit
     * @return Collection
     */
    public function getSimilarByCategory($limit = 20)
    {
        $posts = Post::query();

        $postsTable = (new Post())->getTable();
        dd($postsTable);

        $select = [
            $postsTable . '.id',
            $postsTable . '.country_code',
            'category_id',
            'post_type_id',
            'company_id',
            'company_name',
            'posts.logo',
            'title',
            $postsTable . '.description',
            'salary_min',
            'salary_max',
            'salary_type_id',
            $postsTable . '.created_at',
            $postsTable . '.created_at',
            'is_deleted',
            'user_id',
        ];

        if (is_array($select) && count($select) > 0) {
            foreach ($select as $column) {
                $posts->addSelect($column);
            }
        }

        // Get the sub-categories of the current ad parent's category
        $similarCatIds = [];
        if (!empty($this->category)) {
            if ($this->category->id == $this->category->parent_id) {
                $similarCatIds[] = $this->category->id;
            } else {
                if (!empty($this->category->parent_id)) {
                    $similarCatIds = Category::where('parent_id', $this->category->parent_id)->get()
                        ->keyBy('id')
                        ->keys()
                        ->toArray();
                    $similarCatIds[] = (int)$this->category->parent_id;
                } else {
                    $similarCatIds[] = (int)$this->category->id;
                }
            }
        }

        // Default Filters
        $posts->currentCountry()->verified()->unarchived();
        if (config('settings.single.posts_review_activation')) {
            $posts->reviewed();
        }

        // Get ads from same category
        if (!empty($similarCatIds)) {
            if (count($similarCatIds) == 1) {
                if (isset($similarCatIds[0]) && !empty(isset($similarCatIds[0]))) {
                    $posts->where('category_id', (int)$similarCatIds[0]);
                }
            } else {
                $posts->whereIn('category_id', $similarCatIds);
            }
        }

        // Relations
        $posts->with('category')->has('category');
        $posts->with('postType')->has('postType');
        $posts->with('salaryType')->has('salaryType');
        $posts->with('postDetail')->has('postDetail');
        $posts->with('postDetail.city')->has('postDetail.city');
        $posts->with('postMeta')->has('postMeta');

        if (isset($this->id)) {
            $posts->where($postsTable . '.id', '!=', $this->id);
        }

        // Set ORDER BY
        $posts->orderBy('created_at', 'DESC');

        $posts = $posts->take((int)$limit)->get();

        // Randomize the Posts
        $posts = collect($posts)->shuffle();

        return $posts;
    }
}
