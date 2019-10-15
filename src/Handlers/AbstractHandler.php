<?php

namespace Nerbiz\PrivateStats\Handlers;

use Nerbiz\PrivateStats\VisitInfo;

abstract class AbstractHandler
{
    /**
     * There where clauses used for reading data
     * @var WhereClause[]
     */
    protected $whereClauses = [];

    /**
     * Add a where clause for reading data
     * @param WhereClause $whereClause
     * @return self
     */
    public function addWhere(WhereClause $whereClause): self
    {
        $this->whereClauses[] = $whereClause;

        return $this;
    }

    /**
     * See if an item needs to be kept, by using where clauses
     * @param VisitInfo $visitInfo
     * @return bool
     */
    protected function keepItem(VisitInfo $visitInfo): bool
    {
        // Keep the item, if there are no where clauses
        if (count($this->whereClauses) < 1) {
            return true;
        }

        $keepItem = 1;
        foreach ($this->whereClauses as $whereClause) {
            switch ($whereClause->getKey()) {
                case 'timestamp':
                case 'date':
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
     * Store information about a page visit
     * @param VisitInfo $visitInfo
     * @return bool Indicates whether storing was successful
     */
    abstract public function write(VisitInfo $visitInfo): bool;

    /**
     * Get stored information
     * @return VisitInfo[]
     */
    abstract public function read(): array;
}
