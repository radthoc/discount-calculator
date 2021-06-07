<?php

namespace App\Services\Interfaces;

interface FileServiceInterface
{
    /**
     * @param string $fileName
     *
     * @return Iterator
     */
    public function readFile(string $fileName);

    /**
     * @param string $row
     *
     * @return array
     */
    public function getRowColumns(string $row): array;
}
