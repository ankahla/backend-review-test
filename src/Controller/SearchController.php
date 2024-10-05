<?php

namespace App\Controller;

use App\Dto\SearchInput;
use App\Services\EventSearchInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Annotation\Route;

class SearchController
{
    public function __construct(private readonly EventSearchInterface $eventSearch)
    {
    }

    #[Route(path: '/api/search', name: 'api_search', methods: ['GET'])]
    public function searchCommits(#[MapQueryString] SearchInput $searchInput): JsonResponse
    {
        return new JsonResponse(($this->eventSearch)($searchInput));
    }
}
