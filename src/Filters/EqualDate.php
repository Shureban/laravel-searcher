<?php

namespace Shureban\LaravelSearcher\Filters;

use DateTime;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Shureban\LaravelSearcher\Exceptions\WrongDateTimeFormatException;

class EqualDate extends Filter
{
    /**
     * @inerhitDoc
     *
     * @param Builder $query
     * @param mixed   $value
     *
     * @return Builder
     * @throws WrongDateTimeFormatException
     */
    public function apply(Builder $query, mixed $value): Builder
    {
        try {
            $date = new DateTime($value);
        } catch (Exception) {
            throw new WrongDateTimeFormatException();
        }

        return $query->where(DB::raw("{$this->getFieldName()}::date"), '=', $date);
    }
}
