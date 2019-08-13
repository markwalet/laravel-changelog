<?php

namespace MarkWalet\Changelog\Tests\Adapters;

use MarkWalet\Changelog\Adapters\FakeReleaseAdapter;
use MarkWalet\Changelog\Adapters\ReleaseAdapter;
use MarkWalet\Changelog\Change;
use MarkWalet\Changelog\Feature;
use MarkWalet\Changelog\Release;
use PHPUnit\Framework\TestCase;

class FakeReleaseAdapterTest extends TestCase
{
    use ReleaseAdapterTests;

    /**
     * Get an adapter instance.
     *
     * @return ReleaseAdapter
     */
    public function adapter(): ReleaseAdapter
    {
        $adapter = new FakeReleaseAdapter;

        $adapter->addRelease($this->readPath, $this->readVersion, tap(new Release($this->readVersion), function(Release $release) {
            $release->add(new Feature([
                new Change('added', 'Added helper commands.'),
                new Change('removed', 'Removed unused trait.'),
            ]));
            $release->add(new Feature([
                new Change('changed', 'Renamed methods in the adapter interfaces.'),
                new Change('added', 'Added a feature.'),
            ]));
        }));
        $adapter->addRelease($this->readAll, 'unreleased', tap(new Release('unreleased'), function(Release $release) {
            $release->add(new Feature([]));
        }));
        $adapter->addRelease($this->readAll, 'v1.0.1', tap(new Release('v1.0.1'), function(Release $release) {
            $release->add(new Feature([
                new Change('added', 'Added helper commands.'),
                new Change('removed', 'Removed unused trait.'),
            ]));
        }));
        $adapter->addRelease($this->readAll, 'v1.0.2', tap(new Release('v1.0.2'), function(Release $release) {
            $release->add(new Feature([
                new Change('changed', 'Renamed methods in the adapter interfaces.'),
                new Change('added', 'Added a feature.'),
            ]));
        }));

        return $adapter;
    }

    public function it_can_execute_a_release()
    {
        $adapter = $this->adapter();
        $path = __DIR__ . '/../test-data/release-write-test';

        $unreleasedBefore = $adapter->exists($path, 'unreleased');
        $versionBefore = $adapter->exists($path, 'v1.0.2');
        $adapter->release($path, 'v1.0.2');
        $unreleasedAfter = $adapter->exists($path, 'unreleased');
        $versionAfter = $adapter->exists($path, 'v1.0.2');

        $this->assertTrue($unreleasedBefore);
        $this->assertFalse($versionBefore);
        $this->assertTrue($unreleasedAfter);
        $this->assertTrue($versionAfter);

        rmdir(__DIR__.'/../test-data/release-write-test/unreleased');
        rename(__DIR__ . '/../test-data/release-write-test/unreleased', __DIR__.'/../test-data/release-write-test/unreleased');
    }
}
