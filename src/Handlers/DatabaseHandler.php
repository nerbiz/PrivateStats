<?php

namespace Nerbiz\PrivateStats\Handlers;

use Exception;
use Nerbiz\PrivateStats\Collections\DatabaseQuery;
use Nerbiz\PrivateStats\Collections\VisitInfoCollection;
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
    public function write(VisitInfo $visitInfo): bool
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
    public function read(): VisitInfoCollection
    {
        return new VisitInfoCollection([]);
    }
}
