<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ReviewedScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param Builder $builder
     * @param Model $model
     * @return $this|Builder
     */
    public function apply(Builder $builder, Model $model)
    {
        if (request()->segment(1) == admin_uri()) {
            return $builder;
        }

        if (config('settings.single.posts_review_activation')) {
            return $builder->where('reviewed', 1);
        }

        return $builder;
    }
}
