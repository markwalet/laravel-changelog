<?php

namespace MarkWalet\Changelog\Commands;

use Illuminate\Console\Command;
use MarkWalet\Changelog\Adapters\FeatureAdapter;
use MarkWalet\Changelog\Change;
use MarkWalet\Changelog\Feature;
use MarkWalet\GitState\Drivers\GitDriver;

class ChangelogAddCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'changelog:add {--type=} {--message=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a change to the current feature entry';

    /**
     * Execute the command.
     *
     * @param GitDriver      $gitState
     * @param FeatureAdapter $adapter
     */
    public function handle(GitDriver $gitState, FeatureAdapter $adapter)
    {
        $path = $this->path($gitState);

        $feature = $this->changelog($adapter, $path);

        $change = new Change(
            $this->getTypeArgument(),
            $this->getMessageArgument()
        );

        $feature->add($change);

        $this->write($adapter, $path, $feature);

        $this->info("Wrote a new change to the feature file.");
    }

    /**
     * Write the updated feature to the filesystem.
     * 
     * @param FeatureAdapter $adapter
     * @param string         $path
     * @param Feature        $feature
     */
    private function write(FeatureAdapter $adapter, string $path, Feature $feature)
    {
        $adapter->write($path, $feature);
    }

    /**
     * Get the feature for the current path.
     * If it doesn't exist, create a new feature instance.
     *
     * @param FeatureAdapter $adapter
     * @param string         $path
     * @return Feature
     */
    private function changelog(FeatureAdapter $adapter, string $path): Feature
    {
        if ($adapter->exists($path)) {
            return $adapter->read($path);
        }

        return new Feature;
    }

    /**
     * Generate a path.
     *
     * @param GitDriver $gitState
     * @return string
     */
    private function path(GitDriver $gitState)
    {
        return config('changelog.path')
            . DIRECTORY_SEPARATOR . 'unreleased'
            . DIRECTORY_SEPARATOR . $gitState->currentBranch() . '.xml';
    }

    /**
     * Get the type argument.
     *
     * @return string
     */
    private function getTypeArgument(): string
    {
        $type = $this->option('type');

        if (is_null($type)) {
            $type = $this->ask("type");
        }

        return $type;
    }

    /**
     * Get the message argument.
     *
     * @return string
     */
    private function getMessageArgument(): string
    {
        $message = $this->option('message');

        if (is_null($message)) {
            $message = $this->ask("message");
        }

        return $message;
    }
}
