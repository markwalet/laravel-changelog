<?php

namespace MarkWalet\Changelog;

class Release
{
    /**
     * @var Change[]|array
     */
    private $changes;

    /**
     * @var string
     */
    private $version;

    /**
     * Change constructor.
     *
     * @param string $version
     */
    public function __construct(string $version)
    {
        $this->version = $version;
        $this->changes = [];
    }

    /**
     * Add a feature to the release.
     *
     * @param Feature $feature
     */
    public function add(Feature $feature): void
    {
        // Add all changes of the given feature to the release.
        foreach ($feature->changes() as $change) {
            $this->changes[] = $change;
        }

        // Prevent sorting when the list of changes is not big enough.
        if (count($this->changes) <= 1) {
            return;
        }

        // Sort all items.
        usort($this->changes, function (Change $first, Change $second) {
            if ($first->type() === $second->type()) {
                return $first->message() < $second->message() ? -1 : 1;
            }

            return $first->type() < $second->type() ? -1 : 1;
        });
    }

    /**
     * Get a list of all changes for the release.
     *
     * @return array|Change[]
     */
    public function changes(): array
    {
        return $this->changes;
    }

    /**
     * Get the version for the release.
     *
     * @return string
     */
    public function version(): string
    {
        return $this->version;
    }

    /**
     * Update the version of this release.
     *
     * @param string $version
     */
    public function setVersion(string $version): void
    {
        $this->version = $version;
    }
}
