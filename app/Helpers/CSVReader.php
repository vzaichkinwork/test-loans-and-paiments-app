<?php

namespace App\Helpers;

/**
 * Definitely it's better to create service & put it in the service container.
 * And we need to split this class into two (services): one for parsing and
 * one for string source to follow SOLID.
 *
 * And no request errors processing here.
 *
 * Class CSVReader.
 */
class CSVReader
{
    public static function readFile(
        string $file,
        ?int $blockSize = null,
        string $delimiter = ",",
        string $enclosure = '"',
        string $escape = "\\") : ?array
    {
        $csv = file_get_contents($file);
        return self::readString($csv, $blockSize, $delimiter, $enclosure, $escape);
    }

    public static function readString(
        string $csv,
        ?int $blockSize = null,
        string $delimiter = ",",
        string $enclosure = '"',
        string $escape = "\\") : ?array
    {
        $blockSize = $blockSize ?: self::defineBlockSize($csv, $delimiter, $enclosure, $escape);

        $csv = str_replace("\n", "$delimiter\n", trim($csv));
        $csv = str_getcsv($csv, $delimiter, $enclosure, $escape);
        return array_chunk($csv, $blockSize);
    }

    public static function defineBlockSize(
        string $csv,
        string $delimiter = ",",
        string $enclosure = '"',
        string $escape = "\\") : int
    {
        $delimiterPos = mb_strpos($csv, "\n");

        $firstRow = mb_substr($csv, 0, $delimiterPos);
        $row = str_getcsv($firstRow, $delimiter, $enclosure, $escape);

        return count($row);
    }
}
