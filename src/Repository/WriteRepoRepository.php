<?php

namespace App\Repository;

use App\Dto\GhRepo;

interface WriteRepoRepository
{
    public function insert(GhRepo $ghRepo): void;
}
