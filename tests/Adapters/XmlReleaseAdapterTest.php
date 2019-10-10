<?php

namespace MarkWalet\Changelog\Tests\Adapters;

use MarkWalet\Changelog\Adapters\XmlReleaseAdapter;
use PHPUnit\Framework\TestCase;

class XmlReleaseAdapterTest extends TestCase
{
    use ReleaseAdapterTests;

    /**
     * Get an adapter instance.
     *
     * @return XmlReleaseAdapter
     */
    public function adapter(): XmlReleaseAdapter
    {
        return new XmlReleaseAdapter;
    }

    /** @test */
    public function it_can_execute_a_release()
    {
        $adapter = $this->adapter();
        $path = __DIR__.'/../test-data/release-write-test';

        $unreleasedBefore = $adapter->exists($path, 'unreleased');
        $versionBefore = $adapter->exists($path, 'v1.0.2');
        $adapter->release($path, 'v1.0.2');
        $unreleasedAfter = $adapter->exists($path, 'unreleased');
        $versionAfter = $adapter->exists($path, 'v1.0.2');

        $this->assertTrue($unreleasedBefore);
        $this->assertFalse($versionBefore);
        $this->assertFalse($unreleasedAfter);
        $this->assertTrue($versionAfter);

        rename(__DIR__.'/../test-data/release-write-test/v1.0.2', __DIR__.'/../test-data/release-write-test/unreleased');
    }
}
