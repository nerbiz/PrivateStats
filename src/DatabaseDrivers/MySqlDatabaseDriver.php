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
                    %s,
                    %s,
                    %s,
                    %s,
                    %s,
                    primary key (`id`)
                )',
                $this->databaseConnection->getFullTableName(),
                $this->getColumnDefinition('id'),
                $this->getColumnDefinition('timestamp'),
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
    public function getSelectStatement(?ReadQuery $readQuery = null): PDOStatement
    {
        return $this->databaseConnection
            ->getPdo()
            ->query(sprintf(
                'select *
                from `%s`
                %s
                %s',
                $this->databaseConnection->getFullTableName(),
                ($readQuery !== null)
                    ? $this->createWhereQuery($readQuery)
                    : '',
                ($readQuery !== null)
                    ? $this->createOrderByQuery($readQuery)
                    : ''
            ));
    }

    /**
     * Create 'where' queries per clause
     * @param ReadQuery $readQuery
     * @return string
     */
    protected function createWhereQuery(ReadQuery $readQuery): string
    {
        $whereQueries = array_map(function ($whereClause) {
            return sprintf(
                "`%s` %s '%s'",
                $whereClause->getKey(),
                $whereClause->getOperator(),
                $whereClause->getValue()
            );
        }, $readQuery->getWhereClauses());

        // Construct the full 'where' query
        return (count($whereQueries) > 0)
            ? 'where ' . implode(' and ', $whereQueries)
            : '';
    }

    /**
     * Construct an 'order by' query
     * @param ReadQuery|null $readQuery
     * @return string
     */
    protected function createOrderByQuery(?ReadQuery $readQuery): string
    {
        $orderByClause = $readQuery->getOrderByClause();

        return ($orderByClause !== null)
            ? sprintf('order by `%s` %s', $orderByClause->getKey(), $orderByClause->getOrder())
            : '';
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
            case 'id':
                return '`id` int(10) unsigned not null auto_increment';
            case 'timestamp':
                return '`timestamp` int(10) unsigned not null';
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
