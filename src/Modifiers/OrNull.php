<?php

namespace Shureban\LaravelSearcher\Modifiers;

use Illuminate\Database\Eloquent\Builder;
use Shureban\LaravelSearcher\FilterInterface;
use Shureban\LaravelSearcher\Filters\Filter;

class OrNull implements FilterInterface
{
    private Filter $filter;

    /**
     * @param Filter $filter
     */
    public function __construct(Filter $filter)
    {
        $this->filter = $filter;
    }

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
        return $query->where(function (Builder $query) use ($value) {
            return $this->filter->apply($query, $value)->orWhereNull($this->filter->getFieldName());
        });
    }
}
