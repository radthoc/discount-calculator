<?php

namespace Tests\Unit;

use Illuminate\Support\Carbon;
use App\Services\FileService;
use Tests\TestCase;

class FileServiceTest extends TestCase
{
    public function testReadFile()
    {
        $fileName = base_path() . DIRECTORY_SEPARATOR . 'input.txt';
        $expectedRows = 20;

        $fileService = $this->app->make(FileService::class);

        $fileIterator = $fileService->readfile($fileName);

        $this->assertIsIterable($fileIterator);
        $this->assertEquals($expectedRows, count(iterator_to_array($fileIterator)));
    }

    public function testFileContent()
    {
        $fileName = base_path() . DIRECTORY_SEPARATOR . 'input.txt';
        $expectedRows = 20;

        $fileService = $this->app->make(FileService::class);

        $fileIterator = $fileService->readfile($fileName);

        $this->assertIsIterable($fileIterator);
        
        foreach ($fileIterator as $row) {
            $columns = $fileService->getRowColumns($row);
            $this->assertIsArray($columns);
            $this->assertTrue($this->validateDate($columns[0]));
        }
    }

    private function validateDate($date, $format = 'Y-m-d')
    {
        $dateObject = Carbon::createFromFormat($format, $date);

        return $dateObject && $dateObject->format($format) === $date;
    }
}
