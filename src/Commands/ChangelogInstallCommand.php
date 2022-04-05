<?php

namespace MarkWalet\Changelog\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ChangelogInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'changelog:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a folder for the changelog files';

    /**
     * Execute the command.
     */
    public function handle()
    {
        File::makeDirectory($this->folder());
        File::put($this->folder() . DIRECTORY_SEPARATOR . '.gitkeep', '');
    }

    /**
     * Get the folder where the gitkeep file should be written to.
     *
     * @return string
     */
    public function folder(): string
    {
        return config('changelog.path').DIRECTORY_SEPARATOR.'unreleased';
    }
}
