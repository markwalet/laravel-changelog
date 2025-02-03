<?php

namespace MarkWalet\Changelog\Tests\Adapters;

use MarkWalet\Changelog\Adapters\FakeFeatureAdapter;
use MarkWalet\Changelog\Adapters\FeatureAdapter;
use MarkWalet\Changelog\Change;
use MarkWalet\Changelog\Feature;
use MarkWalet\Changelog\Tests\LaravelTestCase;
use PHPUnit\Framework\Attributes\Test;

class FakeFeatureAdapterTest extends LaravelTestCase
{
    use FeatureAdapterTests;

    /**
     * Get an adapter instance.
     *
     * @return FeatureAdapter
     */
    public function adapter(): FeatureAdapter
    {
        $adapter = new FakeFeatureAdapter;
        $feature = new Feature([
            new Change('added', 'Added a new feature.'),
            new Change('added', 'Added an other feature.'),
            new Change('changed', 'Changed something that breaks the application.'),
            new Change('removed', 'Removed a deprecated function.'),
        ]);
        $adapter->write($this->readPath, $feature);

        return $adapter;
    }

    #[Test]
    public function it_can_write_a_changelog(): void
    {
        $adapter = $this->adapter();
        $feature = new Feature([
            new Change('added', 'Added a new feature.'),
            new Change('removed', 'Removed something else.'),
        ]);
        $path = __DIR__.'/../test-data/write-test/example.xml';

        $before = $adapter->exists($path);
        $adapter->write($path, $feature);
        $after = $adapter->exists($path);

        $this->assertFalse($before);
        $this->assertTrue($after);
    }
}
