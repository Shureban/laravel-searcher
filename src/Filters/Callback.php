<?php

namespace Shureban\LaravelSearcher\Filters;

use Illuminate\Database\Eloquent\Builder;
use Shureban\LaravelSearcher\FilterInterface;

class Callback implements FilterInterface
{
    /**
     * @var callable
     */
    private $callback;

    /**
     * @param callable $callback
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
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
        return call_user_func($this->callback, $query, $value);
    }
}
