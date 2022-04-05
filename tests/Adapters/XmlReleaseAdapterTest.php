<?php

namespace MarkWalet\Changelog\Tests\Adapters;

use MarkWalet\Changelog\Adapters\XmlReleaseAdapter;
use MarkWalet\Changelog\Tests\LaravelTestCase;

class XmlReleaseAdapterTest extends LaravelTestCase
{
    use ReleaseAdapterTests;

    /**
     * Get an adapter instance.
     *
     * @return XmlReleaseAdapter
     */
    public function adapter(): XmlReleaseAdapter
    {
        return new XmlReleaseAdapter();
    }

    /** @test */
    public function it_can_execute_a_release()
    {
        $adapter = $this->adapter();
        $path = __DIR__.'/../test-data/release-write-test';

        $this->assertFileExists($path.'/unreleased/.gitkeep');
        $this->assertFileExists($path.'/unreleased/nested/feature-1.xml');
        $this->assertFileExists($path.'/unreleased/feature-2.xml');
        $this->assertFileDoesNotExist($path.'/v1.0.2');

        $adapter->release($path, 'v1.0.2');

        $this->assertFileExists($path.'/unreleased/.gitkeep');
        $this->assertFileExists($path.'/v1.0.2/nested/feature-1.xml');
        $this->assertFileExists($path.'/v1.0.2/feature-2.xml');
        $this->assertFileDoesNotExist($path.'/unreleased/nested');

        rename(__DIR__ . '/../test-data/release-write-test/v1.0.2/nested', __DIR__.'/../test-data/release-write-test/unreleased/nested');
        rename(__DIR__ . '/../test-data/release-write-test/v1.0.2/feature-2.xml', __DIR__.'/../test-data/release-write-test/unreleased/feature-2.xml');
        rmdir(__DIR__.'/../test-data/release-write-test/v1.0.2');
    }
}
