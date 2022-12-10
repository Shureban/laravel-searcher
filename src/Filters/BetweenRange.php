<?php

namespace Shureban\LaravelSearcher\Filters;

use Illuminate\Database\Eloquent\Builder;
use Shureban\LaravelSearcher\Exceptions\RangeIsIncompleteException;

class BetweenRange extends ColumnFilter
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
        if (count($value) < 2) {
            throw new RangeIsIncompleteException();
        }

        $min = current($value);
        next($value);
        $max = current($value);

        return $query->whereBetween($this->getColumnName(), [$min, $max]);
    }
}
