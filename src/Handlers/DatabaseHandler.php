<?php

namespace Nerbiz\PrivateStats\Handlers;

use Exception;
use Nerbiz\PrivateStats\Query\ReadQuery;
use Nerbiz\PrivateStats\VisitInfo;
use Nerbiz\PrivateStats\VisitInfoCollection;
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
    public function read(?ReadQuery $readQuery = null): VisitInfoCollection
    {
        // Create an empty query object, if none given
        if ($readQuery === null) {
            $readQuery = new ReadQuery();
        }

        $driver = $this->databaseConnection->getDriver();
        $driver->ensureTable();
        $driver->ensureColumns();

        $selectStatement = $driver->getSelectStatement($readQuery);

        // Create VisitInfo instances from fetched rows
        $rows = array_map(function ($item) {
            return VisitInfo::fromStdClass($item);
        }, $selectStatement->fetchAll(PDO::FETCH_OBJ));

        return new VisitInfoCollection($rows);
    }
}
