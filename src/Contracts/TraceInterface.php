<?php

namespace Malbrandt\Laravel\Trace\Contracts;

/**
 * TODO:1.1: docs
 *
 * Interface TraceInterface
 * @package Malbrandt\Laravel\TraceModel\Contracts
 */
interface TraceInterface
{
    const TYPE_INFO = 0x01;
    const TYPE_DECISION = 0x02;
    const TYPE_WARNING = 0x04;
    const TYPE_ERROR = 0x08;

    /**
     * Returns this trace item type. See interface constants.
     *
     * @return int
     */
    public function getType();

    /**
     * Sets this trace item type. See interface constants.
     *
     * @param int $type
     * @return mixed
     */
    public function setType($type);

    /**
     * @return string
     */
    public function getMessage();

    /**
     * @param string $message
     * @return self
     */
    public function setMessage($message);

    /**
     * @return string
     */
    public function getSource();

    /**
     * @param string $source
     */
    public function setSource($source);

    /**
     * @return mixed|null
     */
    public function getParent();

    /**
     * @param mixed $parent
     * @return self
     */
    public function setParent($parent);

    /**
     * @return mixed|null
     */
    public function getAuthor();

    /**
     * @param mixed $author
     * @return self
     */
    public function setAuthor($author);

    /**
     * @param array $context
     * @return self
     */
    public function setContext($context = []);

    /**
     * @return array
     */
    public function getContext();
}
