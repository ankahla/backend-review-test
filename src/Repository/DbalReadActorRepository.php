<?php

namespace App\Repository;

use Doctrine\DBAL\Connection;

class DbalReadActorRepository implements ReadActorRepository
{
    public function __construct(private readonly Connection $connection)
    {
    }

    public function exist(int $id): bool
    {
        $sql = <<<SQL
            SELECT 1
            FROM actor
            WHERE id = :id
        SQL;

        $result = $this->connection->fetchOne($sql, [
            'id' => $id
        ]);

        return (bool) $result;
    }
}
