<?php

namespace Malbrandt\Laravel\Trace\Contracts;

/**
 * TODO:1.1: docs
 * Interface TracePersisterInterface
 * @package Malbrandt\Laravel\TraceModel\Contracts
 */
interface TracePersisterInterface
{
    /**
     * @param TraceInterface[] $trace
     * @param array $options
     * @return mixed
     */
    public function persist($trace, $options = []);
}
