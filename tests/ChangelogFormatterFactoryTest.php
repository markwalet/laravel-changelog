<?php

namespace MarkWalet\Changelog\Tests;

use MarkWalet\Changelog\ChangelogFormatterFactory;
use MarkWalet\Changelog\Exceptions\InvalidArgumentException;
use MarkWalet\Changelog\Exceptions\MissingConfigurationException;
use MarkWalet\Changelog\Formatters\ChangelogFormatter;
use MarkWalet\Changelog\Release;

class ChangelogFormatterFactoryTest extends LaravelTestCase
{
    /** @test */
    public function it_can_generate_a_factory()
    {
        $this->app['config']['changelog.formatters.test-driver'] = [
            'driver' => TestFormatter::class,
        ];
        /** @var ChangelogFormatterFactory $factory */
        $factory = $this->app->make(ChangelogFormatterFactory::class);

        $formatter = $factory->make('test-driver');

        $this->assertInstanceOf(TestFormatter::class, $formatter);
    }

    /** @test */
    public function it_passes_through_configuration_options_to_the_formatter()
    {
        $this->app['config']['changelog.formatters.test-driver'] = [
            'driver' => TestFormatter::class,
            'other-option' => 'value',
        ];
        /** @var ChangelogFormatterFactory $factory */
        $factory = $this->app->make(ChangelogFormatterFactory::class);

        /** @var TestFormatter $formatter */
        $formatter = $factory->make('test-driver');
        $config = $formatter->getConfiguration();

        $this->assertEquals([
            'other-option' => 'value',
        ], $config);
    }

    /** @test */
    public function it_throws_an_exception_if_the_format_configuration_does_not_exist()
    {
        /** @var ChangelogFormatterFactory $factory */
        $factory = $this->app->make(ChangelogFormatterFactory::class);

        $this->expectException(MissingConfigurationException::class);
        $factory->make('non-existing');
    }

    /** @test */
    public function it_throws_an_exception_when_the_driver_is_not_set_in_a_configuration()
    {
        $this->app['config']['changelog.formatters.missing-driver'] = [
            'other-option' => 'value',
        ];
        /** @var ChangelogFormatterFactory $factory */
        $factory = $this->app->make(ChangelogFormatterFactory::class);

        $this->expectException(MissingConfigurationException::class);
        $factory->make('missing-driver');
    }

    /** @test */
    public function it_throws_an_exception_when_the_driver_does_not_extends_the_base_formatter_class()
    {
        $this->app['config']['changelog.formatters.invalid-driver'] = [
            'driver' => InvalidFormatter::class,
        ];
        /** @var ChangelogFormatterFactory $factory */
        $factory = $this->app->make(ChangelogFormatterFactory::class);

        $this->expectException(InvalidArgumentException::class);
        $factory->make('invalid-driver');
    }
}

final class TestFormatter extends ChangelogFormatter
{
    /**
     * Format a single release to a string format.
     *
     * @param Release $release
     * @return string
     */
    public function single(Release $release): string
    {
        return 'single';
    }

    /**
     * Format a list of releases to a string format.
     *
     * @param Release[]|array $releases
     * @return string
     */
    public function multiple(array $releases): string
    {
        return 'multiple';
    }

    /**
     * Get the current configuration.
     *
     * @return array
     */
    public function getConfiguration()
    {
        return $this->config;
    }
}

final class InvalidFormatter
{
}
