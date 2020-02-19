<?php

namespace Nerbiz\PrivateStats\Handlers;

use Nerbiz\PrivateStats\Query\ReadQuery;
use Nerbiz\PrivateStats\VisitInfo;
use Nerbiz\PrivateStats\VisitInfoCollection;

abstract class AbstractHandler
{
    /**
     * Store information about a page visit
     * @param VisitInfo $visitInfo
     * @return bool Indicates whether storing was successful
     */
    abstract public function write(VisitInfo $visitInfo): bool;

    /**
     * Get stored information
     * @param ReadQuery|null $readQuery
     * @return VisitInfoCollection
     */
    abstract public function read(?ReadQuery $readQuery = null): VisitInfoCollection;
}
