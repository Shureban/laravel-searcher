<?php

namespace Shureban\LaravelSearcher\Filters;

use Illuminate\Database\Eloquent\Builder;

class IsNull implements Filter
{
    private string $fieldName;

    /**
     * @param string $fieldName
     */
    public function __construct(string $fieldName)
    {
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
        return $value
            ? $query->whereNotNull($this->fieldName)
            : $query->whereNull($this->fieldName);
    }
}
