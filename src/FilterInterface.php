<?php

namespace Shureban\LaravelSearcher;

use Illuminate\Database\Eloquent\Builder;

interface FilterInterface
{
    /**
     * Apply filter to query
     *
     * @param Builder $query
     * @param mixed   $value
     *
     * @return Builder
     */
    public function apply(Builder $query, mixed $value): Builder;
}
