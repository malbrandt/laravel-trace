<?php


namespace Malbrandt\Laravel\Trace;


use Malbrandt\Laravel\Trace\Contracts\TraceFactoryInterface;
use Malbrandt\Laravel\Trace\Contracts\TraceInterface;
use Malbrandt\Laravel\Trace\Errors\InvalidContractImplementation;

/**
 * {@inheritDoc}
 */
class TraceFactory implements TraceFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function make($message, $type, $context = [], $source = null)
    {
        /** @var TraceInterface $trace */
        $trace = app()->makeWith(config('trace.model'));

        if (!is_subclass_of($trace, TraceInterface::class, false)) {
            throw new InvalidContractImplementation(TraceInterface::class, get_class($trace));
        }

        $trace
            ->setMessage($message)
            ->setType($type)
            ->setContext($context)
            ->setSource($source);

        return $trace;
    }
}
