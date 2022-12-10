<?php

namespace Shureban\LaravelSearcher\Filters;

use Shureban\LaravelSearcher\FilterInterface;

abstract class ColumnFilter implements FilterInterface
{
    private string $columnName;

    /**
     * @param string $columnName
     */
    public function __construct(string $columnName)
    {
        $this->columnName = $columnName;
    }

    /**
     * @return string
     */
    public function getColumnName(): string
    {
        return $this->columnName;
    }
}
