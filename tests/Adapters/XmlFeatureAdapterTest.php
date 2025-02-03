<?php

namespace MarkWalet\Changelog\Tests\Adapters;

use MarkWalet\Changelog\Adapters\FeatureAdapter;
use MarkWalet\Changelog\Adapters\XmlFeatureAdapter;
use MarkWalet\Changelog\Change;
use MarkWalet\Changelog\Exceptions\InvalidXmlException;
use MarkWalet\Changelog\Feature;
use MarkWalet\Changelog\Tests\LaravelTestCase;
use PHPUnit\Framework\Attributes\Test;

class XmlFeatureAdapterTest extends LaravelTestCase
{
    use FeatureAdapterTests;

    /**
     * Get an adapter instance.
     *
     * @return FeatureAdapter
     */
    public function adapter(): FeatureAdapter
    {
        return new XmlFeatureAdapter;
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

        $adapter->write($path, $feature);

        $contents = file_get_contents($path);

        $this->assertStringContainsString('<change type="added">Added a new feature.</change>', $contents);
        $this->assertStringContainsString('<change type="removed">Removed something else.</change>', $contents);

        unlink($path);
    }

    #[Test]
    public function it_creates_the_directory_recursively_if_it_does_not_exist(): void
    {
        $adapter = $this->adapter();
        $feature = new Feature([
            new Change('added', 'Added a new feature.'),
        ]);
        $folderA = __DIR__.'/../test-data/write-test/nested';
        $folderB = $folderA.'/second-child';
        $path = $folderB.'/example.xml';

        $adapter->write($path, $feature);

        $contents = file_get_contents($path);

        $this->assertStringContainsString('<change type="added">Added a new feature.</change>', $contents);

        unlink($path);
        rmdir($folderB);
        rmdir($folderA);
    }

    #[Test]
    public function it_throws_an_exception_when_the_file_is_invalid(): void
    {
        $adapter = $this->adapter();
        $path = __DIR__.'/../test-data/invalid.xml';

        $this->expectException(InvalidXmlException::class);

        $adapter->read($path);
    }
}
