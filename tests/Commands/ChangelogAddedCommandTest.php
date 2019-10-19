<?php

namespace MarkWalet\Changelog\Tests\Commands;

use MarkWalet\Changelog\Adapters\FakeFeatureAdapter;
use MarkWalet\Changelog\Adapters\FeatureAdapter;
use MarkWalet\Changelog\Tests\LaravelTestCase;
use MarkWalet\GitState\Drivers\FakeGitDriver;
use MarkWalet\GitState\Drivers\GitDriver;

/**
 * Class ChangelogAddedCommandTest.
 */
class ChangelogAddedCommandTest extends LaravelTestCase
{
    /**
     * Get and bind a fake adapter.
     *
     * @return FakeFeatureAdapter
     */
    private function fakeAdapter()
    {
        $adapter = new FakeFeatureAdapter;
        $this->app->singleton(FeatureAdapter::class, function () use ($adapter) {
            return $adapter;
        });

        return $adapter;
    }

    /**
     * Create a fake git driver with the given branch.
     *
     * @param string $branch
     * @return FakeGitDriver
     */
    private function fakeBranch(string $branch): FakeGitDriver
    {
        $driver = new FakeGitDriver(['branch' => $branch]);
        $this->app->bind(GitDriver::class, function () use ($driver) {
            return $driver;
        });

        return $driver;
    }

    /**
     * @dataProvider aliases
     * @test
     *
     * @param string $alias
     */
    public function the_alias_commands_work(string $alias)
    {
        $this->app['config']['changelog.path'] = 'test-path';
        $adapter = $this->fakeAdapter();
        $this->fakeBranch('test-branch');

        $this->artisan("changelog:{$alias}", ['message' => 'this is a message']);
        $feature = $adapter->read('test-path/unreleased/test-branch.xml');

        $this->assertCount(1, $feature->changes());
        $this->assertEquals($alias, $feature->changes()[0]->type());
        $this->assertEquals('this is a message', $feature->changes()[0]->message());
    }

    public function aliases()
    {
        return [['added'], ['changed'], ['deprecated'], ['removed'], ['fixed'], ['security']];
    }
}
