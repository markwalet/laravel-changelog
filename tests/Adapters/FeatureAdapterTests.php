<?php

namespace MarkWalet\Changelog\Tests\Adapters;

use MarkWalet\Changelog\Adapters\FeatureAdapter;
use MarkWalet\Changelog\Exceptions\FileNotFoundException;
use PHPUnit\Framework\Attributes\Test;

trait FeatureAdapterTests
{
    public $readPath = __DIR__.'/../test-data/example.xml';

    /**
     * Get an adapter instance.
     *
     * @return FeatureAdapter
     */
    abstract public function adapter(): FeatureAdapter;

    #[Test]
    public function it_can_see_if_a_changelog_exists(): void
    {
        $adapter = $this->adapter();

        $resultA = $adapter->exists($this->readPath);
        $resultB = $adapter->exists(__DIR__.'/../test-data/non-existing.xml');

        $this->assertTrue($resultA);
        $this->assertFalse($resultB);
    }

    #[Test]
    public function it_can_read_a_changelog(): void
    {
        $adapter = $this->adapter();

        $feature = $adapter->read($this->readPath);
        $changes = $feature->changes();

        $this->assertCount(4, $changes);
        $this->assertEquals('added', $changes[0]->type());
        $this->assertEquals('Added a new feature.', $changes[0]->message());
        $this->assertEquals('added', $changes[1]->type());
        $this->assertEquals('Added an other feature.', $changes[1]->message());
        $this->assertEquals('changed', $changes[2]->type());
        $this->assertEquals('Changed something that breaks the application.', $changes[2]->message());
        $this->assertEquals('removed', $changes[3]->type());
        $this->assertEquals('Removed a deprecated function.', $changes[3]->message());
    }

    #[Test]
    public function it_throws_an_exception_when_it_tries_to_read_a_non_existing_file(): void
    {
        $adapter = $this->adapter();
        $path = __DIR__.'/../test-data/not-existing.xml';

        $this->expectException(FileNotFoundException::class);

        $adapter->read($path);
    }
}
