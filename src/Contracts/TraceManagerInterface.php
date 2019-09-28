<?php

namespace Malbrandt\Laravel\Trace\Contracts;

use Illuminate\Database\Eloquent\Model;

/**
 * TODO:1.1: docs
 *
 * Interface TraceManagerInterface
 * @package Malbrandt\Laravel\TraceModel\Contracts
 */
interface TraceManagerInterface
{
    /**
     * @return TracePersisterInterface
     */
    public function getPersister();

    /**
     * @param TracePersisterInterface $persister
     * @return mixed
     */
    public function setPersister($persister);

    /**
     * @return TraceCollectorInterface
     */
    public function getCollector();

    /**
     * @param TraceCollectorInterface $collector
     */
    public function setCollector($collector);

    /**
     * @return TraceFactoryInterface
     */
    public function getFactory();

    /**
     * @param $factory TraceFactoryInterface
     */
    public function setFactory($factory);

    /**
     * Collects trace item and appends some details to it.
     *
     * @param $trace TraceInterface TraceModel item to collect.
     * @return TraceInterface|null Collected trace item (if trace was collected; null otherwise).
     */
    public function collect($trace);

    /**
     * @return int Number of persisted trace items.
     */
    public function persist();

    /**
     * @param string $message
     * @param int $type
     * @return TraceInterface|Model
     */
    public function prepare($message, $type = TraceInterface::TYPE_INFO);

    /**
     * Turns on trace collecting and persisting.
     *
     * @return void
     */
    public function enable();

    /**
     * Turns off trace collecting and persisting.
     *
     * @return void
     */
    public function disable();
}
