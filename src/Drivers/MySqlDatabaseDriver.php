<?php

namespace Nerbiz\PrivateStats\Drivers;

use PDOStatement;

class MySqlDatabaseDriver extends AbstractDatabaseDriver
{
    /**
     * {@inheritdoc}
     */
    public function ensureTable(): void
    {
        $this->connection->exec(sprintf(
            'create table if not exists `%s` (
                `id` int(10) unsigned not null auto_increment,
                `timestamp` int(10) unsigned not null,
                %s,
                %s,
                %s,
                primary key (`id`)
            )',
            $this->tableName,
            $this->getColumnDefinition('ip_hash'),
            $this->getColumnDefinition('url'),
            $this->getColumnDefinition('referrer')
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function ensureColumns(): void
    {
        $statement = $this->connection->query(sprintf(
            'show columns
            from `%s`',
            $this->tableName
        ));

        $requiredColumns = ['ip_hash', 'url', 'referrer'];
        $currentColumns = array_map(function ($row) {
            return $row->Field;
        }, $statement->fetchAll());

        $missingColumns = array_diff($requiredColumns, $currentColumns);

        // Add any missing columns
        foreach ($missingColumns as $columnName) {
            $this->connection->exec(sprintf(
                'alter table `%s`
                add column %s',
                $this->tableName,
                $this->getColumnDefinition($columnName)
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPreparedInsertStatement(): PDOStatement
    {
        return $this->connection->prepare(sprintf(
            'insert into `%s`
            (`ip_hash`, `url`, `referrer`, `timestamp`)
            values(:ip_hash, :url, :referrer, :timestamp)',
            $this->tableName
        ));
    }

    /**
     * Get a column definition for creating or altering a table
     * @param string $columnName
     * @return string
     */
    protected function getColumnDefinition(string $columnName): string
    {
        switch ($columnName) {
            case 'ip_hash':
                return '`ip_hash` varchar(191) null';
            case 'url':
                return '`url` varchar(191) null';
            case 'referrer':
                return '`referrer` varchar(191) null';
            default:
                return '';
        }
    }
}
