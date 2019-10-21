<?php

namespace Malbrandt\Laravel\Trace\Tests\Persiters;

use Malbrandt\Laravel\Trace\Facade\Trace;
use Malbrandt\Laravel\Trace\Tests\TestCase;
use Malbrandt\Laravel\Trace\TraceModel;

class EloquentTracePersisterTest extends TestCase
{
    protected $runMigrations = true;

    /** @test */
    public function persists_trace_in_the_database()
    {
        $this->assertEquals(0, TraceModel::query()->count());

        Trace::info('info');
        Trace::warning('warning');
        Trace::persist();

        $this->assertEquals(2, TraceModel::query()->count());
    }

    /** @test */
    public function serializes_context_properly()
    {
        $this->assertEquals(0, TraceModel::query()->count());

        Trace::info('info', ['foo' => 'bar']);
        Trace::persist();

        $trace = TraceModel::first();
        $this->assertEquals('info', $trace->message);
        $this->assertEquals(TraceModel::TYPE_INFO, $trace->type);
        $this->assertEquals(['foo' => 'bar'], $trace->context);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpPersister();
    }

    protected function setUpPersister()
    {
        Trace::setPersister(app()->make('Malbrandt\Laravel\Trace\Persisters\EloquentTracePersister'));
    }
}
