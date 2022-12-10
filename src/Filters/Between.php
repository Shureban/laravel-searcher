<?php

namespace Shureban\LaravelSearcher\Filters;

use Illuminate\Database\Eloquent\Builder;
use Shureban\LaravelSearcher\Exceptions\RangeIsIncompleteException;

class Between extends ColumnFilter
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

        return $query->whereBetween($this->getColumnName(), array_values($value));
    }
}
