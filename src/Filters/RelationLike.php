<?php

namespace Shureban\LaravelSearcher\Filters;

use Illuminate\Database\Eloquent\Builder;

class RelationLike extends RelationFilter
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
        $callback = fn(Builder $query) => $query->where($this->getFieldName(), 'ilike', "%{$value}%");

        return $query->whereHas($this->getRelation(), $callback);
    }
}
