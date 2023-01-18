<?php

namespace Shureban\LaravelSearcher\Modifiers;

use Illuminate\Database\Eloquent\Builder;
use Shureban\LaravelSearcher\FilterInterface;

class MultipleOr implements FilterInterface
{
    /** @var FilterInterface[] $filters */
    private array $filters;

    /**
     * @param FilterInterface ...$filters
     */
    public function __construct(FilterInterface ...$filters)
    {
        $this->filters = $filters;
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
        if (empty($this->filters)) {
            return $query;
        }

        return $query->where(function (Builder $query) use ($value) {
            if (count($this->filters) === 1) {
                return current($this->filters)->apply($query, $value);
            }

            $query = current($this->filters)->apply($query, $value);

            foreach ($this->filters as $filter) {
                $query->orWhere(fn(Builder $query) => $filter->apply($query, $value));
            }

            return $query;
        });
    }
}
