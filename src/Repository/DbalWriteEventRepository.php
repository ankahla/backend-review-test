<?php

namespace App\Repository;

use App\Dto\EventInput;
use App\Dto\GhEvent;
use App\Entity\EventType;
use Doctrine\DBAL\Connection;

class DbalWriteEventRepository implements WriteEventRepository
{
    public function __construct(private readonly Connection $connection)
    {
    }

    public function update(EventInput $authorInput, int $id): void
    {
        $sql = <<<SQL
        UPDATE event
        SET comment = :comment
        WHERE id = :id
SQL;

        $this->connection->executeQuery($sql, ['id' => $id, 'comment' => $authorInput->comment]);
    }

    public function insert(GhEvent $ghEvent): void
    {
        $sql = <<<SQL
        INSERT INTO event (id, actor_id, repo_id, type, count, payload, comment, create_at)
        VALUES (:id, :actor_id, :repo_id, :type, :count, :payload, :comment, :created_at)
SQL;

        $eventType = match ($ghEvent->type) {
            GhEvent::PUSH_EVENT => EventType::COMMIT,
            GhEvent::PULL_REQUEST_EVENT => EventType::PULL_REQUEST,
            default => EventType::COMMENT,
        };

        $this->connection->executeQuery($sql, [
            'id' => $ghEvent->id,
            'actor_id' => $ghEvent->actor->id,
            'repo_id' => $ghEvent->repo->id,
            'type' => $eventType,
            'count' => $eventType === EventType::COMMIT ? count($ghEvent->payload['commits'] ?? []) : 0,
            'comment' => $eventType === EventType::COMMENT ? $ghEvent->payload['comment']['url'] : null,
            'payload' => json_encode($ghEvent->payload),
            'created_at' => $ghEvent->createdAt->format('c'),
        ]);
    }
}
