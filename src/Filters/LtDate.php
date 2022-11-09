<?php

namespace Shureban\LaravelSearcher\Filters;

use DB;
use Illuminate\Database\Eloquent\Builder;

class LtDate extends Filter
{
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
        return $query->where(DB::raw("{$this->getFieldName()}::date"), '<', $value);
    }
}