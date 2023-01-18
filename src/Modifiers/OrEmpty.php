<?php

namespace Shureban\LaravelSearcher\Modifiers;

use Illuminate\Database\Eloquent\Builder;
use Shureban\LaravelSearcher\FilterInterface;
use Shureban\LaravelSearcher\Filters\ColumnFilter;

class OrEmpty implements FilterInterface
{
    private ColumnFilter $filter;

    /**
     * @param ColumnFilter $filter
     */
    public function __construct(ColumnFilter $filter)
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
            return $this->filter->apply($query, $value)->orWhere($this->filter->getColumnName(), '');
        });
    }
}
