<?php

namespace Wearesho\GoogleAutocomplete\Exceptions;

/**
 * Class InvalidResponse
 * @package Wearesho\GoogleAutocomplete\Exceptions
 */
class InvalidResponse extends \Exception
{
    /** @var string */
    protected $response;

    public function __construct(string $response, int $code = 0, \Throwable $previous = null)
    {
        parent::__construct("Invalid response: {$response}", $code, $previous);
        $this->response = $response;
    }

    public function getResponse(): string
    {
        return $this->response;
    }
}
