<?php

namespace MarkWalet\Changelog\Tests;

use MarkWalet\Changelog\Adapters\FeatureAdapter;
use MarkWalet\Changelog\Adapters\ReleaseAdapter;
use MarkWalet\Changelog\Adapters\XmlFeatureAdapter;
use MarkWalet\Changelog\Adapters\XmlReleaseAdapter;
use MarkWalet\Changelog\ChangelogFormatterFactory;

class ChangelogServiceProviderTest extends LaravelTestCase
{
    /** @test */
    public function it_registers_a_default_configuration()
    {
        $config = $this->app['config']->get('changelog');

        $this->assertIsArray($config);
    }

    /** @test */
    public function it_registers_a_default_changelog_adapter()
    {
        $bindings = $this->app->getBindings();
        $this->assertArrayHasKey(FeatureAdapter::class, $bindings);

        $adapter = $this->app->make(FeatureAdapter::class);
        $this->assertInstanceOf(XmlFeatureAdapter::class, $adapter);
    }

    /** @test */
    public function it_registers_a_default_release_adapter()
    {
        $bindings = $this->app->getBindings();
        $this->assertArrayHasKey(ReleaseAdapter::class, $bindings);

        $adapter = $this->app->make(ReleaseAdapter::class);
        $this->assertInstanceOf(XmlReleaseAdapter::class, $adapter);
    }

    /** @test */
    public function it_registers_a_formatter_factory()
    {
        $bindings = $this->app->getBindings();
        $this->assertArrayHasKey(ChangelogFormatterFactory::class, $bindings);

        $adapter = $this->app->make(ChangelogFormatterFactory::class);
        $this->assertInstanceOf(ChangelogFormatterFactory::class, $adapter);
    }
}
