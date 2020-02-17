<?php


namespace Malbrandt\Git\Drivers\Error;


use Throwable;

class DriverDoesNotExistException extends \Exception
{
    public function __construct(
        $name,
        $code = 0,
        Throwable $previous = null
    ) {
        $message = "Git driver [$name] does not exists." .
            " Tip: implement driver for service [$name] and register it using" .
            " GitDriver::registerDriver() method.";

        parent::__construct(
            $message,
            $code,
            $previous
        );
    }

}