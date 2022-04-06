<?php

namespace MarkWalet\Changelog\Tests\Commands;

use Illuminate\Support\Facades\File;
use MarkWalet\Changelog\Tests\LaravelTestCase;

class ChangelogInstallCommandTest extends LaravelTestCase
{
    /** @test */
    public function it_creates_a_gitkeep_file_in_the_configured_folder_once()
    {
        $this->app['config']['changelog.path'] = __DIR__.'/.changes';

        $this->artisan('changelog:install');

        $this->assertFileExists(__DIR__.'/.changes/unreleased/.gitkeep');

        $this->artisan('changelog:install');

        $this->assertFileExists(__DIR__.'/.changes/unreleased/.gitkeep');

        File::deleteDirectory(__DIR__.'/.changes');
    }
}
