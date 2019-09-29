<?php


namespace Malbrandt\Laravel\Trace\Tests;


use Orchestra\Testbench\TestCase as OrchestraTestbench;

abstract class TestCase extends OrchestraTestbench
{
    /**
     * Tells if migrations should be run before each test (including migrations from tests dir).
     *
     * **Default: false**
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
        // TODO: add method exists check for loadMigrationsFrom (for compatibility purposes)
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
