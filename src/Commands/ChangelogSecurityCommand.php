<?php

namespace MarkWalet\Changelog\Commands;

use Illuminate\Console\Command;
use MarkWalet\Changelog\Concerns\CallsAddCommand;

class ChangelogSecurityCommand extends Command
{
    use CallsAddCommand;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'changelog:security {message}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a security change to the current feature entry';

    /**
     * Execute the command.
     */
    public function handle()
    {
        $this->callAdd('security', $this->argument('message'));
    }
}
