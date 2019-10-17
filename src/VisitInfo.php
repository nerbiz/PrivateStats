<?php

namespace Nerbiz\PrivateStats;

use Jenssegers\Date\Date;

class VisitInfo
{
    /**
     * The timestamp of the visit
     * @var int
     */
    protected $timestamp;

    /**
     * A date representation of the timestamp of the visit
     * @var string
     */
    protected $date;

    /**
     * The hashed remote IP address of the visit
     * @var string
     */
    protected $ipHash;

    /**
     * The URL of the visit
     * @var string
     */
    protected $url;

    /**
     * The referring URL of the visit
     * @var string|null
     */
    protected $referrer;

    /**
     * Fill the properties of this instance
     * @return void
     */
    public function setCurrentValues(): void
    {
        $this->setTimestamp(time());
        $this->setDateFromTimestamp($this->getTimestamp());
        // Hash the IP address for anonymity
        $this->setIpHash(hash('sha256', Server::getRemoteAddress()));
        $this->setUrl(Server::getRequestUri());
        $this->setReferrer(Server::getReferrer());
    }

    /**
     * @return int
     */
    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    /**
     * @param int $timestamp
     * @return self
     */
    public function setTimestamp(int $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @param string $date
     * @return self
     */
    public function setDate(string $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @param int $timestamp
     * @return self
     */
    public function setDateFromTimestamp(int $timestamp): self
    {
        $this->date = Date::createFromTimestamp($timestamp)
            ->format('Y-m-d H:i:s O');

        return $this;
    }

    /**
     * @return string
     */
    public function getIpHash(): string
    {
        return $this->ipHash;
    }

    /**
     * @param string $ipHash
     * @return self
     */
    public function setIpHash(string $ipHash): self
    {
        $this->ipHash = $ipHash;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return self
     */
    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getReferrer(): ?string
    {
        return $this->referrer;
    }

    /**
     * @param string|null $referrer
     * @return self
     */
    public function setReferrer(?string $referrer): self
    {
        $this->referrer = $referrer;

        return $this;
    }
}
