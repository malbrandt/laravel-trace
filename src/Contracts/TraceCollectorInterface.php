<?php

namespace Malbrandt\Laravel\Trace\Contracts;

/**
 * TODO:1.1: docs
 *
 * Interface TraceCollectorInterface
 * @package Malbrandt\Laravel\TraceModel\Contracts
 */
interface TraceCollectorInterface
{
    /**
     * Returns collected trace.
     *
     * @return TraceInterface[]
     */
    public function getCollectedTrace();

    /**
     * Collects some trace to the pool.
     *
     * @param TraceInterface $trace The trace item to collect.
     * @return TraceInterface|null Collected trace item (if trace was collected; null otherwise).
     */
    public function collect($trace);

    /**
     * Clears the current trace pool.
     *
     * @return int Number of removed items.
     */
    public function clear();


    /**
     * Registers collector (context resolver) that would be used before collecting trace to add some details to it.
     * I.e. u can add "user" context resolver that would add current user.id to trace details.
     *
     * @param string $name
     * @param callable $collector
     * @return mixed
     */
    public function addContextResolver($name, $collector);

    /**
     * @return AuthorResolverInterface
     */
    public function getAuthorResolver();

    /**
     * @param AuthorResolverInterface $resolver
     * @return mixed
     */
    public function setAuthorResolver($resolver);
}
