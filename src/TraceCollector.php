<?php


namespace Malbrandt\Laravel\Trace;

use Exception;
use InvalidArgumentException;
use Malbrandt\Laravel\Trace\Contracts\TraceCollectorInterface;
use Malbrandt\Laravel\Trace\Contracts\TraceInterface;
use Malbrandt\Laravel\Trace\Errors\ContextResolvingError;

/**
 * TODO:1.1: docs
 * @package Malbrandt\Laravel\Trace
 */
class TraceCollector implements TraceCollectorInterface
{
    /**
     * Collected trace items.
     *
     * @var TraceInterface[]
     */
    private $collectedTrace = [];

    /**
     * An array with context resolvers.
     *
     * @var array
     */
    private $contextResolvers = [];


    /**
     * Returns collected trace.
     *
     * @return TraceInterface[]
     */
    public function getCollectedTrace()
    {
        return $this->collectedTrace;
    }


    /**
     * {@inheritDoc}
     */
    public function collect($trace)
    {
        if ($this->beforeCollect($trace) !== null
            && $this->canCollect($trace)
        ) {
            $this->collectedTrace[] = $trace;

            return $trace;
        }

        return null;
    }

    /**
     * Callback called before collecting the trace.
     * Can be used to fill some details in trace item or to refuse collecting (with returning "null" value).
     *
     * @param TraceInterface $trace
     * @return TraceInterface|null TraceModel item if we want to add this item to this pool or null, if we want to skip the trace item.
     * @throws ContextResolvingError
     */
    protected function beforeCollect($trace)
    {
        return $trace->setContext(array_merge($trace->getContext(), $this->resolveContext($trace)));
    }

    /**
     * Clears the current trace pool.
     *
     * @return int Number of removed items.
     */
    public function clear()
    {
        $numRemoved = count($this->collectedTrace);

        $this->collectedTrace = [];

        return $numRemoved;
    }

    /**
     * Examines if the trace can be collected. Can be used i.e. to intercept collection of trace items.
     * It's called after collecting trace' context, so you can use context to examine, if trace should be collected.
     *
     * I.e., if you're collecting logged user permissions to your trace context,
     * you could limit trace collection to non-admin users only, or to some
     * certain classes/methods (using PHP reflection mechanisms).
     *
     *
     * @param TraceInterface $trace
     * @return bool
     */
    private function canCollect($trace)
    {
        //TODO:1.1: white- and blacklist, predicate/context based tracing
        return true;
    }

    /**
     * Registers context resolver (callback) that would be used before collecting trace to add some details to it.
     * I.e. u can add "user.id" context resolver that would add current user.id to trace details or "client.ip" to
     * resolve current request client IP address.
     *
     * @param string $name
     * @param callable $collector
     * @return self
     */
    public function addContextResolver($name, $collector)
    {
        if (! is_callable($collector)) {
            throw new InvalidArgumentException(
                'Invalid context resolver. Tip: context resolver should be a callable (i.e. anonymous function).'
            );
        }

        $this->contextResolvers[$name] = $collector;

        return $this;
    }

    /**
     * Resolve context for given trace item using context resolvers.
     * Context resolvers can be used to retrieve information about currently logged user ID (blame),
     * it's browser version, permissions and other important details.
     *
     * @param TraceInterface $trace
     * @return array
     * @throws ContextResolvingError
     */
    protected function resolveContext($trace): array
    {
        $resolvedContext = [];

        foreach ($this->contextResolvers as $name => $resolver) {
            try {
                $result = $resolver($trace);

                // If context resolver returns something, append this as additional context under resolver name.
                // Tip: resolvers receives $trace object instance, so they can directly modify attributes of trace model.
                if (! empty($result)) {
                    $resolvedContext[$name] = $result;
                }
            } catch (Exception $exception) {
                throw new ContextResolvingError(
                    'Cannot collect context for some trace context collector.',
                    0,
                    $exception
                );
            }
        }

        return $resolvedContext;
    }
}
