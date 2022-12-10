<?php

namespace Shureban\LaravelSearcher\Modifiers;

use Illuminate\Database\Eloquent\Builder;
use Shureban\LaravelSearcher\FilterInterface;
use Shureban\LaravelSearcher\Filters\ColumnFilter;

class Relation implements FilterInterface
{
    private ColumnFilter $filter;
    private string       $relation;
    private bool         $has;

    /**
     * @param string       $relation
     * @param ColumnFilter $filter
     * @param bool         $has
     */
    public function __construct(string $relation, ColumnFilter $filter, bool $has = true)
    {
        $this->filter   = $filter;
        $this->relation = $relation;
        $this->has      = $has;
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
        $callback = fn(Builder $query) => $this->filter->apply($query, $value);

        return $this->has
            ? $query->whereHas($this->relation, $callback)
            : $query->whereDoesntHave($this->relation, $callback);
    }
}
