<?php


namespace Malbrandt\Laravel\Trace\Contracts;


use Illuminate\Database\Eloquent\Model;

/**
 * Default trace items factory.
 *
 * Interface TraceFactoryInterface
 * @package Malbrandt\Laravel\TraceModel\Contracts
 */
interface TraceFactoryInterface
{
    /**
     * Makes trace item instance.
     *
     * @param string $message The trace message.
     * @param int $type Trace type (see TraceInterface for predefined types).
     * @param array $context Additional trace context.
     * @param string|null $source Source of the trace (i.e. UserController@update).
     *
     * @return TraceInterface|Model Created trace item (not persisted).
     *
     * @see TraceInterface::TYPE_WARNING
     * @see TraceInterface::TYPE_ERROR
     * @see TraceInterface::TYPE_INFO
     */
    public function make($message, $type, $context = [], $source = null);
}
