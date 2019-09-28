<?php


namespace Malbrandt\Laravel\Trace\Facade;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Facade;
use Malbrandt\Laravel\Trace\Contracts\TraceCollectorInterface;
use Malbrandt\Laravel\Trace\Contracts\TraceInterface;
use Malbrandt\Laravel\Trace\Contracts\TracePersisterInterface;

/**
 * TODO:1.1: docs
 *
 * @method static void enable()
 * @method static void disable()
 * @method static TraceCollectorInterface getCollector()
 * @method static mixed setCollector(TraceCollectorInterface $collector)
 * @method static TracePersisterInterface getPersister()
 * @method static mixed setPersister(TracePersisterInterface $persister)
 * @method static TraceInterface collect(TraceInterface $trace)
 * @method static mixed persist(array $options = [])
 * @method static TraceInterface prepare(string $message, int $type = TraceInterface::TYPE_INFO, array $context = [], $source = null)
 * @method static TraceInterface info(string $message, array $context = [], Model|null $parent = null)
 * @method static TraceInterface warning(string $message, array $context = [], Model|null $parent = null)
 * @method static TraceInterface error(string $message, array $context = [], Model|null $parent = null)
 * @method static TraceInterface decision(string $message, array $context = [], Model|null $parent = null)
 *
 * @package Malbrandt\Laravel\TraceModel\Facade
 * @author Marek Malbrandt <marek.malbrandt@gmail.com>
 */
class Trace extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'trace';
    }
}
