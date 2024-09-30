<?php

namespace App\Controller;

use App\Dto\EventInput;
use App\Repository\ReadEventRepository;
use App\Repository\WriteEventRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class EventController
{
    public function __construct(private readonly WriteEventRepository $writeEventRepository, private readonly ReadEventRepository $readEventRepository)
    {
    }

    #[Route(path: '/api/event/{id}/update', name: 'api_commit_update', methods: ['PUT'])]
    public function update(
        int $id,
        #[MapRequestPayload(validationFailedStatusCode: Response::HTTP_BAD_REQUEST)]
        EventInput $eventInput,
    ): Response {
        if (false === $this->readEventRepository->exist($id)) {
            throw new NotFoundHttpException(sprintf('Event identified by %d not found !', $id));
        }

        try {
            $this->writeEventRepository->update($eventInput, $id);
        } catch (\Exception) {
            return new Response(null, Response::HTTP_SERVICE_UNAVAILABLE);
        }

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
