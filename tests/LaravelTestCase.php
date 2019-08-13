<?php

namespace MarkWalet\Changelog\Tests;

use Illuminate\Foundation\Application;
use MarkWalet\Changelog\Adapters\FeatureAdapter;
use MarkWalet\Changelog\Adapters\FakeFeatureAdapter;
use MarkWalet\Changelog\ChangelogServiceProvider;
use MarkWalet\GitState\GitStateServiceProvider;
use Orchestra\Testbench\TestCase;

class LaravelTestCase extends TestCase
{
    /**
     * Get package providers.
     *
     * @param Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            ChangelogServiceProvider::class,
        ];
    }

    /**
     * Resolve application core configuration implementation.
     *
     * @param Application $app
     *
     * @return void
     */
    protected function resolveApplicationConfiguration($app)
    {
        parent::resolveApplicationConfiguration($app);

        $app['config']['git-state.default'] = 'test';
        $app['config']['git-state.drivers.test'] = ['driver' => 'fake'];
    }
}
