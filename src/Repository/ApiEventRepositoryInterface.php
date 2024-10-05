<?php

namespace App\Repository;

use App\Dto\GhEvent;

interface ApiEventRepositoryInterface
{
    /**
     * @return \Generator<GhEvent>
     */
    public function findAll(string $year, string $month, string $day, string $hour): \Generator;
}
