<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Override RefreshDatabase's migrate call to bypass PendingCommand.
     *
     * The default implementation calls $this->artisan('migrate') which wraps
     * the command in PendingCommand. PendingCommand creates a strict Mockery
     * partial mock of OutputStyle; if migrate calls confirm() for any reason
     * (e.g. "SQLite database does not exist, create it?"), Mockery throws
     * BadMethodCallException which PendingCommand does not catch, killing the
     * test. Using Kernel::call() directly skips PendingCommand entirely.
     */
    protected function refreshInMemoryDatabase()
    {
        $this->app[Kernel::class]->call('migrate');
        $this->app[Kernel::class]->setArtisan(null);
    }
}
