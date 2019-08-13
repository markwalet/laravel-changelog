<?php

namespace MarkWalet\Changelog\Tests\Concerns;

use MarkWalet\Changelog\Change;
use MarkWalet\Changelog\Concerns\CanSortReleases;
use MarkWalet\Changelog\Release;
use PHPUnit\Framework\TestCase;

class CanSortVersionsTest extends TestCase
{
    /** @test */
    public function it_can_sort_versions_alphabetically()
    {
        $instance = new SortableReleasesTestClass;

        $sorted = $instance->sortVersions(['AAA', 'CCC', 'BBB']);

        $this->assertEquals(['CCC', 'BBB', 'AAA'], $sorted);
    }

    /** @test */
    public function it_can_sort_versions_on_version_number()
    {
        $instance = new SortableReleasesTestClass;

        $sorted = $instance->sortVersions(['v1.0.4', 'v1.0.10', 'v1.0.2']);

        $this->assertEquals(['v1.0.10', 'v1.0.4', 'v1.0.2'], $sorted);
    }

    /** @test */
    public function it_always_sorts_an_unreleased_version_first()
    {
        $instance = new SortableReleasesTestClass;

        $sortedA = $instance->sortVersions(['unreleased', 'v1.0.10', 'v1.0.2']);
        $sortedB = $instance->sortVersions(['v2.0.1', 'v1.0.10', 'unreleased']);

        $this->assertEquals('unreleased', $sortedA[0]);
        $this->assertEquals('unreleased', $sortedB[0]);
    }
}

class SortableReleasesTestClass {
    use CanSortReleases;
}