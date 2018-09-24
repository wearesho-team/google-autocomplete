<?php

namespace Wearesho\GoogleAutocomplete\Tests;

use GuzzleHttp;

use PHPUnit\Framework\TestCase;

use Wearesho\GoogleAutocomplete\Config;
use Wearesho\GoogleAutocomplete\Enums;
use Wearesho\GoogleAutocomplete\Location;
use Wearesho\GoogleAutocomplete\SearchQuery;
use Wearesho\GoogleAutocomplete\Service;

/**
 * Class ServiceTest
 * @package Wearesho\GoogleAutocomplete\Tests
 * @coversDefaultClass \Wearesho\GoogleAutocomplete\Service
 * @internal
 */
class ServiceTest extends TestCase
{
    protected const SESSION_TOKEN = 'session_token';
    protected const URL = 'https://google.com';
    protected const KEY = 'testKey';
    protected const INPUT = 'testInput';
    protected const DESCRIPTION = 'testDescription';
    protected const MAIN_TEXT = 'testMainText';

    /** @var GuzzleHttp\Handler\MockHandler */
    protected $mock;

    /** @var GuzzleHttp\Client */
    protected $client;

    /** @var array */
    protected $container = [];

    /** @var Service */
    protected $fakeService;

    protected function setUp(): void
    {
        $this->mock = new GuzzleHttp\Handler\MockHandler();
        $history = GuzzleHttp\Middleware::history($this->container);
        $stack = new GuzzleHttp\HandlerStack($this->mock);
        $stack->push($history);
        $this->client = new GuzzleHttp\Client(['handler' => $stack,]);
        $this->fakeService = new Service(new Config(static::KEY, static::URL), $this->client);
    }

    /**
     * @expectedException \Wearesho\GoogleAutocomplete\Exceptions\InvalidResponse
     * @expectedExceptionMessage Response contain invalid data: invalid json
     */
    public function testInvalidResponse(): void
    {
        $this->mock->append(
            new GuzzleHttp\Psr7\Response(200, [], 'invalid json')
        );

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->fakeService->load($this->getCitySearchQuery());
    }

    public function testZeroResults(): void
    {
        $this->mock->append(
            new GuzzleHttp\Psr7\Response(200, [], json_encode([
                'status' => Enums\SearchStatus::ZERO_RESULTS()->getValue(),
            ]))
        );

        /** @noinspection PhpUnhandledExceptionInspection */
        $data = $this->fakeService->load($this->getCitySearchQuery());

        $this->assertEmpty($data);
    }

    /**
     * @expectedException  \Wearesho\GoogleAutocomplete\Exceptions\QueryException
     * @expectedExceptionMessage Search failed with status [OVER_QUERY_LIMIT]
     */
    public function testRequestException(): void
    {
        $this->mock->append(
            new GuzzleHttp\Psr7\Response(200, [], json_encode([
                'status' => Enums\SearchStatus::OVER_QUERY_LIMIT()->getValue(),
            ]))
        );

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->fakeService->load($this->getCitySearchQuery());
    }

    public function testEmptyPredictions(): void
    {
        $body = json_encode([
            'status' => Enums\SearchStatus::OK()->getValue(),
        ]);

        $this->mock->append(
            new GuzzleHttp\Psr7\Response(200, [], $body)
        );

        /** @noinspection PhpUnhandledExceptionInspection */
        $data = $this->fakeService->load($this->getCitySearchQuery());

        $this->assertEmpty($data);
    }

    public function testCities(): void
    {
        $body = json_encode([
            'status' => Enums\SearchStatus::OK()->getValue(),
            'predictions' => [
                [
                    'description' => static::DESCRIPTION,
                    'structured_formatting' => [
                        'main_text' => static::MAIN_TEXT,
                    ],
                ],
                [],
            ],
        ]);

        $this->mock->append(
            new GuzzleHttp\Psr7\Response(200, [], $body)
        );

        /** @noinspection PhpUnhandledExceptionInspection */
        $data = $this->fakeService->load($this->getCitySearchQuery());
        $this->assertNotEmpty($data);
        $this->assertCount(1, $data);
        $this->assertEquals(static::MAIN_TEXT, $data->offsetGet(0)->getValue());
        $this->assertArraySubset(
            [static::MAIN_TEXT,],
            $data->jsonSerialize()
        );
    }

    public function testStreets(): void
    {
        $this->mock->append(
            new GuzzleHttp\Psr7\Response(200, [], json_encode([
                'status' => Enums\SearchStatus::OK()->getValue(),
                'predictions' => [
                    [
                        'description' => static::DESCRIPTION,
                        'structured_formatting' => [
                            'main_text' => static::MAIN_TEXT,
                        ],
                    ],
                    [
                        'structured_formatting' => [],
                    ],
                    [],
                ],
            ]))
        );

        /** @noinspection PhpUnhandledExceptionInspection */
        $data = $this->fakeService->load($this->getStreetSearchQuery());
        $this->assertNotEmpty($data);
        $this->assertCount(1, $data);
        $this->assertEquals(static::DESCRIPTION, $data->offsetGet(0)->getValue());
        $this->assertArraySubset(
            [static::DESCRIPTION,],
            $data->jsonSerialize()
        );
    }

    public function testStreetsInCity(): void
    {
        $this->mock->append(
            new GuzzleHttp\Psr7\Response(200, [], file_get_contents('Mocks/cities.json', true)),
            new GuzzleHttp\Psr7\Response(200, [], file_get_contents('Mocks/streets.json', true)),
            new GuzzleHttp\Psr7\Response(200, [], file_get_contents('Mocks/streets.json', true))
        );

        /** @noinspection PhpUnhandledExceptionInspection */
        $cities = $this->fakeService->load($this->getCitySearchQuery('Харьков'));

        $this->assertArraySubset(
            [
                'Харьков',
                'Харьково',
                'Харьковский горсовет',
                'Харьковцы',
                'Харьковка',
            ],
            $cities->jsonSerialize()
        );

        $street = 'Сумская';
        /** @noinspection PhpUnhandledExceptionInspection */
        $streets = $this->fakeService->load($this->getStreetSearchQuery($street));

        $this->assertArraySubset(
            [
                'Сумская улица, Кременчуг, Полтавская область, Украина',
                'улица Сумская, Харьков, Харьковская область, Украина',
                'Сумская улица, Мерефа, Харьковская область, Украина',
                'Сумская улица, Черновцы, Черновицкая область, Украина',
            ],
            $streets->jsonSerialize()
        );

        /** @var Location $city */
        $city = $cities->offsetGet(0);

        /** @noinspection PhpUnhandledExceptionInspection */
        $streets = $this->fakeService->load($this->getStreetSearchQuery($street, $city->getValue()));

        $this->assertArraySubset(
            ['улица Сумская, Харьков, Харьковская область, Украина',],
            $streets->jsonSerialize()
        );
    }

    protected function getCitySearchQuery(string $input = self::INPUT): SearchQuery
    {
        return $this->generateSearchQuery($input, Enums\AddressPart::CITY());
    }

    protected function getStreetSearchQuery(string $input = self::INPUT, string $city = null): SearchQuery
    {
        return $this->generateSearchQuery($input, Enums\AddressPart::STREET(), $city);
    }

    protected function generateSearchQuery(string $input, Enums\AddressPart $addressPart, string $city = null)
    {
        return new SearchQuery($this->getTestSessionToken(), $input, $addressPart, Enums\SearchLanguage::RU(), $city);
    }

    protected function getTestSessionToken(): string
    {
        return base64_encode(static::SESSION_TOKEN);
    }
}
