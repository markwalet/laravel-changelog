<?php

namespace MarkWalet\Changelog\Commands;

use Illuminate\Console\Command;
use MarkWalet\Changelog\Adapters\FeatureAdapter;
use MarkWalet\Changelog\Exceptions\FileNotFoundException;
use MarkWalet\Changelog\Exceptions\InvalidXmlException;
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

        try {
            $feature = $adapter->read($path);
        } catch(InvalidXmlException $e) {
            $this->error('Something went wrong while parsing the xml file.');

            return;
        }

        $this->line("Changes for $branch:");
        foreach ($feature->changes() as $change) {
            $type = ucfirst($change->type());
            $this->line(" - $type: {$change->message()}");
        }
    }
}
