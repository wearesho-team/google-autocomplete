<?php

namespace Wearesho\GoogleAutocomplete\Tests\Exceptions;

use PHPUnit\Framework\TestCase;
use Wearesho\GoogleAutocomplete\Exceptions\InvalidResponse;

/**
 * Class InvalidResponseTest
 * @package Wearesho\GoogleAutocomplete\Tests\Exceptions
 */
class InvalidResponseTest extends TestCase
{
    public function testGetResponse(): void
    {
        $exception = new InvalidResponse('test response');
        $this->assertEquals('test response', $exception->getResponse());
        $this->assertEquals('Invalid response: test response', $exception->getMessage());
    }
}
