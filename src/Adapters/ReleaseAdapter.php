<?php

namespace MarkWalet\Changelog\Adapters;

use MarkWalet\Changelog\Release;

interface ReleaseAdapter
{
    /**
     * Load a single release.
     *
     * @param string $path
     * @param string $version
     * @return Release
     */
    public function read(string $path, string $version): Release;

    /**
     * Load all releases for the given path.
     *
     * @param string $path
     * @return Release[]|array
     */
    public function all(string $path): array;

    /**
     * Move the unreleased changes to a versioned release.
     *
     * @param string $path
     * @param string $version
     */
    public function release(string $path, string $version): void;

    /**
     * Check if there is an existing release on the given path.
     *
     * @param string $path
     * @param string $version
     * @return bool
     */
    public function exists(string $path, string $version): bool;
}
