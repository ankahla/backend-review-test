<?php

namespace App\Repository;

use App\GhArchive\ArchiveReader;
use App\Dto\GhEvent;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class GhArchiveEventRepository implements ApiEventRepositoryInterface
{
    public function __construct(
        protected SerializerInterface $serializer,
        private string $ghArchiveHost
    ) {
    }

    /**
     * @inheritDoc
     */
    public function findAll(string $year, string $month, string $day, string $hour): \Generator
    {
        $filename = sprintf('%s/%s-%s-%s-%s.json.gz', $this->ghArchiveHost, $year, $month, $day, $hour);
        $items = ArchiveReader::read($filename);

        foreach ($items as $line) {
            yield $this->serializer->deserialize($line, GhEvent::class, JsonEncoder::FORMAT);
        }
    }
}
