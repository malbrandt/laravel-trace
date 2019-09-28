<?php

namespace Malbrandt\Laravel\Trace;

use Illuminate\Database\Eloquent\Model;
use Malbrandt\Laravel\Trace\Contracts\TraceCollectorInterface;
use Malbrandt\Laravel\Trace\Contracts\TraceFactoryInterface;
use Malbrandt\Laravel\Trace\Contracts\TraceInterface;
use Malbrandt\Laravel\Trace\Contracts\TraceManagerInterface;
use Malbrandt\Laravel\Trace\Contracts\TracePersisterInterface;
use Malbrandt\Laravel\Trace\Events\TraceCollected;
use Malbrandt\Laravel\Trace\Events\TracePersisted;

/**
 * @package Malbrandt\Laravel\TraceModel
 */
class TraceManager implements TraceManagerInterface
{
    const VERSION = '1.0.0';

    /**
     * @var bool
     */
    public $raiseTraceCollectedEvents;
    /**
     * @var bool
     */
    public $raiseTracePersistedEvents;
    /**
     * Whether this manager is active.
     *
     * @var bool
     */
    protected $isEnabled;
    /**
     * Whether to persist collected items immediately.
     *
     * @var bool
     */
    protected $persistImmediately;
    /**
     * @var TraceCollectorInterface
     */
    protected $collector;
    /**
     * @var TracePersisterInterface
     */
    protected $persister;
    /**
     * @var TraceFactoryInterface
     */
    protected $factory;

    public function __construct(TraceCollectorInterface $collector, TracePersisterInterface $persister, TraceFactoryInterface $factory)
    {
        $this->persistImmediately = config('trace.persistence.moment') === 'immediately';
        $this->raiseTraceCollectedEvents = (bool)config('trace.events.TraceCollected');
        $this->raiseTracePersistedEvents = (bool)config('trace.events.TracePersisted');

        $this->collector = $collector;
        $this->persister = $persister;
        $this->factory = $factory;

        (bool)config('trace.enabled') ? $this->enable() : $this->disable();
    }

    public function info($message, $context = [], $parent = null)
    {
        return $this->collect(
            $this->prepare($message, TraceInterface::TYPE_INFO, $context, $this->examineCaller())
        )->setParent($parent);
    }

    public function collect($trace)
    {
        if (!$this->isEnabled) {
            return null;
        }

        $collected = $this->collector->collect($trace);

        if ($collected !== null) {
            if ($this->raiseTraceCollectedEvents) {
                event(new TraceCollected($trace));
            }

            if ($this->persistImmediately) {
                $this->persist();
            }
        }

        return $collected;
    }

    public function persist($options = [])
    {
        if (!$this->isEnabled) {
            return 0;
        }

        $numPersisted = $this->persister->persist(
            $this->collector->getCollectedTrace()
        );

        $this->collector->clear();

        if ($this->raiseTracePersistedEvents) {
            event(new TracePersisted($numPersisted));
        }

        return $numPersisted;
    }

    /**
     * @param $message
     * @param int $type
     * @param array $context
     * @param null $source
     * @return Model|TraceInterface
     */
    public function prepare($message, $type = TraceInterface::TYPE_INFO, $context = [], $source = null)
    {
        return $this->factory->make($message, $type, $context, $source);
    }

    /**
     * @param int $backwards
     * @return string
     */
    private function examineCaller($backwards = 4)
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, $backwards);
        $item = last($backtrace);

        return ($item['class'] ?? 'unknown class') . '@' . ($item['function'] ?? 'unknown function');
    }

    /**
     * @return TraceCollectorInterface
     */
    public function getCollector()
    {
        return $this->collector;
    }

    /**
     * @param TraceCollectorInterface $collector
     */
    public function setCollector($collector)
    {
        $this->collector = $collector;
    }

    /**
     * @return TracePersisterInterface
     */
    public function getPersister()
    {
        return $this->persister;
    }

    /**
     * @param TracePersisterInterface $persister
     */
    public function setPersister($persister)
    {
        $this->persister = $persister;
    }

    public function warning($message, $context = [], $parent = null)
    {
        return $this->collect(
            $this->prepare($message, TraceInterface::TYPE_WARNING, $context, $this->examineCaller())
        )->setParent($parent);
    }

    public function error($message, $context = [], $parent = null)
    {
        return $this->collect(
            $this->prepare($message, TraceInterface::TYPE_ERROR, $context, $this->examineCaller())
        )->setParent($parent);
    }

    public function decision($message, $context = [], $parent = null)
    {
        return $this->collect(
            $this->prepare($message, TraceInterface::TYPE_DECISION, $context, $this->examineCaller())
        )->setParent($parent);
    }

    /**
     * @return TraceFactoryInterface
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * @param TraceFactoryInterface $factory
     */
    public function setFactory($factory)
    {
        $this->factory = $factory;
    }

    /**
     * Turns on trace collecting and persisting.
     *
     * @return void
     */
    public function enable()
    {
        $this->isEnabled = true;
    }

    /**
     * Turns off trace collecting and persisting.
     *
     * @return void
     */
    public function disable()
    {
        $this->isEnabled = false;
    }
}
