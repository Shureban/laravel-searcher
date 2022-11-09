<?php

namespace Shureban\LaravelSearcher\Filters;

use Illuminate\Database\Eloquent\Builder;
use Shureban\LaravelSearcher\Exceptions\RangeIsIncompleteException;

class Between extends Filter
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

        return $query->whereBetween($this->getFieldName(), array_values($value));
    }
}
