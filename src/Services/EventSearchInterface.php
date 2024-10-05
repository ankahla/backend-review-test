<?php

namespace App\Services;

use App\Dto\SearchInput;
use App\Dto\SearchOutput;

interface EventSearchInterface
{
    public function __invoke(SearchInput $searchInput): SearchOutput;
}
