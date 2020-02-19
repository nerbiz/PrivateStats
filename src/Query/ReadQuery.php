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
     * @param string $orderByKey The key to order by
     * @param string $orderByOrder The ordering direction (asc/desc)
     */
    public function __construct(string $orderByKey = 'timestamp', string $orderByOrder = 'desc')
    {
        $this->orderByClause = new OrderByClause($orderByKey, $orderByOrder);
    }

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
     * See if an item passes the 'where' clauses
     * @param VisitInfo $visitInfo
     * @return bool
     */
    public function itemPassesChecks(VisitInfo $visitInfo): bool
    {
        // The item always passes, if there are no where clauses
        if (count($this->whereClauses) < 1) {
            return true;
        }

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

            // Only compare valid keys
            if ($compareValue === null) {
                continue;
            }

            // If at least one check fails, don't keep the item
            if (! $whereClause->valuePasses($compareValue)) {
                return false;
            }
        }

        return true;
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
