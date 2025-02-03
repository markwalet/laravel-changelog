<?php

namespace MarkWalet\Changelog\Tests\Formatters;

use MarkWalet\Changelog\Change;
use MarkWalet\Changelog\Feature;
use MarkWalet\Changelog\Formatters\SlackChangelogFormatter;
use MarkWalet\Changelog\Release;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class SlackChangelogFormatterTest extends TestCase
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
        $formatter = new SlackChangelogFormatter;

        $result = $formatter->single($release);

        $this->assertEquals('_Unreleased_'
            .'\\n* - Added:* Added a feature.'
            .'\\n* - Added:* Added helper commands.'
            .'\\n* - Removed:* Removed unused trait.', $result);
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
        $formatter = new SlackChangelogFormatter;

        $result = $formatter->multiple([$releaseA, $releaseB]);

        $this->assertEquals('_Unreleased_'
            .'\\n* - Added:* Added helper commands.'
            .'\\n* - Removed:* Removed unused trait.'
            .'\\n_V1.0.1_'
            .'\\n* - Added:* Added a feature.'
            .'\\n* - Changed:* Renamed methods in the adapter interfaces.', $result);
    }

    #[Test]
    public function it_can_optionally_capitalize_the_version(): void
    {
        $release = new Release('v1.0.1');
        $defaultFormatter = new SlackChangelogFormatter;
        $capitalizedFormatter = new SlackChangelogFormatter(['capitalize' => true]);
        $lowercaseFormatter = new SlackChangelogFormatter(['capitalize' => false]);

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
