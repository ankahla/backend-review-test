<?php

namespace App\ValueResolver;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsTargetedValueResolver;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[AsTargetedValueResolver('event')]
class EventValueResolver implements ValueResolverInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();

        if (Event::class !== $argumentType) {
            return [];
        }

        $id = $request->attributes->get('id');

        if (!is_string($id)) {
            return [];
        }

        $event = $this->entityManager->getRepository(Event::class)->find($id);

        if (!$event) {
            throw new NotFoundHttpException(sprintf('Event identified by %d not found !', $id));
        }

        return [$event];
    }
}
