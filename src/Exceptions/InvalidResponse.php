<?php

namespace Wearesho\GoogleAutocomplete\Exceptions;

use Psr\Http\Message\ResponseInterface;

/**
 * Class InvalidResponse
 * @package Wearesho\GoogleAutocomplete\Exceptions
 */
class InvalidResponse extends \Exception
{
    /** @var ResponseInterface */
    protected $response;

    public function __construct(ResponseInterface $response, int $code = 0, \Throwable $previous = null)
    {
        $this->response = $response;

        parent::__construct("Response contain invalid data: {$response->getBody()->__toString()}", $code, $previous);
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
