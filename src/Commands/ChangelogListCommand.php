<?php

namespace MarkWalet\Changelog\Commands;

use Illuminate\Console\Command;
use MarkWalet\Changelog\Adapters\ReleaseAdapter;
use MarkWalet\Changelog\ChangelogFormatterFactory;
use MarkWalet\Changelog\Release;

class ChangelogListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'changelog:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show a list of changes for all versions';

    /**
     * Execute the command.
     *
     * @param ReleaseAdapter $adapter
     * @param ChangelogFormatterFactory $factory
     */
    public function handle(ReleaseAdapter $adapter, ChangelogFormatterFactory $factory)
    {
        $formatter = $factory->make('text');
        $path = config('changelog.path');

        $releases = $this->releases($adapter, $path);

        $formatted = $formatter->multiple($releases);

        $this->line($formatted);
    }

    /**
     * Get the releases for the given path.
     *
     * @param ReleaseAdapter $adapter
     * @param string $path
     * @return Release[]|array
     */
    private function releases(ReleaseAdapter $adapter, string $path): array
    {
        return $adapter->all($path);
    }
}
