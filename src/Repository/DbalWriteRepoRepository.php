<?php

namespace App\Repository;

use App\Dto\GhRepo;
use Doctrine\DBAL\Connection;

class DbalWriteRepoRepository implements WriteRepoRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function insert(GhRepo $ghRepo): void
    {
        $sql = <<<SQL
        INSERT INTO repo (id, name, url)
        VALUES (:id, :name,  :url)
SQL;

        $this->connection->executeQuery($sql, [
            'id' => $ghRepo->id,
            'name' => $ghRepo->name,
            'url' => $ghRepo->url,
        ]);
    }
}
