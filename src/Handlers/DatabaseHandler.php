<?php

namespace Nerbiz\PrivateStats\Handlers;

use Nerbiz\PrivateStats\VisitInfo;
use PDO;

class DatabaseHandler implements HandlerInterface
{
    /**
     * The database connection
     * @var PDO
     */
    protected $connection;

    /**
     * The table name to store the visit info in
     * @var string
     */
    protected $tableName;

    /**
     * @param PDO    $connection
     * @param string $tableNamePrefix
     * @param string $tableName
     */
    public function __construct(PDO $connection, string $tableNamePrefix = '', string $tableName = 'private_stats')
    {
        $this->connection = $connection;
        $this->tableName = $tableNamePrefix . $tableName;
    }

    /**
     * {@inheritdoc}
     */
    public function store(VisitInfo $visitInfo): bool
    {
        $statement = $this->connection->prepare(
            'insert into `' . $this->tableName . '`
            (`ip_hash`, `url`, `referring_url`, `timestamp`)
            values(:ip_hash, :url, :referring_url, :timestamp)'
        );

        return $statement->execute([
            'ip_hash' => $visitInfo->getIpHash(),
            'url' => $visitInfo->getUrl(),
            'referring_url' => $visitInfo->getReferringUrl(),
            'timestamp' => $visitInfo->getTimestamp(),
        ]);
    }

    /**
     * Get the code for creating the stats table
     * @return string|null
     */
    public function getTableCreateCode(): ?string
    {
        $driver = $this->connection->getAttribute(PDO::ATTR_DRIVER_NAME);

        if ($driver === 'mysql') {
            return sprintf(
                'create table if not exists `%s` (
                    `id` int(10) unsigned not null auto_increment,
                    `ip_hash` varchar(191) null,
                    `url` varchar(191) null,
                    `referring_url` varchar(191) null,
                    `timestamp` int(10) unsigned NOT null,
                    primary key (`id`)
                )',
                $this->tableName
            );
        }

        return null;
    }
}
