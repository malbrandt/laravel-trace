<?php

namespace Malbrandt\Laravel\Trace\Persisters;

use Illuminate\Database\Eloquent\Model;
use Malbrandt\Laravel\Trace\Contracts\TraceInterface;
use Malbrandt\Laravel\Trace\Contracts\TracePersisterInterface;
use Malbrandt\Laravel\Trace\Errors\TracePersistenceError;

/**
 * TODO:1.1: docs
 *
 * Class EloquentTracePersister
 * @package Malbrandt\Laravel\TraceModel\Facade
 */
class EloquentTracePersister implements TracePersisterInterface
{
    /**
     * @param TraceInterface[] $trace
     * @param array $options
     * @return mixed
     * @throws TracePersistenceError
     */
    public function persist($trace, $options = [])
    {
        $numPersisted = 0;

        foreach ($trace as $item) {
            if (!is_subclass_of($item, Model::class, false)) {
                throw new TracePersistenceError(
                    'Cannot persist one of trace items because it is not a Laravel Model.' .
                    ' Tip: write your own persistence driver.'
                );
            }

            /** @var Model $item */
            $item->save();
            ++$numPersisted;
        }

        return $numPersisted;
    }
}
