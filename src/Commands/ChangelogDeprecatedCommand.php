<?php

namespace MarkWalet\Changelog\Commands;

use Illuminate\Console\Command;
use MarkWalet\Changelog\Concerns\CallsAddCommand;

class ChangelogDeprecatedCommand extends Command
{
    use CallsAddCommand;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'changelog:deprecated {message}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a deprecated change to the current feature entry';

    /**
     * Execute the command.
     */
    public function handle()
    {
        $this->callAdd('deprecated', $this->argument('message'));
    }
}
