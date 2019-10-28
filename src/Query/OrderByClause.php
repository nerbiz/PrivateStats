<?php

namespace Nerbiz\PrivateStats\Query;

use Nerbiz\PrivateStats\VisitInfo;

class OrderByClause
{
    /**
     * The key to order by
     * @var string
     */
    protected $key;

    /**
     * The sorting order
     * @var string
     */
    protected $order;

    /**
     * @param string $key
     * @param string $order
     */
    public function __construct(string $key, string $order)
    {
        $this->key = $key;

        // Make sure the 'order' value is valid
        $order = strtolower($order);
        if (! in_array($order, ['asc', 'desc'], true)) {
            $order = 'asc';
        }

        $this->order = $order;
    }

    /**
     * Apply the ordering to an array of VisitInfo objects
     * @param VisitInfo[] $items
     * @return VisitInfo[]
     */
    public function getSortedItems(array $items): array
    {
        usort($items, function (VisitInfo $a, VisitInfo $b) {
            $aValue = $a->getValueByKey($this->getKey());
            $bValue = $b->getValueByKey($this->getKey());

            // Don't sort invalid keys
            if ($aValue === null || $bValue === null) {
                return 0;
            }

            if ($aValue === $bValue) {
                return 0;
            }

            if ($this->getOrder() === 'asc') {
                return ($aValue < $bValue) ? -1 : 1;
            } else {
                return ($aValue > $bValue) ? -1 : 1;
            }
        });

        return $items;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getOrder(): string
    {
        return $this->order;
    }
}
