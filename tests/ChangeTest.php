<?php

namespace MarkWalet\Changelog\Tests;

use MarkWalet\Changelog\Change;
use PHPUnit\Framework\TestCase;

class ChangeTest extends TestCase
{
    /** @test */
    public function it_can_set_properties_trough_the_constructor()
    {
        $change = new Change('added', 'Added a new feature');

        $type = $change->type();
        $message = $change->message();

        $this->assertEquals('added', $type);
        $this->assertEquals('Added a new feature', $message);
    }

    /** @test */
    public function change_type_is_converted_to_lowercase()
    {
        $changeA = new Change('Ucfirst', '');
        $changeB = new Change('UPPERCASE', '');

        $typeA = $changeA->type();
        $typeB = $changeB->type();

        $this->assertEquals('ucfirst', $typeA);
        $this->assertEquals('uppercase', $typeB);
    }
}
