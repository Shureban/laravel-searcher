<?php

namespace Shureban\LaravelSearcher\Filters;

use Illuminate\Database\Eloquent\Builder;

class NotIn extends ColumnFilter
{
    /**
     * @inerhitDoc
     *
     * @param Builder $query
     * @param mixed   $value
     *
     * @return Builder
     */
    public function apply(Builder $query, mixed $value): Builder
    {
        return $query->whereNotIn($this->getColumnName(), $value);
    }
}
