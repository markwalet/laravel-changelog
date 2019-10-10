<?php

namespace MarkWalet\Changelog\Tests\Adapters;

use MarkWalet\Changelog\Adapters\ReleaseAdapter;
use MarkWalet\Changelog\Exceptions\DirectoryNotFoundException;
use MarkWalet\Changelog\Exceptions\FileNotFoundException;
use MarkWalet\Changelog\Exceptions\VersionAlreadyExistsException;

trait ReleaseAdapterTests
{
    public $readPath = __DIR__.'/../test-data';
    public $readVersion = 'multiple';
    public $readAll = __DIR__.'/../test-data/releases';

    /**
     * Get an adapter instance.
     *
     * @return ReleaseAdapter
     */
    public abstract function adapter(): ReleaseAdapter;

    /** @test */
    public function it_can_see_if_a_release_exists()
    {
        $adapter = $this->adapter();

        $resultA = $adapter->exists($this->readPath, $this->readVersion);
        $resultB = $adapter->exists($this->readPath, 'other-version');

        $this->assertTrue($resultA);
        $this->assertFalse($resultB);
    }

    /** @test */
    public function it_can_read_a_release()
    {
        $adapter = $this->adapter();

        $release = $adapter->read($this->readPath, $this->readVersion);

        $changes = $release->changes();

        $this->assertCount(4, $changes);
        $this->assertEquals('added', $changes[0]->type());
        $this->assertEquals('Added a feature.', $changes[0]->message());
        $this->assertEquals('added', $changes[1]->type());
        $this->assertEquals('Added helper commands.', $changes[1]->message());
        $this->assertEquals('changed', $changes[2]->type());
        $this->assertEquals('Renamed methods in the adapter interfaces.', $changes[2]->message());
        $this->assertEquals('removed', $changes[3]->type());
        $this->assertEquals('Removed unused trait.', $changes[3]->message());
    }

    /** @test */
    public function it_can_list_all_releases()
    {
        $adapter = $this->adapter();

        $releases = $adapter->all($this->readAll);

        $this->assertCount(3, $releases);
        $this->assertEquals('unreleased', $releases[0]->version());
        $this->assertCount(0, $releases[0]->changes());
        $this->assertEquals('v1.0.2', $releases[1]->version());
        $this->assertCount(2, $releases[1]->changes());
        $this->assertEquals('v1.0.1', $releases[2]->version());
        $this->assertCount(2, $releases[2]->changes());
    }

    /** @test */
    public function it_throws_an_exception_when_the_release_is_not_found()
    {
        $this->expectException(FileNotFoundException::class);
        $adapter = $this->adapter();

        $adapter->read('non-existing', 'version');
    }

    /** @test */
    public function it_throws_an_exception_when_the_working_directory_is_not_found()
    {
        $this->expectException(DirectoryNotFoundException::class);
        $adapter = $this->adapter();

        $adapter->all('non-existing');
    }

    /** @test */
    public function it_throws_an_exception_when_the_version_already_exists()
    {
        $this->expectException(VersionAlreadyExistsException::class);
        $adapter = $this->adapter();

        $adapter->release($this->readAll, 'v1.0.1');
        $this->assertTrue($adapter->exists($this->readAll, 'unreleased'));
    }

}
