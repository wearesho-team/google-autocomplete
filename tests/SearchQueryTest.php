<?php

namespace Wearesho\GoogleAutocomplete\Tests;

use PHPUnit\Framework\TestCase;

use Wearesho\GoogleAutocomplete\Enums;
use Wearesho\GoogleAutocomplete\Queries\CitySearch;
use Wearesho\GoogleAutocomplete\Queries\Interfaces\SearchQueryInterface;
use Wearesho\GoogleAutocomplete\Queries\StreetSearch;

/**
 * Class SearchQueryTest
 * @package Wearesho\GoogleAutocomplete\Tests
 * @coversDefaultClass \Wearesho\GoogleAutocomplete\Queries\SearchQuery
 * @internal
 */
class SearchQueryTest extends TestCase
{
    protected const TOKEN = 'token';
    protected const INPUT = 'testInput';
    protected const CITY = 'testCity';
    protected const TYPE = 'testType';

    /** @var SearchQueryInterface */
    protected $fakeSearchQuery;

    public function testCitySearch(): void
    {
        $this->fakeSearchQuery = new CitySearch(
            static::TOKEN,
            static::INPUT,
            Enums\SearchLanguage::RU(),
            Enums\SearchMode::FULL()
        );

        $this->assertEquals(
            static::TOKEN,
            $this->fakeSearchQuery->getToken()
        );
        $this->assertEquals(
            static::INPUT,
            $this->fakeSearchQuery->getInput()
        );
        $this->assertEquals(
            Enums\SearchMode::FULL(),
            $this->fakeSearchQuery->getMode()
        );
        $this->assertEquals(
            Enums\SearchLanguage::RU(),
            $this->fakeSearchQuery->getLanguage()
        );
    }

    public function testStreetSearch(): void
    {
        $this->fakeSearchQuery = new StreetSearch(
            static::TOKEN,
            static::INPUT,
            Enums\SearchLanguage::UK(),
            static::CITY,
            static::TYPE,
            Enums\SearchMode::SHORT()
        );

        $this->assertEquals(
            static::TOKEN,
            $this->fakeSearchQuery->getToken()
        );
        $this->assertEquals(
            static::INPUT,
            $this->fakeSearchQuery->getInput()
        );
        $this->assertEquals(
            Enums\SearchMode::SHORT(),
            $this->fakeSearchQuery->getMode()
        );
        $this->assertEquals(
            Enums\SearchLanguage::UK(),
            $this->fakeSearchQuery->getLanguage()
        );
        $this->assertEquals(
            static::TYPE,
            $this->fakeSearchQuery->getType()
        );
        $this->assertEquals(
            static::CITY,
            $this->fakeSearchQuery->getCity()
        );
    }
}
