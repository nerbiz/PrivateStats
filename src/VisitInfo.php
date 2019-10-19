<?php

namespace Nerbiz\PrivateStats;

use Jenssegers\Date\Date;
use stdClass;

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
     * A mapping of array keys with corresponding class properties
     * @var array
     */
    protected static $keysPropertiesMap = [
        'timestamp' => 'timestamp',
        'date' => 'date',
        'ip_hash' => 'ipHash',
        'url' => 'url',
        'referrer' => 'referrer',
    ];

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
     * Create a new instance from an array
     * @param array $values
     * @return self
     */
    public static function fromArray(array $values): self
    {
        return static::fromStdClass((object)$values);
    }

    /**
     * Create an array of this instance's properties
     * @return array
     */
    public function toArray(): array
    {
        $array = [];
        foreach (static::getKeysPropertiesMap() as $key => $property) {
            $array[$key] = $this->{'get' . ucfirst($property)}();
        }

        return $array;
    }

    /**
     * Create a new instance from a stdClass instance
     * @param stdClass $values
     * @return VisitInfo
     */
    public static function fromStdClass(stdClass $values): self
    {
        $instance = new static();

        $instance->setTimestamp($values->timestamp ?? '');
        if (isset($values->date)) {
            $instance->setDate($values->date);
        } else {
            $instance->setDateFromTimestamp($values->timestamp ?? '');
        }
        $instance->setIpHash($values->ip_hash ?? '');
        $instance->setUrl($values->url ?? '');
        $instance->setReferrer($values->referrer ?? '');

        return $instance;
    }

    /**
     * Create an stdClass of this instance's properties
     * @return stdClass
     */
    public function toStdClass(): stdClass
    {
        return (object)$this->toArray();
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

    /**
     * @return array
     */
    public static function getKeysPropertiesMap(): array
    {
        return static::$keysPropertiesMap;
    }
}
