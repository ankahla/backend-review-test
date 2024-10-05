<?php

namespace App\Tests\Unit\GhArchive;

use App\Services\ArchiveReader;
use PHPUnit\Framework\TestCase;

final class ArchiveReaderTest extends TestCase
{
    public function testRead(): void
    {
        $filename = tempnam(sys_get_temp_dir(), 'test');
        $string = "Some information to compress\n2nd line";
        $gz = gzopen($filename, 'w9');
        gzwrite($gz, $string);
        gzclose($gz);

        $lines = iterator_to_array(ArchiveReader::read($filename));
        self::assertCount(2, $lines);
        self::assertSame('2nd line', $lines[1]);
    }
}
