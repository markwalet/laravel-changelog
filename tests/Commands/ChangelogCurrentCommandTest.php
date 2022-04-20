<?php

namespace MarkWalet\Changelog\Tests\Commands;

use MarkWalet\Changelog\Adapters\FeatureAdapter;
use MarkWalet\Changelog\Change;
use MarkWalet\Changelog\Feature;
use MarkWalet\Changelog\Tests\LaravelTestCase;
use MarkWalet\GitState\Drivers\GitDriver;
use Mockery\MockInterface;

class ChangelogCurrentCommandTest extends LaravelTestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('changelog.path', 'fake-path');
        $this->mock(GitDriver::class, function (MockInterface $git) {
            $git->expects('currentBranch')->once()->andReturn('test-branch');
        });
    }

    /** @test */
    public function it_can_list_all_changes_for_all_releases()
    {
        $this->mock(FeatureAdapter::class, function (MockInterface $adapter) {
            $adapter->allows('exists')->andReturnUsing(function (string $branch) {
                return $branch === 'fake-path/unreleased/test-branch.xml';
            });

            $adapter->allows('read')->andReturn(tap(new Feature(), function (Feature $feature) {
                $feature->add(new Change('added', 'Added a new feature.'));
                $feature->add(new Change('fixed', 'Fixed a bug.'));
            }));
        });

        $this->artisan('changelog:current')
            ->expectsOutput('Changes for test-branch:')
            ->expectsOutput(' - Added: Added a new feature.')
            ->expectsOutput(' - Fixed: Fixed a bug.');
    }

    /** @test */
    public function it_shows_a_warning_when_there_are_no_unreleased_changes_found_yet(): void
    {
        config()->set('changelog.path', 'fake-path');
        $this->mock(FeatureAdapter::class, function (MockInterface $adapter) {
            $adapter->allows('exists')->andReturn(false);
        });

        $this->artisan('changelog:current')
            ->expectsOutput('No changes found for test-branch');
    }
}
