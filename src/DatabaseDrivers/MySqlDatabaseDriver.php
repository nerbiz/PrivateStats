<?php

namespace Nerbiz\PrivateStats\DatabaseDrivers;

use Nerbiz\PrivateStats\Query\ReadQuery;
use PDOStatement;

class MySqlDatabaseDriver extends AbstractDatabaseDriver
{
    /**
     * @var int
     */
    const VARCHAR_LENGTH = 191;

    /**
     * {@inheritdoc}
     */
    public function ensureTable(): void
    {
        $this->databaseConnection
            ->getPdo()
            ->exec(sprintf(
                'create table if not exists `%s` (
                    `id` int(10) unsigned not null auto_increment,
                    `timestamp` int(10) unsigned not null,
                    %s,
                    %s,
                    %s,
                    primary key (`id`)
                )',
                $this->databaseConnection->getFullTableName(),
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
        $statement = $this->databaseConnection
            ->getPdo()
            ->query(sprintf(
                'show columns
                from `%s`',
                $this->databaseConnection->getFullTableName()
            ));

        $currentColumns = array_map(function ($row) {
            return $row->Field;
        }, $statement->fetchAll());

        $missingColumns = array_diff($this->requiredColumns, $currentColumns);

        // Add any missing columns
        foreach ($missingColumns as $columnName) {
            $this->databaseConnection
                ->getPdo()
                ->exec(sprintf(
                    'alter table `%s`
                    add column %s',
                    $this->databaseConnection->getFullTableName(),
                    $this->getColumnDefinition($columnName)
                ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPreparedInsertStatement(): PDOStatement
    {
        $columns = [];
        $placeholders = [];

        // Create the column and placeholder values
        foreach ($this->requiredColumns as $columnName) {
            $columns[] = '`' . $columnName . '`';
            $placeholders[] = ':' . $columnName;
        }

        return $this->databaseConnection
            ->getPdo()
            ->prepare(sprintf(
                'insert into `%s`
                (%s)
                values(%s)',
                $this->databaseConnection->getFullTableName(),
                implode(', ', $columns),
                implode(', ', $placeholders)
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function getSelectStatement(ReadQuery $readQuery): PDOStatement
    {
        // Create where queries per clause
        $whereQueries = array_map(function ($whereClause) {
            return sprintf(
                "`%s` %s '%s'",
                $whereClause->getKey(),
                $whereClause->getOperator(),
                $whereClause->getValue()
            );
        }, $readQuery->getWhereClauses());

        // Construct the full where query
        $fullWhereQuery = (count($whereQueries) > 0)
            ? 'where ' . implode(' and ', $whereQueries)
            : '';
        
        return $this->databaseConnection
            ->getPdo()
            ->query(sprintf(
                'select *
                from `%s`
                %s',
                $this->databaseConnection->getFullTableName(),
                $fullWhereQuery
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function filterBeforeInsert(array $values): array
    {
        // Values can't exceed the maximum character length
        if (isset($values['url'])) {
            $values['url'] = substr($values['url'], 0, static::VARCHAR_LENGTH);
        }

        if (isset($values['referrer'])) {
            $values['referrer'] = substr($values['referrer'], 0, static::VARCHAR_LENGTH);
        }

        return $values;
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
                return sprintf('`ip_hash` varchar(%d) null', static::VARCHAR_LENGTH);
            case 'url':
                return sprintf('`url` varchar(%d) null', static::VARCHAR_LENGTH);
            case 'referrer':
                return sprintf('`referrer` varchar(%d) null', static::VARCHAR_LENGTH);
            default:
                return '';
        }
    }
}
