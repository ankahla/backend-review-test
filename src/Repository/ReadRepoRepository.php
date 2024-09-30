<?php

namespace App\Repository;

interface ReadRepoRepository
{
    public function exist(int $id): bool;
}
