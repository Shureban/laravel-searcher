<?php

namespace Shureban\LaravelSearcher\Filters;

use Illuminate\Database\Eloquent\Builder;
use Shureban\LaravelSearcher\Exceptions\RangeIsIncompleteException;

class RelationBetween extends RelationFilter
{
    /**
     * @inerhitDoc
     *
     * @param Builder $query
     * @param mixed   $value
     *
     * @return Builder
     * @throws RangeIsIncompleteException
     */
    public function apply(Builder $query, mixed $value): Builder
    {
        if (count($value) !== 2) {
            throw new RangeIsIncompleteException();
        }

        $callback = fn(Builder $query) => $query->whereBetween($this->getFieldName(), array_values($value));

        return $query->whereHas($this->getRelation(), $callback);
    }
}
