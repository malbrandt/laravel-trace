<?php

namespace Malbrandt\Laravel\Trace\Events;

use Malbrandt\Laravel\Trace\Contracts\TraceInterface;

/**
 * TODO:1.1: docs
 *
 * Class TraceCollected
 * @package Malbrandt\Laravel\Trace\Events
 */
class TraceCollected
{
    /**
     * Collected trace item.
     *
     * Can be used i.e. to create real-time notifications for trace items (you can use Pusher or other real-time database).
     *
     * @var TraceInterface
     */
    public $trace;

    public function __construct($trace)
    {
        $this->trace = $trace;
    }
}
