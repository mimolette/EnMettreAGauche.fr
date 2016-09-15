<?php

namespace CoreBundle\Exception;

/**
 * EmagException class file
 *
 * PHP Version 5.6
 *
 * @category Exception
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */

/**
 * EmagException class
 *
 * @category Exception
 * @author   Guillaume ORAIN <guillaume.orain27@laposte.net>
 */
class EmagException extends \Exception
{
    /** @var  string */
    protected $method;

    /**
     * @param string     $message
     * @param int        $code
     * @param string     $method
     * @param \Exception $previous
     */
    public function __construct($message, $code, $method, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->setMethod($method);
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }
}