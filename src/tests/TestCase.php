<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Override the RefreshDatabase migration step to bypass PendingCommand.
     *
     * RefreshDatabase::refreshDatabase() calls $this->artisan('migrate') (or
     * migrate:fresh) which wraps the command in PendingCommand. PendingCommand
     * creates a strict Mockery partial mock of OutputStyle; if the migrate
     * command calls confirm() for any reason Mockery throws BadMethodCallException
     * that PendingCommand does not catch, killing every test in CI.
     *
     * We force SQLite in-memory config at runtime so this works even when a
     * config cache or stale env vars point at MySQL. Then we run migrate
     * directly via the Kernel (no PendingCommand), and wrap in a transaction
     * for per-test isolation.
     */
    public function refreshDatabase()
    {
        $this->app['config']->set('database.default', 'sqlite');
        $this->app['config']->set('database.connections.sqlite.database', ':memory:');

        $this->beforeRefreshingDatabase();
        $this->app[Kernel::class]->call('migrate');
        $this->app[Kernel::class]->setArtisan(null);
        $this->beginDatabaseTransaction();
        $this->afterRefreshingDatabase();
    }
}
