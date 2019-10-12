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
     * A list of IP addresses to exclude in statistics
     * @var array
     */
    protected $excludeIps = [];

    /**
     * @param HandlerInterface $handler
     */
    public function __construct(HandlerInterface $handler)
    {
        $this->handler = $handler;
    }

    /**
     * Exclude an IP address from appearing in statistics
     * @param string $ipAddress
     */
    public function addExcludeIp(string $ipAddress): void
    {
        $this->excludeIps[] = $ipAddress;
    }

    /**
     * Store the current visit information
     * @return bool Indicates whether storing was successful
     */
    public function storeCurrentVisitInfo(): bool
    {
        // Check if the current visit should be excluded
        if (in_array(Server::getRemoteAddress(), $this->excludeIps, true)) {
            return false;
        }

        $visitInfo = new VisitInfo();
        $visitInfo->setCurrentValues();

        return $this->handler->store($visitInfo);
    }
}
