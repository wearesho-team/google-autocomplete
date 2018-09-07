<?php

namespace Wearesho\GoogleAutocomplete\Tests\Exceptions;

use GuzzleHttp\Psr7\Response;

use PHPUnit\Framework\TestCase;

use Wearesho\GoogleAutocomplete\Exceptions\InvalidResponse;

/**
 * Class InvalidResponseTest
 * @package Wearesho\GoogleAutocomplete\Tests\Exceptions
 * @coversDefaultClass InvalidResponse
 * @internal
 */
class InvalidResponseTest extends TestCase
{
    /** @var InvalidResponse */
    protected $fakeInvalidResponse;

    /** @var Response */
    protected $fakeResponse;

    protected function setUp(): void
    {
        $this->fakeResponse = new Response();
        $this->fakeInvalidResponse = new InvalidResponse($this->fakeResponse);
    }

    public function testGetResponse(): void
    {
        $this->assertEquals(
            $this->fakeResponse,
            $this->fakeInvalidResponse->getResponse()
        );
    }
    
    public function testGetMessage(): void
    {
        $this->assertEquals(
            'Response contain invalid data: ',
            $this->fakeInvalidResponse->getMessage()
        );
    }
}
