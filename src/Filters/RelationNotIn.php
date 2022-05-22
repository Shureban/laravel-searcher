<?php

namespace Shureban\LaravelSearcher\Filters;

use Illuminate\Database\Eloquent\Builder;

class RelationNotIn implements Filter
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
        return $query->whereDoesntHave($this->relation, fn(Builder $query) => $query->whereIn($this->fieldName, $value));
    }
}
