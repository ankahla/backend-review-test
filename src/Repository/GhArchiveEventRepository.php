<?php

namespace App\Repository;

use App\Dto\GhEvent;
use App\Services\ArchiveReader;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class GhArchiveEventRepository implements ApiEventRepositoryInterface
{
    public function __construct(
        protected SerializerInterface $serializer,
        private readonly string $ghArchiveHost,
    ) {
    }

    public function findAll(string $year, string $month, string $day, string $hour): \Generator
    {
        $filename = sprintf('%s/%s-%s-%s-%s.json.gz', $this->ghArchiveHost, $year, $month, $day, $hour);
        $items = ArchiveReader::read($filename);

        foreach ($items as $line) {
            yield $this->serializer->deserialize($line, GhEvent::class, JsonEncoder::FORMAT);
        }
    }
}
