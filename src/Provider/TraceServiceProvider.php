<?php


namespace Malbrandt\Laravel\Trace\Provider;


use Illuminate\Support\ServiceProvider;

class TraceServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([__DIR__ . '/../../config/trace.php' => config_path('trace.php'),], 'config');
        $this->publishes([__DIR__ . '/../../database/migrations' => database_path('migrations'),], 'migrations');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/trace.php', 'trace');

        $this->app->bind('Malbrandt\Laravel\Trace\Contracts\AuthorResolverInterface', 'Malbrandt\Laravel\Trace\AuthorRequestResolver');
        $this->app->bind('Malbrandt\Laravel\Trace\Contracts\TraceCollectorInterface', 'Malbrandt\Laravel\Trace\TraceCollector');
        $this->app->bind('Malbrandt\Laravel\Trace\Contracts\TracePersisterInterface', config('trace.persistence.driver'));
        $this->app->bind('Malbrandt\Laravel\Trace\Contracts\TraceFactoryInterface', 'Malbrandt\Laravel\Trace\TraceFactory');
        $this->app->singleton('Malbrandt\Laravel\Trace\Contracts\TraceManagerInterface', 'Malbrandt\Laravel\Trace\TraceManager');
        $this->app->singleton('trace', 'Malbrandt\Laravel\Trace\Contracts\TraceManagerInterface');
    }
}
