<?php

namespace MarkWalet\Changelog\Commands;

use Illuminate\Console\Command;
use MarkWalet\Changelog\Concerns\CallsAddCommand;

class ChangelogFixedCommand extends Command
{
    use CallsAddCommand;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'changelog:fixed {message}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a fixed change to the current feature entry';

    /**
     * Execute the command.
     */
    public function handle()
    {
        $this->callAdd('fixed', $this->argument('message'));
    }
}
