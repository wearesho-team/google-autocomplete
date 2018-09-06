<?php

namespace Wearesho\GoogleAutocomplete\Tests;

use GuzzleHttp;
use PHPUnit\Framework\TestCase;
use Wearesho\GoogleAutocomplete\Config;
use Wearesho\GoogleAutocomplete\SearchData;
use Wearesho\GoogleAutocomplete\Service;

/**
 * Class ServiceTest
 * @package Wearesho\GoogleAutocomplete\Tests
 */
class ServiceTest extends TestCase
{
    /** @var GuzzleHttp\Handler\MockHandler */
    protected $mock;

    /** @var GuzzleHttp\Client */
    protected $client;

    /** @var array */
    protected $container;

    /** @var Service */
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mock = new GuzzleHttp\Handler\MockHandler();
        $this->container = [];
        $history = GuzzleHttp\Middleware::history($this->container);
        $stack = new GuzzleHttp\HandlerStack($this->mock);
        $stack->push($history);
        $this->client = new GuzzleHttp\Client(['handler' => $stack,]);
        $this->service = new Service(new Config('https://google.com', 'test'), $this->client);
    }

    /**
     * @expectedException  \Wearesho\GoogleAutocomplete\Exceptions\InvalidResponse
     * @expectedExceptionMessage Invalid response: invalid json
     */
    public function testInvalidResponse(): void
    {
        $this->mock->append(
            new GuzzleHttp\Psr7\Response(200, [], 'invalid json')
        );

        $this->service->load(new SearchData('test', 'city', 'uk'));
    }

    public function testZeroResults(): void
    {
        $this->mock->append(
            new GuzzleHttp\Psr7\Response(200, [], json_encode([
                'status' => 'ZERO_RESULTS',
            ]))
        );

        $data = $this->service->load(new SearchData('test', 'city', 'uk'));
        $this->assertEquals([], $data);
    }

    /**
     * @expectedException  \Wearesho\GoogleAutocomplete\Exceptions\RequestException
     * @expectedExceptionMessage Request failed with status OVER_QUERY_LIMIT: {"status":"OVER_QUERY_LIMIT"}
     */
    public function testRequestException(): void
    {
        $this->mock->append(
            new GuzzleHttp\Psr7\Response(200, [], json_encode([
                'status' => 'OVER_QUERY_LIMIT',
            ]))
        );

        $this->service->load(new SearchData('test', 'city', 'uk'));
    }

    public function testEmptyPredictions(): void
    {
        $body = json_encode([
            'status' => 'OK',
        ]);

        $this->mock->append(
            new GuzzleHttp\Psr7\Response(200, [], $body)
        );

        $data = $this->service->load(new SearchData('test', 'city', 'uk'));
        $this->assertEquals([], $data);
    }

    public function testCities(): void
    {
        $body = json_encode([
            'status' => 'OK',
            'predictions' => [
                [
                    'description' => 'Test',
                ],
                [],
            ],
        ]);

        $this->mock->append(
            new GuzzleHttp\Psr7\Response(200, [], $body)
        );

        $data = $this->service->load(new SearchData('test', 'city', 'uk'));
        $this->assertEquals(1, count($data));
        $this->assertEquals('Test', $data[0]);
    }

    public function testStreets(): void
    {
        $this->mock->append(
            new GuzzleHttp\Psr7\Response(200, [], json_encode([
                'status' => 'OK',
                'predictions' => [
                    [
                        'structured_formatting' => [
                            'main_text' => 'Test',
                        ],
                    ],
                    [
                        'structured_formatting' => [],
                    ],
                    [],
                ],
            ]))
        );

        $data = $this->service->load(new SearchData('test', 'street', 'uk'));
        $this->assertEquals(1, count($data));
        $this->assertEquals('Test', $data[0]);
    }
}
