<?php

namespace Shureban\LaravelSearcher\Filters;

use DB;
use Illuminate\Database\Eloquent\Builder;

class GteDate implements Filter
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
        return $query->where(DB::raw("$this->fieldName::date"), '>=', $value);
    }
}
