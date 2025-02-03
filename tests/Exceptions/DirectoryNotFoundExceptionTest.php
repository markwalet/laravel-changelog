<?php

namespace MarkWalet\Changelog\Tests\Exceptions;

use MarkWalet\Changelog\Exceptions\DirectoryNotFoundException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class DirectoryNotFoundExceptionTest extends TestCase
{
    #[Test]
    public function it_can_create_an_exception_instance(): void
    {
        $exception = new DirectoryNotFoundException('test-path');

        $message = $exception->getMessage();

        $this->assertEquals('Directory `test-path` is not found.', $message);
    }
}
