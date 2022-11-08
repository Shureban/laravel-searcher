<?php

namespace Shureban\LaravelSearcher\Filters;

abstract class RelationFilter extends Filter
{
    private string $relation;

    /**
     * @param string $relation
     * @param string $fieldName
     */
    public function __construct(string $relation, string $fieldName)
    {
        parent::__construct($fieldName);

        $this->relation  = $relation;
    }

    /**
     * @return string
     */
    public function getRelation(): string
    {
        return $this->relation;
    }
}
