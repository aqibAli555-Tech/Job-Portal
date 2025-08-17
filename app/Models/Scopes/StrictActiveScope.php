<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class StrictActiveScope implements Scope
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
        // Load all entries from some Admin panel Controllers:
        // - Admin\PaymentController
        // - Admin\AjaxController
        if (
            Str::contains(Route::currentRouteAction(), 'Admin\PaymentController')
            || Str::contains(Route::currentRouteAction(), 'Admin\AjaxController')
            || Str::contains(Route::currentRouteAction(), 'Admin\InlineRequestController')
        ) {
            return $builder;
        }

        // Load only activated entries for the rest of the website (Admin panel & Front)
        return $builder->where('active', 1);
    }
}
