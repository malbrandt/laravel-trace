<?php


namespace Malbrandt\Laravel\Trace;


use InvalidArgumentException;
use Malbrandt\Laravel\Trace\Contracts\AuthorResolverInterface;
use Malbrandt\Laravel\Trace\Contracts\TraceCollectorInterface;
use Malbrandt\Laravel\Trace\Contracts\TraceInterface;
use Malbrandt\Laravel\Trace\Errors\ContextResolvingError;
use Throwable;

/**
 * TODO:1.1: docs
 * @package Malbrandt\Laravel\Trace
 */
class TraceCollector implements TraceCollectorInterface
{
    /**
     * Collected trace.
     *
     * @var TraceInterface[]
     */
    private $collectedTrace = [];

    /**
     * @var array
     */
    private $contextResolvers = [];
    /**
     * @var AuthorResolverInterface
     */
    private $authorResolver;

    public function __construct(AuthorResolverInterface $authorResolver)
    {
        $this->authorResolver = $authorResolver;
    }

    /**
     * @return AuthorResolverInterface
     */
    public function getAuthorResolver()
    {
        return $this->authorResolver;
    }

    /**
     * @param AuthorResolverInterface $authorResolver
     */
    public function setAuthorResolver($authorResolver)
    {
        $this->authorResolver = $authorResolver;
    }


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
        $trace->setContext(array_merge($trace->getContext(), $this->resolveContext()));
        $trace->setAuthor($this->authorResolver->resolve());

        return $trace;
    }

    /**
     * Resolves contexts using context resolvers (collectors).
     *
     * @return array
     * @throws ContextResolvingError
     */
    protected function resolveContext()
    {
        $context = [];

        foreach ($this->contextResolvers as $key => $collector) {
            try {
                $context[$key] = call_user_func($collector);
            } catch (Throwable $exception) {
                throw new ContextResolvingError('Cannot collect context for some trace context collector.', 0, $exception);
            }
        }

        return $context;
    }

    /**
     * Examines if the trace can be collected.
     * Can be used i.e. to limit collecting to some classes or methods.
     *
     * @param TraceInterface $trace
     * @return bool
     */
    private function canCollect($trace)
    {
        //TODO:1.1: impl whitelist and blacklist
        return true;
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
        if (!is_callable($collector)) {
            throw new InvalidArgumentException('Invalid context resolver. Tip: context resolver should be a callable (i.e. anonymous function).');
        }

        $this->contextResolvers[$name] = $collector;

        return $this;
    }
}
