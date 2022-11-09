<?php

namespace Shureban\LaravelSearcher\Filters;

use Illuminate\Database\Eloquent\Builder;

class RelationNotIn extends RelationFilter
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
        $callback = fn(Builder $query) => $query->whereIn($this->getFieldName(), $value);
        
        return $query->whereDoesntHave($this->getRelation(), $callback);
    }
}
