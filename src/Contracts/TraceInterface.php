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
    const TYPE_INFO = 'INFO';
    const TYPE_WARNING = 'WARNING';
    const TYPE_ERROR = 'ERROR';

    /**
     * Returns this trace item type. See interface constants.
     *
     * @return string
     */
    public function getType();

    /**
     * Sets this trace item type.
     *
     * @param string $type
     * @return self
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
     * @param array $context
     * @return self
     */
    public function setContext($context = []);

    /**
     * @return array
     */
    public function getContext();

    /**
     * @return string|null
     */
    public function getSource();

    /**
     * @param string|null $source
     * @return self
     */
    public function setSource($source);
}
