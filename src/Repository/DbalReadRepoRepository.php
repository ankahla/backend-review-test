<?php

namespace App\Repository;

use Doctrine\DBAL\Connection;

class DbalReadRepoRepository implements ReadRepoRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function exist(int $id): bool
    {
        $sql = <<<SQL
            SELECT 1
            FROM repo
            WHERE id = :id
        SQL;

        $result = $this->connection->fetchOne($sql, [
            'id' => $id
        ]);

        return (bool) $result;
    }
}
