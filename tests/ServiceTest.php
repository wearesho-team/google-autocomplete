<?php

namespace Wearesho\GoogleAutocomplete\Tests;

use GuzzleHttp;

use PHPUnit\Framework\TestCase;

use Wearesho\GoogleAutocomplete\Config;
use Wearesho\GoogleAutocomplete\Enums;
use Wearesho\GoogleAutocomplete\Location;
use Wearesho\GoogleAutocomplete\Queries;
use Wearesho\GoogleAutocomplete\Service;

/**
 * Class ServiceTest
 * @package Wearesho\GoogleAutocomplete\Tests
 * @coversDefaultClass \Wearesho\GoogleAutocomplete\Service
 * @internal
 */
class ServiceTest extends TestCase
{
    protected const TOKEN = 'session_token';
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
     * @expectedException  \Wearesho\GoogleAutocomplete\Exceptions\QueryException
     * @expectedExceptionMessage Search failed with status [QUERY_NOT_SET]
     */
    public function testLoadWithEmptyQuery(): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->fakeService->load();
    }

    /**
     * @expectedException \Wearesho\GoogleAutocomplete\Exceptions\InvalidQueryType
     * @expectedExceptionMessage Invalid query type
     */
    public function testInvalidQueryType(): void
    {
        $query = new class(
            'token',
            'input',
            Enums\SearchLanguage::RU()
        ) extends Queries\SearchQuery
        {
        };

        $this->fakeService->setParameters($query)->load();
    }

    /**
     * @expectedException  \Wearesho\GoogleAutocomplete\Exceptions\InvalidResponse
     * @expectedExceptionMessage Response contain invalid data: invalid json
     */
    public function testInvalidResponse(): void
    {
        $this->mock->append(
            new GuzzleHttp\Psr7\Response(200, [], 'invalid json')
        );

        $this->fakeService
            ->setParameters($this->getCitySearchQuery())
            ->load();
    }

    public function testZeroResults(): void
    {
        $this->mock->append(
            new GuzzleHttp\Psr7\Response(200, [], json_encode([
                'status' => Enums\SearchStatus::ZERO_RESULTS,
            ]))
        );

        $suggestions = $this->fakeService
            ->setParameters($this->getCitySearchQuery())
            ->load()
            ->getResults();

        $this->assertEmpty($suggestions);
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

        $this->fakeService
            ->setParameters($this->getCitySearchQuery())
            ->load();
    }

    public function testEmptyPredictions(): void
    {
        $body = json_encode([
            'status' => Enums\SearchStatus::OK()->getValue(),
        ]);

        $this->mock->append(
            new GuzzleHttp\Psr7\Response(200, [], $body)
        );

        $suggestions = $this->fakeService
            ->setParameters($this->getCitySearchQuery())
            ->load()
            ->getResults();

        $this->assertEmpty($suggestions);
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

        $suggestions = $this->fakeService
            ->setParameters($this->getCitySearchQuery())
            ->load()
            ->getResults();
        $this->assertNotEmpty($suggestions);
        $this->assertCount(1, $suggestions);
        $this->assertEquals(static::MAIN_TEXT, $suggestions->offsetGet(0)->getValue());
        $this->assertArraySubset(
            [static::MAIN_TEXT,],
            $suggestions->jsonSerialize()
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

        $suggestions = $this->fakeService
            ->setParameters($this->getStreetSearchQuery())
            ->load()
            ->getResults();
        $this->assertNotEmpty($suggestions);
        $this->assertCount(1, $suggestions);
        $this->assertEquals(static::MAIN_TEXT, $suggestions->offsetGet(0)->getValue());
        $this->assertArraySubset(
            [static::MAIN_TEXT,],
            $suggestions->jsonSerialize()
        );
    }

    public function testStreetsInCity(): void
    {
        $this->mock->append(
            $this->mockResponseFromMockFile('Mocks/cities.json'),
            $this->mockResponseFromMockFile('Mocks/streets.json'),
            $this->mockResponseFromMockFile('Mocks/streets.json')
        );

        $cities = $this->fakeService
            ->setParameters($this->getCitySearchQuery('Харьков'))
            ->load()
            ->getResults();

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

        $streets = $this->fakeService
            ->setParameters($this->getStreetSearchQuery('Сумская'))
            ->load()
            ->getResults();

        $this->assertArraySubset(
            ['Сумская улица',],
            $streets->jsonSerialize()
        );

        /** @var Location $city */
        $city = $cities->offsetGet(0);

        $streets = $this->fakeService
            ->setParameters($this->getStreetSearchQuery('Сумская', $city->getValue()))
            ->load()
            ->getResults();

        $this->assertArraySubset(
            ['улица Сумская',],
            $streets->jsonSerialize()
        );
    }

    protected function mockResponseFromMockFile(string $path = null, array $headers = []): GuzzleHttp\Psr7\Response
    {
        return new GuzzleHttp\Psr7\Response(
            200,
            $headers,
            file_get_contents($path, true)
        );
    }

    protected function getTestSessionToken(): string
    {
        return base64_encode(static::TOKEN);
    }

    protected function getCitySearchQuery(string $input = self::INPUT): Queries\CitySearch
    {
        return new Queries\CitySearch(
            $this->getTestSessionToken(),
            $input,
            Enums\SearchLanguage::RU(),
            Enums\SearchMode::SHORT()
        );
    }

    protected function getStreetSearchQuery(string $input = self::INPUT, string $city = null): Queries\StreetSearch
    {
        return new Queries\StreetSearch(
            $this->getTestSessionToken(),
            $input,
            Enums\SearchLanguage::RU(),
            $city,
            null,
            Enums\SearchMode::SHORT()
        );
    }
}
