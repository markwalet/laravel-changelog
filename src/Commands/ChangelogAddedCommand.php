<?php

namespace MarkWalet\Changelog\Commands;

use Illuminate\Console\Command;

class ChangelogAddedCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'changelog:added {message}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a added change to the current feature entry';

    /**
     * Execute the command.
     */
    public function handle()
    {
        $this->call('changelog:add',
                    [
                        '--type' => 'added',
                        '--message' => $this->argument('message')
                    ]
        );
    }
}
