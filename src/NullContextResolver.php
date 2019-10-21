<?php


namespace Malbrandt\Laravel\Trace;


use Malbrandt\Laravel\Trace\Contracts\ContextResolverInterface;

/**
 * TODO:1.1: docs
 * @package Malbrandt\Laravel\Trace
 */
class NullContextResolver implements ContextResolverInterface
{
    public function resolve($trace)
    {
        return null;
    }
}
