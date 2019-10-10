<?php

namespace MarkWalet\Changelog\Tests\Commands;

use MarkWalet\Changelog\Adapters\FakeReleaseAdapter;
use MarkWalet\Changelog\Adapters\ReleaseAdapter;
use MarkWalet\Changelog\Change;
use MarkWalet\Changelog\Feature;
use MarkWalet\Changelog\Release;
use MarkWalet\Changelog\Tests\LaravelTestCase;

class ChangelogGenerateCommandTest extends LaravelTestCase
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
    public function it_can_generate_a_changelog_file()
    {
        $path = __DIR__.'/../test-data/CHANGELOG-CUSTOM.md';
        $this->fakeAdapter();

        $this->assertFalse(file_exists($path));
        $this->artisan('changelog:generate', [
            '--path' => $path,
        ]);
        $this->assertTrue(file_exists($path));

        unlink($path);
    }

    /** @test */
    public function it_gets_the_default_path_from_the_configuration()
    {
        $this->fakeAdapter();
        $path = __DIR__.'/../test-data/CHANGELOG-CUSTOM.md';

        $this->app['config']['changelog.changelog_path'] = $path;

        $this->assertFalse(file_exists($path));
        $this->artisan('changelog:generate');
        $this->assertTrue(file_exists($path));

        unlink($path);
    }

    /** @test */
    public function it_does_not_create_a_file_on_a_dry_run()
    {
        $this->fakeAdapter();
        $path = __DIR__.'/../test-data/CHANGELOG.md';
        $this->artisan('changelog:generate', [
            '--dry-run' => true,
            '--path' => $path,
        ]);

        $this->assertFalse(file_exists($path));
    }
}
