<?php

namespace Shureban\LaravelSearcher\Filters;

use Illuminate\Database\Eloquent\Builder;

class Equal extends ColumnFilter
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
        return $query->where($this->getColumnName(), '=', $value);
    }
}
