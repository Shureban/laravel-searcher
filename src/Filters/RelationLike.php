<?php

namespace Shureban\LaravelSearcher\Filters;

use Illuminate\Database\Eloquent\Builder;

class RelationLike extends RelationColumnFilter
{
    /**
     * @inerhitDoc
     *
     * @param Builder $query
     * @param         $value
     *
     * @return Builder
     */
    public function apply(Builder $query, $value): Builder
    {
        $callback = fn(Builder $query) => $query->where($this->getColumnName(), 'ilike', "%{$value}%");

        return $query->whereHas($this->getRelation(), $callback);
    }
}
