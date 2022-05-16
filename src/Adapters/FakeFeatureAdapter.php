<?php

namespace MarkWalet\Changelog\Adapters;

use MarkWalet\Changelog\Change;
use MarkWalet\Changelog\Exceptions\FileNotFoundException;
use MarkWalet\Changelog\Exceptions\InvalidXmlException;
use MarkWalet\Changelog\Feature;

class FakeFeatureAdapter implements FeatureAdapter
{
    /**
     * @var Change[]|array
     */
    private array $changes = [];
    /**
     * @var string[]|array
     */
    private array $invalidChanges = [];

    /**
     * Set a lis of changes for the given path.
     *
     * @param string $path
     * @param Feature $changes
     */
    public function setChanges(string $path, Feature $changes): void
    {
        $this->changes[$path] = $changes;
    }

    /**
     * Load a feature.
     *
     * @param string $path
     * @return Feature
     */
    public function read(string $path): Feature
    {
        if ($this->exists($path) === false) {
            throw new FileNotFoundException($path);
        }

        if (in_array($path, $this->invalidChanges)) {
            throw new InvalidXmlException("Xml file on $path is invalid.");
        }

        return $this->changes[$path];
    }

    /**
     * Store a feature.
     *
     * @param string $path
     * @param Feature $feature
     */
    public function write(string $path, Feature $feature): void
    {
        $this->changes[$path] = $feature;
    }

    /**
     * Store an invalid feature.
     *
     * @param string $path
     * @return void
     */
    public function writeInvalid(string $path): void
    {
        $this->invalidChanges[] = $path;
    }

    /**
     * Check if there is an existing feature on the given path.
     *
     * @param string $path
     * @return bool
     */
    public function exists(string $path): bool
    {
        return array_key_exists($path, $this->changes) || in_array($path, $this->invalidChanges);
    }
}
