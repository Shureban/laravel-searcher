<?php

namespace Shureban\LaravelSearcher\Filters;

use Illuminate\Database\Eloquent\Builder;

class OrNull implements Filter
{
    private Filter $filter;
    private string $fieldName;

    /**
     * @param Filter $filter
     * @param string $fieldName
     */
    public function __construct(Filter $filter, string $fieldName)
    {
        $this->fieldName = $fieldName;
        $this->filter    = $filter;
    }

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
        if (!$value) {
            return $query->whereNull($this->fieldName);
        }

        $subQuery = fn(Builder $query) => $this->filter->apply($query, $value)->orWhereNull($this->fieldName);

        return $query->where($subQuery);
    }
}
