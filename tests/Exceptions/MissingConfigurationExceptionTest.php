<?php

namespace MarkWalet\Changelog\Tests\Exceptions;

use MarkWalet\Changelog\Exceptions\MissingConfigurationException;
use PHPUnit\Framework\TestCase;

class MissingConfigurationExceptionTest extends TestCase
{
    /** @test */
    public function it_can_create_an_exception_instance(): void
    {
        $exception = new MissingConfigurationException('config-key');

        $message = $exception->getMessage();

        $this->assertEquals('Configuration key `config-key` is missing.', $message);
    }
}
