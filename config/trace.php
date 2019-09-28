<?php

return [

    // TODO:1.1: docs

    'enabled' => env('TRACE_ENABLED', true),

    'model' => 'Malbrandt\Laravel\Trace\TraceModel',

    'events' => [
        'TraceCollected' => env('TRACE_EVENT_COLLECTED', false),
        'TracePersisted' => env('TRACE_EVENT_PERSISTED', false),
    ],

    'persistence' => [

        /**
         * TraceModel persistence driver. Tells HOW the trace will be persisted.
         * TODO:1.2: LogTracePersister
         */
        'driver' => 'Malbrandt\Laravel\Trace\Persisters\EloquentTracePersister',

        /**
         * TraceModel persistence moment. Tells WHEN the trace will be persisted.
         * Available options:
         * - deferred - persists the trace before application shutdown,
         * - immediately - persists each trace immediately after Trace::collect() method call.
         */
        'moment' => env('TRACE_PERSISTENCE_MOMENT', 'deferred'),

    ],

    'collection' => [

        // TODO:1.1: config source classes that should be collected
        // TODO:1.2: ignore context resolving problems

    ],

];
