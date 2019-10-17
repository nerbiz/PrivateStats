<?php

namespace Nerbiz\PrivateStats\Handlers;

use Exception;
use Nerbiz\PrivateStats\DatabaseDrivers\AbstractDatabaseDriver;
use Nerbiz\PrivateStats\DatabaseDrivers\MySqlDatabaseDriver;
use PDO;

class DatabaseConnection
{
    /**
     * The PDO object of the connection
     * @var PDO
     */
    protected $pdo;

    /**
     * The prefix of the table name to store the visit info in
     * @var string
     */
    protected $tableNamePrefix;

    /**
     * The table name to store the visit info in
     * @var string
     */
    protected $tableName;

    /**
     * The driver for the database handling
     * @var AbstractDatabaseDriver
     */
    protected $driver;

    /**
     * @param PDO    $pdo
     * @param string $tableNamePrefix
     * @param string $tableName
     * @throws Exception
     */
    public function __construct(PDO $pdo, string $tableNamePrefix, string $tableName)
    {
        $this->pdo = $pdo;
        $this->tableNamePrefix = $tableNamePrefix;
        $this->tableName = $tableName;

        $pdoDriver = $this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME);

        if ($pdoDriver === 'mysql') {
            $this->driver = new MySqlDatabaseDriver($this);
        } else {
            throw new Exception(sprintf(
                "%s(): database type '%s' is not supported yet",
                __METHOD__,
                $pdoDriver
            ));
        }
    }

    /**
     * @return PDO
     */
    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    /**
     * @return string
     */
    public function getTableNamePrefix(): string
    {
        return $this->tableNamePrefix;
    }

    /**
     * @return string
     */
    public function getTableName(): string
    {
        return $this->tableName;
    }

    /**
     * @return AbstractDatabaseDriver
     */
    public function getDriver(): AbstractDatabaseDriver
    {
        return $this->driver;
    }

    /**
     * Get the full table name, including prefix
     * @return string
     */
    public function getFullTableName(): string
    {
        return $this->getTableNamePrefix() . $this->getTableName();
    }
}
