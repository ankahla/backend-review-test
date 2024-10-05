<?php

namespace App\Services;

use App\Dto\SearchInput;
use App\Dto\SearchOutput;
use App\Entity\EventType;
use App\Repository\ReadEventRepository;

final class EventSearch implements EventSearchInterface
{
    public function __construct(private readonly ReadEventRepository $repository)
    {
    }

    public function __invoke(SearchInput $searchInput): SearchOutput
    {
        $countByType = $this->repository->countByType($searchInput);

        return new SearchOutput(
            [
                'totalEvents' => $this->repository->countAll($searchInput),
                'totalPullRequests' => $countByType[EventType::PULL_REQUEST] ?? 0,
                'totalCommits' => $countByType[EventType::COMMIT] ?? 0,
                'totalComments' => $countByType[EventType::COMMENT] ?? 0,
            ],
            [
                'events' => $this->repository->getLatest($searchInput),
                'stats' => $this->repository->statsByTypePerHour($searchInput),
            ]
        );
    }
}
