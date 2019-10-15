<?php

namespace Nerbiz\PrivateStats\Collections;

use Nerbiz\PrivateStats\VisitInfo;

class VisitInfoCollection
{
    /**
     * @var VisitInfo[]
     */
    protected $items = [];

    /**
     * @param VisitInfo[] $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * Add an item to the collection
     * @param VisitInfo $visitInfo
     * @return void
     */
    public function add(VisitInfo $visitInfo): void
    {
        $this->items[] = $visitInfo;
    }

    /**
     * Get the collection as an array
     * @return array
     */
    public function toArray(): array
    {
        return $this->items;
    }
}
