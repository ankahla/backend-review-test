<?php

namespace App\Repository;

use App\Dto\GhActor;
use Doctrine\DBAL\Connection;

class DbalWriteActorRepository implements WriteActorRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function insert(GhActor $ghActor): void
    {
        $sql = <<<SQL
        INSERT INTO actor (id, login, url, avatar_url)
        VALUES (:id, :login,  :url, :avatar_url)
SQL;

        $this->connection->executeQuery($sql, [
            'id' => $ghActor->id,
            'login' => $ghActor->login,
            'url' => $ghActor->url,
            'avatar_url' => $ghActor->avatarUrl,
        ]);
    }
}
