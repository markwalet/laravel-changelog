<?php

namespace MarkWalet\Changelog\Tests;

use MarkWalet\Changelog\ChangelogFormatterFactory;
use MarkWalet\Changelog\Exceptions\InvalidArgumentException;
use MarkWalet\Changelog\Exceptions\MissingConfigurationException;
use MarkWalet\Changelog\Formatters\ChangelogFormatter;
use MarkWalet\Changelog\Release;
use PHPUnit\Framework\Attributes\Test;

class ChangelogFormatterFactoryTest extends LaravelTestCase
{
    #[Test]
    public function it_can_generate_a_factory(): void
    {
        $this->app['config']['changelog.formatters.test-driver'] = [
            'driver' => TestFormatter::class,
        ];
        /** @var ChangelogFormatterFactory $factory */
        $factory = $this->app->make(ChangelogFormatterFactory::class);

        $formatter = $factory->make('test-driver');

        $this->assertInstanceOf(TestFormatter::class, $formatter);
    }

    #[Test]
    public function it_passes_through_configuration_options_to_the_formatter(): void
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

    #[Test]
    public function it_throws_an_exception_if_the_format_configuration_does_not_exist(): void
    {
        /** @var ChangelogFormatterFactory $factory */
        $factory = $this->app->make(ChangelogFormatterFactory::class);

        $this->expectException(MissingConfigurationException::class);
        $factory->make('non-existing');
    }

    #[Test]
    public function it_throws_an_exception_when_the_driver_is_not_set_in_a_configuration(): void
    {
        $this->app['config']['changelog.formatters.missing-driver'] = [
            'other-option' => 'value',
        ];
        /** @var ChangelogFormatterFactory $factory */
        $factory = $this->app->make(ChangelogFormatterFactory::class);

        $this->expectException(MissingConfigurationException::class);
        $factory->make('missing-driver');
    }

    #[Test]
    public function it_throws_an_exception_when_the_driver_does_not_extends_the_base_formatter_class(): void
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
