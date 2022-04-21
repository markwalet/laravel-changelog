<?php

namespace MarkWalet\Changelog\Commands;

use Illuminate\Console\Command;
use MarkWalet\Changelog\Adapters\FeatureAdapter;
use MarkWalet\GitState\Drivers\GitDriver;

class ChangelogCurrentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'changelog:current';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show a list of unreleased changes for the current branch.';

    /**
     * Execute the command.
     *
     * @param FeatureAdapter $adapter
     * @param GitDriver $gitState
     */
    public function handle(FeatureAdapter $adapter, GitDriver $gitState)
    {
        $branch = $gitState->currentBranch();
        $path = config('changelog.path').DIRECTORY_SEPARATOR.'unreleased'.DIRECTORY_SEPARATOR.$branch.'.xml';

        if (! $adapter->exists($path)) {
            $this->warn("No changes found for $branch");

            return;
        }

        $feature = $adapter->read($path);
        $this->line("Changes for $branch:");

        foreach ($feature->changes() as $change) {
            $type = ucfirst($change->type());
            $this->line(" - $type: {$change->message()}");
        }
    }
}
