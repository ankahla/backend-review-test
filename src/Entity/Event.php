<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Table(name: '`event`')]
#[ORM\Index(name: 'IDX_EVENT_TYPE', columns: ['type'])]
#[ORM\Entity]
class Event
{
    #[ORM\Column(type: 'EventType', nullable: false)]
    private readonly string $type;

    #[ORM\Column(type: 'integer', nullable: false)]
    private int $count = 1;

    public function __construct(#[ORM\Id]
    #[ORM\Column(type: 'bigint')]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    private readonly int $id, string $type, #[ORM\JoinColumn(name: 'actor_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: \App\Entity\Actor::class, cascade: ['persist'])]
    private readonly Actor $actor, #[ORM\JoinColumn(name: 'repo_id', referencedColumnName: 'id')]
    #[ORM\ManyToOne(targetEntity: \App\Entity\Repo::class, cascade: ['persist'])]
    private readonly Repo $repo, #[ORM\Column(type: 'json', nullable: false, options: ['jsonb' => true])]
    private readonly array $payload, #[ORM\Column(type: 'datetime_immutable', nullable: false)]
    private readonly \DateTimeImmutable $createAt, #[ORM\Column(type: 'text', nullable: true)]
    private readonly ?string $comment)
    {
        EventType::assertValidChoice($type);
        $this->type = $type;

        if ($type === EventType::COMMIT) {
            $this->count = $this->payload['size'] ?? 1;
        }
    }

    public function id(): int
    {
        return $this->id;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function actor(): Actor
    {
        return $this->actor;
    }

    public function repo(): Repo
    {
        return $this->repo;
    }

    public function payload(): array
    {
        return $this->payload;
    }

    public function createAt(): \DateTimeImmutable
    {
        return $this->createAt;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }
}
