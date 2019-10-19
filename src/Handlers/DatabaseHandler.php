<?php

namespace Nerbiz\PrivateStats\Handlers;

use Exception;
use Nerbiz\PrivateStats\Query\ReadQuery;
use Nerbiz\PrivateStats\VisitInfo;
use PDO;

class DatabaseHandler extends AbstractHandler
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
            ->execute($driver->filterBeforeInsert(
                $visitInfo->toArray()
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function read(ReadQuery $readQuery): array
    {
        $driver = $this->databaseConnection->getDriver();
        $driver->ensureTable();
        $driver->ensureColumns();

        $selectStatement = $driver->getSelectStatement($readQuery);

        // Create VisitInfo instances from fetched rows
        return array_map(function ($item) {
            return VisitInfo::fromStdClass($item);
        }, $selectStatement->fetchAll());
    }
}
