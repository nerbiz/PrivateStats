<?php

namespace Nerbiz\PrivateStats\Handlers;

use Nerbiz\PrivateStats\Collections\VisitInfoCollection;
use Nerbiz\PrivateStats\VisitInfo;

interface HandlerInterface
{
    /**
     * Store information about a page visit
     * @param VisitInfo $visitInfo
     * @return bool Indicates whether storing was successful
     */
    public function write(VisitInfo $visitInfo): bool;

    /**
     * Get stored information
     * @return VisitInfoCollection
     */
    public function read(): VisitInfoCollection;
}
