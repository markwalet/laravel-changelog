<?php

namespace MarkWalet\Changelog\Tests;

use MarkWalet\Changelog\Change;
use MarkWalet\Changelog\Feature;
use MarkWalet\Changelog\Release;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ReleaseTest extends TestCase
{
    #[Test]
    public function it_can_set_properties_trough_the_constructor(): void
    {
        $change = new Release('v1.0.2');

        $version = $change->version();

        $this->assertEquals('v1.0.2', $version);
    }

    #[Test]
    public function it_appends_all_changes_of_a_changelog_to_the_release(): void
    {
        $release = new Release('v1');
        $release->add(new Feature([
            new Change('added', 'Added a new feature'),
            new Change('added', 'Added a second feature'),
        ]));
        $release->add(new Feature([
            new Change('added', 'Added a new feature'),
            new Change('added', 'Added a second feature'),
        ]));

        $changes = $release->changes();

        $this->assertCount(4, $changes);
    }

    #[Test]
    public function it_sorts_all_changes_on_type(): void
    {
        $release = new Release('v1');
        $release->add(new Feature([
            new Change('aaa', '-'),
            new Change('ddd', '-'),
        ]));
        $release->add(new Feature([
            new Change('ccc', '-'),
            new Change('bbb', '-'),
        ]));

        $changes = $release->changes();

        $this->assertEquals('aaa', $changes[0]->type());
        $this->assertEquals('bbb', $changes[1]->type());
        $this->assertEquals('ccc', $changes[2]->type());
        $this->assertEquals('ddd', $changes[3]->type());
    }

    #[Test]
    public function it_sorts_all_changes_on_message(): void
    {
        $release = new Release('v1');
        $release->add(new Feature([
            new Change('-', 'aaa'),
            new Change('-', 'ddd'),
        ]));
        $release->add(new Feature([
            new Change('-', 'ccc'),
            new Change('-', 'bbb'),
        ]));

        $changes = $release->changes();

        $this->assertEquals('aaa', $changes[0]->message());
        $this->assertEquals('bbb', $changes[1]->message());
        $this->assertEquals('ccc', $changes[2]->message());
        $this->assertEquals('ddd', $changes[3]->message());
    }

    #[Test]
    public function it_prioritizes_sorting_on_type(): void
    {
        $release = new Release('v1');
        $release->add(new Feature([
            new Change('aaa', 'aaa'),
            new Change('bbb', 'ddd'),
        ]));
        $release->add(new Feature([
            new Change('bbb', 'ccc'),
            new Change('aaa', 'bbb'),
        ]));

        $changes = $release->changes();

        $this->assertEquals('aaa', $changes[0]->type());
        $this->assertEquals('aaa', $changes[0]->message());
        $this->assertEquals('aaa', $changes[1]->type());
        $this->assertEquals('bbb', $changes[1]->message());
        $this->assertEquals('bbb', $changes[2]->type());
        $this->assertEquals('ccc', $changes[2]->message());
        $this->assertEquals('bbb', $changes[3]->type());
        $this->assertEquals('ddd', $changes[3]->message());
    }
}
