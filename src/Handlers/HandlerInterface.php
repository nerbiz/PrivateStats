<?php

namespace Nerbiz\PrivateStats\Handlers;

use Nerbiz\PrivateStats\Query\AbstractQuery;
use Nerbiz\PrivateStats\VisitInfo;

interface HandlerInterface
{
    /**
     * Store information about a page visit
     * @param VisitInfo $visitInfo
     * @return bool Indicates whether storing was successful
     */
    public function store(VisitInfo $visitInfo): bool;

    /**
     * Get a query object to get items with
     * @return AbstractQuery
     */
    public function getQuery(): AbstractQuery;
}
