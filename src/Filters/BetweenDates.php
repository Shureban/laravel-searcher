<?php

namespace Shureban\LaravelSearcher\Filters;

use Illuminate\Database\Eloquent\Builder;
use Shureban\LaravelSearcher\Exceptions\RangeIsIncompleteException;

class BetweenDates extends ColumnFilter
{
    private bool $strict;

    /**
     * @param string $columnName
     * @param bool   $strict
     */
    public function __construct(string $columnName, bool $strict = false)
    {
        parent::__construct($columnName);

        $this->strict = $strict;
    }

    /**
     * @inerhitDoc
     *
     * @param Builder $query
     * @param mixed   $value
     *
     * @return Builder
     * @throws RangeIsIncompleteException
     */
    public function apply(Builder $query, mixed $value): Builder
    {
        if (count($value) < 2) {
            throw new RangeIsIncompleteException();
        }

        $startDate = current($value);
        next($value);
        $endDate = current($value);

        if ($startDate > $endDate) {
            [$startDate, $endDate] = [$endDate, $startDate];
        }

        if ($this->strict) {
            return $query->where(function (Builder $query) use ($endDate, $startDate) {
                (new GteDate($this->getColumnName()))->apply($query, $startDate);
                (new LtDate($this->getColumnName()))->apply($query, $endDate);
            });
        }

        return $query->where(function (Builder $query) use ($endDate, $startDate) {
            (new GteDate($this->getColumnName()))->apply($query, $startDate);
            (new LteDate($this->getColumnName()))->apply($query, $endDate);
        });
    }
}
