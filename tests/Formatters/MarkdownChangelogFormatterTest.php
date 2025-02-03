<?php

namespace MarkWalet\Changelog\Tests\Formatters;

use MarkWalet\Changelog\Change;
use MarkWalet\Changelog\Feature;
use MarkWalet\Changelog\Formatters\MarkdownChangelogFormatter;
use MarkWalet\Changelog\Release;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class MarkdownChangelogFormatterTest extends TestCase
{
    #[Test]
    public function it_can_format_a_single_release(): void
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
            .PHP_EOL.''
            .PHP_EOL.'### Added'
            .PHP_EOL.' - Added a feature.'
            .PHP_EOL.' - Added helper commands.'
            .PHP_EOL.''
            .PHP_EOL.'### Removed'
            .PHP_EOL.' - Removed unused trait.', $result);
    }

    #[Test]
    public function it_can_format_all_releases(): void
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
            .PHP_EOL.'## [Unreleased]'
            .PHP_EOL.''
            .PHP_EOL.'### Added'
            .PHP_EOL.' - Added helper commands.'
            .PHP_EOL.''
            .PHP_EOL.'### Removed'
            .PHP_EOL.' - Removed unused trait.'
            .PHP_EOL.''
            .PHP_EOL.'## [V1.0.1]'
            .PHP_EOL.''
            .PHP_EOL.'### Added'
            .PHP_EOL.' - Added a feature.'
            .PHP_EOL.''
            .PHP_EOL.'### Changed'
            .PHP_EOL.' - Renamed methods in the adapter interfaces.', $result);
    }

    #[Test]
    public function it_can_optionally_capitalize_the_version(): void
    {
        $release = new Release('v1.0.1');
        $defaultFormatter = new MarkdownChangelogFormatter;
        $capitalizedFormatter = new MarkdownChangelogFormatter(['capitalize' => true]);
        $lowercaseFormatter = new MarkdownChangelogFormatter(['capitalize' => false]);

        $resultA = $defaultFormatter->single($release);
        $resultB = $defaultFormatter->multiple([$release]);
        $resultC = $capitalizedFormatter->single($release);
        $resultD = $capitalizedFormatter->multiple([$release]);
        $resultE = $lowercaseFormatter->single($release);
        $resultF = $lowercaseFormatter->multiple([$release]);

        $this->assertStringContainsString('V1.0.1', $resultA);
        $this->assertStringContainsString('V1.0.1', $resultB);
        $this->assertStringContainsString('V1.0.1', $resultC);
        $this->assertStringContainsString('V1.0.1', $resultD);
        $this->assertStringContainsString('v1.0.1', $resultE);
        $this->assertStringContainsString('v1.0.1', $resultF);
    }
}
