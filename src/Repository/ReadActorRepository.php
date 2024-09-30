<?php

namespace App\Repository;

interface ReadActorRepository
{
    public function exist(int $id): bool;
}
