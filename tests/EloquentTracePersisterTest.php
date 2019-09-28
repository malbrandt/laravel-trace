<?php

namespace Malbrandt\Laravel\Trace\Tests\Persiters;

use Malbrandt\Laravel\Trace\Contracts\TraceInterface;
use Malbrandt\Laravel\Trace\Facade\Trace;
use Malbrandt\Laravel\Trace\Tests\TestCase;
use Malbrandt\Laravel\Trace\Tests\TestModel;
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

    /** @test */
    public function can_have_parent()
    {
        $parent = TestModel::create(['key' => 'can_have_parent',]);

        Trace::info('with parent', [], $parent);
        Trace::persist();

        /** @var TraceInterface $trace */
        $trace = TraceModel::first();
        $this->assertEquals($parent->getAttributes(), $trace->getParent()->getAttributes());
    }

    /** @test */
    public function can_have_author()
    {
        $author = TestModel::create(['key' => 'can_have_author',]);

        Trace::info('with author', [])->setAuthor($author);
        Trace::persist();

        /** @var TraceInterface $trace */
        $trace = TraceModel::first();
        $this->assertEquals($author->getAttributes(), $trace->getAuthor()->getAttributes());
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
