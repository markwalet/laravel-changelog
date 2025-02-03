<?php

namespace MarkWalet\Changelog\Tests\Concerns;

use MarkWalet\Changelog\Concerns\CanSortReleases;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class CanSortVersionsTest extends TestCase
{
    #[Test]
    public function it_can_sort_versions_alphabetically(): void
    {
        $instance = new SortableReleasesTestClass;

        $sorted = $instance->sortVersions(['AAA', 'CCC', 'BBB']);

        $this->assertEquals(['CCC', 'BBB', 'AAA'], $sorted);
    }

    #[Test]
    public function it_can_sort_versions_on_version_number(): void
    {
        $instance = new SortableReleasesTestClass;

        $sorted = $instance->sortVersions(['v1.0.4', 'v1.0.10', 'v1.0.2']);

        $this->assertEquals(['v1.0.10', 'v1.0.4', 'v1.0.2'], $sorted);
    }

    #[Test]
    public function it_always_sorts_an_unreleased_version_first(): void
    {
        $instance = new SortableReleasesTestClass;

        $sortedA = $instance->sortVersions(['unreleased', 'v1.0.10', 'v1.0.2']);
        $sortedB = $instance->sortVersions(['v2.0.1', 'v1.0.10', 'unreleased']);

        $this->assertEquals('unreleased', $sortedA[0]);
        $this->assertEquals('unreleased', $sortedB[0]);
    }
}

class SortableReleasesTestClass
{
    use CanSortReleases;
}
