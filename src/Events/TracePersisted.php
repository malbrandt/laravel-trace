<?php

namespace Malbrandt\Laravel\Trace\Events;

/**
 * TODO:1.1: docs
 * @package Malbrandt\Laravel\Trace\Events
 */
class TracePersisted
{
    /**
     * The number of persisted trace items.
     *
     * @var int
     */
    public $numPersisted;

    public function __construct($numPersisted)
    {
        $this->numPersisted = $numPersisted;
    }
}
