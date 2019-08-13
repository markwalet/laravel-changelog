<?php

namespace MarkWalet\Changelog\Tests;

use MarkWalet\Changelog\Change;
use MarkWalet\Changelog\Feature;
use MarkWalet\Changelog\Release;
use PHPUnit\Framework\TestCase;

class ReleaseTest extends TestCase
{
    /** @test */
    public function it_can_set_properties_trough_the_constructor()
    {
        $change = new Release('v1.0.2');

        $version = $change->version();

        $this->assertEquals('v1.0.2', $version);
    }

    /** @test */
    public function it_appends_all_changes_of_a_changelog_to_the_release()
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

    /** @test */
    public function it_sorts_all_changes_on_type()
    {
        $release = new Release('v1');
        $release->add(new Feature([
            new Change('AAA', '-'),
            new Change('DDD', '-'),
        ]));
        $release->add(new Feature([
            new Change('CCC', '-'),
            new Change('BBB', '-'),
        ]));

        $changes = $release->changes();

        $this->assertEquals('AAA', $changes[0]->type());
        $this->assertEquals('BBB', $changes[1]->type());
        $this->assertEquals('CCC', $changes[2]->type());
        $this->assertEquals('DDD', $changes[3]->type());
    }

    /** @test */
    public function it_sorts_all_changes_on_message()
    {
        $release = new Release('v1');
        $release->add(new Feature([
            new Change('-', 'AAA'),
            new Change('-', 'DDD'),
        ]));
        $release->add(new Feature([
            new Change('-', 'CCC'),
            new Change('-', 'BBB'),
        ]));

        $changes = $release->changes();

        $this->assertEquals('AAA', $changes[0]->message());
        $this->assertEquals('BBB', $changes[1]->message());
        $this->assertEquals('CCC', $changes[2]->message());
        $this->assertEquals('DDD', $changes[3]->message());
    }

    /** @test */
    public function it_prioritizes_sorting_on_type()
    {
        $release = new Release('v1');
        $release->add(new Feature([
            new Change('AAA', 'AAA'),
            new Change('BBB', 'DDD'),
        ]));
        $release->add(new Feature([
            new Change('BBB', 'CCC'),
            new Change('AAA', 'BBB'),
        ]));

        $changes = $release->changes();

        $this->assertEquals('AAA', $changes[0]->type());
        $this->assertEquals('AAA', $changes[0]->message());
        $this->assertEquals('AAA', $changes[1]->type());
        $this->assertEquals('BBB', $changes[1]->message());
        $this->assertEquals('BBB', $changes[2]->type());
        $this->assertEquals('CCC', $changes[2]->message());
        $this->assertEquals('BBB', $changes[3]->type());
        $this->assertEquals('DDD', $changes[3]->message());
    }
}
