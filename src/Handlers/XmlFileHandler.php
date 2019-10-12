<?php

namespace Nerbiz\PrivateStats\Handlers;

use Nerbiz\PrivateStats\VisitInfo;

class XmlFileHandler extends AbstractFileHandler implements HandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function store(VisitInfo $visitInfo): bool
    {
        return true;
    }
}
