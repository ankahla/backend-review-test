<?php

namespace App\Tests\Unit\Repository;

use App\Dto\GhEvent;
use App\Repository\ApiEventRepositoryInterface;
use App\Repository\GhArchiveEventRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\SerializerInterface;

final class GhArchiveEventRepositoryTest extends TestCase
{
    protected SerializerInterface|MockObject $serializer;
    protected ApiEventRepositoryInterface $ghArchiveEventRepository;

    public function setUp(): void
    {
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->ghArchiveEventRepository = new GhArchiveEventRepository(
            $this->serializer,
            sys_get_temp_dir()
        );

        $gzFile = sys_get_temp_dir().'/2024-05-14-23.json.gz';
        $this->createFakeGzFile($gzFile, ['{"id": 1}', '{"id": 2}']);
    }

    public function testFindAll(): void
    {
        $firstEvent = new GhEvent();
        $secondEvent = new GhEvent();

        $this->serializer->expects(self::exactly(2))
            ->method('deserialize')
            ->willReturnOnConsecutiveCalls($firstEvent, $secondEvent);

        $iterator = $this->ghArchiveEventRepository->findAll('2024', '05', '14', '23');
        $events = iterator_to_array($iterator);

        self::assertCount(2, $events);
        self::assertSame($firstEvent, $events[0]);
    }

    private function createFakeGzFile(string $filename, array $data): string
    {
        $string = implode("\n", $data);
        $gz = gzopen($filename,'w9');
        gzwrite($gz, $string);
        gzclose($gz);

        return $filename;
    }
}
