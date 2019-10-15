<?php

namespace Nerbiz\PrivateStats\Drivers;

use Nerbiz\PrivateStats\Handlers\DatabaseConnection;
use PDOStatement;

abstract class AbstractDatabaseDriver
{
    /**
     * The database connection
     * @var DatabaseConnection
     */
    protected $databaseConnection;

    /**
     * The columns that the statistics table must have
     * @var array
     */
    protected $requiredColumns = ['timestamp', 'ip_hash', 'url', 'referrer'];

    public function __construct(DatabaseConnection $databaseConnection)
    {
        $this->databaseConnection = $databaseConnection;
    }

    /**
     * Make sure the statistics table exists
     * @return void
     */
    abstract public function ensureTable(): void;

    /**
     * Make sure the statistics table has all the required columns
     * @return void
     */
    abstract public function ensureColumns(): void;

    /**
     * Get a prepared statement for inserting statistics into a database
     * @return PDOStatement
     */
    abstract public function getPreparedInsertStatement(): PDOStatement;

    /**
     * Make optional adjustments, before inserting data into the database
     * @param array $values
     * @return array
     */
    abstract public function filterBeforeInsert(array $values): array;
}
