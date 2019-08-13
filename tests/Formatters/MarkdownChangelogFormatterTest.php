<?php

namespace MarkWalet\Changelog\Tests;

use MarkWalet\Changelog\Change;
use MarkWalet\Changelog\Feature;
use MarkWalet\Changelog\Formatters\MarkdownChangelogFormatter;
use MarkWalet\Changelog\Formatters\TextChangelogFormatter;
use MarkWalet\Changelog\Release;
use PHPUnit\Framework\TestCase;

class MarkdownChangelogFormatterTest extends TestCase
{
    /** @test */
    public function it_can_format_a_single_release()
    {
        $release = new Release('unreleased');
        $release->add(new Feature([
            new Change('added', 'Added helper commands.'),
            new Change('removed', 'Removed unused trait.'),
        ]));
        $release->add(new Feature([
            new Change('added', 'Added a feature.'),
        ]));
        $formatter = new MarkdownChangelogFormatter;

        $result = $formatter->single($release);

        $this->assertEquals('## [Unreleased]'
            . PHP_EOL . ''
            . PHP_EOL . '### Added'
            . PHP_EOL . ' - Added a feature.'
            . PHP_EOL . ' - Added helper commands.'
            . PHP_EOL . ''
            . PHP_EOL . '### Removed'
            . PHP_EOL . ' - Removed unused trait.', $result);
    }

    /** @test */
    public function it_can_format_all_releases()
    {
        $releaseA = new Release('unreleased');
        $releaseB = new Release('v1.0.1');
        $releaseA->add(new Feature([
            new Change('added', 'Added helper commands.'),
            new Change('removed', 'Removed unused trait.'),
        ]));
        $releaseB->add(new Feature([
            new Change('changed', 'Renamed methods in the adapter interfaces.'),
            new Change('added', 'Added a feature.'),
        ]));
        $formatter = new MarkdownChangelogFormatter;

        $result = $formatter->multiple([$releaseA, $releaseB]);

        $this->assertEquals(''
            . PHP_EOL . '## [Unreleased]'
            . PHP_EOL . ''
            . PHP_EOL . '### Added'
            . PHP_EOL . ' - Added helper commands.'
            . PHP_EOL . ''
            . PHP_EOL . '### Removed'
            . PHP_EOL . ' - Removed unused trait.'
            . PHP_EOL . ''
            . PHP_EOL . '## [V1.0.1]'
            . PHP_EOL . ''
            . PHP_EOL . '### Added'
            . PHP_EOL . ' - Added a feature.'
            . PHP_EOL . ''
            . PHP_EOL . '### Changed'
            . PHP_EOL . ' - Renamed methods in the adapter interfaces.', $result);
    }
}
