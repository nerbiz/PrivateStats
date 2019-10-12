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
     * @param string $tableName
     */
    public function __construct(PDO $connection, string $tableName = 'private_stats')
    {
        $this->connection = $connection;
        $this->tableName = $tableName;
    }

    /**
     * {@inheritdoc}
     */
    public function store(VisitInfo $visitInfo): bool
    {
        return true;
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
                'CREATE TABLE IF NOT EXISTS `%s` (
                    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                    `ip_hash` varchar(191) NULL,
                    `url` varchar(191) NULL,
                    `referring_url` varchar(191) NULL,
                    `timestamp` int(10) unsigned NOT NULL,
                    PRIMARY KEY (`id`)
                )',
                $this->tableName
            );
        }

        return null;
    }
}
