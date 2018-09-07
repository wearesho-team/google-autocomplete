<?php

namespace Wearesho\GoogleAutocomplete\Tests;

use PHPUnit\Framework\TestCase;

use Wearesho\GoogleAutocomplete\SearchQuery;
use Wearesho\GoogleAutocomplete\Enums;

/**
 * Class SearchQueryTest
 * @package Wearesho\GoogleAutocomplete\Tests
 * @coversDefaultClass SearchQuery
 * @internal
 */
class SearchQueryTest extends TestCase
{
    protected const INPUT = 'testInput';
    protected const CITY = 'testCity';

    /** @var SearchQuery */
    protected $fakeSearchQuery;

    protected function setUp(): void
    {
        $this->fakeSearchQuery = new SearchQuery(
            static::INPUT,
            Enums\AddressPart::CITY(),
            Enums\SearchLanguage::RU(),
            static::CITY
        );
    }

    public function testGetInput(): void
    {
        $this->assertEquals(
            static::INPUT,
            $this->fakeSearchQuery->getInput()
        );
    }

    public function testGetLanguage(): void
    {
        $this->assertEquals(
            Enums\SearchLanguage::RU(),
            $this->fakeSearchQuery->getLanguage()
        );
        $this->assertTrue(
            $this->fakeSearchQuery->getLanguage()->equals(Enums\SearchLanguage::RU())
        );
    }

    public function testGetType(): void
    {
        $this->assertEquals(
            Enums\AddressPart::CITY(),
            $this->fakeSearchQuery->getType()
        );
        $this->assertTrue(
            $this->fakeSearchQuery->getType()->equals(Enums\AddressPart::CITY())
        );
    }

    public function testGetCity(): void
    {
        $this->assertEquals(
            static::CITY,
            $this->fakeSearchQuery->getCity()
        );
    }
}
