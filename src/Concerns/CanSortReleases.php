<?php

namespace MarkWalet\Changelog\Concerns;

trait CanSortReleases
{
    /**
     * Sort the given version numbers.
     *
     * @param string[]|array $versions
     * @return string[]|array
     */
    public function sortVersions(array $versions): array
    {
        if (count($versions) > 1) {
            // Sort all items.
            usort($versions, function (string $first, string $second) {
                if ($first === 'unreleased') {
                    return -1;
                }

                if ($second === 'unreleased') {
                    return 1;
                }

                if (strtolower($first[0]) === 'v' && strtolower($second[0]) === 'v') {
                    return version_compare($second, $first);
                }

                return strcmp($second, $first);
            });
        }

        return $versions;
    }
}
