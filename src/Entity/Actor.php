<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'actor')]
#[ORM\Entity]
class Actor
{
    public function __construct(#[ORM\Id]
        #[ORM\Column(type: 'bigint')]
        #[ORM\GeneratedValue(strategy: 'NONE')]
        public int $id,
        #[ORM\Column(type: 'string')]
        public string $login,
        #[ORM\Column(type: 'string')]
        public string $url,
        #[ORM\Column(type: 'string')]
        public string $avatarUrl)
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getAvatarUrl(): string
    {
        return $this->avatarUrl;
    }
}
