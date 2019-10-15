<?php

namespace Nerbiz\PrivateStats\Handlers;

use Exception;
use Nerbiz\PrivateStats\Query\AbstractQuery;
use Nerbiz\PrivateStats\Query\DatabaseQuery;
use Nerbiz\PrivateStats\VisitInfo;
use PDO;

class DatabaseHandler implements HandlerInterface
{
    /**
     * The object containing connection information
     * @var DatabaseConnection
     */
    protected $databaseConnection;

    /**
     * @param PDO    $pdo
     * @param string $tableNamePrefix
     * @param string $tableName
     * @throws Exception
     */
    public function __construct(PDO $pdo, string $tableNamePrefix = '', string $tableName = 'private_stats')
    {
        $this->databaseConnection = new DatabaseConnection($pdo, $tableNamePrefix, $tableName);
    }

    /**
     * {@inheritdoc}
     */
    public function store(VisitInfo $visitInfo): bool
    {
        $driver = $this->databaseConnection->getDriver();

        $driver->ensureTable();
        $driver->ensureColumns();

        return $driver
            ->getPreparedInsertStatement()
            ->execute($driver->filterBeforeInsert([
                'ip_hash' => $visitInfo->getIpHash(),
                'url' => $visitInfo->getUrl(),
                'referrer' => $visitInfo->getReferrer(),
                'timestamp' => $visitInfo->getTimestamp(),
            ]));
    }

    /**
     * {@inheritdoc}
     */
    public function getQuery(): AbstractQuery
    {
        return new DatabaseQuery($this->databaseConnection);
    }
}
