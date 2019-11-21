<?php
/**
 * Created by PhpStorm.
 * User: philips
 * Date: 03.06.18
 * Time: 22:32
 */
class base_Exception extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        throw new parent($message, $code, $previous);
    }
}