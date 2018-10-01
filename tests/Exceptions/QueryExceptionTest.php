<?php

namespace Wearesho\GoogleAutocomplete\Tests\Exceptions;

use PHPUnit\Framework\TestCase;
use Wearesho\GoogleAutocomplete\Enums;
use Wearesho\GoogleAutocomplete\Exceptions\QueryException;
use Wearesho\GoogleAutocomplete\Queries\CitySearch;

/**
 * Class QueryExceptionTest
 * @package Wearesho\GoogleAutocomplete\Tests\Exceptions
 * @coversDefaultClass QueryException
 * @internal
 */
class QueryExceptionTest extends TestCase
{
    protected const TOKEN = 'token';
    protected const INPUT = 'testInput';
    protected const CITY = 'testCity';

    /** @var QueryException */
    protected $fakeQueryException;

    protected function setUp(): void
    {
        $this->fakeQueryException = new QueryException(
            Enums\SearchStatus::INVALID_REQUEST(),
            new CitySearch(
                static::TOKEN,
                static::INPUT,
                Enums\SearchLanguage::RU(),
                Enums\SearchMode::SHORT()
            )
        );
    }

    public function testGetStatus(): void
    {
        $this->assertEquals(
            Enums\SearchStatus::INVALID_REQUEST(),
            $this->fakeQueryException->getStatus()
        );
        $this->assertTrue(
            $this->fakeQueryException->getStatus()->equals(Enums\SearchStatus::INVALID_REQUEST())
        );
    }

    public function testGetQuery(): void
    {
        $this->assertEquals(
            new CitySearch(
                static::TOKEN,
                static::INPUT,
                Enums\SearchLanguage::RU(),
                Enums\SearchMode::SHORT()
            ),
            $this->fakeQueryException->getQuery()
        );
    }
}
