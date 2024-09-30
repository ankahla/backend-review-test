<?php

namespace App\Repository;

use App\Dto\EventInput;
use App\Dto\GhEvent;

interface WriteEventRepository
{
    public function update(EventInput $authorInput, int $id): void;

    public function insert(GhEvent $ghEvent): void;
}
