<?php

namespace MarkWalet\Changelog;

use Illuminate\Support\Arr;

class Feature
{
    /**
     * @var Change[]|array
     */
    private $changes;

    /**
     * Feature constructor.
     *
     * @param Change[]|array $changes
     */
    public function __construct(array $changes = [])
    {
        $this->changes = $changes;
    }

    /**
     * Get a list of changes in the feature.
     *
     * @return Change[]|array
     */
    public function changes(): array
    {
        return $this->changes;
    }

    /**
     * Add a change to the feature.
     *
     * @param Change $change
     */
    public function add(Change $change): void
    {
        $this->changes[] = $change;
    }

    /**
     * Remove the given change from the feature.
     *
     * @param int $line
     */
    public function remove(int $line): void
    {
        $this->changes = Arr::except($this->changes, $line);
    }
}
