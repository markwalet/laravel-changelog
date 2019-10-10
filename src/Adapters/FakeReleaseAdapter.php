<?php

namespace MarkWalet\Changelog\Adapters;

use MarkWalet\Changelog\Concerns\CanSortReleases;
use MarkWalet\Changelog\Exceptions\DirectoryNotFoundException;
use MarkWalet\Changelog\Exceptions\FileNotFoundException;
use MarkWalet\Changelog\Exceptions\VersionAlreadyExistsException;
use MarkWalet\Changelog\Release;

class FakeReleaseAdapter implements ReleaseAdapter
{
    use CanSortReleases;

    /**
     * @var Release[][]|array
     */
    private $releases = [];

    /**
     * A a release to the list.
     *
     * @param string $path
     * @param string $version
     * @param Release $release
     */
    public function addRelease(string $path, string $version, Release $release)
    {
        if (array_key_exists($path, $this->releases) === false) {
            $this->releases[$path] = [];
        }

        $this->releases[$path][$version] = $release;
    }

    /**
     * Load a single release.
     *
     * @param string $path
     * @param string $version
     * @return Release
     */
    public function read(string $path, string $version): Release
    {
        if ($this->exists($path, $version) === false) {
            throw new FileNotFoundException($path);
        }

        return $this->releases[$path][$version];
    }

    /**
     * Move the unreleased changes to a versioned release.
     *
     * @param string $path
     * @param string $version
     */
    public function release(string $path, string $version): void
    {
        // Prevent release if the given version already exists.
        if ($this->exists($path, $version)) {
            throw new VersionAlreadyExistsException($version);
        }

        $this->releases[$path]['unreleased']->setVersion($version);
        $this->releases[$path][$version] = $this->releases[$path]['unreleased'];
        $this->releases[$path]['unreleased'] = new Release('unreleased');
    }

    /**
     * Load all releases for the given path.
     *
     * @param string $path
     * @return Release[]|array
     */
    public function all(string $path): array
    {
        if (array_key_exists($path, $this->releases) === false) {
            throw new DirectoryNotFoundException($path);
        }

        $versions = $this->releases[$path];

        // Sort versions.
        $versions = $this->sortVersions(array_keys($versions));

        $releases = [];
        foreach ($versions as $version) {
            $releases[] = $this->read($path, $version);
        }

        return $releases;
    }

    /**
     * Check if there is an existing release on the given path.
     *
     * @param string $path
     * @param string $version
     * @return bool
     */
    public function exists(string $path, string $version): bool
    {
        return array_key_exists($path, $this->releases)
            && array_key_exists($version, $this->releases[$path]);
    }
}
