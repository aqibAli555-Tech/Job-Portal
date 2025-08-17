<?php

namespace App\Helpers\Search\Traits;

trait Select
{
    protected function setSelect()
    {
        if (!(isset($this->posts) && isset($this->postsTable))) {
            return;
        }

        // Default Select Columns
        $select = [
            $this->postsTable . '.id',
            'country_code',
            'category_id',
            'post_type_id',
            'company_id',
            'company_name',
            'logo',
            'title',
            $this->postsTable . '.description',
            'salary_min',
            'salary_max',
            'salary_type_id',
            'start_date',
            'negotiable',
            $this->postsTable . '.created_at',
            $this->postsTable . '.user_id',
            $this->postsTable . '.as_soon',

        ];

        // Default GroupBy Columns
        $groupBy = [$this->postsTable . '.id'];

        // Merge Columns
        $this->select = array_merge($this->select, $select);
        $this->groupBy = array_merge($this->groupBy, $groupBy);

        // Add the Select Columns
        if (is_array($this->select) && count($this->select) > 0) {
            foreach ($this->select as $column) {
                $this->posts->addSelect($column);
            }
        }

        // If the MySQL strict mode is activated, ...
        // Append all the non-calculated fields available in the 'SELECT' in 'GROUP BY' to prevent error related to 'only_full_group_by'
        if (env('DB_MODE_STRICT')) {
            $this->groupBy = $this->select;
        }
    }
}
