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
     * @var OrderByClause|null
     */
    protected $orderByClause = null;

    /**
     * Add a 'where' clause for the query
     * @param string $key
     * @param mixed  $value
     * @param string $operator
     * @return self
     */
    public function addWhere(string $key, $value, string $operator = '=='): self
    {
        $this->whereClauses[] = new WhereClause($key, $value, $operator);

        return $this;
    }

    /**
     * Set the 'order by' clause for the query
     * @param string $key
     * @param string $order
     * @return self
     */
    public function setOrderBy(string $key, string $order = 'asc'): self
    {
        $this->orderByClause = new OrderByClause($key, $order);

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
     * @return OrderByClause|null
     */
    public function getOrderByClause(): ?OrderByClause
    {
        return $this->orderByClause;
    }
}
