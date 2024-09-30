<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: '`event`')]
#[ORM\Index(name: 'IDX_EVENT_TYPE', columns: ['type'])]
#[ORM\Entity]
class Event
{
    #[ORM\Column(type: 'EventType', nullable: false)]
    private readonly string $type;

    #[ORM\Column(type: 'integer', nullable: false)]
    private int $count = 1;

    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'bigint')]
        #[ORM\GeneratedValue(strategy: 'NONE')]
        private readonly int $id, string $type,
        #[ORM\JoinColumn(name: 'actor_id', referencedColumnName: 'id')]
        #[ORM\ManyToOne(targetEntity: Actor::class, cascade: ['persist'])]
        private readonly Actor $actor, #[ORM\JoinColumn(name: 'repo_id', referencedColumnName: 'id')]
        #[ORM\ManyToOne(targetEntity: Repo::class, cascade: ['persist'])]
        private readonly Repo $repo,
        #[ORM\Column(type: 'json', nullable: false, options: ['jsonb' => true])]
        private readonly array $payload,
        #[ORM\Column(type: 'datetime_immutable', nullable: false)]
        private readonly \DateTimeImmutable $createAt,
        #[ORM\Column(type: 'text', nullable: true)]
        private readonly ?string $comment,
    ) {
        EventType::assertValidChoice($type);
        $this->type = $type;

        if (EventType::COMMIT === $type) {
            $this->count = $this->payload['size'] ?? 1;
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function getActor(): Actor
    {
        return $this->actor;
    }

    public function getRepo(): Repo
    {
        return $this->repo;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function getCreateAt(): \DateTimeImmutable
    {
        return $this->createAt;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }
}
