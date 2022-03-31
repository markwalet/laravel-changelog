<?php

namespace MarkWalet\Changelog;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Arr;
use MarkWalet\Changelog\Exceptions\InvalidArgumentException;
use MarkWalet\Changelog\Exceptions\MissingConfigurationException;
use MarkWalet\Changelog\Formatters\ChangelogFormatter;

class ChangelogFormatterFactory
{
    /** @var Application */
    protected Application $app;

    /**
     * ChangelogFormatterFactory constructor.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Create a new formatter instance.
     *
     * @param string $type
     * @return ChangelogFormatter
     */
    public function make(string $type): ChangelogFormatter
    {
        $config = $this->config($type);

        if (Arr::exists($config, 'driver') === false) {
            throw new MissingConfigurationException("changelog.formatters.$type.driver");
        }

        $driver = Arr::pull($config, 'driver');

        if (Arr::has(class_parents($driver), ChangelogFormatter::class) === false) {
            throw new InvalidArgumentException("The driver $driver is not a valid driver.");
        }

        return new $driver($config);
    }

    /**
     * Get the config for a given formatter type.
     *
     * @param string $type
     * @return string[]|array
     */
    private function config(string $type): array
    {
        $config = $this->app['config']['changelog.formatters'];

        if (array_key_exists($type, $config) === false) {
            throw new MissingConfigurationException("changelog.formatters.$type");
        }

        return $config[$type];
    }
}
