<?php

namespace Nerbiz\PrivateStats\Handlers;

use Exception;
use Nerbiz\PrivateStats\Drivers\DatabaseDriverInterface;
use Nerbiz\PrivateStats\Drivers\MySqlDatabaseDriver;
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
     * The driver for the database handling
     * @var DatabaseDriverInterface
     */
    protected $driver;

    /**
     * @param PDO    $connection
     * @param string $tableNamePrefix
     * @param string $tableName
     * @throws Exception
     */
    public function __construct(PDO $connection, string $tableNamePrefix = '', string $tableName = 'private_stats')
    {
        $this->connection = $connection;
        $this->tableName = $tableNamePrefix . $tableName;

        $pdoDriver = $this->connection->getAttribute(PDO::ATTR_DRIVER_NAME);
        if ($pdoDriver === 'mysql') {
            $this->driver = new MySqlDatabaseDriver($this->connection, $this->tableName);
        } else {
            throw new Exception(sprintf(
                "%s(): database type '%s' is not supported yet",
                __METHOD__,
                $pdoDriver
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function store(VisitInfo $visitInfo): bool
    {
        $this->driver->ensureTable();
        $this->driver->ensureColumns();

        return $this->driver
            ->getPreparedInsertStatement()
            ->execute([
                'ip_hash' => $visitInfo->getIpHash(),
                'url' => $visitInfo->getUrl(),
                'referrer' => $visitInfo->getReferrer(),
                'timestamp' => $visitInfo->getTimestamp(),
            ]);
    }
}
