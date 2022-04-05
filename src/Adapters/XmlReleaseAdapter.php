<?php

namespace MarkWalet\Changelog\Adapters;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use MarkWalet\Changelog\Concerns\CanSortReleases;
use MarkWalet\Changelog\Exceptions\DirectoryNotFoundException;
use MarkWalet\Changelog\Exceptions\FileNotFoundException;
use MarkWalet\Changelog\Exceptions\VersionAlreadyExistsException;
use MarkWalet\Changelog\Release;
use Symfony\Component\Finder\SplFileInfo;

class XmlReleaseAdapter implements ReleaseAdapter
{
    use CanSortReleases;

    /**
     * @var XmlFeatureAdapter
     */
    private XmlFeatureAdapter $featureAdapter;

    /**
     * XmlReleaseAdapter constructor.
     */
    public function __construct()
    {
        $this->featureAdapter = new XmlFeatureAdapter;
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
            throw new FileNotFoundException(realpath($path.DIRECTORY_SEPARATOR.$version));
        }

        $files = $this->filesForRelease($path, $version)
            ->map(fn (SplFileInfo $file) => $file->getPathname());

        $release = new Release($version);
        foreach ($files as $file) {
            $feature = $this->featureAdapter->read($file);
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
        // Prevent release if the given version already exists.
        if ($this->exists($path, $version)) {
            throw new VersionAlreadyExistsException($version);
        }

        $old = $path.DIRECTORY_SEPARATOR.'unreleased';
        $new = $path.DIRECTORY_SEPARATOR.$version;

//        $files = collect(File::files($old))
//            ->filter(fn (SplFileInfo $file) => $file->getExtension() === 'xml')
//            ->merge(File::cleanDirectory($old))
//            ->map(fn (SplFileInfo $file) => $file->getRelativePathname()));

        File::makeDirectory($new);
        collect(File::glob($old.DIRECTORY_SEPARATOR.'**'))
            ->map(fn (string $path) => substr($path, strlen($old)))
            ->each(function (string $file) use ($old, $new) {
                $source = $old . $file;
                $target = $new . $file;

                File::move($source, $target);
            });
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
            return is_dir($path.DIRECTORY_SEPARATOR.$p) && in_array($p, ['.', '..']) === false;
        });

        $versions = $this->sortVersions($versions);

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
        $fullPath = $path.DIRECTORY_SEPARATOR.$version;

        return file_exists($fullPath) && is_dir($fullPath);
    }

    /**
     * Get all xml files for a given release.
     *
     * @param string $path
     * @param string $version
     * @return Collection<SplFileInfo>
     */
    private function filesForRelease(string $path, string $version): Collection
    {
        return collect(File::allFiles($path.DIRECTORY_SEPARATOR.$version))
            ->filter(fn (SplFileInfo $file) => $file->getExtension() === 'xml');
    }
}
