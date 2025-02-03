<?php

namespace MarkWalet\Changelog\Tests;

use MarkWalet\Changelog\Change;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ChangeTest extends TestCase
{
    #[Test]
    public function it_can_set_properties_trough_the_constructor(): void
    {
        $change = new Change('added', 'Added a new feature');

        $type = $change->type();
        $message = $change->message();

        $this->assertEquals('added', $type);
        $this->assertEquals('Added a new feature', $message);
    }

    #[Test]
    public function change_type_is_converted_to_lowercase(): void
    {
        $changeA = new Change('Ucfirst', '');
        $changeB = new Change('UPPERCASE', '');

        $typeA = $changeA->type();
        $typeB = $changeB->type();

        $this->assertEquals('ucfirst', $typeA);
        $this->assertEquals('uppercase', $typeB);
    }
}
