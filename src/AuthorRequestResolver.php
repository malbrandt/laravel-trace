<?php


namespace Malbrandt\Laravel\Trace;


use Illuminate\Database\Eloquent\Model;
use Malbrandt\Laravel\Trace\Contracts\AuthorResolverInterface;

/**
 * TODO:1.1: docs
 * @package Malbrandt\Laravel\Trace
 */
class AuthorRequestResolver implements AuthorResolverInterface
{
    /**
     * @return Model|null
     */
    public function resolve()
    {
        return request()->user();
    }
}
