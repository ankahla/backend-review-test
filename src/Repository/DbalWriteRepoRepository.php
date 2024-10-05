<?php

namespace App\Repository;

use App\Dto\GhRepo;
use Doctrine\DBAL\Connection;

class DbalWriteRepoRepository implements WriteRepoRepository
{
    public function __construct(private readonly Connection $connection)
    {
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
