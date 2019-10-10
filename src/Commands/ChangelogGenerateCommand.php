<?php

namespace MarkWalet\Changelog\Commands;

use Illuminate\Console\Command;
use MarkWalet\Changelog\Adapters\ReleaseAdapter;
use MarkWalet\Changelog\ChangelogFormatterFactory;
use MarkWalet\Changelog\Release;
use Throwable;

class ChangelogGenerateCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'changelog:generate {--dry-run} {--path=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a markdown file based on your changes';

    /**
     * Execute the command.
     *
     * @param ReleaseAdapter $adapter
     * @param ChangelogFormatterFactory $factory
     * @throws Throwable
     */
    public function handle(ReleaseAdapter $adapter, ChangelogFormatterFactory $factory)
    {
        $formatter = $factory->make('markdown');
        $readPath = $this->readPath();
        $writePath = $this->writePath();

        $releases = $this->releases($adapter, $readPath);

        $formatted = $formatter->multiple($releases);
        $content = $this->view($formatted);

        if ($this->option('dry-run')) {
            $this->line($content);

            return;
        }

        file_put_contents($writePath, $content);
        $this->info('The changelog is written to: '.$writePath);
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

    /**
     * Format the given lines to a string.
     *
     * @param string $content
     * @return string|null
     * @throws Throwable
     */
    private function view(string $content): ?string
    {
        return view('changelog::changelog', compact('content'))->render();
    }

    /**
     * Get the path the changes should be read from.
     *
     * @return string
     */
    private function readPath(): string
    {
        return config('changelog.path');
    }

    /**
     * Get the path the changes should be written to.
     *
     * @return string
     */
    private function writePath(): string
    {
        $path = $this->option('path');

        return (is_null($path)) ? config('changelog.changelog_path') : $path;
    }
}
