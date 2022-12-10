<?php

namespace Shureban\LaravelSearcher\Filters;

use Illuminate\Database\Eloquent\Builder;

class RelationIn extends RelationColumnFilter
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
        $callback = fn(Builder $query) => $query->whereIn($this->getColumnName(), $value);

        return $query->whereHas($this->getRelation(), $callback);
    }
}
