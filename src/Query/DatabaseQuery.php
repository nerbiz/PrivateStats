<?php

namespace Nerbiz\PrivateStats\Query;

use Nerbiz\PrivateStats\Handlers\DatabaseConnection;
use Nerbiz\PrivateStats\VisitInfo;

class DatabaseQuery extends AbstractQuery
{
    /**
     * The connection to perform queries with
     * @var DatabaseConnection
     */
    protected $databaseConnection;

    /**
     * @param DatabaseConnection $databaseConnection
     */
    public function __construct(DatabaseConnection $databaseConnection)
    {
        $this->databaseConnection = $databaseConnection;
    }

    /**
     * {@inheritdoc}
     */
    public function get(): array
    {
        return [];
    }
}
