<?php

namespace MarkWalet\Changelog\Formatters;

use MarkWalet\Changelog\Release;

class MarkdownChangelogFormatter extends ChangelogFormatter
{
    /**
     * Format a single release to a string format.
     *
     * @param Release $release
     * @return string
     */
    public function single(Release $release): string
    {
        $version = ucfirst($release->version());
        $lines = ["## [{$version}]"];

        $currentType = null;
        foreach ($release->changes() as $change) {

            if ($currentType !== $change->type()) {
                $currentType = $change->type();
                $lines[] = '';
                $lines[] = '### ' . ucfirst($change->type());
            }

            $lines[] = ' - ' . $change->message();
        }

        return implode(PHP_EOL, $lines);
    }

    /**
     * Format a list of releases to a string format.
     *
     * @param Release[]|array $releases
     * @return string
     */
    public function multiple(array $releases): string
    {
        $lines = [];
        foreach ($releases as $release) {
            $lines[] = '';
            $lines[] = $this->single($release);
        }

        return implode(PHP_EOL, $lines);
    }
}