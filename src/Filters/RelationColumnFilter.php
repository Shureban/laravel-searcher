<?php

namespace Shureban\LaravelSearcher\Filters;

abstract class RelationColumnFilter extends ColumnFilter
{
    private string $relation;

    /**
     * @param string $relation
     * @param string $columnName
     */
    public function __construct(string $relation, string $columnName)
    {
        parent::__construct($columnName);

        $this->relation = $relation;
    }

    /**
     * @return string
     */
    public function getRelation(): string
    {
        return $this->relation;
    }
}
