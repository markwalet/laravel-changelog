<?php

namespace MarkWalet\Changelog\Formatters;

use MarkWalet\Changelog\Release;

abstract class ChangelogFormatter
{
    /**
     * @var array
     */
    protected $config;

    /**
     * ChangelogFormatter constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * Format a single release to a string format.
     *
     * @param Release $release
     * @return string
     */
    public abstract function single(Release $release): string;

    /**
     * Format a list of releases to a string format.
     *
     * @param Release[]|array $releases
     * @return string
     */
    public abstract function multiple(array $releases): string;
}