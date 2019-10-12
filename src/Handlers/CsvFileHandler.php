<?php

namespace Nerbiz\PrivateStats\Handlers;

use Nerbiz\PrivateStats\VisitInfo;

class CsvFileHandler extends AbstractFileHandler implements HandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function store(VisitInfo $visitInfo): bool
    {
        return true;
    }
}
