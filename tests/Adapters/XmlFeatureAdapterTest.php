<?php

namespace MarkWalet\Changelog\Tests\Adapters;

use MarkWalet\Changelog\Adapters\FeatureAdapter;
use MarkWalet\Changelog\Adapters\XmlFeatureAdapter;
use MarkWalet\Changelog\Change;
use MarkWalet\Changelog\Exceptions\InvalidXmlException;
use MarkWalet\Changelog\Feature;
use PHPUnit\Framework\TestCase;

class XmlFeatureAdapterTest extends TestCase
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

    /** @test */
    public function it_can_write_a_changelog()
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

    /** @test */
    public function it_throws_an_exception_when_the_file_is_invalid()
    {
        $adapter = $this->adapter();
        $path = __DIR__.'/../test-data/invalid.xml';

        $this->expectException(InvalidXmlException::class);

        $adapter->read($path);
    }
}
