<?php


namespace Malbrandt\Laravel\Trace\Contracts;


interface ContextResolverInterface
{
    /**
     * Resolves and returns some context (or modifies directly Trace object to fill it).
     *
     * @param TraceInterface $trace
     * @return mixed|null
     */
    public function resolve($trace);
}
