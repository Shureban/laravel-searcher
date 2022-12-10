<?php

namespace Shureban\LaravelSearcher\Filters;

use Illuminate\Database\Eloquent\Builder;

class IsNull extends ColumnFilter
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
        return $value ? $query->whereNotNull($this->getColumnName()) : $query->whereNull($this->getColumnName());
    }
}
