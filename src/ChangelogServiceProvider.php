<?php

namespace MarkWalet\Changelog;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use MarkWalet\Changelog\Adapters\FeatureAdapter;
use MarkWalet\Changelog\Adapters\ReleaseAdapter;
use MarkWalet\Changelog\Adapters\XmlFeatureAdapter;
use MarkWalet\Changelog\Adapters\XmlReleaseAdapter;
use MarkWalet\Changelog\Commands\ChangelogAddCommand;
use MarkWalet\Changelog\Commands\ChangelogAddedCommand;
use MarkWalet\Changelog\Commands\ChangelogChangedCommand;
use MarkWalet\Changelog\Commands\ChangelogDeprecatedCommand;
use MarkWalet\Changelog\Commands\ChangelogFixedCommand;
use MarkWalet\Changelog\Commands\ChangelogGenerateCommand;
use MarkWalet\Changelog\Commands\ChangelogListCommand;
use MarkWalet\Changelog\Commands\ChangelogReleaseCommand;
use MarkWalet\Changelog\Commands\ChangelogRemovedCommand;
use MarkWalet\Changelog\Commands\ChangelogSecurityCommand;
use MarkWalet\Changelog\Commands\ChangelogUnreleasedCommand;

class ChangelogServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/changelog.php', 'changelog');

        $this->registerServices();
    }

    /**
     * Register Changelog services.
     */
    private function registerServices(): void
    {
        $this->app->singleton(FeatureAdapter::class, function () {
            return new XmlFeatureAdapter;
        });

        $this->app->singleton(ReleaseAdapter::class, function () {
            return new XmlReleaseAdapter;
        });

        $this->app->singleton(ChangelogFormatterFactory::class, function (Application $app) {
            return new ChangelogFormatterFactory($app);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/changelog.php' => $this->app->configPath('changelog.php'),
            __DIR__.'/../resources/views/changelog.blade.php' => $this->app->resourcePath('views/vendor/changelog/changelog.blade.php'),
        ]);

        $this->bootCommands();
        $this->bootViews();
    }

    /**
     * Boot commands when the application is running in the console.
     */
    private function bootCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ChangelogAddCommand::class,
                ChangelogListCommand::class,
                ChangelogUnreleasedCommand::class,
                ChangelogReleaseCommand::class,
                ChangelogGenerateCommand::class,

                //aliases
                ChangelogAddedCommand::class,
                ChangelogChangedCommand::class,
                ChangelogDeprecatedCommand::class,
                ChangelogRemovedCommand::class,
                ChangelogFixedCommand::class,
                ChangelogSecurityCommand::class,

            ]);
        }
    }

    /**
     * Load the publishable views.
     */
    private function bootViews(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'changelog');
    }
}
