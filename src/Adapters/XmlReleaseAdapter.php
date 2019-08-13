<?php

namespace MarkWalet\Changelog\Adapters;

use MarkWalet\Changelog\Concerns\CanSortReleases;
use MarkWalet\Changelog\Exceptions\DirectoryNotFoundException;
use MarkWalet\Changelog\Exceptions\FileNotFoundException;
use MarkWalet\Changelog\Exceptions\VersionAlreadyExistsException;
use MarkWalet\Changelog\Release;

class XmlReleaseAdapter implements ReleaseAdapter
{
    use CanSortReleases;

    /**
     * @var XmlFeatureAdapter
     */
    private $featureAdapter;

    /**
     * XmlReleaseAdapter constructor.
     */
    public function __construct()
    {
        $this->featureAdapter = new XmlFeatureAdapter;
    }

    /**
     * Load a single release
     *
     * @param string $path
     * @param string $version
     * @return Release
     */
    public function read(string $path, string $version): Release
    {
        $fullPath = $path . DIRECTORY_SEPARATOR . $version;

        if ($this->exists($path, $version) === false) {
            throw new FileNotFoundException($fullPath);
        }


        $files = array_filter(scandir($fullPath), function ($p) {
            return substr($p, -4) === '.xml';
        });

        $release = new Release($version);
        foreach($files as $file) {
            $feature = $this->featureAdapter->read($fullPath . DIRECTORY_SEPARATOR . $file);
            $release->add($feature);
        }

        return $release;
    }

    /**
     * Move the unreleased changes to a versioned release.
     *
     * @param string $path
     * @param string $version
     */
    public function release(string $path, string $version): void
    {
        $old = $path.DIRECTORY_SEPARATOR.'unreleased';
        $new = $path.DIRECTORY_SEPARATOR.$version;

        // Prevent release if the given version already exists.
        if ($this->exists($path, $version)) {
            throw new VersionAlreadyExistsException($version);
        }

        rename($old, $new);
    }

    /**
     * Load all releases for the given path.
     *
     * @param string $path
     * @return Release[]|array
     */
    public function all(string $path): array
    {
        if (is_dir($path) === false) {
            throw new DirectoryNotFoundException($path);
        }

        $versions = array_filter(scandir($path), function ($p) use ($path) {
            return is_dir($path.DIRECTORY_SEPARATOR.$p) && !in_array($p, ['.', '..']);
        });

        $versions = $this->sortVersions($versions);

        $releases = [];
        foreach($versions as $version) {
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
        $fullPath = $path . DIRECTORY_SEPARATOR . $version;

        return file_exists($fullPath) && is_dir($fullPath);
    }
}
