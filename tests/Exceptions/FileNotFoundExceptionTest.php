<?php

namespace MarkWalet\Changelog\Tests\Exceptions;

use MarkWalet\Changelog\Exceptions\FileNotFoundException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class FileNotFoundExceptionTest extends TestCase
{
    #[Test]
    public function it_can_create_an_exception_instance(): void
    {
        $exception = new FileNotFoundException('test-file');

        $message = $exception->getMessage();

        $this->assertEquals('File `test-file` is not found.', $message);
    }
}
