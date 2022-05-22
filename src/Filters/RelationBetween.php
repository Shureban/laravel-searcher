<?php

namespace Shureban\LaravelSearcher\Filters;

use Illuminate\Database\Eloquent\Builder;

class RelationBetween implements Filter
{
    private string $relation;
    private string $fieldName;

    /**
     * @param string $relation
     * @param string $fieldName
     */
    public function __construct(string $relation, string $fieldName)
    {
        $this->relation  = $relation;
        $this->fieldName = $fieldName;
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
        $callback = fn(Builder $query) => $query->whereBetween($this->fieldName, array_values($value));

        return $query->whereHas($this->relation, $callback);
    }
}
