<?php

namespace App\Services;

use App\Services\Interfaces\FileServiceInterface;

class FileService implements FileServiceInterface
{
    const COLUMN_SEPARATOR = ' ';

    /**
     * @param string $fileName
     *
     * @return Iterator
     */
    public function readFile(string $fileName)
    {
        $handle = fopen($fileName, "r");

        while (!feof($handle)) {
            yield trim(fgets($handle));
        }

        fclose($handle);
    }

    /**
     * @param string $row
     *
     * @return array
     */
    public function getRowColumns(string $row): array
    {
        return explode(self::COLUMN_SEPARATOR, $row);
    }
}
