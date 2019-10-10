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
    abstract public function single(Release $release): string;

    /**
     * Format a list of releases to a string format.
     *
     * @param Release[]|array $releases
     * @return string
     */
    abstract public function multiple(array $releases): string;

    /**
     * Get a configuration value.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    protected function config(string $key, $default = null)
    {
        return (array_key_exists($key, $this->config))
            ? $this->config[$key]
            : $default;
    }
}
