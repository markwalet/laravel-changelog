<?php

namespace MarkWalet\Changelog\Tests;

use MarkWalet\Changelog\Adapters\FeatureAdapter;
use MarkWalet\Changelog\Adapters\ReleaseAdapter;
use MarkWalet\Changelog\Adapters\XmlFeatureAdapter;
use MarkWalet\Changelog\Adapters\XmlReleaseAdapter;
use MarkWalet\Changelog\ChangelogFormatterFactory;
use PHPUnit\Framework\Attributes\Test;

class ChangelogServiceProviderTest extends LaravelTestCase
{
    #[Test]
    public function it_registers_a_default_configuration(): void
    {
        $config = $this->app['config']->get('changelog');

        $this->assertIsArray($config);
    }

    #[Test]
    public function it_registers_a_default_changelog_adapter(): void
    {
        $bindings = $this->app->getBindings();
        $this->assertArrayHasKey(FeatureAdapter::class, $bindings);

        $adapter = $this->app->make(FeatureAdapter::class);
        $this->assertInstanceOf(XmlFeatureAdapter::class, $adapter);
    }

    #[Test]
    public function it_registers_a_default_release_adapter(): void
    {
        $bindings = $this->app->getBindings();
        $this->assertArrayHasKey(ReleaseAdapter::class, $bindings);

        $adapter = $this->app->make(ReleaseAdapter::class);
        $this->assertInstanceOf(XmlReleaseAdapter::class, $adapter);
    }

    #[Test]
    public function it_registers_a_formatter_factory(): void
    {
        $bindings = $this->app->getBindings();
        $this->assertArrayHasKey(ChangelogFormatterFactory::class, $bindings);

        $adapter = $this->app->make(ChangelogFormatterFactory::class);
        $this->assertInstanceOf(ChangelogFormatterFactory::class, $adapter);
    }
}
