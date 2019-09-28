<?php


namespace Malbrandt\Laravel\Trace\Contracts;


use Illuminate\Database\Eloquent\Model;

/**
 * TODO:1.1: DOCS
 *
 * Interface AuthorResolverInterface
 * @package Malbrandt\Laravel\Trace\Contracts
 */
interface AuthorResolverInterface
{
    /**
     * @return Model|null
     */
    public function resolve();
}
