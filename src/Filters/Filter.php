<?php

namespace Shureban\LaravelSearcher\Filters;

use Shureban\LaravelSearcher\FilterInterface;

abstract class Filter implements FilterInterface
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
     * @return string
     */
    public function getFieldName(): string
    {
        return $this->fieldName;
    }
}
