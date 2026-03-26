<?php

namespace MarkWalet\Changelog\Commands;

use Illuminate\Console\Command;
use MarkWalet\Changelog\Adapters\ReleaseAdapter;
use MarkWalet\Changelog\ChangelogFormatterFactory;
use MarkWalet\Changelog\Commands\Concerns\WarnsAboutInvalidXml;
use MarkWalet\Changelog\Exceptions\InvalidXmlException;
use MarkWalet\Changelog\Release;

class ChangelogUnreleasedCommand extends Command
{
    use WarnsAboutInvalidXml;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'changelog:unreleased';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show a list of unreleased changes';

    /**
     * Execute the command.
     *
     * @param ReleaseAdapter $adapter
     * @param ChangelogFormatterFactory $factory
     */
    public function handle(ReleaseAdapter $adapter, ChangelogFormatterFactory $factory): int
    {
        $formatter = $factory->make('text');
        $path = config('changelog.path');

        try {
            $release = $this->release($adapter, $path);
        } catch (InvalidXmlException $exception) {
            return $this->warnAboutInvalidXml($exception);
        }

        $lines = explode(PHP_EOL, $formatter->single($release));

        foreach ($lines as $line) {
            $this->line($line);
        }

        return self::SUCCESS;
    }

    /**
     * Get the unreleased feature.
     *
     * @param ReleaseAdapter $adapter
     * @param string $path
     * @return Release
     */
    private function release(ReleaseAdapter $adapter, string $path): Release
    {
        if ($adapter->exists($path, 'unreleased') === false) {
            return new Release('unreleased');
        }

        return $adapter->read($path, 'unreleased');
    }
}
