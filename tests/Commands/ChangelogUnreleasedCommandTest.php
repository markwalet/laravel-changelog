<?php

namespace MarkWalet\Changelog\Tests\Commands;

use MarkWalet\Changelog\Adapters\FakeReleaseAdapter;
use MarkWalet\Changelog\Adapters\ReleaseAdapter;
use MarkWalet\Changelog\Change;
use MarkWalet\Changelog\Exceptions\InvalidXmlException;
use MarkWalet\Changelog\Feature;
use MarkWalet\Changelog\Release;
use MarkWalet\Changelog\Tests\LaravelTestCase;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Test;

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

    #[Test]
    public function it_can_list_all_unreleased_changes(): void
    {
        $this->fakeAdapter();

        $this->artisan('changelog:unreleased')
            ->expectsOutput('Unreleased')
            ->expectsOutput('  - Added: Added a new feature.')
            ->expectsOutput('  - Removed: Removed unused trait.')
            ->assertExitCode(0);
    }

    #[Test]
    public function it_shows_a_warning_when_the_unreleased_changelog_contains_invalid_xml(): void
    {
        config()->set('changelog.path', 'fake-folder');
        $this->mock(ReleaseAdapter::class, function (MockInterface $adapter) {
            $adapter->allows('exists')->andReturn(true);
            $adapter->allows('read')->andThrow(new InvalidXmlException('Invalid xml in "fake-folder/unreleased/example.xml".'));
        });

        $this->artisan('changelog:unreleased')
            ->expectsOutput('Invalid xml in "fake-folder/unreleased/example.xml".')
            ->assertExitCode(1);
    }
}
