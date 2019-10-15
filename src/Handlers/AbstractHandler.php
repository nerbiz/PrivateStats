<?php

namespace Nerbiz\PrivateStats\Handlers;

use Nerbiz\PrivateStats\VisitInfo;

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
     * @return VisitInfo[]
     */
    abstract public function read(): array;
}
