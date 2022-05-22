<?php

namespace Shureban\LaravelSearcher\Filters;

use Illuminate\Database\Eloquent\Builder;

class BetweenRange implements Filter
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
        return $query->where(function (Builder $query) use ($value) {
            return $query->whereBetween($this->fieldName, [$value['min'], $value['max']])->orWhereNull($this->fieldName);
        });
    }
}
