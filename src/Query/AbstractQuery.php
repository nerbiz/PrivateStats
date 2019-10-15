<?php

namespace Nerbiz\PrivateStats\Query;

use Nerbiz\PrivateStats\VisitInfo;

abstract class AbstractQuery
{
    /**
     * Where clauses for getting items from the collection
     * @var WhereClause[]
     */
    protected $whereClauses = [];

    /**
     * Add a where clause for getting items from the collection
     * @param WhereClause $whereClause
     * @return self
     */
    public function addWhere(WhereClause $whereClause): self
    {
        $this->whereClauses[] = $whereClause;

        return $this;
    }

    /**
     * Get an array of VisitInfo items
     * @return VisitInfo[]
     */
    abstract public function get(): array;
}
