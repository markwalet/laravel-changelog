<?php

namespace MarkWalet\Changelog\Adapters;

use MarkWalet\Changelog\Feature;

interface FeatureAdapter
{
    /**
     * Load a feature.
     *
     * @param string $path
     * @return Feature
     */
    public function read(string $path): Feature;

    /**
     * Store a feature
     *
     * @param string  $path
     * @param Feature $feature
     */
    public function write(string $path, Feature $feature): void;

    /**
     * Check if there is an existing feature on the given path.
     *
     * @param string $path
     * @return bool
     */
    public function exists(string $path): bool;
}
