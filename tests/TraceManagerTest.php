<?php

namespace Malbrandt\Laravel\Trace\Tests;


use Illuminate\Support\Facades\Event;
use Malbrandt\Laravel\Trace\Contracts\TraceManagerInterface;
use Malbrandt\Laravel\Trace\Events\TraceCollected;
use Malbrandt\Laravel\Trace\Events\TracePersisted;
use Malbrandt\Laravel\Trace\Facade\Trace;

class TraceManagerTest extends TestCase
{
    protected $runMigrations = true;

    /**
     * @var TraceManagerInterface $manager
     */
    private $manager;

    /** @test */
    public function can_turn_on_TraceCollected_events()
    {
        Event::fake([TraceCollected::class]);
        $this->expectsEvents(TraceCollected::class);
        $this->manager->raiseTraceCollectedEvents = true;
        Trace::info('test');
    }

    /** @test */
    public function can_turn_off_TraceCollected_events()
    {
        Event::fake([TraceCollected::class]);
        $this->doesntExpectEvents(TraceCollected::class);
        $this->manager->raiseTraceCollectedEvents = false;
        Trace::info('test');
    }

    /** @test */
    public function can_turn_on_TracePersisted_events()
    {
        Event::fake([TracePersisted::class]);
        $this->expectsEvents(TracePersisted::class);
        $this->manager->raiseTracePersistedEvents = true;
        Trace::info('test');
        Trace::persist();
    }

    /** @test */
    public function can_turn_off_TracePersisted_events()
    {
        Event::fake([TracePersisted::class]);
        $this->doesntExpectEvents(TracePersisted::class);
        $this->manager->raiseTracePersistedEvents = false;
        Trace::info('test');
        Trace::persist();
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->manager = Trace::getFacadeRoot();
    }
}
