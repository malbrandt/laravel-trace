<?php

namespace Malbrandt\Laravel\Trace\Tests;


use Malbrandt\Laravel\Trace\AuthorRequestResolver;
use Malbrandt\Laravel\Trace\Contracts\TraceCollectorInterface;
use Malbrandt\Laravel\Trace\Contracts\TraceInterface;
use Malbrandt\Laravel\Trace\Facade\Trace;
use Malbrandt\Laravel\Trace\TraceCollector;

class TraceCollectorInterfaceTest extends TestCase
{
    /**
     * @var TraceCollectorInterface
     */
    protected $collector;

    /** @test */
    public function can_add_new_trace_item()
    {
        $info = Trace::prepare('Foo', TraceInterface::TYPE_INFO);

        $this->collector->collect($info);

        $warning = Trace::prepare('Bar', TraceInterface::TYPE_WARNING);
        $this->collector->collect($warning);

        $collected = $this->collector->getCollectedTrace();
        $this->assertEquals($info, $collected[0]);
        $this->assertEquals($warning, $collected[1]);
    }

    /** @test */
    public function can_add_custom_context_resolver()
    {
        $fooResolver = function () {
            return 'bar';
        };

        $this->collector->addContextResolver('foo', $fooResolver);
        $this->collector->collect(Trace::prepare('Test'));

        $collected = $this->collector->getCollectedTrace();
        $item = $collected[0];

        $this->assertEquals('bar', $item->getContext()['foo']);
    }

    /** @test */
    public function returns_collected_trace()
    {
        $this->collector->collect($info = Trace::prepare('info', TraceInterface::TYPE_INFO));
        $this->collector->collect($warning = Trace::prepare('warning', TraceInterface::TYPE_WARNING));
        $this->collector->collect($error = Trace::prepare('error', TraceInterface::TYPE_ERROR));
        $this->collector->collect($decision = Trace::prepare('decision', TraceInterface::TYPE_DECISION));

        $collected = $this->collector->getCollectedTrace();

        $this->assertEquals($info, $collected[0]);
        $this->assertEquals($warning, $collected[1]);
        $this->assertEquals($error, $collected[2]);
        $this->assertEquals($decision, $collected[3]);
    }

    /** @test */
    public function clears_collected_trace()
    {
        $this->collector->collect(Trace::prepare('foo'));
        $this->assertCount(1, $this->collector->getCollectedTrace());

        $this->collector->collect(Trace::prepare('bar'));
        $this->assertCount(2, $this->collector->getCollectedTrace());

        $this->collector->clear();
        $this->assertCount(0, $this->collector->getCollectedTrace());
    }

    /** @test */
    public function does_not_collect_trace_when_beforeCollect_returns_null()
    {
        $infoCollector = new class(new AuthorRequestResolver()) extends TraceCollector
        {
            public function beforeCollect($trace)
            {
                if ($trace->getType() !== TraceInterface::TYPE_INFO) {
                    return null;
                }

                return parent::beforeCollect($trace);
            }
        };

        $infoCollector->collect(Trace::prepare('Info1', TraceInterface::TYPE_INFO));
        $infoCollector->collect(Trace::prepare('Info2', TraceInterface::TYPE_INFO));
        $infoCollector->collect(Trace::prepare('Warning1', TraceInterface::TYPE_WARNING));
        $infoCollector->collect(Trace::prepare('Error1', TraceInterface::TYPE_ERROR));

        $this->assertCount(2, $infoCollector->getCollectedTrace());
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->collector = $this->app->make('Malbrandt\Laravel\Trace\Contracts\TraceCollectorInterface');
    }
}
