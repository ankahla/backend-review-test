<?php

namespace App\Repository;

use App\Dto\GhEvent;

interface WriteEventRepository
{
    public function insert(GhEvent $ghEvent): void;
}
