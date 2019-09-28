<?php


namespace Malbrandt\Laravel\Trace;


use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
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
        'message',
        'type',
        'context',
        'source',
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
     * {@inheritDoc}
     */
    public function getParent()
    {
        if (isset($this->attributes['parent_id'], $this->attributes['parent_type'])) {
            return $this->attributes['parent_type']::find($this->attributes['parent_id']);
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function setParent($parent)
    {
        if ($parent === null) {
            $this->attributes['parent_id'] = null;
            $this->attributes['parent_type'] = null;

            return $this;
        }

        if (!is_a($parent, Model::class)) {
            throw new InvalidArgumentException('Invalid parent given. Tip: parent should be an Eloquent Model.');
        }

        $this->attributes['parent_id'] = $parent->getKey();
        $this->attributes['parent_type'] = get_class($parent);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getAuthor()
    {
        if (isset($this->attributes['author_id'], $this->attributes['author_type'])) {
            return $this->attributes['author_type']::find($this->attributes['author_id']);
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function setAuthor($author)
    {
        if ($author === null) {
            $this->attributes['author_id'] = null;
            $this->attributes['author_type'] = null;

            return $this;
        }

        if (!is_a($author, Model::class)) {
            throw new InvalidArgumentException('Invalid author given. Tip: author should be an Eloquent Model.');
        }

        $this->attributes['author_id'] = $author->getKey();
        $this->attributes['author_type'] = get_class($author);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getSource()
    {
        return $this->attributes['source'];
    }

    /**
     * {@inheritDoc}
     */
    public function setSource($source)
    {
        $this->attributes['source'] = $source;
    }
}
