<?php

namespace MarkWalet\Changelog\Tests;

use MarkWalet\Changelog\Change;
use MarkWalet\Changelog\Feature;
use PHPUnit\Framework\TestCase;

class FeatureTest extends TestCase
{
    /** @test */
    public function it_can_set_properties_trough_the_constructor()
    {
        $feature = new Feature([
            new Change('added', 'Added a new feature.'),
            new Change('changed', 'Changed an existing feature.'),
        ]);

        $changes = $feature->changes();

        $this->assertCount(2, $changes);
        $this->assertEquals('added', $changes[0]->type());
        $this->assertEquals('Added a new feature.', $changes[0]->message());
        $this->assertEquals('changed', $changes[1]->type());
        $this->assertEquals('Changed an existing feature.', $changes[1]->message());
    }

    /** @test */
    public function the_changes_list_defaults_to_an_empty_array()
    {
        $feature = new Feature;

        $changes = $feature->changes();

        $this->assertCount(0, $changes);
    }

    /** @test */
    public function it_can_add_a_change()
    {
        $feature = new Feature([
            new Change('added', 'Added a new feature.'),
            new Change('changed', 'Changed an existing feature.'),
        ]);

        $feature->add(new Change('removed', 'Removed something.'));
        $changes = $feature->changes();

        $this->assertCount(3, $changes);
        $this->assertEquals('removed', $changes[2]->type());
        $this->assertEquals('Removed something.', $changes[2]->message());
    }

    /** @test */
    public function it_can_remove_a_change()
    {
        $feature = new Feature([
            new Change('added', 'Added a new feature.'),
            new Change('changed', 'Changed an existing feature.'),
        ]);

        $feature->remove(1);
        $changes = $feature->changes();

        $this->assertCount(1, $changes);
        $this->assertEquals('added', $changes[0]->type());
        $this->assertEquals('Added a new feature.', $changes[0]->message());
    }
}
