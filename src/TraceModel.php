<?php


namespace Malbrandt\Laravel\Trace;


use Illuminate\Database\Eloquent\Model;
use Malbrandt\Laravel\Trace\Contracts\TraceInterface;

/**
 * TODO:1.1: config model table
 * TODO:1.1: fields PHPDoc
 * TODO:1.2: morph relations (parent, author)
 *
 * @package Malbrandt\Laravel\TraceModel
 */
class TraceModel extends Model implements TraceInterface
{
    protected $table = 'trace';

    protected $fillable = [
        'type',
        'message',
        'context',
    ];

    protected $casts = [
        'context' => 'array',
    ];

    /**
     * {@inheritDoc}
     */
    public function getType()
    {
        return $this->attributes['type'];
    }

    /**
     * {@inheritDoc}
     */
    public function setType($type)
    {
        $this->attributes['type'] = $type;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getMessage()
    {
        return $this->attributes['message'];
    }

    /**
     * {@inheritDoc}
     */
    public function setMessage($message)
    {
        $this->attributes['message'] = trim($message);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setContext($context = [])
    {
        $this->context = $context;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @return string|null
     */
    public function getSource()
    {
        return $this->attributes['source'];
    }

    /**
     * @param string|null $source
     * @return self
     */
    public function setSource($source)
    {
        $this->attributes['source'] = substr($source, 0, 254);
        return $this;
    }
}
