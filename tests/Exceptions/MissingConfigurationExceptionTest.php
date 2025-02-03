<?php

namespace MarkWalet\Changelog\Tests\Exceptions;

use MarkWalet\Changelog\Exceptions\MissingConfigurationException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class MissingConfigurationExceptionTest extends TestCase
{
    #[Test]
    public function it_can_create_an_exception_instance(): void
    {
        $exception = new MissingConfigurationException('config-key');

        $message = $exception->getMessage();

        $this->assertEquals('Configuration key `config-key` is missing.', $message);
    }
}
