<?php

namespace Nerbiz\PrivateStats;

class VisitInfo
{
    /**
     * The timestamp of the visit
     * @var int
     */
    protected $timestamp;

    /**
     * The remote IP address of the visit
     * @var string
     */
    protected $ipAddress;

    /**
     * The URL of the visit
     * @var string
     */
    protected $url;

    /**
     * The referring URL of the visit
     * @var string|null
     */
    protected $referringUrl;

    /**
     * Fill the properties of this instance
     * @return void
     */
    public function setCurrentValues(): void
    {
        $this->timestamp = time();
        $this->ipAddress = Server::getRemoteAddress();
        $this->url = Server::getRequestUri();
        $this->referringUrl = Server::getReferrer();
    }

    /**
     * @return int
     */
    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    /**
     * @return string
     */
    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string|null
     */
    public function getReferringUrl(): ?string
    {
        return $this->referringUrl;
    }
}
