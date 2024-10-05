<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'repo')]
#[ORM\Entity]
class Repo
{
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'bigint')]
        #[ORM\GeneratedValue(strategy: 'NONE')]
        private readonly int $id,
        #[ORM\Column(type: 'string')]
        public string $name,
        #[ORM\Column(type: 'string')]
        public string $url,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
