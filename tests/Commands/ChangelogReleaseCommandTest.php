<?php

namespace MarkWalet\Changelog\Tests\Commands;

use MarkWalet\Changelog\Adapters\FakeReleaseAdapter;
use MarkWalet\Changelog\Adapters\ReleaseAdapter;
use MarkWalet\Changelog\Change;
use MarkWalet\Changelog\Feature;
use MarkWalet\Changelog\Exceptions\VersionAlreadyExistsException;
use MarkWalet\Changelog\Release;
use MarkWalet\Changelog\Tests\LaravelTestCase;

class ChangelogReleaseCommandTest extends LaravelTestCase
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
            ]));
        }));
        $adapter->addRelease('fake-folder', 'v1.0.1', tap(new Release('v1.0.1'), function (Release $release) {
            $release->add(new Feature([
                new Change('added', 'Added helper commands.'),
                new Change('removed', 'Removed unused trait.'),
                new Change('added', 'Added a third feature.'),
            ]));
        }));

        return $adapter;
    }

    /** @test */
    public function it_can_release_a_new_version()
    {
        $adapter = $this->fakeAdapter();
        $this->artisan('changelog:release', [
            'version' => 'v1.0.2'
        ]);

        $releases = $adapter->all('fake-folder');
        $versions = collect($releases)->map(function (Release $release) {
            return $release->version();
        });

        $this->assertContains('v1.0.2', $versions);
        $this->assertContains('v1.0.1', $versions);
        $this->assertContains('unreleased', $versions);
    }
}
