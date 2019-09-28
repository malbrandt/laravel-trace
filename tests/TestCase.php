<?php


namespace Malbrandt\Laravel\Trace\Tests;


use Orchestra\Testbench\TestCase as OrchestraTestbench;

abstract class TestCase extends OrchestraTestbench
{
    /**
     * Examines whether to run the migrations (including test migrations).
     *
     * @var bool
     */
    protected $runMigrations = false;

    protected function setUp(): void
    {
        parent::setUp();

        if ($this->runMigrations) {
            $this->runMigrations();
        }
    }

    protected function runMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->artisan('migrate', ['--database' => 'testbench'])->run();
    }

    protected function getPackageProviders($app)
    {
        return ['Malbrandt\Laravel\Trace\Provider\TraceServiceProvider'];
    }

    protected function getPackageAliases($app)
    {
        return [
            'trace' => 'Malbrandt\Laravel\Trace\Facade\Trace',
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }
}
