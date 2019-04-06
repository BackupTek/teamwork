<?php

namespace DigitalEquation\Teamwork\Exceptions;

use Exception;

class TeamworkTicketException extends Exception
{
    /**
     * TeamworkTicketException constructor.
     *
     * @param string $message
     * @param int    $code
     */
    public function __construct($message, $code = 400)
    {
        parent::__construct($message, $code);
    }
}
