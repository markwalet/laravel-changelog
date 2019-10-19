<?php

namespace MarkWalet\Changelog\Commands;

use Illuminate\Console\Command;
use MarkWalet\Changelog\Concerns\CallsAddCommand;

class ChangelogRemovedCommand extends Command
{
    use CallsAddCommand;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'changelog:removed {message}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a removed change to the current feature entry';

    /**
     * Execute the command.
     */
    public function handle()
    {
        $this->callAdd('removed', $this->argument('message'));
    }
}
