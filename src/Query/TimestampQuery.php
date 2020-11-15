<?php

namespace Nerbiz\PrivateStats\Query;

class TimestampQuery
{
    /**
     * Various chunking options
     * @var string
     */
    const CHUNK_MINUTE = 'minute';
    const CHUNK_HOUR = 'hour';
    const CHUNK_DAY = 'day';
    const CHUNK_WEEK = 'week';
    const CHUNK_YEAR = 'year';

    /**
     * The start timestamp of the stats
     * @var int
     */
    protected $startTimestamp;

    /**
     * The end timestamp of the stats
     * @var int
     */
    protected $endTimestamp;

    /**
     * The size of the stats chunks
     * @var string
     */
    protected $chunkSize;

    /**
     * @param int    $startTimestamp
     * @param int    $endTimestamp
     * @param string $chunkSize
     */
    public function __construct(int $startTimestamp, int $endTimestamp, string $chunkSize)
    {
        $this->startTimestamp = $startTimestamp;
        $this->endTimestamp = $endTimestamp;
        $this->chunkSize = $chunkSize;
    }
}
