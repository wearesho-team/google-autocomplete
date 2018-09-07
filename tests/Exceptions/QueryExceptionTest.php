<?php

namespace Wearesho\GoogleAutocomplete\Tests\Exceptions;

use PHPUnit\Framework\TestCase;

use Wearesho\GoogleAutocomplete\Enums;
use Wearesho\GoogleAutocomplete\Exceptions\QueryException;
use Wearesho\GoogleAutocomplete\SearchQuery;

/**
 * Class QueryExceptionTest
 * @package Wearesho\GoogleAutocomplete\Tests\Exceptions
 * @coversDefaultClass QueryException
 * @internal
 */
class QueryExceptionTest extends TestCase
{
    protected const INPUT = 'testInput';
    protected const CITY = 'testCity';

    /** @var QueryException */
    protected $fakeQueryException;

    protected function setUp(): void
    {
        $this->fakeQueryException = new QueryException(
            new SearchQuery(
                static::INPUT,
                Enums\AddressPart::CITY(),
                Enums\SearchLanguage::RU(),
                static::CITY
            ),
            Enums\SearchStatus::INVALID_REQUEST()
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
            new SearchQuery(
                static::INPUT,
                Enums\AddressPart::CITY(),
                Enums\SearchLanguage::RU(),
                static::CITY
            ),
            $this->fakeQueryException->getQuery()
        );
    }
}
