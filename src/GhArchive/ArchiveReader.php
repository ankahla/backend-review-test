<?php

namespace App\GhArchive;

final class ArchiveReader
{
    public static function read(string $filename): \Generator
    {
        $handle = gzopen($filename, 'r');

        while (gzeof($handle) === false) {
            yield gzgets($handle);
        }

        gzclose($handle);
    }
}
