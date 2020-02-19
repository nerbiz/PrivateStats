<?php

namespace Nerbiz\PrivateStats;

use Nerbiz\PrivateStats\Query\ReadQuery;

class VisitInfoCollection
{
    /**
     * The collection of VisitInfo instances
     * @var VisitInfo[]
     */
    protected $items;

    /**
     * @param VisitInfo[] $items
     */
    public function __construct(array $items)
    {
        // Set the items array, with indexes reset
        $this->items = array_values($items);
    }

    /**
     * Get items, based on where clauses
     * @param ReadQuery|null $readQuery
     * @return VisitInfo[]
     */
    public function get(?ReadQuery $readQuery = null): array
    {
        if ($readQuery === null) {
            return $this->items;
        }

        $filteredItems = array_filter($this->items, [$readQuery, 'itemPassesChecks']);
        return $readQuery->getOrderByClause()->getSortedItems($filteredItems);
    }
}
