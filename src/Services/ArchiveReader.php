<?php

namespace App\Services;

final class ArchiveReader
{
    public static function read(string $filename): \Generator
    {
        $handle = gzopen($filename, 'r');

        while (false === gzeof($handle)) {
            yield gzgets($handle);
        }

        gzclose($handle);
    }
}
