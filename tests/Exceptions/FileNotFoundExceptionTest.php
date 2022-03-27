<?php

namespace MarkWalet\Changelog\Tests\Exceptions;

use MarkWalet\Changelog\Exceptions\FileNotFoundException;
use PHPUnit\Framework\TestCase;

class FileNotFoundExceptionTest extends TestCase
{
    /** @test */
    public function it_can_create_an_exception_instance(): void
    {
        $exception = new FileNotFoundException('test-file');

        $message = $exception->getMessage();

        $this->assertEquals('File `test-file` is not found.', $message);
    }
}
