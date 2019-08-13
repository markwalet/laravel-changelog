<?php

namespace MarkWalet\Changelog\Tests\Commands;

use MarkWalet\Changelog\Adapters\FakeReleaseAdapter;
use MarkWalet\Changelog\Adapters\ReleaseAdapter;
use MarkWalet\Changelog\Change;
use MarkWalet\Changelog\Feature;
use MarkWalet\Changelog\Release;
use MarkWalet\Changelog\Tests\LaravelTestCase;

class ChangelogUnreleasedCommandTest extends LaravelTestCase
{
    /**
     * Get and bind a fake adapter.
     *
     * @return FakeReleaseAdapter
     */
    private function fakeAdapter(): FakeReleaseAdapter
    {
        $this->app['config']['changelog.path'] = 'fake-folder';
        $adapter = new FakeReleaseAdapter;
        $this->app->singleton(ReleaseAdapter::class, function () use ($adapter) {
            return $adapter;
        });

        $adapter->addRelease('fake-folder', 'unreleased', tap(new Release('unreleased'), function (Release $release) {
            $release->add(new Feature([
                new Change('added', 'Added a new feature.'),
                new Change('removed', 'Removed unused trait.'),
            ]));
        }));
        $adapter->addRelease('fake-folder', 'v1.0.1', tap(new Release('v1.0.1'), function (Release $release) {
            $release->add(new Feature([
                new Change('added', 'Added helper commands.'),
                new Change('added', 'Added a third feature.'),
            ]));
        }));

        return $adapter;
    }

    /** @test */
    public function it_can_list_all_unreleased_changes()
    {
        $this->fakeAdapter();

        $this->artisan('changelog:unreleased')
             ->expectsOutput('Unreleased'
                 . PHP_EOL . '  - Added: Added a new feature.'
                 . PHP_EOL . '  - Removed: Removed unused trait.');
    }
}
