<?php

namespace Nerbiz\PrivateStats\Query;

use Nerbiz\PrivateStats\VisitInfo;

class ReadQuery
{
    /**
     * The 'where' clauses used for the query
     * @var WhereClause[]
     */
    protected $whereClauses = [];

    /**
     * The 'order by' clause used for the query
     * @var OrderByClause
     */
    protected $orderByClause;

    /**
     * Add a 'where' clause for the query
     * @param WhereClause $whereClause
     * @return self
     */
    public function addWhere(WhereClause $whereClause): self
    {
        $this->whereClauses[] = $whereClause;

        return $this;
    }

    /**
     * Set the 'order by' clause for the query
     * @param OrderByClause $orderByClause
     * @return self
     */
    public function setOrderBy(OrderByClause $orderByClause): self
    {
        $this->orderByClause = $orderByClause;

        return $this;
    }

    /**
     * See if an item passes the 'where' clauses
     * @param VisitInfo $visitInfo
     * @return bool
     */
    public function itemPassesChecks(VisitInfo $visitInfo): bool
    {
        // Keep the item, if there are no where clauses
        if (count($this->whereClauses) < 1) {
            return true;
        }

        $keepItem = 1;
        foreach ($this->whereClauses as $whereClause) {
            switch ($whereClause->getKey()) {
                case 'timestamp':
                    $compareValue = $visitInfo->getTimestamp();
                    break;
                case 'ip_hash':
                    $compareValue = $visitInfo->getIpHash();
                    break;
                case 'url':
                    $compareValue = $visitInfo->getUrl();
                    break;
                case 'referrer':
                    $compareValue = $visitInfo->getReferrer();
                    break;
                default:
                    $compareValue = null;
                    break;
            }

            // Only compare existing values
            if ($compareValue === null) {
                continue;
            }

            // If at least one check fails, don't keep the item
            $valuePasses = $whereClause->valuePasses($compareValue);
            $keepItem = min($keepItem, (int)$valuePasses);
        }

        return ($keepItem === 1);
    }

    /**
     * @return WhereClause[]
     */
    public function getWhereClauses(): array
    {
        return $this->whereClauses;
    }

    /**
     * @return OrderByClause
     */
    public function getOrderByClause(): OrderByClause
    {
        return $this->orderByClause;
    }
}
