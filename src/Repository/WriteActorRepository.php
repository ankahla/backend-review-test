<?php

namespace App\Repository;

use App\Dto\GhActor;

interface WriteActorRepository
{
    public function insert(GhActor $ghActor): void;
}
