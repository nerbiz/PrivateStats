<?php

namespace Nerbiz\PrivateStats;

use Nerbiz\PrivateStats\Handlers\HandlerInterface;

class PrivateStats
{
    /**
     * The handler for storing visit information
     * @var HandlerInterface
     */
    protected $handler;

    /**
     * @param HandlerInterface $handler
     */
    public function __construct(HandlerInterface $handler)
    {
        $this->handler = $handler;
    }
}
