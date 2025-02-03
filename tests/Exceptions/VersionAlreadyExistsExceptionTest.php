<?php

namespace MarkWalet\Changelog\Tests\Exceptions;

use MarkWalet\Changelog\Exceptions\VersionAlreadyExistsException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class VersionAlreadyExistsExceptionTest extends TestCase
{
    #[Test]
    public function it_can_create_an_exception_instance(): void
    {
        $exception = new VersionAlreadyExistsException('test-version');

        $message = $exception->getMessage();

        $this->assertEquals('Version `test-version` already exists.', $message);
    }
}
