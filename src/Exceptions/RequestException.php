<?php

namespace Wearesho\GoogleAutocomplete\Exceptions;

use Throwable;

/**
 * Class RequestException
 * @package Wearesho\GoogleAutocomplete\Exceptions
 */
class RequestException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
