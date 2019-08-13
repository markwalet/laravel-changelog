<?php

namespace MarkWalet\Changelog\Tests\Commands;

use MarkWalet\Changelog\Adapters\FeatureAdapter;
use MarkWalet\Changelog\Adapters\FakeFeatureAdapter;
use MarkWalet\Changelog\Change;
use MarkWalet\Changelog\Feature;
use MarkWalet\Changelog\Tests\LaravelTestCase;
use MarkWalet\GitState\Drivers\FakeGitDriver;
use MarkWalet\GitState\Drivers\GitDriver;

class ChangelogAddCommandTest extends LaravelTestCase
{
    /**
     * Get and bind a fake adapter.
     *
     * @return FakeFeatureAdapter
     */
    private function fakeAdapter()
    {
        $adapter = new FakeFeatureAdapter;
        $this->app->singleton(FeatureAdapter::class, function () use ($adapter) {
            return $adapter;
        });

        return $adapter;
    }

    /**
     * Create a fake git driver with the given branch.
     *
     * @param string $branch
     * @return FakeGitDriver
     */
    private function fakeBranch(string $branch): FakeGitDriver
    {
        $driver = new FakeGitDriver(['branch' => $branch]);
        $this->app->bind(GitDriver::class, function () use ($driver) {
            return $driver;
        });

        return $driver;
    }

    /** @test */
    public function it_creates_a_new_changelog_when_there_is_no_changelog_yet()
    {
        $this->app['config']['changelog.path'] = 'test-path';
        $adapter = $this->fakeAdapter();
        $this->fakeBranch('test-branch');

        $this->artisan('changelog:add')
            ->expectsQuestion('type', 'added')
            ->expectsQuestion('message', 'Added a new feature.');

        $exists = $adapter->exists('test-path/unreleased/test-branch.xml');
        $this->assertTrue($exists);

        $feature = $adapter->read('test-path/unreleased/test-branch.xml');
        $changes = $feature->changes();

        $this->assertCount(1, $changes);
        $this->assertEquals('added', $changes[0]->type());
        $this->assertEquals('Added a new feature.', $changes[0]->message());
    }

    /** @test */
    public function it_can_call_the_command_in_one_line()
    {
        $this->app['config']['changelog.path'] = 'test-path';
        $adapter = $this->fakeAdapter();
        $this->fakeBranch('test-branch');

        $this->artisan('changelog:add', [
            '--type' => 'added',
            '--message' => 'Added a new feature.',
        ]);

        $exists = $adapter->exists('test-path/unreleased/test-branch.xml');
        $this->assertTrue($exists);

        $feature = $adapter->read('test-path/unreleased/test-branch.xml');
        $changes = $feature->changes();

        $this->assertCount(1, $changes);
        $this->assertEquals('added', $changes[0]->type());
        $this->assertEquals('Added a new feature.', $changes[0]->message());
    }

    /** @test */
    public function it_can_append_a_change_to_an_existing_changelog()
    {
        $this->app['config']['changelog.path'] = 'test-path';
        $adapter = $this->fakeAdapter();
        $adapter->setChanges('test-path/unreleased/test-branch.xml', new Feature([
            new Change('added', 'Added a new feature.'),
            new Change('removed', 'Removed an old feature.'),
        ]));
        $this->fakeBranch('test-branch');

        $this->artisan('changelog:add', [
            '--type' => 'changed',
            '--message' => 'Changed an existing feature.',
        ]);

        $exists = $adapter->exists('test-path/unreleased/test-branch.xml');
        $this->assertTrue($exists);

        $feature = $adapter->read('test-path/unreleased/test-branch.xml');
        $changes = $feature->changes();

        $this->assertCount(3, $changes);
        $this->assertEquals('added', $changes[0]->type());
        $this->assertEquals('Added a new feature.', $changes[0]->message());
        $this->assertEquals('removed', $changes[1]->type());
        $this->assertEquals('Removed an old feature.', $changes[1]->message());
        $this->assertEquals('changed', $changes[2]->type());
        $this->assertEquals('Changed an existing feature.', $changes[2]->message());
    }
}
