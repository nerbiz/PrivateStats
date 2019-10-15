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
     * Supports wildcard (*) character
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
        foreach ($this->excludeIps as $excludeIp) {
            // Create the regular expression, replace wildcard character
            $regex = preg_quote($excludeIp);
            $regex = str_replace('\*', '.+', $regex);
            $regex = sprintf('/^%s$/', $regex);

            if (preg_match('/^'.$regex.'$/', Server::getRemoteAddress()) === 1) {
                return false;
            }
        }

        // Store the current visit information
        $visitInfo = new VisitInfo();
        $visitInfo->setCurrentValues();

        return $this->handler->store($visitInfo);
    }

    /**
     * @return HandlerInterface
     */
    public function getHandler(): HandlerInterface
    {
        return $this->handler;
    }
}
